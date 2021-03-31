ALTER TABLE `bereso_item` ADD `item_ocr` TINYINT(1) NOT NULL DEFAULT '0' COMMENT 'is ocr enabled for this item' AFTER `item_favorite`, ADD `item_ocr_text` TEXT NULL DEFAULT NULL COMMENT 'ocr recognized text' AFTER `item_ocr`;

ALTER TABLE `bereso_user` ADD `user_ocr` TINYINT(1) NOT NULL DEFAULT '0' COMMENT 'is ocr enabled for this user' AFTER `user_last_taggroup`;

CREATE TABLE `bereso`.`bereso_config` ( `config_id` INT(10) NOT NULL AUTO_INCREMENT COMMENT 'id of the bereso config item' , `config_name` VARCHAR(250) NOT NULL COMMENT 'name of the bereso config item' , `config_value` TEXT NOT NULL COMMENT 'value of the config item' , PRIMARY KEY (`config_id`)) ENGINE = InnoDB COMMENT = 'stores global bereso configuration';

INSERT INTO `bereso_config` (`config_name`, `config_value`) VALUES ('dbversion', '3.4');
INSERT INTO `bereso_config` (`config_name`, `config_value`) VALUES ('ocr_enabled', '0');
INSERT INTO `bereso_config` (`config_name`, `config_value`) VALUES ('ocr_password', 'PASSWORD_FOR_OCR_AGENT');
