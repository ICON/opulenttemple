DROP TABLE IF EXISTS `#__gigcal_bands`;
DROP TABLE IF EXISTS `#__gigcal_gigs`;
DROP TABLE IF EXISTS `#__gigcal_venues`;
DROP TABLE IF EXISTS `#__gigcal_alist_fields`;
DROP TABLE IF EXISTS `#__gigcal_cal_fields`;
DROP TABLE IF EXISTS `#__gigcal_config`;
DROP TABLE IF EXISTS `#__gigcal_list_fields`;
DROP TABLE IF EXISTS `#__gigcal_menu_fields`;
DROP TABLE IF EXISTS `#__gigcal_upcom_fields`;

DROP VIEW IF EXISTS `#__gigcal_gigs_import`;
 
CREATE TABLE `#__gigcal_bands` (
  `id` int(11) NOT NULL auto_increment,
  `published` tinyint(1) NOT NULL default '0',
  `featured` tinyint(1) NOT NULL default '0',
  `thedefault` tinyint(1) NOT NULL default '0',
  `bandname` varchar(120) NOT NULL default '',
  `website` varchar(180) NOT NULL default '',
  `contactname` varchar(180) NOT NULL default '',
  `contactemail` varchar(180) NOT NULL default '',
  `contactphone` varchar(180) NOT NULL default '',
  `city` varchar(180) NOT NULL default '',
  `state` varchar(180) NOT NULL default '',
  `notes` text NOT NULL,
  `checked_out` tinyint(4) NOT NULL default '0',
  `checked_out_time` datetime NOT NULL default '0000-00-00 00:00:00',
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  `created_by` tinyint(4) NOT NULL default '0',
  `created_by_alias` varchar(255) NOT NULL DEFAULT '',
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY  (`id`)
);

CREATE TABLE `#__gigcal_gigs` (
  `id` int(11) NOT NULL auto_increment,
  `gigdate` int(11) NOT NULL default '0',
  `venue_id` int(11) NOT NULL default '0',
  `band_id` int(11) NOT NULL default '0',
  `published` tinyint(1) NOT NULL default '0',
  `featured` tinyint(1) NOT NULL default '0',
  `access` tinyint(1) NOT NULL default '0',
  `gigtitle` varchar(255) NOT NULL default '',
  `covercharge` varchar(255) NOT NULL default '',
  `saleslink` varchar(255) NOT NULL default '',
  `info` text NOT NULL,
  `searchfields` text NOT NULL,
  `checked_out` tinyint(4) NOT NULL default '0',
  `checked_out_time` datetime NOT NULL default '0000-00-00 00:00:00',
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  `created_by` tinyint(4) NOT NULL default '0',
  `created_by_alias` varchar(255) NOT NULL DEFAULT '',
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY  (`id`)
);

CREATE TABLE  `#__gigcal_venues` (
  `id` int(11) NOT NULL auto_increment,
  `venuename` varchar(60) NOT NULL default '',
  `published` tinyint(1) NOT NULL default '0',
  `featured` tinyint(1) NOT NULL default '0',
  `thedefault` tinyint(1) NOT NULL default '0',
  `address1` varchar(60) NOT NULL default '',
  `address2` varchar(60) NOT NULL default '',
  `city` varchar(50) NOT NULL default '',
  `state` varchar(30) NOT NULL default '',
  `zip` varchar(15) NOT NULL default '',
  `country` varchar(120) NOT NULL default '',
  `website` varchar(180) NOT NULL default '',
  `phone` varchar(60) NOT NULL default '',
  `fax` varchar(60) NOT NULL default '',
  `contactname` varchar(60) NOT NULL default '',
  `contactphone` varchar(80) NOT NULL default '',
  `contactemail` varchar(120) NOT NULL default '',
  `info` text NOT NULL,
  `checked_out` tinyint(4) NOT NULL default '0',
  `checked_out_time` datetime NOT NULL default '0000-00-00 00:00:00',
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  `created_by` tinyint(4) NOT NULL default '0',
  `created_by_alias` varchar(255) NOT NULL DEFAULT '',
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY  (`id`)
  );

CREATE TABLE  `#__gigcal_alist_fields` (
  `id` int(11) NOT NULL auto_increment,
  `fieldname` varchar(30) NOT NULL default '',
  `ordering` int(11) NOT NULL default '0',
  `published` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
  );
              
CREATE TABLE  `#__gigcal_cal_fields` (
    `id` int(11) NOT NULL auto_increment,
    `fieldname` varchar(30) NOT NULL default '',
    `ordering` int(11) NOT NULL default '0',
    `published` int(11) NOT NULL default '0',
    PRIMARY KEY  (`id`)
    );
    
