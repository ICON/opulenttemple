<?php
/**
* @version $Id: mod_gigcal_minical.php 1124 2005-12-15 06:37:21Z gsbe $
* @package gigCalendar
* @copyright Copyright (C) 2005 nuthin' werked. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* This file is part of gigCalendar.
* gigCalendar is free software; you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation; either version 2 of the License, or
* (at your option) any later version.
*
* gigCalendar is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with gigCalendar; if not, write to the Free Software
* Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*
* See COPYRIGHT.php for copyright notices and details.
*/

defined('_JEXEC') or die('Restricted access');

$document = & JFactory::getDocument ();
$document->addScript(JURI::base().'/components/com_gigcal/overlib.js');

require_once("components/com_gigcal/views/subroutines.php");
//mosCommonHTML::loadOverlib();

//function get_accesslevelg() {
//global $my;
//$gid=$my->gid;
//return $gid;
//}

//$accesslevel =  get_accesslevelg();
//global $config['dbprefix'];

$config = get_object_vars(new JConfig());

$database_connect = mysql_connect($config['host'], $config['user'], $config['password']);
mysql_select_db($config['db'], $database_connect);

$now = time();

$sqlc = 'select * from '.$config['dbprefix'].'gigcal_config where active=1 LIMIT 1';
$gigconfig = mysql_fetch_assoc(mysql_query($sqlc));


$overlib_params = "";
if($gigconfig['minical_hover_params'] != "") $overlib_params = ", " . $gigconfig['minical_hover_params'];

$gigconfig['minical_dateformat'] = date_reformater($gigconfig['minical_dateformat']);
//$gigconfig['minical_dateformat2'] = date_reformater($gigconfig['minical_dateformat2']);
$gigconfig['minical_timeformat'] = time_reformater($gigconfig['minical_timeformat']);

?>
<div id="gigcal_minical">
<style type="text/css">
 <?php echo $gigconfig['minical_css']; ?>
</style>
<?php if($gigconfig['minical_text'] != "") { ?>
<span class="gigcal_minical_introtext">
<?php echo $gigconfig['minical_text']; ?><br />
</span>
<?php } ?>

<?php
   $month = date("n");
   $year = date("Y");
//   $accesslevel =  $my->gid;

   date_default_timezone_set($config['offset']);

   $thisMonth = mktime( 0, 0, 0, $month,    0,  $year );  // calculate first second of this and next month
   $nextMonth = mktime( 0, 0, 0, $month+1,  1,  $year );  // everything in between (inclusive / exclusive) is THIS month
   $sql = 'select * from '.$config['dbprefix'].'gigcal_gigs where published=1'
 //         .' and access <= '.$accesslevel
          .' and gigdate >= '.$thisMonth
          .' and gigdate < '.$nextMonth
          .' order by gigdate';
   $i = 0;    // counter
   $results;  // initialize array

   if (($get_results = mysql_query( $sql ) ) && ( mysql_num_rows( $get_results ) > 0)) {
      while ($result = mysql_fetch_assoc( $get_results)) {
          $results[$i] = $result;   //load array with current month's results
          $i++;
      }
   }

   $months = array(1 => $gigconfig['cal_january'],    2 => $gigconfig['cal_february'], 3 => $gigconfig['cal_march'],     4 => $gigconfig['cal_april'],
                   5 => $gigconfig['cal_may'],        6 => $gigconfig['cal_june'],     7 => $gigconfig['cal_july'],      8 => $gigconfig['cal_august'],
                   9 => $gigconfig['cal_september'], 10 => $gigconfig['cal_october'], 11 => $gigconfig['cal_november'], 12 => $gigconfig['cal_december']);

   $weekdays      = array("S", "M", "T", "W", "T", "F", "S");

   date_default_timezone_set($config['offset']);
   $first_dow     = $gigconfig['cal_first_day'] % 7;
   $first_day     = mktime(0, 0, 0, $month, 1, $year);
   $days_in_month = date('t', $first_day);
   $day_offset    = date('w', $first_day)-$first_dow;
?>


<table class="gigcal_minicaltable" cellspacing=0 cellpadding=0>
 <caption>
  <a href="<?php echo JRoute::_('index.php?option=com_gigcal&task=cal&month=$month&year=$year'); ?>"><?php echo $months[$month]." ".$year; ?></a>
 </caption>
 <thead><tr>
   <?php for ($i = 0; $i < 7; $i++) { $weekday = $weekdays[($i+$first_dow)%7]?>
     <td class="gigcal_minical_dayname"><?php echo $weekday; ?></td>
   <?php } ?>
 </tr></thead>
 <tbody>
 <tr>
