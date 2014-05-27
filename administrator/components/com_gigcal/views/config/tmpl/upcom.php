<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

//echo 'items[<pre>'.print_r($this->items,true).'</pre>]';
$url = JRoute::_('index.php?option=com_gigcal&view=config&fields='.$fields);
?>

<form action="<?php echo $url ?>" method="post" name="adminForm" id="adminForm">
  <div class="clr"> </div>
  <table class="adminlist">
    <thead>
      <tr>
        <th width="1%">
          <input type="checkbox" name="checkall-toggle" value="" onclick="checkAll(this)" />
        </th>
        <th width="60">
          <?php echo JText::_('JSTATUS'); ?>
        </th>
        <th>
          <?php echo JText::_('JGRID_HEADING_ORDERING'); ?>
        </th>
        <th width="200" class="left">
          <?php echo JText::_('COM_GIGCAL_FIELD_NAME_LABEL'); ?>
        </th>
        <th width="*" class="left">
          <?php echo JText::_('COM_GIGCAL_OPTIONS_LABEL'); ?>
        </th>
        <th width="50" class="nowrap">
          <?php echo JText::_('JGRID_HEADING_ID'); ?>
        </th>
      </tr>
    </thead>
    <tbody>
    <?php foreach ($this->items as $i => $item) :
      ?>
      <tr class="row<?php echo $i % 2; ?>">
        <td class="center">
          <?php echo JHtml::_('grid.id', $i, $item->id); ?>
        </td>

        <td class="center">
          <?php echo JHtml::_('jgrid.published', $item->published, $i, 'config.'); ?>
        </td>

        <td class="order" style="width:60px;">
          <span><?php echo $this->pagination->orderUpIcon($i, true, 'config.orderup', 'JLIB_HTML_MOVE_UP'); ?></span>
          <span><?php echo $this->pagination->orderDownIcon($i, count($this->items), true, 'config.orderdown', 'JLIB_HTML_MOVE_DOWN'); ?></span>
          <?php //echo $item->ordering; ?>
        </td>

        <td class="left">
          <?php echo $item->fieldname; ?>
        </td>


