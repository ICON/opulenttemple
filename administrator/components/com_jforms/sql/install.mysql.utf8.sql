CREATE TABLE IF NOT EXISTS `#__jforms_forms` (
	`id` int(11) NOT NULL auto_increment,
	`name` VARCHAR(255) NOT NULL ,
	`alias` VARCHAR(255) ,
	`description` TEXT ,
	`fieldsets` LONGTEXT ,
	`message_after_submit` TEXT ,
	`language_file` VARCHAR(255) ,
	`emails` LONGTEXT ,
	`redirect_after_submit` VARCHAR(255) ,
	`events` LONGTEXT ,
	`save_data_in_db` TINYINT DEFAULT 1 ,
	`generate_pdf` TINYINT DEFAULT 1 ,
	`layout_type` VARCHAR(255) DEFAULT 'wizard' ,
	`options` LONGTEXT ,
	`published` INT(11) DEFAULT 1 ,
	`access` INT(11) DEFAULT 1 ,
	`ordering` INT(11) ,

	PRIMARY KEY  (`id`),
	UNIQUE(`alias`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__jforms_submissions` (
	`id` int(11) NOT NULL auto_increment,
	`created_by` INT(11) ,
	`form_id` INT(11) ,
	`creation_date` INT ,
	`ip_address` VARCHAR(255) ,
	`form_data` LONGTEXT ,
	`jforms_snapshot` LONGTEXT ,
	`pdf` VARCHAR(255) ,
	`password` VARCHAR(255) ,

	PRIMARY KEY  (`id`),
	UNIQUE(`password`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



