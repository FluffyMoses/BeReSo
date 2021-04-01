ALTER TABLE `bereso_item` ADD `item_ocr` TINYINT(1) NOT NULL DEFAULT '0' COMMENT 'is ocr enabled for this item' AFTER `item_favorite`, ADD `item_ocr_text` TEXT NULL DEFAULT NULL COMMENT 'ocr recognized text' AFTER `item_ocr`;

ALTER TABLE `bereso_user` ADD `user_ocr` TINYINT(1) NOT NULL DEFAULT '0' COMMENT 'is ocr enabled for this user' AFTER `user_last_taggroup`;

CREATE TABLE `bereso`.`bereso_config` ( `config_id` INT(10) NOT NULL AUTO_INCREMENT COMMENT 'id of the bereso config item' , `config_name` VARCHAR(250) NOT NULL COMMENT 'name of the bereso config item' , `config_value` TEXT NOT NULL COMMENT 'value of the config item' , PRIMARY KEY (`config_id`)) ENGINE = InnoDB COMMENT = 'stores global bereso configuration';

INSERT INTO `bereso_config` (`config_name`, `config_value`) VALUES ('dbversion', '3.4');
INSERT INTO `bereso_config` (`config_name`, `config_value`) VALUES ('ocr_enabled', '0');
INSERT INTO `bereso_config` (`config_name`, `config_value`) VALUES ('ocr_password', 'PASSWORD_FOR_OCR_AGENT');
INSERT INTO `bereso_config` (`config_name`, `config_value`) VALUES ('login_motd', '');

INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (1, 'main_navigation_show_stop_ocr', 'OCR deaktivieren', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (1, 'main_navigation_show_start_ocr', 'OCR aktivieren', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (2, 'main_navigation_show_stop_ocr', 'OCR deaktivieren', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (2, 'main_navigation_show_start_ocr', 'OCR aktivieren', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (3, 'main_navigation_show_stop_ocr', 'OCR deaktivieren', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (3, 'main_navigation_show_start_ocr', 'OCR aktivieren', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (4, 'main_navigation_show_stop_ocr', 'Disable OCR', 'en');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (4, 'main_navigation_show_start_ocr', 'Enable OCR', 'en');

INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (1, 'main_navigation_show_item_ocr_edit', 'OCR bearbeiten', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (2, 'main_navigation_show_item_ocr_edit', 'OCR bearbeiten', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (3, 'main_navigation_show_item_ocr_edit', 'OCR bearbeiten', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (4, 'main_navigation_show_item_ocr_edit', 'Edit OCR', 'en');

INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (1, 'edit_ocr', 'OCR bearbeiten', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (1, 'edit_ocr_text', 'OCR Text', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (1, 'edit_ocr_save', 'Speichern', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (2, 'edit_ocr', 'OCR bearbeiten', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (2, 'edit_ocr_text', 'OCR Text', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (2, 'edit_ocr_save', 'Speichern', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (3, 'edit_ocr', 'OCR bearbeiten', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (3, 'edit_ocr_text', 'OCR Text', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (3, 'edit_ocr_save', 'Speichern', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (4, 'edit_ocr', 'OCR bearbeiten', 'en');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (4, 'edit_ocr_text', 'OCR Text', 'en');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (4, 'edit_ocr_save', 'Speichern', 'en');

INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (1, 'edit_ocr_entry_saved', 'Eintrag gespeichert.', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (2, 'edit_ocr_entry_saved', 'Eintrag gespeichert.', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (3, 'edit_ocr_entry_saved', 'Eintrag gespeichert.', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (4, 'edit_ocr_entry_saved', 'Entry saved.', 'en');