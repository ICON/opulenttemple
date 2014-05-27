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


JDom::_('framework.jquery.condrules');

$config	= JComponentHelper::getParams( 'com_jforms' );
$jForm = $this->form->jForm;
$this->form->jForm->jforms_id = $formId = 'jForm'. JformsHelper::generateRandomString();

$formData = array();
$formData['form'] = $this->form;
$formData['jForm'] = $jForm;

JformsHelper::triggerEvents('on_before_display',$formData);

$this->form = $formData['form'];
$jForm = $formData['jForm'];
?>
<script language="javascript" type="text/javascript">
	//Secure the user navigation on the page, in order preserve datas.
	var holdForm = false;
	window.onbeforeunload = function closeIt(){	if (holdForm) return false;};
	
	jQuery(document).ready(function(){
		jQuery("#<?php echo $formId ?>").validationEngine();
		
		var vhref = jQuery(location).attr('href');
		var vTitle = jQuery(this).attr('title');
		jQuery("#<?php echo $formId ?>").find('#page_title').val(vTitle);
		jQuery("#<?php echo $formId ?>").find('#page_url').val(vhref);   
	});
</script>

<div class="jForms_component_container">
	<h2><?php echo $this->title;?></h2>
	<form action="<?php echo(JRoute::_("index.php")); ?>" method="post" name="<?php echo $formId ?>" id="<?php echo $formId ?>" class='form-validate' enctype='multipart/form-data'>
		<div>
<?php /*  ?>		
			<div>
				<?php echo $this->loadTemplate('formdetails'); ?>
			</div>
<?php  */ ?>		
			<div>	
				<?php if(count($this->form->jForm->fieldsets) > 1 AND $this->form->jForm->layout_type == 'wizard'){ ?>
					<?php echo $this->loadTemplate('wizard'); ?>
				<?php } else { ?>
					<?php echo $this->loadTemplate('form'); ?>
				<?php } ?>
			</div>
		</div>

			<input name="_download" type="hidden" id="_download" value=""/>

		<?php 
			$jinput = JFactory::getApplication()->input;
			echo JDom::_('html.form.footer', array(
			'dataObject' => $this->item,
			'values' => array(
						'id' => $this->state->get('submission.id'),
						'page_url' => '',
						'frm' => $this->state->get('jforms.form'),
						'page_title' => ''
					)));
		?>
	</form>
</div>
<?php JformsHelper::triggerEvents('on_after_display',$formData); ?>
