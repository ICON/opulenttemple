<?php

/**
 * CMigrator - Adminstrator
 * @Copyright (C) 2010 - Daniel Dimitrov - http://compojoom.com
 * @All rights reserved
 * @license under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
 **/

defined('_JEXEC') or die('Restricted access');
JToolBarHelper::title(JText::_('COM_CMIGRATOR_CONFIGURATION'), 'config.png');
JHtml::stylesheet('/media/com_cmigrator/css/cmigrator.css');
JToolBarHelper::editList();
JToolBarHelper::deleteList('Are you sure you want to delete this configuration?!', 'deleteConf');
JToolBarHelper::cancel();

?>
<form action="index.php?option=com_cmigrator&view=config&layout=make_edit"
	  method="post" name="adminForm"
	  id="adminForm">
	<div class="width-60 fltlft">
		<fieldset class="adminform">
			<legend>
				<?php echo JText::_("COM_CMIGRATOR_CONFIGURATIONS"); ?>
			</legend>
			<?php if (count($this->configs)) : ?>
			<table class="adminlist">
				<thead>
				<tr>
					<th align="center" width="1%" style="padding-left:10px">
						<input type="checkbox" onclick="Joomla.checkAll(this)"
							   title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>"
							   value=""
							   name="checkall-toggle"/>
					</th>
					<th align="center">
						<?php echo JText::_("COM_CMIGRATOR_NAME"); ?>
					</th>
					<th align="center">
						<?php echo JText::_("COM_CMIGRATOR_CMS_NAME"); ?>
					</th>
					<th align="center">
						<?php echo JText::_("COM_CMIGRATOR_CONTENT_NAME"); ?>
					</th>
				</tr>
				</thead>
				<tbody>
					<?php $i = 0; foreach ($this->configs as $config) : ?>
						<tr class="row<?php echo $i % 2; ?>">
							<td align='center'>
								<?php echo JHtml::_('grid.id', $i, $config->id); ?>
							</td>
							<td align='center'>
								<?php echo JText::_($config->name); ?>
							</td>
							<td align='center'>
								<?php echo JText::_($config->cms); ?>
							</td>
							<td align='center'>
								<?php
								$registry = new JRegistry($config->settings);
								echo $registry->get('content_selected');
								?>
							</td>
						</tr>
					<?php $i++; endforeach; ?>
				</tbody>
			</table>
			<?php else : ?>
			<?php echo JText::_('COM_CMIGRATOR_YOU_NEED_A_CONFIG_FIRST'); ?>

			<?php endif; ?>
		</fieldset>

		<fieldset class="adminform">
			<legend><?php echo JText::_("COM_CMIGRATOR_NEW_CONFIG"); ?></legend>

			<div class="width-30 fltlft">
				<h3>
					<?php echo JText::_("COM_CMIGRATOR_CMSTYPE"); ?>
				</h3>

				<?php echo $this->cms_cmigrator; ?>
			</div>

			<div class="width-30 fltlft">
				<h3>
					<?php echo JText::_("COM_CMIGRATOR_MIGRATE_TO"); ?>
				</h3>
				<?php echo $this->content_cmigrator; ?>
			</div>
			<div class="width-30 fltlft" style="padding-top:10px">
				<a href="#" onclick="Joomla.submitbutton('add');">
					<img src="../media/com_cmigrator/images/config_add.png"
						 alt="<?php echo JText::_("COM_CMIGRATOR_NEW_CONFIGURATION"); ?>"
						 title="<?php echo JText::_("COM_CMIGRATOR_NEW_CONFIG"); ?>"/>
				</a>
			</div>

		</fieldset>
	</div>
	<div class="width-40 fltrt">
		<fieldset class="adminform">
			<h2>
				<?php echo JText::_('COM_CMIGRATOR_IMPORTANT_PLEASE_READ'); ?>
			</h2>
			<hr/>
			<br/>

			<div><?php echo JText::_('COM_CMIGRATOR_MIGRATION_INFO'); ?></div>
			<a href="https://compojoom.com" target="_blank">
				<img src="../media/com_cmigrator/images/compojoom.png"
					 alt="Compojoom" title="Compojoom">
			</a>
		</fieldset>
	</div>

	<input type="hidden" name="option" value="com_cmigrator"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="boxchecked" value="1"/>
	<input type="hidden" name="view" value="config"/>
</form>
