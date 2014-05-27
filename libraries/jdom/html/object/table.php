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
* @addon		Fly table description list
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


class JDomHtmlObjectTable extends JDomHtmlObject
{
	var $domClass = 'table table-striped table-bordered table-condensed ';	
	var $assetName = 'table';
	
	function __construct($args)
	{
		parent::__construct($args);
		
		$this->attachCss[] = 'table.css';
	}
	
	function build()
	{		
		$rows = array();
		
		foreach($this->fieldset as $field){
			$fieldName = $field->fieldname;
			if(isset($this->fieldsToRender) AND !in_array($fieldName,$this->fieldsToRender)){
				continue;
			}

			if($field->type == 'hidden'){
				continue;
			}
			
			$field_output = '';
			if(isset($this->dataObject->$fieldName)){
				$value = $this->dataObject->$fieldName;
				$allLabels = array();
				// check method getAllLabels exists
				if(method_exists($field,'getAllLabels')){
					$allLabels = $field->getAllLabels();					
				}
				
				// check method getOutput exists
				if(method_exists($field,'getOutput') AND !is_array($value)){
					$rp = new ReflectionProperty($field,'value');
					if($rp->isProtected() AND $this->form instanceof JForm){
						$this->form->setValue($fieldName,$field->group,$this->dataObject->$fieldName);
						$field = $this->form->getField($fieldName,$field->group,$this->dataObject->$fieldName);
					} else if($rp->isPublic()){
						$field->value = $this->dataObject->$fieldName;
					}
					$output = $field->getOutput($this->tmplEngine);					
				}
				
				if(is_array($value)){
					$field_output .= '<ul>';
					
					foreach($value as $val){
						if(isset($allLabels[$val])){
							$val = $allLabels[$val];
						}
						$field_output .= '<li>'. $val .'</li>';
					}
					
					$field_output .= '</ul>';
				} else {
					$field_output .= $output;
				}				
			}
	
			$row = '<tr class="'. $fieldName .'_cln">';
			$field->markup = 'span';
			switch($field->type){
				case 'ckspacer':
				case 'Spacer':
					$row .= '<td colspan="2">'. html_entity_decode($field->label) .'</td>';
					break;
				
				default:
					$row .= '<th>'. $field->getLabel() .'</th>';
					$row .= '<td>'. $field_output .'</td>';
					break;
			}			
			$row .= '</tr>';
			
			$rows[$fieldName] = $row;
		}

		// sort based on fieldsToRender
		if(count($this->fieldsToRender) > 0){
			$newOrder = array();
			foreach($this->fieldsToRender as $fi){
				if(isset($rows[$fi])){
					$newOrder[$fi] = $rows[$fi];
				}
			}
			$rows = $newOrder;
		}

		$html = '<table <%CLASS%><%SELECTORS%>>'. implode('',$rows) .'</table>';		
		return $html;
	}
	
}