CREATE TABLE  `#__gigcal_config` (
    `active` int(11) NOT NULL default '0',
    `alist_country_link` varchar(50) NOT NULL default '',
    `alist_country_popup` varchar(50) NOT NULL default '',
    `alist_covercharge_link` int(11) NOT NULL default '0',
    `alist_covercharge_name` varchar(40) NOT NULL default '',
    `alist_css` text NOT NULL,
    `alist_dateformat` varchar(140) NOT NULL default '',
    `alist_dateformat2` varchar(140) NOT NULL default '',
    `alist_delim` varchar(250) NOT NULL default '',
    `alist_filter_display_len` int(11) NOT NULL default '0',
    `alist_gigband_link` varchar(40) NOT NULL default '',
    `alist_gigband_popup` int(11) NOT NULL default '0',
    `alist_gigdate_hover` int(11) NOT NULL default '0',
    `alist_gigdate_hover_bandname` int(11) NOT NULL default '0',
    `alist_gigdate_hover_cityst` int(11) NOT NULL default '0',
    `alist_gigdate_hover_covercharge` int(11) NOT NULL default '0',
    `alist_gigdate_hover_date` int(11) NOT NULL default '0',
    `alist_gigdate_hover_gigtitle` int(11) NOT NULL default '0',
    `alist_gigdate_hover_notes` int(11) NOT NULL default '0',
    `alist_gigdate_hover_time` int(11) NOT NULL default '0',
    `alist_gigdate_hover_venue` int(11) NOT NULL default '0',
    `alist_gigdate_link` int(11) NOT NULL default '0',
    `alist_gigdate2_hover` int(11) NOT NULL default '0',
    `alist_gigdate2_hover_bandname` int(11) NOT NULL default '0',
    `alist_gigdate2_hover_cityst` int(11) NOT NULL default '0',
    `alist_gigdate2_hover_covercharge` int(11) NOT NULL default '0',
    `alist_gigdate2_hover_date` int(11) NOT NULL default '0',
    `alist_gigdate2_hover_gigtitle` int(11) NOT NULL default '0',
    `alist_gigdate2_hover_notes` int(11) NOT NULL default '0',
    `alist_gigdate2_hover_time` int(11) NOT NULL default '0',
    `alist_gigdate2_hover_venue` int(11) NOT NULL default '0',
    `alist_gigdate2_link` int(11) NOT NULL default '0',
    `alist_giginfo_link` int(11) NOT NULL default '0',
    `alist_gigtime_link` int(11) NOT NULL default '0',
    `alist_gigtitle_hover` int(11) NOT NULL default '0',
    `alist_gigtitle_hover_bandname` int(11) NOT NULL default '0',
    `alist_gigtitle_hover_date` int(11) NOT NULL default '0',
    `alist_gigtitle_hover_time` int(11) NOT NULL default '0',
    `alist_gigtitle_hover_venue` int(11) NOT NULL default '0',
    `alist_gigtitle_link` int(11) NOT NULL default '0',
    `alist_gigvenue_link` varchar(40) NOT NULL default '0',
    `alist_gigvenue_popup` int(11) NOT NULL default '0',
    `alist_hover_params` text NOT NULL,
    `alist_ical_header` varchar(140) NOT NULL default '',
    `alist_ical_link_name` varchar(140) NOT NULL default '',
    `alist_location_link` varchar(50) NOT NULL default '',
    `alist_location_popup` varchar(50) NOT NULL default '',
    `alist_maplink_name` varchar(40) NOT NULL default '',
    `alist_sort_reverse` int(11) NOT NULL default '1',
    `alist_text` text NOT NULL,
    `alist_ticketlink_name` varchar(40) NOT NULL default '',
    `alist_timeformat` varchar(140) NOT NULL default '',
    `alist_vcal_header` varchar(140) NOT NULL default '',
    `alist_vcal_link_name` varchar(140) NOT NULL default '',
    `auto_gig_clone` int(11) NOT NULL default '1',
    `cal_april` varchar(60) NOT NULL default '',
    `cal_august` varchar(60) NOT NULL default '',
    `cal_country_link` varchar(50) NOT NULL default '',
    `cal_country_popup` varchar(50) NOT NULL default '',
    `cal_covercharge_link` int(11) NOT NULL default '0',
    `cal_covercharge_name` varchar(40) NOT NULL default '',
    `cal_css` text NOT NULL,
    `cal_date_jumper` int(11) NOT NULL default '0',
    `cal_dateformat` varchar(140) NOT NULL default '',
    `cal_dateformat2` varchar(140) NOT NULL default '',
    `cal_december` varchar(60) NOT NULL default '',
    `cal_delim` varchar(40) NOT NULL default '',
    `cal_february` varchar(60) NOT NULL default '',
    `cal_filter_display_len` int(11) NOT NULL default '0',
    `cal_friday` varchar(25) NOT NULL default '',
    `cal_gigband_link` varchar(40) NOT NULL default '',
    `cal_gigband_popup` int(11) NOT NULL default '0',
    `cal_gigdate_hover` int(11) NOT NULL default '0',
    `cal_gigdate_hover_bandname` int(11) NOT NULL default '0',
    `cal_gigdate_hover_cityst` int(11) NOT NULL default '0',
    `cal_gigdate_hover_covercharge` int(11) NOT NULL default '0',
    `cal_gigdate_hover_date` int(11) NOT NULL default '0',
    `cal_gigdate_hover_gigtitle` int(11) NOT NULL default '0',
    `cal_gigdate_hover_notes` int(11) NOT NULL default '0',
    `cal_gigdate_hover_time` int(11) NOT NULL default '0',
    `cal_gigdate_hover_venue` int(11) NOT NULL default '0',
    `cal_gigdate_link` int(11) NOT NULL default '0',
    `cal_gigdate2_hover` int(11) NOT NULL default '0',
    `cal_gigdate2_hover_bandname` int(11) NOT NULL default '0',
    `cal_gigdate2_hover_cityst` int(11) NOT NULL default '0',
    `cal_gigdate2_hover_covercharge` int(11) NOT NULL default '0',
    `cal_gigdate2_hover_date` int(11) NOT NULL default '0',
    `cal_gigdate2_hover_gigtitle` int(11) NOT NULL default '0',
    `cal_gigdate2_hover_notes` int(11) NOT NULL default '0',
    `cal_gigdate2_hover_time` int(11) NOT NULL default '0',
    `cal_gigdate2_hover_venue` int(11) NOT NULL default '0',
    `cal_gigdate2_link` int(11) NOT NULL default '0',
    `cal_giginfo_link` int(11) NOT NULL default '0',
    `cal_gigtime_link` int(11) NOT NULL default '0',
    `cal_gigtitle_hover` int(11) NOT NULL default '0',
    `cal_gigtitle_hover_bandname` int(11) NOT NULL default '0',
    `cal_gigtitle_hover_date` int(11) NOT NULL default '0',
    `cal_gigtitle_hover_time` int(11) NOT NULL default '0',
    `cal_gigtitle_hover_venue` int(11) NOT NULL default '0',
    `cal_gigtitle_link` int(11) NOT NULL default '0',
    `cal_gigvenue_link` varchar(40) NOT NULL default '',
    `cal_gigvenue_popup` int(11) NOT NULL default '0',
    `cal_hover_params` text NOT NULL,
    `cal_ical_link_name` varchar(100) NOT NULL default '',
    `cal_january` varchar(60) NOT NULL default '',
    `cal_july` varchar(60) NOT NULL default '',
    `cal_june` varchar(60) NOT NULL default '',
    `cal_leftarrowmark` text NOT NULL,
    `cal_location_link` varchar(50) NOT NULL default '',
    `cal_location_popup` varchar(50) NOT NULL default '',
    `cal_maplink_name` varchar(40) NOT NULL default '',
    `cal_march` varchar(60) NOT NULL default '',
    `cal_may` varchar(60) NOT NULL default '',
    `cal_monday` varchar(60) NOT NULL default '',
    `cal_november` varchar(60) NOT NULL default '',
    `cal_october` varchar(60) NOT NULL default '',
    `cal_rightarrowmark` text NOT NULL,
    `cal_saturday` varchar(25) NOT NULL default '',
    `cal_september` varchar(60) NOT NULL default '',
    `cal_sunday` varchar(25) NOT NULL default '',
    `cal_text` text NOT NULL,
    `cal_thursday` varchar(25) NOT NULL default '',
    `cal_ticketlink_name` varchar(40) NOT NULL default '',
    `cal_timeformat` varchar(140) NOT NULL default '',
    `cal_tuesday` varchar(60) NOT NULL default '',
    `cal_vcal_link_name` varchar(100) NOT NULL default '',
    `cal_wendsday` varchar(25) NOT NULL default '',
    `city_header` varchar(120) NOT NULL default '',
    `city_header_alist` varchar(140) NOT NULL default '',
    `Country_header` varchar(120) NOT NULL default '',
    `Country_header_alist` varchar(140) NOT NULL default '',
    `covercharge_header` varchar(120) NOT NULL default '',
    `covercharge_header_alist` varchar(140) NOT NULL default '',
    `default_task` int(11) NOT NULL default '1',    
    `details_band` text NOT NULL,
    `details_css` text NOT NULL,
    `details_dateformat` varchar(140) NOT NULL default '',
    `details_gig` text NOT NULL,
    `details_timeformat` varchar(140) NOT NULL default '',
    `details_venue` text NOT NULL,
    `export_all_cals` int(11) NOT NULL default '0',
    `gen_css` text NOT NULL,
    `gigBand_header` varchar(120) NOT NULL default '',
    `gigBand_header_alist` varchar(140) NOT NULL default '',
    `gigDate_header` varchar(120) NOT NULL default '',
    `gigDate_header_alist` varchar(140) NOT NULL default '',
    `gigDate2_header` varchar(120) NOT NULL default '',
    `gigDate2_header_alist` varchar(140) NOT NULL default '',
    `gignotes_header` varchar(120) NOT NULL default '',
    `gignotes_header_alist` varchar(140) NOT NULL default '',
    `gigTime_header` varchar(120) NOT NULL default '',
    `gigTime_header_alist` varchar(140) NOT NULL default '',
    `gigtitle_header` varchar(120) NOT NULL default '',
    `gigtitle_header_alist` varchar(140) NOT NULL default '',
    `gigVenue_header` varchar(120) NOT NULL default '',
    `gigVenue_header_alist` varchar(140) NOT NULL default '',
    `ical_header` varchar(100) NOT NULL default '',
    `ical_header_alist` varchar(240) NOT NULL default '',
    `list_country_link` varchar(50) NOT NULL default '',
    `list_country_popup` varchar(50) NOT NULL default '',
    `list_covercharge_link` int(11) NOT NULL default '0',
    `list_covercharge_name` varchar(40) NOT NULL default '',
    `list_css` text NOT NULL,
    `list_dateformat` varchar(140) NOT NULL default '',
    `list_dateformat2` varchar(140) NOT NULL default '',
    `list_delim` varchar(250) NOT NULL default '',
    `list_gigband_link` varchar(40) NOT NULL default '',
    `list_gigband_popup` int(11) NOT NULL default '0',
    `list_gigdate_hover` int(11) NOT NULL default '0',
    `list_gigdate_hover_bandname` int(11) NOT NULL default '0',
    `list_gigdate_hover_cityst` int(11) NOT NULL default '0',
    `list_gigdate_hover_covercharge` int(11) NOT NULL default '0',
    `list_gigdate_hover_date` int(11) NOT NULL default '0',
    `list_gigdate_hover_gigtitle` int(11) NOT NULL default '0',
    `list_gigdate_hover_notes` int(11) NOT NULL default '0',
    `list_gigdate_hover_time` int(11) NOT NULL default '0',
    `list_gigdate_hover_venue` int(11) NOT NULL default '0',
    `list_gigdate_link` int(11) NOT NULL default '0',
    `list_gigdate2_hover` int(11) NOT NULL default '0',
    `list_gigdate2_hover_bandname` int(11) NOT NULL default '0',
    `list_gigdate2_hover_cityst` int(11) NOT NULL default '0',
    `list_gigdate2_hover_covercharge` int(11) NOT NULL default '0',
    `list_gigdate2_hover_date` int(11) NOT NULL default '0',
    `list_gigdate2_hover_gigtitle` int(11) NOT NULL default '0',
    `list_gigdate2_hover_notes` int(11) NOT NULL default '0',
    `list_gigdate2_hover_time` int(11) NOT NULL default '0',
    `list_gigdate2_hover_venue` int(11) NOT NULL default '0',
    `list_gigdate2_link` int(11) NOT NULL default '0',
    `list_giginfo_link` int(11) NOT NULL default '0',
    `list_gigtime_link` int(11) NOT NULL default '0',
    `list_gigtitle_hover` int(11) NOT NULL default '0',
    `list_gigtitle_hover_bandname` int(11) NOT NULL default '0',
    `list_gigtitle_hover_date` int(11) NOT NULL default '0',
    `list_gigtitle_hover_time` int(11) NOT NULL default '0',
    `list_gigtitle_hover_venue` int(11) NOT NULL default '0',
    `list_gigtitle_link` int(11) NOT NULL default '0',
    `list_gigvenue_link` varchar(40) NOT NULL default '',
    `list_gigvenue_popup` int(11) NOT NULL default '0',
    `list_hover_params` text NOT NULL,
    `list_ical_link_name` varchar(100) NOT NULL default '',
    `list_location_link` varchar(50) NOT NULL default '',
    `list_location_popup` varchar(50) NOT NULL default '',
    `list_maplink_name` varchar(40) NOT NULL default '',
    `list_sort_reverse` int(11) NOT NULL default '0',
    `list_text` text NOT NULL,
    `list_ticketlink_name` varchar(40) NOT NULL default '',
    `list_timeformat` varchar(140) NOT NULL default '',
    `list_vcal_link_name` varchar(100) NOT NULL default '',
    `Location_header` varchar(120) NOT NULL default '',
    `Location_header_alist` varchar(140) NOT NULL default '',
    `map_header` varchar(120) NOT NULL default '',
    `map_header_alist` varchar(140) NOT NULL default '',
    `menu_alist` varchar(80) NOT NULL default '',
    `menu_bandslist` varchar(80) NOT NULL default '',
    `menu_bottom` int(11) NOT NULL default '0',
    `menu_cal` varchar(80) NOT NULL default '',
    `menu_delim` varchar(20) NOT NULL default '',
    `menu_details` int(11) NOT NULL default '0',
    `menu_list` varchar(80) NOT NULL default '',
    `menu_rss` varchar(80) NOT NULL default '',
    `menu_top` int(11) NOT NULL default '0',
    `menu_venueslist` varchar(80) NOT NULL default '',
    `minical_css` text NOT NULL,
    `minical_dateformat` varchar(140) NOT NULL default '',
    `minical_gigcal_link_text` text NOT NULL,
    `minical_gigcal_link_to` varchar(40) NOT NULL default '',
    `minical_hover` int(11) NOT NULL default '0',
    `minical_hover_bandname` int(11) NOT NULL default '0',
    `minical_hover_cityst` int(11) NOT NULL default '0',
    `minical_hover_covercharge` int(11) NOT NULL default '0',
    `minical_hover_date` int(11) NOT NULL default '0',
    `minical_hover_gigtitle` int(11) NOT NULL default '0',
    `minical_hover_notes` int(11) NOT NULL default '0',
    `minical_hover_params` text NOT NULL,
    `minical_hover_time` int(11) NOT NULL default '0',
    `minical_hover_venue` int(11) NOT NULL default '0',
    `minical_link` int(11) NOT NULL default '0',
    `minical_text` text NOT NULL,
    `minical_timeformat` varchar(140) NOT NULL default '',
    `rss_all` int(11) NOT NULL default '0',
    `rss_band` int(11) NOT NULL default '0',
    `rss_venue` int(11) NOT NULL default '0',
    `show_gig_clone` int(11) NOT NULL default '1',
    `State_header` varchar(120) NOT NULL default '',
    `State_header_alist` varchar(140) NOT NULL default '',
    `ticket_header` varchar(120) NOT NULL default '',
    `ticket_header_alist` varchar(140) NOT NULL default '',
    `upcom_country_link` varchar(50) NOT NULL default '',
    `upcom_country_popup` varchar(50) NOT NULL default '',
    `upcom_covercharge_link` int(11) NOT NULL default '0',
    `upcom_covercharge_name` varchar(60) NOT NULL default '',
    `upcom_css` text NOT NULL,
    `upcom_dateformat` varchar(140) NOT NULL default '',
    `upcom_dateformat2` varchar(140) NOT NULL default '',
    `upcom_delim` varchar(250) NOT NULL default '',
    `upcom_gigband_link` varchar(30) NOT NULL default '0',
    `upcom_gigband_popup` int(11) NOT NULL default '0',
    `upcom_gigdate_hover` int(11) NOT NULL default '0',
    `upcom_gigdate_hover_bandname` int(11) NOT NULL default '0',
    `upcom_gigdate_hover_cityst` int(11) NOT NULL default '0',
    `upcom_gigdate_hover_covercharge` int(11) NOT NULL default '0',
    `upcom_gigdate_hover_date` int(11) NOT NULL default '0',
    `upcom_gigdate_hover_gigtitle` int(11) NOT NULL default '0',
    `upcom_gigdate_hover_notes` int(11) NOT NULL default '0',
    `upcom_gigdate_hover_time` int(11) NOT NULL default '0',
    `upcom_gigdate_hover_venue` int(11) NOT NULL default '0',
    `upcom_gigdate_link` int(11) NOT NULL default '0',
    `upcom_gigdate2_hover` int(11) NOT NULL default '0',
    `upcom_gigdate2_hover_bandname` int(11) NOT NULL default '0',
    `upcom_gigdate2_hover_cityst` int(11) NOT NULL default '0',
    `upcom_gigdate2_hover_covercharge` int(11) NOT NULL default '0',
    `upcom_gigdate2_hover_date` int(11) NOT NULL default '0',
    `upcom_gigdate2_hover_gigtitle` int(11) NOT NULL default '0',
    `upcom_gigdate2_hover_notes` int(11) NOT NULL default '0',
    `upcom_gigdate2_hover_time` int(11) NOT NULL default '0',
    `upcom_gigdate2_hover_venue` int(11) NOT NULL default '0',
    `upcom_gigdate2_link` int(11) NOT NULL default '0',
    `upcom_giginfo_link` int(11) NOT NULL default '0',
    `upcom_gigtime_link` int(11) NOT NULL default '0',
    `upcom_gigtitle_hover` int(11) NOT NULL default '0',
    `upcom_gigtitle_hover_bandname` int(11) NOT NULL default '0',
    `upcom_gigtitle_hover_date` int(11) NOT NULL default '0',
    `upcom_gigtitle_hover_time` int(11) NOT NULL default '0',
    `upcom_gigtitle_hover_venue` int(11) NOT NULL default '0',
    `upcom_gigtitle_link` int(11) NOT NULL default '0',
    `upcom_gigvenue_link` varchar(30) NOT NULL default '0',
    `upcom_gigvenue_popup` int(11) NOT NULL default '0',
    `upcom_hover_params` text NOT NULL,
    `upcom_limit` int(11) NOT NULL default '0',
    `upcom_location_link` varchar(50) NOT NULL default '',
    `upcom_location_popup` varchar(50) NOT NULL default '',
    `upcom_maplink_name` varchar(120) NOT NULL default '0',
    `upcom_text` text NOT NULL,
    `upcom_ticketlink_name` varchar(120) NOT NULL default '0',
    `upcom_timeformat` varchar(140) NOT NULL default '',
    `upcom_group_days` int(11) NOT NULL default '0',
    `upcom_hrule` int(11) NOT NULL default '1',
    `vcal_header` varchar(100) NOT NULL default '',
    `vcal_header_alist` varchar(240) NOT NULL default '0',
    `list_gigtitle_name` varchar(50) NOT NULL default '0',
    `alist_gigtitle_name` varchar(50) NOT NULL default '0',
    `upcom_gigtitle_name` varchar(50) NOT NULL default '0',
    `minical_gigtitle_name` varchar(50) NOT NULL default '0',
     PRIMARY KEY  (`active`)
    );
    
