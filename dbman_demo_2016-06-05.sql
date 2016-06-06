# ************************************************************
# Sequel Pro SQL dump
# Version 4096
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: localhost (MySQL 5.5.42)
# Database: dbman_demo
# Generation Time: 2016-06-06 03:01:50 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table blog
# ------------------------------------------------------------

DROP TABLE IF EXISTS `blog`;

CREATE TABLE `blog` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(128) DEFAULT NULL,
  `notes` tinytext,
  `date_created` date DEFAULT NULL,
  `author` int(11) DEFAULT NULL,
  `img` varchar(64) DEFAULT NULL,
  `alias` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `blog` WRITE;
/*!40000 ALTER TABLE `blog` DISABLE KEYS */;

INSERT INTO `blog` (`id`, `title`, `notes`, `date_created`, `author`, `img`, `alias`)
VALUES
	(1,'This is a new blog entry','dsa sdfasf asdfef ads fasd asdf faasdf asfa asfa dad adsfa sdfaf dsa sdfasf asdfef ads fasd asdf faasdf asfa asfa dad adsfa sdfaf dsa sdfasf asdfef ads fasd asdf faasdf asfa asfa dad adsfa sdfaf dsa sdfasf asdfef ads fasd asdf faasdf asfa asfa dad adsfa s','2015-02-02',NULL,'','this-is-a-new-blog-entry'),
	(2,'What about something else?','This is something else that I\'m posting. This is something else that I\'m posting. This is something else that I\'m posting. This is something else that I\'m posting. This is something else that I\'m posting. vThis is something else that I\'m posting. This is ','2015-02-03',NULL,'','what-about-something-else'),
	(3,'And another thing!','asfd asdf asdf dasf asd asfd asdf asdf dasf asd asfd asdf asdf dasf asd asfd asdf asdf dasf asd asfd asdf asdf dasf asd vasfd asdf asdf dasf asd asfd asdf asdf dasf asd asfd asdf asdf dasf asd asfd asdf asdf dasf asd asfd asdf asdf dasf asd asfd asdf asdf','2015-02-02',NULL,'','and-another-thing');

/*!40000 ALTER TABLE `blog` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table demos
# ------------------------------------------------------------

DROP TABLE IF EXISTS `demos`;

CREATE TABLE `demos` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(132) DEFAULT NULL,
  `alias` varchar(132) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `demos` WRITE;
/*!40000 ALTER TABLE `demos` DISABLE KEYS */;

INSERT INTO `demos` (`id`, `name`, `alias`)
VALUES
	(1,'Members','members'),
	(2,'Products','products'),
	(3,'Content','content'),
	(4,'Events','events'),
	(5,'Image Gallery','gallery'),
	(6,'Content Management','cms');

/*!40000 ALTER TABLE `demos` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table events
# ------------------------------------------------------------

DROP TABLE IF EXISTS `events`;

CREATE TABLE `events` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(128) DEFAULT NULL,
  `event_date` date DEFAULT NULL,
  `descr` text,
  `active` tinyint(1) DEFAULT NULL,
  `start_time` varchar(64) DEFAULT NULL,
  `end_time` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `events` WRITE;
/*!40000 ALTER TABLE `events` DISABLE KEYS */;

INSERT INTO `events` (`id`, `title`, `event_date`, `descr`, `active`, `start_time`, `end_time`)
VALUES
	(1,'Birthday Party','2013-09-23','asdfasdf',1,'8:30 AM','12:30 PM');

/*!40000 ALTER TABLE `events` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table files
# ------------------------------------------------------------

DROP TABLE IF EXISTS `files`;

CREATE TABLE `files` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(128) DEFAULT NULL,
  `date_created` date DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `files` WRITE;
/*!40000 ALTER TABLE `files` DISABLE KEYS */;

INSERT INTO `files` (`id`, `title`, `date_created`, `active`)
VALUES
	(18,'Testttesttest','2015-06-10',1),
	(19,'another-test','2015-06-29',1),
	(20,'Something-New','2015-06-29',1),
	(21,'Lorem-Ipsum','2015-06-29',1);

/*!40000 ALTER TABLE `files` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table gallery
# ------------------------------------------------------------

DROP TABLE IF EXISTS `gallery`;

CREATE TABLE `gallery` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(128) DEFAULT NULL,
  `img` varchar(128) DEFAULT NULL,
  `date_created` date DEFAULT NULL,
  `descr` tinytext,
  `active` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `gallery` WRITE;
/*!40000 ALTER TABLE `gallery` DISABLE KEYS */;

