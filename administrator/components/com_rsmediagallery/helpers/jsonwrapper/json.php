<?php
/**
* @version 1.0.0
* @package RSMediaGallery! 1.0.0
* @copyright (C) 2012 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-3.0.html
*/

defined( '_JEXEC' ) or die( 'Restricted access' );

# In PHP 5.2 or higher we don't need to bring this in
if (!function_exists('json_encode'))
	require_once dirname(__FILE__).DS.'jsonwrapper_inner.php';