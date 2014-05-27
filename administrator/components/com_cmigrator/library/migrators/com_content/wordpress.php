<?php

/**

 * CMigrator - Adminstrator
 * @Copyright (C) 2010 - Daniel Dimitrov - http://compojoom.com
 * @All rights reserved
 * @license under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html

 **/

defined('_JEXEC') or die('Restricted access');

require_once JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_cmigrator' . DS . 'library' . DS . 'migrators' . DS . 'com_content' . DS . 'com_content.php';
require_once JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_cmigrator' . DS . 'library' . DS . 'helpers' . DS . 'wordpress.php';

class CMigratorWordpress extends CMigratorMigrationToJoomla {

    public function __construct($id) {
        //  create connection to the cms database
        parent::__construct($id);
    }

    /**
     * Check if the CMS tables a in the database
     * @return bool
     */
    public function tablesExist() {
        return WordPressHelper::checkTableExist($this->db, $this->db_prefix);
    }

    public function migrateCategories($start, $limit) {
        $relationsDb = array();
        $insert_id = null;
        $categories = WordPressHelper::getCategories($this->db, $this->db_prefix, $start, $limit);
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
            if ($category->parent == 0) {
                $category->parent = 1;
            }
            $id = $category->term_id;
            $category->alias = JFilterOutput::stringURLSafe($category->name) . $i;
            $category->author = $user->id;
            
            $insert_id = $this->insertCategory($category);
            if($insert_id == -1) break;
            
            $dispatcher->trigger('onContentAfterSave', array('com_content.category.'.$insert_id, &$table, $isNew));

            $relationsDb[] = $this->db->quote($insert_id) . ',' . $this->db->quote($id) . ',' . $category->parent . ',' . $this->db->quote($category->term_taxonomy_id);
            $i++;
        }

        // Categories for uncategorized pages and posts
        if($start == 0) {            
            $category = new JObject();
            $category->set('name', 'Uncategorized pages');
            if(!$this->cleanMigration) {
                $name = $this->checkCategoryExists($category->name);
                if($name) {
                    $category->name = html_entity_decode($name);
                }
            }
            $category->alias = JFilterOutput::stringURLSafe($category->name) . $i;
            $category->set('author', $user->id);
            $category->set('parent', 1);
            $insert_id = $this->insertCategory($category);
            $relationsDb[] = $this->db->quote($insert_id) . ',' . $this->db->quote($insert_id) . ',' . $category->parent . ',' . $this->db->quote('pages');
            
            $category->set('name', 'Uncategorized posts');
            if(!$this->cleanMigration) {
                $name = $this->checkCategoryExists($category->name);
                if($name) {
                    $category->name = html_entity_decode($name);
                }
            }
            $category->alias = JFilterOutput::stringURLSafe($category->name) . $i;
            $category->set('author', $user->id);
            $category->set('parent', 1);
            $insert_id = $this->insertCategory($category);
            $relationsDb[] = $this->db->quote($insert_id) . ',' . $this->db->quote($insert_id) . ',' . $category->parent . ',' . $this->db->quote('posts');
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
        $ret = 0;
        $no_post = 0;
        $no_pages = 0;
        
        if(!$no_post) {
            $no_post = WordPressHelper::getNumberOfPosts($this->db, $this->db_prefix);
        }
        if(!$no_pages) {
            $no_pages = WordPressHelper::getNumberOfPages($this->db, $this->db_prefix);
        }
        if($start < $no_pages) {
            $ret = $this->migratePages($start, $limit) + $start;
        } elseif($start-$no_pages < $no_post) {
            $start1 = round(($start-$no_pages) / $limit) * $limit;
            $ret = $this->migratePosts($start1, $limit) + $start;
        }
        return ($ret>$start) ? array('status' => 'OK', 'processed' => $ret) : array('status' => 'ERROR', 'processed' => $ret);
    }

    private function migratePosts($start, $limit) {
        $posts = WordPressHelper::getPosts($this->db, $this->db_prefix, $start, $limit);
        $ret = $this->processContent($posts);
        return $ret;
    }

    private function migratePages($start, $limit) {
        $pages = WordPressHelper::getPages($this->db, $this->db_prefix, $start, $limit);
        $ret = $this->processContent($pages);
        return $ret;
    }

