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
		JToolBarHelper::title(' ','rsmediagallery');
		
		$jversion = new JVersion();
		
		$src = JDEBUG ? '.src' : '';
		
		$document =& JFactory::getDocument();
		
		$document->addScript('components/com_rsmediagallery/assets/js/jquery'.$src.'.js?v=3');
		$document->addScriptDeclaration("jQuery.noConflict();");
		$document->addScript('components/com_rsmediagallery/assets/js/jquery.ui'.$src.'.js?v=3');
		$document->addScript('components/com_rsmediagallery/assets/js/jquery.superfish'.$src.'.js?v=3');
		$document->addScript('components/com_rsmediagallery/assets/js/jquery.timers'.$src.'.js?v=3');
		$document->addScript('components/com_rsmediagallery/assets/js/jquery.dropshadow'.$src.'.js?v=3');
		$document->addScript('components/com_rsmediagallery/assets/js/jquery.mbtooltip'.$src.'.js?v=3');
		$document->addScript('components/com_rsmediagallery/assets/js/jquery.jrumble'.$src.'.js?v=3');
		$document->addScript('components/com_rsmediagallery/assets/js/jquery.imgareaselect'.$src.'.js?v=3');
		$document->addScript('components/com_rsmediagallery/assets/js/jquery.fileupload'.$src.'.js?v=3');
		$document->addScript('components/com_rsmediagallery/assets/js/jquery.iframe-transport'.$src.'.js?v=3');
		$document->addScript('components/com_rsmediagallery/assets/js/jquery.postmessage-transport'.$src.'.js?v=3');
		$document->addScript('components/com_rsmediagallery/assets/js/jquery.xdr-transport'.$src.'.js?v=3');
		$document->addScript('components/com_rsmediagallery/assets/js/jquery.fancyzoom'.$src.'.js?v=3');
		$document->addScript('components/com_rsmediagallery/assets/js/jquery.script'.$src.'.js?v=3');
		$document->addScriptDeclaration("function rsmg_get_root() { return '".addslashes(rtrim(JURI::root(), '/'))."'; }");
		$document->addScriptDeclaration("function rsmg_get_lang(id) {
			switch (id)
			{
				default: return id;
				case 'RSMG_UNPUBLISHED': 	return '".JText::_('RSMG_UNPUBLISHED', true)."'; break;
				case 'RSMG_PUBLISHED': 		return '".JText::_('RSMG_PUBLISHED', true)."'; break;
				case 'RSMG_PUBLISH_DESC': 	return '".JText::_('RSMG_PUBLISH_DESC', true)."'; break;
				case 'RSMG_UNPUBLISH_DESC': return '".JText::_('RSMG_UNPUBLISH_DESC', true)."'; break;
				case 'RSMG_PREVIEW_DESC': 	return '".JText::_('RSMG_PREVIEW_DESC', true)."'; break;
				case 'RSMG_EDIT_DESC': 		return '".JText::_('RSMG_EDIT_DESC', true)."'; break;
				case 'RSMG_DELETE_DESC': 	return '".JText::_('RSMG_DELETE_DESC', true)."'; break;
				case 'RSMG_LOAD_MORE': 		return '".JText::_('RSMG_LOAD_MORE', true)."'; break;
				case 'RSMG_LOAD_ALL': 		return '".JText::_('RSMG_LOAD_ALL', true)."'; break;
				case 'RSMG_ARE_YOU_SURE': 	return '".JText::_('RSMG_ARE_YOU_SURE', true)."'; break;
				case 'RSMG_DELETE': 		return '".JText::_('RSMG_DELETE', true)."'; break;
				case 'RSMG_CANCEL': 		return '".JText::_('RSMG_CANCEL', true)."'; break;
				case 'RSMG_CLOSE': 			return '".JText::_('RSMG_CLOSE', true)."'; break;
				case 'RSMG_SAVE_CHANGES': 	return '".JText::_('RSMG_SAVE_CHANGES', true)."'; break;
				case 'RSMG_PLEASE_WAIT': 	return '".JText::_('RSMG_PLEASE_WAIT', true)."'; break;
				case 'RSMG_TAGS_HAVE_BEEN_ADDED': 	return '".JText::_('RSMG_TAGS_HAVE_BEEN_ADDED', true)."'; break;
				case 'RSMG_TAGS_HAVE_BEEN_REMOVED': return '".JText::_('RSMG_TAGS_HAVE_BEEN_REMOVED', true)."'; break;
				case 'RSMG_CLEAR_ALL_FILTERS': 		return '".JText::_('RSMG_CLEAR_ALL_FILTERS', true)."'; break;
				case 'RSMG_ITEMS_PER_PAGE': 		return '".JText::_('RSMG_ITEMS_PER_PAGE', true)."'; break;
				case 'RSMG_SELECT_ALL_PAGES': 		return '".JText::_('RSMG_SELECT_ALL_PAGES', true)."'; break;
				case 'RSMG_SELECT_ALL': 			return '".JText::_('RSMG_SELECT_ALL', true)."'; break;
				case 'RSMG_ITEM_SAVED': 	return '".JText::_('RSMG_ITEM_SAVED', true)."'; break;
				case 'RSMG_ITEM_DELETED': 	return '".JText::_('RSMG_ITEM_DELETED', true)."'; break;
				case 'RSMG_ITEMS_DELETED': 	return '".JText::_('RSMG_ITEMS_DELETED', true)."'; break;
				case 'RSMG_SELECTED_ITEMS': return '".JText::_('RSMG_SELECTED_ITEMS', true)."'; break;
			}
		}");
		
		$document->addStyleSheet('components/com_rsmediagallery/assets/css/jquery.ui.css');
		$document->addStyleSheet('components/com_rsmediagallery/assets/css/jquery.superfish.css');
		$document->addStyleSheet('components/com_rsmediagallery/assets/css/style.css');
		
		// IE fixes
		$document->addCustomTag('<!--[if IE]><link type="text/css" href="components/com_rsmediagallery/assets/css/ie.css" media="screen" rel="stylesheet" /><![endif]-->');
		$document->addCustomTag('<!--[if IE 7]><link type="text/css" href="components/com_rsmediagallery/assets/css/ie7.css" media="screen" rel="stylesheet" /><![endif]-->');
		
		$toolbar =& JToolBar::getInstance('toolbar');
		$toolbar->addButtonPath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_rsmediagallery'.DS.'helpers'.DS.'buttons');
		
		$toolbar->appendButton('RSMediaGallery', 'toolbar-start', '_default');
		$toolbar->appendButton('RSMediaGallery', 'upload');
		$toolbar->appendButton('RSMediaGallery', 'publish');
		$toolbar->appendButton('RSMediaGallery', 'unpublish');
		$toolbar->appendButton('RSMediaGallery', 'tag');
		$toolbar->appendButton('RSMediaGallery', 'remove');
		if ($jversion->isCompatible('1.6.0'))
		{
			$user =& JFactory::getUser();
			if ($user->authorise('core.admin', 'com_rsmediagallery'))
				JToolBarHelper::preferences('com_rsmediagallery');
		}
		else
			JToolBarHelper::preferences('com_rsmediagallery', '600');
		$toolbar->appendButton('RSMediaGallery', 'toolbar-end', '_default');
		$toolbar->appendButton('RSMediaGallery', 'toolbar-start', '_edit');
		$toolbar->appendButton('RSMediaGallery', 'save');
		$toolbar->appendButton('RSMediaGallery', 'apply');
		$toolbar->appendButton('RSMediaGallery', 'cancel');
		$toolbar->appendButton('RSMediaGallery', 'toolbar-end', '_edit');
		
		$filters = $this->get('filters');
		$this->assignRef('columns', 	$filters[0]);
		$this->assignRef('operators', 	$filters[1]);
		$this->assignRef('values', 		$filters[2]);
		
		$orderings = $this->get('orderings');
		$this->assignRef('ordering', $orderings[0]);
		$this->assignRef('direction', $orderings[1]);
		
		$this->assignRef('hasNoItems', $this->get('hasnoitems'));
		
		$this->assignRef('limit', $this->get('limit'));
		
		parent::display($tpl);
	}
	
	function translate($text)
	{
		switch ($text)
		{
			// columns & ordering
			case 'title':
			case 'i.title':
				return JText::_('RSMG_TITLE');
			break;
			
			case 'description':
			case 'i.description':
				return JText::_('RSMG_DESC');
			break;
			
			case 'tags':
			case 't.tag':
				return JText::_('RSMG_TAGS');
			break;
			
			case 'hits':
			case 'i.hits':
				return JText::_('RSMG_HITS');
			break;
			
			case 'published':
				return JText::_('RSMG_PUBLISHED');
			break;
			
			case 'created':
			case 'i.created':
				return JText::_('RSMG_CREATED_DATE');
			break;
			
			case 'modified':
			case 'i.modified':
				return JText::_('RSMG_MODIFIED_DATE');
			break;
			
			// operators
			case 'is':
				return JText::_('RSMG_IS');
			break;
			
			case 'is_not':
				return JText::_('RSMG_IS_NOT');
			break;
			
			case 'contains':
				return JText::_('RSMG_CONTAINS');
			break;
			
			case 'contains_not':
				return JText::_('RSMG_DOES_NOT_CONTAIN');
			break;
			
			// ordering
			case 'i.ordering':
				return JText::_('RSMG_FREE_ORDERING');
			break;
			
			// direction
			case 'asc':
				return JText::_('RSMG_ASCENDING');
			break;
			
			case 'desc':
				return JText::_('RSMG_DESCENDING');
			break;
		}
	}
}