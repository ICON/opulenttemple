<?php

/**

 * CMigrator - Adminstrator
 * @Copyright (C) 2010 - Daniel Dimitrov - http://compojoom.com
 * @All rights reserved
 * @license under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html

 * */
defined('_JEXEC') or die('Restricted access');

require_once JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_cmigrator' . DS . 'library' . DS . 'migrators' . DS . 'com_k2' . DS . 'com_k2.php';
require_once JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_cmigrator' . DS . 'library' . DS . 'helpers' . DS . 'wordpress.php';

JTable::addIncludePath(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_k2' . DS . 'tables');

class CMigratorWordpress extends CMigratorMigrationToK2 {

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
        $cat = new JObject();
        $categories = WordPressHelper::getCategories($this->db, $this->db_prefix, $start, $limit);
        $i = 0;

        foreach ($categories as $category) {
            $cat->set('name', html_entity_decode($category->name));
            if(!$this->cleanMigration) {
                $name = $this->checkCategoryExists($cat->name);
                if($name) {
                    $cat->name = html_entity_decode($name);
                }
            }
            $cat->set('alias', JFilterOutput::stringURLSafe($cat->name));
            $cat->set('description', '');
            $cat->set('parent', $category->parent);
            $id = $category->term_id;

            $insert_id = $this->insertCategory($cat);
            $relationsDb[] = $this->db->quote($insert_id) . ',' . $this->db->quote($id) . ',' . $this->db->quote($cat->parent). ',' . $this->db->quote($category->term_taxonomy_id);
            $i++;
        }
        
        // Categories for uncategorized pages and posts
        if ($start == 0) {
            $category = new JObject();
            $category->set('name', 'Uncategorized pages');
            if(!$this->cleanMigration) {
                $name = $this->checkCategoryExists($category->name);
                if($name) {
                    $category->name = html_entity_decode($name);
                }
            }
            $category->alias = JFilterOutput::stringURLSafe($category->name) . $i;
            $category->set('description', 'Uncategorized pages');
            $category->set('parent', 0);
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
            $category->set('description', 'Uncategorized posts');
            $category->set('parent', 0);
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

        if (!$no_post) {
            $no_post = WordPressHelper::getNumberOfPosts($this->db, $this->db_prefix);
        }
        if (!$no_pages) {
            $no_pages = WordPressHelper::getNumberOfPages($this->db, $this->db_prefix);
        }
        if ($start < $no_pages) {
            $ret = $this->migratePages($start, $limit) + $start;
        } elseif ($start - $no_pages < $no_post) {
            $start1 = round(($start - $no_pages) / $limit) * $limit;
            $ret = $this->migratePosts($start1, $limit) + $start;
        }
        return ($ret > $start) ? array('status' => 'OK', 'processed' => $ret) : array('status' => 'ERROR', 'processed' => $ret);
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
        $cats = null;
        $article = new JObject();
        $no_category = 0;

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
            $article->set('catid', WordPressHelper::getCategory($post, $cats, $no_category));
            $article->set('created_by', WordPressHelper::getAuthor($user_conn, $post, $user));
            $article->set('created', $post->post_date);
            $article->set('title', html_entity_decode($post->post_title));
            if(!$this->cleanMigration) {
                $name = $this->checkArticleExists($article->title);
                if($name) {
                    $article->title = html_entity_decode($name);
                }
            }
            $article->set('alias', JFilterOutput::stringURLSafe($i . ($post->post_title)));
            $article->set('introtext', $post->post_excerpt);
            $article->set('fulltext', $post->post_content);
            $article->set('id', $post->ID);

            $this->insertArticle($article);
            $i++;
        }

        return $i;
    }

    public function migrateTags($start, $limit) {
        $tags_wp = null;
        $tags = array();
        $rels = null;
        $data = array();
        $tag_obj = new JObject();
        $post_tags = $this->getArticlesList();
        $jid = null;
        $tid = null;


        $query = 'SELECT t.name AS name,tt.term_taxonomy_id as tt_id ' .
                'FROM ' . $this->db_prefix . 'terms as t ' .
                'INNER JOIN ' . $this->db_prefix . 'term_taxonomy AS tt ON t.term_id = tt.term_id ' .
                'WHERE tt.taxonomy="post_tag"';
        if ($start != -1 && $limit != -1) {
            $query .= 'LIMIT ' . $start . ',' . $limit;
        }

        $this->db->setQuery($query);

        $tags_wp = $this->db->loadObjectList();

        foreach ($tags_wp as $tag) {
            $tag_temp = $this->checkTagExists($tag);
            if($tag_temp) {
                array_push($tags, array("id" => $tag_temp->id, "ttid" => $tag->tt_id));
            } else {
                $query = $this->db->getQuery(true);
                $query->insert('#__k2_tags');
                $query->set('name=' . $this->db->quote($tag->name));
                $query->set('published=1');
                $this->db->setQuery($query);
                $this->db->query();
                array_push($tags, array("id" => $this->db->insertid(), "ttid" => $tag->tt_id));
            }
        }

        $query = $this->db->getQuery(true);
        $query->select('object_id,term_taxonomy_id');
        $query->from($this->db_prefix . 'term_relationships');
        $this->db->setQuery($query);
        $rels = $this->db->loadObjectList();

        foreach ($rels as $rel) {
            foreach ($tags as $tag) {
                if ($rel->term_taxonomy_id == $tag['ttid']) {
                    $tid = $tag['id'];
                    foreach ($post_tags as $post) {
                        if ($post->cms_id == $rel->object_id) {
                            $jid = $post->joomla_id;
                        }
                    }
                    array_push($data, array("jid" => $jid, "tid" => $tid));
                }
            }
        }

        foreach ($data as $single_tag) {
            $tag_obj->set('tagID', $single_tag['tid']);
            $tag_obj->set('itemID', $single_tag['jid']);

            $this->insertTag($tag_obj);
        }
        return ($tags_wp) ? array('status' => 'OK', 'processed' => $start + count($tags_wp)) : array('status' => 'ERROR', 'processed' => $start + count($tags_wp));
    }

    public function migrateUsers($start, $limit) {
        $result = WordPressHelper::userMigrationWordPress($this->db, $this->db_prefix, $this, $start, $limit);
        return ($result) ? array('status' => 'OK', 'processed' => $result) : array('status' => 'ERROR', 'processed' => $result);
    }
    
    public function migrateComments($start, $limit) {
        $comments = WordPressHelper::getComments($this->db, $this->db_prefix, $start, $limit);
        
        WordPressHelper::formatComments($comments, 'com_k2');
        $bool = $this->insertComments($comments);
        
        return ($comments && $bool) ? array('status' => 'OK', 'processed' => ($start + count($comments))) : array('status' => 'ERROR', 'processed' => ($start + count($comments)));
    }

    public function getNumberOfContents() {
        return (int) WordPressHelper::getNumberOfPages($this->db, $this->db_prefix) + WordPressHelper::getNumberOfPosts($this->db, $this->db_prefix);
    }

    public function getNumberOfCategories() {
        return (int) WordPressHelper::getNumberOfCategories($this->db, $this->db_prefix);
    }

    public function getNumberOfTags() {
        $query = $this->db->getQuery(true);

        $query->select('COUNT(*)');
        $query->from($this->db_prefix . 'terms as t');
        $query->innerJoin($this->db_prefix . 'term_taxonomy AS tt ON t.term_id = tt.term_id');
        $query->where("tt.taxonomy='post_tag'");
        $this->db->setQuery($query);

        return (int) $this->db->loadResult();
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
        
        $query = 'SELECT `id`,`parent` '.
                 'FROM `#__k2_categories` '.
                 'ORDER BY `id` DESC '.
                 'LIMIT 0,'.$no_cats;
        $this->db->setQuery($query);
        $rows = $this->db->loadAssocList();

        foreach ($rows as $row) {
            if ($row['parent'] != 0) {
                $orig = $relations[$row['parent']];
                $row['parent'] = $orig;
            }
            $table = JTable::getInstance('k2category', 'Table');

            // Bind the data.
            if (!$table->bind($row)) {
                throw new Exception($table->getError());
                return false;
            }

            // Store the data.
            if (!$table->store()) {
                throw new Exception($table->getError());
                return false;
            }
        }
        
        return true;
    }

}
