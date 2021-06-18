CREATE TABLE IF NOT EXISTS `#__ninox_config` (
	`name` varchar(64) NOT NULL default '',
	`params` text NOT NULL,
	PRIMARY KEY  (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__ninox_user_map` (
	`id` int(11) NOT NULL,
	`ninoxId` int(11) NOT NULL,
	PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

