ALTER TABLE `bereso_item` ADD `item_ocr` TINYINT(1) NOT NULL DEFAULT '0' COMMENT 'is ocr enabled for this item' AFTER `item_favorite`, ADD `item_ocr_text` TEXT NULL DEFAULT NULL COMMENT 'ocr recognized text' AFTER `item_ocr`;

ALTER TABLE `bereso_user` ADD `user_ocr` TINYINT(1) NOT NULL DEFAULT '0' COMMENT 'is ocr enabled for this user' AFTER `user_last_taggroup`;

CREATE TABLE IF NOT EXISTS `bereso_config` (
  `config_id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'id of the bereso config item',
  `config_name` varchar(250) COLLATE latin1_german1_ci NOT NULL COMMENT 'name of the bereso config item',
  `config_value` text COLLATE latin1_german1_ci NOT NULL COMMENT 'value of the config item',
  PRIMARY KEY (`config_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci COMMENT='stores global bereso configuration';


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

INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (1, 'list_tags_all_ocr_items', 'Alle Rezepte mit OCR Text', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (2, 'list_tags_all_ocr_items', 'Alle Eintr&auml;ge mit OCR Text', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (3, 'list_tags_all_ocr_items', 'Alle Projekte mit OCR Text', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (4, 'list_tags_all_ocr_items', 'All recipes with ocr text', 'en');

ALTER TABLE `bereso_item` ADD `item_ocr_searchable` TINYINT(1) NOT NULL DEFAULT '0' COMMENT 'is ocr text searchable enabled for this item' AFTER `item_ocr_text`;

INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (1, 'show_ocr_status_done', 'OCR Status: abgeschlossen', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (1, 'show_ocr_status_pending', 'OCR Status: ausstehend', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (1, 'show_ocr_status_disabled', 'OCR Status: deaktiviert', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (1, 'edit_ocr_searchable', 'OCR Text in der Suche aktivieren', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (2, 'show_ocr_status_done', 'OCR Status: abgeschlossen', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (2, 'show_ocr_status_pending', 'OCR Status: ausstehend', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (2, 'show_ocr_status_disabled', 'OCR Status: deaktiviert', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (2, 'edit_ocr_searchable', 'OCR Text in der Suche aktivieren', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (3, 'show_ocr_status_done', 'OCR Status: abgeschlossen', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (3, 'show_ocr_status_pending', 'OCR Status: ausstehend', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (3, 'show_ocr_status_disabled', 'OCR Status: deaktiviert', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (3, 'edit_ocr_searchable', 'OCR Text in der Suche aktivieren', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (4, 'show_ocr_status_done', 'OCR status: done', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (4, 'show_ocr_status_pending', 'OCR status: pending', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (4, 'show_ocr_status_disabled', 'OCR status: disabled', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (4, 'edit_ocr_searchable', 'OCR text searchable', 'de');

INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (1, 'edit_ocr_entry_error_text_characters', 'Eintrag <b>NICHT</b> gespeichert. Text enth&auml;lt nicht erlaubte Zeichen.', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (2, 'edit_ocr_entry_error_text_characters', 'Eintrag <b>NICHT</b> gespeichert. Text enth&auml;lt nicht erlaubte Zeichen.', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (3, 'edit_ocr_entry_error_text_characters', 'Eintrag <b>NICHT</b> gespeichert. Text enth&auml;lt nicht erlaubte Zeichen.', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (4, 'edit_ocr_entry_error_text_characters', 'Entry <b>NOT</b> saved. Text contains forbidden characters.', 'en');

--ALTER DATABASE bereso CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE bereso_config CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE bereso_group CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE bereso_images CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE bereso_item CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE bereso_tags CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE bereso_template CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE bereso_template_text CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE bereso_user CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
