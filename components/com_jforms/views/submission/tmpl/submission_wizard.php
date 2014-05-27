<?php
/*
* @version		0.0.1
* @package		jForms
* @subpackage	Submissions
* @copyright	G. Tomaselli
* @author		Girolamo Tomaselli - http://bygiro.com - girotomaselli@gmail.com
* @license		GNU GPL v3 or later
*/

// no direct access
defined('_JEXEC') or die('Restricted access');


$fieldSets = $this->form->getFieldsets();
JDom::_('framework.bootstrap');
JDom::_('framework.bootstrap.wizard');
JDom::_('framework.jquery.blockui');
$jFieldsets = $this->form->jForm->fieldsets;

$options = $this->form->jForm->options;
?>
<div id="<?php echo $this->form->jForm->jforms_id ?>_wizard" class="wizard">
	<ul class="steps">
	<?php 
		$steps = array();
		foreach($jFieldsets as $jFset){
			$form = $jFset->form;
			if(!($form instanceof JForm)){
				continue;
			}
			
			$fieldSets = $form->getFieldsets();
			if(1 > 0){ // always split the fieldsets in steps
				foreach($fieldSets as $fset){
					$steps[] = array($fset);
				}
			} else {
				$steps[] = $fieldSets;
			}
		} ?>
		
	<?php	foreach($steps as $key => $fsets){				
				if($fsets[0]->label != ''){
					$label = JText::_($fsets[0]->label);
				} else {
					$label = JText::_('JFORMS_STEP') .' '. ($key +1);
				} ?>
				
				<li data-target="#<?php echo $this->form->jForm->jforms_id .'Step'. ($key +1) ?>" class="<?php if(($key +1) == 1){echo 'active'; } ?> step">
					<span class="badge <?php if(($key +1) == 1){echo 'badge-info'; } ?>"><?php echo ($key +1) ?></span>
					<?php echo $label; ?>
					<span class="chevron"></span>
				</li>
	  <?php } ?>		
	</ul>
	<div class="actions">
		<div class="act">
			<span class="btn btn-mini jForms_btn-prev">
				<i class="icomoon icon-arrow-left"></i>
				<?php echo JText::_("JFORMS_PREV"); ?>
			</span>
		</div>
		<div class="act">
			<span class="btn btn-mini jForms_btn-next">
				<?php echo JText::_("JFORMS_NEXT"); ?>
				<i class="icomoon icon-arrow-right"></i>				
			</span>
		</div>
	</div>
</div>
<div class="step-content">
<?php foreach($steps as $key => $fsets){?>
		<div class="step-pane <?php if(($key +1) == 1){echo 'active'; } ?>" id="<?php echo $this->form->jForm->jforms_id ?>Step<?php echo ($key +1) ?>">
			<?php foreach($fsets as $fset){
				// get the fields
				$fset_fields = $this->form->getFieldset($fset->name);
			?>
				<fieldset class="fieldsform form-horizontal <?php echo $fset->class; ?>">
					<?php echo $this->renderFieldset($fset_fields, $this->form); ?>
				</fieldset>
			<?php } ?>
		</div>
<?php } ?>
	
	<div style="display: table; width: 100%;">
		<div style="display: table-cell; text-align: left;">
			<span class="btn jForms_btn-prev">
				<i class="icomoon icon-arrow-left"></i>
				<?php echo JText::_("JFORMS_PREV"); ?>
			</span>
		</div>
		<div style="display: table-cell; text-align: right;">
			<span class="btn jForms_btn-next">
				<?php echo JText::_("JFORMS_NEXT"); ?>
				<i class="icomoon icon-arrow-right"></i>
			</span>
		</div>
	</div>
</div>

<script type="text/javascript">
jQuery(document).ready(function(){
	var formName = '<?php echo $this->form->jForm->jforms_id ?>';
	jQuery('#'+formName+'_wizard').wizard({
		nextButtons: '.jForms_btn-next',
		prevButtons: '.jForms_btn-prev',
		text:{
			finished: '<?php echo JText::_("JFORMS_SUBMIT"); ?>'
		}
	});
	
	jQuery('#'+formName+'_wizard').on('change_'+formName+'_wizard',function(e,data){
		if(data.direction == 'next'){
			if(!checkFormStep(data.step,formName)){
				e.preventDefault();
			} else {
				scrollToElement( '#'+formName+'_wizard');
<?php if(isset($options['enable_partial_save']) AND $options['enable_partial_save'] AND 1==0){ 
	JDom::_('framework.jquery.ajax');
?>
				if(typeof window[formName +'_stepsData'] == 'undefined'){
					window[formName +'_stepsData'] = [];
				}
				// save current and previous steps
				var step,fId=<?php echo $this->form->jForm->id ?>,
					tk="<?php echo JSession::getFormToken() ?>",
					changed,stepsData = [];
				for(var i=1;i<=data.step;i++){
					step = jQuery('#'+ formName +'Step'+data.step);
					stepsData[data.step] = step.serializeObject();
				}
				
				// compare objects
				changed = !(JSON.stringify(window[formName +'_stepsData']) === JSON.stringify(stepsData));
				if(changed){
					submitStep(fId,stepsData,tk);
					window[formName +'_stepsData'] = stepsData;
				}				
<?php } ?>
			}
		} else {
			scrollToElement( '#'+formName+'_wizard');
		}
	});
	
	jQuery('#'+formName+'_wizard').on('finished_'+formName+'_wizard',function(e,data){
		if(checkFormStep(data.step,formName)){
			Joomla.submitform('submission.save','#'+formName);
		}
	});
});
</script>