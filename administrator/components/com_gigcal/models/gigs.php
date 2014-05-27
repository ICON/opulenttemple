<?php
/**
 * @version    $Id: gigs.php 20267 2011-01-11 03:44:44Z eddieajau $
 * @copyright  Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of gig records.
 *
 * @package    Joomla.Administrator
 * @subpackage  com_gigcal
 * @since    1.6
 */
class GigCalModelGigs extends JModelList
{
  
  /**
   * Constructor.
   *
   * @param  array  An optional associative array of configuration settings.
   * @see    JController
   * @since  1.6
   */
  public function __construct($config = array())
  {
//error_log('gigs model');
    if (empty($config['filter_fields'])) {
      $config['filter_fields'] = array(
        'id', 'a.id',
        'gigdate', 'a.gigdate',
        'gigtitle', 'a.gigtitle',
        'band_id', 'a.band_id',
        'venue_id', 'a.venue_id',
        'bandname', 'b.bandname',
        'venuename', 'v.venuename',
        'published', 'a.published',
        'featured', 'a.featured',
        'checked_out', 'a.checked_out',
        'checked_out_time', 'a.checked_out_time',
//        'catid', 'a.catid', 'category_title',
//        'access', 'a.access', 'access_level',
        'created', 'a.created',
        'created_by', 'a.created_by',
        'created_by_alias', 'a.created_by_alias',
        'modified', 'a.modified',
        'modified_by', 'a.modified_by',
        'website', 'a.website',
      );
    }

    $this->bandNameOptions  = $this->getNameOptions('band');
    $this->venueNameOptions  = $this->getNameOptions('venue');

    parent::__construct($config);
  }
  
  
  /**
   * Method to auto-populate the model state.
   *
   * Note. Calling getState in this method will result in recursion.
   *
   * @since  1.6
   */
  protected function populateState($ordering = 'a.gigdate', $direction = 'desc')
  {
    // Initialise variables.
    $app = JFactory::getApplication('administrator');

    // Load the filter state.
    $search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
    $this->setState('filter.search', $search);

//    $accessId = $this->getUserStateFromRequest($this->context.'.filter.access', 'filter_access', null, 'int');
//    $this->setState('filter.access', $accessId);

    $band_id = $this->getUserStateFromRequest($this->context.'.filter.band_id', 'filter_band_id', null, 'int');
    $this->setState('filter.band_id', $band_id);

    $venue_id = $this->getUserStateFromRequest($this->context.'.filter.venue_id', 'filter_venue_id', null, 'int');
    $this->setState('filter.venue_id', $venue_id);

    $published = $this->getUserStateFromRequest($this->context.'.filter.published', 'filter_published', '', 'string');
    $this->setState('filter.published', $published);

    $scope = $this->getUserStateFromRequest($this->context.'.filter.scope', 'filter_scope', null, 'int');
    $this->setState('filter.scope', $scope);

//    $categoryId = $this->getUserStateFromRequest($this->context.'.filter.category_id', 'filter_category_id', '');
//    $this->setState('filter.category_id', $categoryId);

//    $language = $this->getUserStateFromRequest($this->context.'.filter.language', 'filter_language', '');
//    $this->setState('filter.language', $language);

    // Load the parameters.
    $params = JComponentHelper::getParams('com_gigcal');
    $this->setState('params', $params);

    // List state information.
    parent::populateState($ordering, $direction);
  }

  /**
   * Method to get a store id based on model configuration state.
   *
   * This is necessary because the model is used by the component and
   * different modules that might need different sets of data or different
   * ordering requirements.
   *
   * @param  string    $id  A prefix for the store id.
   * @return  string    A store id.
   * @since  1.6
   */
  protected function getStoreId($id = '')
  {
    // Compile the store id.
    $id.= ':' . $this->getState('filter.search');
//    $id.= ':' . $this->getState('filter.access');
    $id.= ':' . $this->getState('filter.band_id');
    $id.= ':' . $this->getState('filter.venue_id');
    $id.= ':' . $this->getState('filter.published');
    $id.= ':' . $this->getState('filter.scope');
//    $id.= ':' . $this->getState('filter.category_id');
//    $id.= ':' . $this->getState('filter.language');

    return parent::getStoreId($id);
  }

