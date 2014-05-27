<?php

/**
 * Description of CMigratorMigration
 * 
 * This is basic class for creating any migration class.
 * Following methods must be implemented!
 * 
 * @since version 0.3
 * @abstract
 * @version 0.2
 * @Copyright (C) 2010 - Daniel Dimitrov - http://compojoom.com
 * @All rights reserved
 * @license under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
 *
 * @author Petar Tuovic, email : petar@compojoom.com
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

abstract class CMigratorMigration extends JObject
{
    public $db_prefix = null;
    public $db = null;
    public $cleanMigration;
    
    public function __construct($id) {
        //  create connection to the cms database
        $this->settings = CMigrateHelper::getSettings($id);
        $this->db_prefix = $this->settings->get('prefix');
        $this->db = JFactory::getDBO();
        $this->cleanMigration = $this->settings->get('migration') ? true : false;
    }
    
    public abstract function tablesExist();
    public abstract function migrateCategories($start, $limit);
    public abstract function migrateContent($start, $limit);
    public abstract function migrateUsers($start, $limit);
    public abstract function migrateTags($start, $limit);
    public abstract function migrateComments($start, $limit);
    public abstract function getNumberOfContents();
    public abstract function getNumberOfCategories();
    public abstract function getNumberOfUsers();
    public abstract function getNumberOfTags();
    public abstract function getNumberOfComments();
    public abstract function deleteContent();
    public abstract function deleteCategories();
    public abstract function deleteTags();
    public abstract function noCategories();
    public abstract function rebuildCategories($no_cats);
    public abstract function checkArticleExists($name);
    public abstract function checkCategoryExists($name);
    public abstract function checkTagExists($tag);
    
    public function checkUserExists($username) {
        $user = null;
        $user_id = JUserHelper::getUserId($username);
        if($user_id) {
            $user = JUser::getInstance($user_id);
        }
        return $user;
    }
    
    public function deleteUsers() {
        $user = JFactory::getUser();
        
        $query = $this->db->getQuery(true);
        $query->delete('`#__users`');
        $query->where('`id`!='.$user->id);
        $this->db->setQuery($query);
        $this->db->query();
        
        $query = $this->db->getQuery(true);
        $query->delete('`#__user_usergroup_map`');
        $query->where('`user_id`!='.$user->id);
        $this->db->setQuery($query);
        $this->db->query();
    }
    
    public function deleteComments() {
        $query = $this->db->getQuery(true);
        $query->delete('`#__comment`');
        $this->db->setQuery($query);
        return $this->db->query();
    }
    
    public function deleteCMigratorData() {
        $query = $this->db->getQuery(true);
        $query->delete('#__cmigrator_articles');
        $this->db->setQuery($query);
        $this->db->query();
        
        $query = $this->db->getQuery(true);
        $query->delete('`#__cmigrator_users`');
        $this->db->setQuery($query);
        $this->db->query();
        
        $query = $this->db->getQuery(true);
        $query->delete('#__cmigrator_categories');
        $this->db->setQuery($query);
        $this->db->query();
        
        $query = $this->db->getQuery(true);
        $query->delete('#__cmigrator_comments');
        $this->db->setQuery($query);
        $this->db->query();
    }
    
    public function getArticlesList() {
        $query = $this->db->getQuery(true);
        $query->select('*');
        $query->from('#__cmigrator_articles');
        $this->db->setQuery($query);
        return $this->db->loadObjectList();
    }
    
    public function getUsersList() {
        $query = $this->db->getQuery(true);
        $query->select('*');
        $query->from('#__cmigrator_users');
        $this->db->setQuery($query);
        return $this->db->loadObjectList();
    }
    
    public function getCategoriesList() {
        $query = $this->db->getQuery(true);
        $query->select('*');
        $query->from('#__cmigrator_categories');
        $this->db->setQuery($query);
        return $this->db->loadObjectList();
    }
    
    public function getCommentsList() {
        $query = $this->db->getQuery(true);
        $query->select('*');
        $query->from('#__cmigrator_comments');
        $this->db->setQuery($query);
        return $this->db->loadObjectList();
    }
    
    public function insertComment($comment) {
        $contentlist = $this->getArticlesList();
        $userlist = $this->getUsersList();
        $users = array();
        $content = array();
        
        foreach ($userlist as $user) {
            $users[$user->cms_id] = $user->joomla_id;
        }
        foreach ($contentlist as $article) {
            $content[$article->cms_id] = $article->joomla_id;
        }
        
        $query = $this->db->getQuery(true);
        
        $query->insert('`#__comment`');
        $query->set('`contentid`=' . $this->db->quote($content[$comment->contentid]));        
        $query->set('`component`=' . $this->db->quote($comment->component));
        $query->set('`ip`=' . $this->db->quote($comment->ip));
        if(isset($comment->userid) && $comment->userid != 0) {
            if (isset($userlist[$comment->userid])) {
                $user = JUser::getInstance($users[$comment->userid]);
                if ($user) {
                    $query->set('`userid`=' . $this->db->quote($user->id));
                    $query->set('`usertype`=' . $this->db->quote($user->usertype));
                }
            }
        }
        $query->set('`date`=' . $this->db->quote($comment->date));
        $query->set('`name`=' . $this->db->quote($comment->name));
        $query->set('`email`=' . $this->db->quote($comment->email));
        if(isset($comment->karma)) {
            if($comment->karma > 0) {
                $query->set('`voting_yes`=' . $comment->karma);
            } else if ($comment->karma < 0) {
                $query->set('`voting_no`=' . $comment->karma);
            }
        } else if (isset($comment->voteyes) && isset($comment->voteno)) {
            $query->set('`voting_yes`=' . $comment->voteyes);
            $query->set('`voting_no`=' . $comment->voteno);
        }
        $query->set('`website`=' . $this->db->quote($comment->website));
        $query->set('`comment`=' . $this->db->quote($comment->comment));
        $query->set('`published`=1');
        if($comment->parent) {
            $query->set('`parentid`=' . $this->db->quote($comment->parent));
        }
        
        $this->db->setQuery($query);
        $result = $this->db->query();
        $insert_id = $this->db->insertId();
        
        $query = $this->db->getQuery(true);
        
        $query->insert('`#__cmigrator_comments`');
        $query->set('`joomla_id`=' . $this->db->quote($insert_id));
        $query->set('`cms_id`=' . $this->db->quote($comment->id));
        
        $this->db->setQuery($query);
        $this->db->query();
        
        return $result;
    }
    
    public function rebuildComments($no_comments) {
        $comments_import = $this->getCommentsList();
        $comments_array = array();
        
        $query = "SELECT * " .
                 "FROM `#__comment` " .
                 "ORDER BY id DESC " .
                 "LIMIT 0," . $no_comments;
        
        $this->db->setQuery($query);
        $comments = $this->db->loadAssocList();
        
        foreach ($comments_import as $comment) {
            $comments_array[$comment->cms_id] = $comment->joomla_id;
        }
        
        foreach ($comments as $comment) {
            if ($comment['parentid'] != 0 && $comment['parentid'] != -1) {
                $comment['parentid'] = $comments_array[$comment['parentid']];
            } else {
                $comment['parentid'] = -1;
            }
            $query = $this->db->getQuery(true);
            $query->update('`#__comment`');
            $query->set('`parentid`=' . $comment['parentid']);
            $query->where('`id`=' . $comment['id']);
            $this->db->setQuery($query);
            $this->db->query();
        }
    }
}