<?php
/**                               ______________________________________________
*                          o O   |                                              |
*                 (((((  o      <    Generated with Cook Self Service  V2.6.3   |
*                ( o o )         |______________________________________________|
* --------oOOO-----(_)-----OOOo---------------------------------- www.j-cook.pro --- +
* @version		0.2.9
* @package		jForms
* @subpackage	Forms
* @copyright	G. Tomaselli
* @author		Girolamo Tomaselli - http://bygiro.com - girotomaselli@gmail.com
* @license		GNU GPL v3 or later
*
*             .oooO  Oooo.
*             (   )  (   )
* -------------\ (----) /----------------------------------------------------------- +
*               \_)  (_/
*/

// no direct access
defined('_JEXEC') or die('Restricted access');



/**
* HTML View class for the Jforms component
*
* @package	Jforms
* @subpackage	Forms
*/
class JformsCkViewForms extends JformsClassView
{
	/**
	* Execute and display a template script.
	*
	* @access	public
	* @param	string	$tpl	The name of the template file to parse; automatically searches through the template paths.
	*
	* @return	mixed	A string if successful, otherwise a JError object.
	*
	* @since	11.1
	*/
	public function display($tpl = null)
	{
		$layout = $this->getLayout();
		if (!in_array($layout, array('default', 'modal')))
			JError::raiseError(0, $layout . ' : ' . JText::_('JERROR_LAYOUT_PAGE_NOT_FOUND'));

		$fct = "display" . ucfirst($layout);

		$this->addForkTemplatePath();
		$this->$fct($tpl);			
		$this->_parentDisplay($tpl);
	}

	/**
	* Execute and display a template : Forms
	*
	* @access	protected
	* @param	string	$tpl	The name of the template file to parse; automatically searches through the template paths.
	*
	* @return	mixed	A string if successful, otherwise a JError object.
	*
	* @since	11.1
	*/
	protected function displayDefault($tpl = null)
	{
		$document	= JFactory::getDocument();
		$this->title = JText::_("JFORMS_LAYOUT_FORMS");
		$document->title = $document->titlePrefix . $this->title . $document->titleSuffix;

		$this->model		= $model	= $this->getModel();
		$this->state		= $state	= $this->get('State');
		$state->set('context', 'forms.default');
		$this->items		= $items	= $this->get('Items');
		$this->canDo		= $canDo	= JformsHelper::getActions();
		$this->pagination	= $this->get('Pagination');
		$this->filters = $filters = $model->getForm('default.filters');
		$this->menu = JformsHelper::addSubmenu('forms', 'default');
		$lists = array();
		$this->lists = &$lists;

		
		$lists['enum']['forms.layout_type'] = JformsHelper::enumList('forms', 'layout_type');


		//Filters
		// Layout type
		$filters['filter_layout_type']->jdomOptions = array(
			'list' => JformsHelper::enumList('forms', 'layout_type')
		);

		// Sort by
		$filters['sortTable']->jdomOptions = array(
			'list' => $this->getSortFields('default')
		);

		// Limit
		$filters['limit']->jdomOptions = array(
			'pagination' => $this->pagination
		);

		$version = new JVersion();
		$exportIcon = 'download';
		$importIcon = 'database';
		// Joomla! 1.6 - 1.7 - 2.5
		if (version_compare($version->RELEASE, '2.5', '<=')){
			$exportIcon = 'extension';
			$importIcon = 'upload';
		}
		
		//Toolbar initialization

		JToolBarHelper::title(JText::_('JFORMS_LAYOUT_FORMS'), 'jforms_forms');
		// New
		if ($model->canCreate())
			CkJToolBarHelper::addNew('form.add', "JFORMS_JTOOLBAR_NEW");

		// Edit
		if ($model->canEdit())
			CkJToolBarHelper::editList('form.edit', "JFORMS_JTOOLBAR_EDIT");

		// Trash
		if ($model->canEditState())
			CkJToolBarHelper::trash('forms.trash', "JFORMS_JTOOLBAR_TRASH");

		// Archive
		if ($model->canEditState())
			CkJToolBarHelper::archiveList('forms.archive', "JFORMS_JTOOLBAR_ARCHIVE");

		// Delete
		if ($model->canDelete())
			CkJToolBarHelper::deleteList(JText::_('JFORMS_JTOOLBAR_ARE_YOU_SURE_TO_DELETE'), 'form.delete', "JFORMS_JTOOLBAR_DELETE");

		// Publish
		if ($model->canEditState())
			CkJToolBarHelper::publishList('forms.publish', "JFORMS_JTOOLBAR_PUBLISH");

		// Unpublish
		if ($model->canEditState())
			CkJToolBarHelper::unpublishList('forms.unpublish', "JFORMS_JTOOLBAR_UNPUBLISH");

		// Config
		if ($model->canAdmin())
			CkJToolBarHelper::custom( 'form.export', $exportIcon, 'JFORMS_JTOOLBAR_EXPORT', 'JFORMS_JTOOLBAR_EXPORT', true );
			CkJToolBarHelper::custom( 'form.import', $importIcon, 'JFORMS_JTOOLBAR_IMPORT', 'JFORMS_JTOOLBAR_IMPORT', false );
			CkJToolBarHelper::preferences('com_jforms');
	}

