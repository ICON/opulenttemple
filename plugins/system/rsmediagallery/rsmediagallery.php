<?php
/**
* @version 1.0.0
* @package RSMediaGallery! 1.0.0
* @copyright (C) 2012 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-3.0.html
*/

defined('_JEXEC') or die('Restricted access');

class plgSystemRSMediaGallery extends JPlugin
{
	var $_buffer;
	var $_css 		= array();
	var $_js 		= array();
	var $_js_inline = array();
	
	function _addStyleSheet($file, $fullpath=false)
	{
		static $template;
		static $is16;
		
		if (empty($template))
		{
			$app	  =& JFactory::getApplication();
			$template = $app->getTemplate();
		}
		if (!is_bool($is16))
		{
			$jversion 	= new JVersion();
			$is16 		= $jversion->isCompatible('1.6.0');
		}
		
		if ($fullpath)
		{
			$path = $file;
		}
		else
		{
			if (file_exists(JPATH_SITE.DS.'templates'.DS.$template.DS.'html'.DS.'plg_system_rsmediagallery'.DS.'assets'.DS.'css'.DS.$file))
				$path = JURI::root(true).'/templates/'.$template.'/html/plg_system_rsmediagallery/assets/css/'.$file;
			elseif ($is16)
				$path = JURI::root(true).'/plugins/system/rsmediagallery/plg_system_rsmediagallery/assets/css/'.$file;
			else
				$path = JURI::root(true).'/plugins/system/plg_system_rsmediagallery/assets/css/'.$file;
		}
		
		$this->_css[] = '<link rel="stylesheet" href="'.$this->escape($path).'" type="text/css" />';
	}
	
	function _addScript($file, $fullpath=false)
	{
		static $is16;
		
		if (!is_bool($is16))
		{
			$jversion 	= new JVersion();
			$is16 		= $jversion->isCompatible('1.6.0');
		}
		if ($fullpath)
		{
			$path = $file;
		}
		else
		{
			if ($is16)
				$path = JURI::root(true).'/plugins/system/rsmediagallery/plg_system_rsmediagallery/assets/js/'.$file;
			else
				$path = JURI::root(true).'/plugins/system/plg_system_rsmediagallery/assets/js/'.$file;
		}
		
		$this->_js[] = '<script type="text/javascript" src="'.$this->escape($path).'"></script>';
	}
	
	function _addScriptDeclaration($declaration)
	{
		$this->_js_inline[] = $declaration;
	}
	
	function _cleanUp()
	{
		$this->_buffer = str_replace('</head>', implode("\r\n", $this->_js)."\r\n".'<script type="text/javascript">'.implode("\r\n", $this->_js_inline).'</script>'."\r\n".implode("\r\n", $this->_css)."\r\n".'</head>', $this->_buffer);
		JResponse::setBody($this->_buffer);
		unset($this->_buffer, $this->_css, $this->_js, $this->_js_inline);
	}
	
