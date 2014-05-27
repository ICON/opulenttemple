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
* Jforms Item Model
*
* @package	Jforms
* @subpackage	Classes
*/
class JformsCkModelSubmission extends JformsClassModelItem
{
	/**
	* List of all fields files indexes
	*
	* @var array
	*/
	protected $fileFields = array('pdf');

	/**
	* View list alias
	*
	* @var string
	*/
	protected $view_item = 'submission';

	/**
	* View list alias
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
		parent::__construct();
	}

	/**
	* Method to delete item(s).
	*
	* @access	public
	* @param	array	&$pks	Ids of the items to delete.
	*
	* @return	boolean	True on success.
	*/
	public function delete(&$pks)
	{
		if (!count( $pks ))
			return true;

		//Integrity : delete the files associated to this deleted item
		if (!$this->deleteFiles($pks, array(
												'pdf' => 'delete'
											))){
			JError::raiseWarning( 1303, JText::_("DEMO120_ALERT_ERROR_ON_DELETE_FILES") );
			return false;
		}

		if (!parent::delete($pks))
			return false;



		return true;
	}

	/**
	* Method to get the layout (including default).
	*
	* @access	public
	*
	* @return	string	The layout alias.
	*/
	public function getLayout()
	{
		$jinput = JFactory::getApplication()->input;
		return $jinput->get('layout', 'submissiondetails', 'STRING');
	}

	/**
	* Returns a Table object, always creating it.
	*
	* @access	public
	* @param	string	$type	The table type to instantiate.
	* @param	string	$prefix	A prefix for the table class name. Optional.
	* @param	array	$config	Configuration array for model. Optional.
	*
	* @return	JTable	A database object
	*
	* @since	1.6
	*/
	public function getTable($type = 'submission', $prefix = 'JformsTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	/**
	* Method to increment hits (check session and layout)
	*
	* @access	public
	* @param	array	$layouts	List of authorized layouts for hitting the object.
	*
	* @return	boolean	Null if skipped. True when incremented. False if error.
	*
	* @since	11.1
	*/
	public function hit($layouts = null)
	{
		return parent::hit(array('submissiondetails'));
	}

	/**
	* Method to get the data that should be injected in the form.
	*
	* @access	protected
	*
	* @return	mixed	The data for the form.
	*/
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_jforms.edit.submission.data', array());

		if (empty($data)) {
			//Default values shown in the form for new item creation
			$data = $this->getItem();

			// Prime some default values.
			if ($this->getState('submission.id') == 0)
			{
				$jinput = JFactory::getApplication()->input;

				$data->id = 0;
				$data->created_by = $jinput->get('filter_created_by', $this->getState('filter.created_by'), 'INT');
				$data->form_id = $jinput->get('filter_form_id', $this->getState('filter.form_id'), 'INT');
				$data->creation_date = null;
				$data->ip_address = null;
				$data->form_data = null;
				$data->jforms_snapshot = null;
				$data->pdf = null;
				$data->password = null;

			}
		}
		return $data;
	}

	/**
	* Prepare some additional derivated objects.
	*
	* @access	public
	* @param	object	&$item	The object to populate.
	* @return	void
	*
	* @since	Cook 2.0
	*/
	public function populateObjects(&$item)
	{
		if (!empty($item->form_data) && is_string($item->form_data))
		{
			$registry = new JRegistry;
			$registry->loadString($item->form_data);
			$item->form_data = $registry->toObject();
		}

		if (!empty($item->jforms_snapshot) && is_string($item->jforms_snapshot))
		{
			$registry = new JRegistry;
			$registry->loadString($item->jforms_snapshot);
			$item->jforms_snapshot = $registry->toObject();
		}
	
		parent::populateObjects($item);
	}

