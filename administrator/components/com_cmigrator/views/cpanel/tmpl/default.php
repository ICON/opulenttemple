<?php
/**
 * CMigrator - Adminstrator
 * @Copyright (C) 2010 - Daniel Dimitrov - http://compojoom.com
 * @All rights reserved
 * @license under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
 **/

defined('_JEXEC') or die('Restricted access');

JHTML::_('behavior.tooltip');
JHtml::stylesheet('media/com_cmigrator/css/cmigrator.css');
JHtml::script('media/com_cmigrator/js/migration.js');
JHtml::script('media/com_cmigrator/js/parsing.js');
JHtml::script('media/com_cmigrator/js/proceed.js');
JHtml::script('media/com_cmigrator/js/progress_bar.js');
JToolBarHelper::title(JText::_('COM_CMIGRATOR_TITLE'), 'cmigrator-logo');
?>
<script type="text/javascript">
    window.addEvent('domready', function() {
        <?php if (count($this->configurations)) {?>
        var options = {
            baseUrl : '<?php echo JURI::base(); ?>'
        };        
        var migrate = new Migration(options);
        <?php }?>
        var options = {
            task : 'migration'
        };
        proceed_migration = new CmigrateProceed(options);
        var options = {
            task : 'parsing'
        }
        proceed_parsing = new CmigrateProceed(options);
        <?php if (count($this->configurations)) {?>
        var options = {
            baseUrl : '<?php echo JURI::base(); ?>'
        };        
        var parse = new Parsing(options);
        <?php }?>
    });
</script>
<div id="cmigrator" class="ui-corner-all fltlft width-60">
	<div class="cpanel">
		<div class="icon-wrapper hasTip"
			 title="<?php echo JText::_('COM_CMIGRATOR_CONFIGURATION') . '::' . JText::_('COM_CMIGRATOR_CONFIGURATION_DESC');?>">
			<div class="icon">
				<a href="<?php echo JRoute::_('index.php?option=com_cmigrator&view=config'); ?>">

					<img src="<?php echo JURI::root(); ?>/media/com_cmigrator/images/config.png"/>

					<span><?php echo JText::_('COM_CMIGRATOR_CONFIGURATION'); ?></span>
				</a>
			</div>
		</div>
                <div class="icon-wrapper hasTip"
			 title="<?php echo JText::_('COM_CMIGRATOR_PARSE') . '::' . JText::_('COM_CMIGRATOR_PARSE_DESC');?>">
			<div class="icon">
				<a href="#"
				   onclick='return (confirm("<?php echo JText::_('COM_CMIGRATOR_WARNING_BEFORE_PARSE'); ?>")) ? proceed_parsing.createElement() : false; '>

					<img src="<?php echo JURI::root(); ?>/media/com_cmigrator/images/parse.png"/>

					<span><?php echo JText::_('COM_CMIGRATOR_PARSE'); ?></span>
				</a>
			</div>
		</div>
		<div class="icon-wrapper hasTip"
			 title="<?php echo JText::_('COM_CMIGRATOR_MIGRATE') . '::' . JText::_('COM_CMIGRATOR_MIGRATE_DESC');?>">
			<div class="icon">
				<a href="#"
                                   onclick='return (confirm("<?php echo JText::_('COM_CMIGRATOR_WARNING_BEFORE_MIGRATION'); ?>")) ? proceed_migration.createElement() : false; '>

					<img src="<?php echo JURI::root(); ?>/media/com_cmigrator/images/migrate.png"/>

					<span><?php echo JText::_('COM_CMIGRATOR_MIGRATE'); ?></span>
				</a>
			</div>
		</div>
		<div class="icon-wrapper">
			<?php echo LiveUpdate::getIcon(); ?>
		</div>
		<div class="clr"></div>
	</div>

</div>
<div class="width-35 fltlft">
	<fieldset class="adminform">
		<legend><?php echo JText::_('COM_CMIGRATOR_SOME_GENERAL_INFO'); ?></legend>
		<ul class="adminformlist">
			<li>
				<label>
					<?php echo JText::_('COM_CMIGRATOR_SUPPORT'); ?>
				</label>

				<span class="fltlft readonly">
					<a target="_new" href="https://compojoom.com">https://compojoom.com</a>
				</span>
			</li>

			<li>
				<label>
					<?php echo JText::_('COM_CMIGRATOR_LICENCE'); ?>
				</label>

				<span class="fltlft readonly">
					<a target="_blank" title="GNU General Public License, version 2"
					   href="http://www.gnu.org/licenses/old-licenses/gpl-2.0.html">GNU General Public License, version
						2</a>
				</span>
			</li>
			<li>
				<label>
					<?php echo JText::_('COM_CMIGRATOR_COPYRIGHT'); ?>
				</label>

				<span class="fltlft readonly">
					<a target="_blank" title="compojoom.com"
					   href="https://compojoom.com">2008 - <?php echo date('Y'); ?> compojoom.com </a>
				</span>
			</li>
			<li>
				<label>
					<?php echo JText::_('COM_CMIGRATOR_VERSION'); ?>
				</label>

				<span class="fltlft readonly">
					<?php echo CMIGRATE_VERSION; ?>
				</span>
			</li>
		</ul>
	</fieldset>
