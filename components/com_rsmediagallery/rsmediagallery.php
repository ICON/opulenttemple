<?php
/**
* @version 1.0.0
* @package RSMediaGallery! 1.0.0
* @copyright (C) 2012 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-3.0.html
*/

defined('_JEXEC') or die('Restricted access');

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