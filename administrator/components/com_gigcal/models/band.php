<?php
/**
 * @version		$Id: band.php 21148 2011-04-14 17:30:08Z ian $
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.modeladmin');

/**
 * GigCal Band model.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_gigcal
 * @since		1.5
 */
class GigCalModelBand extends JModelAdmin
{
  protected $text_prefix = 'COM_GIGCAL';

  protected function canDelete($record)
  {
    if (!empty($record->id)) {
      if ($record->state != -2)
        return ;

      $user = JFactory::getUser();
	
      if ($record->catid)
        return $user->authorise('core.delete', 'com_gigcal.category.'.(int) $record->catid);
      else
        return parent::canDelete($record);
    }	
  }

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

  public function getTable($type = 'Band', $prefix = 'GigCalTable', $config = array())
  {
    return JTable::getInstance($type, $prefix, $config);
  }

  public function getForm($data = array(), $loadData = true)
  {
    $app = JFactory::getApplication();

    // Get the form.
    $form = $this->loadForm('com_gigcal.band', 'band', array('control' => 'jform', 'load_data' => $loadData));
    if (empty($form))
      return false;

    // Determine correct permissions to check.
    if ($this->getState('band.id')) {
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
//      $form->setFieldAttribute('ordering', 'disabled', 'true');
      $form->setFieldAttribute('state', 'disabled', 'true');

      // Disable fields while saving.
      // The controller has already verified this is a record you can edit.
      $form->setFieldAttribute('featured', 'filter', 'unset');
//      $form->setFieldAttribute('ordering', 'filter', 'unset');
      $form->setFieldAttribute('state', 'filter', 'unset');
    }

    return $form;
  }

  protected function loadFormData()
  {
    // Check the session for previously entered form data.
    $data = JFactory::getApplication()->getUserState('com_gigcal.edit.band.data', array());

    if (empty($data))
      $data = $this->getItem();

    return $data;
  }

  protected function prepareTable(&$table)
  {
    jimport('joomla.filter.output');
    $date = JFactory::getDate();
    $user = JFactory::getUser();

    $table->bandname = htmlspecialchars_decode($table->bandname, ENT_QUOTES);
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
      $db->setQuery('UPDATE #__gigcal_bands SET featured='.(int) $value.' WHERE id IN ('.implode(',', $pks).')');
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

  public function setDefault($id = 0)
  {
    // Initialise variables.
    $user  = JFactory::getUser();
    $db    = $this->getDbo();

    // Access checks.
    if (!$user->authorise('core.edit.state', 'com_gigcal')) {
      throw new Exception(JText::_('JLIB_APPLICATION_ERROR_EDITSTATE_NOT_PERMITTED'));
    }

    // Reset the home fields for the client_id.
    $db->setQuery('UPDATE #__gigcal_bands SET thedefault=0 WHERE thedefault=1');
    if (!$db->query()) {
      throw new Exception($db->getErrorMsg());
    }

    // Set the new default display.
    $db->setQuery('UPDATE #__gigcal_bands SET thedefault=1 WHERE id='.(int) $id);
    if (!$db->query()) {
      throw new Exception($db->getErrorMsg());
    }

    // Clean the cache.
    $this->cleanCache();
    
    return true;
  }
}
