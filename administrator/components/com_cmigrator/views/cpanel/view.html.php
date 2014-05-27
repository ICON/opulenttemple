<?php
/**
 * CMigrator - Adminstrator
 * @Copyright (C) 2010 - Daniel Dimitrov - http://compojoom.com
 * @All rights reserved
 * @license under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
 **/

defined('_JEXEC') or die();

jimport('joomla.application.component.view');
require_once JPATH_ADMINISTRATOR.DS.'components'.DS.'com_cmigrator'.DS.'helpers'.DS.'helper.php';

class CMigratorViewCpanel extends JView
{
	public function display($tpl = null)
	{
		$this->configurations = $this->getConfigurations();
                
                $this->select_migrate = JHTML::_('select.genericlist', $this->configurations, 'config-migrate');
                
                $this->select_parse = JHTML::_('select.genericlist', $this->configurations, 'config-parse');

		parent::display($tpl);
	}

	private function getConfigurations()
	{
		$configModel = JModel::getInstance('Config', 'CMigratorModel');

		$configurations = $configModel->getConfigs();
		$ret = array();

		foreach ($configurations as $config) {
			$ret[] = JHTML::_('select.option', $config->id, $config->name);
		}

		return $ret;
	}
}
