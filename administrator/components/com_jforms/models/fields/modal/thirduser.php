<?php
/**                               ______________________________________________
*                          o O   |                                              |
*                 (((((  o      <    Generated with Cook Self Service  V2.6.3   |
*                ( o o )         |______________________________________________|
* --------oOOO-----(_)-----OOOo---------------------------------- www.j-cook.pro --- +
* @version		0.2.9
* @package		jForms
* @subpackage	Users
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

if (!class_exists('JformsClassFormField'))
	require_once(JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR. 'components' .DIRECTORY_SEPARATOR. 'com_jforms' .DIRECTORY_SEPARATOR. 'helpers' .DIRECTORY_SEPARATOR. 'loader.php');


/**
* Form field for Jforms.
*
* @package	Jforms
* @subpackage	Form
*/
class JFormFieldModal_Thirduser extends JdomClassFormFieldModal
{
	/**
	* Default label for the picker.
	*
	* @var string
	*/
	protected $_nullLabel = 'JFORMS_DATA_PICKER_SELECT_USER';

	/**
	* Option in URL
	*
	* @var string
	*/
	protected $_option = 'com_jforms';

	/**
	* Modal Title
	*
	* @var string
	*/
	protected $_title;

	/**
	* View in URL
	*
	* @var string
	*/
	protected $_view = "thirdusers";

	/**
	* Field type
	*
	* @var string
	*/
	protected $type = 'modal_thirduser';

	/**
	* Method to get the field input markup.
	*
	* @access	protected
	*
	* @return	string	The field input markup.
	*
	* @since	11.1
	*/
	protected function getInput()
	{
		if ($this->value == 'auto')
			$this->_title = JText::_('JFORMS_AUTO');
		else
		{
			$db	= JFactory::getDBO();
			$db->setQuery(
				'SELECT `name`' .
				' FROM #__users' .
				' WHERE id = '.(int) $this->value
			);
			$this->_title = $db->loadResult();
	
			if ($error = $db->getErrorMsg()) {
				JError::raiseWarning(500, $error);
			}
		}

		return parent::getInput();
	}

	/**
	* Method to extend the buttons in the picker.
	*
	* @access	protected
	*
	* @return	array	An array of tasks
	*
	* @since	Cook 2.5.8
	*/
	protected function getTasks()
	{
		$labelAuto = JText::_('JFORMS_AUTO');
		$scriptAuto = "document.id('" . $this->id . "_id').value = 'auto';";
		$scriptAuto .= "document.id('" . $this->id . "_name').value = '" . htmlspecialchars($labelAuto, ENT_QUOTES, 'UTF-8') . "';";
		
		return array(
			'auto' => array(
				'label' => 'JFORMS_AUTO',
				'icon' => 'user',
				'jsCommand' => $scriptAuto,
				'description' => 'JFORMS_AUTOSELECT_CURRENT_USER'
			)

		);
	}


}

// Load the fork
JformsHelper::loadFork(__FILE__);

// Fallback if no fork has been found
if (!class_exists('JFormFieldModal_Thirduser')){ class JFormFieldModal_Thirduser extends JformsCkJFormFieldModal_Thirduser{} }

