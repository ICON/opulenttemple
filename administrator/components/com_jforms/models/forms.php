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
* Jforms List Model
*
* @package	Jforms
* @subpackage	Classes
*/
class JformsCkModelForms extends JformsClassModelList
{
	/**
	* The URL view item variable.
	*
	* @var string
	*/
	protected $view_item = 'form';

	/**
	* Constructor
	*
	* @access	public
	* @param	array	$config	An optional associative array of configuration settings.
	* @return	void
	*/
	public function __construct($config = array())
	{
		//Define the sortables fields (in lists)
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'id', 'a.id',
				'alias', 'a.alias',
				'name', 'a.name',
				'published', 'a.published',
				'save_data_in_db', 'a.save_data_in_db',
				'generate_pdf', 'a.generate_pdf',
				'layout_type', 'a.layout_type',
				'ordering', 'a.ordering',

			);
		}

		//Define the filterable fields
		$this->set('filter_vars', array(
			'published' => 'cmd',
			'save_data_in_db' => 'cmd',
			'layout_type' => 'varchar',
			'sortTable' => 'cmd',
			'directionTable' => 'cmd',
			'limit' => 'cmd'
				));

		//Define the searchable fields
		$this->set('search_vars', array(
			'search' => 'string',
			'search_1' => 'string'
				));


		parent::__construct($config);
		
	}

	/**
	* Method to get a list of items.
	*
	* @access	public
	*
	* @return	mixed	An array of data items on success, false on failure.
	*
	* @since	11.1
	*/
	public function getItems()
	{

		$items	= parent::getItems();
		$app	= JFactory::getApplication();


		$this->populateParams($items);

		//Create linked objects
		$this->populateObjects($items);

		return $items;
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
		return $jinput->get('layout', 'default', 'STRING');
	}

	/**
	* Method to get a store id based on model configuration state.
	* 
	* This is necessary because the model is used by the component and different
	* modules that might need different sets of data or differen ordering
	* requirements.
	*
	* @access	protected
	* @param	string	$id	A prefix for the store id.
	* @return	void
	*
	* @since	1.6
	*/
	protected function getStoreId($id = '')
	{
		// Compile the store id.









		return parent::getStoreId($id);
	}

	/**
	* Prepare some additional derivated objects.
	*
	* @access	public
	* @param	object	&$items	The items to populate.
	* @return	void
	*
	* @since	Cook 2.0
	*/
	public function populateObjects(&$items)
	{
		foreach($items as $item)
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
		}
	
		parent::populateObjects($items);
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
		// Initialise variables.
		$app = JFactory::getApplication();
		$session = JFactory::getSession();
		$acl = JformsHelper::getActions();

		parent::populateState('a.ordering', 'asc');

		//Only show the published items
		if (!$acl->get('core.admin') && !$acl->get('core.edit.state'))
			$this->setState('filter.published', 1);
	}

	/**
	* Preparation of the list query.
	*
	* @access	protected
	* @param	object	&$query	returns a filled query object.
	* @return	void
	*/
	protected function prepareQuery(&$query)
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
			case 'forms.default':

				//BASE FIELDS
				$this->addSelect(	'a.alias,'
								.	'a.events,'
								.	'a.generate_pdf,'
								.	'a.layout_type,'
								.	'a.name,'
								.	'a.ordering,'
								.	'a.save_data_in_db');

				break;

			case 'forms.modal':

				//BASE FIELDS
				$this->addSelect(	'a.name');


				break;
			case 'all':
				//SELECT : raw complete query without joins
				$this->addSelect('a.*');

				// Disable the pagination
				$this->setState('list.limit', null);
				$this->setState('list.start', null);
				break;
		}

		//FILTER - Access for : Root table
		$whereAccess = $wherePublished = true;
		$allowAuthor = false;
		$this->prepareQueryAccess('a', $whereAccess, $wherePublished, $allowAuthor);
		$query->where("$whereAccess AND $wherePublished");

		//WHERE - FILTER : Publish state
		$published = $this->getState('filter.published');
		if (is_numeric($published))
			$query->where('a.published = ' . (int) $published);
		elseif (!$published)
			$query->where('(a.published = 0 OR a.published = 1 OR a.published IS NULL)');

		//WHERE - SEARCH : search_search : search on Name + Alias
		$search_search = $this->getState('search.search');
		$this->addSearch('search', 'a.name', 'like');
		$this->addSearch('search', 'a.alias', 'like');
		if (($search_search != '') && ($search_search_val = $this->buildSearch('search', $search_search)))
			$this->addWhere($search_search_val);

		//WHERE - FILTER : Save data in DB
		if($this->getState('filter.save_data_in_db') !== null AND $this->getState('filter.save_data_in_db') != '')
			$this->addWhere("a.save_data_in_db = " . (int)$this->getState('filter.save_data_in_db'));

		//WHERE - FILTER : Layout type
		if($this->getState('filter.layout_type') !== null AND $this->getState('filter.layout_type') != '')
			$this->addWhere("a.layout_type = " . $this->_db->Quote($this->getState('filter.layout_type')));

		//WHERE - SEARCH : search_search_1 : search on Name + Alias
		$search_search_1 = $this->getState('search.search_1');
		$this->addSearch('search_1', 'a.name', 'like');
		$this->addSearch('search_1', 'a.alias', 'like');
		if (($search_search_1 != '') && ($search_search_1_val = $this->buildSearch('search_1', $search_search_1)))
			$this->addWhere($search_search_1_val);

		//Populate only uniques strings to the query
		//SELECT
		foreach($this->getState('query.select', array()) as $select)
			$query->select($select);

		//JOIN
		foreach($this->getState('query.join.left', array()) as $join)
			$query->join('LEFT', $join);

		//WHERE
		foreach($this->getState('query.where', array()) as $where)
			$query->where($where);

		//GROUP ORDER : Prioritary order for groups in lists
		foreach($this->getState('query.groupOrder', array()) as $groupOrder)
			$query->order($groupOrder);

		//ORDER
		foreach($this->getState('query.order', array()) as $order)
			$query->order($order);

		//ORDER
		$orderCol = $this->getState('list.ordering');
		$orderDir = $this->getState('list.direction', 'asc');

		if ($orderCol)
			$query->order($orderCol . ' ' . $orderDir);
	}


}

// Load the fork
JformsHelper::loadFork(__FILE__);

// Fallback if no fork has been found
if (!class_exists('JformsModelForms')){ class JformsModelForms extends JformsCkModelForms{} }

