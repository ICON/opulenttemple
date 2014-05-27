<?php
/**                               ______________________________________________
*                          o O   |                                              |
*                 (((((  o      <    Generated with Cook Self Service  V2.6.2   |
*                ( o o )         |______________________________________________|
* --------oOOO-----(_)-----OOOo---------------------------------- www.j-cook.pro --- +
* @version		0.0.1
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
class JFormFieldCkcheckboxes extends JdomClassFormField
{
	/**
	* The form field type.
	*
	* @var string
	*/
	public $type = 'ckcheckboxes';

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
		$options = array();
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
			$labelKey = $this->getOption('labelKey');
			$listKey = $this->getOption('listKey');
		
			foreach($this->jdomOptions['list'] as $item){
				$opt = new stdClass();
				$opt->value = $item->$listKey;
				$opt->text = $item->$labelKey;
				$options[] = $opt;				
			}
		}
		$dataValue = (isset($this->value)) ? $this->value : $this->jdomOptions['dataValue'];

		$checkOptions = array_merge($this->jdomOptions, array(
							'dataKey' => $this->getOption('name'),
				'formGroup' => $this->group,
				'formControl' => $this->formControl,
			'domClass' => $this->getOption('class'),
			'domId' => $this->id,
			'domName' => $this->name,
			'dataValue' => $dataValue,
			'list' => $options,
			'cols' => $this->getOption('cols'),
			'responsive' => $this->getOption('responsive')
		));
			
		$this->input = JDom::_('html.form.input.checkboxes', $checkOptions);		
		
		return parent::getInput();
	}

	public function getLabel()
	{
		return parent::getLabel();
	}


}