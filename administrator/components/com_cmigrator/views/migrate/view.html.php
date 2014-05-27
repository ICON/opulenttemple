<?php

/**
 * CMigrator - Adminstrator
 * @Copyright (C) 2010 - Daniel Dimitrov - http://compojoom.com
 * @All rights reserved
 * @license under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
 * */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport('joomla.application.component.view');

class CMigratorViewMigrate extends JView {

    /**
     *
     * @param null $tpl
     * @return void
     */
    public function display($tpl = null) {
        JToolBarHelper::title(JText::_('COM_CMIGRATOR_MIGRATE'), 'install.png');

        $post = JRequest::get('post');
        
        $content_status = $post['content'];

        $categories_status = $post['categories'];

        $users_status = $post['users'];

        $tags_status = $post['tags'];
        
        $comments_status = $post['comments'];

        $status = array("content" => $content_status, "categories" => $categories_status, "users" => $users_status, "tags" => $tags_status, "comments" => $comments_status);

        $errors = $this->errorCheck($status);

        $this->assignRef('errors', $errors);

        parent::display($tpl);
    }

    private function errorCheck($status) {

        $errors = array();

        foreach ($status as $key => $value) {

            if ($value == "ERROR") {
                $errors[$key] = "FAILED! - DB error!";
            } else if ($value == "OK") {
                $errors[$key] = "OK!";
            } else if ($value == -1) {
                $errors[$key] = "Disabled!";
            } else if ($value == 0) {
                $errors[$key] = "Nothing to migrate!";
            }
        }

        return $errors;
    }

}
