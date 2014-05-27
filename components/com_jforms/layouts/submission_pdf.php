<?php

// no direct access
defined('_JEXEC') or die('Restricted access');

$options = $displayData['options'];
$form_data = JformsHelper::arrayToObject($displayData['form_data']);
$mainForm = JformsHelper::arrayToObject($displayData['jforms_snapshot']);
?>
<h1><?php echo $mainForm->name_ml ?></h1>
<?php echo $mainForm->description_ml; ?>
<hr>

<?php
$k = 0;
foreach($mainForm->fieldsets as $jFset){

	$form = $jFset->form;
	if(!($form instanceof JForm)){
		continue;
	}
	$unwantedFields = array('hidden','ckcaptcha');
	$fieldsets = $form->getFieldsets();

	foreach($fieldsets as $fset){
		$k++;
		$fset_name = JText::_('JFORMS_STEP') .' '. $k;
		if($fset->label != ''){
			$fset_name = JText::_($fset->label);
		}

		$fset_description = '';
		if($fset->description != ''){
			$fset_description = JText::_($fset->description);
		}
		
		$fields = $form->getFieldset($fset->name);
		$fieldsToRender = array();
		foreach($fields as $fi){
			if(in_array($fi->type,$unwantedFields)){
				continue;
			}
			
			$fieldName = $fi->fieldname;
			$groups = $fi->group;
		
			if(!$options['printAll']){
				if(!$fset->printable OR $fset->printable != 'true'){
					continue;
				}
				
				$printable = $form->getFieldAttribute($fieldName,'printable',false,$groups);
				if($printable == 'false' OR $printable == 0){
					continue;
				}
			}
			
			$fieldsToRender[] = $fieldName;
		}
		
		if(count($fieldsToRender) == 0){
			continue;
		}
		
		?>

<fieldset>
<h4><?php echo $fset_name; ?></h4>
<?php echo $fset_description; ?>
<?php if($fset->repeatable){
			echo JDom::_('html.list.table', array(
							'form' => $form,
							'domClass' => 'table table-striped table-bordered table-condensed fieldset_table',
							'fieldsetName' => $fset->name,
							'fieldsToRender' => $fieldsToRender,
							'dataList' => $form_data[$fset->name],
							'loadItemsByJs' => false
						));		
		} else {
			echo JDom::_('html.object.table', array(
							'form' => $form,
							'domClass' => 'table table-striped table-bordered table-condensed fieldset_table',
							'fieldsetName' => $fset->name,
							'fieldsToRender' => $fieldsToRender,
							'dataObject' => $form_data,
							'tmplEngine' => ''
						));
		} ?>
</fieldset>		
<?php }
}
?>