	/**
	* Method to auto-populate the model state.
	* 
	* This method should only be called once per instantiation and is designed to
	* be called on the first call to the getState() method unless the model
	* configuration flag to ignore the request is set.
	* 
	* Note. Calling getState in this method will result in recursion.
	*
	* @access	public
	* @param	string	$ordering	
	* @param	string	$direction	
	* @return	void
	*
	* @since	11.1
	*/
	public function populateState($ordering = null, $direction = null)
	{
		$app = JFactory::getApplication();
		$session = JFactory::getSession();
		$acl = JformsHelper::getActions();



		parent::populateState($ordering, $direction);
	}

	/**
	* Preparation of the query.
	*
	* @access	protected
	* @param	object	&$query	returns a filled query object.
	* @param	integer	$pk	The primary id key of the submission
	* @return	void
	*/
	protected function prepareQuery(&$query, $pk)
	{

		$acl = JformsHelper::getActions();

		//FROM : Main table
		$query->from('#__jforms_submissions AS a');



		//IMPORTANT REQUIRED FIELDS
		$this->addSelect(	'a.id,'
						.	'a.created_by');

		switch($this->getState('context', 'all'))
		{
			case 'submission.submission':

				//BASE FIELDS
				$this->addSelect(	'a.form_id,'
								.	'a.password');

				//SELECT
				$this->addSelect('_form_id_.name AS `_form_id_name`');
				$this->addSelect('_form_id_.description AS `_form_id_description`');

				//JOIN
				$this->addJoin('`#__jforms_forms` AS _form_id_ ON _form_id_.id = a.form_id', 'LEFT');

				break;

			case 'submission.submissiondetails':

				//BASE FIELDS
				$this->addSelect(	'a.creation_date,'
								.	'a.form_data,'
								.	'a.form_id,'
								.	'a.ip_address,'
								.	'a.password,'
								.	'a.jforms_snapshot,'
								.	'a.pdf');

				//SELECT
				$this->addSelect('_form_id_.name AS `_form_id_name`');
				$this->addSelect('_form_id_.description AS `_form_id_description`');
				$this->addSelect('_form_id_.generate_pdf AS `_form_id_generate_pdf`');
				$this->addSelect('_form_id_.options AS `_form_id_options`');
				
				//JOIN
				$this->addJoin('`#__jforms_forms` AS _form_id_ ON _form_id_.id = a.form_id', 'LEFT');

				break;
			case 'all':
				//SELECT : raw complete query without joins
				$query->select('a.*');
				break;
		}

		//WHERE : Item layout (based on $pk)
		$query->where('a.id = ' . (int) $pk);		//TABLE KEY

		//FILTER - Access for : Root table


		//SELECT : Instance Add-ons
		foreach($this->getState('query.select', array()) as $select)
			$query->select($select);

		//JOIN : Instance Add-ons
		foreach($this->getState('query.join.left', array()) as $join)
			$query->join('LEFT', $join);
	}

	/**
	* Prepare and sanitise the table prior to saving.
	*
	* @access	protected
	* @param	JTable	$table	A JTable object.
	*
	* @return	void	
	* @return	void
	*
	* @since	1.6
	*/
	protected function prepareTable($table)
	{
		$date = JFactory::getDate();


		if (empty($table->id))
		{
			//Creation date
			if (empty($table->creation_date))
				$table->creation_date = $date->toUnix();


			//Defines automatically the author of this element
			$table->created_by = JFactory::getUser()->get('id');
		}
		else
		{

		}

	}

