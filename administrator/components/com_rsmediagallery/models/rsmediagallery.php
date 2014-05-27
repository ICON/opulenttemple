<?php
/**
* @version 1.0.0
* @package RSMediaGallery! 1.0.0
* @copyright (C) 2012 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-3.0.html
*/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

class RSMediaGalleryModelRSMediaGallery extends JModel
{
	var $_result = array();
	
	function __construct()
	{
		parent::__construct();
		
		$mainframe 		= &JFactory::getApplication();
		
		$this->_db 		= &JFactory::getDBO();
		$this->_option	= 'com_rsmediagallery';
		$this->_query 	= $this->_buildQuery();
		
		// Get pagination request variables
		// no need to remember pages now
		//$limit 		= $mainframe->getUserStateFromRequest($this->_option.'.items.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
		//$limitstart 	= $mainframe->getUserStateFromRequest($this->_option.'.items.limitstart', 'limitstart', 0, 'int');
		
		$limit 		= $this->getLimit();
		$limitstart = JRequest::getInt('limitstart', 0);

		// In case limit has been changed, adjust it
		// no need to adjust it now
		//$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
		
		$this->setState($this->_option.'.items.limit', $limit);
		$this->setState($this->_option.'.items.limitstart', $limitstart);
	}
	
	function _buildQuery($split=false)
	{
		list($columns, $operators, $values) = $this->getFilters();
		list($order, $direction)			= $this->getOrderings();
		$join		= '';
		$where 		= array();
		$group		= '';
		$having		= '';
		$where_tags = array();
		$num_tags	= 0;
		
		if ($order == 't.tag')
			$join = "LEFT JOIN #__rsmediagallery_tags t ON (i.id=t.item_id)";
		
		for ($i=0; $i<count($columns); $i++)
		{
			$column 	= $columns[$i];			
			$operator 	= $operators[$i];
			$value 		= $values[$i];
			$is_tag		= false;
			
			switch ($column)
			{
				case 'tags':
					$join 	= "LEFT JOIN #__rsmediagallery_tags t ON (i.id=t.item_id)";
					$column = 't.tag';
					$num_tags++;
					$is_tag = true;
				break;
				
				case 'published':
					$column = 'i.'.$column;
					$value 	= '1';
				break;
				
				default:
					$column = 'i.'.$column;
				break;
			}
			
			switch ($operator)
			{
				case 'contains':
					$operator = 'LIKE';
					$value	  = '%'.str_replace('%', '\%', $value).'%';
				break;
				
				case 'contains_not':
					$operator = 'NOT LIKE';
					$value	  = '%'.str_replace('%', '\%', $value).'%';
				break;
				
				case 'is':
					$operator = '=';
				break;
				
				case 'is_not':
					$operator = '<>';
				break;
			}
			
			if ($is_tag)
				$where_tags[] = "(".$this->_db->getEscaped($column)." ".$operator." '".$this->_db->getEscaped($value)."')";
			else
				$where[] = "AND (".$this->_db->getEscaped($column)." ".$operator." '".$this->_db->getEscaped($value)."')";
		}
		
		$where = implode(' ', $where);
		if ($where_tags)
		{
			$where .= " AND (".implode(" OR ", $where_tags).")";
			$group  = " GROUP BY t.item_id ";
			$having	= " HAVING COUNT(t.tag) = '".(int) $num_tags."'";
		}
		
		$query = "SELECT i.* FROM #__rsmediagallery_items i ".$join." WHERE 1 ".$where." ".$group." ".$having." ORDER BY ".$this->_db->getEscaped($order)." ".$this->_db->getEscaped($direction);
		
		if ($split)
		{
			return array(
				'select' 	=> 'i.*',
				'join'	 	=> $join,
				'where'  	=> $where,
				'group'		=> $group,
				'having'	=> $having,
				'order'	 	=> $order,
				'direction'	=> $direction
			);
		}
		
		return $query;
	}
	
	function getItems()
	{		
		if (empty($this->_data))
			$this->_data = $this->_getList($this->_query, $this->getState($this->_option.'.items.limitstart'), $this->getState($this->_option.'.items.limit'));
		
		return $this->_data;
	}
	
