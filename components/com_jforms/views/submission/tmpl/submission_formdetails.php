<?php
/**                               ______________________________________________
*                          o O   |                                              |
*                 (((((  o      <    Generated with Cook Self Service  V2.6.2   |
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
	<legend><?php echo JText::_('JFORMS_FIELDSET_FORM_DETAILS') ?></legend>

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

</fieldset>
