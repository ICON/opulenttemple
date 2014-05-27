<?php
/**

 * CMigrator - Adminstrator
 * @Copyright (C) 2010 - Daniel Dimitrov - http://compojoom.com
 * @All rights reserved
 * @license under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html

**/

defined('_JEXEC') or die();

require_once(JPATH_COMPONENT_ADMINISTRATOR . '/library/migrator.php');

class CMigratorControllerMigrate extends JController
{
    public function display($cachable = false, $urlparams = false) {
        parent::display($cachable, $urlparams);
    }
    
    /*
    * function start()
    * Description: Creates migrator for migration and returns number of articles, categories, users and tags for migration
    * Return: JSON array
    */
    
    public function start() {
        $post = JFactory::getApplication()->input;        
        $config = $post->get('config');
        $settings = CMigrateHelper::getSettings($config);
        ini_set('max_execution_time', '3600');
        $cmigrator = new CMigratorMigrator($settings->get('cms',''),$settings->get('content_selected',''),$config);
        $response = array();
        $cmigrator->deleteCMigratorData();
                
        if (!$cmigrator->tablesExist()) {
            $response += array('tablesExist' => 1);
        } else {
            $response += array('tablesExist' => 0);
        }

        if ($settings->get('users', 0)) {
            $response += array('users' => $cmigrator->getNumberOfUsers());
        } else {
            $response += array('users' => -1);
        }

        if ($settings->get('categories', 0)) {
            $response += array('categories' => $cmigrator->getNumberOfCategories());
        } else {
            $response += array('categories' => -1);
        }

        if ($settings->get('content', 0)) {
            $response += array('content' => $cmigrator->getNumberOfContents());
        } else {
            $response += array('content' => -1);
        }

        if ($settings->get('tags', 0)) {
            $response += array('tags' => $cmigrator->getNumberOfTags());
        } else {
            $response += array('tags' => -1);
        }
        
        if ($settings->get('comments', 0)) {
            $response += array('comments' => $cmigrator->getNumberOfComments());
        } else {
            $response += array('comments' => -1);
        }
                
        // Get the document object.
        $document = JFactory::getDocument();

        // Set the MIME type for JSON output.
        $document->setMimeEncoding('application/json');

        // Change the suggested filename.
        JResponse::setHeader('Content-Disposition', 'attachment;filename=element' . '.json"');

        echo json_encode($response);

        // stop joomla from further processing
        jexit(0);
    }
    
    /*
    * function articles()
    * Description: Migrates articles from selected CMS and returns status 'OK' on success and number of processed articles
    * Return: JSON array
    */ 
    public function content() {
        $jsdata = JFactory::getApplication()->input;
        $config = $jsdata->get('config');
       
        $settings = CMigrateHelper::getSettings($config);
        ini_set('max_execution_time', '3600');
        $cmigrator = new CMigratorMigrator($settings->get('cms',''),$settings->get('content_selected',''),$config);
        $start = $jsdata->get('start');
        $limit = $jsdata->get('limit');
        
        if($settings->get('migration') == 1) {
            $bool = true;
        } else {
            $bool = false;
        }
        
        if($start == 0 && $bool) {
            $cmigrator->deleteContent();
        }
        
        $result = $cmigrator->migrateContent($start, $limit);
        
        $response = array(
            'status' => $result['status'],
            'processed' => $result['processed']
        );
        
        // Get the document object.
        $document = JFactory::getDocument();

        // Set the MIME type for JSON output.
        $document->setMimeEncoding('application/json');

        // Change the suggested filename.
        JResponse::setHeader('Content-Disposition', 'attachment;filename=element' . '.json"');

        echo json_encode($response);

        // stop joomla from further processing
        jexit(0);
    }
    
    /*
    * function categories()
    * Description: Migrates categories from selected CMS and returns status 'OK' on success and number of processed categories
    * Return: JSON array
    */ 
    public function categories() {
        $jsdata = JFactory::getApplication()->input;
        $config = $jsdata->get('config');
        
        $settings = CMigrateHelper::getSettings($config);
        ini_set('max_execution_time', '3600');
        $cmigrator = new CMigratorMigrator($settings->get('cms',''),$settings->get('content_selected',''),$config);
        $start = $jsdata->get('start');
        $limit = $jsdata->get('limit');
        $total = $jsdata->get('total');
        
        if($settings->get('migration') == 1) {
            $bool = true;
        } else {
            $bool = false;
        }
        
        if($start == 0 && $bool) {
            $cmigrator->deleteCategories();
        }
        
        $result = $cmigrator->migrateCategories($start, $limit);
        
        if($result['processed'] == $total) {
            $cmigrator->rebuildCategories($cmigrator->getNumberOfCategories());
        }
        
        $response = array(
            'status' => $result['status'],
            'processed' => $result['processed']
        );
        
        // Get the document object.
        $document = JFactory::getDocument();

        // Set the MIME type for JSON output.
        $document->setMimeEncoding('application/json');

        // Change the suggested filename.
        JResponse::setHeader('Content-Disposition', 'attachment;filename=element' . '.json"');

        echo json_encode($response);

        // stop joomla from further processing
        jexit(0);
    }
    