INSERT INTO `gallery` (`id`, `title`, `img`, `date_created`, `descr`, `active`)
VALUES
	(1,'French Vanilla','frenchvanilla.jpg','2013-09-25','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris laoreet sem at urna mattis bibendum. Nunc suscipit nunc sed turpis dictum elementum. Integer sapien ante, tristique id mattis sit amet, facilisis non libero. Praesent bibendum dolor sit amet ',1),
	(2,'Cinnerin','cinnerin.jpg','2013-09-25','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris laoreet sem at urna mattis bibendum. Nunc suscipit nunc sed turpis dictum elementum. Integer sapien ante, tristique id mattis sit amet, facilisis non libero. Praesent bibendum dolor sit amet ',1),
	(3,'Asdfasdfasdf','broadberry.jpg','2015-06-29','asf dasd fasdf ',1),
	(4,'AppleLanche','appelanche.jpg','2015-06-29','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris laoreet sem at urna mattis bibendum. Nunc suscipit nunc sed turpis dictum elementum. Integer sapien ante, tristique id mattis sit amet, facilisis non libero. Praesent bibendum dolor sit amet ',1),
	(5,'asd fasdf ','bwpeach.jpg','2015-06-29','as dfasd fasdf ',1);

/*!40000 ALTER TABLE `gallery` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table member_groups
# ------------------------------------------------------------

DROP TABLE IF EXISTS `member_groups`;

CREATE TABLE `member_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

LOCK TABLES `member_groups` WRITE;
/*!40000 ALTER TABLE `member_groups` DISABLE KEYS */;

INSERT INTO `member_groups` (`id`, `name`)
VALUES
	(1,'Group One'),
	(2,'Group Two'),
	(3,'Group Three'),
	(4,'Group Four'),
	(5,'Group Five');

/*!40000 ALTER TABLE `member_groups` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table member_lists
# ------------------------------------------------------------

DROP TABLE IF EXISTS `member_lists`;

CREATE TABLE `member_lists` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

LOCK TABLES `member_lists` WRITE;
/*!40000 ALTER TABLE `member_lists` DISABLE KEYS */;

INSERT INTO `member_lists` (`id`, `name`)
VALUES
	(1,'Politics'),
	(2,'Science'),
	(3,'Sports'),
	(4,'Finance'),
	(5,'Technology');

/*!40000 ALTER TABLE `member_lists` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table member_types
# ------------------------------------------------------------

DROP TABLE IF EXISTS `member_types`;

CREATE TABLE `member_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

LOCK TABLES `member_types` WRITE;
/*!40000 ALTER TABLE `member_types` DISABLE KEYS */;

INSERT INTO `member_types` (`id`, `name`)
VALUES
	(1,'Standard'),
	(2,'Premium'),
	(3,'Ultimate'),
	(4,'Administrator');

/*!40000 ALTER TABLE `member_types` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table members
# ------------------------------------------------------------

DROP TABLE IF EXISTS `members`;

CREATE TABLE `members` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `active` tinyint(1) DEFAULT NULL,
  `name` varchar(128) DEFAULT NULL,
  `email` varchar(128) DEFAULT NULL,
  `password` varchar(128) DEFAULT NULL,
  `date_created` date DEFAULT NULL,
  `bio` tinytext,
  `avatar` varchar(128) DEFAULT NULL,
  `type_id` int(11) DEFAULT NULL,
  `groups` varchar(128) DEFAULT NULL,
  `lists` varchar(128) DEFAULT NULL,
  `session_id` varchar(128) DEFAULT NULL,
  `use_bio` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

LOCK TABLES `members` WRITE;
/*!40000 ALTER TABLE `members` DISABLE KEYS */;

INSERT INTO `members` (`id`, `active`, `name`, `email`, `password`, `date_created`, `bio`, `avatar`, `type_id`, `groups`, `lists`, `session_id`, `use_bio`)
VALUES
	(6,1,'asdfasd','fasdfasd','asdf','2015-06-10','asdfasdf','cat.jpg',1,NULL,NULL,NULL,1),
	(5,1,'asdfasdfasdf','asdfasdf','asdf','2015-06-10','asdfasdfasdf','cat.jpg',1,NULL,NULL,NULL,1);

/*!40000 ALTER TABLE `members` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table shop_groups
# ------------------------------------------------------------

DROP TABLE IF EXISTS `shop_groups`;

CREATE TABLE `shop_groups` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) DEFAULT NULL,
  `descr` varchar(255) DEFAULT NULL,
  `active` tinyint(1) unsigned zerofill DEFAULT NULL,
  `sort` int(9) DEFAULT NULL,
  `alias` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `shop_groups` WRITE;
/*!40000 ALTER TABLE `shop_groups` DISABLE KEYS */;

INSERT INTO `shop_groups` (`id`, `name`, `descr`, `active`, `sort`, `alias`)
VALUES
	(2,'Abstract','',1,2,'abstract'),
	(3,'Monsters','',1,3,'monsters'),
	(4,'Robots','',1,4,'robots'),
	(5,'Animals','',1,4,'animals'),
	(6,'Expressive','',1,5,'expressive');

