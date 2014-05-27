<?php

/**
 * Description of CMigratorMigrationToJoomla
 * 
 * This is basic class for creating any migration class for import to Joomla! Content.
 * 
 * @since version 0.3
 * @abstract
 * @version 0.1
 * @Copyright (C) 2010 - Daniel Dimitrov - http://compojoom.com
 * @All rights reserved
 * @license under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
 *
 * @author Petar Tuovic , email : petar@compojoom.com
 */

defined('_JEXEC') or die('Restricted access');

require_once JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_cmigrator' . DS . 'library' . DS . 'migration.php';

abstract class CMigratorMigrationToJoomla extends CMigratorMigration {

    public function __construct($id) {
        //  create connection to the cms database
        parent::__construct($id);
    }

    public function migrateCategories($start, $limit) {
        // must be implemented
        JError::raiseError('003', 'Function "migrateCategories()" not implemented!');
    }

    public function migrateContent($start, $limit) {
        // must be implemented
        JError::raiseError('003', 'Function "migrateContent()" not implemented!');
    }

    public function migrateUsers($start, $limit) {
        // must be implemented
        JError::raiseError('003', 'Function "migrateUsers()" not implemented!');
    }

    public function migrateTags($start, $limit) {
        // must be implemented
        JError::raiseError('003', 'Function "migrateTags()" not implemented!');
    }
    
    public function migrateComments($start, $limit) {
        // must be implemented
        JError::raiseError('003', 'Function "migrateComments()" not implemented!');
    }

    public function tablesExist() {
        // must be implemented
        JError::raiseError('003', 'Function "tablesExist()" not implemented!');
    }
    
    public function getNumberOfContents() {
        // must be implemented
        JError::raiseError('003', 'Function "getNumberOfContents()" not implemented!');
    }
    
    public function getNumberOfCategories() {
        // must be implemented
        JError::raiseError('003', 'Function "getNumberOfCategories()" not implemented!');
    }
    
    public function getNumberOfTags() {
        // must be implemented
        JError::raiseError('003', 'Function "getNumberOfTags()" not implemented!');
    }
    
    public function getNumberOfUsers() {
        // must be implemented
        JError::raiseError('003', 'Function "getNumberOfUsers()" not implemented!');
    }
    
    public function getNumberOfComments() {
        // must be implemented
        JError::raiseError('003', 'Function "getNumberOfComments()" not implemented!');
    }

    public function deleteContent() {
        $query = $this->db->getQuery(true);
        $query->delete();
        $query->from('#__content');
        $this->db->setQuery($query);
        $ret = $this->db->query();
        
        $query = $this->db->getQuery(true);
        $query->delete();
        $query->from('#__cmigrator_articles');
        $this->db->setQuery($query);
        $ret = $ret && $this->db->query();
        
        $query = $this->db->getQuery(true);
        $query->delete();
        $query->from('#__assets');
        $query->where('`name` LIKE "com_content.article.%"');
        $this->db->setQuery($query);
        $ret = $ret && $this->db->query();
        
        return $ret;
    }

    public function deleteCategories() {
        $query = $this->db->getQuery(true);
        $query->delete();
        $query->from('#__categories');
        $query->where('extension="com_content"');
        $this->db->setQuery($query);
        $ret = $this->db->query();
        
        $query = $this->db->getQuery(true);
        $query->delete();
        $query->from('#__assets');
        $query->where('`name` LIKE "com_content.category.%"');
        $this->db->setQuery($query);
        $ret = $ret && $this->db->query();
        
         return $ret;
    }
    
    public function deleteTags() {
        // since content is deleted, tags are deleted also, so we just return true
        return true;
    }

    public function insertCategory($category) {
        $ret = -1;
        
        $query = $this->db->getQuery(true);
        $query->insert('#__categories');
        $query->set('`title`=' . $this->db->quote($category->name));
        $query->set('`alias`=' . $this->db->quote($category->alias));
        $query->set('`parent_id`=' . $this->db->quote($category->parent));
        $query->set('`extension`="com_content"');
        $query->set('`published`=1');
        $query->set('`created_user_id`=' . $this->db->quote($category->author));
        $query->set('`access`=1');
        $query->set('`language`="*"');
        $this->db->setQuery($query);
        
        if($this->db->query()) {
            $ret = $this->db->insertId();
        } else { 
            $ret = -1;
            JError::raiseWarning('004', $this->db->getErrorMsg());
        }
        
        return $ret;
    }

