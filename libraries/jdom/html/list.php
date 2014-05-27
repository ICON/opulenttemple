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


class JDomHtmlList extends JDomHtml
{
	var $fallback = 'table';	//Used for default
	var $listName;

	var $attachJs = array();
	var $attachCss = array();
	var $selectors;
	var $jListByGiro;
	var $loadItemsByJs;
	var $showId;
	var $showCounter;
	var $repeatable;
	var $editable;
	var $markup;
	var $baseFromGroup;
	
	protected $dataList;
	protected $dataObject;
	protected $fieldset;
	protected $form;
	protected $fieldsToRender;
	protected $actions;
	protected $tmplEngine;
	
	/*
	 * Constuctor
	 * 	@namespace 	: requested class
	 *  @options	: Configuration
	 *
	 * 	@selectors	: selectors
	 * 	@listName	: default 'itemsList'
	 * 	@jformFields: array of jform fields 
	 * 	@fieldsToRender: array of fields  to render
	 * 	@actions	: default array()
	 * 	@tmplEngine	: JS Template Engine, default null
	 * 	@domClass	: CSS class
	 * 	@jListByGiro: default: false
	 * 	@repeatable	: default: false
	 * 	@editable	: default: false
	 * 	@dataList	: values list (array of objects)
	 *
	 */
	function __construct($args)
	{
		parent::__construct($args);

		$this->arg('listName'		, null, $args, 'itemsList');
		$this->arg('dataList'		, null, $args, array());
		$this->arg('dataObject'		, null, $args);
		$this->arg('baseFormGroup'	, null, $args);
		$this->arg('selectors'		, null, $args);
		$this->arg('fieldset'		, null, $args, false);
		$this->arg('fieldsetName'	, null, $args);
		$this->arg('form'			, null, $args);
		$this->arg('fieldsToRender'	, null, $args);
		$this->arg('actions'		, null, $args, array());
		$this->arg('markup'			, null, $args, 'td');
		$this->arg('tmplEngine'		, null, $args, 'doT');
		$this->arg('domClass'		, null, $args);
		$this->arg('showId'			, null, $args, false);
		$this->arg('showCounter'	, null, $args, false);
		$this->arg('repeatable'		, null, $args, false);
		$this->arg('editable'		, null, $args, false);
		$this->arg('jListByGiro'	, null, $args, false);
		$this->arg('loadItemsByJs', null, $args, true);

		if(!empty($this->dataList)){
			$this->dataList = (array)$this->dataList;
		} else {
			$this->dataList = array();
		}

		if($this->form AND isset($this->fieldsetName)){
			$this->listName = str_replace('.','_',$this->fieldsetName);
			
			if(!$this->fieldset){
				$this->fieldset = $this->form->getFieldset($this->fieldsetName);
			}
			
			$fieldSets = $this->form->getFieldsets();
			$this->repeatable = $fieldSets[$this->fieldsetName]->repeatable;
			$this->editable = $fieldSets[$this->fieldsetName]->editable;
		}

		if(!isset($this->baseFormGroup)){
			$arr = array_values($this->fieldset);
			$firstField = array_shift($arr);
			$dataField = explode('.',$firstField->group);
				
			$this->baseFormGroup = $dataField[0];
		}
		
		if($this->jListByGiro){
			JDom::_('framework.jquery.jlist');
		}
			
		if($this->repeatable){
			JDom::_('framework.dot');
			JDom::_('framework.jquery.repeatable');
			
			// add data attributes
			$this->selectors .= ' data-repeatable="'. $this->listName .'"';
			
			if($this->editable){
				// add action EDIT, DELETE
				$repeatableActions = array();
				
				// edit
				$act = new stdClass;
				$act->label = 'JACTION_EDIT';
				$act->icon = 'icomoon icon-pencil icon-white';
				$act->domClass = 'btn-edit btn btn-success btn-mini';
				$act->selectors = '';
				
				$repeatableActions[] = $act;
				
				
				// DELETE
				JText::script("PLG_JDOM_ALERT_ASK_BEFORE_REMOVE");
				$act = new stdClass;
				$act->label = 'JACTION_DELETE';
				$act->icon = 'icomoon icon-remove icon-white';
				$act->domClass = 'btn-delete btn btn-danger btn-mini';
				$act->selectors = '';
				
				$repeatableActions[] = $act;
				
				
				$this->actions = array_merge($this->actions,$repeatableActions);
			}
		}		
	}

	protected function parseVars($vars)
	{
		return parent::parseVars(array_merge(array(
			'STYLE'		=> $this->buildDomStyles(),
			'CLASS'			=> $this->buildDomClass(),		//With attrib name
			'SELECTORS'		=> $this->buildSelectors(),
		), $vars));
	}
}