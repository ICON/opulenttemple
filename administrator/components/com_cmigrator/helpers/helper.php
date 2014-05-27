<?php
/**

 * CMigrator - Adminstrator
 * @Copyright (C) 2010 - Daniel Dimitrov - http://compojoom.com
 * @All rights reserved
 * @license under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html

**/

defined('_JEXEC') or die('Restricted access');
jimport('joomla.filesystem');

class CMigrateHelper
{
    private static $instances = array();

    protected function __construct() {}
    protected function __clone() {}

    public static function getConfig($id = null, $component = '', $name = '') {
        if(!isset(self::$instances[$component])) {
            self::$instances[$component] = self::_createConfig($id,$component,$name);
        }
        return self::$instances[$component];
    }
    
    public static function getSettings($id = null, $component = '', $name = '') {
        $config = self::getConfig($id,$component,$name);
        $registryData = '';
        if($config) {
            $registryData = $config->settings;
        }
        $settings = new JRegistry($registryData);
        return $settings;
    }

    private static function _createConfig($id = null, $component = '', $name = '') {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        if($id != null){
            $query->select('*');
            $query->from('#__cmigrator_configuration');
            $query->where('id='.$id);
        } else {
            $query->select('*');
            $query->from('#__cmigrator_configuration');
            $query->where('cms='.$db->quote($component))->where('name='.$db->quote($name));
        }
        $db->setQuery($query, 0, 1);
        return $db->loadObject();
    }

    public static function isComponentEnabled($option) {
    	
    	$path = JPATH_ROOT . '/administrator/components/'.$option;
    
        return JFolder::exists($path);
    }
}
