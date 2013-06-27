<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 
/**
 * HTML View class for the GigCal RSS Component
 */
class GigCalViewRSS extends JView
{
  public $config;

  // Overwriting JView display method
  function display($tpl = null) 
  {
    // Load common data
    $db =& JFactory::GetDBO();
    $db->setQuery('SELECT * FROM #__gigcal_config WHERE active=1');
    $this->config = $db->LoadAssoc();

    $db->setQuery('SELECT fieldname FROM #__gigcal_menu_fields WHERE published=1 ORDER BY ordering');
    $this->menus = $db->loadAssocList();

    $db->setQuery('SELECT id, bandname FROM #__gigcal_bands WHERE published=1 ORDER BY bandname');
    $this->bands = $db->loadAssocList();

    $db->setQuery('SELECT id, venuename FROM #__gigcal_venues WHERE published=1 ORDER BY venuename');
    $this->venues = $db->loadAssocList();

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

