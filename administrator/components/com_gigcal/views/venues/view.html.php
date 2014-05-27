<?php
/**
 * @version		$Id: view.html.php 20989 2011-03-18 09:19:41Z infograf768 $
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * View class for a list of venues.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_gigcal
 * @since		1.5
 */
class GigCalViewVenues extends JView
{
	protected $items;
	protected $pagination;
	protected $state;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		$this->state		= $this->get('State');
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');

		// Count number of gigs for each venue...
		$db =& JFactory::GetDBO();
		$db->setQuery('SELECT venue_id, MAX(gigdate) AS lastGig, count(*) numGigs FROM #__gigcal_gigs WHERE published>0 GROUP BY venue_id');
		$this->venueGigSummary = $db->loadAssocList('venue_id');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		$this->addToolbar();
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @since	1.6
	 */
	protected function addToolbar()
	{
		require_once JPATH_COMPONENT.'/helpers/gigcal.php';

		$state	= $this->get('State');
		$canDo	= GigCalHelper::getActions($state->get('filter.category_id'));
		$user	= JFactory::getUser();
		
		$document = JFactory::getDocument();
		$document->addStyleDeclaration('.icon-48-venues {background-image: url(components/com_gigcal/media/images/venues-48x48.png);}');

		JToolBarHelper::title(JText::_('COM_GIGCAL_VENUEMANAGER_TITLE'), 'venues');
//		if (count($user->getAuthorisedCategories('com_gigcal', 'core.create')) > 0) {
			JToolBarHelper::addNew('venue.add','JTOOLBAR_NEW');
//		}
		if ($canDo->get('core.edit')) {
			JToolBarHelper::editList('venue.edit','JTOOLBAR_EDIT');
		}
		if (true || $canDo->get('core.edit.state')) {

			JToolBarHelper::divider();
			JToolBarHelper::custom('venues.publish', 'publish.png', 'publish_f2.png','JTOOLBAR_PUBLISH', true);
			JToolBarHelper::custom('venues.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
			JToolBarHelper::custom('venues.featured', 'featured.png', 'featured_f2.png', 'JFEATURED', true);			

			JToolBarHelper::divider();
			JToolBarHelper::archiveList('venues.archive','JTOOLBAR_ARCHIVE');
			JToolBarHelper::custom('venues.checkin', 'checkin.png', 'checkin_f2.png', 'JTOOLBAR_CHECKIN', true);
		}
		if ($state->get('filter.published') == -2 && $canDo->get('core.delete')) {
			JToolBarHelper::deleteList('', 'venues.delete','JTOOLBAR_EMPTY_TRASH');
			JToolBarHelper::divider();
		} else if (true || $canDo->get('core.edit.state')) {
			JToolBarHelper::trash('venues.trash','JTOOLBAR_TRASH');
			JToolBarHelper::divider();
			JToolBarHelper::makeDefault('venues.setDefault', 'COM_GIGCAL_TOOLBAR_SET_DEFAULT');
			JToolBarHelper::divider();
		}
		if ($canDo->get('core.admin')) {
			JToolBarHelper::preferences('com_gigcal');
			JToolBarHelper::divider();
		}

		JToolBarHelper::help('JHELP_COMPONENTS_GIGCAL_LINKS');
	}
}
