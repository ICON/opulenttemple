<?php
/**                               ______________________________________________
*                          o O   |                                              |
*                 (((((  o      <    Generated with Cook Self Service  V2.6.3   |
*                ( o o )         |______________________________________________|
* --------oOOO-----(_)-----OOOo---------------------------------- www.j-cook.pro --- +
* @version		0.2.9
* @package		jForms
* @subpackage	Submissions
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
* @subpackage	Submission
*/
class JformsCkViewSubmission extends JformsClassView
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
		if (!in_array($layout, array('submission', 'submissiondetails', 'ajax'))) 
			JError::raiseError(0, $layout . ' : ' . JText::_('JERROR_LAYOUT_PAGE_NOT_FOUND'));

		$fct = "display" . ucfirst($layout);

		$this->addForkTemplatePath();
		$this->$fct($tpl);			
		$this->_parentDisplay($tpl);
	}


	/**
	* Execute and display ajax queries
	*
	* @access	protected
	* @param	string	$tpl	The name of the template file to parse; automatically searches through the template paths.
	*
	* @return	mixed	A string if successful, otherwise a JError object.
	*
	* @since	11.1
	*/
	protected function displayAjax($tpl = null)
	{	
		CkJSession::checkToken() or CkJSession::checkToken('get') or jexit(JText::_('JINVALID_TOKEN'));
		
		
		$jinput = new JInput;
		$render = $jinput->get('render', null, 'CMD');

		$data = $jinput->get('frmData',array(),'ARRAY');
echo '<pre>';
print_r($data);
echo '</pre>';
jexit();
		$this->model	= $model	= CkJModel::getInstance('submission', 'JformsModel');
		$db = JFactory::getDBO();
		
		switch($render)
		{
			case 'savestep':
				break;
		}		
		
		jexit();
	}
	
	
	/**
	* Execute and display a template : Submission
	*
	* @access	protected
	* @param	string	$tpl	The name of the template file to parse; automatically searches through the template paths.
	*
	* @return	mixed	A string if successful, otherwise a JError object.
	*
	* @since	11.1
	*/
	protected function displaySubmission($tpl = null)
	{
		$app = JFactory::getApplication();
		$jinput = $app->input;
		$form_id = $jinput->get('frm', null, 'INT');
	
		// Initialiase variables.
		$this->model	= $model	= CkJModel::getInstance('submission', 'JformsModel');
		
		$this->state	= $state	= $this->get('State');
		if(!$form_id){
			$state->set('jforms.form', null);
			
			$link = 'index.php';
			$msg = JText::_("JFORMS_MISSING_FORM_ID");
			$app->redirect($link, $msg, $msgType='error');
			return false;
		}		
		
		$state->set('jforms.form', $form_id);
		
		$state->set('context', 'submission.submission');
		$this->item		= $item		= $this->get('Item');
		$this->form		= $form		= $this->get('Form');

		if(!($form instanceof JForm)){
			$msg = JText::_("JFORMS_FORM_EMPTY");
			$app->enqueueMessage($msg, 'error');
			return false;
		}
		
		$document	= JFactory::getDocument();
		$this->title = $form->jForm->name_ml;
		$this->description = $form->jForm->description_ml;
		
		$active_menu = $app->getMenu()->getActive();
		if(!empty($active_menu)){
			$page_title = $active_menu->params->get("page_title");
		}
		
		if(!isset($page_title) OR $page_title == ''){
			$page_title = $document->titlePrefix . $this->title . $document->titleSuffix;
		}
		$document->title = $page_title;


	}

	/**
	* Execute and display a template : Submission details
	*
	* @access	protected
	* @param	string	$tpl	The name of the template file to parse; automatically searches through the template paths.
	*
	* @return	mixed	A string if successful, otherwise a JError object.
	*
	* @since	11.1
	*/
	protected function displaySubmissiondetails($tpl = null)
	{		
		$document	= JFactory::getDocument();
		$this->title = JText::_("JFORMS_LAYOUT_SUBMISSION_DETAILS");
		$document->title = $document->titlePrefix . $this->title . $document->titleSuffix;

		// Initialiase variables.
		$this->model	= $model	= $this->getModel();
		$this->state	= $state	= $this->get('State');
		$state->set('context', 'submission.submissiondetails');
		$this->item		= $item		= $this->get('Item');
		$this->canDo	= $canDo	= JformsHelper::getActions($model->getId());
		$lists = array();
		$this->lists = &$lists;

		$user		= JFactory::getUser();
		$isNew		= ($model->getId() == 0);

		//Check ACL before opening the view (prevent from direct access)
		if (!$model->canAccess($item))
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

		//Toolbar initialization




	}


}

// Load the fork
JformsHelper::loadFork(__FILE__);

// Fallback if no fork has been found
if (!class_exists('JformsViewSubmission')){ class JformsViewSubmission extends JformsCkViewSubmission{} }

