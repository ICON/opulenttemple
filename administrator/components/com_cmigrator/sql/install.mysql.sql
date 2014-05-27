--
-- Table structure for table `#__cmigrator_categories`
--

CREATE TABLE IF NOT EXISTS `#__cmigrator_categories` (
  `joomla_id` BIGINT(11) NOT NULL,
  `imported_id` BIGINT(11) NOT NULL,
  `imported_parent_id` int(11) NOT NULL,
  `help_text` varchar(255) NULL
)DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `#__cmigrator_configuration`
--

CREATE TABLE IF NOT EXISTS `#__cmigrator_configuration` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cms` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,  
  `settings` text NOT NULL,
  KEY `id` (`id`), UNIQUE KEY (`name`)
) DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `#__cmigrator_users`
--

CREATE TABLE IF NOT EXISTS `#__cmigrator_users` (
  `joomla_id` BIGINT(11) NOT NULL,
  `cms_id` BIGINT(11) NOT NULL,
  KEY `key` (`joomla_id`,`cms_id`)
) DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `#__cmigrator_articles`
--

CREATE TABLE IF NOT EXISTS `#__cmigrator_articles` (
  `joomla_id` BIGINT(11) NOT NULL,
  `cms_id` BIGINT(11) NOT NULL,
  KEY `key` (`joomla_id`,`cms_id`)
) DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `#__cmigrator_comments`
--

CREATE TABLE IF NOT EXISTS `#__cmigrator_comments` (
  `joomla_id` BIGINT(11) NOT NULL,
  `cms_id` BIGINT(11) NOT NULL,
  KEY `key` (`joomla_id`,`cms_id`)
) DEFAULT CHARSET=utf8;