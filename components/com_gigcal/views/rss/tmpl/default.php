<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
?>

<?php

require_once JPATH_COMPONENT.DS.'views'.DS.'nav.php';

echo $this->menuHTML;
//getHeadingHTML($this->config, 'rss');

// Let's insert some CSS here
//echo '<style type="text/css">'.$this->config['details_css'].'</style>';

echo "<h2>RSS Feeds: </h2>";
echo "<blockquote>";

if($this->config['rss_all']) {
  echo "<h3>All Gigs</h3>";
  $url=JRoute::_(JURI::base().'/components/com_gigcal/rss.php');
  echo '<a href="'.$url.'">'.$url.'</a><br />';
} 

if($this->config['rss_band']) {
  if (count($this->bands) > 1) {
    echo "<h3>RSS Feeds per band</h3><ul>"; 
    foreach($this->bands as $band) {
      $url=JRoute::_(JURI::base().'/components/com_gigcal/rss.php?band_id='.$band['id']);
      echo '<li>RSS feed for <b>'.$band['bandname'].'</b><br />';
      echo '<a href="'.$url.'">'.$url.'</a></li>';
    }
    echo "</ul>";
  }
}

if($this->config['rss_venue']) {
  if (count($this->venues) > 1) {
    echo "<h3>RSS Feeds per venue</h3><ul>";
    foreach($this->venues as $venue) {
      $url=JRoute::_(JURI::base().'/components/com_gigcal/rss.php?venue_id='.$venue['id']);
      echo '<li>RSS feed for <b>'.$venue['venuename'].'</b><br />';
      echo '<a href="'.$url.'">'.$url.'</a></li>';
    }
    echo "</ul>";
  }
}

echo '</blockquote>';

echo $this->menuHTML;

