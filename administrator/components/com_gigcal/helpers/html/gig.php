<?php
/**
 * @version    $Id: gig.php 20196 2011-01-09 02:40:25Z ian $
 * @copyright  Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Utility class for creating HTML Grids
 *
 * @static
 * @package    Joomla.Administrator
 * @subpackage  com_gigcal
 * @since    1.6
 */
abstract class JHtmlgig
{
  static function featured($value = 0, $i)
  {
    // Array of image, task, title, action
    $states  = array(
      0  => array('disabled.png', 'gigs.featured', 'COM_GIGCAL_GIG_UNFEATURED', 'COM_GIGCAL_GIG_TOGGLE_TO_FEATURE'),
      1  => array('featured.png', 'gigs.unfeatured', 'COM_GIGCAL_GIG_FEATURED', 'COM_GIGCAL_GIG_TOGGLE_TO_UNFEATURE'));
    $state  = JArrayHelper::getValue($states, (int) $value, $states[1]);
    $html  = '<a href="#" onclick="return listItemTask(\'cb'.$i.'\',\''.$state[1].'\')" title="'.JText::_($state[3]).'">'
      .JHtml::_('image','admin/'.$state[0], JText::_($state[2]), NULL, true).'</a>';

    return $html;
  }
}