    public function insertArticle($article) {
        $table = JTable::getInstance('Content', 'JTable');
        if (!$table->bind($article)) {
            return false;
        }
        
        $rules = '{"core.delete":[],"core.edit":[],"core.edit.state":[]}';
        $table->setRules($rules);

        if (!$table->store()) {
            return false;
        }

        $joomla_id = $table->id;

        $query = $this->db->getQuery(true);
        $query->insert('`#__cmigrator_articles`');
        $query->set('`cms_id`=' . $article['cms_id']);
        $query->set('`joomla_id`=' . $joomla_id);
        $this->db->setQuery($query);
        $this->db->query();

        return $joomla_id;
    }

    public function insertTag($tag) {
        $query = $this->db->getQuery(true);
        $query->update('#__content');
        $query->set('`metakey`=' . $this->db->quote($tag['name']));
        $query->where('`id`=' . $tag['id']);
        $this->db->setQuery($query);
        $this->db->query();
    }
    
    public function insertTags($tags) {
        foreach ($tags as $tag) {
            $this->insertTag($tag);
        }
    }
    
    public function insertComments($comments) {
        foreach ($comments as $comment) {
            if(!$this->insertComment($comment)) {
                return false;
            }
        }
        return true;
    }
    
    public function getPostTags() {
        $query = $this->db->getQuery(true);
        $query->select('*');
        $query->from('#__cmigrator_articles');
        $this->db->setQuery($query);
        return $this->db->loadObjectList();
    }
    
    public function noCategories() {
        $query = $this->db->getQuery(true);
        $query->delete();
        $query->from('#__categories');
        $query->where('`extension`="com_content"');
        $this->db->setQuery($query);
        $rows = $this->db->query();
        
        $user = JFactory::getUser();
        $category = new JObject();
        $category->name = "Import data";
        $cat_temp = $this->checkCategoryExists($category->name);
        if($cat_temp) {
            $category->name = $cat_temp;
        }
        $category->alias = JFilterOutput::stringURLSafe($category->name);
        $category->parent = 1;
        $category->author = $user->id;
        $id = $this->insertCategory($category);
        
        $query = $this->db->getQuery(true);
        $query->insert('#__cmigrator_categories');
        $query->values($this->db->quote($id) . ',' . $this->db->quote($id) . ',' . $category->parent . ', "no-categories"');
        $this->db->setQuery($query);
        $this->db->query();
        
        $query = $this->db->getQuery(true);
        $query->select('*');
        $query->from('#__categories');
        $query->where('`extension`="com_content"');
        $this->db->setQuery($query);
        $rows = $this->db->loadAssocList();

        foreach ($rows as $row) {
            $table = JTable::getInstance('Category', 'JTable');

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
        $table = JTable::getInstance('Category', 'JTable');
        $table->rebuild();
        return $id;
    }
    
    public function checkArticleExists($name, &$counter = 0) {
        $query = $this->db->getQuery(true);
        $query->select('*');
        $query->from('#__content');
        if($counter != 0) {
            $query->where('`title`='.$this->db->quote($name.' (CMigrator copy '.$counter.')'));
        } else {
            $query->where('`title`='.$this->db->quote($name));
        }
        $this->db->setQuery($query);
        $result = $this->db->loadObject();
        
        if($result) {
            $counter++;
            $this->checkArticleExists($name, $counter);
            return $name.' (CMigrator copy '. $counter.')';
        } else {
            return null;
        }
    }
    
    public function checkCategoryExists($name, &$counter = 0) {
        $query = $this->db->getQuery(true);
        $query->select('*');
        $query->from('#__categories');
        $query->where('`extension`="com_content"');
        if($counter != 0) {
            $query->where('`title`='.$this->db->quote($name.' (CMigrator copy '.$counter.')'));
        } else {
            $query->where('`title`='.$this->db->quote($name));
        }
        $this->db->setQuery($query);
        $result = $this->db->loadObject();
        
        if($result) {
            $counter++;
            $this->checkCategoryExists($name, $counter);
            return $name.' (CMigrator copy '. $counter.')';
        } else {
            return null;
        }
    }
    
    public function checkTagExists($tag) {
        $query = $this->db->getQuery(true);
        $query->select('*');
        $query->from('#__content');
        $query->where('`id`='.$tag->article_id);
        $this->db->setQuery($query);
        $article = $this->db->loadObject();
        
        if(stristr($article->metakey,$tag->name) === false) {
            return false;
        }
        return true;
    }
}