CREATE TABLE  `#__gigcal_list_fields` (
    `id` int(11) NOT NULL auto_increment,
    `fieldname` varchar(30) NOT NULL default '',
    `ordering` int(11) NOT NULL default '0',
    `published` int(11) NOT NULL default '0',
    PRIMARY KEY  (`id`)
    );

CREATE TABLE  `#__gigcal_menu_fields` (
    `id` int(11) NOT NULL auto_increment,
    `fieldname` varchar(50) NOT NULL default '',
    `ordering` int(11) NOT NULL default '0',
    `published` int(11) NOT NULL default '0',
    PRIMARY KEY  (`id`)
    );
   
CREATE TABLE  `#__gigcal_upcom_fields` (
    `id` int(11) NOT NULL auto_increment,
    `fieldname` varchar(30) NOT NULL default '',
    `ordering` int(11) NOT NULL default '0',
    `published` int(11) NOT NULL default '0',
    PRIMARY KEY  (`id`)
    );              

CREATE VIEW `#__gigcal_gigs_import` AS SELECT 
    `id`, concat(`band_id`, '|', `venue_id`, '|', `gigdate`) AS `gigname` 
    FROM `#__gigcal_gigs`;


INSERT INTO `#__gigcal_alist_fields` (`id`, `fieldname`, `ordering`, `published`) VALUES (1, 'gigDate', 1, 1);
INSERT INTO `#__gigcal_alist_fields` (`id`, `fieldname`, `ordering`, `published`) VALUES (2, 'gigTitle', 2, 1);
INSERT INTO `#__gigcal_alist_fields` (`id`, `fieldname`, `ordering`, `published`) VALUES (3, 'gigBand', 3, 1);
INSERT INTO `#__gigcal_alist_fields` (`id`, `fieldname`, `ordering`, `published`) VALUES (4, 'gigVenue', 4, 1);
INSERT INTO `#__gigcal_alist_fields` (`id`, `fieldname`, `ordering`, `published`) VALUES (5, 'Country', 5, 1);
INSERT INTO `#__gigcal_alist_fields` (`id`, `fieldname`, `ordering`, `published`) VALUES (6, 'Location', 6, 1);
INSERT INTO `#__gigcal_alist_fields` (`id`, `fieldname`, `ordering`, `published`) VALUES (7, 'Link to Map', 7, 1);
INSERT INTO `#__gigcal_alist_fields` (`id`, `fieldname`, `ordering`, `published`) VALUES (8, 'gigTime', 9, 0);
INSERT INTO `#__gigcal_alist_fields` (`id`, `fieldname`, `ordering`, `published`) VALUES (9, 'Link to online Ticket Sales', 10, 0);
INSERT INTO `#__gigcal_alist_fields` (`id`, `fieldname`, `ordering`, `published`) VALUES (10, 'gigCover Charge', 11, 0);
INSERT INTO `#__gigcal_alist_fields` (`id`, `fieldname`, `ordering`, `published`) VALUES (11, 'gigNotes/Info', 8, 1);
INSERT INTO `#__gigcal_alist_fields` (`id`, `fieldname`, `ordering`, `published`) VALUES (12, 'iCal Link', 12, 0);
INSERT INTO `#__gigcal_alist_fields` (`id`, `fieldname`, `ordering`, `published`) VALUES (13, 'gigDate2', 14, 0);
INSERT INTO `#__gigcal_alist_fields` (`id`, `fieldname`, `ordering`, `published`) VALUES (14, 'vCal Link', 13, 0);

INSERT INTO `#__gigcal_cal_fields` (`id`, `fieldname`, `ordering`, `published`) VALUES (1, 'gigDate', 5, 0);
INSERT INTO `#__gigcal_cal_fields` (`id`, `fieldname`, `ordering`, `published`) VALUES (2, 'gigTitle', 1, 1);
INSERT INTO `#__gigcal_cal_fields` (`id`, `fieldname`, `ordering`, `published`) VALUES (3, 'gigBand', 2, 1);
INSERT INTO `#__gigcal_cal_fields` (`id`, `fieldname`, `ordering`, `published`) VALUES (4, 'gigVenue', 3, 1);
INSERT INTO `#__gigcal_cal_fields` (`id`, `fieldname`, `ordering`, `published`) VALUES (5, 'Country', 5, 1);
INSERT INTO `#__gigcal_cal_fields` (`id`, `fieldname`, `ordering`, `published`) VALUES (6, 'Location', 4, 1);
INSERT INTO `#__gigcal_cal_fields` (`id`, `fieldname`, `ordering`, `published`) VALUES (7, 'Link to Map', 7, 0);
INSERT INTO `#__gigcal_cal_fields` (`id`, `fieldname`, `ordering`, `published`) VALUES (8, 'gigTime', 8, 0);
INSERT INTO `#__gigcal_cal_fields` (`id`, `fieldname`, `ordering`, `published`) VALUES (9, 'Link to online Ticket Sales', 9, 0);
INSERT INTO `#__gigcal_cal_fields` (`id`, `fieldname`, `ordering`, `published`) VALUES (10, 'gigCover Charge', 10, 0);
INSERT INTO `#__gigcal_cal_fields` (`id`, `fieldname`, `ordering`, `published`) VALUES (11, 'gigNotes/Info', 11, 0);
INSERT INTO `#__gigcal_cal_fields` (`id`, `fieldname`, `ordering`, `published`) VALUES (12, 'gigDate2', 12, 0);

