<?php
/**                               ______________________________________________
*                          o O   |                                              |
*                 (((((  o      <    Generated with Cook Self Service  V2.6.2   |
*                ( o o )         |______________________________________________|
* --------oOOO-----(_)-----OOOo---------------------------------- www.j-cook.pro --- +
* @version		2.5
* @package		Cook Self Service
* @subpackage	JDom
* @license		GNU General Public License
* @author		Jocelyn HUARD
*
* @added by		Girolamo Tomaselli - http://bygiro.com
* @version		0.0.1
*
*             .oooO  Oooo.
*             (   )  (   )
* -------------\ (----) /----------------------------------------------------------- +
*               \_)  (_/
*/

// no direct access
defined('_JEXEC') or die('Restricted access');


/**
* Form field for Jdom.
*
* @package	Jdom
* @subpackage	Form
*/
class JFormFieldCkcontentlanguage extends JdomClassFormField
{
	/**
	* The form field type.
	*
	* @var string
	*/
	public $type = 'ckcontentlanguage';
	
	/**
	* Method to get the field input markup.
	*
	* @access	public
	*
	* @return	string	The field input markup.
	*
	* @since	11.1
	*/
	public function getInput()
	{
		$options = $this->getOptions();
		
		$this->input = JDom::_('html.form.input.select', array_merge(array(
				'dataKey' => $this->getOption('name'),
				'formGroup' => $this->group,
				'formControl' => $this->formControl,
				'domClass' => $this->getOption('class'),
				'dataValue' => (string)$this->value,
				'labelKey' => $this->getOption('labelKey'),
				'list' => $options,
				'listKey' => $this->getOption('listKey'),
				'nullLabel' => $this->getOption('nullLabel'),
				'responsive' => $this->getOption('responsive'),
				'size' => $this->getOption('size', 3, 'int'),
				'submitEventName' => ($this->getOption('submit') == 'true'?'onchange':null)
			), $this->jdomOptions));

		return parent::getInput();
	}

	public function getLabel()
	{
		return parent::getLabel();
	}
	
	public function getOutput($tmplEngine = '')
	{
		$options = $this->getOptions();
		$values = array();
		foreach($options as $opt){
			$listKey = $this->getOption('listKey') ? $this->getOption('listKey') : 'id';
			$labelKey = $this->getOption('labelKey') ? $this->getOption('labelKey') : 'text';
			$values[$opt->$listKey] = $opt->$labelKey;
		}

		$html = '';
		$fieldName = $this->getOption('name');
		switch($tmplEngine){
			case 'doT':
				$values = str_replace("'","\'",$this->escapeJsonString(json_encode($values)));
				$html .= 
					'{{ var value,tmp_val,vals = JSON.parse(\''. $values .'\');
							tmp_val = it.'. $fieldName .';
							}}
							{{ if(typeof it.'. $fieldName .' == "boolean"){ 
								tmp_val = 0;
								if(it.'. $fieldName .'){
									tmp_val = 1; }}
								{{ } }}
							{{ } 
							value = vals[tmp_val]; }}
						{{=value || ""}}';
				break;
				
			default:
				$html .= $values[$this->value];
				break;
		}

		return $html;
	}
	
	protected function getOptions(){
		$options = array();
		$opt = new stdClass();
		$opt->text = JText::_('JALL');
		$opt->value = '*';
		$options[] = $opt;		
		
		if (!isset($this->jdomOptions['list']))
		{
			//Get the options from XML
			foreach ($this->element->children() as $option)
			{
				$opt = new stdClass();
				foreach($option->attributes() as $attr => $value)
					$opt->$attr = (string)$value;
		
				$opt->text = JText::_(trim((string) $option));
				$options[] = $opt;
			}
		} else {
			$options = $this->jdomOptions['list'];
		}
		
		$this->languages = $languages = JHtml::_('contentlanguage.existing');
		foreach ($languages as $opt)
		{
			$options[] = $opt;
		}
		
		return $options;
	}
}