<?php
/**                               ______________________________________________
*                          o O   |                                              |
*                 (((((  o      <    Generated with Cook Self Service  V2.6.2   |
*                ( o o )         |______________________________________________|
* --------oOOO-----(_)-----OOOo---------------------------------- www.j-cook.pro --- +
* @version		0.2.1
* @package		jForms
* @subpackage	Forms
* @copyright	G. Tomaselli
* @author		Girolamo Tomaselli - http://bygiro.com - girotomaselli@gmail.com
* @license		GNU GPL v3 or later
*
*             .oooO  Oooo.
*             (   )  (   )
* -------------\ (----) /----------------------------------------------------------- +
*               \_)  (_/
*/

// no direct access
defined('_JEXEC') or die('Restricted access');


$fieldSets = $this->form->getFieldsets();
?>

<?php 
$emails_fields = $this->form->getFieldset('emails');
$fields_to_render = array('from','to','subject','language','attach_pdf_submitted_form','enabled');
?>
<?php
	echo JDom::_('html.list.table', array(
					'fieldsToRender' => $fields_to_render,
					'form' => $this->form,
					'fieldsetName' => 'emails',
					'dataList' => $this->item->emails,
					'tmplEngine' => 'doT'
				));
?>

<?php
$fake_form = $this->item->fake_form;
$variables = array();

if($fake_form instanceof JForm){
	$fake_fieldSets = $fake_form->getFieldsets();

	foreach($fake_fieldSets as $k => $fset){
		$fsetLabel = $fset->label;
		if($fsetLabel == ''){
			$fsetLabel = ucwords(str_replace('_',' ',$fset->name));
		} else if($fsetLabel === strtoupper($fsetLabel)){
			$fsetLabel = '{'. $fset->label .'}';
		}
		
		$group = array();
		$group['text'] = $fset->label;
		$group['label'] = $fsetLabel;
		$group['name'] = str_replace('.','_',$fset->name);
		
		$fake_fieldSet = $fake_form->getFieldset($k);			
		foreach($fake_fieldSet as $fld){
			$text = $label = $fake_form->getFieldAttribute($fld->fieldname,'label');				
			if($label === strtoupper($label)){
				$label = '{'. $label .'}';
			}
			
			$name = $fake_form->getFieldAttribute($fld->fieldname,'name');
			$name = '[[form_data:'. $name .']]';
			
			$group['fields'][] = array('text' => $text, 'label' => $label, 'variable' => $name);				
		}
		
		$variables[] = $group;		
	}
}
?>

<div style="display: none;">
<span id="varsSelector">

<select class="addVar">
	<option value=""><?php echo JText::_("JFORMS_SELECT"); ?></option>
	<optgroup class="l1" label="<?php echo JText::_("JFORMS_DATA_FORMS_VARIABLES"); ?>"> 
<?php foreach($variables as $var){
	if(count($var['fields']) == 0){
		continue;
	}
?>
	  <optgroup class="l2" label="<?php echo $var['label']; ?>">
		<option class="allVariables" value="formVariablesTable_<?php echo $var['name']; ?>"><?php echo $var['label'] .' - '. JText::_("JFORMS_ALL_VARIABLES"); ?></option>
		<?php foreach($var['fields'] as $v){ ?>
				<option value="<?php echo $v['label']; ?>"><?php echo JText::_($v['text']); ?> - <?php echo JText::_("JFORMS_LABEL"); ?></option>
				<option value="<?php echo $v['variable']; ?>"><?php echo JText::_($v['text']); ?> - <?php echo JText::_("JFORMS_VALUE"); ?></option>
		<?php } ?>
	  </optgroup>
<?php } ?>
  </optgroup>

  
<optgroup class="l1" label="<?php echo JText::_("JFORMS_USER_VARIABLES"); ?>">
	<?php 
		$wanted_variables = array('id','username','name','email','registerDate','lastvisitDate');
		foreach($this->user as $k => $v){
			if(!in_array($k,$wanted_variables)){
				continue;
			}
			$var = '[[user:'.$k .']]';
			?>
			<option value="<?php echo $var; ?>"><?php echo $var; ?> (<?php echo JText::sprintf("JFORMS_EXAMPLE_VARIABLE",$v); ?>)</option>
	<?php } ?>
