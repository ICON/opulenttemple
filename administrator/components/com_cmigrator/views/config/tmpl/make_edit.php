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
JToolBarHelper::back();
JToolBarHelper::save();
JToolBarHelper::cancel();

?>
<form action="index.php" method="post" name="adminForm">
	<div class="width-60 fltlft">
		<fieldset class="adminform">
			<legend>
				<?php echo JText::_('COM_CMIGRATOR_NEW_CONFIGURATION'); ?>
			</legend>
			<ul class="adminformlist">
				<li>
					<label><?php echo JText::_("COM_CMIGRATOR_CMS_SELECTED"); ?></label>
					<span class="fltlft readonly"><?php echo $this->cms_make; ?></span>
				</li>
				<li>
					<label><?php echo JText::_("COM_CMIGRATOR_CONTENT_SELECTED"); ?></label>
					<span class="fltlft readonly"><?php echo $this->content_make; ?></span>
				</li>
				<li>
					<label><?php echo JText::_("COM_CMIGRATOR_NAME"); ?></label>
					<input type="text" name="name" value="<?php echo $this->settings->get('name', '');?>"/>

				</li>
				<li>
					<label><?php echo JText::_("COM_CMIGRATOR_PREFIX"); ?></label>
					<input type="text" name="prefix" value="<?php echo $this->settings->get('prefix', '');?>"/>
				</li>
			</ul>
		</fieldset>
		<fieldset class="adminform">
			<legend><?php echo JText::_("COM_CMIGRATOR_MIGRATE_WHAT"); ?></legend>
			<ul class="adminformlist">
				<li>
					<label>
						<?php if (isset($this->cms_make)) : ?>
						<?php if ($this->cms_make == 'drupal') : ?>
							<?php echo JText::_("COM_CMIGRATOR_IMPORT_CATEGORIES_DP"); ?>
							<?php else : ?>
							<?php echo JText::_("COM_CMIGRATOR_IMPORT_CATEGORIES_WP"); ?>
							<?php endif; ?>
						<?php else : ?>
						<?php echo JText::_("COM_CMIGRATOR_IMPORT_CATEGORIES"); ?>
						<?php endif; ?>
					</label>
					<?php echo $this->lists['categories']; ?>
				</li>
				<li>
					<label>

						<?php
						if ($this->cms_make == 'drupal')
							echo JText::_("COM_CMIGRATOR_IMPORT_CONTENT_DP");
						else
							echo JText::_("COM_CMIGRATOR_IMPORT_CONTENT_WP");
						?>

					</label>

					<?php echo $this->lists['content']; ?>

				</li>
				<li>
					<label>
						<?php
						echo JText::_("COM_CMIGRATOR_IMPORT_TAGS");
						?>
					</label>
					<?php echo $this->lists['tags']; ?>
				</li>
                                <li>
                                        <label>
						<?php
						echo JText::_("COM_CMIGRATOR_IMPORT_USERS");
						?>
					</label>
					<?php echo $this->lists['users']; ?>
				</li>
                                <?php if ($this->cms_make == 'wordpress') { ?>
                                <li>
                                        <label>
						<?php
						echo JText::_("COM_CMIGRATOR_IMPORT_COMMENTS");
						?>
					</label>
					<?php echo $this->lists['comments']; ?>
				</li>
                                <?php } ?>
                                <li>
                                        <label>
						<?php
						echo JText::_("COM_CMIGRATOR_CLEAN_MIGRATION");
						?>
					</label>
					<?php echo $this->lists['migration']; ?>
				</li>
			</ul>
		</fieldset>
	</div>
	<div class="width-40 fltrt">
		<fieldset class="adminform">
			<h2>
				<?php echo JText::_('COM_CMIGRATOR_IMPORTANT_PLEASE_READ'); ?>
			</h2>
			<hr/>
			<br/>

			<div>
				<p style="color:red">
					<?php echo JText::sprintf('COM_CMIGRATOR_MIGRATION_TABLES_NEED_TO_BE_IN_THE_JOOMLA_DB', ucfirst($this->cms_make)); ?>
				</p>
				<?php
				if ($this->cms_make == "drupal") {
					?>
					<ul>
						<li>
							<?php
							echo JText::_('COM_CMIGRATOR_IMPORT_RELATIONSHIP_DP');
							?>
						</li>
						<li>
							<?php
							echo JText::_('COM_CMIGRATOR_NO_COMMENTS_DP');
							?>
						</li>
					
					<?php } else { ?>
					<ul>
						<li>
							<?php
							echo JText::_('COM_CMIGRATOR_IMPORT_RELATIONSHIP_WP');
							?>
						</li>
						<li>
							<?php
							echo JText::_('COM_CMIGRATOR_WORDPRESS_POST_CAT_REL_WP');
							?>
						</li>
					<?php } ?>
                                        	<li>
							<?php
							echo JText::_('COM_CMIGRATOR_CLEAN_MIGRATION_INFO');
							?>
						</li>
                                                <li>
							<?php
							echo JText::_('COM_CMIGRATOR_CLEAN_MIGRATION_FOOTNOTE');
							?>
						</li>
					</ul>
			</div>
		</fieldset>
	</div>

        <?php if($this->config_id) {?>
        <input type="hidden" name="config_id" value ="<?php echo $this->config_id; ?>" />
        <?php }?>
	<input type="hidden" name="content_selected" value="<?php echo $this->content_make; ?>"/>
	<input type="hidden" name="cms" value="<?php echo $this->cms_make; ?>"/>
	<input type="hidden" name="option" value="com_cmigrator"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="view" value="config"/>
	<input type="hidden" name="layout" value="make_edit"/>
</form>
<script type="text/javascript">
    if(document.id('content').get('value') == 0) {
        document.id('tags').set('value', 0);
        document.id('tags').set('disabled' , 'true');
        document.id('comments').set('value', 0);
        document.id('comments').set('disabled' , 'true');
    }
    document.id('content').addEvent('change', function() {
        if(document.id('content').get('value') == 0) {
            document.id('tags').set('value', 0);
            document.id('tags').set('disabled' , 'true');
            document.id('comments').set('value', 0);
            document.id('comments').set('disabled' , 'true');
        } else {
            document.id('tags').erase('disabled');
            document.id('comments').erase('disabled');
        }
    });
</script>