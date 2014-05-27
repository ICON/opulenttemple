<?php

/**
 * CMigrator - Adminstrator
 * @Copyright (C) 2010 - Daniel Dimitrov - http://compojoom.com
 * @All rights reserved
 * @license under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
jimport('joomla.filesystem');

define('PARENTSRC', $this->parent->getPath('source'));

class com_CMigratorInstallerScript {

    public function install($parent) {
        $result['plugins'] = $this->installPlugins();

        echo $this->displayInstallationInfo($result);

        return true;
    }

    public function update($parent) {
        $result = array();
        $version = $this->getVersion();

        switch ($version) {
            case '0.1' : $result['0.2'] = $this->updateTo0_2();
                break;
            case '0.2' : $result['0.3'] = $this->updateTo0_3();
                break;
            case '0.3' : $result['0.4'] = $this->updateTo0_4();
                break;
            case '0.4' : $result['0.5'] = $this->updateTo0_5();
                break;
            default : $result['0.5'] = $this->updateTo0_2();
        }
        $result['plugins'] = $this->installPlugins();

        echo $this->displayInstallationInfo($result);

        return true;
    }
    
    public function getVersion() {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        
        $query->select('*');
        $query->from('`#__extensions`');
        $query->where('`name`='.$db->quote('com_cmigrator'));
        $db->setQuery($query);
        
        $extension = $db->loadObject();
        $json = $extension->manifest_cache;
        $data = json_decode($json);
        
        return $data->version;
    }
	
    public function displayInstallationInfo($result) {
        $language = JFactory::getLanguage();
        $language->load('com_cmigrator', PARENTSRC . '/administrator', 'en-GB', true);
        $language->load('com_cmigrator', PARENTSRC . '/administrator', $language->getDefault(), true);
        $language->load('com_cmigrator', PARENTSRC . '/administrator', null, true);

        $row = 0;
        $html = "<table class='adminlist'><tr><th>" . JText::_('COM_CMIGRATOR_INSTALLATION_TASK') . "</th><th>" . JText::_('COM_CMIGRATOR_INSTALLATION_STATUS') . "</th></tr>";
        foreach ($result as $key => $value) {
            $html .= "<tr class='row" . $row % 2 . "'>";
            if ($key != 'plugins') {
                if ($value) {
                    $html .= "<td>" . JText::_('COM_CMIGRATOR_INSTALLATION_UPDATE') . $key . "</td><td>" . JText::_('COM_CMIGRATOR_INSTALLATION_SUCCESS') . "</td>";
                } else {
                    $html .= "<td>" . JText::_('COM_CMIGRATOR_INSTALLATION_UPDATE') . $key . "</td><td>" . JText::_('COM_CMIGRATOR_INSTALLATION_FAILED') . "</td>";
                }
                $html .= "</tr>";
            } else {
                if ($value) {
                    $html .= "<td>" . JText::_('COM_CMIGRATOR_INSTALLATION_INSTALLING') . $key . "</td><td>" . JText::_('COM_CMIGRATOR_INSTALLATION_SUCCESS') . "</td>";
                } else {
                    $html .= "<td>" . JText::_('COM_CMIGRATOR_INSTALLATION_INSTALLING') . $key . "</td><td>" . JText::_('COM_CMIGRATOR_INSTALLATION_FAILED') . "</td>";
                }
                $html .= "</tr>";
            }
            $row++;
        }
        $html .= "</table>";

        return $html;
    }
    
    public function updateTo0_2() {
        $this->cleanConf();
        $db = JFactory::getDbo();
        
        $query = "SHOW COLUMNS FROM `#__cmigrator_configuration` LIKE 'name'";
        $db->setQuery($query);
        $query = "";
        
        if($db->loadResult() == null) {
            $query = 'ALTER TABLE #__cmigrator_configuration ADD `name` varchar(255) NOT NULL AFTER `id`;';                 
        }
        $query .= 'ALTER TABLE #__cmigrator_configuration ADD UNIQUE KEY (`name`)';
        
        $db->setQuery($query);
        return ($db->query() && $this->updateTo0_3());
    }
    
    public function updateTo0_3() {
        $this->cleanConf();
        $db = JFactory::getDbo();
        $ret1 = false;
        $ret2 = false;
        $ret3 = true;
        
        $query = 'CREATE TABLE IF NOT EXISTS `#__cmigrator_users` ('.
        '`joomla_id` bigint(11) NOT NULL, '.
        '`cms_id` bigint(11) NOT NULL, '.
        'KEY `key` (`joomla_id`,`cms_id`) '.
        ') DEFAULT CHARSET=utf8';
        
        $db->setQuery($query);
        $ret1 = $db->query();
            
        $query = 'CREATE TABLE IF NOT EXISTS `#__cmigrator_articles` ('.
        '`joomla_id` bigint(11) NOT NULL, '.
        '`cms_id` bigint(11) NOT NULL, '.
        'KEY `key` (`joomla_id`,`cms_id`) '.
        ') DEFAULT CHARSET=utf8';
        
        $db->setQuery($query);
        $ret2 = $db->query();
        
        $query = "SHOW COLUMNS FROM #__cmigrator_categories LIKE 'help_text'";
        $db->setQuery($query);
        $query = "";
        
        if($db->loadResult() == null) {
            $query = 'ALTER TABLE #__cmigrator_categories ADD `help_text` varchar(255) NULL AFTER `imported_parent_id`;';
            $db->setQuery($query);
            $ret3 = $db->query();
        }
        
        return ($ret1 && $ret2 && $ret3 && $this->updateTo0_4());
    }
    
    public function updateTo0_4() {
        return ($this->cleanConf() && $this->clearAssets() && $this->updateTo0_5());
    }
    
    public function updateTo0_5() {
        $this->cleanConf();
        $this->clearAllData();
        $db = JFactory::getDbo();
        
        $query = 'CREATE TABLE IF NOT EXISTS `#__cmigrator_comments` ('.
        '`joomla_id` bigint(11) NOT NULL, '.
        '`cms_id` bigint(11) NOT NULL, '.
        'KEY `key` (`joomla_id`,`cms_id`) '.
        ') DEFAULT CHARSET=utf8';
        
        $db->setQuery($query);
        $ret1 = $db->query();
        
        $query = "ALTER TABLE #__cmigrator_articles CHANGE `joomla_id` `joomla_id` BIGINT(11) NOT NULL";
        $db->setQuery($query);
        $ret2 = $db->query();
        
        $query = "ALTER TABLE #__cmigrator_articles CHANGE `cms_id` `cms_id` BIGINT(11) NOT NULL";
        $db->setQuery($query);
        $ret3 = $db->query();
        
        $query = "ALTER TABLE #__cmigrator_categories CHANGE `joomla_id` `joomla_id` BIGINT(11) NOT NULL";
        $db->setQuery($query);
        $ret4 = $db->query();
        
        $query = "ALTER TABLE #__cmigrator_categories CHANGE `imported_id` `imported_id` BIGINT(11) NOT NULL";
        $db->setQuery($query);
        $ret5 = $db->query();
        
        $query = "ALTER TABLE #__cmigrator_users CHANGE `joomla_id` `joomla_id` BIGINT(11) NOT NULL";
        $db->setQuery($query);
        $ret6 = $db->query();
        
        $query = "ALTER TABLE #__cmigrator_users CHANGE `cms_id` `cms_id` BIGINT(11) NOT NULL";
        $db->setQuery($query);
        $ret7 = $db->query();
        
        return ($ret1 && $ret2 && $ret3 && $ret4 && $ret5 && $ret6 && $ret7);
    }
    
    public function clearAssets() {
        $db = JFactory::getDbo();
        
        // let us remove older errors in assets table
        
        $query = "DELETE FROM `#__assets` WHERE `name` LIKE '.category.%'";
        
        $db->setQuery($query);
        return $db->query();
    }

    public function cleanConf() {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->delete();
        $query->from('`#__cmigrator_configuration`');
        $db->setQuery($query);
        return $db->query();
    }

    public function installPlugins() {
        $db = JFactory::getDbo();
        $status = array();

        $plugins = array(
            'plg_authentication_cmigrator' => 0
        );

        foreach ($plugins as $plugin => $published) {
            $parts = explode('_', $plugin);
            $pluginType = $parts[1];
            $pluginName = $parts[2];

            $path = PARENTSRC . "/plugins/$pluginType/$pluginName";

            $query = "SELECT COUNT(*) FROM  #__extensions WHERE element=" . $db->Quote($pluginName) . " AND folder=" . $db->Quote($pluginType);

            $db->setQuery($query);
            $count = $db->loadResult();

            $installer = new JInstaller;
            $result = $installer->install($path);
            $status[] = array('name' => $plugin, 'group' => $pluginType, 'result' => $result);

            if ($published && !$count) {
                $query = "UPDATE #__extensions SET enabled=1 WHERE element=" . $db->Quote($pluginName) . " AND folder=" . $db->Quote($pluginType);
                $db->setQuery($query);
                $db->query();
            }
        }

        return $status;
    }
    
    public function clearAllData() {
        $db = JFactory::getDbo();
        
        $query = $db->getQuery(true);
        $query->delete('#__cmigrator_articles');
        $db->setQuery($query);
        $ret1 = $db->query();
        
        $query = $db->getQuery(true);
        $query->delete('`#__cmigrator_users`');
        $db->setQuery($query);
        $ret2 = $db->query();
                
        $query = $db->getQuery(true);
        $query->delete('#__cmigrator_categories');
        $db->setQuery($query);
        $ret3 = $db->query();
        
        $query = $db->getQuery(true);
        $query->delete('#__cmigrator_comments');
        $db->setQuery($query);
        $ret4 = $db->query();
        
        return ($ret1 && $ret2 && $ret3 && $ret4);
    }

}