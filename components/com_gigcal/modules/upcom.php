<?php
/**
* @version $Id: mod_gigcal_upcom.php 1124 2005-12-15 06:37:21Z gsbe $
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

//function get_accesslevel() {
//global $my;
//$gid=$my->gid;
//return $gid;
//}

// The notes section often contains irregular HTML tags etc, some of which cause overlib to fail.
// This function cleans up strings prior to display by overlib, attempting to remove known problematic characters
// and tag sequences
function cleanup($text)
{
  $text = preg_replace('/((<br>|<br \/>|<br\/>)\s*)|(\r\n|\r|\n)/i', '<br />', $text);

  return htmlspecialchars(addslashes($text), ENT_QUOTES);
}

function formatCityState($city, $state, $extra='<br />')
{
  if ($city == '')
    $city = $state;
  else if ($state != '')
    $city .= ', '.$state;
  if ($city != '')
    $city .= $extra;
  return $city;
}

//$accesslevel =  get_accesslevel();
//global $config['dbprefix'];
$now = time();

$config = get_object_vars(new JConfig());

date_default_timezone_set($config['offset']);

$database_connect = mysql_connect($config['host'], $config['user'], $config['password']);
mysql_select_db($config['db'], $database_connect);

$sqlc = 'select * from '.$config['dbprefix'].'gigcal_config where active=1 LIMIT 1';
$gigconfig = mysql_fetch_assoc(mysql_query($sqlc));

$overlib_params = "";
if($gigconfig['upcom_hover_params'] != "") 
  $overlib_params = ', '.$gigconfig['upcom_hover_params'];

$gigconfig['upcom_dateformat2'] = date_reformater($gigconfig['upcom_dateformat2']);
$gigconfig['upcom_dateformat'] = date_reformater($gigconfig['upcom_dateformat']);
$gigconfig['upcom_timeformat'] = time_reformater($gigconfig['upcom_timeformat']);

//Get Fields
$sqlf = 'select * from '.$config['dbprefix'].'gigcal_upcom_fields where published=1 order by ordering';
if ($get_resultsf = mysql_query($sqlf))
  $num_resultsf = mysql_num_rows($get_resultsf);

if ($num_resultsf > 0) 
{
  while ($resultf = mysql_fetch_assoc($get_resultsf)) 
  {
    $itemorder[] = $resultf['fieldname'];
  }
}

$numitems = count($itemorder);
$itemcount = 0; 

echo '<style type="text/css">'.$gigconfig['upcom_css'].'</style>';

if($gigconfig['upcom_text'] != "") 
{
  echo '<span class="mod_gigcal_upcom_introtext">'.$gigconfig['upcom_text'].'<br /></span>';
  if($gigconfig['upcom_hrule'])
    echo '<hr />';
}

$sql = 'select * from '.$config['dbprefix'].'gigcal_gigs where published=1'
	.' and gigdate > '.$now
	.' order by gigdate';

if ($gigconfig['upcom_group_days'] == 0)
	$sql .= ' LIMIT '.$gigconfig['upcom_limit'];

if ($get_results = mysql_query($sql))
  $num_results = mysql_num_rows($get_results);

if ($num_results > 0) 
{
  echo '<div class="mod_gigcal_upcom">';

  $num_dates=0;
  $last_date='';

  while ($result = mysql_fetch_assoc($get_results)) 
  {
    if ($gigconfig['upcom_group_days'])
    {
      $this_date = date($gigconfig['upcom_dateformat'], $result["gigdate"]);
      if ($last_date != $this_date)
      {
        if (++$num_dates > $gigconfig['upcom_limit'])
          break;
        if ($last_date != '')
        {
          echo '</ul></div>';
          if($gigconfig['upcom_gule'])
            echo '<hr />';
        }
        $last_date = $this_date;
        echo '<div class="mod_gigcal_upcom_date">'.$this_date;
        echo '<ul class="mod_gigcal_upcom">';
      }
      echo '<li>';
    }

    $sqlv = 'select * from '.$config['dbprefix'].'gigcal_venues where id='.$result['venue_id'];
    $resultv = mysql_fetch_assoc(mysql_query($sqlv));

    $sqlb = 'select * from '.$config['dbprefix'].'gigcal_bands where id='.$result['band_id'];
    $resultb = mysql_fetch_assoc(mysql_query($sqlb));

    foreach($itemorder as $item) 
    {
      if($item == "gigTitle") 
      {
        $gigtitle = $result['gigtitle'];
        if ($gigtitle == '') 
          $gigtitle = $resultb['bandname']; // default to bandname if no explicit title provided...

        echo '<span class="mod_gigcal_upcom_title">';
        if($itemcount) 
          echo ' '.$gigconfig['upcom_delim'].' ';

        if($gigconfig['upcom_gigtitle_name'] != '')
          $prefix = $gigconfig['upcom_gigtitle_name'].' ';

        if($gigconfig['upcom_gigtitle_link']) 
          echo '<a href="'.JRoute::_('index.php?option=com_gigcal&task=details&id='.$result['id']).'">';           
 
        if($gigconfig['upcom_gigtitle_hover']) 
        {
          $caption = "CAPTION, '".addslashes($gigtitle)."' ";
          $hover =  '';
          if($gigconfig['upcom_gigtitle_hover_bandname']) 
            $hover .= $resultb['bandname'].'<br />';
          if($gigconfig['upcom_gigtitle_hover_venue']) 
            $hover .= $resultv['venuename'].'<br />';
          if($gigconfig['upcom_gigtitle_hover_date'])  
            $hover .= date($gigconfig['upcom_dateformat'], $result["gigdate"]).'<br />';
          if($gigconfig['upcom_gigtitle_hover_time']) 
            $hover .= date($gigconfig['upcom_timeformat'], $result["gigdate"]).'<br />';

          $hover = cleanup($hover);

          echo '<span onmouseover="return overlib(\''.$hover.'\', '.$caption.$overlib_params.');" onmouseout="return nd();">'.$prefix.$gigtitle.'</span>'; 
        }
        else
          echo $prefix.$gigtitle;

        if($gigconfig['upcom_gigtitle_link'])
          echo '</a>';
        echo '</span>';
      }

      if($item == "gigDate") 
      { 
        echo '<span class="mod_gigcal_upcom_date">';
        $date = date($gigconfig['upcom_dateformat'], $result["gigdate"]);

        if($itemcount) 
          echo ' '.$gigconfig['upcom_delim'].' ';

        if($gigconfig['upcom_gigdate_link']) 
          echo '<a href="'.JRoute::_('index.php?option=com_gigcal&task=details&id='.$result['id']).'">';           

        if($gigconfig['upcom_gigdate_hover']) 
        {
          $caption = "CAPTION, '".addslashes($date)."' ";
          $hover =  "";
          if($gigconfig['upcom_gigdate_hover_gigtitle'] && $result['gigtitle'] != '') 
            $hover .= $result['gigtitle'].'<br />';
          if($gigconfig['upcom_gigdate_hover_bandname']) 
            $hover .= $resultb["bandname"].'<br />';
          if($gigconfig['upcom_gigdate_hover_venue']) 
            $hover .= $resultv["venuename"].'<br />';
          if($gigconfig['upcom_gigdate_hover_cityst']) 
            $hover .= formatCityState($resultv["city"], $resultv['state']);
          if($gigconfig['upcom_gigdate_hover_date'])  
            $hover .= date($gigconfig['upcom_dateformat'], $result["gigdate"]).'<br />';
          if($gigconfig['upcom_gigdate_hover_time']) 
            $hover .= date($gigconfig['upcom_timeformat'], $result["gigdate"]).'<br />';
          if(($gigconfig['upcom_gigdate_hover_covercharge']) && ($result["covercharge"] != "")) 
            $hover .= $result["covercharge"].'<br />';
          if($gigconfig['upcom_gigdate_hover_notes'])  
            $hover .= $result["info"];

          $hover = cleanup($hover);

          echo '<span onmouseover="return overlib(\''.$hover.'\', '.$caption.$overlib_params.');" onmouseout="return nd();">'.$date.'</span>'; 
        }
        else
          echo $date;

        if($gigconfig['upcom_gigdate_link'])
          echo '</a>';
        echo '</span>';
      }

      if($item == "gigDate2") 
      { 
        echo '<span class="mod_gigcal_upcom_date2">';
        $date = date($gigconfig['upcom_dateformat2'], $result["gigdate"]);

        if($itemcount) 
          echo ' '.$gigconfig['upcom_delim'].' ';

        if($gigconfig['upcom_gigdate_link']) 
          echo '<a href="'.JRoute::_('index.php?option=com_gigcal&task=details&id='.$result['id']).'">';           

        if($gigconfig['upcom_gigdate_hover']) 
        {
          $caption = "CAPTION, '".addslashes($date)."' ";
          $hover =  "";
          if($gigconfig['upcom_gigdate2_hover_gigtitle'] && $result['gigtitle'] != '') 
            $hover .= $result['gigtitle'].'<br />';
          if($gigconfig['upcom_gigdate2_hover_bandname']) 
            $hover .= $resultb["bandname"].'<br />';
          if($gigconfig['upcom_gigdate2_hover_venue']) 
            $hover .= $resultv["venuename"].'<br />';
          if($gigconfig['upcom_gigdate2_hover_cityst']) 
            $hover .= formatCityState($resultv["city"], $resultv['state']);
          if($gigconfig['upcom_gigdate2_hover_date'])  
            $hover .= date($gigconfig['upcom_dateformat'], $result["gigdate"]).'<br />';
          if($gigconfig['upcom_gigdate2_hover_time']) 
            $hover .= date($gigconfig['upcom_timeformat'], $result["gigdate"]).'<br />';
          if(($gigconfig['upcom_gigdate2_hover_covercharge']) && ($result["covercharge"] != "")) 
            $hover .= $result["covercharge"].'<br />';
          if($gigconfig['upcom_gigdate2_hover_notes'])  
            $hover .= $result["info"];

          $hover = cleanup($hover);

          echo '<span onmouseover="return overlib(\''.$hover.'\', '.$caption.$overlib_params.');" onmouseout="return nd();">'.$date.'</span>'; 
        }
        else
          echo $date;

        if($gigconfig['upcom_gigdate2_link'])
          echo '</a>';
        echo '</span>';
      }

      if($item == "gigTime") 
      { 
        echo '<span class="mod_gigcal_upcom_time">';
        if($itemcount) 
          echo ' '.$gigconfig['upcom_delim'].'@ ';
        if($gigconfig['upcom_gigtime_link']) 
          echo '<a href="'.JRoute::_('index.php?option=com_gigcal&task=details&id='.$result['id']).'">';
        echo htmlspecialchars(date($gigconfig['upcom_timeformat'], $result["gigdate"]));
        if($gigconfig['upcom_gigtime_link'])
          echo '</a>';
        echo '</span>';
      }

      if($item == "gigBand") 
      {
        echo '<span class="mod_gigcal_upcom_band">';
        $bandname = $resultb['bandname'];

        if($itemcount) 
          echo ' '.$gigconfig['upcom_delim'].' ';

        $link = '';
        if(($gigconfig['upcom_gigband_link'] == 'bandwebsite') && ($resultb['website'] != "") && ($resultb['website'] != 'http://')) 
          $link = '<a target="_blank" href="'.$resultb[website].'">';
        else if($gigconfig['upcom_gigband_link'] == 'banddetails') 
          $link = '<a href="'.JRoute::_("index.php?option=com_gigcal&task=details&band_id=".$resultb['id']).'">';

        if($link != '')
          echo $link;

        if($gigconfig['upcom_gigband_popup']) 
        {
          if($gigconfig['upcom_gigband_link'] == 'banddetails') 
            $caption = 'Click to learn more about '.$bandname;
          else if(($gigconfig['upcom_gigband_link'] == 'bandwebsite') && ($resultb['website'] != "")) 
            $caption = 'Click to visit '.$bandname.'\'s website';
          else
            $caption = $bandname;

          $caption = "CAPTION, '".addslashes($caption)."'";
          $city = formatCityState($resultb['city'], $resultb['state']);

          $notes = $resultb['notes'];
          if ($notes != '')
            $notes .= '<br />';

          $hover = cleanup($city.$notes);

          echo '<span onmouseover="return overlib(\''.$hover.'\', '.$caption.$overlib_params.');" onmouseout="return nd();">'.$bandname.'</span>'; 
        }
        else
          echo $bandname;

        if($link != '')
          echo '</a>';
        echo '</span>';
      }

      if($item == "gigVenue") 
      {
        echo '<span class="mod_gigcal_upcom_venue">';
        $venuename = $resultv['venuename'];
 
        if($itemcount) 
          echo ' '.$gigconfig['upcom_delim'].' ';

        $link = '';
        if(($gigconfig['upcom_gigvenue_link'] == 'venuewebsite') && ($resultv['website'] != "") && ($resultv['website'] != 'http://')) 
          $link = '<a target="_blank" href="'.$resultv['website'].'">';
        else if($gigconfig['upcom_gigvenue_link'] == 'venuedetails') 
          $link = '<a href="'.JRoute::_('index.php?option=com_gigcal&task=details&venue_id='.$resultv['id']).'">';
        else if($gigconfig['upcom_gigvenue_link'] == "map") 
          $link = maplink($resultv['address1'], $resultv['address2'], $resultv['city'], $resultv['state'], $resultv['zip'], $resultv['country'], "r");

        if($link != '')
          echo $link;

        if($gigconfig['upcom_gigvenue_popup']) 
        { 
          if($gigconfig['upcom_gigvenue_link'] == "venuedetails") 
            $caption = 'Click to learn more about '.$venuename;
          else if($gigconfig['upcom_gigvenue_link'] == "map")
            $caption = 'Click for directions to '.$venuename;
          else if(($gigconfig['upcom_gigvenue_link'] == "venuewebsite") && ($resultv['website'] != "")) 
            $caption = 'Click to visit '.$venuename.'\'s website';
          else
            $caption = $venuename;

          $caption = "CAPTION, '".addslashes($caption)."'";
          $city = formatCityState($resultv['city'], $resultv['state']);
          if($resultv['phone'] != '')
            $city .= $resultv['phone'].'<br />';
          $hover = cleanup($city.$resultv['info']);

          echo '<span onmouseover="return overlib(\''.$hover.'\', '.$caption.$overlib_params.');" onmouseout="return nd();">'.$venuename.'</span>';
        }
        else
          echo $venuename;

        if($link != '')
          echo '</a>';
        echo '</span>';
      }

      if($item == "Location") 
      { 
        $location = formatCityState($resultv['city'], $resultv['state'], '');
        if ($location != '')
        {
          echo '<span class="mod_gigcal_upcom_location">';
          $venuename = $resultv['venuename'];

          if($itemcount) 
            echo ' '.$gigconfig['upcom_delim'].' ';
        
          $link = '';
          if(($gigconfig['upcom_location_link'] == 'venuewebsite') && ($resultv['website'] != "") && ($resultv['website'] != 'http://')) 
            $link = '<a target="_blank" href="'.$resultv['website'].'">';
          else if($gigconfig['upcom_location_link'] == 'venuedetails') 
            $link = '<a href="'.JRoute::_('index.php?option=com_gigcal&task=details&venue_id='.$resultv['id']).'">';
          else if($gigconfig['upcom_location_link'] == "map") 
            $link = maplink($resultv['address1'], $resultv['address2'], $resultv['city'], $resultv['state'], $resultv['zip'], $resultv['country'], "r");

          if($link != '')
            echo $link;

          if($gigconfig['upcom_location_popup']) 
          {
            $caption = "";
            if($gigconfig['upcom_location_link'] == "venuedetails") 
              $caption = 'Click to learn more about '.$venuename;
            else if($gigconfig['upcom_location_link'] == "map") 
              $caption = 'Click for directions to '.$venuename;
            else if(($gigconfig['upcom_location_link'] == 'venuewebsite') && ($resultv['website'] != "")) 
              $caption = 'Click to visit '.$venuename.'\'s website';
            else
              $caption = $venuename;

            $caption = "CAPTION, '".addslashes($caption)."'";

            $hover = $venuename.'<br />'.$location.'<br />';
            if($resultv['phone'] != '')
              $hover .= $resultv['phone'].'<br />';
            $hover = cleanup($hover.$resultv['info']);

            echo '<span onmouseover="return overlib(\''.$hover.'\', '.$caption.$overlib_params.');" onmouseout="return nd();">'.$location.'</span>';
          }
          else
            echo $location;

          if($link != '')
            echo '</a>';
          echo '</span>';
        }
      }

      if($item == "Country") 
      { 
        $country = $resultv["country"];

        if ($country != '')
        {
          echo '<span class="mod_gigcal_upcom_country">';
          $venuename = $resultv['venuename'];

          if($itemcount) 
            echo ' '.$gigconfig['upcom_delim'].' ';

          $link = '';
          if(($gigconfig['upcom_country_link'] == 'venuewebsite') && ($resultv['website'] != "") && ($resultv['website'] != 'http://')) 
            $link = '<a target="_blank" href="'.$resultv['website'].'">';
          else if($gigconfig['upcom_country_link'] == 'venuedetails') 
            $link = '<a href="'.JRoute::_('index.php?option=com_gigcal&task=details&venue_id='.$resultv['id']).'">';
          else if($gigconfig['upcom_country_link'] == "map") 
            $link = maplink($resultv['address1'], $resultv['address2'], $resultv['city'], $resultv['state'], $resultv['zip'], $resultv['country'], "r");

          if($link != '')
            echo $link;

          if($gigconfig['upcom_country_popup']) 
          {
            $caption = "";
            if($gigconfig['upcom_country_link'] == "venuedetails") 
              $caption = "Click to learn more about ".$venuename;
            else if($gigconfig['upcom_country_link'] == "map") 
              $caption = "Click for directions to ".$venuename;
            else if(($gigconfig['upcom_country_link'] == "venuewebsite") && ($resultv['website'] != "")) 
              $caption = "Click to visit ".$venuename."'s website";
            else
              $caption = $venuename;

            $caption = "CAPTION, '".addslashes($caption)."' ";

            $hover = $venuename.'<br />'.formatCityState($resultv['city'], $resultv['state']);
            if($resultv['phone'] != '')
              $hover .= $resultv['phone'].'<br />';
            $hover = cleanup($hover.$resultv['info']);

            echo '<span onmouseover="return overlib(\''.$hover.'\', '.$caption.$overlib_params.');" onmouseout="return nd();">'.$country.'</span>';
          }
          else
            echo $country;

          if($link != '')
            echo '</a>';
          echo '</span>';
        }
        else
          $itemcount--; 
      }

      if($item == "gigNotes/Info") 
      { 
        $info = $result["info"];

        if ($info != '')
        {
          echo '<span class="mod_gigcal_upcom_info">';
          if($itemcount) 
            echo ' '.$gigconfig['upcom_delim'].' ';

          if($gigconfig['upcom_giginfo_link']) 
            echo '<a href="'.JRoute::_('index.php?option=com_gigcal&task=details&id='.$result['id']).'">';
          echo $info;
          if($gigconfig['upcom_giginfo_link']) 
            echo '</a>';
          echo '</span>';
        }
        else
          $itemcount--; 
      }

      if($item == "gigCover Charge") 
      {
        if ($result['covercharge'] != "") 
        {
          echo '<span class="mod_gigcal_upcom_covercharge">';
          if($itemcount) 
            echo ' '.$gigconfig['upcom_delim'].' ';
          echo $gigconfig['upcom_covercharge_name'].' '.$result['covercharge'];
          echo '</span>';
        }
        else
          $itemcount--; 
      }

      if($item == "Link to online Ticket Sales") 
      {
        $link = $result['saleslink'];
        if(($link != '') && ($link != 'http://')) 
        {
          echo '<span class="mod_gigcal_upcom_saleslink">';
          $linkname = $gigconfig['upcom_ticketlink_name'];

          if($itemcount) 
            echo ' '.$gigconfig['upcom_delim'].' ';

          echo '<a target="_blank" href="'.$link.'" alt="'.$linkname.'" title="'.$linkname.'">'.$linkname.'</a>';
          echo '</span>';
        }
        else
          $itemcount--; 
      }

      if($item == "Link to Map") 
      { 
        echo '<span class="mod_gigcal_upcom_map">';

        if($itemcount) 
          echo ' '.$gigconfig['upcom_delim'].' ';
        echo maplink($resultv['address1'], $resultv['address2'], $resultv['city'], $resultv['state'], $resultv['zip'], $resultv['country'], "r");
        echo $gigconfig['upcom_maplink_name'].'</a>'; 
        echo '</span>';
      }
      $itemcount++; 
    } 

    if ($gigconfig['upcom_group_days'])
      echo '</li>';
    else if($gigconfig['upcom_hrule'])
      echo '<hr />';
    else
      echo '<br /><br />';

    $itemcount = 0;
  }

  if ($gigconfig['upcom_group_days'])
  {
    echo '</ul></div>';
    if($gigconfig['upcom_hrule'])
      echo '<hr />';
  }

  echo '</div>';
}


