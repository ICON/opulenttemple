<?php
/**
 * @version		$Id: venues.php 20228 2011-01-10 00:52:54Z eddieajau $
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');

/**
 * GigCal Venue list controller class.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_gigcal
 * @since		1.6
 */
class GigCalControllerVenues extends JControllerAdmin
{
  public function __construct($config = array())
  {
    parent::__construct($config);

    $this->registerTask('unfeatured', 'featured');
  }

  function featured()
  {
    // Check for request forgeries
    JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

    // Initialise variables.
    $user	= JFactory::getUser();
    $ids	= JRequest::getVar('cid', array(), '', 'array');
    $values	= array('featured' => 1, 'unfeatured' => 0);
    $task	= $this->getTask(); 
    $value	= JArrayHelper::getValue($values, $task, 0, 'int'); 

    // Access checks.
    foreach ($ids as $i => $id)
    {
      if (!$user->authorise('core.edit.state', 'com_gigcal.venue.'.(int) $id)) 
      {
        // Prune items that you can't change.
        unset($ids[$i]);
        JError::raiseNotice(403, JText::_('JLIB_APPLICATION_ERROR_EDITSTATE_NOT_PERMITTED'));
      }
    }

    if (empty($ids))
      JError::raiseWarning(500, JText::_('COM_CONTACT_NO_ITEM_SELECTED'));
    else 
    {
      $model = $this->getModel();

      if (!$model->featured($ids, $value))
        JError::raiseWarning(500, $model->getError());
    }
    $this->setRedirect('index.php?option=com_gigcal&view=venues');
  }

  public function setDefault()
  {
    // Check for request forgeries
    JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

    // Initialise variables.
    $pks = JRequest::getVar('cid', array(), 'post', 'array');

    try
    {
      if (empty($pks)) {
        throw new Exception(JText::_('COM_GIGCAL_NO_DISPLAY_SELECTED'));
      }

      JArrayHelper::toInteger($pks);

      // Pop off the first element.
      $id = array_shift($pks);
      $model = $this->getModel();
      $model->setDefault($id);
      $this->setMessage(JText::_('COM_GIGCAL_SUCCESS_DEFAULT_SET'));

    }
    catch (Exception $e)
    {
      JError::raiseWarning(500, $e->getMessage());
    }

    $this->setRedirect('index.php?option=com_gigcal&view=venues');
  }

  public function getModel($name = 'Venue', $prefix = 'GigCalModel', $config = array('ignore_request' => true))
  {
    $model = parent::getModel($name, $prefix, $config);
    return $model;
  }
}
