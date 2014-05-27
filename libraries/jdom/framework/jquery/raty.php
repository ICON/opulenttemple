<?php
/**
* @author		Girolamo Tomaselli http://bygiro.com - girotomaselli@gmail.com
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );


class JDomFrameworkJqueryRaty extends JDomFrameworkJquery
{	

	var $assetName = 'raty';
	
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
		
		//Requires jQuery
		JDom::_('framework.jquery');		
		
		// addresspicker manager files needed
		$this->attachJs[] = 'jquery.raty.js';
	
		$script = "jQuery(document).ready(function(){" . LN
				.	'jQuery(".star").raty({'. LN
				.	'	score: function(){'. LN
				.	'		return jQuery(this).attr("data-score");'. LN
				.	'	},'. LN
				.	'	path: "libraries/jdom/assets/raty/img/",'. LN
				.	'	hints: ["'. JText::_("JSHOP_ENUM_FEEDBACKS_POOR") .'", "'. JText::_("JSHOP_ENUM_FEEDBACKS_FAIR") .'", "'. JText::_("JSHOP_ENUM_FEEDBACKS_GOOD") .'", "'. JText::_("JSHOP_ENUM_FEEDBACKS_GREAT") .'", "'. JText::_("JSHOP_ENUM_FEEDBACKS_EXCELLENT") .'"]'. LN
				.	'});'. LN
				. "});";
		$doc->addScriptDeclaration($script);
		
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