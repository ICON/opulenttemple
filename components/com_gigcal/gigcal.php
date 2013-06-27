<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import joomla controller library
jimport('joomla.application.component.controller');
 
$controller = JController::getInstance('GigCal');
$controller->execute(JRequest::getCmd('task'));
$controller->redirect();

