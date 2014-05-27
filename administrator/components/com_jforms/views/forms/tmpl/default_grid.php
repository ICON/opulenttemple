<?php
/**                               ______________________________________________
*                          o O   |                                              |
*                 (((((  o      <    Generated with Cook Self Service  V2.6.3   |
*                ( o o )         |______________________________________________|
* --------oOOO-----(_)-----OOOo---------------------------------- www.j-cook.pro --- +
* @version		0.2.9
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


JHtml::addIncludePath(JPATH_ADMIN_JFORMS.'/helpers/html');
JHtml::_('behavior.tooltip');
//JHtml::_('behavior.multiselect');

$model		= $this->model;
$user		= JFactory::getUser();
$userId		= $user->get('id');
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
$saveOrder	= $listOrder == 'a.ordering' && $listDirn != 'desc';
JDom::_('framework.sortablelist', array(
	'domId' => 'grid-forms',
	'listOrder' => $listOrder,
	'listDirn' => $listDirn,
	'formId' => 'adminForm',
	'ctrl' => 'forms',
	'proceedSaveOrderButton' => true,
));
?>
<div class="clearfix"></div>
<div class="">
	<table class='table' id='grid-forms'>
		<thead>
			<tr>
				<?php if ($model->canSelect()): ?>
				<th>
					<?php echo JDom::_('html.form.input.checkbox', array(
						'dataKey' => 'checkall-toggle',
						'title' => JText::_('JGLOBAL_CHECK_ALL'),
						'selectors' => array(
							'onclick' => 'Joomla.checkAll(this);'
						)
					)); ?>
				</th>
				<?php endif; ?>

				<th>

				</th>

				<?php if ($model->canEditState()): ?>
				<th style="text-align:center">
					<?php echo JHTML::_('grid.sort',  "JFORMS_HEADING_ORDERING", 'a.ordering', $listDirn, $listOrder ); ?>
				</th>
				<?php endif; ?>

				<th style="text-align:center" width="40px">
					<?php echo JHTML::_('grid.sort',  "JFORMS_FIELD_ID", 'a.id', $listDirn, $listOrder ); ?>
				</th>

				<th style="text-align:left">
					<?php echo JHTML::_('grid.sort',  "JFORMS_FIELD_ALIAS", 'a.alias', $listDirn, $listOrder ); ?>
				</th>

				<th style="text-align:left">
					<?php echo JHTML::_('grid.sort',  "JFORMS_FIELD_NAME", 'a.name', $listDirn, $listOrder ); ?>
				</th>

				<?php if ($model->canEditState()): ?>
				<th style="text-align:center" width="90px">
					<?php echo JHTML::_('grid.sort',  "JFORMS_FIELD_PUBLISHED", 'a.published', $listDirn, $listOrder ); ?>
				</th>
				<?php endif; ?>

				<th style="text-align:center">
					<?php echo JHTML::_('grid.sort',  "JFORMS_FIELD_SAVE_DATA_IN_DB", 'a.save_data_in_db', $listDirn, $listOrder ); ?>
				</th>

				<th style="text-align:center">
					<?php echo JHTML::_('grid.sort',  "JFORMS_FIELD_GENERATE_PDF", 'a.generate_pdf', $listDirn, $listOrder ); ?>
				</th>

				<th style="text-align:center">
					<?php echo JHTML::_('grid.sort',  "JFORMS_FIELD_LAYOUT_TYPE", 'a.layout_type', $listDirn, $listOrder ); ?>
				</th>
			</tr>
		</thead>
		<tbody>
		<?php
		$k = 0;
		for ($i=0, $n=count( $this->items ); $i < $n; $i++):
			$row = &$this->items[$i];
			?>

			<tr class="<?php echo "row$k"; ?>">
				<?php if ($model->canSelect()): ?>
				<td>
					<?php if ($row->params->get('access-edit') || $row->params->get('tag-checkedout')): ?>
						<?php echo JDom::_('html.grid.checkedout', array(
													'dataObject' => $row,
													'num' => $i
														));
						?>
					<?php endif; ?>
				</td>
				<?php endif; ?>

				<td>
					<div class="btn-group">
						<?php if ($model->canEdit()): ?>
							<?php echo JDom::_('html.grid.task', array(
								'commandAcl' => array('core.edit.own', 'core.edit'),
								'enabled' => ((bool)$row->params->get('access-edit')),
								'label' => 'JFORMS_JTOOLBAR_EDIT',
								'num' => $i,
								'task' => 'form.edit'
							));?>
						<?php endif; ?>
						<?php if ($model->canEditState()): ?>
							<?php echo JDom::_('html.grid.task', array(
								'commandAcl' => 'core.edit.state',
								'dataKey' => 'published',
								'dataObject' => $row,
								'enabled' => ($row->params->get('tag-published') != 1),
								'label' => 'JFORMS_JTOOLBAR_PUBLISH',
								'num' => $i,
								'task' => 'forms.publish'
							));?>
						<?php endif; ?>
						<?php if ($model->canEditState()): ?>
							<?php echo JDom::_('html.grid.task', array(
								'commandAcl' => 'core.edit.state',
								'dataKey' => 'published',
								'dataObject' => $row,
								'enabled' => ($row->params->get('tag-published') != 0),
								'label' => 'JFORMS_JTOOLBAR_UNPUBLISH',
								'num' => $i,
								'task' => 'forms.unpublish'
							));?>
						<?php endif; ?>
						<?php if ($model->canEditState()): ?>
							<?php echo JDom::_('html.grid.task', array(
								'alertConfirm' => 'JFORMS_TOOLBAR_ARE_YOU_SURE_TO_TRASH_THIS_ITEM',
								'commandAcl' => 'core.edit.state',
								'dataKey' => 'published',
								'dataObject' => $row,
								'enabled' => ($row->params->get('tag-published') != -2),
								'label' => 'JFORMS_JTOOLBAR_TRASH',
								'num' => $i,
								'task' => 'forms.trash'
							));?>
						<?php endif; ?>
					</div>
				</td>

				<?php if ($model->canEditState()): ?>
				<td style="text-align:center">
					<?php echo JDom::_('html.grid.ordering', array(
						'aclAccess' => 'core.edit.state',
						'dataKey' => 'ordering',
						'dataObject' => $row,
						'enabled' => $saveOrder
					));?>
				</td>
				<?php endif; ?>

				<td style="text-align:center" width="40px">
					<?php echo JDom::_('html.fly', array(
						'dataKey' => 'id',
						'dataObject' => $row
					));?>
				</td>

				<td style="text-align:left">
					<?php echo JDom::_('html.fly', array(
						'dataKey' => 'alias',
						'dataObject' => $row
					));?>
				</td>

				<td style="text-align:left">
					<?php echo JDom::_('html.fly', array(
						'dataKey' => 'name',
						'dataObject' => $row,
						'route' => array('view' => 'form','layout' => 'form','cid[]' => $row->id)
					));?>
				</td>

				<?php if ($model->canEditState()): ?>
				<td style="text-align:center" width="90px">
					<?php echo JDom::_('html.grid.publish', array(
						'ctrl' => 'forms',
						'dataKey' => 'published',
						'dataObject' => $row,
						'num' => $i
					));?>
				</td>
				<?php endif; ?>

				<td style="text-align:center">
					<?php echo JDom::_('html.grid.bool', array(
						'commandAcl' => array('core.edit.own', 'core.edit'),
						'ctrl' => 'form',
						'dataKey' => 'save_data_in_db',
						'dataObject' => $row,
						'num' => $i,
						'taskYes' => 'toggle_save_data_in_db',
						'viewType' => 'icon'
					));?>
				</td>

				<td style="text-align:center">
					<?php echo JDom::_('html.grid.bool', array(
						'commandAcl' => array('core.edit.own', 'core.edit'),
						'ctrl' => 'form',
						'dataKey' => 'generate_pdf',
						'dataObject' => $row,
						'num' => $i,
						'taskYes' => 'toggle_generate_pdf',
						'viewType' => 'icon'
					));?>
				</td>

				<td style="text-align:center">
					<?php echo JDom::_('html.fly.enum', array(
						'dataKey' => 'layout_type',
						'dataObject' => $row,
						'labelKey' => 'text',
						'list' => JformsHelper::enumList('forms', 'layout_type'),
						'listKey' => 'value'
					));?>
				</td>
			</tr>
			<?php
			$k = 1 - $k;
		endfor;
		?>
		</tbody>
	</table>
</div>

<!-- Modal -->
<?php 
	$body = JText::_("JFORMS_SELECT_IMPORT_BEHAVIOUR"). '<br />
	<select name="import_type" id="import_type">
		<option value="">'. JText::_("JFORMS_SELECT") .'</option>
		<option value="add">'. JText::_("JFORMS_IMPORT_TYPE_ADD") .'</option>
		<option value="replace">'. JText::_("JFORMS_IMPORT_TYPE_REPLACE") .'</option>
		<option value="clean">'. JText::_("JFORMS_IMPORT_TYPE_CLEAN") .'</option>
	</select>
	<div class="import_behaviour add_info">'. JText::_("JFORMS_IMPORT_TYPE_ADD_INFO") .'</div>
	<div class="import_behaviour replace_info">'. JText::_("JFORMS_IMPORT_TYPE_REPLACE_INFO") .'</div>
	<div class="import_behaviour clean_info">'. JText::_("JFORMS_IMPORT_TYPE_CLEAN_INFO") .'</div>
	
	<br />
	
	'. JText::_("JFORMS_SELECT_IMPORT_FILE") .'
	<input type="file" id="import_file" name="import_file" value="" />	
	';
	
	$footer = '<a class="btn btn-cancel" onclick="jQuery(\'#import_type\').val(\'\');" data-dismiss="modal" aria-hidden="true">'. JText::_("JCANCEL") .'</a>'
		.	'<a class="btn btn-primary btn-apply">'. JText::_("JFORMS_UPLOAD") .'</a>';
			
	echo JDom::_('html.fly.bootstrap.modal', array(
			'domId' => 'import_modal',
			'title' => JText::_("JFORMS_SELECT_IMPORT_OPTIONS"),
			'body' => $body,
			'footer' => $footer
		));

?>

<script type="text/javascript">
	jQuery(document).ready(function(){
	<?php 
		$version = new JVersion();
		// Joomla! 1.6 - 1.7 - 2.5
		if (version_compare($version->RELEASE, '2.5', '<=')){	
			echo "var jtoolbar_button = jQuery('#toolbar-upload .toolbar');";
		} else {
			echo "var jtoolbar_button = jQuery('#toolbar-database button');";
		}
	?>
		var on_click = jtoolbar_button.attr('onclick'),
			boxchecked_required = on_click.indexOf('document.adminForm.boxchecked.value');
		
			jtoolbar_button.attr('onclick','');
		
		jtoolbar_button.on('click',function(){
			if(boxchecked_required >= 0){
				var alert_boxchecked = on_click.split('else');
				if (document.adminForm.boxchecked.value==0){
					alert('<?php echo JText::_("JLIB_HTML_PLEASE_MAKE_A_SELECTION_FROM_THE_LIST"); ?>');
				} else {
					jQuery('#import_modal').modal('show');
				}
			} else {
				jQuery('#import_modal').modal('show');
			}
		});
		
		jQuery('#import_modal .btn-apply').on('click',function(){
			var type = jQuery('#import_type').val();
			var file = jQuery('#import_file').val();
			if(type != '' && file != ''){
				eval(on_click);
			}
		});
		
		jQuery('#import_modal').on('hidden',function(){
			jQuery('#import_type').val('');
			jQuery('#import_file').val('');
		});
		
		jQuery('#import_type').on('change',function(){
			// hide all info
			jQuery('.import_behaviour').slideUp();
			
			var selectedVal = jQuery(this).val();
			jQuery('.import_behaviour.'+ selectedVal +'_info').slideDown();
			
		});
	});
</script>