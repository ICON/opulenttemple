<?php
/**
* @version 1.0.0
* @package RSMediaGallery! 1.0.0
* @copyright (C) 2012 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-3.0.html
*/

defined('_JEXEC') or die('Restricted access');

class JButtonRSMediaGallery extends JButton
{
	function fetchButton($type='', $name='', $suffix='', $task=null)
	{
		$this->_name = $name;
		
		$html = '';
		$text  = JText::_('RSMG_'.strtoupper(str_replace('-', '_', $name)));
		$class = $this->fetchIconClass($name);
		
		if (!$task)
			$task = $name;
		
		$html  = '<a class="rsmg_'.$name.$suffix.'" href="javascript: rsmg_submit(jQuery, \''.$task.'\');">'."\n";
		$html .= '<span>'."\n";
		$html .= "$text\n";
		$html .= '</span>'."\n";
		$html .= '</a>'."\n";

		return $html;
	}

	function fetchId($type, $name)
	{
		return 'toolbar-'.$name;
	}
	
	function render(&$definition)
	{
		/*
		 * Initialize some variables
		 */
		 
		$html = '';
		$type = $definition[1];
		switch ($type)
		{
			case 'toolbar-start':
				$html .= '<td>'."\n";
				$html .= '<div class="rsmg_toolbar" id="rsmg_toolbar'.$definition[2].'">'."\n";
				$html .= '<ul>'."\n";
			break;
			
			case 'toolbar-end':
				$html .= '</ul>'."\n";
				$html .= '</div>'."\n";
				$html .= '</td>'."\n";
			break;
			
			default:
				$action	= call_user_func_array(array(&$this, 'fetchButton'), $definition);

				// Build the HTML Button
				$html	.= "<li>\n";
				$html	.= $action;
				$html	.= "</li>\n";
			break;
		}

		return $html;
	}
}