/*!40000 ALTER TABLE `shop_groups` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table shop_product_sizes
# ------------------------------------------------------------

DROP TABLE IF EXISTS `shop_product_sizes`;

CREATE TABLE `shop_product_sizes` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) DEFAULT NULL,
  `sort` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `shop_product_sizes` WRITE;
/*!40000 ALTER TABLE `shop_product_sizes` DISABLE KEYS */;

INSERT INTO `shop_product_sizes` (`id`, `name`, `sort`)
VALUES
	(1,'Small',NULL),
	(2,'Medium',NULL),
	(3,'Large',NULL),
	(4,'Extra Large',NULL);

/*!40000 ALTER TABLE `shop_product_sizes` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table shop_product_status
# ------------------------------------------------------------

DROP TABLE IF EXISTS `shop_product_status`;

CREATE TABLE `shop_product_status` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `shop_product_status` WRITE;
/*!40000 ALTER TABLE `shop_product_status` DISABLE KEYS */;

INSERT INTO `shop_product_status` (`id`, `name`)
VALUES
	(1,'Available'),
	(2,'Ready to Make'),
	(3,'Not Available'),
	(4,'2-3 Weeks');

/*!40000 ALTER TABLE `shop_product_status` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table shop_products
# ------------------------------------------------------------

DROP TABLE IF EXISTS `shop_products`;

CREATE TABLE `shop_products` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(64) DEFAULT NULL,
  `date_created` date DEFAULT NULL,
  `inventory` int(11) DEFAULT NULL,
  `intro` varchar(255) DEFAULT NULL,
  `descr` text,
  `groups` varchar(64) DEFAULT NULL,
  `img` varchar(64) DEFAULT NULL,
  `price` decimal(8,2) DEFAULT NULL,
  `backorder` varchar(64) DEFAULT NULL,
  `thumb` varchar(64) DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  `size` int(11) DEFAULT NULL,
  `alias` varchar(64) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `width` decimal(6,1) DEFAULT NULL,
  `height` decimal(6,1) DEFAULT NULL,
  `weight` decimal(6,1) DEFAULT NULL,
  `featured` tinyint(1) unsigned zerofill DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `shop_products` WRITE;
/*!40000 ALTER TABLE `shop_products` DISABLE KEYS */;

