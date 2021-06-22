DROP TABLE IF EXISTS `#__ninox_config`;

CREATE TABLE `#__ninox_config` (
	`name` varchar(64) NOT NULL default '',
	`params` text NOT NULL,
	PRIMARY KEY  (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `#__ninox_user_map`;

CREATE TABLE `#__ninox_user_map` (
	`id` int(11) NOT NULL,
	`ninoxId` int(11) NOT NULL,
	PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `#__ninox_mapping`;

CREATE TABLE IF NOT EXISTS `#__ninox_mapping` (
	`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`ordering` INT(11)  NOT NULL  DEFAULT 0,
	`state` TINYINT(1)  NOT NULL  DEFAULT 1,
	`checked_out` INT(11)  NOT NULL  DEFAULT 0,
	`checked_out_time` DATETIME NOT NULL  DEFAULT "0000-00-00 00:00:00",
	`created_by` INT(11)  NOT NULL  DEFAULT 0,
	`modified_by` INT(11)  NOT NULL  DEFAULT 0,
	`joomla_prop` VARCHAR(60)  NOT NULL,
	`ninox_prop` VARCHAR(60)  NOT NULL,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8mb4_unicode_ci;