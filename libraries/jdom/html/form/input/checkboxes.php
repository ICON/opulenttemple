<?php
/**                               ______________________________________________
*                          o O   |                                              |
*                 (((((  o      <     JDom Class - Cook Self Service library    |
*                ( o o )         |______________________________________________|
* --------oOOO-----(_)-----OOOo---------------------------------- www.j-cook.pro --- +
* @version		2.5
* @package		Cook Self Service
* @subpackage	JDom
* @license		GNU General Public License
* @author		Jocelyn HUARD
*
*             .oooO  Oooo.
*             (   )  (   )
* -------------\ (----) /----------------------------------------------------------- +
*               \_)  (_/
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );


class JDomHtmlFormInputCheckboxes extends JDomHtmlFormInput
{

	var $domClass;
	var $selectors;

	protected $list;
	
	/*
	 * Constuctor
	 * 	@namespace 	: requested class
	 *  @options	: Configuration
	 * 	@dataKey	: database field name
	 * 	@dataObject	: complete object row (stdClass or Array)
	 * 	@dataValue	: value  default = dataObject->dataKey
	 * 	@domID		: HTML id (DOM)  default=dataKey
	 *
	 *	@cols		: numer of columns
	 *	@list		: list of items for checkboxes
	 */
	function __construct($args)
	{
		parent::__construct($args);
		
		$this->arg('cols'		, 1, $args);
		$this->arg('list'		, array(), $args);
		$this->arg('domClass'	, null, $args);
		$this->arg('selectors'	, null, $args);
		
	}

	function build()
	{
		$output = '';

		switch(gettype($this->dataValue)){
			case'object':
				$dataValues = (array)$this->dataValue;
			break;
			
			case'string':
				$dataValues = explode(',',$this->dataValue);
			break;
			
			case'array':
				$dataValues = $this->dataValue;
			break;
			
			default:
				$dataValues = array($this->dataValue);
			break;
		}
		
		$checkboxes = array();
		foreach($this->list as $key => $opt){
			$value = null;
			if(in_array($opt->value, $dataValues)){
				$value = $opt->value;
			}

			$checkOptions = array_merge($this->options, array(
				'dataValue' => $value,
				'domId' => $this->domId .'_'. $key,
				'domName' => $this->domName .'[]',
				'inputValue' => $opt->value,
				'inputLabel' => $opt->text
			));
			
			$checkboxes[] = JDom::_('html.form.input.checkbox', $checkOptions);
		}
		
		
		$cols = intVal($this->cols);
		
		$count_checkboxes = count($checkboxes);
		if(empty($count_checkboxes)){
			return '';
		}
		
		if($cols > 1){
			$itemsXcol = ceil($count_checkboxes / $cols);
		} else {
			$cols = 1;
			$itemsXcol = $count_checkboxes;
		}
	
		$output = '';
		$output .= '<ul id="<%DOM_ID%>" class="checkboxes">';
		$k=1;
		foreach($checkboxes as $checkbox){
			$output .= '<li>'. $checkbox .'</li>';
			
			if ($k % $itemsXcol == 0 AND $k < $count_checkboxes){
				$output .= '</ul><ul class="checkboxes">';
			}
			$k++;
		}
		$output .= '</ul>';
			
		return $output;
	}
}