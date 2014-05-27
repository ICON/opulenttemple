CREATE TABLE IF NOT EXISTS `#__rsmediagallery_items` (  `id` int(11) NOT NULL AUTO_INCREMENT,  `original_filename` varchar(255) NOT NULL,  `filename` varchar(255) NOT NULL,  `title` varchar(255) NOT NULL,  `url` text NOT NULL,  `description` text NOT NULL,  `type` varchar(32) NOT NULL,  `params` text NOT NULL,  `free_aspect` tinyint(1) NOT NULL,  `hits` int(11) NOT NULL,  `created` datetime NOT NULL,  `modified` datetime NOT NULL,  `published` tinyint(1) NOT NULL,  `ordering` int(11) NOT NULL,  PRIMARY KEY (`id`)) ENGINE=MyISAM  DEFAULT CHARSET=utf8;CREATE TABLE IF NOT EXISTS `#__rsmediagallery_tags` (  `item_id` int(11) NOT NULL,  `tag` varchar(255) NOT NULL,  UNIQUE KEY `item_id` (`item_id`,`tag`),  KEY `media_id` (`item_id`,`tag`)) ENGINE=MyISAM DEFAULT CHARSET=utf8;