  /**
   * Build an SQL query to load the list data.
   *
   * @return  JDatabaseQuery
   * @since  1.6
   */
  protected function getListQuery()
  {
    // Create a new query object.
    $db  = $this->getDbo();
    $query  = $db->getQuery(true);

    // Select the required fields from the table.
    $query->select(
      $this->getState(
        'list.select',
        'a.id, a.band_id, a.venue_id'
        .', a.published, a.featured'
        .', a.gigdate, a.gigtitle, a.checked_out, a.checked_out_time'
//        .', a.city, a.state'
//        .', a.access'
//        .', a.ordering'
//        .', a.language'
//        .', a.publish_up, a.publish_down'
      )
    );
    $query->from('`#__gigcal_gigs` AS a');

    // Join over the band name .
    $query->select('b.bandname AS bandname');
    $query->join('LEFT', '#__gigcal_bands AS b ON b.id=a.band_id');

    // Join over the venue name .
    $query->select('v.venuename AS venuename');
    $query->join('LEFT', '#__gigcal_venues AS v ON v.id=a.venue_id');

    // Join over the users for the checked out user.
    $query->select('uc.name AS editor');
    $query->join('LEFT', '#__users AS uc ON uc.id=a.checked_out');

//    // Join over the categories.
//    $query->select('c.title AS category_title');
//    $query->join('LEFT', '#__categories AS c ON c.id = a.catid');

//    // Filter by access level.
//    if ($access = $this->getState('filter.access')) {
//      $query->where('a.access = '.(int) $access);
//    }

    // Filter by published state
    $published = $this->getState('filter.published');
    if (is_numeric($published)) {
      $query->where('a.published = '.(int) $published);
    } else if ($published === '') {
      $query->where('(a.published IN (0, 1))');
    }

    // Filter by scope
    $scope = $this->getState('filter.scope');
    if (is_numeric($scope) && $scope != 0) {
      if ($scope > 0)
        $query->where('(a.gigdate > unix_timestamp(now()))');
      else
        $query->where('(a.gigdate < unix_timestamp(now()))');
    }

//    // Filter by category.
//    $categoryId = $this->getState('filter.category_id');
//    if (is_numeric($categoryId)) {
//      $query->where('a.catid = '.(int) $categoryId);
//    }

    // Filter by band_id.
    $band_id = $this->getState('filter.band_id');
    if (is_numeric($band_id) && ($band_id != '')) {
      $query->where('a.band_id = '.(int) $band_id);
    }

    // Filter by venue_id.
    $venue_id = $this->getState('filter.venue_id');
    if (is_numeric($venue_id) && ($venue_id != '')) {
      $query->where('a.venue_id = '.(int) $venue_id);
    }

    // Filter by search in gigtitle, info, bandname, venuename
    $search = $this->getState('filter.search');
    if (!empty($search)) {
      if (stripos($search, 'id:') === 0) {
        $query->where('a.id = '.(int) substr($search, 3));
      } else {
        $search = $db->Quote('%'.$db->getEscaped($search, true).'%');
        $query->where('(a.gigtitle LIKE '.$search
            .' OR a.info LIKE '.$search
            .' OR b.bandname LIKE '.$search
            .' OR v.venuename LIKE '.$search
            .')');
      }
    }

//    $band_Id = $this->getState('filter.band_id');
//    if (is_numeric($band_id))
//      $query->where('band_id = '.$band_id);

    // Check for extra filter parameters in URL
//    if (array_key_exists('band_id', $_REQUEST) && $_REQUEST['band_id'] != '')
//      $query->where('band_id='.$_REQUEST['band_id']);
//
//    if (array_key_exists('venue_id', $_REQUEST) && $_REQUEST['venue_id'] != '')
//      $query->where('venue_id='.$_REQUEST['venue_id']);

    // Add the list ordering clause.
    $orderCol  = $this->state->get('list.ordering');
    $orderDirn  = $this->state->get('list.direction');
//    if ($orderCol == 'a.ordering' || $orderCol == 'category_title') {
//      $orderCol = 'category_title '.$orderDirn.', a.ordering';
//    }
    $query->order($db->getEscaped($orderCol.' '.$orderDirn));

//error_log('GigCalModelGigs::getListQuery() = '.$query);
//echo $query;

//echo '<pre>'.print_r($query,true).'</pre>';
//JQuit();

    return $query;
  }

  protected function getNameOptions($optionName)
  {
    // Build the filter options.
    $db  =& JFactory::getDBO();
    $db->setQuery('SELECT id, '.$optionName.'name FROM #__gigcal_'.$optionName.'s ORDER BY '.$optionName.'name');
    return $db->loadAssocList();
  }


}
