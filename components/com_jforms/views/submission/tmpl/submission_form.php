<?php
/**                               ______________________________________________
*                          o O   |                                              |
*                 (((((  o      <    Generated with Cook Self Service  V2.6.3   |
*                ( o o )         |______________________________________________|
* --------oOOO-----(_)-----OOOo---------------------------------- www.j-cook.pro --- +
* @version		0.2.9
* @package		jForms
* @subpackage	Submissions
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
JDom::_('framework.bootstrap');
JDom::_('framework.jquery.blockui');
$jFieldsets = $this->form->jForm->fieldsets;
?>
<div id="<?php echo $this->form->jForm->jforms_id ?>Step1">
<?php foreach($fieldSets as $k => $fset){?>
	<?php $fieldSet = $this->form->getFieldset($k);?>
	<fieldset class="fieldsform form-horizontal">
		<?php echo $this->renderFieldset($fieldSet); ?>
	</fieldset>
<?php } ?>
<p style="text-align: right;">
	<span style="" class="btn btn-mini jForms_btn-next">
		<?php echo JText::_("JFORMS_SUBMIT"); ?>
	</span>
</p>
</div>

<script type="text/javascript">
jQuery(document).ready(function(){
	var formName = '<?php echo $this->form->jForm->jforms_id ?>';	
	jQuery('#'+formName+'Step1').find('.jForms_btn-next').on('click',function(e,data){
		if(checkFormStep(1,formName)){
			Joomla.submitform('submission.save','#'+formName);
		}
	});
});
</script>