INSERT INTO `#__gigcal_list_fields` (`id`, `fieldname`, `ordering`, `published`) VALUES (1, 'gigDate', 1, 1);
INSERT INTO `#__gigcal_list_fields` (`id`, `fieldname`, `ordering`, `published`) VALUES (2, 'gigTitle', 2, 1);
INSERT INTO `#__gigcal_list_fields` (`id`, `fieldname`, `ordering`, `published`) VALUES (3, 'gigBand', 4, 1);
INSERT INTO `#__gigcal_list_fields` (`id`, `fieldname`, `ordering`, `published`) VALUES (4, 'gigVenue', 5, 1);
INSERT INTO `#__gigcal_list_fields` (`id`, `fieldname`, `ordering`, `published`) VALUES (5, 'Country', 7, 1);
INSERT INTO `#__gigcal_list_fields` (`id`, `fieldname`, `ordering`, `published`) VALUES (6, 'Location', 6, 1);
INSERT INTO `#__gigcal_list_fields` (`id`, `fieldname`, `ordering`, `published`) VALUES (7, 'Link to Map', 8, 1);
INSERT INTO `#__gigcal_list_fields` (`id`, `fieldname`, `ordering`, `published`) VALUES (8, 'gigTime', 3, 1);
INSERT INTO `#__gigcal_list_fields` (`id`, `fieldname`, `ordering`, `published`) VALUES (9, 'Link to online Ticket Sales', 9, 1);
INSERT INTO `#__gigcal_list_fields` (`id`, `fieldname`, `ordering`, `published`) VALUES (10, 'gigCover Charge', 10, 0);
INSERT INTO `#__gigcal_list_fields` (`id`, `fieldname`, `ordering`, `published`) VALUES (11, 'gigNotes/Info', 11, 1);
INSERT INTO `#__gigcal_list_fields` (`id`, `fieldname`, `ordering`, `published`) VALUES (12, 'iCal Link', 12, 0);
INSERT INTO `#__gigcal_list_fields` (`id`, `fieldname`, `ordering`, `published`) VALUES (13, 'gigDate2', 14, 0);
INSERT INTO `#__gigcal_list_fields` (`id`, `fieldname`, `ordering`, `published`) VALUES (14, 'vCal Link', 13, 0);

INSERT INTO `#__gigcal_menu_fields` (`id`, `fieldname`, `ordering`, `published`) VALUES (1, 'List View', 1, 1);
INSERT INTO `#__gigcal_menu_fields` (`id`, `fieldname`, `ordering`, `published`) VALUES (2, 'Calendar View', 2, 1);
INSERT INTO `#__gigcal_menu_fields` (`id`, `fieldname`, `ordering`, `published`) VALUES (3, 'RSS Feeds', 3, 1);
INSERT INTO `#__gigcal_menu_fields` (`id`, `fieldname`, `ordering`, `published`) VALUES (4, 'Archived List View', 4, 1);
INSERT INTO `#__gigcal_menu_fields` (`id`, `fieldname`, `ordering`, `published`) VALUES (5, 'Bands List', 5, 1);
INSERT INTO `#__gigcal_menu_fields` (`id`, `fieldname`, `ordering`, `published`) VALUES (6, 'Venues List', 6, 1);

INSERT INTO `#__gigcal_upcom_fields` (`id`, `fieldname`, `ordering`, `published`) VALUES (1, 'gigDate', 1, 1);
INSERT INTO `#__gigcal_upcom_fields` (`id`, `fieldname`, `ordering`, `published`) VALUES (2, 'gigTitle', 2, 1);

INSERT INTO `#__gigcal_upcom_fields` (`id`, `fieldname`, `ordering`, `published`) VALUES (3, 'gigBand', 3, 1);
INSERT INTO `#__gigcal_upcom_fields` (`id`, `fieldname`, `ordering`, `published`) VALUES (4, 'gigVenue', 4, 1);
INSERT INTO `#__gigcal_upcom_fields` (`id`, `fieldname`, `ordering`, `published`) VALUES (5, 'Country', 8, 0);
INSERT INTO `#__gigcal_upcom_fields` (`id`, `fieldname`, `ordering`, `published`) VALUES (6, 'Location', 5, 1);
INSERT INTO `#__gigcal_upcom_fields` (`id`, `fieldname`, `ordering`, `published`) VALUES (7, 'Link to Map', 9, 0);
INSERT INTO `#__gigcal_upcom_fields` (`id`, `fieldname`, `ordering`, `published`) VALUES (8, 'gigTime', 7, 0);
INSERT INTO `#__gigcal_upcom_fields` (`id`, `fieldname`, `ordering`, `published`) VALUES (9, 'Link to online Ticket Sales', 6, 1);
INSERT INTO `#__gigcal_upcom_fields` (`id`, `fieldname`, `ordering`, `published`) VALUES (10, 'gigCover Charge', 10, 0);
INSERT INTO `#__gigcal_upcom_fields` (`id`, `fieldname`, `ordering`, `published`) VALUES (11, 'gigNotes/Info', 11, 0);
INSERT INTO `#__gigcal_upcom_fields` (`id`, `fieldname`, `ordering`, `published`) VALUES (12, 'gigDate2', 12, 0);

