/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Exportiere Datenbank Struktur für bereso
CREATE DATABASE IF NOT EXISTS `bereso` /*!40100 DEFAULT CHARACTER SET latin1 COLLATE latin1_german1_ci */;
USE `bereso`;

-- Exportiere Struktur von Tabelle bereso.bereso_item
CREATE TABLE IF NOT EXISTS `bereso_item` (
  `item_id` int(10) NOT NULL AUTO_INCREMENT,
  `item_name` varchar(250) CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL,
  `item_text` text CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL,
  `item_user` int(10) DEFAULT NULL,
  `item_imagename` varchar(50) DEFAULT NULL,
  `item_timestamp_creation` int(15) DEFAULT NULL,
  `item_timestamp_edit` int(15) DEFAULT NULL,
  `item_shareid` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`item_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

-- Exportiere Struktur von Tabelle bereso.bereso_tags
CREATE TABLE IF NOT EXISTS `bereso_tags` (
  `tags_id` int(10) NOT NULL AUTO_INCREMENT,
  `tags_name` varchar(50) CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL DEFAULT '',
  `tags_item` int(10) DEFAULT NULL,
  PRIMARY KEY (`tags_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

-- Exportiere Struktur von Tabelle bereso.bereso_template
CREATE TABLE IF NOT EXISTS `bereso_template` (
  `template_id` int(10) NOT NULL AUTO_INCREMENT,
  `template_name` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`template_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

-- Exportiere Struktur von Tabelle bereso.bereso_template_text
CREATE TABLE IF NOT EXISTS `bereso_template_text` (
  `template_text_id` int(10) NOT NULL AUTO_INCREMENT,
  `template_text_template_id` int(10) DEFAULT NULL,
  `template_text_name` varchar(250) NOT NULL,
  `template_text_text` text NOT NULL DEFAULT '',
  PRIMARY KEY (`template_text_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

-- Exportiere Struktur von Tabelle bereso.bereso_user
CREATE TABLE IF NOT EXISTS `bereso_user` (
  `user_id` int(10) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(250) CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL,
  `user_pwhash` varchar(250) DEFAULT NULL,
  `user_template` int(10) DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
