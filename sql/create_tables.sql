/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping structure for table bereso.bereso_config
CREATE TABLE IF NOT EXISTS `bereso_config` (
  `config_id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'id of the bereso config item',
  `config_name` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'name of the bereso config item',
  `config_value` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'value of the config item',
  PRIMARY KEY (`config_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='stores global bereso configuration';

-- Dumping structure for table bereso.bereso_group
CREATE TABLE IF NOT EXISTS `bereso_group` (
  `group_id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'the unique id of a tag group',
  `group_name` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'name of a tag group',
  `group_user` int(10) DEFAULT NULL COMMENT 'user id of the tag group owner',
  `group_text` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'text with all hashtages that are included by the tag group',
  PRIMARY KEY (`group_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='stores information about tag groups';

-- Dumping structure for table bereso.bereso_images
CREATE TABLE IF NOT EXISTS `bereso_images` (
  `images_id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'the unique id of an image',
  `images_item` int(10) DEFAULT NULL COMMENT 'item the image belongs to',
  `images_image_id` int(10) DEFAULT 0 COMMENT 'image id 0 to (last image of the item)',
  `images_fileextension` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'fileextension of the image',
  PRIMARY KEY (`images_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping structure for table bereso.bereso_item
CREATE TABLE IF NOT EXISTS `bereso_item` (
  `item_id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'the unique id of an item',
  `item_name` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'the name',
  `item_text` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'the description text - includes the hashtags for listing',
  `item_user` int(10) DEFAULT NULL COMMENT 'user id of the item owner',
  `item_imagename` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'unique name prefix of the image files',
  `item_timestamp_creation` int(15) DEFAULT NULL COMMENT 'creation timestamp',
  `item_timestamp_edit` int(15) DEFAULT NULL  COMMENT 'last edit timestamp',
  `item_shareid` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL  COMMENT 'unique id for the sharing link when enabled (null if disabled)',
  `item_favorite` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'is favorite true/false',
  `item_ocr` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'is ocr enabled for this item',
  `item_ocr_text` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'ocr recognized text',
  `item_ocr_searchable` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'is ocr text searchable enabled for this item',
  PRIMARY KEY (`item_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping structure for table bereso.bereso_tags
CREATE TABLE IF NOT EXISTS `bereso_tags` (
  `tags_id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'the unique id of a tag',
  `tags_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'tag name without the #',
  `tags_item` int(10) DEFAULT NULL COMMENT 'item id the tag belongs to',
  PRIMARY KEY (`tags_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping structure for table bereso.bereso_template
CREATE TABLE IF NOT EXISTS `bereso_template` (
  `template_id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'the unique id of a template id',
  `template_name` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'name of the template',
  `template_language` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'language of the template',
  PRIMARY KEY (`template_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping structure for table bereso.bereso_template_text
CREATE TABLE IF NOT EXISTS `bereso_template_text` (
  `template_text_id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'the unique id of a template text',
  `template_text_template_id` int(10) DEFAULT NULL COMMENT 'template id the text belongs to',
  `template_text_name` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'template name (called by bereso template replaces)',
  `template_text_text` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'value that is inserted by the replace function',
  `template_text_language` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'language of the template text',
  PRIMARY KEY (`template_text_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping structure for table bereso.bereso_user
CREATE TABLE IF NOT EXISTS `bereso_user` (
  `user_id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'the unique id of an user',
  `user_name` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'login name of the user',
  `user_pwhash` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'hashed password',
  `user_template` int(10) NOT NULL COMMENT 'template that is loaded for this user on login',
  `user_admin` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'is user admin',
  `user_last_list` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'last tag that the user listed (needed for the back-to-list button)',
  `user_last_taggroup` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'last taggroup that the user listed (needed for the back-to-list_tags button)',
  `user_ocr` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'is ocr enabled for this user',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping structure for table bereso.bereso_log
CREATE TABLE IF NOT EXISTS `bereso_log` (
  `log_id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'the unique id of the log entry',
  `log_user` int(10) DEFAULT NULL COMMENT 'id of the user whose entry is logged',
  `log_timestamp` int(15) DEFAULT NULL COMMENT 'log date and time in timestamp format',
  `log_datetime` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'log date and time in human readable format',
  `log_module` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'module that logs the entry',
  `log_action` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'action that triggers the entry',
  `log_text` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'additional information - status - message - etc.',
  `log_clientip` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'the ip address of the client that triggers the log entry',
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
