<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 
/**
 * GigCal Config View
 */
class GigCalViewConfig extends JView
{
  protected $items;
  protected $pagination;
//  protected $state;

  /**
   * GigCal Config view display method
   * @return void
   */
  function display($tpl = null) 
  {
    $document = JFactory::getDocument();
    $document->addStyleDeclaration('.icon-48-gigcal {background-image: url(components/com_gigcal/media/images/config-48x48.png);}');
    $document->setTitle(JText::_('COM_GIGCAL_CONFIG_TITLE'));

//    JToolBarHelper::title(JText::_('COM_GIGCAL_CONFIG_TITLE'), 'gigcal');
//    JToolBarHelper::preferences('com_gigcal');
    JRequest::setVar('hidemainmenu', true);

    JToolBarHelper::apply('config.apply', 'JTOOLBAR_APPLY');
    JToolBarHelper::save('config.save', 'JTOOLBAR_SAVE');
    JToolBarHelper::cancel('config.cancel', 'JTOOLBAR_CANCEL');
//    JToolBarHelper::back();

 
    $db =& JFactory::GetDBO();
    $db->setQuery('SELECT * from #__gigcal_config WHERE active=1');
    $this->config = $db->LoadAssoc();

    $fields = JRequest::GetCmd('fields', 'general');
    if ($fields == 'general')
      $db->setQuery('SELECT id, fieldname, ordering, published FROM #__gigcal_menu_fields WHERE published=1 ORDER BY ordering');
    else
      $db->setQuery('SELECT id, fieldname, ordering, published FROM #__gigcal_'.$fields.'_fields ORDER BY ordering');
    $this->items = $db->LoadObjectList();

    jimport('joomla.html.pagination');
    $this->pagination = new JPagination(count($this->items), 0, 0);

    // Display the template
    parent::display($tpl);
  }
}


