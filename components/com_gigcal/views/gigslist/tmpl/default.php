<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

require_once JPATH_COMPONENT.DS.'views'.DS.'nav.php';

// echo $this->menuHTML;

// Let's insert some CSS here
echo '<style type="text/css">'.$this->config['details_css'].'</style>';

foreach($this->gigs as $gig)
{
  $gig['band'] = $this->bands[$gig['band_id']];
  $gig['venue'] = $this->venues[$gig['venue_id']];
  detailsBuilder($this->config, 'gig', $gig);
}
/*

//$gig = dbLookupDetails("gigs", $_REQUEST['gigcal_gigs_id']);
//$venue = dbLookupDetails("venues", $gig['gigcal_venues_id']);
//$band = dbLookupDetails("bands", $gig['gigcal_bands_id']);
$gig = $this->gigs[0];
$band = $this->bands[$gig['band_id']];
$venue = $this->venues[$gig['venue_id']];

$contents =  $this->config['details_gig'];

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

$contents = str_replace("<\$gigtitle\$>", $gig['gigtitle'], $contents);
$contents = str_replace("<\$gigdate\$>", date(date_reformater($this->config['details_dateformat']), $gig['gigdate']), $contents);
$contents = str_replace("<\$gigtime\$>", date(time_reformater($this->config['details_timeformat']), $gig['gigdate']), $contents);
$contents = str_replace("<\$covercharge\$>", $gig['covercharge'], $contents);

if(($gig['saleslink'] != "") && ($gig['saleslink'] != "http://"))
  $contents = str_replace("<\$saleslink\$>", "<a href=\"{$gig['saleslink']}\" target=\"_blank\">{$gig['saleslink']}</a>", $contents);
else
  $contents = str_replace("<\$saleslink\$>", "", $contents);


$contents = str_replace("<\$giginfo\$>", $gig['info'], $contents);
$contents = str_replace("<\$maplinkstart\$>", maplink($venue['address1'], $venue['address2'], $venue['city'], $venue['state'], $venue['zip'], $venue['country']), $contents);
$contents = str_replace("<\$maplinkend\$>", "</a>", $contents);


//$temp = new stdClass(); 
//$temp->text = 
//$contents = str_replace("<\$bandnotes\$>", $band['notes'], $contents);
//$result = $_MAMBOTS->trigger( 'onPrepareContent' , array( 1, &$temp, null, 0));
//echo $temp->text;

echo $contents;
*/

echo $this->menuHTML;