INSERT INTO `#__gigcal_config` (`active`, `upcom_delim`, `upcom_limit`, `upcom_dateformat`, `upcom_dateformat2`, `upcom_timeformat`, `upcom_css`, 
`upcom_text`, `upcom_hover_params`, `upcom_gigdate_link`, `upcom_gigdate_hover`, `upcom_gigtime_link`, `upcom_gigdate_hover_bandname`, 
`upcom_gigdate_hover_venue`, `upcom_gigdate_hover_cityst`, `upcom_gigdate_hover_date`, `upcom_gigdate_hover_time`, `upcom_gigdate_hover_covercharge`, 
`upcom_gigdate_hover_notes`, `upcom_gigdate2_link`, `upcom_gigdate2_hover`, `upcom_gigdate2_hover_bandname`, `upcom_gigdate2_hover_venue`, 
`upcom_gigdate2_hover_cityst`, `upcom_gigdate2_hover_date`, `upcom_gigdate2_hover_time`, `upcom_gigdate2_hover_covercharge`, `upcom_gigdate2_hover_notes`, 
`list_gigdate2_link`, `list_gigdate2_hover`, `list_gigdate2_hover_bandname`, `list_gigdate2_hover_venue`, `list_gigdate2_hover_cityst`, 
`list_gigdate2_hover_date`, `list_gigdate2_hover_time`, `list_gigdate2_hover_covercharge`, `list_gigdate2_hover_notes`, `alist_gigdate2_link`, 
`alist_gigdate2_hover`, `alist_gigdate2_hover_bandname`, `alist_gigdate2_hover_venue`, `alist_gigdate2_hover_cityst`, `alist_gigdate2_hover_date`, 
`alist_gigdate2_hover_time`, `alist_gigdate2_hover_covercharge`, `alist_gigdate2_hover_notes`, `upcom_gigband_popup`, `upcom_gigband_link`, 
`upcom_gigvenue_popup`, `upcom_gigvenue_link`, `upcom_maplink_name`, `upcom_ticketlink_name`, `upcom_covercharge_name`, `upcom_covercharge_link`, 
`upcom_giginfo_link`, `upcom_location_popup`, `upcom_country_popup`, `upcom_location_link`, `upcom_country_link`, `list_dateformat`, `list_dateformat2`, 
`list_timeformat`, `list_css`, `list_text`, `list_hover_params`, `list_gigdate_link`, `list_gigdate_hover`, `list_gigtime_link`, 
`list_gigdate_hover_bandname`, `list_gigdate_hover_venue`, `list_gigdate_hover_cityst`, `list_gigdate_hover_date`, `list_gigdate_hover_time`, 
`list_gigdate_hover_covercharge`, `list_gigdate_hover_notes`, `list_gigband_popup`, `list_gigband_link`, `list_gigvenue_popup`, `list_gigvenue_link`, 
`list_maplink_name`, `list_ticketlink_name`, `list_covercharge_name`, `list_covercharge_link`, `list_giginfo_link`, `list_location_popup`, 
`list_country_popup`, `list_location_link`, `list_country_link`, `alist_dateformat`, `alist_dateformat2`, `alist_timeformat`, `alist_css`, 
`alist_text`, `alist_hover_params`, `alist_gigdate_link`, `alist_gigdate_hover`, `alist_gigtime_link`, `alist_gigdate_hover_bandname`, 
`alist_gigdate_hover_venue`, `alist_gigdate_hover_cityst`, `alist_gigdate_hover_date`, `alist_gigdate_hover_time`, `alist_gigdate_hover_covercharge`, 
`alist_gigdate_hover_notes`, `alist_gigband_popup`, `alist_gigband_link`, `alist_gigvenue_link`, `alist_gigvenue_popup`, `alist_maplink_name`, 
`alist_ticketlink_name`, `alist_covercharge_name`, `alist_covercharge_link`, `alist_giginfo_link`, `alist_location_popup`, `alist_country_popup`, 
`alist_location_link`, `alist_country_link`, `gignotes_header`, `gigDate_header`, `gigDate2_header`, `Country_header`, `State_header`, `city_header`, 
`gigVenue_header`, `ticket_header`, `map_header`, `gigtitle_header`, `covercharge_header`, `gigTime_header`, `gigBand_header`, `Location_header`, 
`list_ical_link_name`, `list_vcal_link_name`, `ical_header`, `vcal_header`, `gignotes_header_alist`, `gigDate_header_alist`, `gigDate2_header_alist`, 
`Country_header_alist`, `State_header_alist`, `city_header_alist`, `gigVenue_header_alist`, `ticket_header_alist`, `map_header_alist`, 
`gigtitle_header_alist`, `covercharge_header_alist`, `gigTime_header_alist`, `gigBand_header_alist`, `Location_header_alist`, `vcal_header_alist`, 
`ical_header_alist`, `alist_ical_link_name`, `alist_vcal_link_name`, `alist_ical_header`, `alist_vcal_header`, `cal_date_jumper`, `cal_dateformat`, 
`cal_dateformat2`, `cal_timeformat`, `cal_css`, `cal_text`, `cal_hover_params`, `cal_gigdate_link`, `cal_gigdate_hover`, `cal_gigtime_link`, 
`cal_gigdate_hover_bandname`, `cal_gigdate_hover_venue`, `cal_gigdate_hover_cityst`, `cal_gigdate_hover_date`, `cal_gigdate_hover_time`, 
`cal_gigdate_hover_covercharge`, `cal_gigdate_hover_notes`, `cal_gigband_popup`, `cal_gigband_link`, `cal_gigvenue_popup`, `cal_gigvenue_link`, 
`cal_maplink_name`, `cal_ticketlink_name`, `cal_covercharge_name`, `cal_covercharge_link`, `cal_giginfo_link`, `cal_location_popup`, 
`cal_country_popup`, `cal_location_link`, `cal_country_link`, `cal_ical_link_name`, `cal_vcal_link_name`, `cal_gigdate2_link`, `cal_gigdate2_hover`, 
`cal_gigdate2_hover_bandname`, `cal_gigdate2_hover_venue`, `cal_gigdate2_hover_cityst`, `cal_gigdate2_hover_date`, `cal_gigdate2_hover_time`, 
`cal_gigdate2_hover_covercharge`, `cal_gigdate2_hover_notes`, `cal_delim`, `cal_leftarrowmark`, `cal_rightarrowmark`, `details_band`, `details_venue`, 
`details_gig`, `details_timeformat`, `details_dateformat`, `cal_january`, `cal_february`, `cal_march`, `cal_april`, `cal_may`, `cal_june`, `cal_july`, 
`cal_august`, `cal_september`, `cal_october`, `cal_november`, `cal_december`, `cal_monday`, `cal_tuesday`, `cal_wendsday`, `cal_thursday`, `cal_friday`, 
`cal_saturday`, `cal_sunday`, `minical_dateformat`, `minical_timeformat`, `minical_text`, `minical_css`, `minical_hover_params`, `minical_hover`, 
`minical_hover_bandname`, `minical_hover_venue`, `minical_hover_cityst`, `minical_hover_date`, `minical_hover_time`, `minical_hover_covercharge`, 
`minical_hover_notes`, `minical_link`, `minical_gigcal_link_text`, `minical_gigcal_link_to`, `rss_all`, `rss_band`, `rss_venue`, `gen_css`, `menu_rss`, 
`menu_list`, `menu_alist`, `menu_cal`, `menu_bandslist`, `menu_venueslist`, `menu_delim`, `menu_details`, `menu_top`, `menu_bottom`, `details_css`,
`list_gigtitle_name`, `alist_gigtitle_name`, `upcom_gigtitle_name`, `minical_gigtitle_name`) 
VALUES (1, '-', 5, '%wkdy, %mon. %ordday', '%wkdy, %mon %ordday %year', '%hour:%minute %ampm', 
'ul.mod_gigcal_upcom {\r\n  font-weight: bold;\r\n  font-family: Sans-serif;\r\n  font-size: 10px;\r\n  list-style: none;\r\n}\r\n\r\nli.mod_gigcal_upcom {\r\n  list-style-type: none;\r\n}\r\n\r\n.mod_gigcal_upcom_introtext {\r\n  background-color: #FFF;\r\n  color: #000;\r\n  text-align: center;\r\n  display: block;\r\n}', 
'', 'WIDTH, 350, CAPCOLOR, ''#630101'', FGCOLOR, ''#f2f2f2'', BGCOLOR, ''#ebcdcd'', TEXTCOLOR, ''#000000'', OFFSETY, 20', 
1, 1, 0, 1, 1, 1, 0, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 1, 1, 1, 1, 0, 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 0, 1, 1, 
'nothing', 1, 'nothing', 'Map', 'Buy Tix', 'Price:', 0, 0, '', '', 'nothing', 'nothing', '%wkdy, %mon %ordday %year', '%wkdy, %mon %ordday %year', 
'%hour:%minute %ampm', 'table.gigcal_list_table{\r\n    font-size:.9em;\r\n    padding: 6px;\r\n}\r\n.gigcal_list_table tbody td{\r\n    text-align:center;\r\n    padding: 10px;\r\n}\r\n.gigcal_list_table thead th, .gigcal_list_table tfoot th{\r\n    background:#F0F0F0;\r\n    padding: 0px;\r\n    text-align: center;\r\n    font-style: bold;\r\n}\r\n.tour_date {}\r\n.tour_day {}\r\n.tour_city {}\r\n.tour_state {}\r\n.tour_country {}\r\n.tour_venue {}\r\n.tour_ticket {}\r\n.tour_map {}\r\n.tour_price {}\r\n.tour_time {}\r\n.tour_download{}\r\n.tour_notes, .tour_notes th {text-align:left;}', 
'Edit this intro text in the gigConfig - List Settings', 'WIDTH, 500, CAPCOLOR, ''#630101'', FGCOLOR, ''#f2f2f2'', BGCOLOR, ''#ebcdcd'', TEXTCOLOR, ''#000000'', OFFSETY, 20', 1, 1, 0, 1, 1, 1, 0, 1, 1, 1, 1, 
'bandwebsite', 1, 'venuewebsite', 'Map', 'Buy Tix', 'Price:', 0, 0, '', '', 'nothing', 'nothing', '%wkdy, %mon %ordday %year', '%wkdy, %mon %ordday %year', 
'', 'table.gigcal_alist_table{\r\n    font-size:.9em;\r\n    padding: 6px;\r\n}\r\n.gigcal_alist_table tbody td{\r\n    text-align:center;\r\n    padding: 10px;\r\n}\r\n.gigcal_alist_table thead th,\r\n.gigcal_alist_table tfoot th{\r\n    background:#F0F0F0;\r\n    padding: 0px;\r\n    text-align: center;\r\n    font-style: bold;\r\n}\r\n.tour_date {}\r\n.tour_day {}\r\n.tour_city {}\r\n.tour_state {}\r\n.tour_country {}\r\n.tour_venue {}\r\n.tour_ticket {}\r\n.tour_map {}\r\n.tour_price {}\r\n.tour_time {}\r\n.tour_download{}\r\n.tour_notes, .tour_notes th {text-align:left;}', 
'Edit this intro text in the gigConfig - Archived List Settings', 'WIDTH, 500, CAPCOLOR, ''#630101'', FGCOLOR, ''#f2f2f2'', BGCOLOR, ''#ebcdcd'', TEXTCOLOR, ''#000000'', OFFSETY, 20', 1, 1, 0, 1, 1, 1, 0, 0, 0, 1, 1, 
'bandwebsite', 'venuedetails', 1, 'Map', 'Buy Tix', 'Price:', 0, 0, '', '', 'nothing', 'nothing', 'Notes/Info', 'Date', 'Date', 'Country', '', '', 
'Venue', 'Tickets', 'Map Link', 'Title', 'Cover', 'Time', 'Band', 'City, ST', 'iCal', 'vCal', 'iCal file', 'vCal file', 'Notes / Info', 'Date', 'Date', 
'Country', '', '', 'Venue', 'Tickets', 'Map Link', 'Title', 'Cover', 'Time', 'Band', 'City, ST', 'vCal', 'iCal', 'iCal', 'vCal', '', '', 1, 
'%wkdy, %mon %ordday %year', '%wkdy, %mon %ordday %year', '%hour:%minute %ampm', '/*= GigCalendar 1.0 | Default Template\r\n\r\n Developed for nuthin'' werked by Richard Medek, http://richardmedek.com.\r\n\r\n NOTES:\r\n - Class selectors are prefixed by "gigcal_" to avoid overwriting template styles.\r\n - Most rules here rely on specificity and use descendent selectors rather than specific classes.\r\n - These templates do not yet support all legacy (5.0 level) browsers, but are very, very close.\r\n\r\n See readme.txt for more detailed instruction and comments.\r\n*/\r\n\r\n\r\n\r\n/*= overall GigCal styles */\r\n\r\n#gigcal * { /* used to reset whitespace */\r\n margin: 0;\r\n padding: 0;\r\n}\r\n\r\n#gigcal {\r\n border: solid 1px #bfd0d9;\r\n background: #f8f8f8;\r\n padding: 1em;\r\n font: 86%/1.5em "lucida grande", verdana, arial, sans-serif; /* adjust this percentage to fit your template */\r\n}\r\n\r\n\r\n\r\n/*= introduction text container */\r\n\r\n#gigcal_intro {\r\n padding-bottom: .5em;\r\n font-size: 1.1em;\r\n}\r\n\r\n\r\n\r\n/*= calendar navigation */\r\n\r\n#gigcal_navigation {\r\n margin: 3em 0 0 0;\r\n text-align: center;\r\n}\r\n\r\n#gigcal_navigation:after {\r\n content: ".";\r\n display: block;\r\n height: 0;\r\n visibility: hidden;\r\n clear: both;\r\n}\r\n\r\n#gigcal_navigation p {\r\n font: bold 1.2em/1.8em "lucida grande", verdana, arial, sans-serif;\r\n color: #333;\r\n background: #e9eeef;\r\n border: solid 1px #bfd0d9;\r\n width: 8em;\r\n height: 1.8em;\r\n float: left;\r\n margin-right: 1px;\r\n}\r\n\r\n#gigcal_navigation a {\r\n text-decoration: none;\r\n color: #666;\r\n font: normal 1.3em/1em "lucida grande", verdana, arial, sans-serif;\r\n margin: 0 .5em;\r\n}\r\n\r\n#gigcal_navigation a:hover {\r\n color: #999;\r\n}\r\n\r\n#gigcal form {\r\n text-align: right;\r\n}\r\n\r\n#gigcal select, #gigcal input {\r\n font-size: 1.2em;\r\n}\r\n\r\n#gigcal select {\r\n width: 6em;\r\n}\r\n\r\n#gigcal input#submit {\r\n width: 4em;\r\n}\r\n\r\n\r\n\r\n/*= global calendar styles */\r\n\r\n#gigcal_wrapper {\r\n border: solid 1px #bfd0d9;\r\n margin-top: 1em;\r\n padding: .2em;\r\n background: #fff;\r\n}\r\n\r\n#gigcal table {\r\n width: 100%;\r\n border-collapse: collapse;\r\n}\r\n\r\n#gigcal td {\r\n width: 14%;\r\n height: 6em;\r\n vertical-align: top;\r\n text-align: right;\r\n padding: .2em;\r\n border: solid 1px #fff;\r\n background: #f2f2f2;\r\n}\r\n\r\n#gigcal td span {\r\n color: #666;\r\n}\r\n\r\n#gigcal caption {\r\n font: bold 1.5em/2em "lucida grande", verdana, arial, sans-serif;\r\n height: 2.1em;\r\n color: #630101;\r\n background: #fff;\r\n text-align: center;\r\n}\r\n\r\n\r\n\r\n/*= days of the week header  */\r\n\r\n#gigcal th {\r\n color: #630101;\r\n font-weight: bold;\r\n text-align: center;\r\n height: 2em;\r\n line-height: 2em;\r\n background: #ebcdcd;\r\n border: solid 1px #fff;\r\n}\r\n\r\n#gigcal th span { /* makes day of the week abbreviated */\r\n display: none;\r\n}\r\n\r\n\r\n\r\n/*= empty cells */\r\n\r\n#gigcal td.gigcal_empty {\r\n background: #e3e3e3;\r\n}\r\n\r\n\r\n\r\n/*= weekends */\r\n\r\n#gigcal td.gigcal_weekend {\r\n background: #e9eeef;\r\n}\r\n\r\n\r\n\r\n/*= current date */\r\n\r\n#gigcal td.gigcal_current, #gigcal td.gigcal_weekend_current {\r\n background: #f5eded;\r\n}\r\n\r\n#gigcal td.gigcal_current span, #gigcal td.gigcal_weekend_current span {\r\n color: #630101;\r\n font-weight: bold;\r\n}\r\n\r\n\r\n\r\n/*= events  */\r\n\r\n#gigcal .gigcal_event {\r\n min-height: 6em; /* this should match "#gigcal tbody tr" height above */\r\n margin: -.2em; /* overwrites default cell padding so bgcolor extends to edges */\r\n padding: .2em; /* puts padding back */\r\n}\r\n\r\n#gigcal dl {\r\n text-align: left;\r\n padding: 0 .2em .2em 9px;\r\n background: url(http://www.gigcalendar.net/hosted_images/default_marker.png) no-repeat 2px .15em; /* NOTE: At smaller resolutions IE''s lack of png transparency is more obvious. */ \r\n}\r\n\r\n#gigcal dt {\r\n display: none;\r\n}\r\n\r\n#gigcal dd, #gigcal dd a {\r\n color: #556063;\r\n font-weight: bold;\r\n text-decoration: none;\r\n line-height: 1em;\r\n margin-bottom: .5em;\r\n}\r\n\r\n#gigcal dd a:hover {\r\n color: #819296;\r\n}\r\n\r\n#gigcal td .gigcal_event:hover, #gigcal td div.sfhover { /* "sfhover" class added for Internet Explorer :hover support. See readme.txt for implementation. */\r\n background: #d1dddf;\r\n}\r\n\r\n#gigcal td.gigcal_weekend .gigcal_event:hover, #gigcal td.gigcal_weekend div.sfhover { /* "sfhover" class added for Internet Explorer :hover support. See readme.txt for implementation. */\r\n background: #d1dddf;\r\n}\r\n\r\n\r\n\r\n/*= Internet Explorer 6.0 hacks */\r\n\r\n* html #gigcal_navigation { height: 1%; }\r\n* html #gigcal_navigation p { line-height: 1.5em; height: 1.7em; }\r\n* html #gigcal .gigcal_event { height: 6em; }\r\n\r\n\r\n/*= Internet Explorer 5 hacks */\r\n\r\n#gigcal table { font-size: 105%; voice-family: "\\"}\\""; voice-family: inherit; font-size: 100%; }\r\n#gigcal table { width: auto; voice-family: "\\"}\\""; voice-family: inherit; width: 100%; }\r\n', 
'Edit this intro text in the gigConfig - Calendar Settings', 'WIDTH, 400, CAPCOLOR, ''#630101'', FGCOLOR, ''#f2f2f2'', BGCOLOR, ''#ebcdcd'', TEXTCOLOR, ''#000000'', OFFSETY, 20', 1, 1, 0, 1, 1, 1, 0, 1, 1, 1, 1, 
'gigdetails', 1, 'gigdetails', 'Click here for directions!', 'Buy Tix', 'Price:', 0, 0, '', '', 'gigdetails', 'gigdetails', '', '', 1, 
1, 1, 1, 1, 0, 1, 1, 1, '', '&lt;', '&gt;', '&lt;div class="contentheading"&gt;Details for &lt;$bandname$&gt;&lt;/div&gt;\r\n\r\n&lt;table width="85%" border="0" cellspacing="0" cellpadding="0" align="center"&gt;\r\n  &lt;tr&gt;\r\n    &lt;td class="sectiontableheader"&gt;Band Name:&lt;/td&gt;\r\n    &lt;td class="sectiontableheader"&gt;&lt;$bandname$&gt;&lt;/td&gt;\r\n  &lt;/tr&gt;\r\n  &lt;tr&gt;\r\n    &lt;td class="sectiontableentry1"&gt;Band Website:&lt;/td&gt;\r\n    &lt;td class="sectiontableentry1"&gt;&lt;a href="&lt;$website$&gt;" target="_blank"&gt;&lt;$website$&gt;&lt;/a&gt;&lt;/td&gt;\r\n  &lt;/tr&gt;\r\n  &lt;tr&gt;\r\n    &lt;td class="sectiontableentry2"&gt;Band Contact Name:&lt;/td&gt;\r\n    &lt;td class="sectiontableentry2"&gt;&lt;$contactname$&gt;&lt;/td&gt;\r\n  &lt;/tr&gt;\r\n  &lt;tr&gt;\r\n    &lt;td class="sectiontableentry1"&gt;Band Contact Email:&lt;/td&gt;\r\n    &lt;td class="sectiontableentry1"&gt;&lt;a href="mailto:&lt;$contactemail$&gt;"&gt;&lt;$contactemail$&gt;&lt;/a&gt;&lt;/td&gt;\r\n  &lt;/tr&gt;\r\n  &lt;tr&gt;\r\n    &lt;td class="sectiontableentry2"&gt;Band Contact Phone: &lt;/td&gt;\r\n    &lt;td class="sectiontableentry2"&gt;&lt;$contactphone$&gt;&lt;/td&gt;\r\n  &lt;/tr&gt;\r\n  &lt;tr&gt;\r\n    &lt;td class="sectiontableentry1"&gt;Band City:&lt;/td&gt;\r\n    &lt;td class="sectiontableentry1"&gt;&lt;$city$&gt;&lt;/td&gt;\r\n  &lt;/tr&gt;\r\n  &lt;tr&gt;\r\n    &lt;td class="sectiontableentry2"&gt;Band State:&lt;/td&gt;\r\n    &lt;td class="sectiontableentry2"&gt;&lt;$state$&gt;&lt;/td&gt;\r\n  &lt;/tr&gt;\r\n  &lt;tr&gt;\r\n    &lt;td class="sectiontableentry1"&gt;Band Info/Notes:&lt;/td&gt;\r\n    &lt;td class="sectiontableentry1"&gt;&lt;$notes$&gt;&lt;/td&gt;\r\n  &lt;/tr&gt;\r\n&lt;/table&gt;', 
'&lt;div class="contentheading"&gt;Details for &lt;$venuename$&gt;&lt;/div&gt;\r\n\r\n&lt;table width="85%" border="0" cellspacing="0" cellpadding="0" align="center"&gt;\r\n  &lt;tr&gt;\r\n    &lt;td class="sectiontableheader"&gt;Venue Name:&lt;/td&gt;\r\n    &lt;td class="sectiontableheader"&gt;&lt;$venuename$&gt;&lt;/td&gt;\r\n  &lt;/tr&gt;\r\n  &lt;tr&gt;\r\n    &lt;td class="sectiontableentry1"&gt;Venue Website:&lt;/td&gt;\r\n    &lt;td class="sectiontableentry1"&gt;&lt;a href="&lt;$website$&gt;" target="_blank"&gt;&lt;$website$&gt;&lt;/a&gt;&lt;/td&gt;\r\n  &lt;/tr&gt;\r\n  &lt;tr&gt;\r\n    &lt;td class="sectiontableentry2"&gt;Venue Contact Name:&lt;/td&gt;\r\n    &lt;td class="sectiontableentry2"&gt;&lt;$contactname$&gt;&lt;/td&gt;\r\n  &lt;/tr&gt;\r\n  &lt;tr&gt;\r\n    &lt;td class="sectiontableentry1"&gt;Venue Contact Email:&lt;/td&gt;\r\n    &lt;td class="sectiontableentry1"&gt;&lt;a href="mailto:&lt;$contactemail$&gt;"&gt;&lt;$contactemail$&gt;&lt;/a&gt;&lt;/td&gt;\r\n  &lt;/tr&gt;\r\n  &lt;tr&gt;\r\n    &lt;td class="sectiontableentry2"&gt;Venue Contact Phone: &lt;/td&gt;\r\n    &lt;td class="sectiontableentry2"&gt;&lt;$contactphone$&gt;&lt;/td&gt;\r\n  &lt;/tr&gt;\r\n  &lt;tr&gt;\r\n    &lt;td class="sectiontableentry1"&gt;Venue Fax:&lt;/td&gt;\r\n    &lt;td class="sectiontableentry1"&gt;&lt;$fax$&gt;&lt;/td&gt;\r\n  &lt;/tr&gt;\r\n  &lt;tr&gt;\r\n    &lt;td class="sectiontableentry2"&gt;Venue Address1:&lt;/td&gt;\r\n    &lt;td class="sectiontableentry2"&gt;&lt;$address1$&gt;&lt;/td&gt;\r\n  &lt;/tr&gt;\r\n  &lt;tr&gt;\r\n    &lt;td class="sectiontableentry1"&gt;Venue Address2:&lt;/td&gt;\r\n    &lt;td class="sectiontableentry1"&gt;&lt;$address2$&gt;&lt;/td&gt;\r\n  &lt;/tr&gt;\r\n  &lt;tr&gt;\r\n    &lt;td class="sectiontableentry2"&gt;Venue City:&lt;/td&gt;\r\n    &lt;td class="sectiontableentry2"&gt;&lt;$city$&gt;&lt;/td&gt;\r\n  &lt;/tr&gt;\r\n  &lt;tr&gt;\r\n    &lt;td class="sectiontableentry1"&gt;Venue State:&lt;/td&gt;\r\n    &lt;td class="sectiontableentry1"&gt;&lt;$state$&gt;&lt;/td&gt;\r\n  &lt;/tr&gt;\r\n  &lt;tr&gt;\r\n    &lt;td class="sectiontableentry2"&gt;Venue Zip:&lt;/td&gt;\r\n    &lt;td class="sectiontableentry2"&gt;&lt;$zip$&gt;&lt;/td&gt;\r\n  &lt;/tr&gt;\r\n  &lt;tr&gt;\r\n    &lt;td class="sectiontableentry1"&gt;Venue Country:&lt;/td&gt;\r\n    &lt;td class="sectiontableentry1"&gt;&lt;$country$&gt;&lt;/td&gt;\r\n  &lt;/tr&gt;\r\n  &lt;tr&gt;\r\n    &lt;td class="sectiontableentry2"&gt;Venue Info/Notes:&lt;/td&gt;\r\n    &lt;td class="sectiontableentry2"&gt;&lt;$info$&gt;&lt;/td&gt;\r\n  &lt;/tr&gt;\r\n  &lt;tr&gt;\r\n    &lt;td class="sectiontableentry1"&gt;Venue Map Link:&lt;/td&gt;\r\n    &lt;td class="sectiontableentry1"&gt;&lt;$maplinkstart$&gt;Click here for map and directions.&lt;$maplinkend$&gt;&lt;/td&gt;\r\n  &lt;/tr&gt;\r\n&lt;/table&gt;', 
'&lt;div class="contentheading"&gt;Gig Details&lt;/div&gt;\r\n\r\n&lt;table width="85%" border="0" cellspacing="0" cellpadding="0" align="center"&gt;\r\n  &lt;tr&gt;\r\n    &lt;td class="sectiontableheader"&gt;Gig Date: &lt;/td&gt;\r\n    &lt;td class="sectiontableheader"&gt;&lt;$gigdate$&gt;&lt;/td&gt;\r\n  &lt;/tr&gt;\r\n  
&lt;tr&gt;\r\n    &lt;td class="sectiontableheader"&gt;Gig Title: &lt;/td&gt;\r\n    &lt;td class="sectiontableheader"&gt;&lt;$gigtitle$&gt;&lt;/td&gt;\r\n  &lt;/tr&gt;\r\n  &lt;tr&gt;\r\n    &lt;td class="sectiontableentry1"&gt;Gig Time: &lt;/td&gt;\r\n    &lt;td class="sectiontableentry1"&gt;&lt;$gigtime$&gt;&lt;/td&gt;\r\n  &lt;/tr&gt;\r\n  &lt;tr&gt;\r\n    &lt;td class="sectiontableentry2"&gt;Cover Charge: &lt;/td&gt;\r\n    &lt;td class="sectiontableentry2"&gt;&lt;$covercharge$&gt;&lt;/td&gt;\r\n  &lt;/tr&gt;\r\n  &lt;tr&gt;\r\n    &lt;td class="sectiontableentry1"&gt;Online Ticket Sales Link: &lt;/td&gt;\r\n    &lt;td class="sectiontableentry1"&gt;&lt;$saleslink$&gt;&lt;/td&gt;\r\n  &lt;/tr&gt;\r\n  &lt;tr&gt;\r\n    &lt;td class="sectiontableentry2"&gt;Gig Info/Notes: &lt;/td&gt;\r\n    &lt;td class="sectiontableentry2"&gt;&lt;$giginfo$&gt;&lt;/td&gt;\r\n  &lt;/tr&gt;\r\n  &lt;tr&gt;\r\n    &lt;td class="sectiontableentry1"&gt;Map Link:&lt;/td&gt;\r\n    &lt;td class="sectiontableentry1"&gt;&lt;$maplinkstart$&gt;Click here for map and directions.&lt;$maplinkend$&gt;&lt;/td&gt;\r\n  &lt;/tr&gt;\r\n&lt;tr&gt;&lt;td&gt;&lt;br&gt;&lt;/td&gt;&lt;td&gt;&lt;br&gt;&lt;/td&gt;&lt;/tr&gt;\r\n  &lt;tr&gt;\r\n    &lt;td class="sectiontableheader"&gt;Band Name:&lt;/td&gt;\r\n    &lt;td class="sectiontableheader"&gt;&lt;$bandname$&gt;&lt;/td&gt;\r\n  &lt;/tr&gt;\r\n  &lt;tr&gt;\r\n    &lt;td class="sectiontableentry1"&gt;Band Website:&lt;/td&gt;\r\n    &lt;td class="sectiontableentry1"&gt;&lt;a href="&lt;$bandwebsite$&gt;" target="_blank"&gt;&lt;$bandwebsite$&gt;&lt;/a&gt;&lt;/td&gt;\r\n  &lt;/tr&gt;\r\n  &lt;tr&gt;\r\n    &lt;td class="sectiontableentry2"&gt;Band Contact Name:&lt;/td&gt;\r\n    &lt;td class="sectiontableentry2"&gt;&lt;$bandcontactname$&gt;&lt;/td&gt;\r\n  &lt;/tr&gt;\r\n  &lt;tr&gt;\r\n    &lt;td class="sectiontableentry1"&gt;Band Contact Email:&lt;/td&gt;\r\n    &lt;td class="sectiontableentry1"&gt;&lt;a href="mailto:&lt;$bandcontactemail$&gt;"&gt;&lt;$bandcontactemail$&gt;&lt;/a&gt;&lt;/td&gt;\r\n  &lt;/tr&gt;\r\n  &lt;tr&gt;\r\n    &lt;td class="sectiontableentry2"&gt;Band Contact Phone: &lt;/td&gt;\r\n    &lt;td class="sectiontableentry2"&gt;&lt;$bandcontactphone$&gt;&lt;/td&gt;\r\n  &lt;/tr&gt;\r\n  &lt;tr&gt;\r\n    &lt;td class="sectiontableentry1"&gt;Band City:&lt;/td&gt;\r\n    &lt;td class="sectiontableentry1"&gt;&lt;$bandcity$&gt;&lt;/td&gt;\r\n  &lt;/tr&gt;\r\n  &lt;tr&gt;\r\n    &lt;td class="sectiontableentry2"&gt;Band State:&lt;/td&gt;\r\n    &lt;td class="sectiontableentry2"&gt;&lt;$bandstate$&gt;&lt;/td&gt;\r\n  &lt;/tr&gt;\r\n  &lt;tr&gt;\r\n    &lt;td class="sectiontableentry1"&gt;Band Info/Notes:&lt;/td&gt;\r\n    &lt;td class="sectiontableentry1"&gt;&lt;$bandnotes$&gt;&lt;/td&gt;\r\n  &lt;/tr&gt;\r\n&lt;tr&gt;&lt;td&gt;&lt;br&gt;&lt;/td&gt;&lt;td&gt;&lt;br&gt;&lt;/td&gt;&lt;/tr&gt;\r\n  &lt;tr&gt;\r\n    &lt;td class="sectiontableheader"&gt;Venue Name:&lt;/td&gt;\r\n    &lt;td class="sectiontableheader"&gt;&lt;$venuename$&gt;&lt;/td&gt;\r\n  &lt;/tr&gt;\r\n  &lt;tr&gt;\r\n    &lt;td class="sectiontableentry1"&gt;Venue Website:&lt;/td&gt;\r\n    &lt;td class="sectiontableentry1"&gt;&lt;a href="&lt;$venuewebsite$&gt;" target="_blank"&gt;&lt;$venuewebsite$&gt;&lt;/a&gt;&lt;/td&gt;\r\n  &lt;/tr&gt;\r\n  &lt;tr&gt;\r\n    &lt;td class="sectiontableentry2"&gt;Venue Contact Name:&lt;/td&gt;\r\n    &lt;td class="sectiontableentry2"&gt;&lt;$venuecontactname$&gt;&lt;/td&gt;\r\n  &lt;/tr&gt;\r\n  &lt;tr&gt;\r\n    &lt;td class="sectiontableentry1"&gt;Venue Contact Email:&lt;/td&gt;\r\n    &lt;td class="sectiontableentry1"&gt;&lt;a href="mailto:&lt;$venuecontactemail$&gt;"&gt;&lt;$venuecontactemail$&gt;&lt;/a&gt;&lt;/td&gt;\r\n  &lt;/tr&gt;\r\n  &lt;tr&gt;\r\n    &lt;td class="sectiontableentry2"&gt;Venue Contact Phone: &lt;/td&gt;\r\n    &lt;td class="sectiontableentry2"&gt;&lt;$venuecontactphone$&gt;&lt;/td&gt;\r\n  &lt;/tr&gt;\r\n  &lt;tr&gt;\r\n    &lt;td class="sectiontableentry1"&gt;Venue Fax:&lt;/td&gt;\r\n    &lt;td class="sectiontableentry1"&gt;&lt;$venuefax$&gt;&lt;/td&gt;\r\n  &lt;/tr&gt;\r\n  &lt;tr&gt;\r\n    &lt;td class="sectiontableentry2"&gt;Venue Address1:&lt;/td&gt;\r\n    &lt;td class="sectiontableentry2"&gt;&lt;$venueaddress1$&gt;&lt;/td&gt;\r\n  &lt;/tr&gt;\r\n  &lt;tr&gt;\r\n    &lt;td class="sectiontableentry1"&gt;Venue Address2:&lt;/td&gt;\r\n    &lt;td class="sectiontableentry1"&gt;&lt;$venueaddress2$&gt;&lt;/td&gt;\r\n  &lt;/tr&gt;\r\n  &lt;tr&gt;\r\n    &lt;td class="sectiontableentry2"&gt;Venue City:&lt;/td&gt;\r\n    &lt;td class="sectiontableentry2"&gt;&lt;$venuecity$&gt;&lt;/td&gt;\r\n  &lt;/tr&gt;\r\n  &lt;tr&gt;\r\n    &lt;td class="sectiontableentry1"&gt;Venue State:&lt;/td&gt;\r\n    &lt;td class="sectiontableentry1"&gt;&lt;$venuestate$&gt;&lt;/td&gt;\r\n  &lt;/tr&gt;\r\n  &lt;tr&gt;\r\n    &lt;td class="sectiontableentry2"&gt;Venue Zip:&lt;/td&gt;\r\n    &lt;td class="sectiontableentry2"&gt;&lt;$venuezip$&gt;&lt;/td&gt;\r\n  &lt;/tr&gt;\r\n  &lt;tr&gt;\r\n    &lt;td class="sectiontableentry1"&gt;Venue Country:&lt;/td&gt;\r\n    &lt;td class="sectiontableentry1"&gt;&lt;$venuecountry$&gt;&lt;/td&gt;\r\n  &lt;/tr&gt;\r\n  &lt;tr&gt;\r\n    &lt;td class="sectiontableentry2"&gt;Venue Info/Notes:&lt;/td&gt;\r\n    &lt;td class="sectiontableentry2"&gt;&lt;$venueinfo$&gt;&lt;/td&gt;\r\n  &lt;/tr&gt;\r\n&lt;/table&gt;', 
'%hour:%minute %ampm', '%wkdy, %month %ordday %2year', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun', '%wkdy, %mon %ordday %year', '%hour:%minute %ampm', '', 
'/*= GigCalendar 1.0 | Default MiniCal Template\r\n\r\n Developed for nuthin'' werked by Richard Medek, http://richardmedek.com.\r\n\r\n NOTES:\r\n - These styles use the pre-existing HTML output; some styles are redundant to avoid being overwritten by template styles.\r\n\r\n See gigcalendar.net for more detailed instruction and comments.\r\n*/\r\n\r\n\r\n\r\n/*= intro text */\r\n#gigcal_minical .gigcal_minical_introtext {\r\n display: block;\r\n margin: 5px 0;\r\n font: 11px/14px "lucida grande", verdana, arial, sans-serif;\r\n text-align: center;\r\n} \r\n\r\n/*= table */\r\n#gigcal_minical .gigcal_minicaltable {\r\n width: 100%;\r\n background: #fff;\r\n border: solid #BFD0D9;\r\n border-width: 0 1px 1px 1px;\r\n font: 9px "lucida grande", verdana, arial, sans-serif;\r\n}\r\n\r\n/*= caption */\r\n#gigcal_minical .gigcal_minicaltable caption {\r\n border: solid #BFD0D9;\r\n border-width: 1px 1px 0 1px;\r\n color: #630101;\r\n font: bold 11px "lucida grande", verdana, arial, sans-serif;\r\n padding: 4px 0;\r\n}\r\n\r\n#gigcal_minical .gigcal_minicaltable caption a {\r\n color: #630101;\r\n text-decoration: none;\r\n}\r\n\r\n#gigcal_minical .gigcal_minicaltable caption a:hover {\r\n text-decoration: underline;\r\n}\r\n\r\n/*= table headers */\r\n#gigcal_minical .gigcal_minical_dayname {\r\n background: #EBCDCD;\r\n height: 1.5em;\r\n padding-top: 1px;\r\n color: #630101;\r\n font-weight: bold;\r\n}\r\n\r\n/*= empty cells */\r\n#gigcal_minical td.gigcal_minidaybox_empty {\r\n background: #E3E3E3;\r\n}\r\n\r\n/*= normal days */\r\n#gigcal_minical td {\r\n height: 2.5em;\r\n background: #F2F2F2;\r\n color: #333;\r\n text-align: center;\r\n}\r\n\r\n/*= current days */\r\n#gigcal_minical td.gigcal_minidaybox_current {\r\n background: #f5eded;\r\n color: #630101;\r\n font-weight: bold;\r\n}\r\n\r\n/*= weekends */\r\n#gigcal_minical td.gigcal_minidaybox_weekend {\r\n background: #E9EEEF;\r\n}\r\n\r\n/*= event cell */\r\n#gigcal_minical td.gigcal_minidaybox_gig {\r\n font-weight: bold;\r\n color: #365963;\r\n background: #D1DDDF;\r\n}\r\n\r\n/*= event link */\r\n#gigcal_minical .gigcal_minidaybox_gig a {\r\n font-weight: bold;\r\n color: #365963;\r\n text-decoration: none;\r\n}\r\n\r\n#gigcal_minical .gigcal_minidaybox_gig a:hover {\r\n color: #666;\r\n text-decoration: underline;\r\n}\r\n\r\n/*= outro text */\r\n#gigcal_minical .gigcal_minilinktext {\r\n display: block;\r\n text-align: center;\r\n color: #365963;\r\n}\r\n\r\n#gigcal_minical .gigcal_minilinktext a {\r\n color: #365963;\r\n text-decoration: none;\r\n}\r\n\r\n#gigcal_minical .gigcal_minilinktext a:hover {\r\n text-decoration: underline;\r\n}', 
'WIDTH, 200, CAPCOLOR, ''#630101'', FGCOLOR, ''#f2f2f2'', BGCOLOR, ''#ebcdcd'', TEXTCOLOR, ''#000000'', OFFSETY, 20', 1, 1, 1, 1, 0, 0, 1, 1, 1, 
'Full Calendar', 'calendar', 1, 1, 1, '.gigcal_menu {\r\n        font-weight: bold;\r\n}\r\n', 
'RSS', 'Gigs', 'Archive', 'Full Calendar', 'Bands', 'Venues', ' |', 1, 0, 1,
  '/*= GigCalendar 1.0 | Default MiniCal Template\r\n\r\n Developed for nuthin'' werked by Richard Medek, http://richardmedek.com.\r\n\r\n NOTES:\r\n - These styles use the pre-existing HTML output; some styles are redundant to avoid being overwritten by template styles.\r\n\r\n See readme.txt for more detailed instruction and comments.\r\n*/\r\n\r\n\r\n\r\n/*= intro text */\r\n#gigcal_minical .gigcal_minical_introtext {\r\n display: block;\r\n margin: 5px 0;\r\n font: 11px/14px "lucida grande", verdana, arial, sans-serif;\r\n text-align: center;\r\n} \r\n\r\n/*= table */\r\n#gigcal_minical .gigcal_minicaltable {\r\n width: 100%;\r\n background: #fff;\r\n border: solid #BFD0D9;\r\n border-width: 0 1px 1px 1px;\r\n font: 9px "lucida grande", verdana, arial, sans-serif;\r\n}\r\n\r\n/*= caption */\r\n#gigcal_minical .gigcal_minicaltable caption {\r\n border: solid #BFD0D9;\r\n border-width: 1px 1px 0 1px;\r\n color: #630101;\r\n font: bold 11px "lucida grande", verdana, arial, sans-serif;\r\n padding: 4px 0;\r\n}\r\n\r\n#gigcal_minical .gigcal_minicaltable caption a {\r\n color: #630101;\r\n text-decoration: none;\r\n}\r\n\r\n#gigcal_minical .gigcal_minicaltable caption a:hover {\r\n text-decoration: underline;\r\n}\r\n\r\n/*= table headers */\r\n#gigcal_minical .gigcal_minical_dayname {\r\n background: #EBCDCD;\r\n height: 1.5em;\r\n padding-top: 1px;\r\n color: #630101;\r\n font-weight: bold;\r\n}\r\n\r\n/*= empty cells */\r\n#gigcal_minical td.gigcal_minidaybox_empty {\r\n background: #E3E3E3;\r\n}\r\n\r\n/*= normal days */\r\n#gigcal_minical td {\r\n height: 2.5em;\r\n background: #F2F2F2;\r\n color: #333;\r\n text-align: center;\r\n}\r\n\r\n/*= current days */\r\n#gigcal_minical td.gigcal_minidaybox_current {\r\n background: #f5eded;\r\n color: #630101;\r\n font-weight: bold;\r\n}\r\n\r\n/*= weekends */\r\n#gigcal_minical td.gigcal_minidaybox_weekend {\r\n background: #E9EEEF;\r\n}\r\n\r\n/*= event cell */\r\n#gigcal_minical td.gigcal_minidaybox_gig {\r\n font-weight: bold;\r\n color: #365963;\r\n background: #D1DDDF;\r\n}\r\n\r\n/*= event link */\r\n#gigcal_minical .gigcal_minidaybox_gig a {\r\n font-weight: bold;\r\n color: #365963;\r\n text-decoration: none;\r\n}\r\n\r\n#gigcal_minical .gigcal_minidaybox_gig a:hover {\r\n color: #666;\r\n text-decoration: underline;\r\n}\r\n\r\n/*= outro text */\r\n#gigcal_minical .gigcal_minilinktext {\r\n display: block;\r\n text-align: center;\r\n color: #365963;\r\n}\r\n\r\n#gigcal_minical .gigcal_minilinktext a {\r\n color: #365963;\r\n text-decoration: none;\r\n}\r\n\r\n#gigcal_minical .gigcal_minilinktext a:hover {\r\n text-decoration: underline;\r\n}',
'Title:', 'Title:', 'Title:', 'Title:');

