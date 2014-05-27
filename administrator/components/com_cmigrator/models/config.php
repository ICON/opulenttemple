<?php

/**
 * CMigrator - Adminstrator
 * @Copyright (C) 2010 - Daniel Dimitrov - http://compojoom.com
 * @All rights reserved
 * @license under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
 * */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport('joomla.application.component.model');

class CMigratorModelConfig extends JModel {

    /**
     * @param $post
     * @return bool
     * @throws Exception
     */
    public function saveConfig($post) {
        $table = $this->getTable('Configuration', 'CMigratorTable');

        $registry = new JRegistry($post);
        $row = new JObject();
        $row->cms = $post['cms'];
        $row->name = $post['name'];
        $id = $post['config_id'];
        $row->settings = $registry->toString('INI');

        $config = CMigrateHelper::getConfig($id);
        if ($config) {
            $row->id = $config->id;
        }

        if (!$table->bind($row)) {
            throw new Exception($table->getError());
            return false;
        }

        if (!$table->store()) {
            throw new Exception($table->getError());
            return false;
        }
        return true;
    }

    public function deleteConfig($id = null, $cms = '', $name = '') {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        if ($id) {
            $query->delete('#__cmigrator_configuration');
            $query->where('id=' . $id);
        } else {
            $query->delete('#__cmigrator_configuration');
            $query->where('cms=' . $db->quote($cms), 'name=' . $db->quote($name));
        }
        $db->setQuery($query);
        if ($db->query()) {
            return true;
        } else
            return false;
    }

    public function getConfigs() {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query->select('*');
        $query->from('#__cmigrator_configuration');
        $db->setQuery($query);

        return $db->loadObjectList();
    }

    /**
     * Get the Content components we can import to
     *
     * @return array - components that the user has on his system
     */
    public function getContentComponents() {
        jimport('joomla.filesystem');

	// get the migrators for the components
        $migrators = JFolder::folders(JPATH_COMPONENT_ADMINISTRATOR . '/library/migrators');
        $components = array();

        foreach ($migrators as $component) {
	// check if the component is installed on the user's system
            if (CMigrateHelper::isComponentEnabled($component)) {
                $components[$component] = $component;
            }
        }

        return $components;
    }

    /**
     * @return array
     */
    public function getCMSMigrators() {
        $cms_cmigrators = array();

        $migrators_joomla = JFolder::files(JPATH_COMPONENT_ADMINISTRATOR . '/library/migrators/com_content/', '.php');
        $migrators_k2 = JFolder::files(JPATH_COMPONENT_ADMINISTRATOR . '/library/migrators/com_content/', '.php');

        if (in_array('wordpress.php', $migrators_joomla) && in_array('wordpress.php', $migrators_k2)) {
            $cms_cmigrators[] = JHTML::_('select.option', 'wordpress', 'WordPress');
        }
        if (in_array('drupal.php', $migrators_joomla) && in_array('drupal.php', $migrators_k2)) {
            $cms_cmigrators[] = JHTML::_('select.option', 'drupal', 'Drupal');
        }

        return $cms_cmigrators;
    }

}