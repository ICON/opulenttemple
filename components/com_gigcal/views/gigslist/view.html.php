<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 
/**
 * HTML View class for the GigCal Gig List Component
 */
class GigCalViewGigsList extends JView
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

    $id = JRequest::getInt('id', 0);
    $day = JRequest::getInt('day', 0);
    if ($id > 0)
      $where = 'id='.$id;
    else if ($day > 0)
    {
      $month = JRequest::getInt('month', date("n"));
      $year = JRequest::getInt('year', date("Y"));
     
      $thisday = mktime(0, 0, 0, $month, $day,   $year);
      $nextday = mktime(0, 0, 0, $month, $day+1, $year);

      $where = 'gigdate>='.$thisday.' AND gigdate<'.$nextday;
    }
    else
      $where = 'gigdate > '.time();

    $db->setQuery('SELECT g.* FROM #__gigcal_gigs g WHERE published=1'
		.' AND '.$where
		.' ORDER BY gigdate');
    $this->gigs = $db->loadAssocList();
 
    $band_ids = array();
    $venue_ids = array();
    foreach($this->gigs as $gig)
    {
      $band_ids[] = $gig['band_id'];
      $venue_ids[] = $gig['venue_id'];
    }

    $db->setQuery('SELECT b.* FROM #__gigcal_bands b WHERE published=1 AND id in ('.implode(',', $band_ids).')');
    $this->bands = $db->loadAssocList('id');

    $db->setQuery('SELECT v.* FROM #__gigcal_venues v WHERE published=1 AND id in ('.implode(',', $venue_ids).')');
    $this->venues = $db->loadAssocList('id');

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

