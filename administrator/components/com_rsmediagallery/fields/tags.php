<?php
/**
* @version 1.0.0
* @package RSMediaGallery! 1.0.0
* @copyright (C) 2012 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-3.0.html
*/

defined('_JEXEC') or die('Restricted access');

class JFormFieldTags extends JFormField
{
	/**
	* Element name
	*
	* @access       protected
	* @var          string
	*/
	var $_name = 'Tags';

	function __construct($parent = null) {
		parent::__construct($parent);
		
		$src = JDEBUG ? '.src' : '';
		
		$document =& JFactory::getDocument();
		$document->addScript('components/com_rsmediagallery/assets/js/jquery'.$src.'.js?v=3');
		$document->addScriptDeclaration("jQuery.noConflict();");
		$document->addScriptDeclaration("function rsmg_get_lang(id) {
		switch (id)
		{
			default: return id;
			case 'RSMG_PARAM_TAGS_NO_RESULTS': 	return '".JText::_('RSMG_PARAM_TAGS_NO_RESULTS', true)."'; break;
			case 'RSMG_PLEASE_ADD_TAG': 		return '".JText::_('RSMG_PLEASE_ADD_TAG', true)."'; break;
		}
		}");
		$document->addScript('components/com_rsmediagallery/assets/js/jquery.autosuggest'.$src.'.js?v=3');
		$document->addScript('components/com_rsmediagallery/assets/js/jquery.autosuggest.script'.$src.'.js?v=3');
		
		$document->addStyleSheet('components/com_rsmediagallery/assets/css/jquery.autosuggest.css');
		$document->addStyleDeclaration('
			ul.adminformlist > li {
				clear: both;
			}
			span.text {
				width: 100%;
				overflow: hidden;
				display: block;
				background: #7A7A7A;
				margin-top: 5px;
			}
			
			span.text > label {
				color: #DEDEDE !important;	
				padding-left: 5px;
			}
		');
	}
	
	function getInput()
	{
		$name 	= $this->name;
		$id 	= $this->id;
		$value	= $this->value;
		$size = ( isset($this->element['size']) ? 'size="'.$this->element['size'].'"' : '' );
		$class = ( isset($this->element['class']) ? 'size="'.$this->element['class'].'"' : 'class="rsmg_param_tags"' );
        /*
         * Required to avoid a cycle of encoding &
         * html_entity_decode was used in place of htmlspecialchars_decode because
         * htmlspecialchars_decode is not compatible with PHP 4
         */
        $value = htmlspecialchars(html_entity_decode($value, ENT_QUOTES), ENT_QUOTES);		
		return '<input type="text" name="'.$name.'" id="'.$id.'" value="'.$value.'" '.$class.' '.$size.' />';
	}
}