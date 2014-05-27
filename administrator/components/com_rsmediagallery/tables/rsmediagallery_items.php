<?php
/**
* @version 1.0.0
* @package RSMediaGallery! 1.0.0
* @copyright (C) 2012 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-3.0.html
*/

defined('_JEXEC') or die('Restricted access');

class TableRSMediaGallery_Items extends JTable
{
	/**
	 * Primary Key
	 *
	 * @var int
	 */
	var $id 				= null;
	
	var $original_filename 	= null;
	var $filename			= null;
	var $title 				= null;
	var $url 				= null;
	var $description 		= null;
	var $type 				= null;
	var $params 			= null;
	var $free_aspect 		= null;
	var $hits 				= null;
	var $created 			= null;
	var $modified 			= null;
	
	var $published 			= 1;
	var $ordering 			= null;
		
	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function TableRSMediaGallery_Items(& $db)
	{
		parent::__construct('#__rsmediagallery_items', 'id', $db);
	}
}