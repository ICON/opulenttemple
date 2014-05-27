<?php
/**                               ______________________________________________
*                          o O   |                                              |
*                 (((((  o      <    Generated with Cook Self Service  V2.6.3   |
*                ( o o )         |______________________________________________|
* --------oOOO-----(_)-----OOOo---------------------------------- www.j-cook.pro --- +
* @version		0.2.9
* @package		jForms
* @subpackage	
* @copyright	G. Tomaselli
* @author		Girolamo Tomaselli - http://bygiro.com - girotomaselli@gmail.com
* @license		GNU GPL v3 or later
*
*             .oooO  Oooo.
*             (   )  (   )
* -------------\ (----) /----------------------------------------------------------- +
*               \_)  (_/
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

defined('DS') or define("DS", DIRECTORY_SEPARATOR);


/**
* Script file of Jforms component
*
* @package	Jforms
* @subpackage	Installer
*/
class com_jformsInstallerScript
{
	/**
	* Called on installation
	*
	* @access	public
	* @param	JAdapterInstance	$adapter	Installer Component Adapter.
	* @return	void
	*
	* @since	1.6
	*/
	public function install(JAdapterInstance $adapter)
	{
		$adapter->getParent()->setRedirectURL('index.php?option=com_jforms');


	}

	/**
	* Method to install the embedded third extensions.
	*
	* @access	private
	* @param	JAdapterInstance	$adapter	Installer Component Adapter.
	* @return	void
	*
	* @since	Cook 2.6
	*/
	private function installExtensions(JAdapterInstance $adapter)
	{
		$dir = $adapter->getParent()->getPath('source') .DS. 'extensions';

		if(!file_exists($dir)){
			return;	
		}
		
		$installResults = array();

		jimport('joomla.filesystem.folder');
		$folders = JFolder::folders($dir);

		foreach($folders as $folder)
		{
			$source = $dir .DS. $folder;
		    $installer = new JInstaller;
		    $installResults[] = $installer->install($source);
		}
	}

	/**
	* Called after any type of action.
	*
	* @access	public
	* @param	string	$type	Type.
	* @param	JAdapterInstance	$adapter	Installer Component Adapter.
	* @return	void
	*
	* @since	1.6
	*/
	public function postflight($type, JAdapterInstance $adapter)
	{
		switch($type)
		{
			case 'install':
				$txtAction = JText::_('Installing');
		
				//Install all extensions contained in 'extensions' directory
				$this->installExtensions($adapter);
				
				// set default ACL
				$db = JFactory::getDBO();
				$query = "UPDATE #__assets SET "
					. ' rules=\'{"core.admin":[],"core.manage":[],"core.create":{"1":1},"core.delete":[],"core.edit":[],"core.edit.state":[],"core.view.own":{"2":1},"core.edit.own":[],"core.delete.own":[]}\''
					. " WHERE name='com_jforms'";
				$db->setQuery($query);
				$db->query();
				break;
		
			case 'update':
				$txtAction = JText::_('Updating');

				//Install all extensions contained in 'extensions' directory
				$this->installExtensions($adapter);
				break;
	
			case 'uninstall':
				$txtAction = JText::_('Uninstalling');
		
				//Install all extensions contained in 'extensions' directory
				$this->uninstallExtensions($adapter);
				break;
	
		}

		$app = JFactory::getApplication();
		$txtComponent = JText::_('jForms');
		$app->enqueueMessage(JText::sprintf('%s %s was successfull.', $txtAction, $txtComponent));
	}

	/**
	* Called before any type of action
	*
	* @access	public
	* @param	string	$type	Type.
	* @param	JAdapterInstance	$adapter	Installer Component Adapter.
	* @return	void
	*
	* @since	1.6
	*/
	public function preflight($type, JAdapterInstance $adapter)
	{

	}

	/**
	* Called on uninstallation
	*
	* @access	public
	* @param	JAdapterInstance	$adapter	Installer Component Adapter.
	* @return	void
	*
	* @since	1.6
	*/
	public function uninstall(JAdapterInstance $adapter)
	{
		// We run postflight also after uninstalling
		self::postflight('uninstall', $adapter);

	}

	/**
	* Method to uninstall the embedded third extensions.
	*
	* @access	private
	* @param	JAdapterInstance	$adapter	Installer Component Adapter.
	* @return	void
	*
	* @since	Cook 2.6
	*/
	private function uninstallExtensions(JAdapterInstance $adapter)
	{

	}

	/**
	* Called on update
	*
	* @access	public
	* @param	JAdapterInstance	$adapter	Installer Component Adapter.
	* @return	void
	*
	* @since	1.6
	*/
	public function update(JAdapterInstance $adapter)
	{
		$adapter->getParent()->setRedirectURL('index.php?option=com_jforms');
	}


}