</div>

<div id="inner-migration" class="m"
	 style="visibility: hidden; width:300px; height: 125px; background : #fff; position: absolute; z-index: 9999">
	<div id="close-migration" style="text-align: right"><img
		src="<?php echo JURI::root() . 'media/com_cmigrator/images/close.png';?>"
		onclick="proceed_migration.close()" style="cursor: pointer"/></div>
	<?php if (count($this->configurations)) : ?>
		<form action="index.php?option=com_cmigrator&view=migrate" method="post" name="adminForm" id="migration">
			<div style="padding-left: 10px;">
				<h2><?php echo JText::_('COM_CMIGRATOR_CONFIGURATION'); ?></h2>
				<span><?php echo JText::_('COM_CMIGRATOR_LOAD_CONF'); ?></span>
				<?php echo $this->select_migrate; ?>
                                <div style="position: absolute; bottom: 10px; right: 10px; width: 100%;text-align: right;">
                                    <input type="hidden" value="ERROR" id="content" name="content"/>
                                    <input type="hidden" value="ERROR" id="categories" name="categories" />
                                    <input type="hidden" value="ERROR" id="tags" name="tags" />
                                    <input type="hidden" value="ERROR" id="users" name="users" />
                                    <input type="hidden" value="ERROR" id="comments" name="comments" />
                                    <input type="submit" value="<?php echo JText::_('COM_CMIGRATOR_MIGRATE');?>"/>
                                </div>
			</div>
		</form>
	<?php else : ?>
		<h2><?php echo JText::_('COM_CMIGRATOR_NO_CONFIGURATION'); ?></h2>
		<p>
			<?php echo JText::sprintf('COM_CMIGRATOR_NO_CONFIGURATION_DESC', JRoute::_('index.php?option=com_cmigrator&view=config')); ?>
		</p>
	<?php endif; ?>
        <div id="migration-bar" style="position: absolute; top: 30px; width: 100%; font-size: 15px; font-weight: 400; text-align: center; margin-left: -10px;"></div>
        <div id="progress-bar-migrate" style="position: absolute; bottom: 30px; left:60px; border-radius: 10px;"></div>        
</div>
<div id="inner-parsing" class="m"
	 style="visibility: hidden; width:300px; height: 125px; background : #fff; position: absolute; z-index: 9999">
	<div id="close-parsing" style="text-align: right"><img
		src="<?php echo JURI::root() . 'media/com_cmigrator/images/close.png';?>"
		onclick="proceed_parsing.close()" style="cursor: pointer" title="Close" />
        </div>
        <?php if (count($this->configurations)) : ?>
	<form action="index.php?option=com_cmigrator&view=parse" method="post" name="adminForm" id="parsing">
		<div style="padding-left: 10px;">
			<h2><?php echo JText::_('COM_CMIGRATOR_PARSE_CONF'); ?></h2>
			<span><?php echo JText::_('COM_CMIGRATOR_LOAD_CONF'); ?></span>
                        <?php echo $this->select_parse; ?>
                        <input type="hidden" value="0" id="content-counter" name="content-counter" />
                        <input type="hidden" value="0" id="images-counter" name="images-counter" />
                        <input type="hidden" value="" id="content-ids" name="content-ids" />
                        <input type="hidden" value="" id="images-ids" name="images-ids" />
                        <input type="hidden" value="" id="config-id" name="config-id" />
			<div style="position: absolute; bottom: 10px; right: 10px; width: 100%;text-align: right;">
                            <input type="submit" value="<?php echo JText::_('COM_CMIGRATOR_PARSE'); ?>"/>
			</div>
		</div>
	</form>
        <?php else : ?>
            <h2><?php echo JText::_('COM_CMIGRATOR_NO_CONFIGURATION'); ?></h2>
            <p><?php echo JText::sprintf('COM_CMIGRATOR_NO_CONFIGURATION_DESC', JRoute::_('index.php?option=com_cmigrator&view=config')); ?></p>
	<?php endif; ?>
        <div id="parsing-bar" style="position: absolute; top: 30px; width: 100%; font-size: 15px; font-weight: 400; text-align: center; margin-left: -10px;"></div>
        <div id="progress-bar-parse" style="position: absolute; bottom: 30px; left:60px; border-radius: 10px;"></div>
</div>