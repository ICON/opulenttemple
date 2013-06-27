<?php
/**
* @version 1.0.0
* @package RSMediaGallery! 1.0.0
* @copyright (C) 2012 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-3.0.html
*/

defined('_JEXEC') or die('Restricted access');
?>
<?php if ($this->isJ16) { ?>
	<?php if ($this->params->get('show_page_heading', 1)) { ?>
		<h1><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
	<?php } ?>
<?php } else { ?>
	<?php if ($this->params->get('show_page_title', 1)) { ?>
		<div class="componentheading<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>"><?php echo $this->escape($this->params->get('page_title')); ?></div>
	<?php } ?>
<?php } ?>
	
	<div id="rsmg_main" class="rsmg_fullwidth">
		<?php if ($this->prev) { ?>
		<a href="<?php echo RSMediaGalleryRoute::_('index.php?option=com_rsmediagallery&view=rsmediagallery'.($this->limitstart - $this->limit > 0 ? '&limitstart='.($this->limitstart - $this->limit) : '')); ?>" id="rsmg_prev_page" class="rsmg_big_button"><?php echo JText::_('RSMG_PREV_PAGE'); ?></a>
		<?php } ?>
		
		<?php if ($this->items) { ?>
		<ul id="rsmg_gallery">
			<?php foreach ($this->items as $i => $item) { ?>
			<li>
				<div class="rsmg_item_container">
					<a class="rsmg_lightbox" <?php if ($this->params->get('open_in_new_page', 0)) { ?>target="_blank"<?php } ?> href="<?php echo $item->href; ?>" rel="{'link': '<?php echo addslashes($item->full); ?>', 'title': '#rsmg_item_<?php echo $i; ?>', 'id': '<?php echo $item->id; ?>'}" title=""><img src="<?php echo $item->thumb; ?>" width="<?php echo $item->thumb_width; ?>" height="<?php echo $item->thumb_height; ?>" alt="<?php echo $this->escape($item->title); ?>" /></a>
					<?php if ($this->params->get('show_title_list', 1)) { ?>
					<a <?php if ($this->params->get('open_in_new_page', 0)) { ?>target="_blank"<?php } ?> href="<?php echo $item->href; ?>" class="rsmg_title"><?php echo $this->escape($item->title); ?></a>
					<?php } ?>
					<?php if ($this->params->get('show_description_list', 1)) { ?>
					<span class="rsmg_item_description"><?php echo $item->description; ?></span>
					<?php } ?>
					<div id="rsmg_item_<?php echo $i; ?>" style="display: none;">
						<?php if ($this->params->get('show_title_detail', 1)) { ?>
						<h2 class="rsmg_title"><?php echo $this->escape($item->title); ?></h2>
						<?php } ?>
						<?php if ($this->params->get('show_description_detail', 1)) { ?>
							<?php echo $item->full_description; ?>
						<?php } ?>
						<span class="rsmg_clear"></span>
						<?php if ($this->params->get('download_original', 1)) { ?>
						<div class="rsmg_download rsmg_toolbox"><a href="<?php echo RSMediaGalleryRoute::_('index.php?option=com_rsmediagallery&task=downloaditem&id='.$item->filepart.'&ext='.$item->fileext); ?>"><?php echo JText::_('RSMG_DOWNLOAD'); ?></a></div>
						<?php } ?>
						<?php if ($this->params->get('show_hits', 1)) { ?>
						<div class="rsmg_views rsmg_toolbox"><?php echo JText::sprintf($item->hits == 1 ? 'RSMG_HIT' : 'RSMG_HITS', $item->hits); ?></div>
						<?php } ?>
						<?php if ($this->params->get('show_created', 1)) { ?>
						<div class="rsmg_calendar rsmg_toolbox"><?php echo JText::sprintf('RSMG_CREATED', $this->escape($item->created)); ?></div>
						<?php } ?>
						<?php if ($this->params->get('show_modified', 1)) { ?>
						<div class="rsmg_calendar rsmg_toolbox"><?php echo JText::sprintf('RSMG_MODIFIED', $this->escape($item->modified)); ?></div>
						<?php } ?>
						<span class="rsmg_clear"></span>
						<?php if ($this->params->get('show_tags', 1)) { ?>
						<p class="rsmg_tags"><?php echo JText::_('RSMG_TAGS'); ?>: <strong><?php echo $this->escape($item->tags); ?></strong></p>
						<?php } ?>
					</div>
				</div>
			</li>
			<?php } ?>
		</ul><!-- rsmg_gallery -->
		<?php } ?>
		
		<?php if ($this->more) { ?>
		<a href="<?php echo RSMediaGalleryRoute::_('index.php?option=com_rsmediagallery&view=rsmediagallery&limitstart='.($this->limitstart + $this->limit)); ?>" rel="<?php echo $this->total - $this->limitstart; ?>" id="rsmg_load_more" class="rsmg_big_button"><?php echo JText::_('RSMG_NEXT_PAGE'); ?></a>
		<?php } ?>
		
		<input type="hidden" name="Itemid" id="rsmg_itemid" value="<?php echo $this->itemid; ?>" />
		<input type="hidden" id="rsmg_original_limitstart" value="<?php echo $this->limitstart; ?>" />
	</div><!-- rsmg_main -->
	<span class="rsmg_clear"></span>
	<?php if ($this->params->get('show_credits', 1)) { ?>
	<div id="rsmg_footer">
		<?php echo JText::sprintf('RSMG_FOOTER_CREDITS', 'http://www.rsjoomla.com/joomla-extensions/joomla-gallery.html', 'http://www.rsjoomla.com'); ?>
	</div>
	<?php } ?>