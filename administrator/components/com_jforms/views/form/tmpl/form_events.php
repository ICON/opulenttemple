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
<?php $fieldSet = $this->form->getFieldset('form.events');?>
<fieldset class="fieldsform form-horizontal">
	<?php
	// Redirect after submit
	$field = $fieldSet['jform_redirect_after_submit'];
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
<?php 
$events_fields = $this->form->getFieldset('form.events');
$fields_to_render = array('name','event','file','script','enabled');
?>
<?php
	echo JDom::_('html.list.table', array(
					'fieldsToRender' => $fields_to_render,
					'form' => $this->form,
					'fieldsetName' => 'form.events2',
					'dataList' => $this->item->events,
					'tmplEngine' => 'doT'
				));
?>