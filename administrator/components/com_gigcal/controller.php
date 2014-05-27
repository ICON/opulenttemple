<?php
/**
 * @version	z	$Id: controller.php 20196 2011-01-09 02:40:25Z ian $
 * @package		Joomla.Administrator
 * @subpackage	com_gigcal
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');


/**
 * GigCal Controller
 *
 * @package		Joomla.Administrator
 * @subpackage	com_gigcal
 * @since		1.5
 */
class GigCalController extends JController
{
	/**
	 * Method to display a view.
	 *
	 * @param	boolean			$cachable	If true, the view output will be cached
	 * @param	array			$urlparams	An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return	JController		This object to support chaining.
	 * @since	1.5
	 */
	public function display($cachable = false, $urlparams = false)
	{
		require_once JPATH_COMPONENT.'/helpers/gigcal.php';

		// Load the submenu.
		JSubMenuHelper::addEntry(JText::_('COM_GIGCAL_GIGMANAGER_TITLE'), 'index.php?option=com_gigcal&view=gigs');
		JSubMenuHelper::addEntry(JText::_('COM_GIGCAL_BANDMANAGER_TITLE'), 'index.php?option=com_gigcal&view=bands');
		JSubMenuHelper::addEntry(JText::_('COM_GIGCAL_VENUEMANAGER_TITLE'), 'index.php?option=com_gigcal&view=venues');
		JSubMenuHelper::addEntry(JText::_('COM_GIGCAL_DATA_TITLE'), 'index.php?option=com_gigcal&view=data');
		JSubMenuHelper::addEntry(JText::_('COM_GIGCAL_CONFIG_TITLE'), 'index.php?option=com_gigcal&view=config');
		JSubMenuHelper::addEntry(JText::_('COM_GIGCAL_ABOUT_TITLE'), 'index.php?option=com_gigcal&view=about');

		JRequest::setVar('view', JRequest::getCmd('view', 'gigs'));

		parent::display($cachable, $urlparams);

		return $this;
	}
}
