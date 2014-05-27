<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

//echo 'items[<pre>'.print_r($this->items,true).'</pre>]';
$url = JRoute::_('index.php?option=com_gigcal&view=config&fields='.$fields);
?>

<form action="<?php echo $url ?>" method="post" name="adminForm" id="adminForm">
  <div class="clr"> </div>
<table width="680">
 <tr>
  <th>gigConfig - Details Page Templates</th>
 </tr>
</table>
<table width="680">
 <tr>
  <td valign="top"><div align="right"><b>Band Details</b></div></td>
  <td>
   <textarea name="details_band" rows="20" cols="100"><?php echo $this->config['details_band']; ?></textarea>
  </td>
 </tr>
 <tr>
  <td valign="top"><div align="right"><b>Venue Details</b></div></td>
  <td>
   <textarea name="details_venue" rows="20" cols="100"><?php echo $this->config['details_venue']; ?></textarea>
  </td>
 </tr>
</table>
<br />
<b>Date Format: </b> <a href="#" onclick="window.open('components/com_gigcal/datepopup.php', 'gigcal_datepopup_win', 'status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=640,height=480,directories=no,location=no');" alt="Date / Time formating options" title="Date / Time formating options">Date / Time formating options</a>
<br />
<table width="680">
 <tr width="110">
  <td valign="top"><div align="right"><b>Gig Dateformat</b></div></td>
  <td>
    <input type="text" name="details_dateformat" size="60" value="<?php echo htmlspecialchars($this->config['details_dateformat']); ?>">
  </td>
 </tr>
 <tr>
  <td valign="top"><div align="right"><b>Gig Timeformat</b></div></td>
  <td>
    <input type="text" name="details_timeformat" size="60" value="<?php echo htmlspecialchars($this->config['details_timeformat']); ?>">
  </td>
 </tr>
 <tr>
  <td valign="top"><div align="right"><b>Gig Details</b></div></td>
  <td>
   <textarea name="details_gig" rows="20" cols="100"><?php echo $this->config['details_gig']; ?></textarea>
  </td>
 </tr>
 <tr>
  <td valign="top"><div align="right"><b>Details CSS</b></div></td>
  <td>
   <textarea name="details_css" rows="20" cols="100"><?php echo $this->config['details_css']; ?></textarea>
  </td>
 </tr>
</table>

  <div>
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="boxchecked" value="0" />
    <?php echo JHtml::_('form.token'); ?>
  </div>
</form>