	/**
	* Save an item.
	*
	* @access	public
	* @param	array	$data	The post values.
	*
	* @return	boolean	True on success.
	*/
	public function save($data)
	{
		$app = JFactory::getApplication();
		$jinput = $app->input;
		$form = $this->getForm();
		$jForm = $form->jForm;

		//Convert to unix Format (creation_date)
		if (isset($data['creation_date']))
			$data['creation_date'] = JformsHelperDates::getUnixTimestamp($data['creation_date'], array('d-m-Y H:i:s', 'Y-m-d H:i:s'));
		
		if($data['id'] <= 0){
			$data['ip_address'] = JformsHelper::get_ip_address();
			$data['page_url'] = $jinput->get('page_url', '', 'STRING');
			$data['page_title'] = $jinput->get('page_title', '', 'STRING');

			$formData = array();
			$formData['form_data'] = $data;
			unset($formData['form_data']['id']);		

			$formData['jforms_snapshot'] = $jForm;
			JformsHelper::triggerEvents('on_before_save',$formData);
			$data = array();
			$data['ip_address'] = $formData['form_data']['ip_address'];
			$data['form_id'] = $jForm->id;
			$data['form_data'] = $formData['form_data'];

			$data['jforms_snapshot'] = $formData['jforms_snapshot'] = (array)$jForm;
			
			if($jForm->generate_pdf){
				$data['pdf'] = $formData['pdf'] = $this->generatePdf($data);
			}			
		}

		// remove forms from jFieldsets
		foreach($data['jforms_snapshot']->fieldsets as $k => $v){
			unset($data['jforms_snapshot']->fieldsets[$k]->form);
		}
			
		// check fields for JSON data to store
		foreach($data as $key => $val){
			if(is_array($val)){				
				// remove CURRENT, REMOVE, VIEW file input
				$val = $this->removeSysInput($val);
				
				$registry = new JRegistry;
				$registry->loadArray($val);
				$data[$key] = (string) $registry;			
			}
		}

		//Some security checks
		$acl = JformsHelper::getActions();

		//Secure the author key if not allowed to change
		if (isset($data['created_by']) && !$acl->get('core.edit'))
			unset($data['created_by']);

		if($jForm->save_data_in_db){
			$saved = parent::save($data);
		} else {
			$saved = true;
		}

		if(!$saved){
			return false;
		}
		
		if($data['id'] <= 0){
			JformsHelper::triggerEvents('on_after_save',$formData);
			
			if($jForm->message_after_submit_ml != ''){
				$app->enqueueMessage($jForm->message_after_submit_ml, 'notice');
			}
		}
		
		return true;
	}

	protected function generatePdf($data){
		$config	= JComponentHelper::getParams( 'com_jforms' );
		$pdf_dir = $config->get('upload_dir_submissions_pdf', JPATH_COMPONENT .DS. 'files' .DS. 'submissions_pdf');

		$version = new JVersion();
		// Joomla! 1.6 - 1.7 - 2.5
		if (version_compare($version->RELEASE, '2.5', '<='))
		{	
			$displayData = $data;
			ob_start();
			include(JPATH_SITE .'/components/com_jforms/layouts/submission_pdf.php');
			$output = ob_get_contents();
			ob_end_clean();
		} else {
			$layout = new JLayoutFile('submission_pdf', JPATH_ROOT .'/components/com_jforms/layouts/');
			$output = $layout->render($data);
		}

		$pdf_content = JformsHelper::generatePdf($output);
		
		// create the folder/subfolders if it doesn't exist
		$jformUploadClass = new JformsClassFileUpload($pdf_dir);
		$pdf_dir = $jformUploadClass->uploadFolder;
		
		// save PDF file
		$pdf_filename = JformsHelper::generateRandomString(15) .'.pdf';
		file_put_contents($pdf_dir .DS. $pdf_filename,$pdf_content);

		return $pdf_filename;	
	}
	
	protected function preprocessForm(JForm $form, $data, $group = 'content')
	{
		$baseFolder = JPATH_COMPONENT .DS. 'fork' .DS. 'models' .DS. 'forms';
		$formFile = $baseFolder .DS. $this->view_item .'.xml';
		if (file_exists($formFile))
		{		
			$xml = simplexml_load_file($formFile);
			$form->load($xml, true);			
		}

		
		$app = JFactory::getApplication();		
		$jinput = $app->input;
		$form_id = $jinput->get('frm', '', 'INT');	
		
		$jForm = new stdClass;
		if($form_id > 0){
			// load jforms items
			$formModel = CkJModel::getInstance('form', 'JformsModel');
			$jForm = $formModel->getItem($form_id);				
			$jForm = JformsHelper::getjFieldsets($jForm);
			
			// load language files in JOOMLA
			$jForm = JformsHelper::getjFormLanguageFiles($jForm, true, true);
			$ml_fields = JformsHelper::getMultilangTables();
			$jForm = JformsHelper::getMlFields($jForm,$ml_fields['forms']);

			
			foreach($jForm->fieldsets as $fset){
				if(($fset->enabled != 'true' AND $fset->enabled != 1) OR !isset($fset->form)){
					continue;
				}
				
				// integrate forms
				if(!($form instanceof JForm)){
					$form = JForm::getInstance('com_jforms.main', $fset->form_file_content, array('control'=>'jform'));
					$form->addFieldPath(JPATH_SITE .DS. 'libraries/jdom/jform/fields');
					$form->addRulePath(JPATH_SITE .DS. 'libraries/jdom/jform/rules');				
				} else {
					// merge this form with the main form
					$xml = simplexml_load_string($fset->form_file_content);
					$form->load($xml, true);
				}
			}
		}	
		$form->jForm = $jForm;
		
		parent::preprocessForm($form, $data, $group);
	}
	
