<?php
/**

 * CMigrator - Adminstrator
 * @Copyright (C) 2010 - Daniel Dimitrov - http://compojoom.com
 * @All rights reserved
 * @license under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html

**/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

class CMigratorControllerCpanel extends JController
{

    public function display($cachable = false, $urlparams = false) {

        JRequest::setVar( 'view', 'cpanel' );

        parent::display();
    }
    
}