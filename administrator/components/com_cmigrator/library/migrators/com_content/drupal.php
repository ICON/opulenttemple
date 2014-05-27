<?php

/**

 * CMigrator - Adminstrator
 * @Copyright (C) 2010 - Daniel Dimitrov - http://compojoom.com
 * @All rights reserved
 * @license under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html

 * */
defined('_JEXEC') or die('Restricted access');

require_once JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_cmigrator' . DS . 'library' . DS . 'migrators' . DS . 'com_content' . DS . 'com_content.php';
require_once JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_cmigrator' . DS . 'library' . DS . 'helpers' . DS . 'drupal.php';

class CMigratorDrupal extends CMigratorMigrationToJoomla {

    public function __construct($id) {
        //  create connection to the cms database
        parent::__construct($id);
    }

    /**
     * Check if the CMS tables a in the database
     * @return bool
     */
    public function tablesExist() {
        return DrupalHelper::checkTableExist($this->db, $this->db_prefix);
    }

    public function migrateCategories($start, $limit) {

        $insert_id = null;
        $relationsDb = array();
        $categories = DrupalHelper::getCategories($this->db, $this->db_prefix, $start, $limit);
        $user = JFactory::getUser();
        $i = 0;
        $table = JTable::getInstance('Category', 'JTable');
        $dispatcher = JDispatcher::getInstance();
        JPluginHelper::importPlugin('content');

        foreach ($categories as $category) {
            $isNew = true;
            $category->name = html_entity_decode($category->name);
            if(!$this->cleanMigration) {
                $name = $this->checkCategoryExists($category->name);
                if($name) {
                    $category->name = html_entity_decode($name);
                }
            }
            $category->alias = JFilterOutput::stringURLSafe($i . $category->name);
            $category->parent = 1;
            $category->author = $user->id;
            
            $insert_id = $this->insertCategory($category);
            if($insert_id == -1) break;
            
            $dispatcher->trigger('onContentAfterSave', array('com_content.category.'.$insert_id, &$table, $isNew));

            $relationsDb[] = $this->db->quote($insert_id) . ',' . $i . ',' . $category->parent . ',' . $this->db->quote($category->type);
            $i++;
        }

        $query = $this->db->getQuery(true);
        $query->insert('#__cmigrator_categories');
        $query->values($relationsDb);
        $this->db->setQuery($query);
        $this->db->query();

        $ret = $start + $i;

        return ($ret > $start) ? array('status' => 'OK', 'processed' => $ret) : array('status' => 'ERROR', 'processed' => $ret);
    }

    public function migrateContent($start, $limit) {
        $nodes = DrupalHelper::getNodes($this->db, $this->db_prefix, $start, $limit);

        $ret = $this->processContent($nodes);

        return ($ret) ? array('status' => 'OK', 'processed' => ($start + $ret)) : array('status' => 'ERROR', 'processed' => ($start + $ret));
    }

    private function processContent($nodes) {
        date_default_timezone_set('UTC');
        $user = JFactory::getUser();
        $config = JFactory::getConfig();
        $article = array();
        $i = 0;
        $user_conn = $this->getUsersList();
        $content_types = null;
        $no_category = 0;
        $table = JTable::getInstance('Content', 'JTable');
        $dispatcher = JDispatcher::getInstance();
        JPluginHelper::importPlugin('content');

        $query = $this->db->getQuery(true);
        $query->select('joomla_id, help_text');
        $query->from('#__cmigrator_categories');
        $this->db->setQuery($query);
        $content_types = $this->db->loadObjectList();
        
        if(empty($content_types)) {
            $no_category = $this->noCategories();
        }

        foreach ($nodes as $node) {
            $isNew = true;
            $time = new JDate($node->created, $config->getValue('config.offset_user'));
            $created = $time->format('Y-m-d H:i:s', false, false);

            $article['catid'] = DrupalHelper::getCategory($content_types, $node, $no_category);
            $article['created_by'] = DrupalHelper::getAuthor($user_conn, $node, $user);
            $article['created'] = $created;
            $article['title'] = html_entity_decode($node->title);
            if(!$this->cleanMigration) {
                $name = $this->checkArticleExists($article['title']);
                if($name) {
                    $article['title'] = html_entity_decode($name);
                }
            }
            $article['alias'] = JFilterOutput::stringURLSafe($i . $node->title);
            $article['introtext'] = $node->summary;
            $article['fulltext'] = $node->body;
            $article['cms_id'] = $node->id;
            $article['publish_up'] = $created;
            $article['state'] = 1;
            $article['access'] = 1;
            $article['language'] = "*";

            $insert_id = $this->insertArticle($article);
            $dispatcher->trigger('onContentAfterSave', array('com_content.article.'.$insert_id, &$table, $isNew));
            $i++;
        }

        return $i;
    }

