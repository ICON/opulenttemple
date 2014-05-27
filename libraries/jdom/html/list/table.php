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
* @addon		Fly table list
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


class JDomHtmlListTable extends JDomHtmlList
{
	var $domClass = 'table table-striped table-bordered table-condensed ';	
	var $assetName = 'table';
	
	function __construct($args)
	{
		parent::__construct($args);
		
		$this->attachCss[] = 'table.css';
		$this->arg('loadItemsByJs', null, $args, true);
	}
	
	function build()
	{
		$this->domClass .= $this->listName .'_tablelist';
		$cpanel_opts = array();
	
		$header_cels = array();
		foreach($this->fieldset as $field){
			$field->markup = 'span';
			$field->getLabel();
			
			if(isset($this->fieldsToRender) AND !in_array($field->fieldname,$this->fieldsToRender)){
				continue;
			}

			$extraClass = $jList_extra = '';
			
			if($this->jListByGiro){
				$cpanel_opts['sortableFields']['id'] = 'id';
				
				$isFilterable = $isSortable = $isSearchable = false;
				if(($this->form instanceof JForm)){
					$isFilterable = $this->form->getFieldAttribute($field->fieldname,'filterable',null,$field->group);
					$isSortable = $this->form->getFieldAttribute($field->fieldname,'sortable',null,$field->group);
					$isSearchable = $this->form->getFieldAttribute($field->fieldname,'searchable',null,$field->group);
				}
				
				if($isSortable){
					$cpanel_opts['sortableFields'][$field->fieldname] = $field->fieldname;
				
					$jList_extra = 'data-jlist-list-name="'. $this->listName .'_list" data-jlist-sort-by="'. $field->fieldname .'"';
					$extraClass = 'jListSort';
				}
				
				if($isSearchable){
					$cpanel_opts['searchableFields'][$field->fieldname] = $field->fieldname;
				}
				
				if($isFilterable){
					$cpanel_opts['filterableFields'][$field->fieldname] = $field->fieldname;
				}
			}
			
			$header_cels[$field->fieldname] = '<th class="'. $field->fieldname .'_cln '. $extraClass .'" '. $jList_extra .'>'. $field->getLabel() .'</th>';
		}
		
		// sort based on fieldsToRender
		if(count($this->fieldsToRender) > 0){
			$newOrder = array();
			foreach($this->fieldsToRender as $fi){
				if(isset($header_cels[$fi])){
					$newOrder[$fi] = $header_cels[$fi];
				}
			}
			$header_cels = $newOrder;
		}

		if($this->showId){
			$extraClass = $jList_extra = '';
			if($this->jListByGiro){
				$jList_extra = 'data-jlist-list-name="'. $this->listName .'_list" data-jlist-sort-by="id"';
				$extraClass = 'jListSort';
			}
			
			$id_cln = '<th class="id_cln '. $extraClass .'" '. $jList_extra .'>ID</th>';
			array_unshift($header_cels,$id_cln);
		}
		
		if($this->showCounter){
			$counter_cln = '<th class="counter_cln">N°</th>';
			array_unshift($header_cels,$counter_cln);
		}
				
		if(count($this->actions) > 0){
			// NEW
			$act = new stdClass;
			$act->label = 'JTOOLBAR_NEW';
			$act->icon = 'icomoon icon-plus-2 icon-white';
			$act->domClass = 'btn-new btn btn-success btn-small';
			$act->selectors = '';
			
			$btn = JDom::_('html.fly.bootstrap.button', array(
						'domClass' => $act->domClass,
						'selectors' => $act->selectors,
						'icon' => $act->icon,
						'label' => $act->label
					));		
		
			$header_cels[] = '<th class="actions_cln">'. $btn .'</th>';
		}
		
		$header_cels = '<tr data-item-id="0">'. implode('',$header_cels) .'</tr>';

		// add items by PHP
		$rows = '';
		if(!$this->loadItemsByJs){
			foreach($this->dataList as $item){
				$rows .= $this->buildItem($item);
			}
		}
	
		$html =
'<table <%CLASS%><%SELECTORS%>>
	<thead>
		'. $header_cels .'
	</thead>
	<tbody id="'. $this->listName .'_list">
		'. $rows .'
	</tbody>
</table>';

		// add dom elements needed for repeatable feature
		if($this->repeatable){			
			$html .= $this->buildRepeatableDomHtml();

			$options = "
				var options = {};
			";
			
			// load items by JS
			if($this->loadItemsByJs){
				$items = array_values($this->dataList);
				// TO DO: check it!!!
				$options .= "
					options.values = JSON.parse('". str_replace("'","\'",$this->escapeJsonString(json_encode($items))) ."');				
				";
			}
			
			if($this->jListByGiro){	
				$options .= "options.jListByGiro = true;";
				$options .= "options.uniqueIndex = 'id';";
				$options .= "options.controlPanel = true;";
			
				foreach($cpanel_opts as $key => $opt){
					if(count($opt) > 0){
						$options .= "options.". $key ." = ". json_encode(array_values($opt)) .";";
					}
				}
				
				if(count($items) < 40){
					$options .= "options.pagination = false;";
				}
			}
	
			$script = $options ."
				initList('". $this->listName ."',options);";
			

			$this->addScriptInline($script, true);			
		}		
		
		return $html;
	}
	
