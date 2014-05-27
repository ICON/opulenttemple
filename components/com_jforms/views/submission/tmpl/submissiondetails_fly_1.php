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



?>
<fieldset class="fieldsfly fly-horizontal">
	<legend><?php echo JText::_('JFORMS_FIELDSET_DETAILS') ?></legend>

	<div class="control-group field-_form_id_name">
    	<div class="control-label">
			<label><?php echo JText::_( "JFORMS_FIELD_FORM_NAME" ); ?></label>
		</div>
		
        <div class="controls">
			<?php echo JDom::_('html.fly', array(
				'dataKey' => '_form_id_name',
				'dataObject' => $this->item
			));?>
		</div>
    </div>
	<div class="control-group field-_form_id_description">
    	<div class="control-label">
			<label><?php echo JText::_( "JFORMS_FIELD_FORM_DESCRIPTION" ); ?></label>
		</div>
		
        <div class="controls">
			<?php echo JDom::_('html.fly', array(
				'dataKey' => '_form_id_description',
				'dataObject' => $this->item
			));?>
		</div>
    </div>
	<div class="control-group field-creation_date">
    	<div class="control-label">
			<label><?php echo JText::_( "JFORMS_FIELD_CREATION_DATE" ); ?></label>
		</div>
		
        <div class="controls">
			<?php echo JDom::_('html.fly.datetime', array(
				'dataKey' => 'creation_date',
				'dataObject' => $this->item,
				'dateFormat' => 'd-m-Y H:i:s'
			));?>
		</div>
    </div>
	<div class="control-group field-ip_address">
    	<div class="control-label">
			<label><?php echo JText::_( "JFORMS_FIELD_IP_ADDRESS" ); ?></label>
		</div>
		
        <div class="controls">
			<?php echo JDom::_('html.fly', array(
				'dataKey' => 'ip_address',
				'dataObject' => $this->item
			));?>
		</div>
    </div>
	<?php if($this->item->_form_id_generate_pdf){ ?>
	<div class="control-group field-pdf">
    	<div class="control-label">
			<label><?php echo JText::_( "JFORMS_FIELD_PDF" ); ?></label>
		</div>
		
        <div class="controls">
			<a href="<?php echo JRoute::_('index.php?option=com_jforms&task=file&path=[DIR_SUBMISSIONS_PDF]/'. $this->item->pdf .'&action=download', false); ?>"><?php echo $this->item->pdf ?></a>
		</div>
    </div>
	<?php } ?>
	<?php /*  ?>
	<div class="control-group field-password">
    	<div class="control-label">
			<label><?php echo JText::_( "JFORMS_FIELD_SECRET_PASSWORD" ); ?></label>
		</div>
		
        <div class="controls">
			<?php echo JDom::_('html.fly', array(
				'dataKey' => 'password',
				'dataObject' => $this->item
			));?>
		</div>
    </div>
	<?php */ ?>

</fieldset>