<?php
   if ($day_offset > 0) {
       for ($i = 0; $i < $day_offset; $i++) { ?>
          <td <?php if(($day_offset == 0) || ($day_offset == 6)) { echo 'class="gigcal_minidaybox_emptyweekend"'; } else { ?> class="gigcal_minidaybox_empty" <?php } ?>>&nbsp;</td>
       <?php }
   }

   // Make some boxes, fill with data in $results array
   for ($day = 1; $day <= $days_in_month; $day++ ) {
      if( isset($results) ) {
         $printed_a_day = 0;

// This whole thing is ghetto and all this logic needs to be redone for 1.1
         foreach( $results as $result ) {

         if(($result["gigdate"] >= mktime(0, 0, 0, $month , $day, $year)) && ($result["gigdate"] < mktime(0, 0, 0, $month , $day + 1, $year))) 
         {
            $sqlv = 'select * from '.$config['dbprefix'].'gigcal_venues where id='.$result['venue_id'];
            $resultv = mysql_fetch_assoc(mysql_query($sqlv));

            $sqlb = 'select * from '.$config['dbprefix'].'gigcal_bands where id='.$result['band_id'];
            $resultb = mysql_fetch_assoc(mysql_query($sqlb));

/*
         // EDIT 01.27.06 bruce42 Select Multiple Bands
         // 'gigcal_band_id' numbers are stored in the 'gigcal_bands' table as a comma deliniated string.
         // Using explode(), we can put those id numbers into an array and form an SQL query that will 
         // search for each of those id numbers. 
	 $sqlb = 'select * from '.$config['dbprefix'].'gigcal_bands WHERE id IN ('.$result['band_id'].')';
	 $get_resultsb = mysql_query($sqlb);
	 $i = 0;    // counter
	 if ( ($get_resultsb = mysql_query( $sqlb ))  && (mysql_num_rows( $get_resultsb ) > 0 ) ) {
            while ( $resultsb = mysql_fetch_assoc( $get_resultsb ) ) {
               $resultb[$i] = $resultsb;   //load array with our possibly multiple bands
               $i++;
	    }
	}
	// multi-bands
	$band_name_list = implode(', ', $resultb[$j]['bandname']); 
*/
//------------------------------------
?>
<td class="gigcal_minidaybox_gig">
<?php
$printed_a_day++;


?><?php if($gigconfig['minical_hover']) {
 $minical_caption = ", CAPTION, '".$resultb['bandname']." @ ".$resultv['venuename']." "
			.addslashes(date($gigconfig['minical_timeformat'], $result["gigdate"]))."' ";
 $minical_hover =  "";
 if($gigconfig['minical_hover_bandname']) $minical_hover .= "<b>".$resultb['bandname']."</b><br />";
 if($gigconfig['minical_hover_venue']) $minical_hover .= $resultv["venuename"] . "<br />";
 if($gigconfig['minical_hover_cityst']) $minical_hover .= $resultv["city"] . ", " . $resultv['state'] . "<br />";
 if($gigconfig['minical_hover_date'])  $minical_hover .= htmlspecialchars(date($gigconfig['minical_dateformat'], $result["gigdate"]), ENT_QUOTES) . "<br />";
 if($gigconfig['minical_hover_time']) $minical_hover .= htmlspecialchars(date($gigconfig['minical_timeformat'], $result["gigdate"]), ENT_QUOTES) . "<br />";
 if(($gigconfig['minical_hover_covercharge']) && ($result["covercharge"] != ""))   $minical_hover .= $result["covercharge"] . "<br />";
 if($gigconfig['minical_hover_notes'])  $minical_hover .= str_replace("\r\n", "<br />", nl2br($result["info"]));
 ?>
 <span onmouseover="return overlib('<?php echo addslashes(htmlspecialchars($minical_hover)); ?>'<?php echo $minical_caption . $overlib_params; ?>);"  onmouseout="return nd();"><?php } ?>
 <?php if($gigconfig['minical_link']) { ?><a href="<?php echo JRoute::_('index.php?option=com_gigcal&task=details&day='.$day);?>"><?php } ?>
<?php echo $day;?><?php if($gigconfig['minical_link']) { ?></a><?php } ?>
 <?php if($gigconfig['minical_hover']) { ?></span><?php } ?>
<?php

//------------------------------
            } else { ?>       <?php }

       if($printed_a_day) break;   } if($printed_a_day == 0) {?><td <?php if(($day_offset == 0) || ($day_offset == 6)) { if (($day == date("j")) && ($month == date("n"))) { echo 'class="gigcal_minidaybox_current"'; } else { echo 'class="gigcal_minidaybox_weekend"'; }} else {
 if (($day == date("j")) && ($month == date("n"))) { echo 'class="gigcal_minidaybox_current"'; }
}?>><?php echo $day;?><?php }
         } else { ?>      <td <?php if(($day_offset == 0) || ($day_offset == 6)) { if (($day == date("j")) && ($month == date("n"))) { echo 'class="gigcal_minidaybox_current"'; } else { echo 'class="gigcal_minidaybox_weekend"'; }} else {
 if (($day == date("j")) && ($month == date("n"))) { echo 'class="gigcal_minidaybox_current"'; }
}?> ><?php echo $day;?><?php }
	?></td>
<?php
       $day_offset++;
       // if end of week, close the current week's cell, and start a new row if not end of month
       if ($day_offset == 7) {
           $day_offset = 0; ?>
           <?php //</td> ?>
           <?php if( $day != $days_in_month ) { ?>
               </tr><tr>
           <?php }
       }
   }


   // fill in the rest w/ empty boxes
   if ($day_offset > 0 ) {
       for ($i = $day_offset; $i < 7; $i++) { ?>

<td <?php if(($day_offset == 0) || ($day_offset == 6)) { echo 'class="gigcal_minidaybox_emptyweekend"'; } else { ?> class="gigcal_minidaybox_empty" <?php } ?>>&nbsp;</td>

<?php
       }
   }?>
</tr>
</tbody>
</table>

<?php if($gigconfig['minical_gigcal_link_text'] != "") { 
  $nexttask=$gigconfig['minical_gigcal_link_to'];
  if($nexttask=='calendar')
    $nexttask = 'cal';
?>
<span class="gigcal_minilinktext">
<a href="<?php echo JRoute::_('index.php?option=com_gigcal&amp;task='.$nexttask);?>">
<?php echo $gigconfig['minical_gigcal_link_text']; ?></a>
</span>
</div>
<?php } ?>


