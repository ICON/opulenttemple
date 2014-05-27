<?php
/**
 * @version    $Id: bands.php 20267 2011-01-11 03:44:44Z eddieajau $
 * @copyright  Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of band records.
 *
 * @package    Joomla.Administrator
 * @subpackage  com_gigcal
 * @since    1.6
 */
class GigCalModelBands extends JModelList
{
  public function __construct($config = array())
  {
    if (empty($config['filter_fields'])) 
    {
      $config['filter_fields'] = array(
    'id', 'a.id',
    'published', 'a.published',
    'featured', 'a.featured',
    'thedefault', 'a.thedefault',
    'checked_out', 'a.checked_out',
    'checked_out_time', 'a.checked_out_time',
    'bandname', 'a.bandname',
    'website', 'a.website',
    'contactname', 'a.contactname',
    'contactemail', 'a.contactemail',
    'contactphone', 'a.contactphone',
    'city', 'a.city',
    'state', 'a.state',
    'info', 'a.info',
    'created', 'a.created',
    'created_by', 'a.created_by',
    'created_by_alias', 'a.created_by_alias',
    'modified', 'a.modified',
    'modified_by', 'a.modified_by');
    }

    parent::__construct($config);
  }
  
  protected function populateState($ordering = 'a.bandname', $direction = 'asc')
  {
    // Initialise variables.
    $app = JFactory::getApplication('administrator');

    // Load the filter state.
    $search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
    $this->setState('filter.search', $search);

    $published = $this->getUserStateFromRequest($this->context.'.filter.published', 'filter_published', '', 'string');
    $this->setState('filter.published', $published);

    // Load the parameters.
    //$params = JComponentHelper::getParams('com_gigcal');
    //$this->setState('params', $params);

    // List state information.
    parent::populateState($ordering, $direction);
  }

  protected function getStoreId($id = '')
  {
    // Compile the store id.
    $id.= ':' . $this->getState('filter.search');
    $id.= ':' . $this->getState('filter.published');

    return parent::getStoreId($id);
  }

  protected function getListQuery()
  {
    // Create a new query object.
    $db    = $this->getDbo();
    $query  = $db->getQuery(true);

    // Select the required fields from the table.
    $query->select($this->getState('list.select', 'a.id, a.published, a.featured, a.thedefault, a.bandname, a.checked_out, a.checked_out_time, a.city, a.state'));
    $query->from('`#__gigcal_bands` AS a');

    // Join over the users for the checked out user.
    $query->select('uc.name AS editor');
    $query->join('LEFT', '#__users AS uc ON uc.id=a.checked_out');

    // Filter by published state
    $published = $this->getState('filter.published');
    if (is_numeric($published))
      $query->where('a.published = '.(int) $published);
    else if ($published === '')
      $query->where('(a.published IN (0, 1))');

    // Filter by search in bandname, city
    $search = $this->getState('filter.search');
    if (!empty($search)) {
      if (stripos($search, 'id:') === 0)
        $query->where('a.id = '.(int) substr($search, 3));
      else {
        $search = $db->Quote('%'.$db->getEscaped($search, true).'%');
        $query->where('(a.bandname LIKE '.$search.' OR a.city LIKE '.$search.')');
      }
    }

    // Add the list ordering clause.
    $orderCol  = $this->state->get('list.ordering');
    $orderDirn  = $this->state->get('list.direction');

    $query->order($db->getEscaped($orderCol.' '.$orderDirn));
    return $query;
  }
}
