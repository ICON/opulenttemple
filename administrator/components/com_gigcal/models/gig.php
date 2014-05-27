<?php
/**
 * @version    $Id: Gig.php 21148 2011-04-14 17:30:08Z ian $
 * @copyright  Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.modeladmin');

/**
 * GigCal Gig model.
 *
 * @package    Joomla.Administrator
 * @subpackage  com_gigcal
 * @since    1.5
 */
class GigCalModelGig extends JModelAdmin
{
  protected $text_prefix = 'COM_GIGCAL';

  /**
   * Method to test whether a record can be deleted.
   *
   * @param  object  A record object.
   * @return  boolean  True if allowed to delete the record. Defaults to the permission set in the component.
   * @since  1.6
   */
  protected function canDelete($record)
  {
    if (!empty($record->id)) {
      if ($record->state != -2) {
        return ;
      }
      $user = JFactory::getUser();
  
      if ($record->catid) {
        return $user->authorise('core.delete', 'com_gigcal.category.'.(int) $record->catid);
      }
      else {
        return parent::canDelete($record);
      }
    }  
  }

  /**
   * Method to test whether a record can have its state changed.
   *
   * @param  object  A record object.
   * @return  boolean  True if allowed to change the state of the record. Defaults to the permission set in the component.
   * @since  1.6
   */
  protected function canEditState($record)
  {
    $user = JFactory::getUser();

    if (!empty($record->catid)) {
      return $user->authorise('core.edit.state', 'com_gigcal.category.'.(int) $record->catid);
    }
    else {
      return parent::canEditState($record);
    }
  }
  /**
   * Returns a reference to the a Table object, always creating it.
   *
   * @param  type  The table type to instantiate
   * @param  string  A prefix for the table class name. Optional.
   * @param  array  Configuration array for model. Optional.
   * @return  JTable  A database object
   * @since  1.6
   */
  public function getTable($type = 'Gig', $prefix = 'GigCalTable', $config = array())
  {
//error_log('gig::getTable(type='.$type.', prefix='.$prefix.', config='.print_r($config));
    return JTable::getInstance($type, $prefix, $config);
  }

  /**
   * Method to get the record form.
   *
   * @param  array  $data    An optional array of data for the form to interogate.
   * @param  boolean  $loadData  True if the form is to load its own data (default case), false if not.
   * @return  JForm  A JForm object on success, false on failure
   * @since  1.6
   */
  public function getForm($data = array(), $loadData = true)
  {
    // Initialise variables.
    $app  = JFactory::getApplication();

    // Get the form.
    $form = $this->loadForm('com_gigcal.gig', 'gig', array('control' => 'jform', 'load_data' => $loadData));
    if (empty($form)) {
      return false;
    }

    // Determine correct permissions to check.
    if ($this->getState('gig.id')) {
      // Existing record. Can only edit in selected categories.
      $form->setFieldAttribute('catid', 'action', 'core.edit');
    } else {
      // New record. Can only create in selected categories.
      $form->setFieldAttribute('catid', 'action', 'core.create');
    }

    // Modify the form based on access controls.
    if (!$this->canEditState((object) $data)) {
      // Disable fields for display.
      $form->setFieldAttribute('featured', 'disabled', 'true');
      $form->setFieldAttribute('state', 'disabled', 'true');

      // Disable fields while saving.
      // The controller has already verified this is a record you can edit.
      $form->setFieldAttribute('featured', 'filter', 'unset');
      $form->setFieldAttribute('state', 'filter', 'unset');
    }

    return $form;
  }

  /**
   * Build a list of bands
   *
   * @return  JDatabaseQuery
   * @since  1.6
   */
  public function getBands() {
    // Create a new query object.
    $db = $this->getDbo();
    $query = $db->getQuery(true);

    // Construct the query
    $query->select('u.id AS value, u.bandname AS text');
    $query->from('#__gigcal_bands AS u');
    $query->where('u.published >= 0');
    $query->order('u.bandname');

    // Setup the query
    $db->setQuery($query->__toString());

    // Return the result
    return $db->loadObjectList();
  }

  /**
   * Build a list of venues
   *
   * @return  JDatabaseQuery
   * @since  1.6
   */
  public function getVenues() {
    // Create a new query object.
    $db = $this->getDbo();
    $query = $db->getQuery(true);

    // Construct the query
    $query->select('u.id AS value, u.venuename AS text');
    $query->from('#__gigcal_venues AS u');
    $query->where('u.published >= 0');
    $query->order('u.venuename');

    // Setup the query
    $db->setQuery($query->__toString());

    // Return the result
    return $db->loadObjectList();
  }

  /**
   * Method to get the data that should be injected in the form.
   *
   * @return  mixed  The data for the form.
   * @since  1.6
   */
  protected function loadFormData()
  {
    // Check the session for previously entered form data.
    $data = JFactory::getApplication()->getUserState('com_gigcal.edit.gig.data', array());

    if (empty($data)) {
      $data = $this->getItem();

      // Prime some default values.
      if ($this->getState('gig.id') == 0) {
        $data->gigdate = time();
      }
    }
    $data->gigdate = date('Y-m-d H:i', $data->gigdate);
    return $data;
  }

  public function featured($pks, $value = 0)
  {
    // Sanitize the ids.
    $pks = (array) $pks;
    JArrayHelper::toInteger($pks);

    if (empty($pks)) {
      $this->setError(JText::_('COM_GIGCAL_NO_ITEM_SELECTED'));
      return false;
    }

    $table = $this->getTable();

    try
    {
      $db = $this->getDbo();
      $db->setQuery('UPDATE #__gigcal_gigs SET featured='.(int) $value.' WHERE id IN ('.implode(',', $pks).')');
      if (!$db->query())
        throw new Exception($db->getErrorMsg());
    }
    catch (Exception $e)
    {
      $this->setError($e->getMessage());
      return false;
    }

    $table->reorder();

    // Clean component's cache
    $this->cleanCache();

    return true;
  }


 /**
   * Prepare and sanitise the table prior to saving.
   *
   * @since  1.6
   */
  protected function prepareTable(&$table)
  {
    jimport('joomla.filter.output');
    $date = JFactory::getDate();
    $user = JFactory::getUser();

    $table->gigtitle  = htmlspecialchars_decode($table->gigtitle, ENT_QUOTES);
  }
}