</optgroup>

<optgroup class="l1" label="<?php echo JText::_("JFORMS_JFORMS_VARIABLES"); ?>">
	<?php 
		$wanted_variables = array('id','name','alias','description','message_after_submit','redirect_after_submit');
		foreach($this->item as $k => $v){
			if(!in_array($k,$wanted_variables)){
				continue;
			}
			$var = '[[jforms_snapshot:'.$k .']]';
			?>
			<option value="<?php echo $var; ?>"><?php echo $var; ?></option>
	<?php } ?>
</optgroup>

<optgroup class="l1" label="<?php echo JText::_("JFORMS_OTHER_VARIABLES"); ?>">
	<option value="[[ip_address]]">[[ip_address]]</option>
	<option value="[[pdf]]">[[pdf]] (<?php echo JText::_("JFORMS_PDF_VARIABLE_INFO"); ?>)</option>
	<option disabled="disabled" value="[[password]]">[[password]] (new feature, coming soon!)</option>
</optgroup>
</select>
</span>

<?php foreach($variables as $var){ ?>
<span id="formVariablesTable_<?php echo $var['name']; ?>">
	<table>
		<thead><tr><th colspan="2"><?php echo $var['text']; ?></th></tr></thead>
		<tbody>
	<?php foreach($var['fields'] as $var){ ?>
			<tr>
				<td><?php echo $var['label']; ?></td><td><?php echo $var['variable']; ?></td>
			</tr>
	<?php } ?>
		</tbody>
	</table>
</span>
<?php } ?>
</div>

<script type="text/javascript">
jQuery.fn.modal.Constructor.prototype.enforceFocus = function () {};
jQuery(document).ready(function(){	
	var fields = [
		'to',
		'from',
		'reply_to',
		'cc',
		'bcc',
		'subject',
		'body'
		];
		
	var tmplForm = jQuery('#tmpl_emails_form').html();
	tmplForm = jQuery(tmplForm).wrap('<div>').parent();
	jQuery.each(fields,function(i,v){
		var label = tmplForm.find('#jform_emails_\\{\\{\\=it\\.id\\}\\}_'+v+'_label'),		
		varsSelector = jQuery('#varsSelector').clone();
		
		if(v != 'body'){
			varsSelector.find('option.allVariables').attr('disabled',true);			
		}
		varsSelector.find('.addVar').attr('data-target','#jform_emails_{{=it.id}}_'+v);
		varsSelector = varsSelector.html();
		varsSelector = varsSelector.replace(/"/g, '&quot;');

		label.after(' <span data-toggle="popover" data-html="true" data-title="<?php echo JText::_("JFORMS_SELECT_A_VARIABLE"); ?> <span class=&quot;close_popover&quot;>&times;</span>" data-content="'+ varsSelector +'" class="btnVariables btn btn-warning btn-mini"><?php echo JText::_("JFORMS_VARIABLES"); ?></span>');
	});
	
	jQuery('#tmpl_emails_form').html(tmplForm.html());
	
	jQuery('body').on('change','.addVar',function(){
		var target = jQuery(this).attr('data-target'),
			value = jQuery(this).val();

		if(value.indexOf('formVariablesTable_') >= 0){
			value = jQuery('#'+ value).html();
		}		

		var that = jQuery('.modal-body').find(target);
		if(that.is('textarea' && typeof tinyMCE != 'undefined')){
			tinyMCE.execCommand('mceFocus',false,that.attr('name'));
			tinyMCE.activeEditor.execCommand('mceInsertContent', false, value);
		} else {
			value = that.val() + value;
			that.val(value);
		}
		
		jQuery('[data-toggle="popover"]').popover('hide');
	});
	
	jQuery('body').on('click','.popover .close_popover',function(){
		jQuery('[data-toggle="popover"]').popover('hide');
	});

	jQuery('body').on('click', function (e) {
		jQuery('[data-toggle="popover"]').each(function () {
			//the 'is' for buttons that trigger popups
			//the 'has' for icons within a button that triggers a popup
			if (!jQuery(this).is(e.target) && jQuery(this).has(e.target).length === 0 && jQuery('.popover').has(e.target).length === 0) {
				jQuery(this).popover('hide');
			}
		});
	});
});
</script>