	function getHasNoItems()
	{
		$this->_db->setQuery("SELECT id FROM #__rsmediagallery_items LIMIT 1");
		return !$this->_db->loadResult() ? true : false;
	}
	
	function getTotal()
	{
		if (empty($this->_total))
			$this->_total = $this->_getOptimizedListCount($this->_query, 'COUNT(i.id)');
		
		return $this->_total;
	}
	
	function _getOptimizedListCount($query, $count='COUNT(*)')
	{
		if (strpos($query,'FROM') !== false)
		{
			$tmp   = explode('FROM', $query, 2);
			$query = "SELECT $count FROM ".$tmp[1];
			$this->_db->setQuery($query);
			return $this->_db->loadResult($query);
		}
		
		return $this->_getListCount($query);
	}
	
	function delItems()
	{
		$select_all = JRequest::getInt('selectall');
		
		// import classes
		jimport('joomla.filesystem.folder');
		jimport('joomla.filesystem.file');
		
		// path where files are stored
		$path = JPATH_SITE.DS.'components'.DS.'com_rsmediagallery'.DS.'assets'.DS.'gallery';
		
		// get all other thumbs
		$folders = JFolder::folders($path, 'x', false, true, array('original'));
		
		$cid = JRequest::getVar('cid');
		JArrayHelper::toInteger($cid);
		
		if ($select_all) // delete all items
		{
			extract($this->_buildQuery(true));
			
			// get ids
			$this->_db->setQuery("SELECT i.id, i.filename FROM #__rsmediagallery_items i ".$join." WHERE 1 ".$where.$group.$having);
			$tmp = $this->_db->loadObjectList();
			$cid   = array();
			$files = array();
			foreach ($tmp as $result)
			{
				$cid[]   = $result->id;
				$files[] = $result->filename;
			}
		}
		
		if ($cid)
		{
			if (!isset($files))
			{
				// get filenames
				$this->_db->setQuery("SELECT `filename` FROM #__rsmediagallery_items WHERE `id` IN (".implode(',', $cid).")");
				$files = $this->_db->loadResultArray();
			}
			
			// delete entries
			$this->_db->setQuery("DELETE FROM #__rsmediagallery_items WHERE `id` IN (".implode(',', $cid).")");
			$this->_db->query();
			$this->_db->setQuery("DELETE FROM #__rsmediagallery_tags WHERE `item_id` IN (".implode(',', $cid).")");
			$this->_db->query();
			
			// physically delete files
			if (!empty($files))
				foreach ($files as $file)
				{
					if (JFile::exists($path.DS.$file))
						JFile::delete($path.DS.$file);
					if (JFile::exists($path.DS.'original'.DS.$file))
						JFile::delete($path.DS.'original'.DS.$file);
					
					// delete all other thumbs
					if ($folders)
						foreach ($folders as $folder)
							if (JFile::exists($folder.DS.$file))
								JFile::delete($folder.DS.$file);
				}
		}
	}
	
	function saveOrder()
	{
		$cid = JRequest::getVar('cid');
		JArrayHelper::toInteger($cid);
		
		$ordering = JRequest::getVar('ordering');
		JArrayHelper::toInteger($ordering);
		
		// save ordering
		if (count($cid))
		{
			$query = "UPDATE #__rsmediagallery_items SET `ordering` = CASE `id` ";
			for ($i=0; $i<count($cid); $i++)
				$query .= " WHEN ".$cid[$i]." THEN ".$ordering[$i];
			$query .= " END WHERE `id` IN (".implode(",", $cid).")";
			
			$this->_db->setQuery($query);
			$this->_db->query();
		}
	}
	
	function changeStatus($published=1)
	{
		$published  = (int) $published;
		$select_all = JRequest::getInt('selectall');
		$join  		= '';
		$where 		= '';
		
		if ($select_all) // change status for all
		{
			extract($this->_buildQuery(true), EXTR_OVERWRITE);
			$this->_db->setQuery("SELECT i.* FROM #__rsmediagallery_items i ".$join." WHERE 1 ".$where." ".$group." ".$having);
			$cid = $this->_db->loadResultArray();
		}
		else // change status for selected
		{
			$cid = JRequest::getVar('cid');
			JArrayHelper::toInteger($cid);
		}
		
		if ($cid)
		{
			$where = " AND i.id IN (".implode(',', $cid).")";
			$this->_db->setQuery("UPDATE #__rsmediagallery_items i SET i.`published`='".$published."' WHERE 1 ".$where);
			$this->_db->query();
		}
	}
	
