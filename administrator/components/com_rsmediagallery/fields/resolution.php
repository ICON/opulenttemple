<?php
/**
* @version 1.0.0
* @package RSMediaGallery! 1.0.0
* @copyright (C) 2012 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-3.0.html
*/

defined('_JEXEC') or die('Restricted access');

class JFormFieldResolution extends JFormField
{
	/**
	* Element name
	*
	* @access       protected
	* @var          string
	*/
	var $type = 'Resolution';
	
	function __construct($parent = null) {
		static $added;
		parent::__construct($parent);
		
		if (!$added)
		{
			// load language
			$lang = &JFactory::getLanguage();
			$lang->load('com_rsmediagallery.common', JPATH_ADMINISTRATOR);
			
			// add javascript
			$doc = &JFactory::getDocument();
			$doc->addScriptDeclaration("function rsmg_change_other(id, value) {
				var value = value == 'h' ? '".JText::_('RSMG_PARAM_WIDTH', true)."' : '".JText::_('RSMG_PARAM_HEIGHT', true)."';
				document.getElementById('rsmg_other_' + id).innerHTML = value;
			}");
			
			$added = true;
		}
	}
	
	function getInput()
	{
		$html 		= array();
		
		$name  		= $this->name;
		$fieldname 	= $this->fieldname;
		$value 		= $this->value;
		$node  		= $this->element;
		
		$size 		= isset($this->element['size']) ? 'size="'.$this->element['size'].'"' : '';
		$value		= is_array($value) ? $value : explode(',', $value);		
		if (!isset($value[1])) $value[1] = '0';
		
		$options = array(
			JHtml::_('select.option', 'w', JText::_('RSMG_PARAM_WIDTH')),
			JHtml::_('select.option', 'h', JText::_('RSMG_PARAM_HEIGHT'))
		);
		
		$select = JHtml::_('select.genericlist', $options, $name.'[]', 'onchange="rsmg_change_other(\''.addslashes($fieldname).'\', this.value)"', 'value', 'text', $value[0], $fieldname.'_w');
		$input	= '<input style="text-align: center;" type="text" name="'.$name.'[]" id="'.$fieldname.'_res" value="'.(int) $value[1].'" '.$size.' />';
		$other	= $value[0] == 'h' ? JText::_('RSMG_PARAM_WIDTH') : JText::_('RSMG_PARAM_HEIGHT');
		
		// because Joomla! 2.5 doesn't behave quite right with params and we don't want to keep this whole HTML code in the language files, we need to make this ugly workaround
		$words = explode(' ', JText::_('RSMG_PARAM_SIZE_ADJUST'));
		
		$html[] = '<table cellpadding="1" cellspacing="2" border="0">';
		$html[] = '<tr>';
		$html[] = '<td>';
		foreach ($words as $word)
		{
			// found replacement, wrap it in a <td>
			if ($word[0] == '%')
			{
				$html[] = '</td>';
				// 3rd replacement should be $other - add an id to the <td> so that we can dynamically change the text
				$html[] = '<td'.(strstr($word, '%3$s') ? ' id="rsmg_other_'.$fieldname.'"' : '').'>'.$word.'</td>';
				$html[] = '<td>';
			}
			else
				$html[] = $word;
		}
		$html[] = '</td>';
		$html[] = '</tr>';
		$html[] = '</table>';
		
		return sprintf(implode("\r\n", $html), $select, $input, $other);
	}
}