<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
 
// load tooltip behavior
JHtml::_('behavior.tooltip');

jimport('joomla.utilities.date');

require JPATH_ADMINISTRATOR.'/components/com_gigcal/views/data/TimezoneSelector.php';

?>
<h1>Import/Export</h1>

<form action="<?php echo JURI::base(); ?>index.php?option=com_gigcal&amp;task=import.data" method="post" enctype="multipart/form-data">
  <fieldset id="importform">
    <legend><?php echo JText::_('COM_GIGCAL_IMPORT_DATA'); ?></legend>
    <fieldset>
      <input type="file" id="file1" name="file1" size="60" />
      <label for="clearall"><?php echo JText::_('COM_GIGCAL_IMPORT_CLEARALL'); ?></label>
      <input type="checkbox" id="clearall" name="clearall">
      <label for="timezone"><?php echo JText::_('COM_GIGCAL_IMPORT_TIMEZONE'); ?></label>
<?php
      $og = '';
      $ctz = date_default_timezone_get();
      echo '<select id="timezone" name="timezone">';
      foreach(timezone_identifiers_list() as $tz)
      {
        $tza = explode('/', $tz, 2);
        if ($og != $tza[0])
        {
          $og = $tza[0];
          echo '<optgroup label="'.$og.'">';
        }
        if (count($tza) < 2)
          $tza[1] = $tza[0];
        echo '<option value="'.$tz.'"';
        if($tz==$ctz)
          echo ' selected="selected"';
        echo '>'.$tza[1].'</option>';
      }
      echo '</select>';
?>
      <input id="importdata" type="submit" value="<?php echo JText::_('COM_GIGCAL_IMPORT_START'); ?>" style="clear:left;" />
      <input id="exportdata" type="button" value="<?php echo JText::_('COM_GIGCAL_EXPORT_START'); ?>" style="float:left;margin-left:325px;" 
        onclick="window.open('components/com_gigcal/controllers/export.php?type=data')"/>
    </fieldset>
  </fieldset>
</form>

<form action="<?php echo JURI::base(); ?>index.php?option=com_gigcal&amp;task=import.settings" method="post" enctype="multipart/form-data">
  <fieldset id="importform">
    <legend><?php echo JText::_('COM_GIGCAL_IMPORT_SETTINGS'); ?></legend>
    <fieldset>
      <input type="file" id="file2" name="file2" size="60" />
      <input id="importsettings" type="submit" value="<?php echo JText::_('COM_GIGCAL_IMPORT_START'); ?>" style="clear:left;" />
      <input id="exportsettings" type="button" value="<?php echo JText::_('COM_GIGCAL_EXPORT_START'); ?>" style="float:left;margin-left:325px;"
        onclick="window.open('components/com_gigcal/controllers/export.php?type=settings')"/>
    </fieldset>
  </fieldset>
</form>

