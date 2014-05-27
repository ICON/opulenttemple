<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
 
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('script','system/multiselect.js', false, true);

require_once JPATH_ADMINISTRATOR.DS.'components'.DS.'com_gigcal'.DS.'views'.DS.'js.php';

$fields = JRequest::GetCmd('fields', 'general');
?>

<div id="submenu-box">
  <div class="t">
    <div class="t">
      <div class="t"></div>
    </div>
  </div>
  <div class="m">
    <div class="submenu-box">
      <div class="submenu-pad">
        <ul id="submenu" class="config">
          <li><a id="general" href="<?php echo JRoute::_('index.php?option=com_gigcal&view=config').'">'.
            JText::_('COM_GIGCAL_CONFIG_GENERAL'); ?></a></li>
          <li><a id="list" href="<?php echo JRoute::_('index.php?option=com_gigcal&view=config&fields=list').'">'.
            JText::_('COM_GIGCAL_CONFIG_LIST'); ?></a></li>
          <li><a id="alist" href="<?php echo JRoute::_('index.php?option=com_gigcal&view=config&fields=alist').'">'.
            JText::_('COM_GIGCAL_CONFIG_ALIST'); ?></a></li>
          <li><a id="cal" href="<?php echo JRoute::_('index.php?option=com_gigcal&view=config&fields=cal').'">'.
            JText::_('COM_GIGCAL_CONFIG_CAL'); ?></a></li>
          <li><a id="menu" href="<?php echo JRoute::_('index.php?option=com_gigcal&view=config&fields=menu').'">'.
            JText::_('COM_GIGCAL_CONFIG_MENU'); ?></a></li>
          <li><a id="menu" href="<?php echo JRoute::_('index.php?option=com_gigcal&view=config&fields=details').'">'.
            JText::_('COM_GIGCAL_CONFIG_DETAIL'); ?></a></li>
          <li><a id="upcom" href="<?php echo JRoute::_('index.php?option=com_gigcal&view=config&fields=upcom').'">'.
            JText::_('COM_GIGCAL_CONFIG_UPCOM'); ?></a></li>
          <li><a id="minical" href="<?php echo JRoute::_('index.php?option=com_gigcal&view=config&fields=minical').'">'.
            JText::_('COM_GIGCAL_CONFIG_MINICAL'); ?></a></li>
          <li><a id="rss" href="<?php echo JRoute::_('index.php?option=com_gigcal&view=config&fields=rss').'">'.
            JText::_('COM_GIGCAL_CONFIG_RSS'); ?></a></li>
        </ul>
        <div class="clr"></div>
      </div>
    </div>
    <div class="clr"></div>
  </div>
  <div class="b">
    <div class="b">
      <div class="b"></div>
    </div>
  </div>
</div>

<?php    
  echo '<h1>'.ucwords($fields).' Config</h1>';
  require JPATH_ADMINISTRATOR.DS.'components'.DS.'com_gigcal'.DS.'views'.DS.'config'.DS.'tmpl'.DS.$fields.'.php';
?>
