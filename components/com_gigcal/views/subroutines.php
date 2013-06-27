<?php
/**
* @version $Id: subroutines.php 1124 2005-12-15 06:37:21Z gsbe $
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


function getAccessLevel() {
  global $my;
  $gid=$my->gid;
  return $gid;
}

function dbLookupDetails($what, $id)
{
  $db =& JFactory::GetDBO();
  $sql='SELECT * FROM #__gigcal_'.$what.'s WHERE id='.$id;
//error_log($sql);
  $db->setQuery($sql);
  return $db->loadAssoc();
}

function dbLookupFieldnames($display)
{
  $db =& JFactory::GetDBO();
  $sql='SELECT fieldname FROM #__gigcal_'.$display.'_fields WHERE published=1 ORDER BY ordering';
//error_log($sql);
  $db->setQuery($sql);
  return $db->loadResultArray();
}

function maplink($street, $street2, $city, $state, $zip, $country, $type="r")
{
  //Initialize local variables.
  $Mlink = "";         //Return value

  //Strip periods, commas, leading, and tailing spaces from street, city,
  $street = trim($street);
  $street = str_replace('.','',$street);
  $street = str_replace(',','',$street);
  $street = str_replace(' ','+',$street);
  $street2 = trim($street2);
  $street2 = str_replace('.','',$street2);
  $street2 = str_replace(',','',$street2);
  $street2 = str_replace(' ','+',$street2);
  $city = trim($city);
  $city = str_replace('.','',$city);
  $city = str_replace(',','',$city);
  $city = str_replace(' ','+',$city);
  $zip = trim($zip);
  $country = trim($country);
  $state = trim($state);

  $Mlink = "http://maps.google.com/maps?q=" . $street . " " . $street2 . " " . $city . " " . $state . " " . $zip . " " . $country . "&hl=en";
  $Mlink = str_replace(" ", "+", $Mlink);
  $Mlink = str_replace("++", "+", $Mlink);

  global $config;
  if($type == "r") $Mlink = "<a target=\"_blank\" href=\"$Mlink\">";
  if($type == "l") $Mlink = "<a alt=\"$config[list_maplink_name]\" title=\"$config[list_maplink_name]\" target=\"_blank\" href=\"$Mlink\">";
  if($type == "a") $Mlink = "<a alt=\"$config[alist_maplink_name]\" title=\"$config[alist_maplink_name]\" target=\"_blank\" href=\"$Mlink\">";

  return $Mlink;
}

function date_reformater ($value) {
  $value = str_replace("%weekday", "l", $value);
  $value = str_replace("%wkdy", "D", $value);
  $value = str_replace("%month", "F", $value);
  $value = str_replace("%2nmonth", "m", $value);
  $value = str_replace("%day", "j", $value);
  $value = str_replace("%ordday", "jS", $value);
  $value = str_replace("%2year", "y", $value);
  $value = str_replace("%year", "Y", $value);
  $value = str_replace("%nmonth", "n", $value);
  $value = str_replace("%mon", "M", $value);

/*
%weekday  	Day of the week from language file			Wednesday
%wkdy. 		Abbreviated weekday from language file			Wed.
%wkdy 		Abbreviated weekday from language file, no period 	Wed
%hour 		Hour of the day, in 12-hour (AM/PM) format 		10
%24hour		Hour of the day, in 24-hour format 			22
%minute 	The number of minutes 					44
%ampm 		"am" or "pm" 						pm
%AMPM 		"AM" or "PM" 						PM
%month 		Name of the month from language file			December
%mon. 		Abbreviated month from language file 			Dec.
%mon_ 		Abbreviated month from language file, no period 	Dec
%nmonth 	Number of the month					1
%2nmonth 	Number of the month in 2-digit format			01
%day		Number of the day 					19
%ordday		Ordinal Number of the day (1st, 2st, 3st, 4st...) 	19th
%year 		Number of the year in 4-digit format 			2001
%2year 		Number of the year in 2-digit format 			01
*/
  return $value;
}

function time_reformater ($value) {
  $value = str_replace("%hour", "g", $value);
  $value = str_replace("%24hour", "G", $value);
  $value = str_replace("%minute", "i", $value);
  $value = str_replace("%ampm", "a", $value);
  $value = str_replace("%AMPM", "A", $value);

  return $value;
}

