<?php
/**
* @author		Girolamo Tomaselli http://bygiro.com - girotomaselli@gmail.com
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );


class JDomFrameworkJqueryEzmark extends JDomFrameworkJquery
{	

	var $assetName = 'ezmark';
	
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
		$doc = JFactory::getDocument();
		
		JDom::_('framework.jquery');		
		
		// addresspicker manager files needed
		$this->attachJs[] = 'jquery.ezmark.js';
		$this->attachCss[] = 'ezmark.css';
		
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