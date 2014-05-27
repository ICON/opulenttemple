<?php
/**
 * 
 * @since version 0.3
 * @abstract
 * @version 0.1
 * @Copyright (C) 2010 - Daniel Dimitrov - http://compojoom.com
 * @All rights reserved
 * @license under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
 *
 * @author Petar Tuovic, email : petar@compojoom.com
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport('joomla.application.component.model');

class CMigratorModelParse extends JModel
{
    public function __construct()
    {
        parent::__construct();
    }
}