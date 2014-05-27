<?php

/**
 * CMigrator - Adminstrator
 * @Copyright (C) 2012 - Daniel Dimitrov - http://compojoom.com
 * @All rights reserved
 * @license under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
 * */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

class CMigratorControllerConfig extends JController {

    /**
     * constructor (registers additional tasks to methods)
     * @param array $config
     * @return \CMigratorControllerConfig
     */
    public function __construct($config = array()) {
        parent::__construct($config);

        // Register Extra tasks
        $this->registerTask('apply', 'save');
        $this->registerTask('addNew', 'add');
        $this->registerTask('editList', 'edit');
    }

    /**
     * save a record
     * @return void
     */
    public function save() {
        $session_cmigrator = JFactory::getSession();
        $session_cmigrator->clear('cms_cmigrator');
        $session_cmigrator->clear('content_cmigrator');
        $model = $this->getModel('config');
        $data = JRequest::get('post');
        if ($model->saveConfig($data)) {
            $msg = JText::_('COM_CMIGRATOR_CONFIGURATION_SAVED');
        } else {
            $msg = JText::_('COM_CMIGRATOR_ERROR_SAVING_CONFIGURATION');
        }
        if ($this->task == 'apply') {
            $link = 'index.php?option=com_cmigrator&view=config';
        } else {
            $link = 'index.php?option=com_cmigrator';
        }
        $this->setRedirect($link, $msg);
    }

    /**
     * cancel adding or editing a record
     * @return void
     */
    public function cancel() {
        $session_cmigrator = JFactory::getSession();
        $session_cmigrator->clear('cms_cmigrator');
        $session_cmigrator->clear('content_cmigrator');
        $msg = JText::_('COM_CMIGRATOR_CONFIGURATION_CANCELLED');
        $this->setRedirect('index.php?option=com_cmigrator&view=config', $msg);
    }

    /**
     * adding  a record
     * @return void
     */
    public function add() {
        $session_cmigrator = JFactory::getSession();
        $data = JRequest::get('post');
        $session_cmigrator->clear('id_cmigrator');
        $session_cmigrator->set('cms_cmigrator', $data['cms']);
        $session_cmigrator->set('content_cmigrator', $data['content']);
        $link = 'index.php?option=com_cmigrator&view=config&layout=make_edit';
        $this->setRedirect($link);
    }

    /**
     * editing a record
     * @return void
     */
    public function edit() {
        $session_cmigrator = JFactory::getSession();
        $data = JRequest::get('post');
        $id = $data['cid'];
        $msg = '';
        if (count($id) > 1) {
            $msg = JText::_('COM_CMIGRATOR_EDIT_ONE_ONLY');
        }
        $session_cmigrator->set('id_cmigrator', $id[0]);
        $link = 'index.php?option=com_cmigrator&view=config&layout=make_edit';
        if (isset($msg)) {
            $this->setRedirect($link, $msg, 'warning');
        } else {
            $this->setRedirect($link);
        }
    }

    /**
     * deleting configuration from db
     * @return void
     */
    public function deleteConf() {
        $model = $this->getModel('config');
        $data = JRequest::get('post');
        $configurations = $data['cid'];
        $msg = JText::_('COM_CMIGRATOR_CONFIGURATION_DELETED');
        foreach ($configurations as $config) {
            if (!$model->deleteConfig($config)) {
                $msg = JText::_('COM_CMIGRATOR_ERROR_DELETING_CONFIGURATION');
                break;
            }
        }
        $link = 'index.php?option=com_cmigrator&view=config';
        $this->setRedirect($link, $msg);
    }

}