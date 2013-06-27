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
		require_once JPATH_ADMINISTRATOR.DS.'components'.DS.'com_rsmediagallery'.DS.'helpers'.DS.'helper.php';
		
		if (!function_exists('json_encode'))
			require_once JPATH_ADMINISTRATOR.DS.'components'.DS.'com_rsmediagallery'.DS.'helpers'.DS.'jsonwrapper'.DS.'json.php';
		
		$document  =& JFactory::getDocument();
		$params    = $this->get('params');
		
		// set encoding
		$document->setMimeEncoding('application/json');
		
		// layout
		$layout = $this->getLayout();
		if ($layout == 'items')
		{
			$items = $this->get('items');
			if ($items)
			{
				foreach ($items as $i => $item)
					$items[$i] = RSMediaGalleryHelper::parseItem($item, $params, false);
				RSMediaGalleryHelper::addTags($items);
			}
					
			$this->assign('result', $this->_getResult($items, $this->get('total')));
		}
		else
		{
			JError::raiseError(500, JText::_('RSMG_NOT_FOUND'));
			return;
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