	function plgSystemRSMediaGallery( &$subject, $params )
	{
		parent::__construct( $subject, $params );
		
		if (file_exists(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_rsmediagallery'.DS.'helpers'.DS.'helper.php'))
		{
			require_once JPATH_ADMINISTRATOR.DS.'components'.DS.'com_rsmediagallery'.DS.'helpers'.DS.'helper.php';			
			$this->params = RSMediaGalleryHelper::parseParams($this->params);
		}
	}
	
	function onAfterRender()
	{
		$mainframe =& JFactory::getApplication();
		
		// we do not have to run in the admin section
		if ($mainframe->isAdmin())
			return true;
		
		if (!file_exists(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_rsmediagallery'.DS.'helpers'.DS.'helper.php') || !file_exists(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_rsmediagallery'.DS.'helpers'.DS.'jquery.php'))
		{
			$lang =& JFactory::getLanguage();
			$lang->load('plg_system_rsmediagallery', JPATH_ADMINISTRATOR);
			JError::raiseWarning(500, JText::_('RSMG_SYSTEM_PLUGIN_COMPONENT_NOT_INSTALLED_OR_UPDATED'));
			return true;
		}
		
		$this->_buffer = JResponse::getBody();
			
		// simple performance check to determine whether bot should process further
		if (strpos($this->_buffer, '{rsmediagallery') === false)
		{
			unset($this->_buffer);
			return true;
		}
		
		$pattern = '/{rsmediagallery\s+(.*?)}/i';
		if (preg_match_all($pattern, $this->_buffer, $matches, PREG_SET_ORDER))
		{
			jimport('joomla.application.component.helper');
			
			require_once JPATH_ADMINISTRATOR.DS.'components'.DS.'com_rsmediagallery'.DS.'helpers'.DS.'jquery.php';
			
			$componentParams = JComponentHelper::getParams('com_rsmediagallery');
			$jqueryHelper 	 =& RSMediaGalleryjQuery::getInstance();
			
			// handle template overrides
			$this->_addStyleSheet('style.css');
			$this->_addStyleSheet($componentParams->get('contrast', 'light').'.css');
			
			$src = JDEBUG ? '.src' : '';
			
			// JS
			$this->_buffer = $jqueryHelper->replacejQuery($this->_buffer);
			
			$this->_addScript(JURI::root(true).'/components/com_rsmediagallery/assets/js/jquery.ui'.$src.'.js', true);
			$this->_addScriptDeclaration("jQuery.noConflict();");
			$this->_addScript('jquery.pirobox'.$src.'.js');
			$this->_addScript('jquery.script'.$src.'.js');
			
			require_once JPATH_ADMINISTRATOR.DS.'components'.DS.'com_rsmediagallery'.DS.'helpers'.DS.'helper.php';
			jimport('joomla.html.parameter');
		
			foreach ($matches as $index => $match)
			{
				$attributes = JUtility::parseAttributes($match[1]);
				$registry 	= JRegistry::getInstance(md5($match[1]));
				$registry->loadArray($attributes);				
				$data = $registry->toString();
				$data = new JParameter($data);
				
				$tmp_params = clone($this->params);
				
				// can't have both, it would look distorted
				if ($data->get('thumb_width') > 0)
					$data->set('thumb_height', 0);
				elseif ($data->get('thumb_height') > 0)
					$data->set('thumb_width', 0);
				
				$tmp_params->merge($data);
				
				$this->_buffer = str_replace($match[0], $this->_getGallery($index, $tmp_params, $match[0]), $this->_buffer);
			}
			
			$this->_cleanUp();
			
			return true;
		}
	}
	
	function _getGallery($index, $params, $text)
	{
		$html = '';
		
		$tags = $params->get('tags');
		if (!$tags)
			return $text;
			
		$order 				= $params->get('ordering', $this->params->get('ordering', 'ordering'));
		$direction			= $params->get('direction', $this->params->get('direction', 'ASC'));
		$limit				= (int) $params->get('limit', $this->params->get('limit', 0));
		$show_title			= (int) $params->get('show_title', $this->params->get('show_title', 1));
		$show_description 	= (int) $params->get('show_description', $this->params->get('show_description', 1));
		$use_original 		= (int) $params->get('use_original', 0);
		$image				= (int) $params->get('image', 0);
		
		$items = RSMediaGalleryHelper::getItems($tags, $order, $direction, 0, $limit);
		
		if ($items)
		{
			$html .= '<ul class="rsmg_system_gallery">';
			foreach ($items as $i => $item)
			{
				$item 			= RSMediaGalleryHelper::parseItem($item, $params);
				
				$small_image 	= $item->thumb;
				$big_image   	= $item->full;
				$thumb_width 	= $item->thumb_width;
				$thumb_height 	= $item->thumb_height;
				
				$title			= '';
				if ($show_title || $show_description)
				{
					if ($show_title)
						$title .= '<b>'.$item->title.'</b>';
					if ($show_description)
						$title .= ($show_title ? '<br />' : '').$item->full_description;
					$title = ' title="'.$this->escape($title).'"';
				}
				
				$html .= (!$image || $image && $i+1 == $image) ? '<li>' : '<li style="display: none;">';
				$html .= '<div class="rsmg_system_container">';
				$html .= '<a href="'.$big_image.'" rel="gallery" class="pirobox_gall_system'.$index.'"'.$title.'><img src="'.$small_image.'" width="'.$thumb_width.'" height="'.$thumb_height.'" alt="'.$this->escape($item->title).'" /></a>';
				$html .= '</div>';
				$html .= '</li>';
			}
			$html .= '</ul>';
			$html .= '<span class="rsmg_system_clear"></span>';
		}
		
		return $html;
	}
	
	function escape($string)
	{
		return RSMediaGalleryHelper::escape($string);
	}
}