	function processEmails($data){
		$jForm = $data['jforms_snapshot'];
	
		$config	= JComponentHelper::getParams( 'com_jforms' );
		$emails_dir = $config->get('upload_dir_forms_emails', JPATH_COMPONENT .DS. 'files' .DS. 'forms_emails');
		$pdf_dir = $config->get('upload_dir_submissions_pdf', JPATH_COMPONENT .DS. 'files' .DS. 'submissions_pdf');
		$lang = JFactory::getLanguage();		
		$jForm_emails = JformsHelper::objectToArray($jForm->emails);

		$emails = array();
		$pdf_generated = $data['pdf'];
		foreach($jForm_emails as $em){
			
			if(!$em->enabled){
				continue;
			}
			
			if($em->language != $lang->getTag() AND $em->language != '*'){
				continue;
			}

			$skip_fields = array('enabled','html','attach_pdf_submitted_form','attachment_file');
			foreach($em as $k => $v){
				if(in_array($k,$skip_fields)){
					continue;
				}
				$em->$k = JformsHelper::replacer($v, $data);
			}

			$from = explode(',',$em->from);
			
			$vars = array(
				'recipients'=>'to',
				'recipients_cc'=>'cc',
				'recipients_bcc'=>'bcc',
				'recipients_reply_to'=>'reply_to'
			);
			
			$email = new stdClass;
			
			foreach($vars as $key => $var){
				$r_emails = array();
				$r_names = array();
				$recipients = explode(';',$em->$var);
				foreach($recipients as $r){
					$r = trim($r);
					$r = explode(',',$r);
					
					if(count($r) > 2){
						continue;
					}
					
					$r[0] = trim($r[0]);
					$r[1] = trim($r[1]);
					if($r[0] == ''){
						continue;
					}
					
					$r_emails[] = $r[0];
					$r_names[] = $r[1];				
				}
				
				$email->$key = array($r_emails,$r_names);
			}
			
			$email->sender = array($from[1], $from[0]);
			$email->subject = $em->subject;
			$email->body = $em->body;
			$email->html = $em->html;
			
			$email->attachment = array();
			if($em->attachment_file != ''){
				$email->attachment[] = JPath::clean($emails_dir . DS . $em->attachment_file);
			}

			if($em->attach_pdf_submitted_form){
				if($pdf_generated == ''){
					$pdf_generated = $this->generatePdf($data);
				}
				
				if($pdf_generated != ''){
					$email->attachment[] = JPath::clean($pdf_dir . DS . $pdf_generated); // PDF of the form
				}
			}			
			
			$emails[] = $email;
		}

		// remove the file if we don't save the data in the DB
		if(!$jForm->save_data_in_db AND $pdf_generated != '' AND $pdf_filename != ''){
			unlink($pdf_dir .DS. $pdf_filename);
		}

		// send all emails
		JformsHelper::sendEmails($emails);		
	}
}

// Load the fork
JformsHelper::loadFork(__FILE__);

// Fallback if no fork has been found
if (!class_exists('JformsModelSubmission')){ class JformsModelSubmission extends JformsCkModelSubmission{} }