	function _prepareTags($tags)
	{
		foreach ($tags as $i => $tag)
		{
			// trim whitespace
			$tag = trim($tag);
			
			// don't add empty tags
			if (!$tag)
			{
				unset($tags[$i]);
				continue;
			}
			
			$tag = "'".$this->_db->getEscaped($tag)."'";
			$tags[$i] = $tag;
		}
		return $tags;
	}
	
	function changeTag($tagged=1)
	{
		$tagged 	= (int) $tagged;
		$select_all	= JRequest::getInt('selectall');
		$tags 		= explode(',', JRequest::getVar('tags'));
		$tags		= $this->_prepareTags($tags);
		$join 		= '';
		$where 		= '';
		
		if ($select_all)
		{
			extract($this->_buildQuery(true));
			
			if ($tagged)
			{
				foreach ($tags as $tag)
				{
					$query = "SELECT i.id, $tag FROM #__rsmediagallery_items i ".$join." WHERE 1 ".$where.$group.$having;
					$this->_db->setQuery("INSERT IGNORE INTO #__rsmediagallery_tags (`item_id`, `tag`) ".$query);
					$this->_db->query();
				}
			}
			else
			{
				$this->_db->setQuery("SELECT i.id FROM #__rsmediagallery_items i ".$join." WHERE 1 ".$where.$group.$having);
				$cids = $this->_db->loadResultArray();
				if ($cids)
				{
					$this->_db->setQuery("DELETE FROM #__rsmediagallery_tags WHERE `item_id` IN (".implode(',', $cids).") AND `tag` IN (".implode(',', $tags).")");
					$this->_db->query();
				}
			}
		}
		else
		{
			$cids = JRequest::getVar('cid');
			JArrayHelper::toInteger($cids);
			if ($cids)
			{
				if ($tagged)
				{
					$values = array();
					foreach ($tags as $tag)
						foreach ($cids as $cid)
							$values[] = "('".$cid."', $tag)";
					if ($values)
					{
						$this->_db->setQuery("INSERT IGNORE INTO #__rsmediagallery_tags (`item_id`, `tag`) VALUES ".implode(',', $values));
						$this->_db->query();
					}
				}
				else
				{
					$conditions = array();
					foreach ($tags as $tag)
						foreach ($cids as $cid)
							$conditions[] = "(`item_id`='".$cid."' AND `tag`=$tag)";
					
					if ($conditions)
					{
						$this->_db->setQuery("DELETE FROM #__rsmediagallery_tags WHERE ".implode(' OR ', $conditions));
						$this->_db->query();
					}
				}
			}
		}
	}
	
	function getItem()
	{
		$cid = JRequest::getInt('cid');
	
		// get item
		$this->_db->setQuery("SELECT * FROM #__rsmediagallery_items WHERE `id`='".$cid."'");
		$result = $this->_db->loadObject();
		
		if ($result)
		{
			// convert params
			$result->params = $result->params ? @unserialize($result->params) : array();
			
			// correct date
			if ($result->modified != $this->_db->getNullDate() && $result->modified != $result->created)
				$result->modified = JHTML::_('date',  $result->modified, JText::_('DATE_FORMAT_LC2'));
			else
				$result->modified = JText::_('RSMG_NEVER');
				
			if ($result->created != $this->_db->getNullDate())
				$result->created = JHTML::_('date',  $result->created, JText::_('DATE_FORMAT_LC2'));
			else
				$result->created = JText::_('RSMG_NEVER');
			
			// append tags
			$this->_db->setQuery("SELECT `tag` FROM #__rsmediagallery_tags WHERE `item_id`='".$result->id."'");
			$result->tags = implode(', ', $this->_db->loadResultArray());
		}
		
		// return
		return $result;
	}
	
