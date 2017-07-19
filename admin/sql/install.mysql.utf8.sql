DROP TABLE IF EXISTS `#__qatdatabase_items`;
CREATE TABLE `#__qatdatabase_items` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`title` varchar(255) NOT NULL,
	`alias` varchar(255) NOT NULL,
	`published` tinyint(4) NOT NULL,
	`created_by` int(10) NOT NULL DEFAULT 0,
	`created` datetime NOT NULL,
	`publish_up` datetime NOT NULL,
	`publish_down` datetime NOT NULL,
	`catid` TEXT NOT NULL,
	`itemdata` TEXT NOT NULL,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `#__qatdatabase_fields`;
CREATE TABLE `#__qatdatabase_fields` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`title` varchar(255) NOT NULL,
	`name` varchar(255) NOT NULL,
	`labellink` varchar(255) NOT NULL,
	`description` varchar(255) NOT NULL,
	`required` tinyint(4) NOT NULL,
	`editable` tinyint(4) NOT NULL,
	`created` datetime NOT NULL,
	`published` tinyint(4) NOT NULL,
	`publish_up` datetime NOT NULL,
	`publish_down` datetime NOT NULL,
	`catid` text NOT NULL,
	`type` text NOT NULL,
	`placeholder` varchar(255) NOT NULL,
	`names` text NOT NULL,
	`values` text NOT NULL,
	`rows` int(11) NOT NULL,
	`cols` int(11) NOT NULL,
	`max_file_size` int(11) NOT NULL,
	`parameters` text NOT NULL,
	`max_length` int(11) NOT NULL,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `#__qatdatabase_fields_ordering`;
CREATE TABLE `#__qatdatabase_fields_ordering` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`ordering` int(11) NOT NULL,
	`fieldid` int(11) NOT NULL,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;
