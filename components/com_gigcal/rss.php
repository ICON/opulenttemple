<?php
/**
* @version $Id: rss.php 1124 2005-12-15 06:37:21Z gsbe $
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

require_once("../../configuration.php");

$config = get_object_vars(new JConfig());

//error_log(print_r($config,true));
//  // Settings from the Joomla! configuration file
//  foreach (get_object_vars($config) as $k => $v) {
//    $name = 'mosConfig_'.$k;
//    $$name = $GLOBALS[$name] = $v;
//  }
//}
//error_log('connecting to '.$config['host'].', '.$config['user']);
$database_connect = mysql_connect($config['host'], $config['user'], $config['password']);
mysql_select_db($config['db'], $database_connect);

$now = time();

$url = 'http://'.$config['host'].'/'.$config['sitename'].'/';

header("Content-Type: text/xml");
?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
<channel>
<title>gigCalendar RSS Feed</title>
<link><?php echo $url; ?></link>
<description>gigCalendar RSS Feeds</description>
<language>en-us</language>
<ttl>60</ttl>
<?php
$band_id = 0;
if (in_array('band_id', $_GET))
  $band_id = mysql_real_escape_string($_GET['band_id']);

$venue_id = 0;
if (in_array('venue_id', $_GET))
  $venue_id = mysql_real_escape_string($_GET['venue_id']);

$filters = '';
if($venue_id > 0) 
  $filters .= " AND g.venue_id=".$venue_id;
    
if($band_id > 0) 
  $filters .= " AND g.band_id=".$band_id;

$sql = 'SELECT g.*, bandname, venuename FROM '.$config['dbprefix'].'gigcal_gigs AS g, '.
       'JOIN '.$config['dbprefix'].'gigcal_bands AS b ON g.band_id=b.id '.
       'JOIN '.$config['dbprefix'].'gigcal_venues AS v ON g.venue_id=v.id '.
       'WHERE g.published=1 AND gigdate > '.$now.$filters.' ORDER BY gigdate';
if ($get_results = mysql_query($sql))
{
  $num_results = mysql_num_rows($get_results);

  if ($num_results > 0) {
    while ($gig = mysql_fetch_assoc($get_results)) {
      $bandname = str_replace("<br />", "", $gig['bandname']);
      $bandname = str_replace("&", "&amp;", $bandname);
      $bandname = str_replace("<br />", "", $bandname);

      $venuename = str_replace("<br />", "", $gig['venuename']);
      $venuename = str_replace("&", "&amp;", $venuename);
      $venuename = str_replace("<br />", "", $venuename);
    
      $giginfo = str_replace("<br />", "", $gig['info']);
      $giginfo = str_replace("&", "&amp;", $giginfo);
      $giginfo = str_replace("<br />", "", $giginfo);
    
      $gigtitle = str_replace("<br />", "", $gig['gigtitle']);
      $gigtitle = str_replace("&", "&amp;", $gigtitle);
      $gigtitle = str_replace("<br />", "", $gigtitle);
    
      if ($gigtitle!='')
        $gigtitle .= " : ";
      $gigtitle .= $bandname." @ ".$venuename." - ".date("M. d, Y (D)", $gig['gigdate']);
      
      echo "  <item>\n";
      echo "    <title>".$gigtitle."</title>\n";
      echo "    <description><![CDATA[".$giginfo."]]></description>\n";
      echo "    <link>".$url.'?option=com_gigcal&amp;task=details&amp;id='.$gig['id'].'</link>\n';
      echo '    <guid isPermaLink="false">'.$gig['id']."@".$url."</guid>\n";
      //	echo "    <pubDate>".date("r", $gig["gigdate"])."</pubDate>\n";
      echo "  </item>\n";
    }
  }
}
?>
</channel>
</rss>