INSERT INTO `shop_products` (`id`, `title`, `date_created`, `inventory`, `intro`, `descr`, `groups`, `img`, `price`, `backorder`, `thumb`, `active`, `size`, `alias`, `status`, `width`, `height`, `weight`, `featured`)
VALUES
	(67,'Weird Monster Guy','2015-01-31',10,'','','3','',5.55,'','',1,1,'monster-magnet',2,NULL,NULL,NULL,0),
	(68,'Weird Shape Thing','2015-01-31',0,'','','2','',0.00,'','',1,3,'frank-the-fish',4,NULL,NULL,NULL,1),
	(69,'Friendly Ghost','2015-01-31',0,'This is an intro description for the friendly ghost. I certainly hope you enjoy this intro description for the friendly ghost which as I said is of course an intro for the friendly ghost.','This is an intro description for the friendly ghost. I certainly hope you enjoy this intro description for the friendly ghost which as I said is of course an intro for the friendly ghost.\r\nThis is an intro description for the friendly ghost. I certainly hope you enjoy this intro description for the friendly ghost which as I said is of course an intro for the friendly ghost.\r\nThis is an intro description for the friendly ghost. I certainly hope you enjoy this intro description for the friendly ghost which as I said is of course an intro for the friendly ghost.\r\nThis is an intro description for the friendly ghost. I certainly hope you enjoy this intro description for the friendly ghost which as I said is of course an intro for the friendly ghost.','2,4','cat.jpg',0.00,'','cat.jpg',1,1,'friendly-ghost',4,NULL,NULL,NULL,1),
	(70,'Mega Bot 2000','2015-01-31',0,'','','4','',0.00,'','',1,2,'mega-bot',1,NULL,NULL,NULL,0),
	(72,'Test Product 777','2015-01-31',10,'This is the intro description.','This is the full description','2,3,4','',29.99,'','',1,2,'test-product',2,NULL,NULL,NULL,0),
	(73,'Test Product777','2015-01-31',10,'This is the intro description.','This is the full description','2,3,4','',29.99,'','',1,2,'test-product',2,NULL,NULL,NULL,0),
	(77,'***Test Product2','2015-01-31',10,'This is the intro description.','This is the full description','2,3,4','',29.99,'','',1,2,'test-product',2,NULL,NULL,NULL,0),
	(78,'Test Product','2015-01-31',10,'This is the intro description.','This is the full description','2,3,4,5',NULL,29.99,NULL,NULL,1,2,'test-product',2,NULL,NULL,NULL,0),
	(79,'Test Product','2015-01-31',10,'This is the intro description.','This is the full description','2,3,4,5',NULL,29.99,NULL,NULL,1,2,'test-product',2,NULL,NULL,NULL,0),
	(80,'Test Product','2015-01-31',10,'This is the intro description.','This is the full description','2,3,4,5',NULL,29.99,NULL,NULL,1,2,'test-product',2,NULL,NULL,NULL,0),
	(81,'Test Product','2015-01-31',10,'This is the intro description.','This is the full description','2,3,4,5',NULL,29.99,NULL,NULL,1,2,'test-product',2,NULL,NULL,NULL,0),
	(82,'Test Product','2015-01-31',10,'This is the intro description.','This is the full description','2,3,4,5',NULL,29.99,NULL,NULL,1,2,'test-product',2,NULL,NULL,NULL,0),
	(83,'Test Product','2015-01-31',10,'This is the intro description.','This is the full description','2,3,4,5','',29.99,'','',1,2,'test-product',2,NULL,NULL,NULL,0),
	(84,'Test Product','2015-01-31',10,'This is the intro description.','This is the full description','2,3,4,5',NULL,29.99,NULL,NULL,1,2,'test-product',2,NULL,NULL,NULL,0),
	(85,'Test Product','2015-01-31',10,'This is the intro description.','This is the full description','2,3,4,5',NULL,29.99,NULL,NULL,1,2,'test-product',2,NULL,NULL,NULL,0),
	(86,'Test Product','2015-01-31',10,'This is the intro description.','This is the full description','2,3,4,5',NULL,29.99,NULL,NULL,1,2,'test-product',2,NULL,NULL,NULL,0),
	(87,'Test Product','2015-01-31',10,'This is the intro description.','This is the full description','2',NULL,29.99,NULL,NULL,1,2,'test-product',2,NULL,NULL,NULL,0),
	(88,'Test Product','2015-01-31',10,'This is the intro description.','This is the full description','2',NULL,29.99,NULL,NULL,1,2,'test-product',2,NULL,NULL,NULL,0),
	(89,'What!!','2015-06-10',0,'','','3,5,6','',0.00,'','',1,1,'what',1,NULL,NULL,NULL,NULL),
	(90,'Asdfasdfasdf','2015-06-10',0,'','','2,3','',0.00,'','',1,1,'asfasdf',1,NULL,NULL,NULL,NULL);

/*!40000 ALTER TABLE `shop_products` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table shop_products_groups
# ------------------------------------------------------------

DROP TABLE IF EXISTS `shop_products_groups`;

CREATE TABLE `shop_products_groups` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `group_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `shop_products_groups` WRITE;
/*!40000 ALTER TABLE `shop_products_groups` DISABLE KEYS */;

INSERT INTO `shop_products_groups` (`id`, `group_id`, `product_id`)
VALUES
	(81,3,67),
	(82,4,70),
	(83,2,68),
	(88,4,70),
	(129,2,73),
	(130,3,73),
	(131,4,73),
	(132,2,83),
	(133,3,83),
	(134,4,83),
	(135,5,83),
	(136,2,69),
	(137,4,69),
	(138,2,72),
	(139,3,72),
	(140,4,72),
	(147,3,89),
	(148,5,89),
	(149,6,89),
	(150,2,90),
	(151,3,90),
	(152,2,77),
	(153,3,77),
	(154,4,77);

/*!40000 ALTER TABLE `shop_products_groups` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table tasks
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tasks`;

CREATE TABLE `tasks` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(64) DEFAULT NULL,
  `notes` tinytext,
  `completed` tinyint(1) unsigned zerofill DEFAULT NULL,
  `deadline` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `tasks` WRITE;
/*!40000 ALTER TABLE `tasks` DISABLE KEYS */;

INSERT INTO `tasks` (`id`, `title`, `notes`, `completed`, `deadline`)
VALUES
	(1,'Get Business License2','asdfasdf2',0,'2015-03-29'),
	(2,'Get Domain Name','get a domain name for the website',1,'2015-01-28'),
	(3,'Research Online Resellers','research online resellers like Etsy',0,'2015-05-29'),
	(5,'Research Off/Online Merchants','Look into paypal card reader and online merchants for sales',0,'2015-06-03'),
	(6,'test','test',1,'2015-06-10'),
	(7,'sdafsda','asdfasf',0,'2000-11-30'),
	(8,'something new perhaps','as df asdfas dfasd f',0,'2015-06-10'),
	(9,'Asdfasdfasdf','asfasdfasdf',0,'2015-06-10');

/*!40000 ALTER TABLE `tasks` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