	function saveItem()
	{
		$cid = JRequest::getInt('cid');
		
		if ($item = $this->getItem())
		{
			// get date object
			$date =& JFactory::getDate();
			
			// save to db
			JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_rsmediagallery'.DS.'tables');
			$image =& JTable::getInstance('RSMediaGallery_Items', 'Table');
			
			// don't create the thumb if nothing has changed
			$create_thumb = $item->params['selection']['x1'] != JRequest::getInt('x1') || $item->params['selection']['y1'] != JRequest::getInt('y1') || $item->params['selection']['x2'] != JRequest::getInt('x2') || $item->params['selection']['y2'] != JRequest::getInt('y2');
			
			// new selection
			$item->params['selection']['x1'] = JRequest::getInt('x1');
			$item->params['selection']['y1'] = JRequest::getInt('y1');
			$item->params['selection']['x2'] = JRequest::getInt('x2');
			$item->params['selection']['y2'] = JRequest::getInt('y2');
			
			// new admin thumb params
			$item->params['admin_thumb']['w'] = JRequest::getInt('admin_w');
			$item->params['admin_thumb']['h'] = JRequest::getInt('admin_h');
			
			if ($item->params['selection']['x2'] - $item->params['selection']['x1'] < 1 || $item->params['selection']['y2'] - $item->params['selection']['y1'] < 1)
				return $this->_setResult(array('error' => '1', 'message' => JText::_('RSMG_PLEASE_SELECT_THUMBNAIL_ERROR')));
			
			$image->id 			= $item->id;
			$image->title 		= JRequest::getVar('title');
			$image->description = JRequest::getVar('description', '', 'default', 'none', JREQUEST_ALLOWHTML);
			$image->url 		= JRequest::getVar('url');
			$image->params 		= serialize($item->params);
			$image->free_aspect	= JRequest::getInt('free_aspect');
			$image->hits 		= JRequest::getInt('hits');
			$image->published	= JRequest::getInt('published');
			
			// update modified time
			$image->modified 	= $date->toMySQL();
			
			// empty tags
			$this->_db->setQuery("DELETE FROM #__rsmediagallery_tags WHERE `item_id`='".$image->id."'");
			$this->_db->query();
			
			// add the new tags
			JRequest::setVar('cid', array($image->id));
			$this->changeTag(1);
			
			if ($create_thumb)
			{
				$upload_location = JPATH_SITE.DS.'components'.DS.'com_rsmediagallery'.DS.'assets'.DS.'gallery';
				
				// delete all other thumbs
				jimport('joomla.filesystem.folder');
				if ($folders = JFolder::folders($upload_location, 'x', false, true, array('original')))
				{
					jimport('joomla.filesystem.file');
					foreach ($folders as $folder)
						if (file_exists($folder.DS.$item->filename))
							JFile::delete($folder.DS.$item->filename);
				}
				
				require_once JPATH_ADMINISTRATOR.DS.'components'.DS.'com_rsmediagallery'.DS.'helpers'.DS.'phpthumb'.DS.'phpthumb.class.php';
				
				$phpThumb = new phpThumb();
				$phpThumb->src = $upload_location.DS.'original'.DS.$item->filename;
				$phpThumb->w   = JRequest::getInt('admin_w');
				$phpThumb->h   = JRequest::getInt('admin_h');
				$phpThumb->iar = 1;
				$phpThumb->zc  = 0;
				
				$ratio = $item->params['info'][0] / 380;
				
				$phpThumb->sx = round($item->params['selection']['x1']*$ratio);
				$phpThumb->sy = round($item->params['selection']['y1']*$ratio);
				$phpThumb->sw = round(JRequest::getInt('w')*$ratio);
				$phpThumb->sh = round(JRequest::getInt('h')*$ratio);
				
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
					if (!$phpThumb->RenderToFile($upload_location.DS.$item->filename))
					{
						return $this->_setResult(array('error' => '1', 'message' => JText::sprintf('RSMG_COULD_NOT_WRITE_TO_FILE', $upload_location.DS.$item->filename)));
					}
				}
				else
					return $this->_setResult(array('error' => '1', 'message' => JText::sprintf('RSMG_THUMBNAIL_NOT_CREATED', $item->original_filename, $phpThumb->fatalerror)));
				
				// set file permissions
				$componentParams = JComponentHelper::getParams('com_rsmediagallery');
				$perms 			 = octdec('0'.$componentParams->get('file_perms', '644'));
				@chmod($upload_location.DS.$item->filename, $perms);
			}
			
			if ($image->store())
			{
				$image->load();
				$image->params = unserialize($image->params);
				return $this->_setResult(array('error' => '0', 'success' => '1', 'item' => $image->getProperties()));
			}
		}
	}
	
	function uploadItem()
	{
		$file = JRequest::getVar('upload', array(), 'files');
		
		// form was not submitted ?
		if (empty($file))
			return $this->_setResult(array('error' => '1', 'message' => JText::_('RSMG_NO_UPLOAD_REQUESTED')));
		
		// error, display message
		if ($file['error'] > 0)
		{
			switch ($file['error'])
			{
				case 1;
					return $this->_setResult(array('error' => '1', 'message' => JText::_('RSMG_UPLOAD_EXCEEDS_SERVER_SIZE')));
				break;
				
				case 2;
					return $this->_setResult(array('error' => '1', 'message' => JText::_('RSMG_UPLOAD_EXCEEDS_FORM_SIZE')));
				break;
				
				case 3;
					return $this->_setResult(array('error' => '1', 'message' => JText::_('RSMG_UPLOAD_PARTIALLY')));
				break;
				
				default:
				case 4;
					return $this->_setResult(array('error' => '1', 'message' => JText::_('RSMG_NO_FILE_UPLOADED')));
				break;
				
				case 6;
					return $this->_setResult(array('error' => '1', 'message' => JText::_('RSMG_NO_TMP_FOLDER')));
				break;
				
				case 7;
					return $this->_setResult(array('error' => '1', 'message' => JText::_('RSMG_FAILED_TO_WRITE')));
				break;
				
				case 8;
					return $this->_setResult(array('error' => '1', 'message' => JText::_('RSMG_PHP_STOPPED_UPLOAD')));
				break;
			}
		}
		
		jimport('joomla.filesystem.file');
		
		// no error so far - check file extension
		$ext = strtolower(JFile::getExt($file['name']));
		
		// allowed extensions
		$allowed = array('jpg', 'png', 'gif');
		
		// not allowed
		if (!in_array($ext, $allowed))
			return $this->_setResult(array('error' => '1', 'message' => JText::sprintf('RSMG_EXTENSION_NOT_ALLOWED', $ext)));
		
		// check if it is actually an image
		$info = getimagesize($file['tmp_name']);
		// no info can be retrieved - correct extension but not an image
		if (!$info)
			return $this->_setResult(array('error' => '1', 'message' => JText::sprintf('RSMG_CORRECT_EXTENSION_NOT_IMAGE', $file['name'])));
		
		$hash 			 = md5(uniqid($file['name']));
		$upload_location = JPATH_SITE.DS.'components'.DS.'com_rsmediagallery'.DS.'assets'.DS.'gallery';
		
		// upload file to original directory
		// we need to keep the original file in order to create high quality crops and thumbs
		if (!JFile::upload($file['tmp_name'], $upload_location.DS.'original'.DS.$hash.'.'.$ext))
			return $this->_setResult(array('error' => '1', 'message' => JText::sprintf('RSMG_IMAGE_NOT_UPLOADED', $upload_location.DS.'original')));
		
		// set file permissions
		$componentParams = JComponentHelper::getParams('com_rsmediagallery');
		$perms 			 = octdec('0'.$componentParams->get('file_perms', '644'));
		@chmod($upload_location.DS.'original'.DS.$hash.'.'.$ext, $perms);
		
		// create the initial thumb
		require_once JPATH_ADMINISTRATOR.DS.'components'.DS.'com_rsmediagallery'.DS.'helpers'.DS.'phpthumb'.DS.'phpthumb.class.php';
		$phpThumb = new phpThumb();
		$phpThumb->src = $upload_location.DS.'original'.DS.$hash.'.'.$ext;
		$phpThumb->w  = 280;
		$phpThumb->h  = 210;
		$phpThumb->zc = $info[1] > $info[0] ? 'TC' : 1; // need to disable zoom-crop when portrait photos are used
		if ($phpThumb->GenerateThumbnail())
		{
			if (!$phpThumb->RenderToFile($upload_location.DS.$hash.'.'.$ext))
			{
				return $this->_setResult(array('error' => '1', 'message' => JText::sprintf('RSMG_COULD_NOT_WRITE_TO_FILE', $upload_location.DS.$hash.'.'.$ext)));
			}
		}
		else
			return $this->_setResult(array('error' => '1', 'message' => JText::sprintf('RSMG_THUMBNAIL_NOT_CREATED', $file['name'], $phpThumb->fatalerror)));
		
		// set file permissions
		@chmod($upload_location.DS.$hash.'.'.$ext, $perms);
		
		// get date object
		$date =& JFactory::getDate();
		
		// save to db
		JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_rsmediagallery'.DS.'tables');
		$image =& JTable::getInstance('RSMediaGallery_Items', 'Table');
		$image->original_filename 	= $file['name'];
		$image->filename 			= $hash.'.'.$ext;
		$image->title 				= JFile::stripExt($file['name']);
		$image->description 		= '';
		$image->type 				= 'image';
		// set correct ordering
		$image->ordering 			= $image->getNextOrder();
		
		$ratio  = 380 / $phpThumb->source_width;
		$height = $phpThumb->source_height * $ratio;
		
		$image->params = serialize(array(
			'info' 		=> $info,
			'selection' => array(
				'x1' => round($phpThumb->thumbnailCropX*$ratio),
				'y1' => round($phpThumb->thumbnailCropY*$ratio),
				'x2' => round($phpThumb->thumbnailCropW*$ratio + $phpThumb->thumbnailCropX*$ratio),
				'y2' => round($height - $phpThumb->thumbnailCropY*$ratio)
			),
			'admin_thumb' => array(
				'w' => $phpThumb->thumbnail_image_width,
				'h' => $phpThumb->thumbnail_image_height,
				'ratio' => $ratio
			)
		));
		
		$image->created  = $date->toMySQL();
		$image->modified = $date->toMySQL();
		
		// set it to unpublished
		$image->published			= JRequest::getInt('published');
		if ($image->store())
		{
			// add tags
			JRequest::setVar('cid', array($image->id));
			$this->changeTag(1);
			
			return $this->_setResult(array('error' => '0', 'success' => '1', 'message' => JText::sprintf('RSMG_IMAGE_SUCCESSFULLY_UPLOADED', $file['name']), 'id' => $image->id));
		}
	}
	
	function getSuggestions()
	{
		$column 	= JRequest::getVar('filter_columns', '');
		$operator 	= JRequest::getVar('filter_operators', '');
		$value 		= JRequest::getVar('filter_values', '');
		$filter		= $value;
		
		if (!$filter)
			return $this->_setResult(array());
		
		switch ($operator)
		{
			case 'contains':
				$operator = 'LIKE';
				$value	  = '%'.str_replace('%', '\%', $value).'%';
			break;
			
			case 'contains_not':
				$operator = 'NOT LIKE';
				$value	  = '%'.str_replace('%', '\%', $value).'%';
			break;
			
			case 'is':
				$operator = '=';
			break;
			
			case 'is_not':
				$operator = '<>';
			break;
		}
		
		switch ($column)
		{
			case 'title':
				$this->_db->setQuery("SELECT DISTINCT(`title`) FROM #__rsmediagallery_items WHERE `title` ".$operator." '".$this->_db->getEscaped($value)."' AND `title` <> '' ORDER BY `title` ASC", 0, 10);
				$results = $this->_db->loadResultArray();				
				if ($results)
					foreach ($results as $i => $result)
						$this->_trim($results[$i], $filter, $operator);
						
				$this->_setResult($results);
			break;
			
			case 'description':
				$this->_db->setQuery("SELECT `description` FROM #__rsmediagallery_items WHERE `description` ".$operator." '".$this->_db->getEscaped($value)."' AND `description` <> '' ORDER BY `description` ASC", 0, 10);
				$results = $this->_db->loadResultArray();
				if ($results)
					foreach ($results as $i => $result)
						$this->_trim($results[$i], $filter, $operator);
						
				$this->_setResult($results);
			break;
			
			case 'tags':
				$this->_db->setQuery("SELECT DISTINCT(`tag`) FROM #__rsmediagallery_tags WHERE `tag` ".$operator." '".$this->_db->getEscaped($value)."' ORDER BY `tag` ASC", 0, 10);
				$this->_setResult($this->_db->loadResultArray());
			break;
		}
	}
	
	function _trim(&$value, $filter, $operator)
	{
		$max = 60;
		
		if ($operator == 'NOT LIKE' || $operator == '<>')
		{
			$string_tmp = '';
			$words = explode(' ',$value);
			
			for ($i=0; $i<count($words); $i++)
			{
				if (strlen($string_tmp) + strlen($words[$i]) < $max)
					$string_tmp .= $words[$i].' ';
				else
					break;
			}
			$value = substr($string_tmp,0,-1);
		}
		else
		{
			$lcvalue 	= strtolower($value);
			$lcfilter 	= strtolower($filter);
			$pos 		= strpos($lcvalue, $lcfilter);
			
			$max 		= $max - strlen($filter);
			$stopwords 	= array(' ', ',', '.', ';', '!', '?');
			
			if ($pos !== false)
			{
				$left  = substr($value, 0, $pos);
				$right = substr($value, $pos+strlen($filter));
				
				$left_max 	= min(ceil($max/2), strlen($left));
				$right_max 	= min(ceil($max/2), strlen($right));
				
				$left_result = '';
				for ($i=strlen($left)-1; $i>=0; $i--)
				{
					if (in_array($left[$i], $stopwords) && strlen($left_result) >= $left_max)
						break;
					
					$left_result .= $left[$i];
				}
				$left_result = strrev($left_result);
				
				$right_result = '';
				for ($i=0; $i<strlen($right); $i++)
				{
					if (in_array($right[$i], $stopwords) && strlen($right_result) >= $right_max)
						break;
					
					$right_result .= $right[$i];
				}
				
				$value = $left_result.$filter.$right_result;
			}
		}
	}
	
	function getFilters()
	{
		$mainframe 	=& JFactory::getApplication();
		
		if (JRequest::getInt('dont_remember'))
		{
			$columns 	= JRequest::getVar('filter_columns', 	array());
			$operators 	= JRequest::getVar('filter_operators', 	array());
			$values		= JRequest::getVar('filter_values', 	array());
		}
		else
		{
			$columns 	= $mainframe->getUserStateFromRequest($this->_option.'.items.filter_columns', 	'filter_columns', 	array(), 'array');
			$operators 	= $mainframe->getUserStateFromRequest($this->_option.'.items.filter_operators', 'filter_operators', array(), 'array');
			$values 	= $mainframe->getUserStateFromRequest($this->_option.'.items.filter_values', 	'filter_values', 	array(), 'array');
		}
		
		if ($columns && $columns[0] == '')
			$columns = $operators = $values = array();
		
		if (!is_array($columns))
			$columns = array($columns);
		if (!is_array($operators))
			$operators = array($operators);
		if (!is_array($values))
			$values = array($values);
		
		return array($columns, $operators, $values);
	}
	
	function getOrderings()
	{
		$mainframe 	=& JFactory::getApplication();
		
		if (JRequest::getInt('dont_remember'))
		{
			$order 		= JRequest::getVar('order',		'ordering');
			$direction 	= JRequest::getVar('direction', 'asc');
		}
		else
		{
			$order 		= $mainframe->getUserStateFromRequest($this->_option.'.items.order', 	 'order', 		'ordering');
			$direction 	= $mainframe->getUserStateFromRequest($this->_option.'.items.direction', 'direction', 	'asc');
		}
		
		$order 		= $order == 'tags' ? 't.tag' : 'i.'.$order;
		
		return array($order, $direction);
	}
	
	function getLimit()
	{
		$mainframe =& JFactory::getApplication();
		
		if (JRequest::getInt('limitall'))
			return JRequest::getInt('limit', $mainframe->getCfg('list_limit'));
			
		return $mainframe->getUserStateFromRequest($this->_option.'.items.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
	}
	
	function _setResult($param)
	{		
		if (is_array($param))
			foreach ($param as $key => $val)
				$this->_result[$key] = $val;
	}
	
	function getUploadResult()
	{
		$this->uploadItem();
		
		return $this->_result;
	}
	
	function getSaveResult()
	{
		$this->saveItem();
		
		return $this->_result;
	}
	
	function getAutocompleteResult()
	{
		$this->getSuggestions();
		
		return $this->_result;
	}
}