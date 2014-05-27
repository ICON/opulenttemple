<?php
/**
 * @version    $Id: gig.php 20782 2011-02-19 06:01:24Z infograf768 $
 * @copyright  Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

/**
 * GigCal Gig Table class
 *
 * @package    Joomla.Administrator
 * @subpackage  com_gigcal
 * @since    1.5
 */
class GigCalTableGig extends JTable
{
  /**
   * Constructor
   *
   * @param JDatabase A database connector object
   */
  public function __construct(&$db)
  {
//error_log('gig table');
    parent::__construct('#__gigcal_gigs', 'id', $db);
  }

  /**
   * Overloaded bind function to pre-process the params.
   *
   * @param  array    Named array
   * @return  null|string  null is operation was satisfactory, otherwise returns an error
   * @see    JTable:bind
   * @since  1.5
   */
  public function bind($array, $ignore = '')
  {
//    if (isset($array['params']) && is_array($array['params'])) {
//      $registry = new JRegistry();
//      $registry->loadArray($array['params']);
//      $array['params'] = (string)$registry;
//    }

    if (isset($array['metadata']) && is_array($array['metadata'])) {
      $registry = new JRegistry();
      $registry->loadArray($array['metadata']);
      $array['metadata'] = (string)$registry;
    }
    return parent::bind($array, $ignore);
  }


  /**
   * Overload the store method for the Gigs table.
   *
   * @param  boolean  Toggle whether null values should be updated.
   * @return  boolean  True on success, false on failure.
   * @since  1.6
   */
  public function store($updateNulls = false)
  {
    $date  = JFactory::getDate();
    $user  = JFactory::getUser();
    if ($this->id) {
      // Existing item
      $this->modified    = $date->toMySQL();
      $this->modified_by  = $user->get('id');
    } else {
      // New gig. A gig created and created_by field can be set by the user,
      // so we don't touch either of these if they are set.
      if (!intval($this->created)) {
        $this->created = $date->toMySQL();
      }
      if (empty($this->created_by)) {
        $this->created_by = $user->get('id');
      }
    }
   $jconfig =& new JConfig();
   date_default_timezone_set($jconfig->offset);
   $this->gigdate = strtotime($this->gigdate);

    // Attempt to store the user data.
    return parent::store($updateNulls);
  }

  /**
   * Overloaded check method to ensure data integrity.
   *
   * @return  boolean  True on success.
   */
  public function check()
  {
    if (JFilterInput::checkAttribute(array ('href', $this->saleslink))) {
      $this->setError(JText::_('COM_GIGCAL_ERR_TABLES_PROVIDE_URL'));
      return false;
    }

    // check for http, https, ftp on webpage
    if ((stripos($this->saleslink, 'http://') === false)
      && (stripos($this->saleslink, 'https://') === false)
      && (stripos($this->saleslink, 'ftp://') === false))
    {
      $this->saleslink = 'http://'.$this->saleslink;
    }


    // clean up keywords -- eliminate extra spaces between phrases
    // and cr (\r) and lf (\n) characters from string
    if (!empty($this->metakey)) {
      // only process if not empty
      $bad_characters = array("\n", "\r", "\"", "<", ">"); // array of characters to remove
      $after_clean = JString::str_ireplace($bad_characters, "", $this->metakey); // remove bad characters
      $keys = explode(',', $after_clean); // create array using commas as delimiter
      $clean_keys = array();
      foreach($keys as $key) {
        if (trim($key)) {  // ignore blank keywords
          $clean_keys[] = trim($key);
        }
      }
      $this->metakey = implode(", ", $clean_keys); // put array back together delimited by ", "
    }

    return true;
  }


}