//gigFilterBuilder
// Builds the content of the gig filter (used in alist.php and cal.php)
function gigFilterBuilder ($filter_display_len, $task, $limit, $keywordfilter, $bandfilter, $venuefilter)
{
  global $database;
  
  if ($filter_display_len > 0) {
    // count some rows
    $sqlb = "SELECT band_id, bandname FROM #__gigcal_bands WHERE published=1";
    $database->setQuery($sqlb);
    $resultsb=$database->loadAssocList();

    $sqlv = "SELECT venue_id, venuename FROM #__gigcal_venues WHERE published=1";
    $database->setQuery($sqlv);
    $resultsv=$database->loadAssocList();

    $itemid=$_REQUEST['Itemid'];
    $itemidfix="&Itemid=".$itemid;
    
    $link = "index.php?option=com_gigcal&amp;task=".$task.$itemidfix;
    if ($limit > 0)
      $link .= '&amp;limit='.$limit."&amp;limitstart=0";

    $output = "\n".'<form action="'.$link.'" method="post" name="filterForm" id="filterForm" class="filterForm">'."\n";
    $output .= '<table>'."\n".'  <tbody>'."\n".'    <tr>'."\n".'      <td>Filter: </td>'."\n";
    $output .= '      <td><input type="text" name="keywordfilter" value="'.$keywordfilter.'" class="inputbox" onChange="submit()" /></td>'."\n";

    if (count($resultsb) > 1) {
      $output .= '      <td><select name="bandfilter" size="1" onChange="submit()">'."\n";
      $output .= '      <option value="0">- Select Band -</option>'."\n";
      for($i=0; $i<count($resultsb); $i++) {
        $output .= '      <option value="'.$resultsb[$i]['band_id'].'"';
        if($resultsb[$i]['band_id'] == $bandfilter)
          $output .= ' "selected" ';
        $output .= '>'.substr($resultsb[$i]['bandname'], 0, $filter_display_len).'</option>'."\n";
      }
      $output .= '</select></td>'."\n";
    }

    if (count($resultsv) > 1) {
      $output .= '      <td><select name="venuefilter" size="1" onChange="submit()">'."\n";
      $output .= '      <option value="0">- Select Venue -</option>'."\n";
      for($i=0; $i<count($resultsv); $i++) {
        $output .= '      <option value="'.$resultsv[$i]['venue_id'].'"';
        if($resultsv[$i]['venue_id'] == $venuefilter)
          $output .= ' "selected" ';
        $output .= '>'.substr($resultsv[$i]['venuename'], 0, $filter_display_len).'</option>'."\n";
      }
      $output .= '</select></td>'."\n";
   }

    $output .= '    </tr>'."\n".'  </tbody>'."\n".'</table>'."\n".'</form>';
    echo $output;
  }
}

