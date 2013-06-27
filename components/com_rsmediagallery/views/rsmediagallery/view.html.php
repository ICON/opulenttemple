<?php
/**
* @version 1.0.0
* @package RSMediaGallery! 1.0.0
* @copyright (C) 2012 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-3.0.html
*/

defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view');

class RSMediaGalleryViewRSMediaGallery extends JView
{
	function display($tpl = null)
	{
		jimport('joomla.application.component.helper');
		
		require_once JPATH_ADMINISTRATOR.DS.'components'.DS.'com_rsmediagallery'.DS.'helpers'.DS.'helper.php';
		require_once JPATH_ADMINISTRATOR.DS.'components'.DS.'com_rsmediagallery'.DS.'helpers'.DS.'jquery.php';
		
		$mainframe 			=& JFactory::getApplication();
		$document  			=& JFactory::getDocument();
		$componentParams	= JComponentHelper::getParams('com_rsmediagallery');
		$jqueryHelper		=& RSMediaGalleryjQuery::getInstance();
		$params    			= $this->get('params');
		$jversion  			= new JVersion();
		
		// add jQuery library
		$jqueryHelper->addjQuery();

		$src = JDEBUG ? '.src' : '';
		
		$document->addScriptDeclaration("jQuery.noConflict();");
		$document->addScriptDeclaration("function rsmg_get_root() { return '".addslashes(rtrim(JURI::root(), '/'))."'; }");
		$document->addScriptDeclaration("rsmg_add_lang({'RSMG_LOAD_MORE': '".JText::_('RSMG_LOAD_MORE', true)."', 'RSMG_LOAD_ALL': '".JText::_('RSMG_LOAD_ALL', true)."', 'RSMG_DOWNLOAD': '".JText::_('RSMG_DOWNLOAD', true)."', 'RSMG_TAGS': '".JText::_('RSMG_TAGS', true)."', 'RSMG_HIT': '".JText::_('RSMG_HIT', true)."', 'RSMG_HITS': '".JText::_('RSMG_HITS', true)."', 'RSMG_CREATED': '".JText::_('RSMG_CREATED', true)."', 'RSMG_MODIFIED': '".JText::_('RSMG_MODIFIED', true)."'});");
		$document->addScript(JURI::root(true).'/components/com_rsmediagallery/assets/js/jquery.ui'.$src.'.js');
		$document->addScript(JURI::root(true).'/components/com_rsmediagallery/assets/js/jquery.script'.$src.'.js');
		$document->addStyleSheet(JURI::root(true).'/components/com_rsmediagallery/assets/css/style.css');
		$document->addStyleSheet(JURI::root(true).'/components/com_rsmediagallery/assets/css/'.($componentParams->get('contrast', 'light')).'.css');
		
		$layout = $this->getLayout();
		if ($layout == 'image')
		{
			$this->assignRef('item', $this->get('item'));
			// not an item ?
			if (!$this->item)
			{
				JError::raiseError(500, JText::_('RSMG_NOT_FOUND'));
				return;
			}
			
			if ($params->get('use_original', 0))
				$this->item->src = JURI::root(true).'/components/com_rsmediagallery/assets/gallery/original/'.$this->item->filename;
			else
				$this->item->src = RSMediaGalleryHelper::getImage($this->item, $params->get('full_width', 800), $params->get('full_height', 600), true);
			
			// set page title
			$document->setTitle($document->getTitle().' - '.$this->item->title);
			
			// set breadcrumbs
			$pathway =& $mainframe->getPathway();
			$pathway->addItem($this->item->title, '');
			
			// assign variables to the layout
			$this->assignRef('adjacent', $this->get('adjacentitems'));
			$this->assignRef('params', $params);
			$this->assign('isJ16',	$jversion->isCompatible('1.6.0'));
			
			// don't forget to increase the views
			$model = $this->getModel('rsmediagallery');
			$model->hitItem($this->item->id);
		}
		else
		{
			$document->addScript(JURI::root(true).'/components/com_rsmediagallery/assets/js/jquery.lightbox2'.$src.'.js');
			$document->addStyleSheet(JURI::root(true).'/components/com_rsmediagallery/assets/css/lightbox.css');
			
			$open_in = $params->get('open_in', 'slideshow');
			if ($open_in == 'slideshow')
			{
				$document->addScriptDeclaration("
				/* <![CDATA[ */
				function rsmg_init_lightbox2() {
					jQuery('#rsmg_gallery a.rsmg_lightbox').lightBox({
						imageCloseText: '".JText::_('RSMG_CLOSE_LIGHTBOX', true)."',
						ajaxFunction: function(settings) {
							more 			= false;
							original_length = jQuery('ul#rsmg_gallery').children().length;
							
							if (jQuery('#rsmg_load_more').length > 0 && !jQuery('#rsmg_load_more').is(':hidden'))
							{
								rsmg_get_items(jQuery, false, {}, function(data) {
									new_length = jQuery('ul#rsmg_gallery').children().length;
									if (new_length - original_length == 0)
										more = false;
									else
									{
										var images = jQuery('#rsmg_gallery a.rsmg_lightbox');
										for (j = original_length; j<new_length; j++)
										{
											var currentImage = jQuery(images[j]);
											var rel = jQuery(currentImage).attr('rel');
											var href = currentImage.attr('src');
											var title = '';
											var id = 0;
											if (typeof rel != 'undefined' && rel.indexOf('{') > -1 && rel.indexOf('}') > -1)
											{
												eval('var decoded_rel = ' + rel + ';');
												if (typeof decoded_rel == 'object')
												{
													if (typeof decoded_rel.link != 'undefined')
														href = decoded_rel.link;
													
													if (typeof decoded_rel.id != 'undefined')
														id = decoded_rel.id;
												}
											}
											settings.addImage(settings, href, jQuery('#rsmg_item_' + j).html(), id);
										}
										
										more = true;
									}
								}, false);
							}
							
							return more;
						},
						onImageLoad: rsmg_hit_item
					});
				}
				/* ]]> */
				");
			}
			
			$thumb_width = $params->get('thumb_width', 280);
			if ($thumb_width > 0)
				$document->addStyleDeclaration('ul#rsmg_gallery li div { width: '.(int) $thumb_width.'px; }');
			
			$items = $this->get('items');
			if ($items)
			{
				foreach ($items as $i => $item)
					$items[$i] = RSMediaGalleryHelper::parseItem($item, $params, true);
				RSMediaGalleryHelper::addTags($items);
			}
			
			// assign variables to the layout
			$this->assignRef('params', 		$params);
			$this->assignRef('items',  		$items);
			$this->assignRef('total',  		$this->get('total'));
			$this->assignRef('limitstart',  $this->get('limitstart'));
			$this->assignRef('limit', 		$this->get('limit'));
			$this->assign('more',   $this->limitstart + $this->limit < $this->total);
			$this->assign('prev',   $this->limitstart > 0);
			$this->assign('itemid', $this->get('itemid'));
			$this->assign('isJ16',	$jversion->isCompatible('1.6.0'));
		}
		
		parent::display($tpl);
	}
}