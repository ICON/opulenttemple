<?php
/**
 * @version    $Id: bands.php 20228 2011-01-10 00:52:54Z eddieajau $
 * @copyright  Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');

/**
 * GigCal Fields list controller class.
 *
 * @package    Joomla.Administrator
 * @subpackage  com_gigcal
 * @since    1.6
 */
class GigCalControllerConfig extends JControllerAdmin
{
  public function __construct($config = array())
  {
    parent::__construct($config);

    $this->registerTask('apply', 'save');
//    JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_gigcal/tables');
  }

  function publish()
  {
    // Check for request forgeries
    JRequest::checkToken() or die(JText::_('JINVALID_TOKEN'));

    $fields = JRequest::getVar('fields');
    $cid  = JRequest::getVar('cid', array(), '', 'array');
    $id   = (int)$cid[0];
    $data  = array('publish' => 1, 'unpublish' => 0, 'archive'=> 2, 'trash' => -2, 'report'=>-3);
    $task   = $this->getTask();
    $value  = JArrayHelper::getValue($data, $task, 0, 'int');

    if (!empty($cid)) {
//      $table =& JTable::getInstance($fields.'_Fields', 'GigCalTable');
//      $table->reset();
//      $table->load((int)$cid[0]);
//      $table->set('published', $value);
//      $table->store();
      $db =& JFactory::getDBO();
//      error_log('UPDATE #__gigcal_'.$fields.'_fields SET published='.$value.' WHERE id='.$id);
      $db->setQuery('UPDATE #__gigcal_'.$fields.'_fields SET published='.$value.' WHERE id='.$id);
      $db->query();
    }

    $this->save();
    $this->setRedirect(JRoute::_('index.php?option=com_gigcal&view=config&fields='.$fields, false));
//    return true;
  }

  function reorder()
  {
    // Check for request forgeries
    JRequest::checkToken() or die(JText::_('JINVALID_TOKEN'));
    $fields = JRequest::getVar('fields');

    // Initialise variables.
    $cid  = JRequest::getVar('cid', null, 'post', 'array');
    $id   = (int)$cid[0];
    $inc  = ($this->getTask() == 'orderup') ? '-1' : '+1';

    $db =& JFactory::getDBO();
    $db->setQuery('UPDATE #__gigcal_'.$fields.'_fields SET ordering=ordering'.$inc.' WHERE id='.$id);
    $db->query();
    $db->setQuery('SELECT ordering FROM #__gigcal_'.$fields.'_fields WHERE id='.$id);
    $ordering = $db->loadResult();
    $db->setQuery('UPDATE #__gigcal_'.$fields.'_fields SET ordering=ordering-('.$inc.') WHERE ordering='.$ordering.' AND id!='.$id);
    $db->query();

    $this->save();
    $this->setRedirect(JRoute::_('index.php?option=com_gigcal&view=config&fields='.$fields, false));
//    $this->setRedirect(JRoute::_('index.php?option=com_gigcal&view='.$this->view_list, false));
    return true;
  }

 
  function save()
  {
    // Check for request forgeries
    JRequest::checkToken() or die(JText::_('JINVALID_TOKEN'));
    $fields = JRequest::getVar('fields');
    $task = JRequest::getVar('task');

    // check all POST parameters against config fields and save anything that has changed...
    $db =& JFactory::getDBO();
    $db->setQuery('SELECT * FROM #__gigcal_config WHERE active=1');
    $config=$db->LoadAssoc();

    $sets=array();
    foreach(array_intersect_key($_POST, $config) as $key => $value)
    {
	if ($config[$key] != $value)
          $sets[] = $key."='".addslashes($value)."'";
    }
    if (count($sets)>0)
    {
      $db->setQuery('UPDATE #__gigcal_config SET '.implode($sets, ', ').' WHERE active=1');
      $config=$db->query();
    }

    if($task=='apply')
      $this->setRedirect(JRoute::_('index.php?option=com_gigcal&view=config&fields='.$fields, false));
    else
      $this->setRedirect(JRoute::_('index.php?option=com_gigcal', false));

    return true;
  }

 
  function cancel()
  {
    $this->setRedirect(JRoute::_('index.php?option=com_gigcal', false));
    return true;
  }

 
  public function getModel($name = 'fields', $prefix = 'GigCalModel', $config = array('ignore_request' => true))
  {
    $model = parent::getModel($name, $prefix, $config);
    return $model;
  }
}
