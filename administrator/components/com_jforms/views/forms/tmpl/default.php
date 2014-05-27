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
<form action="<?php echo(JRoute::_("index.php")); ?>" method="post" enctype="multipart/form-data" name="adminForm" id="adminForm">
	<?php
	$compat = '1.6';
	$version = new JVersion();
	if ($version->isCompatible('3.0'))
		$compat = '3.0';
	?>

	<?php if ($compat == '3.0'): ?>
	<div class="row-fluid">
		<div id="sidebar" class="span2">
			<div class="sidebar-nav">
				<div>

					<!-- BRICK : menu -->
					<?php echo JDom::_('html.menu.submenu', array(
						'list' => $this->menu
					)); ?>
				</div>
				<div>

					<!-- BRICK : filters -->
					<hr class="hr-condensed">
					<div class="filter-select hidden-phone">
						<h4 class="page-header"><?php echo JText::_('JSEARCH_FILTER_LABEL');?></h4>
						<?php if ($this->canDo->get('core.edit.state')): ?>
							<?php echo $this->filters['filter_published']->input;?>
						<?php endif; ?>

						<hr class="hr-condensed">
						<?php echo $this->filters['filter_save_data_in_db']->input;?>
						<hr class="hr-condensed">
						<?php echo $this->filters['filter_layout_type']->input;?>
					</div>
				</div>
			</div>
		</div>
		<div id="contents" class="span10">
			<div>

				<!-- BRICK : toolbar_plur -->
				<?php echo $this->renderToolbar($this->items);?>
			</div>
			<div>

				<!-- BRICK : filters -->
				<div class="pull-left">
					<?php echo $this->filters['search_search']->input;?>
				</div>
				<div class="pull-right">
					<?php echo $this->filters['limit']->input;?>
				</div>
				<div class="pull-right">
					<?php echo $this->filters['directionTable']->input;?>
				</div>
				<div class="pull-right">
					<?php echo $this->filters['sortTable']->input;?>
				</div>
				<div class="clearfix"></div>

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
			<div class="pull-right">
				<?php echo $this->filters['directionTable']->input;?>
			</div>
			<div class="pull-right">
				<?php echo $this->filters['sortTable']->input;?>
			</div>
			<div class="clearfix"></div>
			<div class="pull-right">
				<?php echo $this->filters['search_search']->input;?>
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
					'layout' => $jinput->get('layout', 'default'),
					'boxchecked' => '0',
					'filter_order' => $this->escape($this->state->get('list.ordering')),
					'filter_order_Dir' => $this->escape($this->state->get('list.direction'))
				)));
	?>
</form>
