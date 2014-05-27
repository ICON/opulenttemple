<?php
/**
 * @version		$Id: bands.php 20196 2011-01-09 02:40:25Z ian $
 * @package		Joomla.Administrator
 * @subpackage	com_gigcal
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

//// Access check.
//if (!JFactory::getUser()->authorise('core.manage', 'com_gigcal')) {
//	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
//}

// Include dependancies
jimport('joomla.application.component.controller');

$controller= JController::getInstance('gigcal');
$controller->execute(JRequest::getCmd('task'));
$controller->redirect();