// gigContentBuilder
// Builds the content with overlib captions and text
function gigContentBuilder ($config, $display, $gig) 
{
  $fieldnames = dbLookupFieldnames($display);

  $itemid=$_REQUEST['Itemid'];
  $itemidfix="&Itemid=".$itemid;
  $giglink =   JRoute::_("index.php?option=com_gigcal&task=details&id=".$gig['id'].$itemidfix);
  $venuelink = JRoute::_("index.php?option=com_gigcal&task=details&venue_id=".$gig['venue_id'].$itemidfix);
  $bandlink =  JRoute::_("index.php?option=com_gigcal&task=details&band_id=".$gig['band_id'].$itemidfix);

  $resultv=dbLookupDetails("venue", $gig['venue_id']);
  $resultb=dbLookupDetails("band", $gig['band_id']);

  $overlib_params = "";
  if($config[$display.'_hover_params'] != "") 
    $overlib_params = ", " . $config[$display.'_hover_params'];

  $config[$display.'_dateformat'] = date_reformater($config[$display.'_dateformat']);
  $config[$display.'_dateformat2'] = date_reformater($config[$display.'_dateformat2']);
  $config[$display.'_timeformat'] = time_reformater($config[$display.'_timeformat']);

  switch ($display) {
    case 'list':
    case 'alist':
      $tag1open = "<tr>";
      $tag2open = "<td>";
      $tag1close = "</tr>";
      $tag2close = "</td>";
    break;

    case 'cal':
      $tag1open = "<dl>";
      $tag2open = "<dd>";
      $tag1close = "</dl>";
      $tag2close = "</dd>";
    break;

    case 'upcom':
      $tag1open = "<div class='mod_gigcal_upcom'>";
      $tag2open = " ";
      $tag1close = "</div>";
      $tag2close = " ";
    break;
  }

  // To eliminate huge tracts of whitespace, we'll build a huge output string as we loop,
  // then dump the whole thing to the screen in one go
  $output = "    ".$tag1open."\n"; // Start building the output string
  $itemcount = 0;
  foreach($fieldnames as $item) {
    $output .= "      ".$tag2open;

    // Gig Notes / Info
    if($item == "gigNotes/Info") {
      if($itemcount) 
        $output .= $config[$display.'_delim']; 

      if($config[$display.'_giginfo_link'])
        $output.= '<a href="'.$giglink.'">'; 

      $output .= $gig["info"];

      if($config[$display.'_giginfo_link'])
        $output .= '</a>'; 
    }

    // Title
    if ($item == "gigTitle") {
      if($itemcount) 
        $output .= $config[$display.'_delim'];
      if($config[$display.'_gigtitle_hover']) {
        $gigtitle_caption = ", CAPTION, '".$gig["gigtitle"]."' ";
        $gigtitle_hover =  "";
        if($config[$display.'_gigtitle_hover_bandname']) $gigtitle_hover .= $resultb["bandname"] . "<br />";
        if($config[$display.'_gigtitle_hover_venue']) $gigtitle_hover .= $resultv["venuename"] . "<br />";
        if($config[$display.'_gigtitle_hover_date'])  $gigtitle_hover .= htmlspecialchars(date($config[$display.'_dateformat'], $gig["gigdate"]), ENT_QUOTES) . "<br />";
        if($config[$display.'_gigtitle_hover_time']) $gigtitle_hover .= htmlspecialchars(date($config[$display.'_timeformat'], $gig["gigdate"]), ENT_QUOTES) . "<br />";
        $output .= '<span onmouseover="return overlib(\''.htmlspecialchars(addslashes($gigtitle_hover)).'\''.$gigtitle_caption . $overlib_params.');"  onmouseout="return nd();">';
      }
      if($config[$display.'_gigtitle_link']) { $output .= '<a href="'.$giglink.'">'; }
      $output .= htmlspecialchars($gig["gigtitle"]);
      if($config[$display.'_gigtitle_link']) { $output .= '</a>'; }
      if($config[$display.'_gigtitle_hover']) { $output .= '</span>'; }
    }

    // Cover Charge
    if($item == "gigCover Charge") {
      if ($gig['covercharge'] != "") {
        if($itemcount) { 
          $output .= $config[$display.'_delim'].$config[$display.'_covercharge_name']." ".$gig['covercharge']; 
        }
      }
    }

    // Buy Tickets
    if($item == "Link to online Ticket Sales") {
      if(($gig["saleslink"] != "") && ($gig["saleslink"] != "http://")) {
        if($itemcount) 
          $output .= $config[$display.'_delim'];
	$output .= '<a alt="'.$config[$display.'_ticketlink_name'].' " target="_blank" title="'.$config[$display.'_ticketlink_name'].'"  href="'.$gig["saleslink"].'">'.$config[$display.'_ticketlink_name'].'</a>';
      }
    }

    // Map Link
    if($item == "Link to Map") {
      if($itemcount) 
        $output .= $config[$display.'_delim'];
      $output .= maplink($resultv['address1'], $resultv['address2'], $resultv['city'], $resultv['state'], $resultv['zip'], $resultv['country'], "l").$config['list_maplink_name'].'</a>';
    }

    // iCal Link
    if($item == "iCal Link") {
      if($itemcount) 
        $output .= $config[$display.'_delim'];
      $output .= '<a alt="'.$config['list_ical_link_name'].'" title="'.$config['list_ical_link_name'].'" href="components/com_gigcal/exporta.php?id='.$gig['id'].'&ext=ics">'.$config['list_ical_link_name'].'</a>';
    }

    // vCal Link
    if($item == "vCal Link") {
      if($itemcount) 
        $output .= $config[$display.'_delim'];
      $output .= '<a alt="'.$config['list_vcal_link_name'].'" title="'.$config['list_vcal_link_name'].'" href="components/com_gigcal/exporta.php?id='.$gig['id'].'&ext=vcs">'.$config['list_vcal_link_name'].'</a>';
    }

    // Band
    if($item == "gigBand") {
      if($itemcount) 
        $output .= $config[$display.'_delim'];
      if($config[$display.'_gigband_popup']) {
        $gigband_caption = "";
        if($config[$display.'_gigband_link'] == "nothing") $gigband_caption = $resultb['bandname'];
        if($config[$display.'_gigband_link'] == "banddetails") $gigband_caption = "Click to learn more about " . $resultb['bandname'];
        if($config[$display.'_gigband_link'] == "gigdetails") $gigband_caption = $resultb['bandname'] . " @ " . $resultv['venuename'] . " " . htmlspecialchars(date($config[$display.'_timeformat'], $gig["gigdate"]));
        if(($config[$display.'_gigband_link'] == "bandwebsite") && ($resultb['website'] != "")) $gigband_caption = "Click to visit ".$resultb['bandname']."'s website";
        $gigband_caption = ", CAPTION, '" . addslashes($gigband_caption) . "' ";
        $nl2br_string = $resultb['bandname']." <br /> ".$resultb['city'].", ".$resultb['state']." <br /> ".$resultb['notes'];
        $output .= '<span onmouseover="return overlib(\''.htmlspecialchars(addslashes(str_replace("\r\n", "<br />", nl2br($nl2br_string)))).'\''.$gigband_caption.$overlib_params.');"  onmouseout="return nd();">';
      }
      if($config[$display.'_gigband_link'] != "nothing") {
        $showlink = 0;
        if(($resultb['website'] != "") && ($resultb['website'] != "http://")) { $showlink = 1; }
        if(($config[$display.'_gigband_link'] == "bandwebsite") && ($showlink)) { $output .= "<a target=\"_blank\" href=\"".$resultb['website']."\">"; }
        if($config[$display.'_gigband_link'] == "banddetails") { $output.= '<a href="'.$bandlink.'">'; }
        if($config[$display.'_gigband_link'] == "gigdetails") { $output .= '<a href="'.$giglink.'">'; }
      }
      $output .= $resultb["bandname"];
      if(($config[$display.'_gigband_link'] != "nothing") && ($config[$display.'_gigband_link'] != "bandwebsite")) 
        $output .= '</a>';
      if(($showlink) && ($config[$display.'_gigband_link'] == "bandwebsite")) 
        $output .= '</a>';
      if($config[$display.'_gigband_popup']) 
        $output .= '</span>';
    }

    // Venue
    if($item == "gigVenue") {
      if($itemcount) 
        $output .= $config[$display.'_delim'];
      if($config[$display.'_gigvenue_popup']) {
        $gigvenue_caption = "";
        if($config[$display.'_gigvenue_link'] == "nothing") $gigvenue_caption = $resultv['venuename'];
        if($config[$display.'_gigvenue_link'] == "gigdetails") { $gigvenue_caption = $resultb['bandname']." @ ".$resultv['venuename'] . " " . htmlspecialchars(date($config['cal_timeformat'], $gig["gigdate"])); }
        if($config[$display.'_gigvenue_link'] == "venuedetails") $gigvenue_caption = "Click to learn more about " . $resultv['venuename'];
        if($config[$display.'_gigvenue_link'] == "map") $gigvenue_caption = "Click for directions to {$resultv['venuename']}";
        if(($config[$display.'_gigvenue_link'] == "venuewebsite") && ($resultv['website'] != "")) $gigvenue_caption = "Click to visit {$resultv['venuename']}'s website";
        $gigvenue_caption = ", CAPTION, '" . addslashes($gigvenue_caption) . "' ";
        $nl2br_string = $resultv['venuename'].'<br />'.$resultv['city'].','.$resultv['state'].'<br />'.$resultv['phone'].'<br />'.$resultv['info'];
        $output .= '<span onmouseover="return overlib(\''.htmlspecialchars(addslashes(str_replace("\r\n", "<br />", nl2br($nl2br_string)))).'\''.$gigvenue_caption.$overlib_params.');"  onmouseout="return nd();">';
      }
      if($config[$display.'_gigvenue_link'] != "nothing") {
        $showlink = 0;
        if(($resultv['website'] != "") && ($resultv['website'] != "http://")) $showlink = 1;
        if(($config[$display.'_gigvenue_link'] == "venuewebsite") && ($showlink)) { $output .= "<a target=\"_blank\" href=\"" . $resultv['website'] . "\">"; }
        if($config[$display.'_gigvenue_link'] == "map") $output .= maplink($resultv['address1'], $resultv['address2'], $resultv['city'], $resultv['state'], $resultv['zip'], $resultv['country'], "l");
        if($config[$display.'_gigvenue_link'] == "venuedetails") { $output .= '<a href="'.$venuelink.'">'; }
      }
      $output .= $resultv["venuename"];
      if(($config[$display.'_gigvenue_link'] != "nothing") && ($config[$display.'_gigvenue_link'] != "venuewebsite"))
        $output .= '</a>';
      if(($showlink) && ($config[$display.'_gigvenue_link'] == "venuewebsite"))
        $output .= '</a>';
      if($config[$display.'_gigvenue_popup'])
        $output .= '</span>';
    }

    // Location
    if($item == "Location") {
      if($itemcount) 
        $output .= $config[$display.'_delim'];
      if($config[$display.'_location_popup']) {
        $giglocation_caption = "";
        if($config[$display.'_location_link'] == "nothing") $giglocation_caption = $resultv['venuename'];
        if($config[$display.'_location_link'] == "gigdetails") $giglocation_caption = $resultv['venuename'];
        if($config[$display.'_location_link'] == "venuedetails") $giglocation_caption = "Click to learn more about " . $resultv['venuename'];
        if($config[$display.'_location_link'] == "map") $giglocation_caption = "Click for directions to {$resultv['venuename']}";
        if(($config[$display.'_location_link'] == "venuewebsite") && ($resultv['website'] != "")) $giglocation_caption = "Click to visit {$resultv['venuename']}'s website";
        $giglocation_caption = ", CAPTION, '" . addslashes($giglocation_caption) . "' ";
	    $nl2br_string = $resultv['venuename'].'<br />'.$resultv['city'].', '.$resultv['state'].'<br />'.$resultv['phone'].'<br />'.$resultv['info'];
        $output .= '<span onmouseover="return overlib(\''.htmlspecialchars(str_replace("\r\n", "<br />", nl2br($nl2br_string))).'\''.$giglocation_caption.$overlib_params.');"  onmouseout="return nd();">';
      }
      if($config['list_location_link'] != "nothing") {
        $showlink = 0;
        if(($resultv['website'] != "") && ($resultv['website'] != "http://")) $showlink = 1;
        if($config[$display.'_location_link'] == "venuewebsite") { if ($showlink) $output .= "<a target=\"_blank\" href=\"" . $resultv['website'] . "\">"; }
        if($config[$display.'_location_link'] == "map") $output .= maplink($resultv['address1'], $resultv['address2'], $resultv['city'], $resultv['state'], $resultv['zip'], $resultv['country'], "l");
        if($config[$display.'_location_link'] == "venuedetails") { $output .= '<a href="'.$venuelink.'">'; }
        if($config[$display.'_location_link'] == "gigdetails") { $output .= '<a href="'.JRoute::_("index.php?option=com_gigcal&task=details&id=".$gig['id'].$itemidfix).'">'; }
      }
      $output .= $resultv["city"].", ".$resultv['state'];
      if(($config[$display.'_location_link'] != "nothing") && ($config[$display.'_location_link'] != "venuewebsite")) { $output .= '</a>'; }
      if(($showlink) && ($config[$display.'_location_link'] == "venuewebsite")) { $output .= '</a>'; }
      if($config[$display.'_location_popup']) { $output .= '</span>'; }
    }

    // Country
    if($item == "Country") {
      if($itemcount) 
        $output .= $config[$display.'_delim'];
      if($config[$display.'_country_popup']) {
        $gigcountry_caption = "";
        if($config[$display.'_country_link'] == "nothing") $gigcountry_caption = $resultv['venuename'];
        if($config[$display.'_country_link'] == "gigdetails") $gigcountry_caption = $resultv['venuename'];
        if($config[$display.'_country_link'] == "venuedetails") $gigcountry_caption = "Click to learn more about " . $resultv['venuename'];
        if($config[$display.'_country_link'] == "map") $gigcountry_caption = "Click for directions to {$resultv['venuename']}";
        if(($config[$display.'_country_link'] == "venuewebsite") && ($resultv['website'] != "")) $gigcountry_caption = "Click to visit {$resultv['venuename']}'s website";
        $gigcountry_caption = ", CAPTION, '" . addslashes($gigcountry_caption) . "' ";
    	$nl2br_string = $resultv['venuename'].'<br />'.$resultv['city'].', '.$resultv['state'].'<br />'.$resultv['phone'].'<br />'.$resultv['info'];
        $output .= '<span onmouseover="return overlib(\''.htmlspecialchars(addslashes(str_replace("\r\n", "<br />", nl2br($nl2br_string)))).'\''.$gigcountry_caption . $overlib_params.');"  onmouseout="return nd();">';
      }
      if($config[$display.'_country_link'] != "nothing") {
        $showlink = 0;
        if(($resultv['website'] != "") && ($resultv['website'] != "http://")) $showlink = 1;
        if(($config[$display.'_country_link'] == "venuewebsite") && ($showlink)) { $output .= "<a target=\"_blank\" href=\"" . $resultv['website'] . "\">"; }
        if($config[$display.'_country_link'] == "map") echo maplink($resultv['address1'], $resultv['address2'], $resultv['city'], $resultv['state'], $resultv['zip'], $resultv['country'], "l");
        if($config[$display.'_country_link'] == "venuedetails") { $output .= '<a href="'.$venuelink.'">'; }
        if($config[$display.'_country_link'] == "gigdetails") { $output .= '<a href="'.JRoute::_("index.php?option=com_gigcal&task=details&id=".$gig['id'].$itemidfix).'">'; }
      }
      $output .= $resultv["country"];
      if(($config[$display.'_country_link'] != "nothing") && ($config[$display.'_country_link'] != "venuewebsite")) { $output .= '</a>'; }
      if(($showlink) && ($config[$display.'_country_link'] == "venuewebsite")) { $output .= '</a>'; }
      if($config[$display.'_country_popup']) { $output .= '</span>'; }
    }

    // Date
    if($item == "gigDate") {
      if($itemcount) 
        $output .= $config[$display.'_delim'];
      if($config[$display.'_gigdate_hover']) {
        $gigdate_caption = ", CAPTION, '" . addslashes(date($config[$display.'_dateformat'], $gig["gigdate"])) . "' ";
        $gigdate_hover =  "";
        if(($config[$display.'_gigdate_hover_gigtitle']) && ($gig["gigtitle"] != "")) $gigdate_hover .= $gig["gigtitle"] . "<br />";
        if($config[$display.'_gigdate_hover_bandname']) $gigdate_hover .= $resultb["bandname"] . "<br />";
        if($config[$display.'_gigdate_hover_venue']) $gigdate_hover .= $resultv["venuename"] . "<br />";
        if($config[$display.'_gigdate_hover_cityst']) $gigdate_hover .= $resultv["city"] . ", " . $resultv['state'] . "<br />";
        if($config[$display.'_gigdate_hover_date'])  $gigdate_hover .= htmlspecialchars(date($config[$display.'_dateformat'], $gig["gigdate"]), ENT_QUOTES) . "<br />";
        if($config[$display.'_gigdate_hover_time']) $gigdate_hover .= htmlspecialchars(date($config[$display.'_timeformat'], $gig["gigdate"]), ENT_QUOTES) . "<br />";
        if(($config[$display.'_gigdate_hover_covercharge']) && ($gig["covercharge"] != ""))   $gigdate_hover .= $gig["covercharge"] . "<br />";
        if($config[$display.'_gigdate_hover_notes'])  $gigdate_hover .= str_replace("\r\n", "<br />", nl2br($gig["info"]));
        $output .= '<span onmouseover="return overlib(\''.htmlspecialchars(addslashes($gigdate_hover)).'\''.$gigdate_caption . $overlib_params.');"  onmouseout="return nd();">';
      }
      if($config[$display.'_gigdate_link']) { $output .= '<a href="'.$giglink.'">'; }
      $output .= htmlspecialchars(date($config[$display.'_dateformat'], $gig["gigdate"]));
      if($config[$display.'_gigdate_link']) { $output .= '</a>'; }
      if($config[$display.'_gigdate_hover']) { $output .= '</span>'; }
    }

    // Date2
    if($item == "gigDate2") {
      if($itemcount) 
        $output .= $config[$display.'_delim'];
      if($config[$display.'_gigdate2_hover']) {
        $gigdate2_caption = ", CAPTION, '" . addslashes(date($config[$display.'_dateformat'], $gig["gigdate"])) . "' ";
        $gigdate2_hover =  "";
        if(($config[$display.'_gigdate2_hover_gigtitle']) && ($gig["gigtitle"] != ""))   $gigdate2_hover .= $gig["gigtitle"] . "<br />";
        if($config[$display.'_gigdate2_hover_bandname']) $gigdate2_hover .= $resultb["bandname"] . "<br />";
        if($config[$display.'_gigdate2_hover_venue']) $gigdate2_hover .= $resultv["venuename"] . "<br />";
        if($config[$display.'_gigdate2_hover_cityst']) $gigdate2_hover .= $resultv["city"] . ", " . $resultv['state'] . "<br />";
        if($config[$display.'_gigdate2_hover_date'])  $gigdate2_hover .= htmlspecialchars(date($config[$display.'_dateformat2'], $gig["gigdate"]), ENT_QUOTES) . "<br />";
        if($config[$display.'_gigdate2_hover_time']) $gigdate2_hover .= htmlspecialchars(date($config[$display.'_timeformat'], $gig["gigdate"]), ENT_QUOTES) . "<br />";
        if(($config[$display.'_gigdate2_hover_covercharge']) && ($gig["covercharge"] != "")) $gigdate2_hover .= $gig["covercharge"] . "<br />";
        if($config[$display.'_gigdate2_hover_notes'])  $gigdate2_hover .= str_replace("\r\n", "<br />", nl2br($gig["info"]));
        $output .= '<span onmouseover="return overlib(\''.htmlspecialchars(addslashes($gigdate2_hover)).'\''.$gigdate_caption . $overlib_params.');"  onmouseout="return nd();">';
      }
      if($config[$display.'_gigdate2_link']) { $output .= '<a href="'.$giglink.'">'; }
      $output .= htmlspecialchars(date($config[$display.'_dateformat2'], $gig["gigdate"]));
      if($config[$display.'_gigdate2_link']) { $output .= '</a>'; }
      if($config[$display.'_gigdate2_hover']) { $output .= '</span>'; }
    }

    // Time
    if($item == "gigTime") {
      if($itemcount) 
        $output .= $config[$display.'_delim'];
      if($config[$display.'_gigtime_link']) 
        $output .= '<a href="'.$giglink.'">';
      $output .= htmlspecialchars(date($config[$display.'_timeformat'], $gig["gigdate"]));
      if($config[$display.'_gigtime_link']) 
        $output .= '</a>';
    }

    $output .= $tag2close."\n";
    $itemcount++;
  }
  $output .= "    ".$tag1close."\n";
  echo $output;
}
// END gigContentBuilder()


