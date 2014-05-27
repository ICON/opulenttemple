<?php
/**
 * @version    $Id: venues.php 20267 2011-01-11 03:44:44Z eddieajau $
 * @copyright  Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

class GigCalModelVenues extends JModelList
{
  public function __construct($config = array())
  {
    if (empty($config['filter_fields'])) {
    $config['filter_fields'] = array(
    'id', 'a.id',
    'venuename', 'a.venuename',
    'city', 'a.city',
    'state', 'a.state',
    'published', 'a.published',
    'featured', 'a.featured',
    'thedefault', 'a.thedefault',
    'checked_out', 'a.checked_out',
    'checked_out_time', 'a.checked_out_time',
    'created', 'a.created',
    'created_by', 'a.created_by',
    'created_by_alias', 'a.created_by_alias',
    'modified', 'a.modified',
    'modified_by', 'a.modified_by',
    'website', 'a.website');
    }

    parent::__construct($config);
  }
  
  protected function populateState($ordering = 'a.venuename', $direction = 'asc')
  {
    // Initialise variables.
    $app = JFactory::getApplication('administrator');

    // Load the filter state.
    $search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
    $this->setState('filter.search', $search);

    $published = $this->getUserStateFromRequest($this->context.'.filter.published', 'filter_published', '', 'string');
    $this->setState('filter.published', $published);

    // Load the parameters.
    $params = JComponentHelper::getParams('com_gigcal');
    $this->setState('params', $params);

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
    $query->select($this->getState('list.select', 'a.id, a.venuename, a.published, a.featured, a.thedefault, a.checked_out, a.checked_out_time, a.city, a.state'));

    $query->from('`#__gigcal_venues` AS a');

    // Join over the users for the checked out user.
    $query->select('uc.name AS editor');
    $query->join('LEFT', '#__users AS uc ON uc.id=a.checked_out');

    // Filter by published state
    $published = $this->getState('filter.published');
    if (is_numeric($published))
      $query->where('a.published = '.(int) $published);
    else if ($published === '')
      $query->where('(a.published IN (0, 1))');

    // Filter by search in venuename, city
    $search = $this->getState('filter.search');
    if (!empty($search)) {
      if (stripos($search, 'id:') === 0)
        $query->where('a.id = '.(int) substr($search, 3));
      else 
      {
  $search = $db->Quote('%'.$db->getEscaped($search, true).'%');
  $query->where('(a.venuename LIKE '.$search.' OR a.city LIKE '.$search.')');
      }
    }

    // Add the list ordering clause.
    $orderCol  = $this->state->get('list.ordering');
    $orderDirn  = $this->state->get('list.direction');
    $query->order($db->getEscaped($orderCol.' '.$orderDirn));

    return $query;
  }
}

