<?php

/**

 * CMigrator - Adminstrator
 * @Copyright (C) 2010 - Daniel Dimitrov - http://compojoom.com
 * @All rights reserved
 * @license under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html

 * */
defined('_JEXEC') or die('Restricted access');

require_once JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_cmigrator' . DS . 'library' . DS . 'migrators' . DS . 'com_k2' . DS . 'com_k2.php';
require_once JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_cmigrator' . DS . 'library' . DS . 'helpers' . DS . 'drupal.php';

class CMigratorDrupal extends CMigratorMigrationToK2 {

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

        $categories = DrupalHelper::getCategories($this->db, $this->db_prefix, $start, $limit);
        $cat = new JObject();
        $i = 0;
        $relationsDb = array();

        foreach ($categories as $category) {
            $cat->set('name', html_entity_decode($category->name));
            if(!$this->cleanMigration) {
                $name = $this->checkCategoryExists($cat->name);
                if($name) {
                    $cat->name = html_entity_decode($name);
                }
            }
            $cat->set('alias', JFilterOutput::stringURLSafe($i . $cat->name));
            $cat->set('description', $category->description);
            $cat->set('parent', 0);

            $insert_id = $this->insertCategory($cat);

            $relationsDb[] = $this->db->quote($insert_id) . ',' . $i . ',' . $cat->parent . ',' . $this->db->quote($category->type);
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

        return ($ret) ? array('status' => 'OK', 'processed' => ($ret + $start)) : array('status' => 'ERROR', 'processed' => ($start + $ret));
    }

    private function processContent($nodes) {
        $user = JFactory::getUser();
        $config = JFactory::getConfig();
        $article = new JObject();
        $i = 0;
        $content_types = null;
        $no_category = 0;

        $query = $this->db->getQuery(true);
        $query->select('joomla_id, help_text');
        $query->from('#__cmigrator_categories');
        $this->db->setQuery($query);
        $content_types = $this->db->loadObjectList();
        $user_conn = $this->getUsersList();
        
        if(empty($content_types)) {
            $no_category = $this->noCategories();
        }

        foreach ($nodes as $node) {
            $time = new JDate($node->created, $config->getValue('config.offset_user'));
            $created = $time->format('Y-m-d H:i:s', false, false);

            $article->set('catid', DrupalHelper::getCategory($content_types, $node, $no_category));
            $article->set('created_by', DrupalHelper::getAuthor($user_conn, $node, $user));
            $article->set('created', $created);
            $article->set('title', html_entity_decode($node->title));
            if(!$this->cleanMigration) {
                $name = $this->checkArticleExists($article->title);
                if($name) {
                    $article->title = html_entity_decode($name);
                }
            }
            $article->set('alias', JFilterOutput::stringURLSafe($i . $node->title));
            $article->set('introtext', $node->summary);
            $article->set('fulltext', $node->body);

            $this->insertArticle($article);
            $i++;
        }

        return $i;
    }

    public function migrateTags($start, $limit) {
        $tags = array();
        $tag_obj = new JObject();
        $post_tags = $this->getArticlesList();

        $query = 'SELECT t.name AS name ' .
                'FROM ' . $this->db_prefix . 'taxonomy_term_data as t ';
        if ($start != -1 && $limit != -1) {
            $query .= 'LIMIT ' . $start . ',' . $limit;
        }

        $this->db->setQuery($query);

        $tags_all = $this->db->loadObjectList();

        foreach ($tags_all as $tag) {
            $tag_temp = $this->checkTagExists($tag);
            if($tag_temp) {
                array_push($tags, array("id" => $tag_temp->id, "name" => $tag->name));
            } else {
                $query = $this->db->getQuery(true);
                $query->insert('#__k2_tags');
                $query->set('name=' . $this->db->quote($tag->name));
                $query->set('published=1');
                $this->db->setQuery($query);
                $this->db->query();
                array_push($tags, array("id" => $this->db->insertid(), "name" => $tag->name));
            }
        }

        $query = $this->db->getQuery(true);
        $query->select('t.nid AS nid,t1.name AS name');
        $query->from($this->db_prefix . "taxonomy_index as t");
        $query->innerJoin($this->db_prefix . "taxonomy_term_data AS t1 ON t1.tid = t.tid");

        $this->db->setQuery($query);
        $tags_conn = $this->db->loadObjectList();

        foreach ($tags_conn as $tag) {
            foreach ($post_tags as $post_tag) {
                if ($post_tag->cms_id == $tag->nid) {
                    $itemid = $post_tag->joomla_id;
                    break;
                }
            }
            foreach ($tags as $tag1) {
                if ($tag1["name"] == $tag->name) {
                    $tagid = $tag1["id"];
                    break;
                }
            }

            $tag_obj->set('tagID', $tagid);
            $tag_obj->set('itemID', $itemid);

            $this->insertTag($tag_obj);
        }

        return true;
    }

    public function migrateUsers($start, $limit) {
        $result = DrupalHelper::userMigrationDrupal($this->db, $this->db_prefix, $this, $start, $limit);
        return ($result) ? array('status' => 'OK', 'processed' => $result) : array('status' => 'ERROR', 'processed' => $result);
    }

    public function getNumberOfContents() {
        return (int) DrupalHelper::getNumberOfNodes($this->db, $this->db_prefix);
    }

    public function getNumberOfCategories() {
        return (int) DrupalHelper::getNumberOfCategories($this->db, $this->db_prefix);
    }

    public function getNumberOfTags() {
        $query = $this->db->getQuery(true);

        $query->select("COUNT(*)");
        $query->from($this->db_prefix . "taxonomy_term_data as t");
        $this->db->setQuery($query);

        return (int) $this->db->loadResult();
    }

    public function getNumberOfUsers() {
        return (int) DrupalHelper::getNumberOfUsers($this->db, $this->db_prefix);
    }
    
    public function rebuildCategories($no_cats) {
        // nothing to rebuild since Drupal dont support nested categories by defualt
        
        return true;
    }

}