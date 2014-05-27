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
$fieldsets_fields = $this->form->getFieldset('form.fieldsets');
$fields_to_render = array('ordering','name','form_file','enabled');
?>
<?php
	echo JDom::_('html.list.table', array(
					'fieldsToRender' => $fields_to_render,
					'form' => $this->form,
					'fieldsetName' => 'form.fieldsets',
					'dataList' => $this->item->fieldsets,
					'tmplEngine' => 'doT'
				));
?>
