<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 
/**
 * HTML View class for the GigCal Calendar Component
 */
class GigCalViewCal extends JView
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

//    $this->fieldsnames = dbLookupFieldnames("gigcal_cal_fields");

    if(!isset($_REQUEST["year"]))
      $this->year = date("Y");
    else
      $this->year = $_REQUEST["year"];

    if(!isset($_REQUEST["month"]))
      $this->month = date("n");
    else {
      $this->month = $_REQUEST["month"];
      if( $this->month <= 0 ) { 
        $this->month = 12; 
        $this->year=$this->year - 1;
      }
      if( $this->month >= 13 ) { 
        $this->month = 1; 
        $this->year=$this->year + 1;
      }
    }

    $thisMonth = mktime( 0, 0, 0, $this->month,    0,  $this->year );  // calculate first second of this and next month
    $nextMonth = mktime( 0, 0, 0, $this->month+1,  1,  $this->year );  // everything in between (inclusive / exclusive) is THIS month

    $sql = 'SELECT * FROM #__gigcal_gigs WHERE published=1 AND gigdate >= '.$thisMonth.' AND gigdate < '.$nextMonth.' ORDER BY gigdate';

    $db->setQuery($sql);
    $this->gigs = $db->loadAssocList();

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

