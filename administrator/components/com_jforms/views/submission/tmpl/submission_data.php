<?php
/**                               ______________________________________________
*                          o O   |                                              |
*                 (((((  o      <    Generated with Cook Self Service  V2.6.2   |
*                ( o o )         |______________________________________________|
* --------oOOO-----(_)-----OOOo---------------------------------- www.j-cook.pro --- +
* @version		0.2.9
* @package		jForms
* @subpackage	Submissions
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

$doc = JFactory::getDocument();
$siteUrl = JURI::root(true);
$baseSite = 'components' .DS. COM_JFORMS;
$baseAdmin = 'administrator' .DS. 'components' .DS. COM_JFORMS;
$componentUrl = $siteUrl . '/' . str_replace(DS, '/', $baseSite);
$componentUrlAdmin = $siteUrl . '/' . str_replace(DS, '/', $baseAdmin);
$doc->addStyleSheet($componentUrl . '/css/pdf.css');

$this->item->jforms_snapshot = JformsHelper::getjFieldsets($this->item->jforms_snapshot,false);
$this->item->jforms_snapshot = JformsHelper::getjFormLanguageFiles($this->item->jforms_snapshot, false, true);
$ml_fields = JformsHelper::getMultilangTables();
$this->item->jforms_snapshot = JformsHelper::getMlFields($this->item->jforms_snapshot,$ml_fields['forms']);
			
$this->item->jforms_snapshot = JformsHelper::getMainForm($this->item->jforms_snapshot,false);
			
$data = array();
$data['options'] = array('printAll' => true);
$data['jforms_snapshot'] = $this->item->jforms_snapshot;
$data['form_data'] = $this->item->form_data;

$version = new JVersion();
// Joomla! 1.6 - 1.7 - 2.5
if (version_compare($version->RELEASE, '2.5', '<='))
{
	$displayData = $data;
	include(JPATH_SITE .'/components/com_jforms/layouts/submission_pdf.php');	
} else {
	$layout = new JLayoutFile('submission_pdf', JPATH_ROOT .'/components/com_jforms/layouts/');
	$output = $layout->render($data);
	echo $output;
}

?>
