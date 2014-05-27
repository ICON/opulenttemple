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
* @addon		form fieldset
* @author		Girolamo Tomaselli - http://bygiro.com
* @version		0.0.1
*
*             .oooO  Oooo.
*             (   )  (   )
* -------------\ (----) /----------------------------------------------------------- +
*               \_)  (_/
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );


class JDomHtmlFormFieldset extends JDomHtmlForm
{
	var $listName = 'itemsList';
//	var $markup = 'div';
	var $dataObject;
	var $formGroup = null;
	var $formControl = null;

	var $attachJs = array();
	var $attachCss = array();
	
	protected $form;
	protected $fieldset;
	protected $fieldsToRender;
	protected $actions = array();
	protected $tmplEngine = null;
	
	/*
	 * Constuctor
	 * 	@namespace 	: requested class
	 *  @options	: Configuration
	 *
	 * 	@listName	: default 'items'
	 * 	@markup		: default 'div'
	 * 	@dataObject	: object to override field value
	 * 	@formControl: Override the fieldset formControl
	 * 	@formGroup	: Override the fieldset formGroup
	 * 	@jformFields: array of jform fields 
	 * 	@fieldsToRender: array of fields  to render
	 * 	@actions	: default array()
	 * 	@tmplEngine	: JS Template Engine, default null
	 * 	@domClass	: CSS class
	 *
	 */
	function __construct($args)
	{
		parent::__construct($args);

		$this->arg('listName'		, null, $args, 'itemsList');
	//	$this->arg('markup'			, null, $args, 'div');
		$this->arg('dataObject'		, null, $args);
		$this->arg('formControl'	, null, $args);
		$this->arg('formGroup'		, null, $args);
		$this->arg('dataObject'		, null, $args);
		$this->arg('fieldset'		, null, $args);
		$this->arg('form'			, null, $args);
		$this->arg('fieldsToRender'	, null, $args, array());
		$this->arg('actions'		, null, $args, array());
		$this->arg('tmplEngine'		, null, $args, null);
		$this->arg('domClass'		, null, $args);

	}


	function build()
	{	
		$item = $this->dataObject;
		$cels = array();		
		foreach($this->fieldset as $field){
			if(!in_array($field->fieldname,$this->fieldsToRender) AND count($this->fieldsToRender) > 0){
				continue;
			}
			$html = '';

			$fieldName = $field->fieldname;
			if($item AND isset($item->$fieldName)){
				$field->jdomOptions = array_merge($field->jdomOptions, array(
						'dataValue' => $item->$fieldName,
							));
			}
			
			$field->jdomOptions = array_merge($field->jdomOptions, array(
					'formControl' => $this->formControl,
					'formGroup' => $this->formGroup
						));
						
			//Check ACL
		    if ((method_exists($field, 'canView')) && !$field->canView())
		    	continue;

			if ($field->hidden)
			{
				$html .= $field->input;
				continue;
			}
			
			$classes = '';
			if(($this->form instanceof JForm)){
				$classes = $this->form->getFieldAttribute($field->fieldname,'class',null,$field->group);
			}
			
			if ($field->type == 'ckspacer' OR $field->type == 'spacer')
			{
				$html .= '<div class="control-group field-' . $field->id . $field->responsive . '">';
				$html .= $field->getLabel();
				$html .= '</div>';
				
				continue;
			}
			
			$selectors = (($field->type == 'Editor' || $field->type == 'Textarea') ? ' style="clear: both; margin: 0;"' : '');

			if(isset($field->inputSelector)){
				$selectors .= ' '. $field->inputSelector;
			}
			
			$labelSelector = '';
			if(isset($field->labelSelector)){
				$labelSelector = $field->labelSelector;
			}
			
			$html .= '<div class="control-group field-' . $field->id . $field->responsive . '">';

			$html .= '<div class="control-label">' 
					. $field->getLabel()
					. '</div>';

			$html .= '<div class="controls"' . $selectors . '>'
					. $field->input
					. '</div>';

			$html .= '</div>';			
			
			$cels[$field->fieldname] = $html;
		}
		
		// sort based on fieldsToRender
		if(count($this->fieldsToRender) > 0){
			$newOrder = array();
			foreach($this->fieldsToRender as $fi){
				if(isset($cels[$fi])){
					$newOrder[$fi] = $cels[$fi];
				}
			}
			$cels = $newOrder;
		}
	
		if(count($this->actions) > 0){
			$buttons = '<div class="actions_cln control-group btn-toolbar"><div class="btn-group">';
			foreach($this->actions as $act){
				$buttons .= JDom::_('html.fly.bootstrap.button', array(
						'domClass' => $act->domClass,
						'extra' => $act->extra,
						'icon' => $act->icon,
						'label' => $act->label
					));
			}
			$buttons .= '</div></div>';
			$cels[] = $buttons;
		}
		
		return implode('',$cels);
	}

}