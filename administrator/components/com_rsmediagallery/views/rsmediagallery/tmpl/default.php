<?php
/**
* @version 1.0.0
* @package RSMediaGallery! 1.0.0
* @copyright (C) 2012 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-3.0.html
*/

defined('_JEXEC') or die('Restricted access');

JHTML::_('behavior.keepalive');
?>
<div id="rsmg_wrapper">
    <div id="rsmg_container">
    	<div id="rsgallery_container_top">
        	<form action="index.php?option=com_rsmediagallery" id="rsmg_filter_form">
        	<div class="rsmg_filter_toolbar">
            	<ul>
                	<li id="rsmg_column"><a href="javascript: void(0);"><?php echo JText::_('RSMG_TITLE'); ?></a>
                    	<ul>
                        	<li><a href="javascript: void(0);" rel="title" class="rsmg_tick"><?php echo JText::_('RSMG_TITLE'); ?></a></li>
                        	<li><a href="javascript: void(0);" rel="description"><?php echo JText::_('RSMG_DESC'); ?></a></li>
                        	<li><a href="javascript: void(0);" rel="tags"><?php echo JText::_('RSMG_TAGS'); ?></a></li>
                        	<li><a href="javascript: void(0);" rel="published"><?php echo JText::_('RSMG_PUBLISHED'); ?></a></li>
                        </ul>
                    </li>
                    <li id="rsmg_operator"><a href="javascript: void(0);"><?php echo JText::_('RSMG_CONTAINS'); ?></a>
                    	<ul>
                        	<li><a href="javascript: void(0);" rel="is"><?php echo JText::_('RSMG_IS'); ?></a></li>
                            <li><a href="javascript: void(0);" rel="is_not"><?php echo JText::_('RSMG_IS_NOT'); ?></a></li>
                            <li><a href="javascript: void(0);" rel="contains" class="rsmg_tick"><?php echo JText::_('RSMG_CONTAINS'); ?></a></li>
                            <li><a href="javascript: void(0);" rel="contains_not"><?php echo JText::_('RSMG_DOES_NOT_CONTAIN'); ?></a></li>                            
                        </ul>
                    </li>
                    <li>
                        <input type="text" value="" id="rsmg_filter_text" />
                    </li>                    	                    	
                </ul>
            </div><!-- .rsmg_filter_toolbar -->
				
            <div class="rsmg_filter_toolbar">            
            	<ul>
                	<li id="rsmg_order"><a href="javascript: void(0);"><?php echo $this->translate($this->ordering); ?></a>
                    	<ul>
                        	<li><a href="javascript: void(0);" rel="ordering" <?php if ($this->ordering == 'i.ordering') { ?>class="rsmg_tick"<?php } ?>><?php echo JText::_('RSMG_FREE_ORDERING'); ?></a></li>
                        	<li><a href="javascript: void(0);" rel="title" <?php if ($this->ordering == 'i.title') { ?>class="rsmg_tick"<?php } ?>><?php echo JText::_('RSMG_TITLE'); ?></a></li>
                        	<li><a href="javascript: void(0);" rel="description" <?php if ($this->ordering == 'i.description') { ?>class="rsmg_tick"<?php } ?>><?php echo JText::_('RSMG_DESC'); ?></a></li>
                        	<li><a href="javascript: void(0);" rel="tags" <?php if ($this->ordering == 't.tag') { ?>class="rsmg_tick"<?php } ?>><?php echo JText::_('RSMG_TAGS'); ?></a></li>
                        	<li><a href="javascript: void(0);" rel="hits" <?php if ($this->ordering == 'i.hits') { ?>class="rsmg_tick"<?php } ?>><?php echo JText::_('RSMG_HITS'); ?></a></li>
							<li><a href="javascript: void(0);" rel="created" <?php if ($this->ordering == 'i.created') { ?>class="rsmg_tick"<?php } ?>><?php echo JText::_('RSMG_CREATED_DATE'); ?></a></li>
							<li><a href="javascript: void(0);" rel="modified" <?php if ($this->ordering == 'i.modified') { ?>class="rsmg_tick"<?php } ?>><?php echo JText::_('RSMG_MODIFIED_DATE'); ?></a></li>
                        </ul>
                    </li>
                    <li id="rsmg_direction" class="rsmg_no_background"><a href="javascript: void(0);"><?php echo $this->translate($this->direction); ?></a>
                    	<ul>
                        	<li><a href="javascript: void(0);" rel="asc" <?php if ($this->direction == 'asc') { ?>class="rsmg_tick"<?php } ?>><?php echo JText::_('RSMG_ASCENDING'); ?></a></li>
                        	<li><a href="javascript: void(0);" rel="desc" <?php if ($this->direction == 'desc') { ?>class="rsmg_tick"<?php } ?>><?php echo JText::_('RSMG_DESCENDING'); ?></a></li>
                        </ul>
                    </li>
                    <li></li>                    	                    	
                </ul>
            </div><!-- .rsmg_filter_toolbar -->
			
			<div class="rsmg_filter_toolbar">            
            	<ul>
                    <li id="rsmg_limit" class="rsmg_no_background"><a href="javascript: void(0);"><?php echo JText::sprintf('RSMG_ITEMS_PER_PAGE', $this->limit); ?></a>
                    	<ul>
                        	<li><a href="javascript: void(0);" rel="5" <?php if ($this->limit == 5) { ?>class="rsmg_tick"<?php } ?>><?php echo JText::sprintf('RSMG_ITEMS_PER_PAGE', 5); ?></a></li>
                        	<li><a href="javascript: void(0);" rel="10" <?php if ($this->limit == 10) { ?>class="rsmg_tick"<?php } ?>><?php echo JText::sprintf('RSMG_ITEMS_PER_PAGE', 10); ?></a></li>
                        	<li><a href="javascript: void(0);" rel="15" <?php if ($this->limit == 15) { ?>class="rsmg_tick"<?php } ?>><?php echo JText::sprintf('RSMG_ITEMS_PER_PAGE', 15); ?></a></li>
                        	<li><a href="javascript: void(0);" rel="20" <?php if ($this->limit == 20) { ?>class="rsmg_tick"<?php } ?>><?php echo JText::sprintf('RSMG_ITEMS_PER_PAGE', 20); ?></a></li>
                        	<li><a href="javascript: void(0);" rel="25" <?php if ($this->limit == 25) { ?>class="rsmg_tick"<?php } ?>><?php echo JText::sprintf('RSMG_ITEMS_PER_PAGE', 25); ?></a></li>
							<li><a href="javascript: void(0);" rel="30" <?php if ($this->limit == 30) { ?>class="rsmg_tick"<?php } ?>><?php echo JText::sprintf('RSMG_ITEMS_PER_PAGE', 30); ?></a></li>
                        	<li><a href="javascript: void(0);" rel="50" <?php if ($this->limit == 50) { ?>class="rsmg_tick"<?php } ?>><?php echo JText::sprintf('RSMG_ITEMS_PER_PAGE', 50); ?></a></li>
                        	<li><a href="javascript: void(0);" rel="100" <?php if ($this->limit == 100) { ?>class="rsmg_tick"<?php } ?>><?php echo JText::sprintf('RSMG_ITEMS_PER_PAGE', 100); ?></a></li>
                        </ul>
                    </li>
                    <li></li>                    	                    	
                </ul>
            </div><!-- .rsmg_filter_toolbar -->
			
			<button type="button" id="rsmg_filter" class="rsmg_button"><?php echo JText::_('RSMG_FILTER'); ?></button>
			
            </form>
            <a href="javascript: void(0);" id="rsmg_select_all"><?php echo JText::_('RSMG_SELECT_ALL'); ?></a>
            <div id="rsmg_info"></div>
        </div><!-- rsgallery_container_top -->
		<ul id="rsmg_filters">
		<?php if ($this->columns) { ?>
			<?php for ($i=0; $i<count($this->columns); $i++) { ?>
			<?php $is_published_column = $this->columns[$i] == 'published'; ?>
			<li>
				<span><?php echo $this->translate($this->columns[$i]); ?></span>
				<span><?php echo $this->translate($this->operators[$i]); ?></span>
				<strong><?php echo $is_published_column ? '' : $this->escape($this->values[$i]); ?></strong>
				<a class="rsmg_close" <?php if ($is_published_column) { ?>style="margin-left: 0; padding-left: 0; border-left: 0"<?php } ?> href="javascript: void(0);"></a>
				<input type="hidden" name="rsmg_columns[]" 	 value="<?php echo $this->escape($this->columns[$i]); ?>" 	/>
				<input type="hidden" name="rsmg_operators[]" value="<?php echo $this->escape($this->operators[$i]); ?>" />
				<input type="hidden" name="rsmg_values[]" 	 value="<?php echo $this->escape($this->values[$i]); ?>" 	/>
			</li>
			<?php } ?>
			<?php if (count($this->columns) > 2) { ?>
			<li id="rsmg_clear_filters"><?php echo JText::_('RSMG_CLEAR_ALL_FILTERS'); ?></li>
			<?php } ?>
		<?php } ?></ul>
        <ul id="rsmg_items" class="rsmg_full_width"></ul>
		<div id="rsmg_item_detail" class="rsmg_hidden">
			<form action="index.php?option=com_rsmediagallery" id="rsmg_save_form">
				<div class="rsmg_box_left">
					<p><label for="rsmg_detail_title"><?php echo JText::_('RSMG_TITLE'); ?></label><br /><input type="text" value="" name="title" id="rsmg_detail_title" /></p>
					<p><label for="rsmg_detail_tags"><?php echo JText::_('RSMG_TAGS'); ?></label><br /><textarea id="rsmg_detail_tags" name="tags"></textarea><small><?php echo JText::_('RSMG_TAGS_HINT'); ?></small></p>
					<p><label for="rsmg_detail_url"><?php echo JText::_('RSMG_URL'); ?></label><br /><input type="text" value="" name="url" id="rsmg_detail_url" /></p>
					<p><label for="rsmg_detail_description"><?php echo JText::_('RSMG_DESC'); ?></label><br /><textarea id="rsmg_detail_description" name="description"></textarea></p>
					<p><label><?php echo JText::_('RSMG_CREATED_DATE'); ?></label><br /><span id="rsmg_created_date"></span></p>
					<p><label><?php echo JText::_('RSMG_MODIFIED_DATE'); ?></label><br /><span id="rsmg_modified_date"></span></p>
					<p><label for="rsmg_detail_hits"><?php echo JText::_('RSMG_HITS'); ?></label><br /><input type="text" value="" name="hits" id="rsmg_detail_hits" /></p>
					<p><input type="checkbox" name="published" id="rsmg_detail_published" /><label for="rsmg_detail_published"><?php echo JText::_('RSMG_PUBLISHED'); ?></label></p>
				</div>
				<div id="rsmg_box_thumb">
					<p><label><?php echo JText::_('RSMG_SELECT_THUMBNAIL'); ?></label></p>
					<p><input type="checkbox" name="free_aspect_ratio" id="rsmg_detail_free_aspect" onchange="rsmg_change_aspect_ratio()" onclick="rsmg_change_aspect_ratio()" /><label for="rsmg_detail_free_aspect"><?php echo JText::_('RSMG_FREE_ASPECT_RATIO'); ?></label></p>
					<p><img style="display: none;" src="" alt="" /></p>
				</div>
				<span class="rsmg_clear"></span>
				<p>
					<span id="rsmg_save_result"></span>
				</p>
				<p class="rsmg_buttons_container">
					<img style="display: none;" id="rsmg_detail_loader" src="components/com_rsmediagallery/assets/images/loader-small-darkgrey.gif" />
					<button type="button" class="rsmg_button_save"><?php echo JText::_('RSMG_SAVE'); ?></button>
					<button type="button" class="rsmg_button_apply"><?php echo JText::_('RSMG_APPLY'); ?></button>
					<button type="button" class="rsmg_button_cancel"><?php echo JText::_('RSMG_CANCEL'); ?></button>
				</p>
				<input type="hidden" id="rsmg_x1" value="" />
				<input type="hidden" id="rsmg_y1" value="" />
				<input type="hidden" id="rsmg_x2" value="" />
				<input type="hidden" id="rsmg_y2" value="" />
				<input type="hidden" id="rsmg_w" value="" />
				<input type="hidden" id="rsmg_h" value="" />
				<input type="hidden" id="rsmg_admin_w" value="" />
				<input type="hidden" id="rsmg_admin_h" value="" />
			</form>
			<div class="rsmg_left_arrow"></div>
		</div>
		<span class="rsmg_clear"></span>
        <a href="javascript: void(0);" id="rsmg_load_more"><?php echo JText::_('RSMG_LOAD_MORE'); ?></a>
    </div><!-- rsgallery_container -->   
