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
* Jforms Submission Controller
*
* @package	Jforms
* @subpackage	Submission
*/
class JformsCkControllerSubmission extends JformsClassControllerItem
{
	/**
	* The context for storing internal data, e.g. record.
	*
	* @var string
	*/
	protected $context = 'submission';

	/**
	* The URL view item variable.
	*
	* @var string
	*/
	protected $view_item = 'submission';

	/**
	* The URL view list variable.
	*
	* @var string
	*/
	protected $view_list = 'submissions';

	/**
	* Constructor
	*
	* @access	public
	* @param	array	$config	An optional associative array of configuration settings.
	* @return	void
	*/
	public function __construct($config = array())
	{
		parent::__construct($config);
		$app = JFactory::getApplication();

	}

	/**
	* Override method when the author allowed to delete own.
	*
	* @access	protected
	* @param	array	$data	An array of input data.
	* @param	string	$key	The name of the key for the primary key; default is id..
	*
	* @return	boolean	True on success
	*/
	protected function allowDelete($data = array(), $key = id)
	{
		return parent::allowDelete($data, $key, array(
		'key_author' => 'created_by'
		));
	}

	/**
	* Override method when the author allowed to edit own.
	*
	* @access	protected
	* @param	array	$data	An array of input data.
	* @param	string	$key	The name of the key for the primary key; default is id..
	*
	* @return	boolean	True on success
	*/
	protected function allowEdit($data = array(), $key = id)
	{
		return parent::allowEdit($data, $key, array(
		'key_author' => 'created_by'
		));
	}

	/**
	* Method to cancel an element.
	*
	* @access	public
	* @return	void
	*/
	public function cancel()
	{
		$this->_result = $result = parent::cancel();
		$model = $this->getModel();

		//Define the redirections
		switch($this->getLayout() .'.'. $this->getTask())
		{
			case 'submission.cancel':
				$this->applyRedirection($result, array(
					'stay',
					'com_jforms.submissions.default'
				), array(
					'cid[]' => null
				));
				break;

			default:
				$this->applyRedirection($result, array(
					'stay',
					'com_jforms.submissions.default'
				));
				break;
		}
	}

	/**
	* Return the current layout.
	*
	* @access	protected
	* @param	bool	$default	If true, return the default layout.
	*
	* @return	string	Requested layout or default layout
	*/
	protected function getLayout($default = null)
	{
		if ($default === 'edit')
			return 'submission';

		if ($default)
			return 'submissiondetails';

		$jinput = JFactory::getApplication()->input;
		return $jinput->get('layout', 'submissiondetails', 'CMD');
	}

	/**
	* Function that allows child controller access to model data after the data
	* has been saved.
	*
	* @access	protected
	* @param	JModel	&$model	The data model object.
	* @param	array	$validData	The validated data.
	* @return	void
	*/
	protected function postSaveHook(&$model, &$validData = array(), $form = null)
	{

		$this->model = $model;

		$oldItem = $model->getItem();
		$validData['id'] = $oldItem->id;

		// save files
		$validData = $model->validate($form, $validData, null, true);

		$oldItem = (array)$oldItem;
		
		$oldItem['form_data'] = array_merge((array)$oldItem['form_data'],$validData);		
		if($model->save($oldItem)){
			// send emails
			$user = JFactory::getUser($oldItem['created_by']);
			$oldItem['user'] = $user;

			$oldItem['jforms_snapshot'] = JformsHelper::getjFieldsets($oldItem['jforms_snapshot'],false);
			$oldItem['jforms_snapshot'] = JformsHelper::getMainForm($oldItem['jforms_snapshot'],false);

			$model->processEmails($oldItem);
		}
	}

	/**
	* Method to save an element.
	*
	* @access	public
	* @return	void
	*/
	public function save()
	{
		CkJSession::checkToken() or CkJSession::checkToken('get') or jexit(JText::_('JINVALID_TOKEN'));
		//Check the ACLs
		$model = $this->getModel();
		$item = $model->getItem();
		$result = false;
		if ($model->canEdit($item, true))
		{
			$result = parent::save();
			//Get the model through postSaveHook()
			if ($this->model)
			{
				$model = $this->model;
				$item = $model->getItem();	
			}
		}
		else
			JError::raiseWarning( 403, JText::sprintf('ACL_UNAUTORIZED_TASK', JText::_('JFORMS_JTOOLBAR_SAVE')) );

		$this->_result = $result;
		
		$app = JFactory::getApplication();
		$jinput = $app->input;
		$form_id = $jinput->get('frm', null, 'INT');
	
		if($item->jforms_snapshot->redirect_after_submit != '' AND $result){
			$app->redirect($item->jforms_snapshot->redirect_after_submit);
		} else {
			// return to previous page
			if($item->page_url != ''){
				$app->redirect($item->page_url);
			}
		}
		
		//Define the redirections
		switch($this->getLayout() .'.'. $this->getTask())
		{
			case 'submission.save':
			default:
				$this->applyRedirection($result, array(
					'stay',
					'com_jforms.submissions.default'
				), array(
					'cid[]' => null,
					'frm' => $form_id
				));
				break;
		}
	}


}

// Load the fork
JformsHelper::loadFork(__FILE__);

// Fallback if no fork has been found
if (!class_exists('JformsControllerSubmission')){ class JformsControllerSubmission extends JformsCkControllerSubmission{} }

