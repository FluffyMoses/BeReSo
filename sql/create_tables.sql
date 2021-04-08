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
  `images_id` int(10) NOT NULL AUTO_INCREMENT,
  `images_item` int(10) DEFAULT NULL,
  `images_image_id` int(10) DEFAULT 0,
  `images_fileextension` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`images_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping structure for table bereso.bereso_item
CREATE TABLE IF NOT EXISTS `bereso_item` (
  `item_id` int(10) NOT NULL AUTO_INCREMENT,
  `item_name` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `item_text` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `item_user` int(10) DEFAULT NULL,
  `item_imagename` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `item_timestamp_creation` int(15) DEFAULT NULL,
  `item_timestamp_edit` int(15) DEFAULT NULL,
  `item_shareid` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `item_favorite` tinyint(1) NOT NULL DEFAULT 0,
  `item_ocr` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'is ocr enabled for this item',
  `item_ocr_text` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'ocr recognized text',
  `item_ocr_searchable` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'is ocr text searchable enabled for this item',
  PRIMARY KEY (`item_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping structure for table bereso.bereso_tags
CREATE TABLE IF NOT EXISTS `bereso_tags` (
  `tags_id` int(10) NOT NULL AUTO_INCREMENT,
  `tags_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `tags_item` int(10) DEFAULT NULL,
  PRIMARY KEY (`tags_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping structure for table bereso.bereso_template
CREATE TABLE IF NOT EXISTS `bereso_template` (
  `template_id` int(10) NOT NULL AUTO_INCREMENT,
  `template_name` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `template_language` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`template_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping structure for table bereso.bereso_template_text
CREATE TABLE IF NOT EXISTS `bereso_template_text` (
  `template_text_id` int(10) NOT NULL AUTO_INCREMENT,
  `template_text_template_id` int(10) DEFAULT NULL,
  `template_text_name` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `template_text_text` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `template_text_language` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`template_text_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping structure for table bereso.bereso_user
CREATE TABLE IF NOT EXISTS `bereso_user` (
  `user_id` int(10) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_pwhash` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_template` int(10) NOT NULL,
  `user_admin` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'is user admin',
  `user_last_list` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_last_taggroup` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_ocr` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'is ocr enabled for this user',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
