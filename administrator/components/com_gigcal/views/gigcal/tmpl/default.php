<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
 
// load tooltip behavior
JHtml::_('behavior.tooltip');
?>
<form action="<?php echo JRoute::_('index.php?option=com_gigcal'); ?>" method="post" name="adminForm">
  <table class="adminform">
    <tr> 
      <td width="55%" valign="top"> <div id="cpanel"> 
        <div style="float:left;"> 
          <div class="icon"> <a href="index.php?option=com_gigcal&view=gigs"> 
            <div class="iconimage"> <img src="components/com_gigcal/media/images/gigs-48x48.png" alt="<?php echo JText::_('COM_GIGCAL_GIGMANAGER') ?>" align="middle" name="image" border="0" /> </div> 
          <?php echo JText::_('COM_GIGCAL_GIGMANAGER') ?></a></div> 
        </div> 
        <div style="float:left;"> 
          <div class="icon"> <a href="index.php?option=com_gigcal&view=bands"> 
            <div class="iconimage"> <img src="components/com_gigcal/media/images/bands-48x48.png" alt="<?php echo JText::_('COM_GIGCAL_BANDMANAGER') ?>" align="middle" name="image" border="0" /> </div> 
          <?php echo JText::_('COM_GIGCAL_BANDMANAGER') ?></a></div> 
        </div> 
        <div style="float:left;"> 
          <div class="icon"> <a href="index.php?option=com_gigcal&view=venues"> 
            <div class="iconimage"> <img src="components/com_gigcal/media/images/venues-48x48.png" alt="<?php echo JText::_('COM_GIGCAL_VENUEMANAGER') ?>" align="middle" name="image" border="0" /> </div> 
          <?php echo JText::_('COM_GIGCAL_VENUEMANAGER') ?></a></div> 
        </div> 
        <div style="float:left;"> 
          <div class="icon"> <a href="index.php?option=com_gigcal&view=about"> 
            <div class="iconimage"> <img src="components/com_gigcal/media/images/about-48x48.png" alt="<?php echo JText::_('COM_GIGCAL_ABOUT') ?>" align="middle" name="image" border="0" /> </div> 
          <?php echo JText::_('COM_GIGCAL_ABOUT') ?></a></div> 
        </div> 
      </div>
      </td> 
    </tr>
  </table>
</form>

