UPDATE `bereso_config` SET `config_value` = '3.7' WHERE `config_name`='dbversion';

INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (0, 'admin_copyitem', 'Copy Entry', 'en');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (0, 'admin_copyitem', 'Eintrag kopieren', 'de');

INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (0, 'admin_copyitem_error', 'Eintrag <b>NICHT</b> kopiert. Eintrag nicht gefunden!', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (0, 'admin_copyitem_successful', 'Eintrag erfolgreich kopiert!', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (0, 'admin_copyitem_save', 'Kopieren', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (0, 'admin_copyitem_sourceitemid', 'Quell Item ID', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (0, 'admin_copyitem_targetuser', 'Ziel Benutzer', 'de');

INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (0, 'admin_copyitem_error', 'Entry <b>NOT</b> copied. Item not found!', 'en');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (0, 'admin_copyitem_successful', 'Entry copied successfully', 'en');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (0, 'admin_copyitem_save', 'Copy', 'en');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (0, 'admin_copyitem_sourceitemid', 'Source item id', 'en');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (0, 'admin_copyitem_targetuser', 'Target user', 'en');

INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (0, 'admin_checkimages_filenotfound', 'file not found', 'en');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (0, 'admin_checkimages_readdatabase', 'Read database metadata - check files', 'en');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (0, 'admin_checkimages_readfile', 'Read files - check database metadata', 'en');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (0, 'admin_checkimages_imagesprocessed', 'images processed', 'en');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (0, 'admin_checkimages_notfoundindatabase', 'not found in database', 'en');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (0, 'admin_checkimages', 'Check Images', 'en');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (0, 'admin_checkimages', 'Bilder &uuml;berpr&uuml;fen', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (0, 'admin_checkimages_imagesprocessed', 'Bilder verarbeitet', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (0, 'admin_checkimages_readdatabase', 'Lese Datenbank Metadaten - Pr&uuml;fe Dateien', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (0, 'admin_checkimages_readfile', 'Lese Dateien - Pr&uuml;fe Datenbank Metadaten', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (0, 'admin_checkimages_notfoundindatabase', 'nicht in der Datebank gefunden', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (0, 'admin_checkimages_filenotfound', 'Datei nicht gefunden', 'de');

UPDATE `bereso_template_text` SET `template_text_text`='Den Bildschrim daran hindern sich auszuschalten. Funktioniert nur wenn das Ger&auml;t dies auch unterst&uuml;zt. <a target="_BLANK" href="https://developer.mozilla.org/en-US/docs/Web/API/Screen_Wake_Lock_API#feature_policy_integration">Unterst&uuml;tzte Browser</a>.' WHERE `template_text_name`='userconfig_wakescreenlock' and `template_text_language` = 'DE';
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (0, 'userconfig_newline_after_hashtag_insert', 'Neue Zeile nach hinzuf&uuml;gen eines Hashtags', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (0, 'userconfig_newline_after_hashtag_insert', 'New line after adding a hashtag', 'en');