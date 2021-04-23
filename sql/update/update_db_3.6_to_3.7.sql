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
