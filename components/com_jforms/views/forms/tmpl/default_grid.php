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
				<th style="text-align:center;" width="100px">
					<?php echo JHTML::_('grid.sort',  "JFORMS_FIELD_NAME", 'a.name', $listDirn, $listOrder ); ?>
				</th>

				<th>
					<?php echo JText::_("JFORMS_FIELD_DESCRIPTION"); ?>
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
				<td style="text-align:left;" width="100px">
					<a href="<?php echo JRoute::_('index.php?option=com_jforms&view=submission&layout=submission&frm='. $row->id , false); ?>">
						<?php echo $row->name ;?>
					</a>
				</td>

				<td>
					<?php echo JDom::_('html.fly', array(
						'dataKey' => 'description',
						'dataObject' => $row
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
