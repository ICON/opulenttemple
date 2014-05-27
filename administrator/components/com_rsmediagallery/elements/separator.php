<?php
/**
* @version 1.0.0
* @package RSMediaGallery! 1.0.0
* @copyright (C) 2012 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-3.0.html
*/

defined('_JEXEC') or die('Restricted access');

class JElementSeparator extends JElement
{
	/**
	* Element name
	*
	* @access       protected
	* @var          string
	*/
	var $_name = 'Separator';
	
	function fetchTooltip($label, $description, &$node, $control_name, $name) {
		return '&nbsp;';
	}
	
	function fetchElement($name, $value, &$node, $control_name)
	{
		if (!$value && $node->attributes('value'))
			$value = $node->attributes('value');
		return '<b>'.JText::_($value).'</b>';
	}
}