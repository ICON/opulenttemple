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
class GigCalModelFields extends JModelList
{
  public function __construct($config = array())
  {
    if (empty($config['filter_fields'])) 
    {
      $config['filter_fields'] = array(
    'id', 'a.id',
    'published', 'a.published',
    'checked_out', 'a.checked_out',
    'checked_out_time', 'a.checked_out_time',
    'bandname', 'a.bandname',
    'created', 'a.created',
    'created_by', 'a.created_by',
    'created_by_alias', 'a.created_by_alias',
    'modified', 'a.modified',
    'modified_by', 'a.modified_by');
    }

    parent::__construct($config);
  }
  
 }
