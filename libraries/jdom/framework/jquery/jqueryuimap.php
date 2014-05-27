<?php
/**
* @author		Girolamo Tomaselli http://bygiro.com - girotomaselli@gmail.com
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );


class JDomFrameworkJqueryJqueryuimap extends JDomFrameworkJquery
{	

	var $assetName = 'jqueryuimap';
	
	var $attachJs = array();
	var $attachCss = array();
	
	protected static $loaded = array();	
	
	/*
	 * Constuctor
	 * 	@namespace 	: requested class
	 *  @options	: Configuration
	 *
	 *
	 */
	function __construct($args)
	{
		parent::__construct($args);
		
		/* example arguments */
		$this->arg('options1'	, null, $args);
		$this->arg('options2'	, null, $args);		
	}
	
	function build()
	{	
		// Only load once
		if (!empty(static::$loaded[__METHOD__]))
		{
			return;
		}		
		JDom::_('framework.google.map');
		
		$this->attachJs[] = 'jquery.ui.map.js';
		$this->attachJs[] = 'jquery.ui.map.extensions.js';
		$this->attachJs[] = 'jquery.ui.map.overlays.js';
		$this->attachJs[] = 'jquery.ui.map.services.js';
		
		
		static::$loaded[__METHOD__] = true;
	}
	
	function buildCss()
	{
	//	$this->attachCss[] = 'bootstrap.min.css';
	}
	
	function buildJs()
	{
	//	$this->attachCss[] = 'bootstrap.min.css';
	}
}