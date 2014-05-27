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


		// delete all the files with the specified ID
		
		$config = JComponentHelper::getParams('com_jforms');
		$attached_files_folder = $config->get("upload_dir_submissions_attached_files", JPATH_SITE .DS. "components" .DS. "com_jforms" .DS. "files" .DS. "submissions_attached_files");
		
		if(file_exists($attached_files_folder)){
			try {
				// get all files in the submission files folder
				$iterator = new RecursiveIteratorIterator(
					new RecursiveDirectoryIterator($attached_files_folder),
					RecursiveIteratorIterator::SELF_FIRST
				);
			} catch (Exception $e) {
				$iterator = array();
			}
		}
		
		foreach ($iterator as $file)
		{
			$fileName = $file->getFilename();

			if (!$file->isFile()){
				continue;
			}
			
			$file_id = explode('_',$fileName);
			$file_id = $file_id[0];
			if(in_array($file_id,$pks)){
				unlink($file->getPath() . '/' . $fileName);
			}
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
		return $jinput->get('layout', 'submission', 'STRING');
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
		return parent::hit(array('submission'));
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
			$item->form_data = (array)$registry->toObject();
		}

		if (!empty($item->jforms_snapshot) && is_string($item->jforms_snapshot))
		{
			$registry = new JRegistry;
			$registry->loadString($item->jforms_snapshot);
			$item->jforms_snapshot = (array)$registry->toObject();
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
				$this->addSelect(	'a.*'); 

				//SELECT
				$this->addSelect('_created_by_.username AS `_created_by_username`');
				$this->addSelect('_form_id_.name AS `_form_id_name`');

				//JOIN
				$this->addJoin('`#__users` AS _created_by_ ON _created_by_.id = a.created_by', 'LEFT');
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
	
		//Convert to unix Format (creation_date)
		if (isset($data['creation_date']))
			$data['creation_date'] = JformsHelperDates::getUnixTimestamp($data['creation_date'], array('d-m-Y H:i:s', 'Y-m-d H:i:s'));

		//Some security checks
		$acl = JformsHelper::getActions();

		//Secure the author key if not allowed to change
		if (isset($data['created_by']) && !$acl->get('core.edit'))
			unset($data['created_by']);

		if (parent::save($data)) {
			return true;
		}
		return false;


	}

	public function export($pks)
	{
		if (!count($pks))
			return;

		$jinput = JFactory::getApplication()->input;
		$format = $jinput->get('export_format', '', 'STRING');
			
		JArrayHelper::toInteger($pks);
		$db = JFactory::getDBO();
		$config	= JComponentHelper::getParams( 'com_jforms' );

		$errors = array();
		$table = $this->getTable();
		//Get all indexes for all fields
		$query = "SELECT s.id, s.form_id, s.form_data, f.name AS form_name "
			. " FROM #__jforms_submissions AS s "
			. " LEFT JOIN #__jforms_forms AS f ON s.form_id = f.id"
			. ' WHERE s.id IN ( '.implode(', ', $pks) .' )';
		$db->setQuery($query);
		$items = $db->loadAssocList();

		// get current error reporting
		$error_reporting = error_reporting();
		
		// set NO error reporting
		error_reporting(0);

		if($format != 'xml'){
			$items = JformsHelper::groupArrayByValue($items, 'form_id');
			/** Include PHPExcel */
			$rootLibraries = JPATH_SITE .DS.'libraries'.DS.'librariesbygiro';
			$helper = JPath::clean($rootLibraries .DS.'excel'.DS.'PHPExcel.php');
			if(file_exists($helper)){
				require_once($helper);
			} else {
				$app->enqueueMessage('missing PHPExcel library', 'error');
				return false;
			}
		
			// Create new PHPExcel object
			$objPHPExcel = new PHPExcel();

			// Set document properties
			$objPHPExcel->getProperties()->setCreator("jForms")
										 ->setLastModifiedBy("")
										 ->setTitle("jForms submissions")
										 ->setSubject("jForms submissions")
										 ->setDescription("")
										 ->setKeywords("")
										 ->setCategory("");

			$sheet = 0;
			foreach($items as $form_id => $objs){		
				$first_ele = array_shift(array_values($objs));			
				
				if($sheet > 0){
					$objPHPExcel->createSheet(NULL, $sheet);
					$objPHPExcel->setActiveSheetIndex($sheet);
				}
				$excel_sheet = $objPHPExcel->setActiveSheetIndex($sheet);
				
				// set name
				$sheetName = $first_ele['form_name'];
				$excel_sheet->setTitle($sheetName);
				
				// create header
				$header = array();
				foreach($objs as $k => $ob){
					$objs[$k]['form_data'] = json_decode($ob['form_data']);
				
					foreach($objs[$k]['form_data'] as $key => $value){
						$header[$key] = $key;
					}
				}

				$col = -1;				
				foreach($header as $key){
					$excel_sheet->setCellValueByColumnAndRow(++$col, 1, $key);
				}

				// create clean rows
				$row = 2;
				foreach($objs as $k => $ob){
					$col = -1;
					foreach($header as $key){
						$value = $objs[$k]['form_data']->$key;
						$excel_sheet->setCellValueByColumnAndRow(++$col, $row, $value);
					}
					
					$row++;
				}

				$sheet++;
			}

			// Set active sheet index to the first sheet, so Excel opens this as the first sheet
			$objPHPExcel->setActiveSheetIndex(0);

		}
		
		$now = date ("d-m-Y__H_i_s", time());		
		$filename = 'jForms_submissions_'. $now ;	
		
		switch($format){
			case 'xls':
				// Redirect output to a client’s web browser (Excel5)
				header('Content-Type: application/vnd.ms-excel');
				
				header('Cache-Control: max-age=0');
				// If you're serving to IE 9, then the following may be needed
				header('Cache-Control: max-age=1');

				// If you're serving to IE over SSL, then the following may be needed
				header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
				header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
				header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
				header ('Pragma: public'); // HTTP/1.0

				$format = 'Excel5';
				$extension = '.xls';
				break;
				
			case 'xlsx':
				// Redirect output to a client’s web browser (Excel2007)
				header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
				header('Cache-Control: max-age=0');
			
				$format = 'Excel2007';
				$extension = '.xlsx';
				break;
				
			case 'pdf':
				$rendererName = PHPExcel_Settings::PDF_RENDERER_MPDF;
				$rendererLibrary = 'mpdf';
				$rendererLibraryPath = $rootLibraries . DS . $rendererLibrary;
				
				PHPExcel_Settings::setPdfRenderer(
						$rendererName,
						$rendererLibraryPath
					);
				// Redirect output to a client’s web browser (PDF)
				header('Content-Type: application/pdf');
				header('Cache-Control: max-age=0');
			
				$format = 'PDF';
				$extension = '.pdf';
				break;
				
			case 'csv':
				header("Content-type: text/csv");
				header("Pragma: no-cache");
				header("Expires: 0");
				
				$format = 'CSV';
				$extension = '.csv';
				
				break;
				
			case 'xml':
			
				// load mustache template engine
				$helper = JPath::clean(JPATH_SITE .DS.'libraries'.DS.'librariesbygiro'.DS.'Mustache'.DS.'Autoloader.php');
				if(file_exists($helper)){
					require_once($helper);
					Mustache_Autoloader::register();
				} else {
					$app->enqueueMessage('missing MUSTACHE library', 'error');
					return false;		
				}
				
				$xml_template = JPath::clean(JPATH_SITE .DS.'components'.DS.'com_jforms'.DS.'layouts'.DS.'xml_tmpl.mustache');	
				if(!is_file($xml_template)){
					$app->enqueueMessage('missing XML template', 'error');
					return false;
				}

				$cleanItems = array();
				foreach($items as $item){
					$row = array();
					$row['id'] = $item['id'];
					
					$row = array_merge($row,(array)json_decode($item['form_data']));			
					$cleanItems[] = $row;
				}			
			
				header("Content-Type: application/xml; charset=UTF-8");
				header('Content-Disposition: attachment; filename="'. $filename .'.xml"');

				$template = file_get_contents($xml_template);
				$m = new Mustache_Engine;
				
				// clean output
				ob_clean();
				echo $m->render($template, array('DateTime' => date("Y-m-dTH:i:s", time()),'items' => $items));		
				jexit();
				
				break;
				
			default:
				
				break;
		}
		
		if($filename == ''){
			return false;
		}
		
		try {
			// clean the PHP output
			ob_clean();

			header('Content-Disposition: attachment;filename="'. $filename . $extension .'"');
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, $format);
			
			if($format == 'CSV'){
				$objWriter->setDelimiter(',')
						->setEnclosure('"')
						->setLineEnding("\r\n")
						->setSheetIndex(0);
			}
			
			$objWriter->save('php://output');
			
			jexit();
		}catch(Exception $e){
			echo $e->getMessage();
		}
		
		// set previous error reporting
		error_reporting($error_reporting);
		
	//	return !(count($errors) == 1 AND $errors[0]);
	}	
}

// Load the fork
JformsHelper::loadFork(__FILE__);

// Fallback if no fork has been found
if (!class_exists('JformsModelSubmission')){ class JformsModelSubmission extends JformsCkModelSubmission{} }

