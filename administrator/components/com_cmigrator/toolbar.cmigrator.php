<?php
/**
* Hotspots - Adminstrator
* @package Joomla!
* @Copyright (C) 2010 - Daniel Dimitrov - http://compojoom.com
* @All rights reserved
* @Joomla! is Free Software
* @license under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
* @version $Revision: 1.0 stable
**/

defined('_JEXEC') or die('Restricted access');

$language = JFactory::getLanguage();
$language->load('com_cmigrator.sys', JPATH_ADMINISTRATOR, null, true);

$view	= JRequest::getCmd('view', 'cpanel');

$subMenus = array (
    'cpanel' => 'COM_CMIGRATOR_CPANEL',
    'config' => 'COM_CMIGRATOR_CONFIG',
    'liveupdate' => 'COM_CMIGRATOR_LIVEUPDATE'    
);

foreach($subMenus as $key => $name) {
	$active	= ( $view == $key );
	
	JSubMenuHelper::addEntry( JText::_($name) , 'index.php?option=com_cmigrator&view=' . $key , $active );
}
