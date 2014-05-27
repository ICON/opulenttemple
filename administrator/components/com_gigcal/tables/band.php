<?php
/**
 * @version    $Id: band.php 20782 2011-02-19 06:01:24Z infograf768 $
 * @copyright  Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

/**
 * GigCal Band Table class
 *
 * @package    Joomla.Administrator
 * @subpackage  com_gigcal
 * @since    1.5
 */
class GigCalTableBand extends JTable
{
  public function __construct(&$db)
  {
    parent::__construct('#__gigcal_bands', 'id', $db);
  }

  public function bind($array, $ignore = '')
  {
    if (isset($array['params']) && is_array($array['params'])) {
      $registry = new JRegistry();
      $registry->loadArray($array['params']);
      $array['params'] = (string)$registry;
    }

    if (isset($array['metadata']) && is_array($array['metadata'])) {
      $registry = new JRegistry();
      $registry->loadArray($array['metadata']);
      $array['metadata'] = (string)$registry;
    }
    return parent::bind($array, $ignore);
  }

  public function store($updateNulls = false)
  {
    $date  = JFactory::getDate();
    $user  = JFactory::getUser();
    if ($this->id) 
    {
      // Existing item
      $this->modified    = $date->toMySQL();
      $this->modified_by  = $user->get('id');
    } 
    else 
    {
      // New band. A band created and created_by field can be set by the user,
      // so we don't touch either of these if they are set.
      if (!intval($this->created))
        $this->created = $date->toMySQL();
      if (empty($this->created_by))
        $this->created_by = $user->get('id');
    }
    
    // Verify that the bandname is unique
    $table = JTable::getInstance('Band', 'GigCalTable');
    if ($table->load(array('bandname'=>$this->bandname /*,'catid'=>$this->catid */)) && ($table->id != $this->id || $this->id==0)) {
      $this->setError(JText::_('COM_GIGCAL_ERR_BAND_NAME_NOT_UNIQUE'));
      return false;
    }
    // Attempt to store the user data.
    return parent::store($updateNulls);
  }

  public function check()
  {
    if (JFilterInput::checkAttribute(array ('href', $this->website))) {
      $this->setError(JText::_('COM_GIGCAL_ERR_TABLES_PROVIDE_URL'));
      return false;
    }

    // check for valid bandname
//    if (trim($this->bandname) == '') {
//      $this->setError(JText::_('COM_GIGCAL_ERR_TABLES_NAME'));
//      return false;
//    }

    // check for http, https, ftp on webpage
    if ((stripos($this->website, 'http://') === false)
      && (stripos($this->website, 'https://') === false)
      && (stripos($this->website, 'ftp://') === false))
    {
      $this->website = 'http://'.$this->website;
    }

    // check for existing bandname
    $query = 'SELECT id FROM #__gigcal_bands WHERE bandname = '.$this->_db->Quote($this->bandname);//.' AND catid = '.(int) $this->catid;
    $this->_db->setQuery($query);

    $xid = intval($this->_db->loadResult());
    if ($xid && $xid != intval($this->id)) {
      $this->setError(JText::_('COM_GIGCAL_ERR_BAND_NAME_NOT_UNIQUE'));
      return false;
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

