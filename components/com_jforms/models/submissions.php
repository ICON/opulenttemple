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
* Jforms List Model
*
* @package	Jforms
* @subpackage	Classes
*/
class JformsCkModelSubmissions extends JformsClassModelList
{
	/**
	* The URL view item variable.
	*
	* @var string
	*/
	protected $view_item = 'submission';

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

			);
		}


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
			
			if (!empty($item->_form_id_options) && is_string($item->_form_id_options))
			{
				$registry = new JRegistry;
				$registry->loadString($item->_form_id_options);
				$item->_form_id_options = (array)$registry->toObject();
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

		parent::populateState('a.created_by', 'asc');
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
		$query->from('#__jforms_submissions AS a');



		//IMPORTANT REQUIRED FIELDS
		$this->addSelect(	'a.id,'
						.	'a.created_by');

		switch($this->getState('context', 'all'))
		{
			case 'submissions.default':

				//BASE FIELDS
				$this->addSelect(	'a.creation_date,'
								.	'a.form_id,'
								.	'a.ip_address,'
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
				$this->addSelect('a.*');

				// Disable the pagination
				$this->setState('list.limit', null);
				$this->setState('list.start', null);
				break;
		}

		//FILTER - Access for : Root table


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
if (!class_exists('JformsModelSubmissions')){ class JformsModelSubmissions extends JformsCkModelSubmissions{} }

