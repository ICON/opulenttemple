<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 
/**
 * HTML View class for the GigCal Archive Gig List Component
 */
class GigCalViewAList extends JView
{
  public $config;

  // Overwriting JView display method
  function display($tpl = null) 
  {
    $app =& JFactory::getApplication();
    
    // get/set limits for pagination
    $this->limit = $app->getUserStateFromRequest("global.list.limit", 'limit', $app->getCfg('list_limit'), 'int');
    $this->limitstart = JRequest::getInt('limitstart', 0);
    $this->limitstart = ($this->limit != 0 ? (floor($this->limitstart / $this->limit) * $this->limit) : 0);

    // Load common data
    $db =& JFactory::GetDBO();
    $db->setQuery('SELECT * FROM #__gigcal_config WHERE active=1');
    $this->config = $db->LoadAssoc();

    $db->setQuery('SELECT fieldname FROM #__gigcal_menu_fields WHERE published=1 ORDER BY ordering');
    $this->menus = $db->loadAssocList();

    $db->setQuery('SELECT g.* FROM #__gigcal_gigs g WHERE published=1'
		.' AND gigdate < '.time()
		.' ORDER BY gigdate desc', $this->limitstart, $this->limit);
    $this->gigs = $db->loadAssocList();

    $db->setQuery('SELECT FOUND_ROWS()');
    
    jimport('joomla.html.pagination');
    $this->pageNav = new JPagination($db->loadResult(), $this->limitstart, $this->limit);

    // Check for errors.
    if (count($errors = $this->get('Errors'))) 
    {
      JError::raiseError(500, implode('<br />', $errors));
      return false;
    }

    // Display the view
    parent::display($tpl);
  }
}

