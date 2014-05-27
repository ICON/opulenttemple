<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 
/**
 * GigCal About View
 */
class GigCalViewAbout extends JView
{
	/**
	 * GigCal About view display method
	 * @return void
	 */
	function display($tpl = null) 
	{
		$document = JFactory::getDocument();
		$document->addStyleDeclaration('.icon-48-gigcal {background-image: url(components/com_gigcal/media/images/about-48x48.png);}');
		$document->setTitle(JText::_('COM_GIGCAL_ABOUT_TITLE'));

		JToolBarHelper::back();

		JToolBarHelper::title(JText::_('COM_GIGCAL_ABOUT_TITLE'), 'gigcal');
		if (GigCalHelper::getActions()->get('core.admin')) 
			JToolBarHelper::preferences('com_gigcal');
 
		// Display the template
		parent::display($tpl);
	}
}

