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
 * View class for a list of bands.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_gigcal
 * @since		1.5
 */
class GigCalViewBands extends JView
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

		// Count number of gigs for each band...
		$db =& JFactory::GetDBO();
		$db->setQuery('SELECT band_id, MAX(gigdate) AS lastGig, count(*) numGigs FROM #__gigcal_gigs WHERE published>0 GROUP BY band_id');
		$this->bandGigSummary = $db->loadAssocList('band_id');

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
		$document->addStyleDeclaration('.icon-48-bands {background-image: url(components/com_gigcal/media/images/bands-48x48.png);}');

		JToolBarHelper::title(JText::_('COM_GIGCAL_BANDMANAGER_TITLE'), 'bands');
//		if (count($user->getAuthorisedCategories('com_gigcal', 'core.create')) > 0) {
			JToolBarHelper::addNew('band.add','JTOOLBAR_NEW');
//		}
		if ($canDo->get('core.edit')) {
			JToolBarHelper::editList('band.edit','JTOOLBAR_EDIT');
		}
		if (true || $canDo->get('core.edit.state')) {

			JToolBarHelper::divider();
			JToolBarHelper::custom('bands.publish', 'publish.png', 'publish_f2.png','JTOOLBAR_PUBLISH', true);
			JToolBarHelper::custom('bands.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
			JToolBarHelper::custom('bands.featured', 'featured.png', 'featured_f2.png', 'JFEATURED', true);			

			JToolBarHelper::divider();
			JToolBarHelper::archiveList('bands.archive','JTOOLBAR_ARCHIVE');
			JToolBarHelper::custom('bands.checkin', 'checkin.png', 'checkin_f2.png', 'JTOOLBAR_CHECKIN', true);
		}
		if ($state->get('filter.published') == -2 && (true || $canDo->get('core.delete'))) {
			JToolBarHelper::deleteList('', 'bands.delete','JTOOLBAR_EMPTY_TRASH');
			JToolBarHelper::divider();
		} else if (true || $canDo->get('core.edit.state')) {
			JToolBarHelper::trash('bands.trash','JTOOLBAR_TRASH');
			JToolBarHelper::divider();
			JToolBarHelper::makeDefault('bands.setDefault', 'COM_GIGCAL_TOOLBAR_SET_DEFAULT');
			JToolBarHelper::divider();
		}
		if ($canDo->get('core.admin')) {
			JToolBarHelper::preferences('com_gigcal');
			JToolBarHelper::divider();
		}

		JToolBarHelper::help('JHELP_COMPONENTS_GIGCAL_LINKS');
	}
}
