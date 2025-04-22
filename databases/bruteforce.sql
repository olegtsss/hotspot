DROP TABLE IF EXISTS `export_files`;

CREATE TABLE `export_files` (
  `id` varchar(25) NOT NULL,
  `time_create` varchar(25) NOT NULL,
  `file_path` varchar(90) NOT NULL,
  `file_name` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `ip_addresses`;

CREATE TABLE `ip_addresses` (
  `id` varchar(25) CHARACTER SET utf8 NOT NULL,
  `ip` varchar(15) CHARACTER SET utf8 NOT NULL,
  `time_last_connect` int(11) NOT NULL,
  `time_last_success` varchar(25) COLLATE utf8_bin DEFAULT NULL,
  `count_connect` tinyint(3) DEFAULT NULL,
  `count_success` tinyint(11) DEFAULT NULL,
  `comment` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  `ban` varchar(6) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='ip addresses, try connect to php';
