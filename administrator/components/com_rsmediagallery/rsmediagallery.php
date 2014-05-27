<?php
/**
* @version 1.0.0
* @package RSMediaGallery! 1.0.0
* @copyright (C) 2012 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-3.0.html
*/

defined('_JEXEC') or die('Restricted access');

// Version
$jversion = new JVersion();

// ACL Check
if ($jversion->isCompatible('1.6.0'))
{
	$user =& JFactory::getUser();
	if (!$user->authorise('core.manage', 'com_rsmediagallery'))
		return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

define('_RSMEDIAGALLERY_VERSION', 3);

// Require the base controller
require_once JPATH_COMPONENT.DS.'controller.php';

// See if this is a request for a specific controller
$controller = JRequest::getCmd('controller');
if (!empty($controller) && file_exists(JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php'))
{
	require_once JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php';
	$controller = 'RSMediaGalleryController'.$controller;
	$RSMediaGalleryController = new $controller();
}
else
	$RSMediaGalleryController = new RSMediaGalleryController();
	
$RSMediaGalleryController->execute(JRequest::getCmd('task'));

// Redirect if set
$RSMediaGalleryController->redirect();