	public function buildRepeatableDomHtml(){
		$html = '';
		
		// add modal bootstrap for popup form
		$html .= JDom::_('html.fly.bootstrap.modal', array(
				'domId' => $this->listName .'_modal',
				'selectors' => array('data-repeatable'=>$this->listName),
				'title' => JText::_("JACTION_EDIT")
			));
			
		// add forms container
		$html .= '<div style="display: none;" id="'. $this->listName .'_forms"></div>';

		$defaultValues = new stdClass;
		$defaultValues->id = 0;
		
		$fakeItem = new stdClass;
		$fakeItem->id = '{{=it.id}}';
		foreach($this->fieldset as $fi){			
			$fieldName = $fi->fieldname;
			$fakeItem->$fieldName = '{{=it.'. $fieldName .'}}';
			
			$def = $fi->getOption('default') ? $fi->getOption('default') : '';

			if(in_array(strtoupper($fi->getOption('filter')),array('INT','BOOL'))){
				$def = intVal($def);
			}
			$defaultValues->$fieldName = $def;
		}

		$html .= "<script type=\"text/javascript\">var ". $this->listName ."Item = JSON.parse('". str_replace("'","\'",$this->escapeJsonString(json_encode($defaultValues))) ."');</script>";
		
		// add form template (all fields)
		$baseFormGroup = '';
		if(isset($this->baseFormGroup) AND $this->baseFormGroup != ''){
			$baseFormGroup = $this->baseFormGroup .'.{{=it.id}}' ;
		}

		$html .= 
'<script id="tmpl_'. $this->listName .'_form" type="text/x-dot-template">
		<fieldset data-item-id="{{=it.id}}" class="fieldsform form-vertical">
			<input type="hidden" class="remove_item" name="jform['. $this->baseFormGroup .'][{{=it.id}}][remove_item]" />
		'.
		
		JDom::_('html.form.fieldset', array(
				'fieldset' => $this->fieldset,
				'formControl' => 'jform',
				'formGroup' => $baseFormGroup ,
				'form' => $this->form,
				'dataObject' => $fakeItem
			))

		.'</fieldset>
</script>';

		
		// add item template
		$html .=	
'<script id="tmpl_'. $this->listName .'_item" type="text/x-dot-template">
		'. $this->buildItem($fakeItem, $this->tmplEngine) .'
</script>';	
	
		return $html;
	}
	
	function buildItem($item, $tmplEngine = ''){
		static $counter;
		static $prevList;
	
		if($prevList == $this->listName){
			$counter++;
		} else {
			$prevList = $this->listName;
			$counter = 1;
		}
			
		$dataItem = '';
		if($tmplEngine == ''){
			$dataItem = 'data-item="'. htmlentities(json_encode($item)) .'"';
		}
	
		$html = '<tr class="'. $this->listName .'_item" data-item-id="'. $item->id .'" '. $dataItem .'>';
		
		if($this->showCounter){
			if($tmplEngine == 'doT'){
				$counter = '{{=it.list_counter || ""}}';
			}
			$html .= '<td class="counter_cln">'. $counter .'</td>';
		}
		
		if($this->showId){
			$html .= '<td class="id_cln">'. $item->id .'</td>';
		}		
		
		$html .= JDom::_('html.list.item', array(
				'domClass' => '',
				'fieldsToRender' => $this->fieldsToRender,
				'fieldset' => $this->fieldset,
				'form' => $this->form,
				'dataObject' => $item,
				'actions' => $this->actions,
				'markup' => 'td',
				'tmplEngine' => $tmplEngine
			));
		$html .= '</tr>';
		
		return $html;
	}
}