	/**
	* Execute and display a template : Forms
	*
	* @access	protected
	* @param	string	$tpl	The name of the template file to parse; automatically searches through the template paths.
	*
	* @return	mixed	A string if successful, otherwise a JError object.
	*
	* @since	11.1
	*/
	protected function displayModal($tpl = null)
	{
		$document	= JFactory::getDocument();
		$this->title = JText::_("JFORMS_LAYOUT_FORMS");
		$document->title = $document->titlePrefix . $this->title . $document->titleSuffix;

		$this->model		= $model	= $this->getModel();
		$this->state		= $state	= $this->get('State');
		$state->set('context', 'forms.modal');
		$this->items		= $items	= $this->get('Items');
		$this->canDo		= $canDo	= JformsHelper::getActions();
		$this->pagination	= $this->get('Pagination');
		$this->filters = $filters = $model->getForm('modal.filters');
		$this->menu = JformsHelper::addSubmenu('forms', 'modal');
		$lists = array();
		$this->lists = &$lists;

		
		$lists['enum']['forms.layout_type'] = JformsHelper::enumList('forms', 'layout_type');


		//Filters
		// Layout type
		$filters['filter_layout_type']->jdomOptions = array(
			'list' => JformsHelper::enumList('forms', 'layout_type')
		);

		// Limit
		$filters['limit']->jdomOptions = array(
			'pagination' => $this->pagination
		);

		//Toolbar initialization

		JToolBarHelper::title(JText::_('JFORMS_LAYOUT_FORMS'), 'jforms_forms');
		// New
		if ($model->canCreate())
			CkJToolBarHelper::addNew('form.add', "JFORMS_JTOOLBAR_NEW");

		// Edit
		if ($model->canEdit())
			CkJToolBarHelper::editList('form.edit', "JFORMS_JTOOLBAR_EDIT");

		// Trash
		if ($model->canEditState())
			CkJToolBarHelper::trash('forms.trash', "JFORMS_JTOOLBAR_TRASH");

		// Archive
		if ($model->canEditState())
			CkJToolBarHelper::archiveList('forms.archive', "JFORMS_JTOOLBAR_ARCHIVE");

		// Delete
		if ($model->canDelete())
			CkJToolBarHelper::deleteList(JText::_('JFORMS_JTOOLBAR_ARE_YOU_SURE_TO_DELETE'), 'form.delete', "JFORMS_JTOOLBAR_DELETE");

		// Publish
		if ($model->canEditState())
			CkJToolBarHelper::publishList('forms.publish', "JFORMS_JTOOLBAR_PUBLISH");

		// Unpublish
		if ($model->canEditState())
			CkJToolBarHelper::unpublishList('forms.unpublish', "JFORMS_JTOOLBAR_UNPUBLISH");

		// Config
		if ($model->canAdmin())
			CkJToolBarHelper::preferences('com_jforms');
	}

	/**
	* Returns an array of fields the table can be sorted by.
	*
	* @access	protected
	* @param	string	$layout	The name of the called layout. Not used yet
	*
	* @return	array	Array containing the field name to sort by as the key and display text as value.
	*
	* @since	3.0
	*/
	protected function getSortFields($layout = null)
	{
		return array(
			'a.ordering' => JText::_('JFORMS_FIELD_ORDERING'),
			'a.id' => JText::_('JFORMS_FIELD_ID'),
			'a.alias' => JText::_('JFORMS_FIELD_ALIAS'),
			'a.name' => JText::_('JFORMS_FIELD_NAME'),
			'a.published' => JText::_('JFORMS_FIELD_PUBLISHED'),
			'a.save_data_in_db' => JText::_('JFORMS_FIELD_SAVE_DATA_IN_DB'),
			'a.generate_pdf' => JText::_('JFORMS_FIELD_GENERATE_PDF'),
			'a.layout_type' => JText::_('JFORMS_FIELD_LAYOUT_TYPE')
		);
	}


}

// Load the fork
JformsHelper::loadFork(__FILE__);

// Fallback if no fork has been found
if (!class_exists('JformsViewForms')){ class JformsViewForms extends JformsCkViewForms{} }

