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
 * View to edit a venue.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_gigcal
 * @since		1.5
 */
class GigCalViewVenue extends JView
{
	protected $state;
	protected $item;
	protected $form;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		$this->state	= $this->get('State');
		$this->item		= $this->get('Item');
		$this->form		= $this->get('Form');

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
		JRequest::setVar('hidemainmenu', true);

		$user		= JFactory::getUser();
		$isNew		= ($this->item->id == 0);
		$checkedOut	= !($this->item->checked_out == 0 || $this->item->checked_out == $user->get('id'));
		$canDo		= GigCalHelper::getActions($this->state->get('filter.category_id'), $this->item->id);
		$canCreate	= 1;//count($user->getAuthorisedCategories('com_gigcal', 'core.create'))

		$document = JFactory::getDocument();
		$document->addStyleDeclaration('.icon-48-venues {background-image: url(components/com_gigcal/media/images/venues-48x48.png);}');

		JToolBarHelper::title(JText::_('COM_GIGCAL_VENUEMANAGER_TITLE'), 'venues');

		// If not checked out, can save the item.
		if (!$checkedOut && ($canDo->get('core.edit')||($canCreate)))
		{
			JToolBarHelper::apply('venue.apply', 'JTOOLBAR_APPLY');
			JToolBarHelper::save('venue.save', 'JTOOLBAR_SAVE');
		}
		if (!$checkedOut && $canCreate){			
			JToolBarHelper::custom('venue.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);
		}
		// If an existing item, can save to a copy.
		if (!$isNew && ($canCreate > 0)) {
			JToolBarHelper::custom('venue.save2copy', 'save-copy.png', 'save-copy_f2.png', 'JTOOLBAR_SAVE_AS_COPY', false);
		}
		if (empty($this->item->id)) {
			JToolBarHelper::cancel('venue.cancel', 'JTOOLBAR_CANCEL');
		}
		else {
			JToolBarHelper::cancel('venue.cancel', 'JTOOLBAR_CLOSE');
		}

		JToolBarHelper::divider();
		JToolBarHelper::help('JHELP_COMPONENTS_GIGCAL_VENUE_EDIT');
	}
}
