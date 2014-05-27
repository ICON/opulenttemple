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
jimport('joomla.filesystem.file');

class CmigratorViewConfig extends JView {

    /**
     * Hellos view display method
     * @param null $tpl
     * @return void
     */
    
    public function display($tpl = null) {
        
        $session_cmigrator = JFactory::getSession();
        $lists = array();
        $cms_cmigrator = null;
        $content_cmigrator = null;
        $registryData = '';
        $settings = null;
        $cms_make = null;
        $content_make = null;
        $config = null;
        $config_id = null;

        $cms_cmigrators = $this->get('CMSMigrators');
        $content_cmigrators = $this->get('ContentComponents');

        if ($session_cmigrator->get('id_cmigrator') != null) {
            $config_id = $session_cmigrator->get('id_cmigrator');
            $config = CMigrateHelper::getConfig($config_id);
        }
        if ($config) {
            $registryData = $config->settings;
            $settings = new JRegistry($registryData);
            $session_cmigrator->set('cms_cmigrator', $config->cms);
            $session_cmigrator->set('content_cmigrator', $settings->get('content_selected'));
        } else {
            $registryData = '';
            $settings = new JRegistry($registryData);
        }
        if ($session_cmigrator->get('cms_cmigrator') != null && $session_cmigrator->get('content_cmigrator') != null) {
            $cms_make = $session_cmigrator->get('cms_cmigrator');
            $content_make = $session_cmigrator->get('content_cmigrator');
            $this->assignRef('cms_make', $cms_make);
            $this->assignRef('content_make', $content_make);
        }

        $yesNo = array(JHTML::_('select.option', '1', JText::_('JYES')), JHTML::_('select.option', '0', JText::_('JNO')));
        $lists['categories'] = JHTML::_('select.genericlist', $yesNo, 'categories', null, 'value', 'text', $settings->get('categories'));
        $lists['content'] = JHTML::_('select.genericlist', $yesNo, 'content', null, 'value', 'text', $settings->get('content'));
        $lists['tags'] = JHTML::_('select.genericlist', $yesNo, 'tags', null, 'value', 'text', $settings->get('tags'));
        $lists['users'] = JHTML::_('select.genericlist', $yesNo, 'users', null, 'value', 'text', $settings->get('users'));
        $lists['comments'] = JHTML::_('select.genericlist', $yesNo, 'comments', null, 'value', 'text', $settings->get('comments'));
        $lists['migration'] = JHTML::_('select.genericlist', $yesNo, 'migration', null, 'value', 'text', $settings->get('migration'));
        $cms_cmigrator = JHTML::_('select.genericlist', $cms_cmigrators, 'cms');
        $content_cmigrator = JHTML::_('select.genericlist', $content_cmigrators, 'content');
        $this->configs = $this->get('Configs');

        $this->assignRef('config_id', $config_id);
        $this->assignRef('settings', $settings);
        $this->assignRef('lists', $lists);
        $this->assignRef('cms_cmigrator', $cms_cmigrator);
        $this->assignRef('content_cmigrator', $content_cmigrator);

        parent::display($tpl);
    }

}