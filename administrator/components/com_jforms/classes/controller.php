<?php
/**                               ______________________________________________
*                          o O   |                                              |
*                 (((((  o      <    Generated with Cook Self Service  V2.6.3   |
*                ( o o )         |______________________________________________|
* --------oOOO-----(_)-----OOOo---------------------------------- www.j-cook.pro --- +
* @version		0.2.9
* @package		jForms
* @subpackage	
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
* Jforms  Controller
*
* @package	Jforms
* @subpackage	
*/
class JformsCkClassController extends CkJController
{
	/**
	* Call the parent display function. Trick for forking overrides.
	*
	* @access	protected
	* @return	void
	*
	* @since	Cook 2.0
	*/
	protected function _parentDisplay()
	{
		//Add the fork views path (LILO) instead of FIFO
		array_push($this->paths['view'], JPATH_COMPONENT . DS. 'fork' .DS. 'views');

		parent::display();
	}


}

// Load the fork
JformsHelper::loadFork(__FILE__);

// Fallback if no fork has been found
if (!class_exists('JformsClassController')){ class JformsClassController extends JformsCkClassController{} }

