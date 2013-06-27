<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla controller library
jimport('joomla.application.component.controller');
 

function GetShortName($name)
{
  $names = array ("Calendar View"=>"cal", 
		"List View"=>"list", 
		"Archived List View"=>"alist", 
		"RSS Feeds"=>"rss", 
		"Bands List"=>"bandslist", 
		"Venues List"=>"venueslist");
  return $names[$name];
}

/**
 * GigCal Component Controller
 */
class GigCalController extends JController
{
  function display()
  {
    $document = & JFactory::getDocument ();
    $document->addStyleSheet(JURI::base().'/components/com_gigcal/gigcal.css');
    $document->addScript(JURI::base().'/components/com_gigcal/overlib.js');

    $task = JRequest::getVar('task', 'gigcal');

    if ($task == 'details')
    {
      if (JRequest::getInt('band_id', 0) > 0)
        $task = 'bandslist';
      else if (JRequest::getInt('venue_id', 0) > 0)
        $task = 'venueslist';
      else
        $task = 'gigslist';
    }

    if ($task == 'gigcal')
    {
      $db =& JFactory::GetDBO();
      $db->setQuery('SELECT fieldname FROM #__gigcal_config c JOIN #__gigcal_menu_fields mf ON mf.id=c.default_task WHERE active=1');
      $task = GetShortName($db->LoadResult());
    }
//error_log( 'task='.$task);
    $view =& $this->getView($task, 'html');
//echo 'view='.print_r($view,true);
    $tpl = JRequest::getVar('tpl', null, '', 'string');

    $view->display($tpl);
  }
}

