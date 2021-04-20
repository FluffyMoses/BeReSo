UPDATE `bereso_config` SET `config_value` = '3.6' WHERE `config_name`='dbversion';

INSERT INTO `bereso_config` (`config_name`, `config_value`) VALUES ('user_log', '0');
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

INSERT INTO `bereso_config` (`config_name`, `config_value`) VALUES ('agent_ocr_log', '0');

DELETE FROM `bereso_template`;
DELETE FROM `bereso_template_text`;

ALTER TABLE `bereso_config` ADD `config_user` INT(10) NOT NULL DEFAULT '0' COMMENT 'configuration belongs to this user - 0 means system configuration' AFTER `config_value`;
