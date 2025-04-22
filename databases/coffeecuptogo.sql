DROP TABLE IF EXISTS `coffeepoints`;

CREATE TABLE `coffeepoints` (
  `coffeepoints_id` varchar(20) CHARACTER SET utf8 NOT NULL,
  `coffeepoints_device` varchar(45) CHARACTER SET utf8 DEFAULT NULL,
  `coffeepoints_city` varchar(45) CHARACTER SET utf8 NOT NULL,
  `coffeepoints_point_name` varchar(45) CHARACTER SET utf8 NOT NULL,
  `coffeepoints_comment` varchar(45) CHARACTER SET utf8 DEFAULT NULL,
  `coffeepoints_some_data` varchar(45) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`coffeepoints_id`),
  UNIQUE KEY `id_UNIQUE` (`coffeepoints_id`),
  UNIQUE KEY `device_UNIQUE` (`coffeepoints_device`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Bars in different cities';

DROP TABLE IF EXISTS `registration`;

CREATE TABLE `registration` (
  `registrations_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `registrations_users` varchar(25) CHARACTER SET utf8 NOT NULL,
  `registrations_id_coffeepoints` varchar(20) CHARACTER SET utf8 NOT NULL,
  `registrations_mac` varchar(12) CHARACTER SET utf8 NOT NULL,
  `registrations_host_name` varchar(45) CHARACTER SET utf8 DEFAULT NULL,
  `registrations_date` varchar(25) CHARACTER SET utf8 NOT NULL,
  `registrations_time` varchar(25) CHARACTER SET utf8 NOT NULL,
  `registrations_ip_nat` varchar(20) CHARACTER SET utf8 NOT NULL,
  `registrations_ip_gray` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `registrations_ip_white` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `registrations_status_code` tinyint(3) DEFAULT NULL,
  `registrations_password` varchar(10) CHARACTER SET utf8 DEFAULT NULL,
  `registrations_comment` varchar(45) CHARACTER SET utf8 DEFAULT NULL,
  `registrations_some_data` varchar(45) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`registrations_id`),
  KEY `fk_1_idx` (`registrations_id_coffeepoints`),
  KEY `fk_2_idx` (`registrations_status_code`),
  KEY `fk_3_idx` (`registrations_users`),
  CONSTRAINT `fk_1` FOREIGN KEY (`registrations_id_coffeepoints`) REFERENCES `coffeepoints` (`coffeepoints_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_2` FOREIGN KEY (`registrations_status_code`) REFERENCES `status` (`status_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_3` FOREIGN KEY (`registrations_users`) REFERENCES `users` (`users_telefon`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=190 DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='users registration';


DROP TABLE IF EXISTS `status`;

CREATE TABLE `status` (
  `status_id` tinyint(3) NOT NULL,
  `status_status` varchar(20) CHARACTER SET utf8 NOT NULL,
  `status_comment` varchar(45) CHARACTER SET utf8 DEFAULT NULL,
  `status_some_date` varchar(45) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`status_id`,`status_status`),
  UNIQUE KEY `id_UNIQUE` (`status_id`),
  UNIQUE KEY `status_UNIQUE` (`status_status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='status code';

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `users_telefon` varchar(25) CHARACTER SET utf8 NOT NULL,
  `users_first_reg` varchar(20) CHARACTER SET utf8 NOT NULL,
  `users_comment` varchar(45) CHARACTER SET utf8 DEFAULT NULL,
  `users_some_data` varchar(45) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`users_telefon`),
  UNIQUE KEY `telefon_UNIQUE` (`users_telefon`),
  KEY `fk_4_idx` (`users_first_reg`),
  CONSTRAINT `fk_4` FOREIGN KEY (`users_first_reg`) REFERENCES `coffeepoints` (`coffeepoints_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='users in points';