</div><!-- rsgallery_main -->
<div id="rsmg_version">
<p><img src="components/com_rsmediagallery/assets/images/rsmediagallery.gif" alt="" valign="middle" /><?php echo JText::sprintf('RSMG_VERSION_TEXT', 'http://www.rsjoomla.com/joomla-extensions/joomla-gallery.html', _RSMEDIAGALLERY_VERSION, 'http://www.rsjoomla.com'); ?></p>
<p><?php echo JText::sprintf('RSMG_FREE_SOFTWARE_TEXT', 'http://www.gnu.org/licenses/gpl-3.0.html'); ?></p>
</div>
<div class="rsmg_hidden">
    <div id="rsmg_dialog_confirm_delete">
		<h2><?php echo JText::_('RSMG_WARNING'); ?></h2>
		<p><span class="ui-icon ui-icon-alert"></span><?php echo JText::_('RSMG_WARNING_PERMANENTLY_REMOVE'); ?></p>
	</div>
	<div id="rsmg_dialog_message">
		<h2><?php echo JText::_('RSMG_INFORMATION'); ?></h2>
		<p><?php echo JText::_('RSMG_PLEASE_SELECT_ITEMS'); ?></p>
		<div><img src="components/com_rsmediagallery/assets/images/tutorial-multiple-selection.jpg" alt="<?php echo JText::_('RSMG_TUTORIAL_MULTIPLE_SELECTION_IMAGE'); ?>" /></div>
	</div>
	<?php if ($this->hasNoItems) { ?>
	<div id="rsmg_dialog_no_items">
		<h2><?php echo JText::_('RSMG_INFORMATION'); ?></h2>
		<p><?php echo JText::_('RSMG_PLEASE_UPLOAD_ITEMS'); ?></p>
		<div><img src="components/com_rsmediagallery/assets/images/tutorial-upload-items.jpg" alt="<?php echo JText::_('RSMG_TUTORIAL_UPLOAD_ITEMS_IMAGE'); ?>" /></div>
	</div>
	<?php } ?>
    <div id="rsmg_dialog_tag">
    	<h2><?php echo JText::_('RSMG_TAG_MULTIPLE_FILES'); ?></h2>
        <p>
        	<label for="rsmg_tags_text"><?php echo JText::_('RSMG_TAGS'); ?></label>
            <textarea name="tags" id="rsmg_tags_text"></textarea>
            <small><?php echo JText::_('RSMG_TAGS_DESC'); ?></small>
		</p>
        <p>
        	<?php echo JText::_('RSMG_TAG_ACTIONS_TO_PERFORM'); ?><br />
            <input name="actions" type="radio" value="" id="rsmg_action0" checked="checked" /><label for="rsmg_action0"><?php echo JText::_('RSMG_ADD'); ?></label>
            <input name="actions" type="radio" value="" id="rsmg_action1" /><label for="rsmg_action1"><?php echo JText::_('RSMG_REMOVE'); ?></label>
        </p>
		<p id="rsmg_tags_text_error"><?php echo JText::_('RSMG_PLEASE_ENTER_TAGS'); ?></p>
    </div>
	<div id="rsmg_dialog_upload">
		<h2><?php echo JText::_('RSMG_ADD_MULTIPLE_FILES'); ?></h2>
        <p>
        	<label for="rsmg_add_tags_text"><?php echo JText::_('RSMG_ADD_TAGS_AUTOMATICALLY'); ?></label>
            <textarea name="tags" id="rsmg_add_tags_text"></textarea>
            <small><?php echo JText::_('RSMG_TAGS_DESC'); ?></small>
		</p>
		<p>
			<input type="checkbox" name="published" id="rsmg_add_published" value="1" /> <label for="rsmg_add_published"><?php echo JText::_('RSMG_ADD_PUBLISHED_AUTOMATICALLY'); ?></label>
		</p>
		<p>
			<label for="rsmg_add_files"><?php echo JText::_('RSMG_SELECT_FILES_TO_UPLOAD'); ?></label>
			<input type="file" id="rsmg_add_files" name="upload" value="select files" multiple="multiple" />
		</p>
		<p>
			<ul id="rsmg_add_results"></ul>
		</p>
	</div>
</div>