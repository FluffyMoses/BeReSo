UPDATE `bereso_config` SET `config_value` = '3.5' WHERE `config_name`='dbversion';

ALTER TABLE `bereso_user` ADD `user_admin` TINYINT(1) NOT NULL DEFAULT '0' COMMENT 'is user admin' AFTER `user_template`;

INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (1, 'main_navigation_admin', 'Admincenter', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (2, 'main_navigation_admin', 'Admincenter', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (3, 'main_navigation_admin', 'Admincenter', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (4, 'main_navigation_admin', 'Admincenter', 'en');

INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (1, 'admin_center', 'Admincenter', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (2, 'admin_center', 'Admincenter', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (3, 'admin_center', 'Admincenter', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (4, 'admin_center', 'Admincenter', 'en');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (1, 'admin_users', 'Benutzerverwaltung', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (2, 'admin_users', 'Benutzerverwaltung', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (3, 'admin_users', 'Benutzerverwaltung', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (4, 'admin_users', 'Usermanagement', 'en');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (1, 'admin_config', 'BeReSo Konfiguration', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (2, 'admin_config', 'BeReSo Konfiguration', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (3, 'admin_config', 'BeReSo Konfiguration', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (4, 'admin_config', 'BeReSo Configuration', 'en');

INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (1, 'admin_config_save', 'Speichern', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (1, 'admin_config_url', 'BeReSo URL, muss mit / enden und mit http:// oder https:// beginnen', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (1, 'admin_config_httpsredirect', 'HTTPS Weiterleitung aktivieren', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (1, 'admin_config_images', 'Pfad zu den Benutzer Bilderordnern - Standardm&auml;ssig images/ d.h. alle Benutzerordner werden unterhalb von diesem Ordner erstellt (images/1/ f&uuml;r Benutzer 1)', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (1, 'admin_config_images_thumbnail', 'Thumbnail H&ouml;he in Pixel', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (1, 'admin_config_timezone', 'Zeitzone - Liste mit unterstützten Zeitzonen:', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (1, 'admin_config_datetime', 'Datum und Zeit Format - Parameterliste:', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (1, 'admin_config_sessionlifetime', 'PHP Session Lifetime in Sekunden', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (1, 'admin_config_new_amount_images', 'Anzahl der Bilder bei hinzuf&uuml;gen eines neuen Eintrags', 'de');

INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (2, 'admin_config_save', 'Speichern', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (2, 'admin_config_url', 'BeReSo URL, muss mit / enden und mit http:// oder https:// beginnen', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (2, 'admin_config_httpsredirect', 'HTTPS Weiterleitung aktivieren', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (2, 'admin_config_images', 'Pfad zu den Benutzer Bilderordnern - Standardm&auml;ssig images/ d.h. alle Benutzerordner werden unterhalb von diesem Ordner erstellt (images/1/ f&uuml;r Benutzer 1)', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (2, 'admin_config_images_thumbnail', 'Thumbnail H&ouml;he in Pixel', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (2, 'admin_config_timezone', 'Zeitzone - Liste mit unterstützten Zeitzonen:', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (2, 'admin_config_datetime', 'Datum und Zeit Format - Parameterliste:', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (2, 'admin_config_sessionlifetime', 'PHP Session Lifetime in Sekunden', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (2, 'admin_config_new_amount_images', 'Anzahl der Bilder bei hinzuf&uuml;gen eines neuen Eintrags', 'de');

INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (3, 'admin_config_save', 'Speichern', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (3, 'admin_config_url', 'BeReSo URL, muss mit / enden und mit http:// oder https:// beginnen', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (3, 'admin_config_httpsredirect', 'HTTPS Weiterleitung aktivieren', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (3, 'admin_config_images', 'Pfad zu den Benutzer Bilderordnern - Standardm&auml;ssig images/ d.h. alle Benutzerordner werden unterhalb von diesem Ordner erstellt (images/1/ f&uuml;r Benutzer 1)', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (3, 'admin_config_images_thumbnail', 'Thumbnail H&ouml;he in Pixel', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (3, 'admin_config_timezone', 'Zeitzone - Liste mit unterstützten Zeitzonen:', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (3, 'admin_config_datetime', 'Datum und Zeit Format - Parameterliste:', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (3, 'admin_config_sessionlifetime', 'PHP Session Lifetime in Sekunden', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (3, 'admin_config_new_amount_images', 'Anzahl der Bilder bei hinzuf&uuml;gen eines neuen Eintrags', 'de');

INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (4, 'admin_config_save', 'Save', 'en');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (4, 'admin_config_url', 'BeReSo URL, must beginn with http:// or https:// and end with /', 'en');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (4, 'admin_config_httpsredirect', 'HTTPS redirect enabled', 'en');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (4, 'admin_config_images', 'Path to the image folder - per default images/ which means that every user image folder is created inside this folder (images/1/ for user 1)', 'en');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (4, 'admin_config_images_thumbnail', 'Thumbnail height in pixel', 'en');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (4, 'admin_config_timezone', 'Timezone - List with all supported timezones:', 'en');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (4, 'admin_config_datetime', 'Datum and time format - List with parameters:', 'en');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (4, 'admin_config_sessionlifetime', 'PHP session lifetime in seconds', 'en');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (4, 'admin_config_new_amount_images', 'Amount of images when adding a new entry', 'en');

INSERT INTO `bereso_config` (`config_name`, `config_value`) VALUES ('session_lifetime', '2592000');
INSERT INTO `bereso_config` (`config_name`, `config_value`) VALUES ('datetimestring', 'd.m.Y H:i:s');
INSERT INTO `bereso_config` (`config_name`, `config_value`) VALUES ('timezone', 'Europe/Berlin');
INSERT INTO `bereso_config` (`config_name`, `config_value`) VALUES ('new_amount_images', '5');
INSERT INTO `bereso_config` (`config_name`, `config_value`) VALUES ('images_thumbnail_height', '200');
INSERT INTO `bereso_config` (`config_name`, `config_value`) VALUES ('https_redirect', '0');
INSERT INTO `bereso_config` (`config_name`, `config_value`) VALUES ('images', 'images/');
INSERT INTO `bereso_config` (`config_name`, `config_value`) VALUES ('url', 'http://bereso/');

INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (1, 'admin_config_saved', 'Eintrag gespeichert.', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (1, 'admin_config_error_text_characters', 'Konfiguration <b>NICHT</b> gespeichert. Eintr&auml;ge enthalten nicht erlaubte Zeichen.', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (2, 'admin_config_saved', 'Eintrag gespeichert.', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (2, 'admin_config_error_text_characters', 'Konfiguration <b>NICHT</b> gespeichert. Eintr&auml;ge enthalten nicht erlaubte Zeichen.', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (3, 'admin_config_saved', 'Eintrag gespeichert.', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (3, 'admin_config_error_text_characters', 'Konfiguration <b>NICHT</b> gespeichert. Eintr&auml;ge enthalten nicht erlaubte Zeichen.', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (4, 'admin_config_saved', 'Entry saved.', 'en');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (4, 'admin_config_error_text_characters', 'Configuration <b>NOT</b> saved. Entries contain forbidden characters.', 'en');

INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (1, 'admin_config_login_motd', 'Nachricht des Tages. Wird auf der Login-Seite angezeigt.', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (1, 'admin_config_ocr_password', 'OCR Agent Passwort - muss im OCR Agent f&uuml;r die Authentifizierung eingegeben werden. Kann frei definiert werden.', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (1, 'admin_config_ocr_enabled', 'OCR aktivieren', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (2, 'admin_config_login_motd', 'Nachricht des Tages. Wird auf der Login-Seite angezeigt.', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (2, 'admin_config_ocr_password', 'OCR Agent Passwort - muss im OCR Agent f&uuml;r die Authentifizierung eingegeben werden. Kann frei definiert werden.', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (2, 'admin_config_ocr_enabled', 'OCR aktivieren', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (3, 'admin_config_login_motd', 'Nachricht des Tages. Wird auf der Login-Seite angezeigt.', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (3, 'admin_config_ocr_password', 'OCR Agent Passwort - muss im OCR Agent f&uuml;r die Authentifizierung eingegeben werden. Kann frei definiert werden.', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (3, 'admin_config_ocr_enabled', 'OCR aktivieren', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (4, 'admin_config_login_motd', 'Message of the day. Displayed on the login page.', 'en');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (4, 'admin_config_ocr_password', 'OCR agent password - must match the one in the ocr agent.', 'en');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (4, 'admin_config_ocr_enabled', 'OCR enabled', 'en');

INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (1, 'admin_center_statistic', 'Statistik', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (1, 'admin_center_statistic_user', 'Benutzer', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (1, 'admin_center_statistic_items', 'Eintr&auml;ge', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (1, 'admin_center_statistic_items_images', 'Bilder', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (1, 'admin_center_statistic_items_ocr', 'OCR Eintr&auml;ge', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (1, 'admin_center_statistic_items_shared', 'Geteilte Eintr&auml;ge', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (2, 'admin_center_statistic', 'Statistik', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (2, 'admin_center_statistic_user', 'Benutzer', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (2, 'admin_center_statistic_items', 'Eintr&auml;ge', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (2, 'admin_center_statistic_items_images', 'Bilder', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (2, 'admin_center_statistic_items_ocr', 'OCR Eintr&auml;ge', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (2, 'admin_center_statistic_items_shared', 'Geteilte Eintr&auml;ge', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (3, 'admin_center_statistic', 'Statistik', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (3, 'admin_center_statistic_user', 'Benutzer', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (3, 'admin_center_statistic_items', 'Eintr&auml;ge', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (3, 'admin_center_statistic_items_images', 'Bilder', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (3, 'admin_center_statistic_items_ocr', 'OCR Eintr&auml;ge', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (3, 'admin_center_statistic_items_shared', 'Geteilte Eintr&auml;ge', 'de');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (4, 'admin_center_statistic', 'Statistics', 'en');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (4, 'admin_center_statistic_user', 'User', 'en');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (4, 'admin_center_statistic_items', 'Entries', 'en');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (4, 'admin_center_statistic_items_images', 'Images', 'en');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (4, 'admin_center_statistic_items_ocr', 'OCR entries', 'en');
INSERT INTO `bereso_template_text` (`template_text_template_id`, `template_text_name`, `template_text_text`, `template_text_language`) VALUES (4, 'admin_center_statistic_items_shared', 'Shared entries', 'en');