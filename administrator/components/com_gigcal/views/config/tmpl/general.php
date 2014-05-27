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
      <td align="right"><b>Default View</b></td>
      <td>
        <select name="default_task">
<?php
        for($i=0; $i<count($this->items); $i++) 
        {
          $item=$this->items[$i];
          $selected='';
          if ($this->config['default_task']==$item->id)
            $selected = ' selected';

          echo '<option value="'.$item->id.'"'.$selected.'>'.$item->fieldname.'</option>';
        }
?>
        </select><br />
      </td>
    </tr>
    <tr>
      <td></td>
      <td><b>Other General Settings</b></td>
    </tr>
    <tr>
      <td valign="top"><div align="right"><b>CSS</b></div></td>
      <td>
        <textarea name="gen_css" rows="8" cols="50"><?php echo $this->config['gen_css']; ?></textarea>
      </td>
    </tr>
 <tr>
  <td valign="top"><div align="right"><b>Month and Day Names</b></div></td>
  <td>
<span>(<a href="javascript:expand('14','show14','hide14');" id="hide14" style="display: none;">Hide</a><a style="" href="javascript:expand('14','show14','hide14');" id="show14">Show</a>)</span>
<table id="14" style="display:none"><tr><td>
 <tr>
  <td><div align="right"><b>Monday</b></div></td>
  <td>
   <input type="text" name="cal_monday" size="20" value="<?php echo $this->config['cal_monday']; ?>">
  </td>
 </tr>
 <tr>
  <td><div align="right"><b>Tuesday</b></div></td>
  <td>
   <input type="text" name="cal_tuesday" size="20" value="<?php echo $this->config['cal_tuesday']; ?>">
  </td>
 </tr>
 <tr>
  <td><div align="right"><b>Wednesday</b></div></td>
  <td>
   <input type="text" name="cal_wendsday" size="20" value="<?php echo $this->config['cal_wendsday']; ?>">
  </td>
 </tr>
 <tr>
  <td><div align="right"><b>Thursday</b></div></td>
  <td>
   <input type="text" name="cal_thursday" size="20" value="<?php echo $this->config['cal_thursday']; ?>">
  </td>
 </tr>
 <tr>
  <td><div align="right"><b>Friday</b></div></td>
  <td>
   <input type="text" name="cal_friday" size="20" value="<?php echo $this->config['cal_friday']; ?>">
  </td>
 </tr>
 <tr>
  <td><div align="right"><b>Saturday</b></div></td>
  <td>
   <input type="text" name="cal_saturday" size="20" value="<?php echo $this->config['cal_saturday']; ?>">
  </td>
 </tr>
 <tr>
  <td><div align="right"><b>Sunday</b></div></td>
  <td>
   <input type="text" name="cal_sunday" size="20" value="<?php echo $this->config['cal_sunday']; ?>">
  </td>
 </tr>
 <tr>
  <td><div align="right"><b>January</b></div></td>
  <td>
   <input type="text" name="cal_january" size="20" value="<?php echo $this->config['cal_january']; ?>">
  </td>
 </tr>
 <tr>
  <td><div align="right"><b>February</b></div></td>
  <td>
   <input type="text" name="cal_february" size="20" value="<?php echo $this->config['cal_february']; ?>">
  </td>
 </tr>
 <tr>
  <td><div align="right"><b>March</b></div></td>
  <td>
   <input type="text" name="cal_march" size="20" value="<?php echo $this->config['cal_march']; ?>">
  </td>
 </tr>
 <tr>
  <td><div align="right"><b>April</b></div></td>
  <td>
   <input type="text" name="cal_april" size="20" value="<?php echo $this->config['cal_april']; ?>">
  </td>
 </tr>
 <tr>
  <td><div align="right"><b>May</b></div></td>
  <td>
   <input type="text" name="cal_may" size="20" value="<?php echo $this->config['cal_may']; ?>">
  </td>
 </tr>
 <tr>
  <td><div align="right"><b>June</b></div></td>
  <td>
   <input type="text" name="cal_june" size="20" value="<?php echo $this->config['cal_june']; ?>">
  </td>
 </tr>
 <tr>
  <td><div align="right"><b>July</b></div></td>
  <td>
   <input type="text" name="cal_july" size="20" value="<?php echo $this->config['cal_july']; ?>">
  </td>
 </tr>
 <tr>
  <td><div align="right"><b>August</b></div></td>
  <td>
   <input type="text" name="cal_august" size="20" value="<?php echo $this->config['cal_august']; ?>">
  </td>
 </tr>
 <tr>
  <td><div align="right"><b>September</b></div></td>
  <td>
   <input type="text" name="cal_september" size="20" value="<?php echo $this->config['cal_september']; ?>">
  </td>
 </tr>
 <tr>
  <td><div align="right"><b>October</b></div></td>
  <td>
   <input type="text" name="cal_october" size="20" value="<?php echo $this->config['cal_october']; ?>">
  </td>
 </tr>
 <tr>
  <td><div align="right"><b>November</b></div></td>
  <td>
   <input type="text" name="cal_november" size="20" value="<?php echo $this->config['cal_november']; ?>">
  </td>
 </tr>
 <tr>
  <td><div align="right"><b>December</b></div></td>
  <td>
   <input type="text" name="cal_december" size="20" value="<?php echo $this->config['cal_december']; ?>">
  </td>
 </tr>
</table>
</td>
</tr>


 <tr>
  <td valign="top"><div align="right"><b>Show Gig Clone</b></div></td>
  <td>
   <input type="hidden" name="show_gig_clone" value="0" />
   <input type="checkbox" value='1'  name="show_gig_clone" <?php if($this->config['show_gig_clone']!=0) echo 'checked'; ?>>
  </td>
 </tr>

 <tr>
  <td valign="top"><div align="right"><b>Auto Gig Clone</b></div></td>
  <td>
   <input type="hidden" name="auto_gig_clone" value="0" />
   <input type="checkbox" value='1'  name="auto_gig_clone" <?php if($this->config['auto_gig_clone']!=0) echo 'checked'; ?>>
   Initialize new gig fields with last added <i>this setting overrides the "default" band setting, if any</i>
  </td>
 </tr>

 <tr>
  <td valign="top"><div align="right"><b>Show Export ALL iCal/vCal links</b></div></td>
  <td>
   <input type="hidden" name="export_all_cals" value="0" />
   <input type="checkbox" value='1'  name="export_all_cals" <?php if($this->config['export_all_cals']!=0) echo 'checked'; ?>>
   Enable export all link on iCal/vCal header</i>
  </td>
 </tr>

</table>

<div>
    <input type="hidden" name="task" value="" />
    <?php echo JHtml::_('form.token'); ?>
</div>

</form>

