<?php
/**

 * CMigrator - Adminstrator
 * @Copyright (C) 2010 - Daniel Dimitrov - http://compojoom.com
 * @All rights reserved
 * @license under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html

**/

// No direct access.
defined('_JEXEC') or die;


class CMigratorTableConfiguration extends JTable
{
    /**
     * Constructor
     * @param $_db
     */
	public function __construct(&$_db)
	{
		parent::__construct('#__cmigrator_configuration', 'id', $_db);
	}
}
