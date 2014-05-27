<?php
/**
* @version 1.0.0
* @package RSMediaGallery! 1.0.0
* @copyright (C) 2012 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-3.0.html
*/

defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view');

class RSMediaGalleryViewRSMediaGallery extends JView
{
	function display($tpl = null)
	{
		jimport('joomla.environment.browser');
		if (!function_exists('json_encode'))
			require_once(JPATH_COMPONENT.DS.'helpers'.DS.'jsonwrapper'.DS.'json.php');
		
		$layout 	= $this->getLayout();
		$browser 	= JBrowser::getInstance();
		$current 	= $browser->getBrowser();
		// assume supported
        $supported 	= true;
        // IE doesn't like AJAX uploads for now
        if ($current == 'msie' && $layout == 'upload')
            $supported = false;
        // Opera as well...
        if ($current == 'opera' && $layout == 'upload')
            $supported = false;
        if ($supported)
        {
            $document =& JFactory::getDocument();
            $document->setMimeEncoding('application/json');
        }
		
		switch ($layout)
		{
			case 'items':
				$items = $this->get('items');
				foreach ($items as $i => $item)
				{
					$item->params = $item->params ? unserialize($item->params) : array();
					$items[$i] = $item;
				}
				$this->assign('result', $this->_getResult($items, $this->get('total')));
			break;
			
			case 'item':
				$this->assign('result', $this->get('item'));
			break;
			
			case 'save':
				// pass the request to the model to save the item
				$this->assign('result', $this->get('saveresult'));
			break;
			
			case 'upload':
				// pass the request to the model to upload the item (one at a time)
				$this->assign('result', $this->get('uploadresult'));
			break;
			
			case 'autocomplete':
				// pass the request to the model to show a list of autocomplete options
				$this->assign('result', $this->get('autocompleteresult'));
			break;
		}
		
		parent::display($tpl);
	}
	
	function _getResult($items, $total)
	{
		$result = new stdClass();
		
		$result->items = $items;
		$result->total = $total;
		
		return $result;
	}
}