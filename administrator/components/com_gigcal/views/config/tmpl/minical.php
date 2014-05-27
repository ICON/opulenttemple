<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

//echo 'items[<pre>'.print_r($this->items,true).'</pre>]';
$url = JRoute::_('index.php?option=com_gigcal&view=config&fields='.$fields);
?>

<form action="<?php echo $url ?>" method="post" name="adminForm" id="adminForm">
  <div class="clr"> </div>
  <table class="adminlist">

 <tr>
  <th>gigConfig - MiniCal Settings</th>
 </tr>
</table>

<table>
  <tr>
    <td>
      <input type="hidden" name="minical_hover" value="0" />
      <input type="checkbox" value="1" name="minical_hover" <?php if($this->config['minical_hover']==1) echo 'checked'; ?>>gigDay Hover<br />
      <input type="hidden" name="minical_hover_bandname" value="0" />
      <input type="checkbox" value="1" name="minical_hover_bandname" <?php if($this->config['minical_hover_bandname']==1) echo 'checked'; ?>>gigDay Hover shows bandname<br />
      <input type="hidden" name="minical_hover_venue" value="0" />
      <input type="checkbox" value="1" name="minical_hover_venue" <?php if($this->config['minical_hover_venue']==1) echo 'checked'; ?>>gigDay Hover shows venue<br />
      <input type="hidden" name="minical_hover_cityst" value="0" />
      <input type="checkbox" value="1" name="minical_hover_cityst" <?php if($this->config['minical_hover_cityst']==1) echo 'checked'; ?>>gigDay  Hover shows cityst<br />
      <input type="hidden" name="minical_hover_date" value="0" />
      <input type="checkbox" value="1" name="minical_hover_date" <?php if($this->config['minical_hover_date']==1) echo 'checked'; ?>>gigDay  Hover shows date<br />
      <b>Date Format: </b><a href="#" onclick="window.open('components/com_gigcal/datepopup.php', 'gigcal_datepopup_win', 'status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=640,height=480,directories=no,location=no');" alt="Date / Time formating options" title="Date / Time formating options">Date / Time formating options</a><br />
      <input type="text" name="minical_dateformat" size="60" value="<?php echo htmlspecialchars($this->config['minical_dateformat']); ?>"><br />
      <input type="hidden" name="minical_hover_time" value="0" />
      <input type="checkbox" value="1" name="minical_hover_time" <?php if($this->config['minical_hover_time']==1) echo 'checked'; ?>>gigDay  Hover shows time<br />
      <b>Time Format: </b><a href="#" onclick="window.open('components/com_gigcal/datepopup.php', 'gigcal_datepopup_win', 'status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=640,height=480,directories=no,location=no');" alt="Date / Time formating options" title="Date / Time formating options">Date / Time formating options</a><br />
      <input type="text" name="minical_timeformat" size="60" value="<?php echo htmlspecialchars($this->config['minical_timeformat']); ?>"><br />
      <input type="hidden" name="minical_hover_gigtitle" value="0" />
      <input type="checkbox" value="1" name="minical_hover_gigtitle" <?php if($this->config['minical_hover_gigtitle']==1) echo 'checked'; ?>>gigDay  Hover shows gigtitle<br />
      <input type="hidden" name="minical_hover_covercharge" value="0" />
      <input type="checkbox" value="1" name="minical_hover_covercharge" <?php if($this->config['minical_hover_covercharge']==1) echo 'checked'; ?>>gigDay  Hover shows covercharge<br />
      <input type="hidden" name="minical_hover_notes" value="0" />
      <input type="checkbox" value="1" name="minical_hover_notes" <?php if($this->config['minical_hover_notes']==1) echo 'checked'; ?>>gigDay  Hover shows notes<br />
     <input type="radio" name="minical_link" value="0" <?php if($this->config['minical_link']=='0') echo 'checked'; ?>>miniCal gigDay link links to nothing<br />
     <input type="radio" name="minical_link" value="1" <?php if($this->config['minical_link']=='1') echo 'checked'; ?>>miniCal gigDay link links to gigDetails page<br />
     <input type="radio" name="minical_link" value="2" <?php if($this->config['minical_link']=='2') echo 'checked'; ?>>miniCal gigDay link links to dayDetails page<br />
     <b>gigCal Link Text</b><br />
     <input type="text" name="minical_gigcal_link_text" size="60" value="<?php echo htmlspecialchars($this->config['minical_gigcal_link_text']); ?>"><br />
     <input type="radio" name="minical_gigcal_link_to" value="" <?php if($this->config['minical_gigcal_link_to']=='') echo 'checked'; ?>>miniCal gigCal link links to List<br />
     <input type="radio" name="minical_gigcal_link_to" value="calendar" <?php if($this->config['minical_gigcal_link_to']=='calendar') echo 'checked'; ?>>miniCal gigCal link links to Calendar<br />
     <input type="radio" name="minical_gigcal_link_to" value="rss" <?php if($this->config['minical_gigcal_link_to']=='rss') echo 'checked'; ?>>miniCal gigCal link links to RSS Feeds<br />
     </td></tr></table>
<br />
<table width="680">
 <tr>
 <td width="100"></td>
 <td><b>Other miniCal Settings</b></td>
 </tr>
 <tr>
  <td><div align="right"><b>Overlib Params (<a target="_blank" href="http://www.bosrup.com/web/overlib/?Command_Reference">Command Reference</a>)</b></div></td>
  <td>
   <input type="text" name="minical_hover_params" size="60" value="<?php echo $this->config['minical_hover_params']; ?>">
  </td>
 </tr>
 <tr>
  <td valign="top"><div align="right"><b>Intro Text</b></div></td>
  <td>
   <textarea name="minical_text" rows="20" cols="100"><?php echo $this->config['minical_text']; ?></textarea>
  </td>
 </tr>
 <tr>
  <td valign="top"><div align="right"><b>CSS</b></div></td>
  <td>
   <textarea name="minical_css" rows="20" cols="100"><?php echo $this->config['minical_css']; ?></textarea>
  </td>
 </tr>

  </table>
  <div>
    <input type="hidden" name="task" value="" />
    <?php echo JHtml::_('form.token'); ?>
  </div>
</form>

