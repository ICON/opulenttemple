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
				<th>
					<?php echo JText::_("JFORMS_FIELD_FORM_NAME"); ?>
				</th>

				<th>
					<?php echo JText::_("JFORMS_FIELD_CREATION_DATE"); ?>
				</th>

				<th>
					<?php echo JText::_("JFORMS_FIELD_IP_ADDRESS"); ?>
				</th>

				<th>
					<?php echo JText::_("JFORMS_FIELD_PDF"); ?>
				</th>
				
				<th>
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
				<td class="form_details">
					<a href="<?php echo JRoute::_('index.php?option=com_jforms&view=submission&layout=submission&frm='. $row->form_id , false); ?>">
						<h4><?php echo $row->_form_id_name ;?></h4>
					</a>
					<?php echo $row->_form_id_description;?>
				</td>

				<td>
					<?php echo JDom::_('html.fly.datetime', array(
						'dataKey' => 'creation_date',
						'dataObject' => $row,
						'dateFormat' => 'd-m-Y H:i:s'
					));?>
				</td>

				<td>
					<?php echo JDom::_('html.fly', array(
						'dataKey' => 'ip_address',
						'dataObject' => $row
					));?>
				</td>

				<td>
				<?php if($row->_form_id_generate_pdf){ ?>
					<a href="<?php echo JRoute::_('index.php?option=com_jforms&task=file&path=[DIR_SUBMISSIONS_PDF]\\'. $row->pdf, false); ?>"><?php echo $row->pdf ?></a>
				<?php } ?>
				</td>

				
				<td>
					<a class="btn btn-small btn-info" href="<?php echo JRoute::_('index.php?option=com_jforms&view=submission&layout=submissiondetails&cid[]='. $row->id , false); ?>"><?php echo JText::_("JFORMS_DETAILS"); ?></a>
				</td>
			</tr>
			<?php
			$k = 1 - $k;
		endfor;
		?>
		</tbody>
	</table>
</div>
