<?php
/**
* @version 1.0.0
* @package RSMediaGallery! 1.0.0
* @copyright (C) 2012 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-3.0.html
*/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

class RSMediaGalleryController extends JController
{
	function __construct()
	{
		parent::__construct();
	}
	
	function createThumb()
	{
		$session =& JFactory::getSession();
		$res	 = JRequest::getCmd('resolution');
		$hash	 = md5($res.JPATH_SITE);		
		if ($session->get('com_rsmediagallery.thumbs.'.$hash) || !empty($_SESSION['com_rsmediagallery.thumbs.'.$hash]))
		{
			$model	   	 			=& $this->getModel('rsmediagallery');
			$mainframe 	 			=& JFactory::getApplication();
			$db 	   	 			=& JFactory::getDBO();
			$admin_thumb 			= $res == '280x210';
			list($width, $height) 	= explode('x', $res);
			
			// do not unset, for now
			//$session->clear('com_rsmediagallery.thumbs.'.$hash);
			//unset($_SESSION['com_rsmediagallery.thumbs.'.$hash]);
			
			// get item from database
			$db->setQuery("SELECT * FROM #__rsmediagallery_items WHERE `filename`='".$db->getEscaped($model->_getId())."'");
			if ($item = $db->loadObject())
			{
				$id 			  = $item->filename;
				$item->params	  = unserialize($item->params);
				$upload_location  = JPATH_SITE.DS.'components'.DS.'com_rsmediagallery'.DS.'assets'.DS.'gallery';
				$thumb_location	  = $admin_thumb ? $upload_location : $upload_location.DS.$res;
				
				// cached file does not exist - must recreate it
				if (!file_exists($thumb_location.DS.$id))
				{
					require_once JPATH_ADMINISTRATOR.DS.'components'.DS.'com_rsmediagallery'.DS.'helpers'.DS.'phpthumb'.DS.'phpthumb.class.php';
					
					$phpThumb = new phpThumb();
					$phpThumb->src = $upload_location.DS.'original'.DS.$id;
					$phpThumb->w   = $width;
					$phpThumb->h   = $height;
					$phpThumb->iar = 1;
					$phpThumb->zc  = 0;
					
					$ratio = $item->params['info'][0] / 380;
					
					$phpThumb->sx = round($item->params['selection']['x1']*$ratio);
					$phpThumb->sy = round($item->params['selection']['y1']*$ratio);
					$phpThumb->sw = round(($item->params['selection']['x2'] - $item->params['selection']['x1'])*$ratio);
					$phpThumb->sh = round(($item->params['selection']['y2'] - $item->params['selection']['y1'])*$ratio);
					
					// bug fix
					if ($phpThumb->sx == 1 && $phpThumb->sw == $item->params['info'][0] - 1)
					{
						$phpThumb->sx = 0;
						$phpThumb->sw = $item->params['info'][0];
					}
					if ($phpThumb->sy == 1 && $phpThumb->sh == $item->params['info'][1] - 1)
					{
						$phpThumb->sy = 0;
						$phpThumb->sh = $item->params['info'][1];
					}
					
					if ($phpThumb->GenerateThumbnail())
					{
						if (!$admin_thumb && !is_dir($thumb_location))
						{
							jimport('joomla.filesystem.file');
							jimport('joomla.filesystem.folder');
							
							// set folder permissions
							$componentParams = JComponentHelper::getParams('com_rsmediagallery');
							$perms 			 = octdec('0'.$componentParams->get('folder_perms', '755'));
							
							if (JFolder::create($thumb_location, $perms))
							{
								$buffer = '<html><body bgcolor="#FFFFFF"></body></html>';
								JFile::write($thumb_location.DS.'index.html', $buffer);
							}
						}
						if ($phpThumb->RenderToFile($thumb_location.DS.$id))
						{
							// set permissions as setup in the configuration screen
							$componentParams = JComponentHelper::getParams('com_rsmediagallery');
							$perms 			 = octdec('0'.$componentParams->get('file_perms', '644'));
							@chmod($thumb_location.DS.$id, $perms);
							
							$phpThumb->OutputThumbnail();
						}
					}
				}
				else
				{
					require_once JPATH_ADMINISTRATOR.DS.'components'.DS.'com_rsmediagallery'.DS.'helpers'.DS.'phpthumb'.DS.'phpthumb.functions.php';
					
					jimport('joomla.filesystem.file');
					
					header('Content-Type: '.phpthumb_functions::ImageTypeToMIMEtype(JFile::getExt($id)));
					header('Content-Disposition: inline; filename="'.$id.'"');
					
					echo JFile::read($thumb_location.DS.$id);
				}
			}
		}
		
		jexit();
	}
	
	function getItems()
	{
		JRequest::setVar('view',   'rsmediagallery');
		JRequest::setVar('layout', 'items');
		JRequest::setVar('format', 'raw');
		
		parent::display();
	}
	
	function downloadItem()
	{
		$model =& $this->getModel('rsmediagallery');
		$model->downloadItem();
	}
	
	function hitItem()
	{
		$cid   =  JRequest::getVar('cid');
		$model =& $this->getModel('rsmediagallery');
		$model->hitItem($cid);
		
		jexit();
	}
}