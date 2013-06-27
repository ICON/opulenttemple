<?php
/**
 * @version		2.1.0 gigcal $
 * @package		gigcal
 * @copyright		Copyright © 2009 Michael Moore - All rights reserved.
 * @license		GNU/GPL
 * @author		Michael Moore
 * @author mail		Michael@MichaelMoore.net
 * @website		http://www.michaelmoore.net
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

require_once JPATH_COMPONENT.DS.'views'.DS.'subroutines.php';

/*
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
*/

// construct navigational menu html string
$task = JRequest::getCmd('task', JRequest::getCmd('view', 'gigcal'));
if($task=='gigcal')
{
  $db =& JFactory::GetDBO();
  $sql="SELECT fieldname FROM #__gigcal_menu_fields WHERE id=".$this->config['default_task'];
  $db->setQuery($sql);
  $task = GetShortName($db->loadResult());
}

//Get Fields
$fieldnames=dbLookupFieldnames("menu");

$limit=10;
$menuitems = array();
foreach(dbLookupFieldnames("menu") as $fieldname) {
  $shortname = GetShortName($fieldname);
  if ($task != $shortname)
  {
    $displayname = $this->config['menu_'.$shortname];
    $menuitems[] = '<a href="' . JRoute::_('index.php?option=com_gigcal&task='.$shortname.'&limit='.$limit.'&limitstart=0').'" class="arrow_btn">'.$displayname.'</a>';
  }
}
$this->menuHTML = '<span class="gigcal_menu">'.implode($this->config['menu_delim'], $menuitems)."</span><br />";

/**
function getDisplayNavHTML($config, $menus, $view, $id)
{
  $class = 'gc_'.$id.'_nav';
  echo '<ul id="'.$class.'">';
  foreach($menus as $menu)
  {
    $shortname = GetShortName($menu['fieldname']);
    $displayname = $config['menu_'.$shortname];
    if ($shortname==$view)
      echo '  <li class="'.$class.'_selected">'.$displayname.'</li>';
    else
      echo '  <li class="'.$class.'">'.JHtml::link(JRoute::_('index.php?option=com_gigcal&view=gigcal&view='.$shortname), $displayname).'</li>';
  }
  echo '</ul>';
}

function getHeadingHTML($config, $view)
{
  $title = $config['menu_'.$view];
  if ($title != '')
    echo '<div id="gc_title">'.$title.'</div>';
}
**/