    public function migrateUsers($start, $limit) {
        $result = DrupalHelper::userMigrationDrupal($this->db, $this->db_prefix, $this, $start, $limit);
        return ($result) ? array('status' => 'OK', 'processed' => $result) : array('status' => 'ERROR', 'processed' => $result);
    }

    public function migrateTags($start, $limit) {
        $tags = array();
        $node_tags = $this->getArticlesList();

        $query = 'SELECT t.nid AS nid,t1.name AS name ' .
                'FROM ' . $this->db_prefix . 'taxonomy_index as t ' .
                'INNER JOIN ' . $this->db_prefix . 'taxonomy_term_data AS t1 ON t1.tid = t.tid ';
        if ($start != -1 && $limit != -1) {
            $query .= 'LIMIT ' . $start . ',' . $limit;
        }

        $this->db->setQuery($query);

        $tags_conn = $this->db->loadObjectList();

        foreach ($tags_conn as $tag) {
            foreach ($node_tags as $node_tag) {
                if ($node_tag->cms_id == $tag->nid) {
                    $itemid = $node_tag->joomla_id;
                    if (isset($tags[$itemid])) {
                        $tag->article_id = $itemid;
                        if(!$this->checkTagExists($tag)) {
                            $tags[$itemid]["name"] .= ', ' . $tag->name;
                        }
                    } else {
                        $tags[$itemid] = array("id" => $itemid, "name" => $tag->name);
                    }
                    break;
                }
            }
        }

        $this->insertTags($tags);

        return ($tags_conn) ? array('status' => 'OK', 'processed' => ($start + count($tags_conn))) : array('status' => 'ERROR', 'processed' => ($start + count($tags_conn)));
    }

    public function getNumberOfContents() {
        return (int) DrupalHelper::getNumberOfNodes($this->db, $this->db_prefix);
    }

    public function getNumberOfCategories() {
        return (int) DrupalHelper::getNumberOfCategories($this->db, $this->db_prefix);
    }

    public function getNumberOfTags() {
        $query = $this->db->getQuery(true);

        $query->select('COUNT(*)');
        $query->from($this->db_prefix . "taxonomy_index as t");
        $query->innerJoin($this->db_prefix . "taxonomy_term_data AS t1 ON t1.tid = t.tid");
        $this->db->setQuery($query);

        return (int) $this->db->loadResult();
    }

    public function getNumberOfUsers() {
        return (int) DrupalHelper::getNumberOfUsers($this->db, $this->db_prefix);
    }
    
    public function rebuildCategories($no_cats) {
        // just fixing nested category tree
        $query = 'SELECT * '.
                 'FROM `#__categories` '.
                 'WHERE `extension`="com_content" '.
                 'ORDER BY `id` DESC '.
                 'LIMIT 0,'.$no_cats;
        $this->db->setQuery($query);
        $rows = $this->db->loadAssocList();
        $table = JTable::getInstance('Category', 'JTable');

        foreach ($rows as $row) {
            // Bind the data.
            if (!$table->bind($row)) {
                throw new Exception($table->getError());
                return false;
            }
            
            // Setting default rules
            $rules = '{"core.create":[],"core.delete":[],"core.edit":[],"core.edit.state":[],"core.edit.own":[]}';
            $table->setRules($rules);

            // Store the data.
            if (!$table->store()) {
                throw new Exception($table->getError());
                return false;
            }
        }
        $table = JTable::getInstance('Category', 'JTable');
        $table->rebuild();
        
        return true;
    }

}