<?php   if($item->fieldname=="gigDate") { ?>
        <td><span>(
          <a href="javascript:expand('1','show1','hide1');" id="hide1" style="display: none;">Hide</a>
          <a style="" href="javascript:expand('1','show1','hide1');" id="show1">Show</a>)</span>
          <table id="1" style="display:none">
            <tr>
              <td>
                <input type="hidden" name="upcom_gigdate_hover" value="0" />
                <input type="checkbox" value="1" name="upcom_gigdate_hover" <?php if($this->config['upcom_gigdate_hover']==1) echo "checked"; ?>>gigDate Hover<br />
                <input type="hidden" name="upcom_gigdate_hover_bandname" value="0" />
                <input type="checkbox" value="1" name="upcom_gigdate_hover_bandname" <?php if($this->config['upcom_gigdate_hover_bandname']==1) echo "checked"; ?>>gigDate Hover shows bandname<br />
                <input type="hidden" name="upcom_gigdate_hover_venue" value="0" />
                <input type="checkbox" value="1" name="upcom_gigdate_hover_venue" <?php if($this->config['upcom_gigdate_hover_venue']==1) echo "checked"; ?>>gigDate Hover shows venue<br />
                <input type="hidden" name="upcom_gigdate_hover_cityst" value="0" />
                <input type="checkbox" value="1" name="upcom_gigdate_hover_cityst" <?php if($this->config['upcom_gigdate_hover_cityst']==1) echo "checked"; ?>>gigDate  Hover shows cityst<br />
                <input type="hidden" name="upcom_gigdate_hover_date" value="0" />
                <input type="checkbox" value="1" name="upcom_gigdate_hover_date" <?php if($this->config['upcom_gigdate_hover_date']==1) echo "checked"; ?>>gigDate  Hover shows date<br />
                <input type="hidden" name="upcom_gigdate_hover_time" value="0" />
                <input type="checkbox" value="1" name="upcom_gigdate_hover_time" <?php if($this->config['upcom_gigdate_hover_time']==1) echo "checked"; ?>>gigDate  Hover shows time<br />
                <input type="hidden" name="upcom_gigdate_hover_gigtitle" value="0" />
                <input type="checkbox" value="1" name="upcom_gigdate_hover_gigtitle" <?php if($this->config['upcom_gigdate_hover_gigtitle']==1) echo "checked"; ?>>gigDate  Hover shows gigtitle<br />
                <input type="hidden" name="upcom_gigdate_hover_covercharge" value="0" />
                <input type="checkbox" value="1" name="upcom_gigdate_hover_covercharge" <?php if($this->config['upcom_gigdate_hover_covercharge']==1) echo "checked"; ?>>gigDate  Hover shows covercharge<br />
                <input type="hidden" name="upcom_gigdate_hover_notes" value="0" />
                <input type="checkbox" value="1" name="upcom_gigdate_hover_notes" <?php if($this->config['upcom_gigdate_hover_notes']==1) echo "checked"; ?>>gigDate  Hover shows notes<br />
                <input type="hidden" name="upcom_gigdate_link" value="0" />
                <input type="checkbox" value="1" name="upcom_gigdate_link" <?php if($this->config['upcom_gigdate_link']==1) echo "checked"; ?>>gigDate Links to gigDetails page<br />
                <b>Date Format: </b>
                  <a href="#" onclick="window.open('components/com_gigcal/datepopup.php', 'gigcal_datepopup_win', 'status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=640,height=480,directories=no,location=no');" alt="Date / Time formating options" title="Date / Time formating options">Date / Time formating options</a><br />
                <input type="text" name="upcom_dateformat" size="60" value="<?php echo htmlspecialchars($this->config['upcom_dateformat']); ?>">
              </td>
            </tr>
          </table>
        </td>

<?php } if($item->fieldname=="gigDate2") { ?>
        <td><span>(
          <a href="javascript:expand('2','show2','hide2');" id="hide2" style="display: none;">Hide</a>
          <a style="" href="javascript:expand('2','show2','hide2');" id="show2">Show</a>)</span>
          <table id="2" style="display:none">
            <tr>
              <td>
                <input type="hidden" name="upcom_gigdate2_hover" value="0" />
                <input type="checkbox" value="1" name="upcom_gigdate2_hover" <?php if($this->config['upcom_gigdate2_hover']==1) echo "checked"; ?>>gigDate2 Hover<br />
                <input type="hidden" name="upcom_gigdate2_hover_bandname" value="0" />
                <input type="checkbox" value="1" name="upcom_gigdate2_hover_bandname" <?php if($this->config['upcom_gigdate2_hover_bandname']==1) echo "checked"; ?>>gigDate2 Hover shows bandname<br />
                <input type="hidden" name="upcom_gigdate2_hover_venue" value="0" />
                <input type="checkbox" value="1" name="upcom_gigdate2_hover_venue" <?php if($this->config['upcom_gigdate2_hover_venue']==1) echo "checked"; ?>>gigDate2 Hover shows venue<br />
                <input type="hidden" name="upcom_gigdate2_hover_cityst" value="0" />
                <input type="checkbox" value="1" name="upcom_gigdate2_hover_cityst" <?php if($this->config['upcom_gigdate2_hover_cityst']==1) echo "checked"; ?>>gigDate2  Hover shows cityst<br />
                <input type="hidden" name="upcom_gigdate2_hover_date" value="0" />
                <input type="checkbox" value="1" name="upcom_gigdate2_hover_date" <?php if($this->config['upcom_gigdate2_hover_date']==1) echo "checked"; ?>>gigDate2  Hover shows date<br />
                <input type="hidden" name="upcom_gigdate2_hover_time" value="0" />
                <input type="checkbox" value="1" name="upcom_gigdate2_hover_time" <?php if($this->config['upcom_gigdate2_hover_time']==1) echo "checked"; ?>>gigDate2  Hover shows time<br />
                <input type="hidden" name="upcom_gigdate2_hover_gigtitle" value="0" />
                <input type="checkbox" value="1" name="upcom_gigdate2_hover_gigtitle" <?php if($this->config['upcom_gigdate2_hover_gigtitle']==1) echo "checked"; ?>>gigDate2  Hover shows gigtitle<br />
                <input type="hidden" name="upcom_gigdate2_hover_covercharge" value="0" />
                <input type="checkbox" value="1" name="upcom_gigdate2_hover_covercharge" <?php if($this->config['upcom_gigdate2_hover_covercharge']==1) echo "checked"; ?>>gigDate2  Hover shows covercharge<br />
                <input type="hidden" name="upcom_gigdate2_hover_notes" value="0" />
                <input type="checkbox" value="1" name="upcom_gigdate2_hover_notes" <?php if($this->config['upcom_gigdate2_hover_notes']==1) echo "checked"; ?>>gigDate2  Hover shows notes<br />
                <input type="hidden" name="upcom_gigdate2_link" value="0" />
                <input type="checkbox" value="1" name="upcom_gigdate2_link" <?php if($this->config['upcom_gigdate2_link']==1) echo "checked"; ?>>gigDate2 Links to gigDetails page<br />
                <b>Date Format: </b>
                  <a href="#" onclick="window.open('components/com_gigcal/datepopup.php', 'gigcal_datepopup_win', 'status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=640,height=480,directories=no,location=no');" alt="Date / Time formating options" title="Date / Time formating options">Date / Time formating options</a><br />
                <input type="text" name="upcom_dateformat2" size="60" value="<?php echo htmlspecialchars($this->config['upcom_dateformat2']); ?>">
              </td>
            </tr>
          </table>
        </td>
   
<?php } if($item->fieldname=="gigBand") { ?>
        <td><span>(
          <a href="javascript:expand('3','show3','hide3');" id="hide3" style="display: none;">Hide</a>
          <a style="" href="javascript:expand('3','show3','hide3');" id="show3">Show</a>)</span>
          <table id="3" style="display:none">
            <tr>
              <td>
                <input type="hidden" name="upcom_gigband_popup" value="0" />
                <input type="checkbox" value="1" name="upcom_gigband_popup" <?php if($this->config['upcom_gigband_popup']==1) echo "checked"; ?>>gigBand hover displays Band Details popup<br />
                <input type="radio" name="upcom_gigband_link" value="bandwebsite" <?php if($this->config['upcom_gigband_link']=="bandwebsite") echo "checked"; ?>>gigBand links to Band Website<br />
                <input type="radio" name="upcom_gigband_link" value="banddetails" <?php if($this->config['upcom_gigband_link']=="banddetails") echo "checked"; ?>>gigBand links to Band Details Page<br />
                <input type="radio" name="upcom_gigband_link" value="nothing" <?php if($this->config['upcom_gigband_link']=="nothing") echo "checked"; ?>>gigBand links to Nothing
              </td>
            </tr>
          </table>
        </td>

<?php } if($item->fieldname=="gigVenue") { ?>
        <td><span>(
          <a href="javascript:expand('4','show4','hide4');" id="hide4" style="display: none;">Hide</a>
          <a style="" href="javascript:expand('4','show4','hide4');" id="show4">Show</a>)</span>
          <table id="4" style="display:none">
            <tr>
              <td>
                <input type="hidden" name="upcom_gigvenue_popup" value="0" />
                <input type="checkbox" value="1" name="upcom_gigvenue_popup" <?php if($this->config['upcom_gigvenue_popup']==1) echo "checked"; ?>>gigVenue hover displays Venue Details popup<br />
                <input type="radio" name="upcom_gigvenue_link" value="venuewebsite" <?php if($this->config['upcom_gigvenue_link']=="venuewebsite") echo "checked"; ?>>gigVenue links to Venue Website<br />
                <input type="radio" name="upcom_gigvenue_link" value="map" <?php if($this->config['upcom_gigvenue_link']=="map") echo "checked"; ?>>gigVenue links to Map<br />
                <input type="radio" name="upcom_gigvenue_link" value="venuedetails" <?php if($this->config['upcom_gigvenue_link']=="venuedetails") echo "checked"; ?>>gigVenue links to Venue Details Page<br />
                <input type="radio" name="upcom_gigvenue_link" value="nothing" <?php if($this->config['upcom_gigvenue_link']=="nothing") echo "checked"; ?>>gigVenue links to Nothing
              </td>
            </tr>
          </table>
        </td>

<?php } if($item->fieldname=="Location") { ?>
        <td><span>(
          <a href="javascript:expand('5','show5','hide5');" id="hide5" style="display: none;">Hide</a>
          <a style="" href="javascript:expand('5','show5','hide5');" id="show5">Show</a>)</span>
          <table id="5" style="display:none">
            <tr>
              <td>
                <input type="hidden" name="upcom_location_popup" value="0" />
                <input type="checkbox" value="1" name="upcom_location_popup" <?php if($this->config['upcom_location_popup']==1) echo "checked"; ?>>Location hover displays Venue Details popup<br />
                <input type="radio" name="upcom_location_link" value="venuewebsite" <?php if($this->config['upcom_location_link']=="venuewebsite") echo "checked"; ?>>Location links to Venue Website<br />
                <input type="radio" name="upcom_location_link" value="map" <?php if($this->config['upcom_location_link']=="map") echo "checked"; ?>>Location links to Map<br />
                <input type="radio" name="upcom_location_link" value="venuedetails" <?php if($this->config['upcom_location_link']=="venuedetails") echo "checked"; ?>>Location links to Venue Details Page<br />
                <input type="radio" name="upcom_location_link" value="nothing" <?php if($this->config['upcom_location_link']=="nothing") echo "checked"; ?>>Location links to Nothing
              </td>
            </tr>
          </table>
        </td>

<?php } if($item->fieldname=="Country") { ?>
        <td><span>(
          <a href="javascript:expand('6','show6','hide6');" id="hide6" style="display: none;">Hide</a>
          <a style="" href="javascript:expand('6','show6','hide6');" id="show6">Show</a>)</span>
          <table id="6" style="display:none">
            <tr>
              <td>
                <input type="hidden" name="upcom_country_popup" value="0" />
                <input type="checkbox" value="1" name="upcom_country_popup" <?php if($this->config['upcom_country_popup']==1) echo "checked"; ?>>Country hover displays Venue Details popup<br />
                <input type="radio" name="upcom_country_link" value="venuewebsite" <?php if($this->config['upcom_country_link']=="venuewebsite") echo "checked"; ?>>Country links to Venue Website<br />
                <input type="radio" name="upcom_country_link" value="map" <?php if($this->config['upcom_country_link']=="map") echo "checked"; ?>>Country links to Map<br />
                <input type="radio" name="upcom_country_link" value="venuedetails" <?php if($this->config['upcom_country_link']=="venuedetails") echo "checked"; ?>>Country links to Venue Details Page<br />
                <input type="radio" name="upcom_country_link" value="nothing" <?php if($this->config['upcom_country_link']=="nothing") echo "checked"; ?>>Country links to Nothing
              </td>
            </tr>
          </table>
        </td>
  
<?php } if($item->fieldname=="Link to Map") { ?>
        <td><span>(
          <a href="javascript:expand('7','show7','hide7');" id="hide7" style="display: none;">Hide</a>
          <a style="" href="javascript:expand('7','show7','hide7');" id="show7">Show</a>)</span>
          <table id="7" style="display:none">
            <tr>
              <td>
                <input type="text" name="upcom_maplink_name" size="20" value="<?php echo $this->config['upcom_maplink_name']; ?>">Map Link Name
              </td>
            </tr>
          </table>
        </td>

<?php } if($item->fieldname=="iCal Link") { ?>
        <td><span>(
          <a href="javascript:expand('8','show8','hide8');" id="hide8" style="display: none;">Hide</a>
          <a style="" href="javascript:expand('8','show8','hide8');" id="show8">Show</a>)</span>
          <table id="8" style="display:none">
            <tr>
              <td>
                <input type="text" name="upcom_ical_link_name" size="20" value="<?php echo $this->config['upcom_ical_link_name']; ?>">iCal Link Name
              </td>
            </tr>
          </table>
        </td>

<?php } if($item->fieldname=="vCal Link") { ?>
        <td><span>(
          <a href="javascript:expand('9','show9','hide9');" id="hide9" style="display: none;">Hide</a>
          <a style="" href="javascript:expand('9','show9','hide9');" id="show9">Show</a>)</span>
          <table id="9" style="display:none">
            <tr>
              <td>
                <input type="text" name="upcom_vcal_link_name" size="20" value="<?php echo $this->config['upcom_vcal_link_name']; ?>">vCal Link Name
              </td>
            </tr>
          </table>
        </td>

<?php } if($item->fieldname=="gigTime") { ?>
        <td><span>(
          <a href="javascript:expand('10','show10','hide10');" id="hide10" style="display: none;">Hide</a>
          <a style="" href="javascript:expand('10','show10','hide10');" id="show10">Show</a>)</span>
          <table id="10" style="display:none">
            <tr>
              <td>
                <input type="hidden" name="upcom_gigtime_link" value="0" />
                <input type="checkbox" value="1" name="upcom_gigtime_link" <?php if($this->config['upcom_gigtime_link']==1) echo "checked"; ?>>gigTime Links to gigDetails page<br />
                <b>Time Format: </b>
                <a href="#" onclick="window.open('components/com_gigcal/datepopup.php', 'gigcal_datepopup_win', 'status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=640,height=480,directories=no,location=no');" alt="Date / Time formating options" title="Date / Time formating options">Date / Time formating options</a><br />
                <input type="text" name="upcom_timeformat" size="60" value="<?php echo htmlspecialchars($this->config['upcom_timeformat']); ?>">
              </td>
            </tr>
          </table>
        </td>

<?php } if($item->fieldname=="Link to online Ticket Sales") { ?>
        <td><span>(
          <a href="javascript:expand('11','show11','hide11');" id="hide11" style="display: none;">Hide</a>
          <a style="" href="javascript:expand('11','show11','hide11');" id="show11">Show</a>)</span>
          <table id="11" style="display:none">
            <tr>
              <td>
                <input type="text" name="upcom_ticketlink_name" size="20" value="<?php echo $this->config['upcom_ticketlink_name']; ?>">Ticket Link Name
              </td>
            </tr>
          </table>
        </td>

<?php } if($item->fieldname=="gigTitle") { ?>
        <td><span>(
          <a href="javascript:expand('12','show12','hide12');" id="hide12" style="display: none;">Hide</a>
          <a style="" href="javascript:expand('12','show12','hide12');" id="show12">Show</a>)</span>
          <table id="12" style="display:none">
            <tr>
              <td>
                <input type="hidden" name="upcom_gigtitle_hover" value="0" />
                <input type="checkbox" value="1" name="upcom_gigtitle_hover" <?php if($this->config['upcom_gigtitle_hover']==1) echo 'checked'; ?>>gigTitle Hover<br />
                <input type="hidden" name="upcom_gigtitle_hover_bandname" value="0" />
                <input type="checkbox" value="1" name="upcom_gigtitle_hover_bandname" <?php if($this->config['upcom_gigtitle_hover_bandname']==1) echo 'checked'; ?>>gigTitle Hover shows bandname<br />
                <input type="hidden" name="upcom_gigtitle_hover_venue" value="0" />
                <input type="checkbox" value="1" name="upcom_gigtitle_hover_venue" <?php if($this->config['upcom_gigtitle_hover_venue']==1) echo 'checked'; ?>>gigTitle Hover shows venue<br />
                <input type="hidden" name="upcom_gigtitle_hover_date" value="0" />
                <input type="checkbox" value="1" name="upcom_gigtitle_hover_date" <?php if($this->config['upcom_gigtitle_hover_date']==1) echo 'checked'; ?>>gigTitle Hover shows date<br />
                <input type="hidden" name="upcom_gigtitle_hover_time" value="0" />
                <input type="checkbox" value="1" name="upcom_gigtitle_hover_time" <?php if($this->config['upcom_gigtitle_hover_time']==1) echo 'checked'; ?>>gigTitle Hover shows time<br />
                <input type="hidden" name="upcom_gigtitle_link" value="0" />
                <input type="checkbox" value="1" name="upcom_gigtitle_link" <?php if($this->config['upcom_gigtitle_link']==1) echo 'checked'; ?>>gigTitle Links to gigDetails page<br />
                gigTitle Prefix:
                <input type="text" name="upcom_gigtitle_name" size="50" value="<?php echo htmlspecialchars($this->config['upcom_gigtitle_name']); ?>">
              </td>
            </tr>
          </table>
        </td>

<?php } if($item->fieldname=="gigCover Charge") { ?>
        <td><span>(
          <a href="javascript:expand('13','show13','hide13');" id="hide13" style="display: none;">Hide</a>
          <a style="" href="javascript:expand('13','show13','hide13');" id="show13">Show</a>)</span>
          <table id="13" style="display:none">
            <tr>
              <td>
                <input type="text" name="upcom_covercharge_name" size="20" value="<?php echo $this->config['upcom_covercharge_name']; ?>">Cover Charge Prefix
              </td>
            </tr>
          </table>
        </td>

<?php } if($item->fieldname=="gigNotes/Info") { ?>
        <td><span>(
          <a href="javascript:expand('14','show14','hide14');" id="hide14" style="display: none;">Hide</a>
          <a style="" href="javascript:expand('14','show14','hide14');" id="show14">Show</a>)</span>
          <table id="14" style="display:none">
            <tr>
              <td>
                <input type="hidden" name="upcom_giginfo_link" value="0" />
                <input type="checkbox" value="1" name="upcom_giginfo_link" <?php if($this->config['upcom_giginfo_link']==1) echo "checked"; ?>>gigNotes/Info links to gigDetails Page
              </td>
            </tr>
          </table>
        </td>

<?php } ?>

        <td class="center">
          <?php echo (int) $item->id; ?>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <div>
    <input type="hidden" name="task" value="config.apply" />
    <input type="hidden" name="boxchecked" value="0" />
    <?php echo JHtml::_('form.token'); ?>
  </div>
