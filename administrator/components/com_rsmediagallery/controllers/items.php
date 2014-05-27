<?php
/**
* @version 1.0.0
* @package RSMediaGallery! 1.0.0
* @copyright (C) 2012 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-3.0.html
*/

defined('_JEXEC') or die('Restricted access');

class RSMediaGalleryControllerItems extends RSMediaGalleryController
{
	function __construct()
	{
		parent::__construct();
		
		$this->registerTask('publishitems',   'changestatus');
		$this->registerTask('unpublishitems', 'changestatus');
		
		$this->registerTask('tagitems',   'changetag');
		$this->registerTask('untagitems', 'changetag');
	}
	
	function getItems()
	{
		JRequest::setVar('view',   'rsmediagallery');
		JRequest::setVar('layout', 'items');
		JRequest::setVar('format', 'raw');
		
		parent::display();
	}
	
	function getItem()
	{
		JRequest::setVar('view',   'rsmediagallery');
		JRequest::setVar('layout', 'item');
		JRequest::setVar('format', 'raw');
		
		parent::display();
	}
	
	function saveItem()
	{		
		JRequest::setVar('view',   'rsmediagallery');
		JRequest::setVar('layout', 'save');
		JRequest::setVar('format', 'raw');
		
		parent::display();
	}
	
	function uploadItem()
	{		
		JRequest::setVar('view',   'rsmediagallery');
		JRequest::setVar('layout', 'upload');
		JRequest::setVar('format', 'raw');
		
		parent::display();
	}
	
	function getSuggestions()
	{
		JRequest::setVar('view',   'rsmediagallery');
		JRequest::setVar('layout', 'autocomplete');
		JRequest::setVar('format', 'raw');
		
		parent::display();
	}
	
	function delItems()
	{
		// pass the request to the model to delete the selected items
		$model =& $this->getModel('rsmediagallery');
		$model->delItems();
		
		// now, we need to pass this to getItems() so that we can show the total images
		$this->getItems();
	}
	
	function saveOrder()
	{
		// pass the request to the model to delete the selected items
		$model =& $this->getModel('rsmediagallery');
		$model->saveOrder();
	}
	
	function changeStatus()
	{
		// pass the request to the model to publish or unpublish the selected items
		$model =& $this->getModel('rsmediagallery');
		$model->changeStatus(JRequest::getVar('task') == 'publishitems' ? 1 : 0);
	}
	
	function changeTag()
	{
		// pass the request to the model to tag or untag the selected items
		$model =& $this->getModel('rsmediagallery');
		$model->changeTag(JRequest::getVar('task') == 'tagitems' ? 1 : 0);
	}
}