<?php
/**
 * @version    $Id: band.php 20782 2011-02-19 06:01:24Z infograf768 $
 * @copyright  Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

/**
 * GigCal Band Table class
 *
 * @package    Joomla.Administrator
 * @subpackage  com_gigcal
 * @since    1.5
 */
class GigCalTableCal_Fields extends JTable
{
  public function __construct(&$db)
  {
    parent::__construct('#__gigcal_cal_fields', 'id', $db);
  }
}