// gigsTableBuilder()
// Builds the table header, footer, and col headings for list.php and alist.php
// 02.16.06 bruce42
// The 'gigs' and 'archive' lists use the same table construction.
// Gigs and Archive lists will now share the same table header text; why does alist
// have seperate table header text fields?
function gigsTableBuilder($config, $display) {
  $items = dbLookupFieldnames($display);
//error_log('fieldnames('.$display.')='.print_r($items,true));
//return;

  $output = '<style type="text/css">'.$config[$display.'_css'].'</style>'."\n";
  if($config[$display.'_text'] != "")
    $output .= '<span class="gigcal_'.$display.'_introtext">'.$config[$display.'_text'].'<br /></span>'."\n";
  $output .= '<table class="gigcal_'.$display.'_table">'."\n";
  $output .= '  <colgroup span="'.count($items).'">'."\n";
  foreach($items as $item) {
    if($item == "gigDate") { $output .= '    <col class="gigcal_'.$display.'_date" />'."\n"; }
    if($item == "gigDate2") { $output .= '    <col class="gigcal_'.$display.'_date2" />'."\n"; }
    if($item == "gigTitle") { $output .= '    <col class="gigcal_'.$display.'_title" />'."\n"; }
    if($item == "country") { $output .= '    <col class="gigcal_'.$display.'_country" />'."\n"; }
    if($item == "state") { $output .= '    <col class="gigcal_'.$display.'_state" />'."\n"; }
    if($item == "city") { $output .= '    <col class="gigcal_'.$display.'_city" />'."\n"; }
    if($item == "gigVenue") { $output .= '    <col class="gigcal_'.$display.'_venue" />'."\n"; }
    if($item == "Link to online Ticket Sales") { $output .= '    <col class="gigcal_'.$display.'_ticketlink" />'."\n"; }
    if($item == "Link to Map") { $output .= '    <col class="gigcal_'.$display.'_map" />'."\n"; }
    if($item == "gigCover Charge") { $output .= '    <col class="gigcal_'.$display.'_covercharge" />'."\n"; }
    if($item == "gigTime") { $output .= '    <col class="gigcal_'.$display.'_time" />'."\n"; }
    if($item == "download") { $output .= '    <col class="gigcal_'.$display.'_download" />'."\n"; }
    if($item == "gigNotes/Info") { $output .= '    <col class="gigcal_'.$display.'_notes" />'."\n"; }
    if($item == "gigBand") { $output .= '    <col class="gigcal_band" />'."\n"; }
    if($item == "Location") { $output .= '    <col class="gigcal_location" />'."\n"; }
    if($item == "iCal Link") { $output .= '    <col class="gigcal_ical" />'."\n"; }
    if($item == "vCal Link") { $output .= '    <col class="gigcal_vcal" />'."\n"; }
  }
  $output .= '  </colgroup>'."\n".'  <thead>'."\n".'    <tr>'."\n";


  foreach($items as $item) {
    if($item == "gigNotes/Info") { $output .= '      <th scope="col">'.$config['gignotes_header'].'</th>'."\n"; }
    elseif($item == "gigCover Charge") { $output .= '      <th scope="col">'.$config['covercharge_header'].'</th>'."\n"; }
    elseif($item == "Link to online Ticket Sales") { $output .= '      <th scope="col">'.$config['ticket_header'].'</th>'."\n"; }
    elseif($item == "Link to Map") { $output .= '      <th scope="col">'.$config['map_header'].'</th>'."\n"; }
    elseif($item == "gigTitle") { $output .= '      <th scope="col">'.$config['gigtitle_header'].'</th>'."\n"; }
    elseif($item == "iCal Link") { 
      $output .= '      <th scope="col">';
      if ($config['export_all_cals']==1)
        $output .= '<a alt="Export ALL '.$config['ical_header'].'" title="'.$config['ical_header'].'" href="components/com_gigcal/exporta.php?ext=ics&display='.$display.'">'.$config['ical_header'].'</a>';
      else
        $output .= $config['ical_header'];
      $output .= '</th>'."\n"; 
    }
    elseif($item == "vCal Link") { 
      $output .= '      <th scope="col">';
      if ($config['export_all_cals']==1)
        $output .= '<a alt="Export ALL '.$config['vcal_header'].'" title="'.$config['vcal_header'].'" href="components/com_gigcal/exporta.php?ext=vcs&display='.$display.'">'.$config['vcal_header'].'</a>';
      else
        $output .= $config['vcal_header'];
      $output .= '</th>'."\n"; 
    }
    else {
      $item_header = $item . "_header";
      $output .= '      <th scope="col">'.$config[$item_header].'</th>'."\n";
    }
  }
  $output .= '    </tr>'."\n".'  </thead>'."\n".'  <tfoot>'."\n".'    <tr>'."\n";


  foreach($items as $item) {
    if($item == "gigNotes/Info") { $output .= '      <th scope="col">'.$config['gignotes_header'].'</th>'."\n"; }
    elseif($item == "gigCover Charge") { $output .= '      <th scope="col">'.$config['covercharge_header'].'</th>'."\n"; }
    elseif($item == "Link to online Ticket Sales") { $output .= '      <th scope="col">'.$config['ticket_header'].'</th>'."\n"; }
    elseif($item == "Link to Map") { $output .= '      <th scope="col">'.$config['map_header'].'</th>'."\n"; }
    elseif($item == "iCal Link") { $output .= '      <th scope="col">'.$config['ical_header'].'</th>'."\n"; }
    elseif($item == "vCal Link") { $output .= '      <th scope="col">'.$config['vcal_header'].'</th>'."\n"; }
    elseif($item == "gigTitle") { $output .= '      <th scope="col">'.$config['gigtitle_header'].'</th>'."\n"; }
    else {
      $item_header = $item . "_header";
      $output .= '      <th scope="col">'.$config[$item_header].'</th>'."\n";
    }
  }
  $output .= '    </tr>'."\n".'  </tfoot>'."\n".'  <tbody>'."\n";
  echo $output;
} // END gigsTableBuilder()


