<?php
/**

 * CMigrator - Adminstrator
 * @Copyright (C) 2010 - Daniel Dimitrov - http://compojoom.com
 * @All rights reserved
 * @license under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html

**/

defined('_JEXEC') or die('Restricted access');

class CMigratorMigrator extends JObject{

    private $migrator;

    public function __construct($cms,$option,$id) {
        $name = 'CMigrator'.ucfirst($cms);
        
        require_once(JPATH_COMPONENT_ADMINISTRATOR . '/library/migrators/'.$option.'/'.$cms.'.php');

        try {
            $this->migrator = new $name($id);
        } catch(Exception $e) {
            $appl = JFactory::getApplication();
            $appl->redirect('index.php?option=com_cmigrator&view=cpanel', $e->getMessage() . JText::_('COM_CMIGRATOR_MIGRATION_ABORTED'), 'error');
        }
    }

    public function tablesExist() {
	return $this->migrator->tablesExist();
    }

    public function migrateCategories($start, $limit) {
        return $this->migrator->migrateCategories($start, $limit);
    }

    public function migrateContent($start, $limit) {
        return $this->migrator->migrateContent($start, $limit);
    }
    
    public function migrateTags($start, $limit) {
        return $this->migrator->migrateTags($start, $limit);
    }
    
    public function migrateUsers($start, $limit) {
        return $this->migrator->migrateUsers($start, $limit);
    }
    
    public function migrateComments($start , $limit) {
        return $this->migrator->migrateComments($start, $limit);
    }
    
    public function getNumberOfContents() {
        return $this->migrator->getNumberOfContents();
    }
    
    public function getNumberOfCategories() {
        return $this->migrator->getNumberOfCategories();
    }
    
    public function getNumberOfUsers() {
        return $this->migrator->getNumberOfUsers();
    }
    
    public function getNumberOfTags() {
        return $this->migrator->getNumberOfTags();
    }
    
    public function getNumberOfComments() {
        return $this->migrator->getNumberOfComments();
    }
    
    public function deleteCategories() {
        return $this->migrator->deleteCategories();
    }

    public function deleteContent() {
        return $this->migrator->deleteContent();
    }
    
    public function deleteTags() {
        return $this->migrator->deleteTags();
    }
    
    public function deleteUsers() {
        return $this->migrator->deleteUsers();
    }
    
    public function deleteComments() {
        return $this->migrator->deleteComments();
    }
    
    public function deleteCMigratorData() {
        return $this->migrator->deleteCMigratorData();
    }
    
    public function rebuildCategories($no_cats) {
        return $this->migrator->rebuildCategories($no_cats);
    }
    
    public function rebuildComments($no_comments) {
        return $this->migrator->rebuildComments($no_comments);
    }

}