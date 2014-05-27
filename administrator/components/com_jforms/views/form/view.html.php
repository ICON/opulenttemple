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
* @subpackage	Form
*/
class JformsCkViewForm extends JformsClassView
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
		if (!in_array($layout, array('form')))
			JError::raiseError(0, $layout . ' : ' . JText::_('JERROR_LAYOUT_PAGE_NOT_FOUND'));

		$fct = "display" . ucfirst($layout);

		$this->addForkTemplatePath();
		$this->$fct($tpl);			
		$this->_parentDisplay($tpl);
	}

	/**
	* Execute and display a template : Form
	*
	* @access	protected
	* @param	string	$tpl	The name of the template file to parse; automatically searches through the template paths.
	*
	* @return	mixed	A string if successful, otherwise a JError object.
	*
	* @since	11.1
	*/
	protected function displayForm($tpl = null)
	{
		$document	= JFactory::getDocument();
		$this->title = JText::_("JFORMS_LAYOUT_FORM");
		$document->title = $document->titlePrefix . $this->title . $document->titleSuffix;

		// Initialiase variables.
		$this->model	= $model	= $this->getModel();
		$this->state	= $state	= $this->get('State');
		$state->set('context', 'form.form');
		$this->item		= $item		= $this->get('Item');
		$this->form		= $form		= $this->get('Form');
		$this->canDo	= $canDo	= JformsHelper::getActions($model->getId());
		$lists = array();
		$this->lists = &$lists;
		$this->item->fieldsets = JformsHelper::sort_on_field($this->item->fieldsets, 'ordering', 'ASC');

		$this->user = $user		= JFactory::getUser();
		$isNew		= ($model->getId() == 0);

		//Check ACL before opening the form (prevent from direct access)
		if (!$model->canEdit($item, true))
			$model->setError(JText::_('JERROR_ALERTNOAUTHOR'));

		// Check for errors.
		if (count($errors = $model->getErrors()))
		{
			JError::raiseError(500, implode(BR, array_unique($errors)));
			return false;
		}
		$jinput = JFactory::getApplication()->input;

		//Hide the component menu in item layout
		$jinput->set('hidemainmenu', true);

	
		$config	= JComponentHelper::getParams( 'com_jforms' );
		$files_dir = $config->get('upload_dir_forms_fieldsets', JPATH_SITE_JFORMS .DS. 'files' .DS. 'forms_fieldsets');

		$fake_form = false;
		if(!$isNew){
			$formModel = CkJModel::getInstance('form', 'JformsModel');
			$jForm = $formModel->getItem($item->id);
			
			$jForm = JformsHelper::getjFieldsets($jForm);
			// load language files in JOOMLA
			$jForm = JformsHelper::getjFormLanguageFiles($jForm, true);
			$ml_fields = JformsHelper::getMultilangTables();
			$jForm = JformsHelper::getMlFields($jForm,$ml_fields['forms']);
		
			foreach($jForm->fieldsets as $fset){
				if($fset->enabled != 'true' OR !isset($fset->form)){
					continue;
				}
				
				// integrate forms
				if(!($fake_form instanceof JForm)){
					$fake_form = JForm::getInstance('com_jforms.main', $fset->form_file_content, array('control'=>'jform'));
					$fake_form->addFieldPath(JPATH_SITE .DS. 'libraries/jdom/jform/fields');
					$fake_form->addRulePath(JPATH_SITE .DS. 'libraries/jdom/jform/rules');				
				} else {
					// merge this form with the main form
					$xml = simplexml_load_string($fset->form_file_content);
					$fake_form->load($xml, true);
				}
			}
		}

		$this->item->fake_form = $fake_form;
		
		
		
		//Toolbar initialization

		JToolBarHelper::title(JText::_('JFORMS_LAYOUT_FORM'), 'jforms_forms');
		// Save
		if (($isNew && $model->canCreate()) || (!$isNew && $item->params->get('access-edit')))
			CkJToolBarHelper::apply('form.apply', "JFORMS_JTOOLBAR_SAVE");
		// Save & Close
		if (($isNew && $model->canCreate()) || (!$isNew && $item->params->get('access-edit')))
			CkJToolBarHelper::save('form.save', "JFORMS_JTOOLBAR_SAVE_CLOSE");
		// Save & New
		if (($isNew && $model->canCreate()) || (!$isNew && $item->params->get('access-edit')))
			CkJToolBarHelper::save2new('form.save2new', "JFORMS_JTOOLBAR_SAVE_NEW");
		// Save to Copy
		if (($isNew && $model->canCreate()) || (!$isNew && $item->params->get('access-edit')))
			CkJToolBarHelper::save2copy('form.save2copy', "JFORMS_JTOOLBAR_SAVE_TO_COPY");
		// Trash
		if (!$isNew && $model->canEditState($item) && ($item->published != -2))
			CkJToolBarHelper::trash('forms.trash', "JFORMS_JTOOLBAR_TRASH", false);
		// Archive
		if (!$isNew && $model->canEditState($item) && ($item->published != 2))
			CkJToolBarHelper::custom('forms.archive', 'archive', 'archive',  "JFORMS_JTOOLBAR_ARCHIVE", false);


		// Delete
		if (!$isNew && $item->params->get('access-delete'))
			JToolbar::getInstance('toolbar')->appendButton('Confirm', JText::_('JFORMS_JTOOLBAR_ARE_YOU_SURE_TO_DELETE'), 'delete', "JFORMS_JTOOLBAR_DELETE", 'form.delete', false);

		// Publish
		if (!$isNew && $model->canEditState($item) && ($item->published != 1))
			CkJToolBarHelper::publish('forms.publish', "JFORMS_JTOOLBAR_PUBLISH");
		// Unpublish
		if (!$isNew && $model->canEditState($item) && ($item->published != 0))
			CkJToolBarHelper::unpublish('forms.unpublish', "JFORMS_JTOOLBAR_UNPUBLISH");
		// Cancel
		CkJToolBarHelper::cancel('form.cancel', "JFORMS_JTOOLBAR_CANCEL");
		$lists['enum']['forms.layout_type'] = JformsHelper::enumList('forms', 'layout_type');

		//Layout type
		$lists['select']['layout_type'] = new stdClass();
		$lists['select']['layout_type']->list = $lists['enum']['forms.layout_type'];
		$lists['select']['layout_type']->value = $item->layout_type;
	}


}

// Load the fork
JformsHelper::loadFork(__FILE__);

// Fallback if no fork has been found
if (!class_exists('JformsViewForm')){ class JformsViewForm extends JformsCkViewForm{} }

