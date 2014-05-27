<?php
/**

 * CMigrator - Adminstrator
 * @Copyright (C) 2010 - Daniel Dimitrov - http://compojoom.com
 * @All rights reserved
 * @license under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html

**/

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');



require_once( JPATH_COMPONENT_ADMINISTRATOR . '/includes/defines.php');
require_once( JPATH_COMPONENT_ADMINISTRATOR . '/helpers/helper.php');

require_once JPATH_COMPONENT_ADMINISTRATOR.DS.'liveupdate'.DS.'liveupdate.php';
if(JRequest::getCmd('view','') == 'liveupdate') {
    JToolBarHelper::preferences( 'com_cmigrator' );
    LiveUpdate::handleRequest();
    return;
}

// load the language files
$jlang = JFactory::getLanguage();
$jlang->load('com_cmigrator', JPATH_ADMINISTRATOR, 'en-GB', true);
$jlang->load('com_cmigrator', JPATH_ADMINISTRATOR, $jlang->getDefault(), true);
$jlang->load('com_cmigrator', JPATH_ADMINISTRATOR, null, true);

// Require specific controller if requested
$controller = JRequest::getVar('view', 'cpanel');

//echo $controller;

if($controller) {
    $path = JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php';
    if (file_exists($path)) {
        require_once $path;
    } else {
        echo JError::raiseError('404', 'Controller not found');
        jexit();
    }
}


// Create the controller
$classname	= 'CMigratorController'.$controller;

$controller = new $classname( );

// Perform the Request task
$controller->execute( JRequest::getVar('task'));

// Redirect if set by the controller
$controller->redirect();