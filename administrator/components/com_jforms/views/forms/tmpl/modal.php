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



?>
<form action="<?php echo(JRoute::_("index.php")); ?>" method="post" name="adminForm" id="adminForm">
	<?php
	$compat = '1.6';
	$version = new JVersion();
	if ($version->isCompatible('3.0'))
		$compat = '3.0';
	?>

	<?php if ($compat == '3.0'): ?>
	<div class="row-fluid">
		<div id="contents" class="span12">
			<div>

				<!-- BRICK : toolbar_plur -->
				<?php echo $this->renderToolbar($this->items);?>
			</div>
			<div>

				<!-- BRICK : filters -->
				<div class="pull-left">
					<?php if ($this->canDo->get('core.edit.state')): ?>
						<?php echo $this->filters['filter_published']->input;?>
					<?php endif; ?>

				</div>
				<div class="pull-left">
					<?php echo $this->filters['filter_save_data_in_db']->input;?>
				</div>
				<div class="pull-left">
					<?php echo $this->filters['filter_layout_type']->input;?>
				</div>
				<div class="pull-right">
					<?php echo $this->filters['limit']->input;?>
				</div>
				<div class="clearfix"></div>
				<div class="pull-right">
					<?php echo $this->filters['search_search_1']->input;?>
				</div>

			</div>
			<div style="clear:both;">

				<!-- BRICK : grid -->
				<?php echo $this->loadTemplate('grid'); ?>
			</div>
			<div>

				<!-- BRICK : pagination -->
				<?php echo $this->pagination->getListFooter(); ?>
			</div>
		</div>
	</div>
	<?php elseif ($compat == '1.6'): ?>
	<div>
		<div>

			<!-- BRICK : toolbar_plur -->
			<?php echo $this->renderToolbar($this->items);?>
		</div>
		<div>

			<!-- BRICK : filters -->
			<div class="pull-left">
				<?php if ($this->canDo->get('core.edit.state')): ?>
					<?php echo $this->filters['filter_published']->input;?>
				<?php endif; ?>

			</div>
			<div class="pull-left">
				<?php echo $this->filters['filter_save_data_in_db']->input;?>
			</div>
			<div class="pull-left">
				<?php echo $this->filters['filter_layout_type']->input;?>
			</div>
			<div class="pull-right">
				<?php echo $this->filters['limit']->input;?>
			</div>
			<div class="clearfix"></div>
			<div class="pull-right">
				<?php echo $this->filters['search_search_1']->input;?>
			</div>

		</div>
		<div>

			<!-- BRICK : grid -->
			<?php echo $this->loadTemplate('grid'); ?>
		</div>
		<div>

			<!-- BRICK : pagination -->
			<?php echo $this->pagination->getListFooter(); ?>
		</div>
	</div>
	<?php endif; ?>


	<?php 
		$jinput = JFactory::getApplication()->input;
		echo JDom::_('html.form.footer', array(
		'values' => array(
					'view' => $jinput->get('view', 'forms'),
					'layout' => $jinput->get('layout', 'modal'),
					'object' => $jinput->get('object', null, 'cmd'),
					'boxchecked' => '0',
					'filter_order' => $this->escape($this->state->get('list.ordering')),
					'filter_order_Dir' => $this->escape($this->state->get('list.direction'))
				)));
	?>
</form>