// detailsBuilder()
// Builds and dumps details for all details pages and bandslist and venueslist
//
function detailsBuilder($config, $display, $item) {
//error_log('display=['.$display.']');
  $contents = "";
  switch ($display) {
    case 'band':
      $contents = $config['details_band'];
      $contents = str_replace("<\$bandname\$>", $item['bandname'], $contents);
      $contents = str_replace("<\$website\$>", $item['website'], $contents);
      $contents = str_replace("<\$contactname\$>", $item['contactname'], $contents);
      $contents = str_replace("<\$contactemail\$>", $item['contactemail'], $contents);
      $contents = str_replace("<\$contactphone\$>", $item['contactphone'], $contents);
      $contents = str_replace("<\$city\$>", $item['city'], $contents);
      $contents = str_replace("<\$state\$>", $item['state'], $contents);
      $contents = str_replace("<\$notes\$>", $item['notes'], $contents);
      echo $contents;
    break;

    case 'gig':
//error_log('detailBuilder(gig)='.print_r($item,true));
      $band = $item['band'];
      $venue = $item['venue'];
      $contents = $config['details_gig'];
      $contents = str_replace("<\$gigdate\$>", date(date_reformater($config['details_dateformat']), $item['gigdate']), $contents);
      $contents = str_replace("<\$gigtime\$>", date(time_reformater($config['details_timeformat']), $item['gigdate']), $contents);
      $contents = str_replace("<\$covercharge\$>", $item['covercharge'], $contents);
      $contents = str_replace("<\$gigtitle\$>", $item['gigtitle'], $contents);
      if(($item['saleslink'] != "") && ($item['saleslink'] != "http://")) 
        $contents = str_replace("<\$saleslink\$>", "<a href=\"{$item['saleslink']}\" target=\"_blank\">{$item['saleslink']}</a>", $contents);
      else
        $contents = str_replace("<\$saleslink\$>", "", $contents);
      $contents = str_replace("<\$giginfo\$>", $item['info'], $contents);
      $contents = str_replace("<\$maplinkstart\$>", maplink($venue['address1'], $venue['address2'], $venue['city'], $venue['state'], $venue['zip'], $venue['country']), $contents);
      $contents = str_replace("<\$maplinkend\$>", "</a>", $contents);

      $contents = str_replace("<\$bandname\$>", $band['bandname'], $contents);
      $contents = str_replace("<\$bandwebsite\$>", $band['website'], $contents);
      $contents = str_replace("<\$bandcontactname\$>", $band['contactname'], $contents);
      $contents = str_replace("<\$bandcontactemail\$>", $band['contactemail'], $contents);
      $contents = str_replace("<\$bandcontactphone\$>", $band['contactphone'], $contents);
      $contents = str_replace("<\$bandcity\$>", $band['city'], $contents);
      $contents = str_replace("<\$bandstate\$>", $band['state'], $contents);
      $contents = str_replace("<\$bandnotes\$>", $band['notes'], $contents);

      $contents = str_replace("<\$venuename\$>", $venue['venuename'], $contents);
      $contents = str_replace("<\$venuewebsite\$>", $venue['website'], $contents);
      $contents = str_replace("<\$venuecontactname\$>", $venue['contactname'], $contents);
      $contents = str_replace("<\$venuecontactemail\$>", $venue['contactemail'], $contents);
      $contents = str_replace("<\$venuecontactphone\$>", $venue['contactphone'], $contents);
      $contents = str_replace("<\$venuecity\$>", $venue['city'], $contents);
      $contents = str_replace("<\$venuestate\$>", $venue['state'], $contents);
      $contents = str_replace("<\$venueinfo\$>", $venue['info'], $contents);
      $contents = str_replace("<\$venueaddress1\$>", $venue['address1'], $contents);
      $contents = str_replace("<\$venueaddress2\$>", $venue['address2'], $contents);
      $contents = str_replace("<\$venuezip\$>", $venue['zip'], $contents);
      $contents = str_replace("<\$venuecountry\$>", $venue['country'], $contents);
      $contents = str_replace("<\$venuefax\$>", $venue['fax'], $contents);
      echo $contents;
    break;

    case 'venue':
      $contents = $config['details_venue'];
      $contents = str_replace("<\$venuename\$>", $item['venuename'], $contents);
      $contents = str_replace("<\$website\$>", $item['website'], $contents);
      $contents = str_replace("<\$contactname\$>", $item['contactname'], $contents);
      $contents = str_replace("<\$contactemail\$>", $item['contactemail'], $contents);
      $contents = str_replace("<\$contactphone\$>", $item['contactphone'], $contents);
      $contents = str_replace("<\$city\$>", $item['city'], $contents);
      $contents = str_replace("<\$state\$>", $item['state'], $contents);
      $contents = str_replace("<\$info\$>", $item['info'], $contents);
      $contents = str_replace("<\$address1\$>", $item['address1'], $contents);
      $contents = str_replace("<\$address2\$>", $item['address2'], $contents);
      $contents = str_replace("<\$zip\$>", $item['zip'], $contents);
      $contents = str_replace("<\$country\$>", $item['country'], $contents);
      $contents = str_replace("<\$fax\$>", $item['fax'], $contents);
      $contents = str_replace("<\$maplinkstart\$>", maplink($item['address1'], $item['address2'], $item['city'], $item['state'], $item['zip'], $item['country']), $contents);
      $contents = str_replace("<\$maplinkend\$>", "</a>", $contents);
      echo $contents;
    break;
  }
}// END detailsBuilder()

/*
 * Loads all necessary files for JS Overlib tooltips
 */
/*
function myloadOverlib() {
  global  $mosConfig_live_site, $mainframe;

  if ( !$mainframe->get( 'loadOverlib' ) ) {
    // check if this function is already loaded
    ?>
    <script language="javascript" type="text/javascript" src="<?php echo $mosConfig_live_site;?>/includes/js/overlib_mini.js"></script>
    <script language="javascript" type="text/javascript" src="<?php echo $mosConfig_live_site;?>/includes/js/overlib_hideform_mini.js"></script>
    <?php
    // change state so it isnt loaded a second time
    $mainframe->set( 'loadOverlib', true );
  }
}
*/

?>
