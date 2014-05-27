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

		//Integrity : delete the files associated to this deleted item
		if (!$this->deleteFiles($pks, array(
												'language_file' => 'delete'
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
		return $jinput->get('layout', '', 'STRING');
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
		//Alias
		if (empty($table->alias))
			$table->alias = JApplication::stringURLSafe($table->name);
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
		if (isset($data['fieldsets']) && is_array($data['fieldsets']))
		{
			$registry = new JRegistry;
			$registry->loadArray($data['fieldsets']);
			$data['fieldsets'] = (string) $registry;
		}


		if (isset($data['emails']) && is_array($data['emails']))
		{
			$registry = new JRegistry;
			$registry->loadArray($data['emails']);
			$data['emails'] = (string) $registry;
		}


		if (isset($data['events']) && is_array($data['events']))
		{
			$registry = new JRegistry;
			$registry->loadArray($data['events']);
			$data['events'] = (string) $registry;
		}


		if (isset($data['options']) && is_array($data['options']))
		{
			$registry = new JRegistry;
			$registry->loadArray($data['options']);
			$data['options'] = (string) $registry;
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


}

// Load the fork
JformsHelper::loadFork(__FILE__);

// Fallback if no fork has been found
if (!class_exists('JformsModelForm')){ class JformsModelForm extends JformsCkModelForm{} }

