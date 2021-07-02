UPDATE `bereso_config` SET `config_value` = '3.8' WHERE `config_name`='dbversion';

INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (1, 'new_item_saving_message', 'Rezept wird gespeichert...', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (2, 'new_item_saving_message', 'Eintrag wird gespeichert...', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (3, 'new_item_saving_message', 'Projekt wird gespeichert...', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (4, 'new_item_saving_message', 'Saving recipe...', 'en');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (1, 'edit_item_saving_message', 'Rezept wird gespeichert...', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (2, 'edit_item_saving_message', 'Eintrag wird gespeichert...', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (3, 'edit_item_saving_message', 'Projekt wird gespeichert...', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (4, 'edit_item_saving_message', 'Saving recipe...', 'en');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (0, 'edit_ocr_saving_message', 'OCR Text wird gespeichert...', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (0, 'edit_ocr_saving_message', 'Saving ocr text...', 'en');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (0, 'edit_ocr_saving_message', 'Tag Gruppe wird gespeichert...', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (0, 'edit_ocr_saving_message', 'Saving tag group...', 'en');

INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (0, 'userconfig_saving_message', 'Konfiguration wird gespeichert...', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (0, 'userconfig_saving_message', 'Saving configuration...', 'en');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (0, 'admin_config_saving_message', 'Konfiguration wird gespeichert...', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (0, 'admin_config_saving_message', 'Saving configuration...', 'en');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (0, 'admin_checkimages_message', 'Bilder werden gepr&uuml;ft...', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (0, 'admin_checkimages_message', 'Checking images...', 'en');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (0, 'admin_copyitem_saving_message', 'Kopiere Eintrag...', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (0, 'admin_copyitem_saving_message', 'Copy entry...', 'en');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (0, 'admin_users_edit_saving_message', 'Benutzer wird gespeichert...', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (0, 'admin_users_new_saving_message', 'Benutzer wird gespeichert...', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (0, 'admin_users_edit_saving_message', 'Saving user...', 'en');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (0, 'admin_users_new_saving_message', 'Saving user...', 'en');

UPDATE `bereso_template_text` SET `template_text_text`='Zeitzone - Liste mit unterst&uuml;tzten Zeitzonen: <a href="https://www.php.net/manual/de/timezones.php" target="_BLANK">https://www.php.net/manual/de/timezones.php</a>' WHERE `template_text_name`='admin_config_timezone' AND `template_text_language`='de';

INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (0, 'userconfig_ocr_checked_new_item', 'Default setting for new items OCR checkbox', 'en');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (0, 'userconfig_ocr_checked_new_item', 'Standardeinstellung für die OCR Checkbox bei Erstellung eines neuen Eintrags', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (0, 'new_item_ocr', 'OCR', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (0, 'new_item_ocr', 'OCR', 'en');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (0, 'new_entry_error_ocr_characters', 'Eintrag <b>NICHT</b> gespeichert. OCR Checkbox enth&auml;lt nicht erlaubte Zeichen.', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (0, 'new_entry_error_ocr_characters', 'Entry <b>NOT</b> saved. OCR checkbox contains forbidden characters.', 'en');

INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (1, 'list_tags_all_ocr_items_without_search', 'Alle Rezepte mit OCR Text und deaktivierter Suche', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (2, 'list_tags_all_ocr_items_without_search', 'Alle Eintr&auml;ge mit OCR Text und deaktivierter Suche', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (3, 'list_tags_all_ocr_items_without_search', 'Alle Projekte mit OCR Text und deaktivierter Suche', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (4, 'list_tags_all_ocr_items_without_search', 'All recipes with ocr text and search disabled', 'en');