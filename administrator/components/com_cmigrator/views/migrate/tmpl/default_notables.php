<?php
defined('_JEXEC') or die('Restricted access');

$session_cmigrator = JFactory::getSession();

$this->id_model = $session_cmigrator->get('id_cmigrator');
$session_cmigrator->clear('id_cmigrator');
$this->settings = CMigrateHelper::getSettings($this->id_model);

?>

<div class="width-40 fltlft">
	<h2 class="error"><?php echo JText::_('COM_CMIGRATOR_NOTABLES_ERROR'); ?></h2>
		<?php echo JText::sprintf('COM_CMIGRATOR_NOTABLES_ERROR_DESC', $this->settings->get('cms'),$this->settings->get('cms')); ?>
</div>