<table>
 <tr>
 <td></td>
 <td><b>Other Upcoming Gigs Module Settings</b></td>
 </tr>

 <tr>
  <td><div align="right"><b>Item Delimiter</b></div></td>
  <td>
   <input type="text" name="upcom_delim" size="10" value="<?php echo $this->config['upcom_delim']; ?>">
  </td>
 </tr>

 <tr>
  <td><div align="right" valign="top"><b>Group by Date</b></div></td>
  <td>
   <input type="hidden" name="upcom_group_days" value="0" />
   <input type="checkbox" value="1" name="upcom_group_days" onchange="this.form.submit()" <?php if($this->config['upcom_group_days']==1) echo 'checked'; ?>>
  </td>
 </tr>

 <tr>
  <td><div align="right"><b># of <?php echo (($this->config['upcom_group_days']==1)?'days':'gigs'); ?> to show</b></div></td>
  <td>
   <input type="text" name="upcom_limit" size="4" value="<?php echo $this->config['upcom_limit']; ?>">
  </td>
 </tr>

 <tr>
  <td><div align="right" valighn="top"><b>Add Horizontal Rule</b></div></td>
  <td>
   <input type="hidden" name="upcom_hrule" value="0" />
   <input type="checkbox" value="1" name="upcom_hrule" <?php if($this->config['upcom_hrule']==1) echo 'checked'; ?>>
  </td>
 </tr>

 <tr>
  <td><div align="right"><b>Overlib Params<br />(<a target="_blank" href="http://www.bosrup.com/web/overlib/?Command_Reference">Command Reference</a>)</b></div></td>
  <td>
   <textarea type="textara" name="upcom_hover_params" rows="3" cols="80"><?php echo $this->config['upcom_hover_params']; ?></textarea>
  </td>
 </tr>

 <tr>
  <td valign="top"><div align="right"><b>Intro Text</b></div></td>
  <td>
   <textarea name="upcom_text" rows="4" cols="80"><?php echo $this->config['upcom_text']; ?></textarea>
  </td>
 </tr>

 <tr>
  <td valign="top"><div align="right"><b>CSS</b></div></td>
  <td>
   <textarea name="upcom_css" rows="8" cols="80"><?php echo $this->config['upcom_css']; ?></textarea>
  </td>
 </tr>
</table>
</form>

