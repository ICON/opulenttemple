<?php
// No direct access to this file
defined('_JEXEC') or die;
 
/**
 * GigCal component helper.
 */
abstract class GigCalHelper
{
  public static function addSubmenu($submenu) 
  {
    JSubMenuHelper::addEntry(JText::_('COM_GIGCAL_SUBMENU_MESSAGES'), 'index.php?option=com_gigcal', $submenu == 'messages');
    JSubMenuHelper::addEntry(JText::_('COM_GIGCAL_SUBMENU_CATEGORIES'), 'index.php?option=com_categories&view=categories&extension=com_gigcal', $submenu == 'categories');

    $document = JFactory::getDocument();
    $document->addStyleDeclaration('.icon-48-gigcal {background-image: url(../media/com_gigcal/images/gigcal-48x48.png);}');
  }

  public static function getActions($messageId = 0)
  {
    $user	= JFactory::getUser();
    $result	= new JObject;
 
    if (empty($messageId))
      $assetName = 'com_gigcal';
    else
      $assetName = 'com_gigcal.message.'.(int) $messageId;
 
    $actions = array('core.admin', 'core.manage', 'core.create', 'core.edit', 'core.delete');
    foreach ($actions as $action)
      $result->set($action, $user->authorise($action, $assetName));
 
    return $result;
  }

}

