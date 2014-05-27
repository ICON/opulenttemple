<?php
/**
* @version 1.0.0
* @package RSMediaGallery! 1.0.0
* @copyright (C) 2012 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-3.0.html
*/

defined('_JEXEC') or die('Restricted access');

function _iniRSMediaGalleryjQuery()
{
	$jqueryHelper 	=& RSMediaGalleryjQuery::getInstance();
	// we're running onAfterRender() so set this to 1
	$jqueryHelper->afterRender = true;
	// force our own mode since the only one available now is the smart load
	$jqueryHelper->mode = 'smart';
	// try to add jQuery
	$jqueryHelper->addjQuery();
}

class RSMediaGalleryjQuery
{
	var $mode = 'auto';
	var $jQueryPattern = '#([\\\/a-zA-Z0-9_:\.-]*)jquery([0-9\.-]|min|pack)*?.js#';
	var $afterRender = false;
	
	function getInstance()
	{
		static $inst;
		
		if (empty($inst))
		{
			jimport('joomla.application.component.helper');
	
			$params = JComponentHelper::getParams('com_rsmediagallery');
			$inst 	= new RSMediaGalleryjQuery($params->get('jquery', 'auto'));
		}
		
		return $inst;
	}
	
	function RSMediaGalleryjQuery($mode)
	{
		$this->mode = $mode;
		// if debug is on, we need to add .src as well since our scripts include it
		if (JDEBUG)
			$this->jQueryPattern = str_replace('|min|pack)', '|min|src|pack)', $this->jQueryPattern);
	}
	
	function addjQuery()
	{		
		// this if() clause will be run only once since 'auto' will be changed to something else
		// attempt to detect best approach
		if ($this->mode == 'auto')
		{
			// did we find multiple instances of jQuery ?
			// we haven't added anything yet so it means something else loaded jQuery this is why it's > 0
			if ($this->foundMultiplejQuery() > 0)
				// ok, try to load just one
				$this->mode = 'smart';
			// otherwise, add our own
			else
				$this->mode = 'own';
			
			// attach the function on both cases since we're on auto and we need to make sure that a single jquery instance runs
			$mainframe =& JFactory::getApplication();
			$mainframe->registerEvent('onAfterRender', '_iniRSMediaGalleryjQuery');
		}
		
		switch ($this->mode)
		{
			case 'own':
				// just add our script
				$this->addScript();
			break;
			
			case 'smart':
				// we can't run now since the "smart load" mode requires "onAfterRender()"
				if (!$this->afterRender)
				{
					// just add our script...
					$this->addScript();
					
					// just attach the event so it can run
					$mainframe =& JFactory::getApplication();
					$mainframe->registerEvent('onAfterRender', '_iniRSMediaGalleryjQuery');
					return true;
				}
					
				// if found multiple instances AND we are running onAfterRender() we can proceed
				// we've already added our own jQuery so this means that we need to check if we have more than one
				if ($this->foundMultiplejQuery() > 1)
					$this->replacejQuery();
			break;
			
			case 'no':
				// do nothing - do not load
				return true;
			break;
		}
	}
	
	function addScript()
	{
		$document =& JFactory::getDocument();
		$src 	  =  JDEBUG ? '.src' : '';
		
		$document->addScript(JURI::root(true).'/components/com_rsmediagallery/assets/js/jquery'.$src.'.js');
		$document->addScript(JURI::root(true).'/components/com_rsmediagallery/assets/js/jquery.noconflict.js');
	}
	
	function foundMultiplejQuery($where=null)
	{
		$body = $where ? $where : JResponse::getBody();
		// find jQuery versions
		$found = preg_match_all($this->jQueryPattern, $body, $matches);
		
		return $found;
	}
	
	function replacejQuery($where=null)
	{
		$src  = JDEBUG ? '.src' : '';
		$body = $where ? $where : JResponse::getBody();
		// remove all other references to jQuery library
		$body = preg_replace($this->jQueryPattern, 'GARBAGE', $body);
		// remove newly empty scripts
		$body = preg_replace('#<script[^>]*GARBAGE[^>]*></script>#', '', $body);
		// jQuery
		$jquery 	= '<script type="text/javascript" src="'.JURI::root(true).'/components/com_rsmediagallery/assets/js/jquery'.$src.'.js'.'"></script>';
		// jQuery noConflict() mode enforcer
		$noconflict = '<script type="text/javascript" src="'.JURI::root(true).'/components/com_rsmediagallery/assets/js/jquery.noconflict.js'.'"></script>';
		$body = str_replace('<head>', '<head>'."\r\n".$jquery."\r\n".$noconflict, $body);
		
		if ($where)
			return $body;
			
		JResponse::setBody($body);
	}
}