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

/* hack */
JDom::_('framework.dot');
$languages = JformsHelper::getInstalledLanguages();

$fake_lang = new stdClass;
$fake_lang->lang_code = '';
$fake_lang->title = 'Default';
array_unshift($languages,$fake_lang);
/* hack */
?>
	<ul class="nav nav-tabs jForms_tabs">
	<?php
		$k = 0;
		foreach($languages as $lang){
			$postfix = '';
			$img = '';
			if($lang->lang_code != ''){
				$postfix = '_'. strtolower(str_replace('-','', $lang->lang_code));
				$img = explode('-',$lang->lang_code);
				$img = $img[0];
				$img = '<img src="'. JURI::root() .'/media/mod_languages/images/'. $img .'.gif" />';
			}
	?>
			<li class="<?php if($k==0){ echo 'active'; } ?> lang_tab">
				<a href="#lang_form<?php echo $postfix; ?>">
					<?php echo $lang->title; ?>
					<?php echo $img; ?>
				</a>
			</li>
	<?php 
			$k++;
		} ?>
	</ul>

<div class="tab-content">
<?php 
	$k=0;
	foreach($languages as $lang){
	$postfix = '';
	if($lang->lang_code != ''){
		$postfix = '_'. strtolower(str_replace('-','', $lang->lang_code));
	}
?>
	<div class="tab-pane <?php if($k==0){ echo 'active'; } ?>" id="lang_form<?php echo $postfix; ?>">
		<?php $fieldSet = $this->form->getFieldset('form.details'. $postfix);?>
		<fieldset class="fieldsform form-horizontal">


	<?php
	// Name
	$field = $fieldSet['jform_name'. $postfix];
	?>
	<div class="control-group <?php echo 'field-' . $field->id . $field->responsive; ?>">
		<div class="control-label">
			<?php echo $field->label; ?>
		</div>
	
	    <div class="controls">
			<?php echo $field->input; ?>
		</div>
	</div>

	<?php
	// Language file
	$field = $fieldSet['jform_language_file'. $postfix];
	$field->jdomOptions = array(
		'actions' => array('remove', 'thumbs', 'delete', 'trash'),
		'cid' => $this->item->id
			);
	?>
	<div class="control-group <?php echo 'field-' . $field->id . $field->responsive; ?>">
		<div class="control-label">
			<?php echo $field->label; ?>
		</div>
	
	    <div class="controls">
			<?php echo $field->input; ?>
		</div>
	</div>
	 
		<?php
		// Description
		$field = $fieldSet['jform_description'. $postfix];
		?>
			<div class="control-group <?php echo 'field-' . $field->id . $field->responsive; ?>">
				<div class="control-label">
					<?php echo $field->label; ?>
				</div>
			
				<div class="controls">
					<?php echo $field->input; ?>
				</div>
			</div>
		
		
		<?php
		// Message after submit
		$field = $fieldSet['jform_message_after_submit'. $postfix];
		?>
			<div class="control-group <?php echo 'field-' . $field->id . $field->responsive; ?>">
				<div class="control-label">
					<?php echo $field->label; ?>
				</div>
			
				<div class="controls">
					<?php echo $field->input; ?>
				</div>
			</div>
		
	</fieldset>

	</div>
<?php 
	$k++;
	} ?>
</div>

<script type="text/javascript">
jQuery(document).ready(function(){
	jQuery('.jForms_tabs a').click(function (e) {
	  e.preventDefault();
	  jQuery(this).tab('show');
	});
});
</script>