    private function processContent($posts) {
        $user = JFactory::getUser();
        $i = 0;
        $article = array();
        $cats = null;
        $no_category = 0;
        $table = JTable::getInstance('Content', 'JTable');
        $dispatcher = JDispatcher::getInstance();
        JPluginHelper::importPlugin('content');

        $query = $this->db->getQuery(true);
        $query->select('joomla_id,imported_id,help_text');
        $query->from('#__cmigrator_categories');
        $this->db->setQuery($query);
        $cats = $this->db->loadObjectList();
        $user_conn = $this->getUsersList();
        
        if(empty($cats)) {
            $cats = -1;
            $no_category = $this->noCategories();
        } else {
            WordPressHelper::getPostsPrepare($this->db, $this->db_prefix, $posts);
        }

        foreach ($posts as $post) {
            $isNew = true;
            
            $article['catid'] = WordPressHelper::getCategory($post, $cats, $no_category);
            $article['created_by'] = WordPressHelper::getAuthor($user_conn, $post, $user);
            $article['created'] = $post->post_date;
            $article['title'] = html_entity_decode($post->post_title);
            if(!$this->cleanMigration) {
                $name = $this->checkArticleExists($article['title']);
                if($name) {
                    $article['title'] = html_entity_decode($name);
                }
            }
            $article['alias'] = JFilterOutput::stringURLSafe($i . $article['title']);
            $article['introtext'] = $post->post_excerpt;
            $article['fulltext'] = $post->post_content;
            $article['cms_id'] = $post->ID;
            $article['publish_up'] = $post->post_date;
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
        $result = WordPressHelper::userMigrationWordPress($this->db, $this->db_prefix, $this, $start, $limit);
        return ($result) ? array('status' => 'OK', 'processed' => $result) : array('status' => 'ERROR', 'processed' => $result);
    }

    public function migrateTags($start, $limit) {
        $tags = array();
        $post_tags = $this->getArticlesList();
        
        $query = 'SELECT tr.object_id as id, t.name AS name '.
                 'FROM '. $this->db_prefix. 'terms as t '.
                 'INNER JOIN '. $this->db_prefix . 'term_taxonomy AS tt ON t.term_id = tt.term_id '.
                 'INNER JOIN '. $this->db_prefix . 'term_relationships AS tr ON tt.term_taxonomy_id = tr.term_taxonomy_id '.
                 'WHERE tt.taxonomy="post_tag"';
        if($start != -1 && $limit != -1) {
            $query .= 'LIMIT '. $start . ',' . $limit;
        }
        
        $this->db->setQuery($query);
        
        $tags_wp = $this->db->loadObjectList();

        foreach ($tags_wp as $tag) {
            foreach ($post_tags as $post_tag) {
                if ($post_tag->cms_id == $tag->id) {
                    $itemid = $post_tag->joomla_id;
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

        return ($tags_wp) ? array('status' => 'OK', 'processed' => ($start + count($tags_wp))) : array('status' => 'ERROR', 'processed' => ($start + count($tags_wp)));
    }
    
    public function migrateComments($start, $limit) {
        $comments = WordPressHelper::getComments($this->db, $this->db_prefix, $start, $limit);
        
        WordPressHelper::formatComments($comments, 'com_content');
        $bool = $this->insertComments($comments);
        
        return ($comments && $bool) ? array('status' => 'OK', 'processed' => ($start + count($comments))) : array('status' => 'ERROR', 'processed' => ($start + count($comments)));
    }
    
    public function getNumberOfContents() {
        return (int)WordPressHelper::getNumberOfPages($this->db, $this->db_prefix) + WordPressHelper::getNumberOfPosts($this->db, $this->db_prefix);
    }
    
    public function getNumberOfCategories() {
        return (int)WordPressHelper::getNumberOfCategories($this->db, $this->db_prefix);
    }
    
    public function getNumberOfTags() {
        $query = $this->db->getQuery(true);
        
        $query->select('COUNT(*)');
        $query->from($this->db_prefix . 'terms as t');
        $query->innerJoin($this->db_prefix . 'term_taxonomy AS tt ON t.term_id = tt.term_id');
        $query->innerJoin($this->db_prefix . 'term_relationships AS tr ON tt.term_taxonomy_id = tr.term_taxonomy_id');
        $query->where("tt.taxonomy='post_tag'");
        $this->db->setQuery($query);
        
        return (int)$this->db->loadResult();
    }
    
    public function getNumberOfUsers() {
        return (int) WordPressHelper::getNumberOfUsers($this->db, $this->db_prefix);
    }
    
    public function getNumberOfComments() {
        return (int) WordPressHelper::getNumberOfComments($this->db, $this->db_prefix);
    }
    
    public function rebuildCategories($no_cats) {
        $relations = array();
        $relations_obj = $this->getCategoriesList();
        
        foreach($relations_obj as $obj) {
            $relations[$obj->imported_id] = $obj->joomla_id;
        }
        
        $query = 'SELECT * '.
                 'FROM `#__categories` '.
                 'WHERE `extension`="com_content" '.
                 'ORDER BY `id` DESC '.
                 'LIMIT 0,' . ($no_cats + 1);
        $this->db->setQuery($query);
        $rows = $this->db->loadAssocList();

        foreach ($rows as $row) {
            if ($row['parent_id'] != 1) {
                $orig = $relations[$row['parent_id']];
                $row['parent_id'] = $orig;
            }
            $table = JTable::getInstance('Category', 'JTable');

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