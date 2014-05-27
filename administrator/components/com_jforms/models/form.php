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
* Jforms Item Model
*
* @package	Jforms
* @subpackage	Classes
*/
class JformsCkModelForm extends JformsClassModelItem
{
	/**
	* List of all fields files indexes
	*
	* @var array
	*/
	protected $fileFields = array('language_file');

	/**
	* View list alias
	*
	* @var string
	*/
	protected $view_item = 'form';

	/**
	* View list alias
	*
	* @var string
	*/
	protected $view_list = 'forms';

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

			$fields = array(
				'emails.{ID}.attachment_file' => 'delete', 
				'events.{ID}.file' => 'delete', 
				'fieldsets.{ID}.form_file' => 'delete' 
			);
			
			$installedLanguages = JformsHelper::getInstalledLanguages();
			$fields['language_file'] = 'delete';
			foreach($installedLanguages as $lg){
				$var = 'language_file_'. strtolower(str_replace('-','', $lg->lang_code));
				$fields[$var] = 'delete';
			}
			
		//Integrity : delete the files associated to this deleted item	
		if (!$this->deleteFiles($pks, $fields)){
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
		return $jinput->get('layout', 'form', 'STRING');
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
	public function getTable($type = 'form', $prefix = 'JformsTable', $config = array())
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
		return parent::hit(array());
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
		$data = JFactory::getApplication()->getUserState('com_jforms.edit.form.data', array());

		if (empty($data)) {
			//Default values shown in the form for new item creation
			$data = $this->getItem();

			// Prime some default values.
			if ($this->getState('form.id') == 0)
			{
				$jinput = JFactory::getApplication()->input;

				$data->id = 0;
				$data->name = null;
				$data->alias = null;
				$data->description = null;
				$data->fieldsets = null;
				$data->message_after_submit = null;
				$data->language_file = null;
				$data->emails = null;
				$data->redirect_after_submit = null;
				$data->events = null;
				$data->save_data_in_db = 1;
				$data->generate_pdf = 1;
				$data->layout_type = $jinput->get('filter_layout_type', $this->getState('filter.layout_type','wizard'), 'STRING');
				$data->options = null;
				$data->published = 1;
				$data->access = $jinput->get('filter_access', $this->getState('filter.access',1), 'INT');
				$data->ordering = null;

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
		if (!empty($item->fieldsets) && is_string($item->fieldsets))
		{
			$registry = new JRegistry;
			$registry->loadString($item->fieldsets);
			$item->fieldsets = (array)$registry->toObject();
		}

		if (!empty($item->emails) && is_string($item->emails))
		{
			$registry = new JRegistry;
			$registry->loadString($item->emails);
			$item->emails = (array)$registry->toObject();
		}

		if (!empty($item->events) && is_string($item->events))
		{
			$registry = new JRegistry;
			$registry->loadString($item->events);
			$item->events = (array)$registry->toObject();
		}

		if (!empty($item->options) && is_string($item->options))
		{
			$registry = new JRegistry;
			$registry->loadString($item->options);
			$item->options = (array)$registry->toObject();
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

		//Only show the published items
		if (!$acl->get('core.admin') && !$acl->get('core.edit.state'))
			$this->setState('filter.published', 1);
	}

	/**
	* Preparation of the query.
	*
	* @access	protected
	* @param	object	&$query	returns a filled query object.
	* @param	integer	$pk	The primary id key of the form
	* @return	void
	*/
	protected function prepareQuery(&$query, $pk)
	{

		$acl = JformsHelper::getActions();

		//FROM : Main table
		$query->from('#__jforms_forms AS a');



		//IMPORTANT REQUIRED FIELDS
		$this->addSelect(	'a.id,'
						.	'a.access,'
						.	'a.published');

		switch($this->getState('context', 'all'))
		{
			case 'form.form':

				//BASE FIELDS
				$this->addSelect(	'a.alias,'
								.	'a.description,'
								.	'a.emails,'
								.	'a.events,'
								.	'a.fieldsets,'
								.	'a.generate_pdf,'
								.	'a.language_file,'
								.	'a.layout_type,'
								.	'a.message_after_submit,'
								.	'a.name,'
								.	'a.options,'
								.	'a.redirect_after_submit,'
								.	'a.save_data_in_db');

				//SELECT
				$this->addSelect('_access_.title AS `_access_title`');

				//JOIN
				$this->addJoin('`#__viewlevels` AS _access_ ON _access_.id = a.access', 'LEFT');

				break;
			case 'all':
				//SELECT : raw complete query without joins
				$query->select('a.*');
				break;
		}

		//WHERE : Item layout (based on $pk)
		$query->where('a.id = ' . (int) $pk);		//TABLE KEY

		//FILTER - Access for : Root table
		$whereAccess = $wherePublished = true;
		$allowAuthor = false;
		$this->prepareQueryAccess('a', $whereAccess, $wherePublished, $allowAuthor);
		$query->where("$whereAccess AND $wherePublished");

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
			// Set ordering to the last item if not set
			$conditions = $this->getReorderConditions($table);
			$conditions = (count($conditions)?implode(" AND ", $conditions):'');
			$table->ordering = $table->getNextOrder($conditions);
		}
		else
		{

		}
		
		// set/check UNIQUE alias
		if($table->alias == ''){
			$db = JFactory::getDBO();
			
			$extra = '';
			if($table->id > 0){
				$extra = 'AND id <> '. $table->id;
			}
			do{
				$table->alias = JformsHelper::generateRandomString(10);
			
				$query = "SELECT alias "
					. " FROM #__jforms_forms "
					. " WHERE alias ='". $table->alias ."' "
					. $extra;
				$db->setQuery($query);
				$aliases = $db->loadColumn();
			}while(count($aliases) > 0);
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
		// check fields for JSON data to store
		foreach($data as $key => $val){
			if(is_array($val)){				
				// remove CURRENT and REMOVE file input
				$val = $this->removeSysInput($val);
				
				$registry = new JRegistry;
				$registry->loadArray($val);
				$data[$key] = (string) $registry;			
			}
		}
		
		//Some security checks
		$acl = JformsHelper::getActions();

		//Secure the published tag if not allowed to change
		if (isset($data['published']) && !$acl->get('core.edit.state'))
			unset($data['published']);

		//Secure the access key if not allowed to change
		if (isset($data['access']) && !$acl->get('core.edit'))
			unset($data['access']);

		if (parent::save($data)) {
			return true;
		}
		return false;


	}


	public function export($pks)
	{
		if (!count($pks))
			return;

		$app = JFactory::getApplication();
		$jinput = $app->input;
			
		JArrayHelper::toInteger($pks);
		$db = JFactory::getDBO();
		$config	= JComponentHelper::getParams( 'com_jforms' );
		$emails_dir = $config->get('upload_dir_forms_emails', JPATH_SITE .DS. 'components' .DS. 'com_jforms' .DS. 'files' .DS. 'forms_emails');
		$events_dir = $config->get('upload_dir_forms_events', JPATH_SITE .DS. 'components' .DS. 'com_jforms' .DS. 'files' .DS. 'forms_events');

		// load jforms items
		$formsModel = CkJModel::getInstance('forms', 'JformsModel');
		$pks = implode(', ', $pks);
		$formsModel->addWhere('a.id IN ( '. $pks .' )');
		$items = $formsModel->getItems();

		foreach($items as &$it){		
			// get fieldsets data
			$it = JformsHelper::getjFieldsets($it,true, false);
			
			// get language files
			$it = JformsHelper::getjFormLanguageFiles($it, true, false);
			
			// get attached files to emails
			foreach($it->emails as &$em){
				$file = $emails_dir .DS. $em->attachment_file;
				
				if($em->attachment_file != '' AND is_file($file)){
					$em->attachment_file_content = file_get_contents($file);
				}
			}
			
			// get scripts
			foreach($it->events as &$ev){
				$file = $events_dir .DS. $ev->file;
				
				if($ev->file != '' AND is_file($file)){
					$ev->file_content = file_get_contents($file);
				}
			}			
		}

		$output = base64_encode(serialize($items));

		$now = date ("d-m-Y__H_i_s", time());		
		$filename = 'jForms_backup_'. $now ;	
		header('Content-Disposition: attachment;filename="'. $filename . '.jforms"');
		header("Content-type: text/jforms");
		header("Pragma: no-cache");
		header("Expires: 0");

		ob_clean();
		echo $output;
		jexit();
	}
	
	public function import($file, $type = 'add')
	{
		$report = array();
		// TO DO file checking		
		
		$upload_content = file_get_contents($file['tmp_name']);
		$upload_content = unserialize(base64_decode($upload_content));
	
		$db = JFactory::getDBO();
		$app = JFactory::getApplication();
		$config	= JComponentHelper::getParams( 'com_jforms' );
		$dirs = array();
		$dirs['emails'] = $config->get('upload_dir_forms_emails', JPATH_SITE .DS. 'components' .DS. 'com_jforms' .DS. 'files' .DS. 'forms_emails');
		$dirs['events'] = $config->get('upload_dir_forms_events', JPATH_SITE .DS. 'components' .DS. 'com_jforms' .DS. 'files' .DS. 'forms_events');
		$dirs['fieldsets'] = $config->get('upload_dir_forms_fieldsets', JPATH_SITE .DS. 'components' .DS. 'com_jforms' .DS. 'files' .DS. 'forms_fieldsets');
		
		$language_file_dir = $config->get('upload_dir_forms_language_file', JPATH_SITE .DS. 'components' .DS. 'com_jforms' .DS. 'files' .DS. 'forms_language_file');

		$installedLanguages = JformsHelper::getInstalledLanguages();
		$lang_prefix = array('default'=>'');
		foreach($installedLanguages as $lg){
			$lang_prefix[$lg->lang_code] = '_'. strtolower(str_replace('-','', $lg->lang_code));
		}

		// remove all old data
		if($type == 'clean'){
			// get all IDS
			$query = "SELECT id "
				. " FROM #__jforms_forms ";
			$db->setQuery($query);
			$ids = $db->loadColumn();
			
			// delete ALL forms
			$formModel = CkJModel::getInstance('form', 'JformsModel');
			$formModel->delete($ids);
			
			// reset autoincrement
			$query = "TRUNCATE TABLE #__jforms_forms ";
			$db->setQuery($query);
			$db->query();
		}
		
		$report['imported'] = 0;
		$report['errors'] = array();
		foreach($upload_content as $rawIt){
			$errors = array();
			$item_info = 'Item: alias '. $rawIt->alias;
			// set current state to NEW item
			$formModel = CkJModel::getInstance('form', 'JformsModel');
			$app->setUserState('com_jforms.edit.form.data', array());
			$this->setState('form.id',0);
			
			// get default jForm item
			$default_item = $this->loadFormData();
			
			$ml_fields = JformsHelper::getMultilangTables();			

			// parse data with default item
			foreach($default_item as $k => $v){
				if(in_array($k,$ml_fields['forms'])){
					// add multilanguage fields
					foreach($lang_prefix as $lg => $pre){
						$langVar = $k . $pre;
						if(isset($rawIt->$langVar)){
							$default_item->$langVar = $rawIt->$langVar;
						}
					}
				} else {
					if(isset($rawIt->$k)){
						$default_item->$k = $rawIt->$k;
					}
				}
			}

			// remove extra data from Item
			unset($default_item->params);
			$fields_to_remove = array(
				'fieldsets'=> array('form_file_content'),
				'emails'=> array('attachment_file_content'),
				'events'=> array('file_content')
			);
						
			foreach($fields_to_remove as $fieldName => $subFields){
				$elements = array();
				foreach($default_item->$fieldName as $subIt){
					$newSubIt = new stdClass;
					
					foreach($subIt as $key => $val){
						if(in_array($key,$subFields)){
							continue;
						}
						$newSubIt->$key = $val;
					}
					$elements[] = $newSubIt;
				}
				
				$default_item->$fieldName = $elements;
			}
			
			// switch type -> save
			$alias = $default_item->alias;			
			$saved = false;
			switch($type){					
				case 'replace':
					// get jForm ID with same alias
					$query = "SELECT * "
						. " FROM #__jforms_forms "
						. " WHERE alias ='". $default_item->alias ."'";
					$db->setQuery($query);
					$existing_item = $db->loadObject();
					
					if($existing_item->id > 0){
						// assign same ID of the existing item
						$default_item->id = $existing_item->id;
						
						// remove old item and related files
						$formModel->delete(array($existing_item->id));						
					} else {
						$default_item->id = 0;
					}
					
					// save
					if($formModel->save((array)$default_item)){
						// saved!
						$saved = true;						
					} else {
						// NOT saved!
						$err = 'ERROR on saving into the database: '. $model->getError();
					}
						
					break;
					
				case 'clean':				
					// add empty item with imported id
					if($default_item->id > 0){
						$query = "INSERT IGNORE INTO #__jforms_forms (id) VALUES (". $default_item->id .")";
						$db->setQuery($query);
						$db->query();
					}
				
					if($formModel->save((array)$default_item)){
						// saved!
						$saved = true;
					} else {
						// NOT saved!
						$err = JText::_("JFORMS_ERROR_SAVING_INTO_DB") . $model->getError();
					}
					break;
					
					
				case 'add':
				default:
					// reset id and alias, automatically created later by the model
					$default_item->id = 0;
					$default_item->alias = '';

					if($formModel->save((array)$default_item)){
						// saved!
						$saved = true;
					} else {
						// NOT saved!
						$err = 'ERROR on saving into the database: '. $model->getError();
					}

					break;					
			}

			if(!$saved){
				$errors[] = $item_info .' - '. $err;				
				continue;
			}
			
			// get just saved item
			$savedItem = $formModel->getItem();

			// add multilanguage fields
			foreach($lang_prefix as $lg => $pre){
				$langVar = 'language_file' . $pre;
				$langVarContent = $langVar . '_content';
				
				if(!isset($rawIt->$langVar) OR $rawIt->$langVar == '' 
					OR !isset($rawIt->$langVarContent) OR $rawIt->$langVarContent == ''){
					continue;
				}
				
				// save file
				$dir = $language_file_dir . DS .'language'. DS . $lg;
				$filePath = $dir .DS. basename($rawIt->$langVar);
				JformsHelper::getUniquePath($filePath);
				
				// set folder if doesn't exist
				$uploadClass = new JformsClassFileUpload($dir);
				
				if(file_put_contents($filePath, $rawIt->$langVarContent)){
					$savedItem->$langVar = 'language' . DS . $lg . DS . basename($filePath);
				} else {
					$errors[] = $item_info .' - '. JText::sprintf('JFORMS_SAVE_FILE_ERROR', 'language_file', $filePath);
				}
			}
				
			// save subitems files
			$item_objJson_file_fields = array(
				'fieldsets'=> array('form_file'),
				'emails'=> array('attachment_file'),
				'events'=> array('file')
			);
		
			foreach($item_objJson_file_fields as $key => $fields){				
				foreach($fields as $fi){
					$fi_content = $fi .'_content';

					$rawElements = $rawIt->$key;
					$savedElements = JformsHelper::objectToArray($savedItem->$key);
					
					$keys = array_keys($savedItem->$key);
					foreach($keys as $ki){				
						if(!isset($savedElements[$ki]->$fi) OR $savedElements[$ki]->$fi == '' 
							OR !isset($rawElements[$ki]->$fi_content) OR $rawElements[$ki]->$fi_content == ''){
							continue;
						}
						
						// save file
						$dir = JPath::clean($dirs[$key] . DS );
						$filePath = $dir . $savedElements[$ki]->$fi;
						JformsHelper::getUniquePath($filePath);

						// set folder if doesn't exist
						$uploadClass = new JformsClassFileUpload($dir);
				
						if(file_put_contents($filePath, $rawElements[$ki]->$fi_content)){
							$savedElements[$ki]->$fi = str_replace($dir,'',$filePath);
						} else {
							$errors[] = $item_info .' - '. JText::sprintf('JFORMS_SAVE_FILE_ERROR', $key, $filePath);
						}
					}
					$savedItem->$key = $savedElements;
				}
			}
			
			// saved again some modification on the filenames
			$formModel->save((array)$savedItem);
			
			// add report
			if(count($errors) == 0){
				$report['imported'] += 1; 
			} else {
				$report['errors'] = array_merge($report['errors'],$errors);
			}
		}

		return $report;
	}

}

// Load the fork
JformsHelper::loadFork(__FILE__);

// Fallback if no fork has been found
if (!class_exists('JformsModelForm')){ class JformsModelForm extends JformsCkModelForm{} }

