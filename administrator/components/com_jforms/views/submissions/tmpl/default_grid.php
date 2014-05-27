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


JHtml::addIncludePath(JPATH_ADMIN_JFORMS.'/helpers/html');
JHtml::_('behavior.tooltip');
//JHtml::_('behavior.multiselect');

$model		= $this->model;
$user		= JFactory::getUser();
$userId		= $user->get('id');
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
$saveOrder	= $listOrder == 'a.ordering' && $listDirn != 'desc';
?>
<div class="clearfix"></div>
<div class="">
	<table class='table' id='grid-submissions'>
		<thead>
			<tr>
				<th class="row_id">
					<?php echo JText::_( 'NUM' ); ?>
				</th>

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

				<th style="text-align:center">
					<?php echo JHTML::_('grid.sort',  "JFORMS_FIELD_ID", 'a.id', $listDirn, $listOrder ); ?>
				</th>

				<th style="text-align:center" width="100px">
					<?php echo JHTML::_('grid.sort',  "JFORMS_FIELD_IP_ADDRESS", 'a.ip_address', $listDirn, $listOrder ); ?>
				</th>

				<th style="text-align:center" width="100px">
					<?php echo JHTML::_('grid.sort',  "JFORMS_FIELD_CREATED_BY_USERNAME", '_created_by_.username', $listDirn, $listOrder ); ?>
				</th>

				<th style="text-align:center">
					<?php echo JHTML::_('grid.sort',  "JFORMS_FIELD_FORM", 'a.form_id', $listDirn, $listOrder ); ?>
				</th>

				<th style="text-align:center">
					<?php echo JHTML::_('grid.sort',  "JFORMS_FIELD_FORM_NAME", '_form_id_.name', $listDirn, $listOrder ); ?>
				</th>

				<th style="text-align:center">
					<?php echo JText::_("JFORMS_FIELD_PDF"); ?>
				</th>

				<th style="text-align:center" width="100px">
					<?php echo JHTML::_('grid.sort',  "JFORMS_FIELD_CREATION_DATE", 'a.creation_date', $listDirn, $listOrder ); ?>
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
				<td class="row_id">
					<?php echo $this->pagination->getRowOffset($i); ?>
				</td>

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
								'task' => 'submission.edit'
							));?>
						<?php endif; ?>
						<?php if ($model->canDelete()): ?>
							<?php echo JDom::_('html.grid.task', array(
								'alertConfirm' => 'JFORMS_TOOLBAR_ARE_YOU_SURE_TO_DELETE_THIS_ITEM',
								'commandAcl' => array('core.delete.own', 'core.delete'),
								'enabled' => ((bool)$row->params->get('access-delete')),
								'label' => 'JFORMS_JTOOLBAR_DELETE',
								'num' => $i,
								'task' => 'submission.delete'
							));?>
						<?php endif; ?>
					</div>
				</td>

				<td style="text-align:center">
					<?php echo JDom::_('html.fly', array(
						'dataKey' => 'id',
						'dataObject' => $row
					));?>
				</td>

				<td style="text-align:center" width="100px">
					<?php echo JDom::_('html.fly', array(
						'dataKey' => 'ip_address',
						'dataObject' => $row,
						'route' => array('view' => 'submission','layout' => 'submission','cid[]' => $row->id)
					));?>
				</td>

				<td style="text-align:center" width="100px">
					<?php echo JDom::_('html.fly', array(
						'dataKey' => '_created_by_username',
						'dataObject' => $row
					));?>
				</td>

				<td style="text-align:center">
					<?php echo JDom::_('html.fly', array(
						'dataKey' => 'form_id',
						'dataObject' => $row
					));?>
				</td>

				<td style="text-align:center">
					<?php echo JDom::_('html.fly', array(
						'dataKey' => '_form_id_name',
						'dataObject' => $row
					));?>
				</td>

				<td style="text-align:center">
					<?php echo JDom::_('html.fly.file', array(
						'dataKey' => 'pdf',
						'dataObject' => $row,
						'height' => 'auto',
						'indirect' => true,
						'num' => $i,
						'root' => '[DIR_SUBMISSIONS_PDF]',
						'target' => 'download',
						'width' => 'auto'
					));?>
				</td>

				<td style="text-align:center" width="100px">
					<?php echo JDom::_('html.fly.datetime', array(
						'dataKey' => 'creation_date',
						'dataObject' => $row,
						'dateFormat' => 'd-m-Y H:i:s'
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
	$body = JText::_("JFORMS_SELECT_EXPORT_FORMAT"). '<br />
	<select name="export_format" id="export_format">
		<option value="">'. JText::_("JFORMS_SELECT") .'</option>
		<option value="csv">CSV</option>
		<option value="xlsx">XLSX</option>
		<option value="xls">XLS</option>
		<option value="pdf">PDF</option>
		<option value="xml">XML</option>
	</select>';
	
	$footer = '<a class="btn btn-cancel" onclick="jQuery(\'#export_format\').val(\'\');" data-dismiss="modal" aria-hidden="true">'. JText::_("JCANCEL") .'</a>'
		.	'<a class="btn btn-primary btn-apply">'. JText::_("JFORMS_DOWNLOAD") .'</a>';
			
	echo JDom::_('html.fly.bootstrap.modal', array(
			'domId' => 'export_modal',
			'title' => JText::_("JFORMS_SELECT_EXPORT_OPTIONS"),
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
			echo "var jtoolbar_button = jQuery('#toolbar-extension .toolbar');";
		} else {
			echo "var jtoolbar_button = jQuery('#toolbar-download button');";
		}
	?>
		var on_click = jtoolbar_button.attr('onclick'),
			on_click = jtoolbar_button.attr('onclick'),
			boxchecked_required = on_click.indexOf('document.adminForm.boxchecked.value');
		
			jtoolbar_button.attr('onclick','');
		
		jtoolbar_button.on('click',function(){
			if(boxchecked_required >= 0){
				var alert_boxchecked = on_click.split('else');
				if (document.adminForm.boxchecked.value==0){
					alert('<?php echo JText::_("JLIB_HTML_PLEASE_MAKE_A_SELECTION_FROM_THE_LIST"); ?>');
				} else {
					jQuery('#export_modal').modal('show');
				}
			} else {
				jQuery('#import_modal').modal('show');
			}
		});
		
		jQuery('#export_modal .btn-apply').on('click',function(){
			var format = jQuery('#export_format').val();
			if(format != ''){
				eval(on_click);
			}
		});
		
		jQuery('#export_modal').on('hidden',function(){
			jQuery('#export_format').val('');
		});
	});
</script>