    /*
    * function users()
    * Description: Migrates users from selected CMS and returns status 'OK' on success and number of processed users
    * Return: JSON array
    */ 
    public function users() {
        $jsdata = JFactory::getApplication()->input;
        $config = $jsdata->get('config');
        $settings = CMigrateHelper::getSettings($config);
        ini_set('max_execution_time', '3600');
        $cmigrator = new CMigratorMigrator($settings->get('cms',''),$settings->get('content_selected',''),$config);
        $start = $jsdata->get('start');
        $limit = $jsdata->get('limit');
        
        if($settings->get('migration') == 1) {
            $bool = true;
        } else {
            $bool = false;
        }
        
        if($start == 0 && $bool) {
            $cmigrator->deleteUsers();
        }
        
        $result = $cmigrator->migrateUsers($start, $limit);
        
        $response = array(
            'status' => $result['status'],
            'processed' => $result['processed']
        );
        
        // Get the document object.
        $document = JFactory::getDocument();

        // Set the MIME type for JSON output.
        $document->setMimeEncoding('application/json');

        // Change the suggested filename.
        JResponse::setHeader('Content-Disposition', 'attachment;filename=element' . '.json"');

        echo json_encode($response);

        // stop joomla from further processing
        jexit(0);
    }
    
    /*
    * function tags()
    * Description: Migrates tags from selected CMS and returns status 'OK' on success and number of processed tags
    * Return: JSON array
    */ 
    public function tags() {
        $jsdata = JFactory::getApplication()->input;
        $config = $jsdata->get('config');
        
        $settings = CMigrateHelper::getSettings($config);
        ini_set('max_execution_time', '3600');
        $cmigrator = new CMigratorMigrator($settings->get('cms',''),$settings->get('content_selected',''),$config);
        $start = $jsdata->get('start');
        $limit = $jsdata->get('limit');
        
        if($settings->get('migration') == 1) {
            $bool = true;
        } else {
            $bool = false;
        }
        
        if($start == 0 && $bool) {
            $cmigrator->deleteTags();
        }
        
        $result = $cmigrator->migrateTags($start, $limit);
        
        $response = array(
            'status' => $result['status'],
            'processed' => $result['processed']
        );
        
        // Get the document object.
        $document = JFactory::getDocument();

        // Set the MIME type for JSON output.
        $document->setMimeEncoding('application/json');

        // Change the suggested filename.
        JResponse::setHeader('Content-Disposition', 'attachment;filename=element' . '.json"');

        echo json_encode($response);

        // stop joomla from further processing
        jexit(0);
    }
    
    public function comments() {
        $jsdata = JFactory::getApplication()->input;
        $config = $jsdata->get('config');
        
        $settings = CMigrateHelper::getSettings($config);
        ini_set('max_execution_time', '3600');
        $cmigrator = new CMigratorMigrator($settings->get('cms',''),$settings->get('content_selected',''),$config);
        $start = $jsdata->get('start');
        $limit = $jsdata->get('limit');
        $total = $jsdata->get('total');
        
        if($settings->get('migration') == 1) {
            $bool = true;
        } else {
            $bool = false;
        }
        
        if($start == 0 && $bool) {
            $cmigrator->deleteComments();
        }
        
        $result = $cmigrator->migrateComments($start, $limit);
        
        if($result['processed'] == $total) {
            $cmigrator->rebuildComments($cmigrator->getNumberOfComments());
        }
        
        $response = array(
            'status' => $result['status'],
            'processed' => $result['processed']
        );
        
        // Get the document object.
        $document = JFactory::getDocument();

        // Set the MIME type for JSON output.
        $document->setMimeEncoding('application/json');

        // Change the suggested filename.
        JResponse::setHeader('Content-Disposition', 'attachment;filename=element' . '.json"');

        echo json_encode($response);

        // stop joomla from further processing
        jexit(0);
    }
    
}