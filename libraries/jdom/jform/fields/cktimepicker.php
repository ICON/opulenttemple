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
class JFormFieldCktimepicker extends JdomClassFormField
{
	/**
	* The form field type.
	*
	* @var string
	*/
	public $type = 'cktimepicker';

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
		$this->input = JDom::_('html.form.input.timepicker', array_merge(array(
				'dataKey' => $this->getOption('name'),
				'formGroup' => $this->group,
				'formControl' => $this->formControl,
				'domClass' => $this->getOption('class') .' input-mini',
				'dataValue' => $this->value,
				'start' => $this->getOption('start'),
				'end' => $this->getOption('end'),
				'time' => $this->getOption('time'),
				'placeholder' => $this->getOption('placeholder'),
				'responsive' => $this->getOption('responsive'),
				'size' => $this->getOption('size')
			), $this->jdomOptions));

		return parent::getInput();
	}

	public function getLabel()
	{
		return parent::getLabel();
	}
}