CREATE TABLE IF NOT EXISTS `notificationsSmsLog` (
  `id` int NOT NULL AUTO_INCREMENT,
  `apiId` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `number` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `status` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `error` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `creationDate` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `apiIdIndex` (`apiId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
ALTER TABLE `notificationsSmsLog`
	CHANGE COLUMN `apiId` `apiId` VARCHAR(50) NULL DEFAULT '' COLLATE 'utf8_unicode_ci' AFTER `id`;
ALTER TABLE `notificationsSmsLog`
	CHANGE COLUMN `status` `status` VARCHAR(255) NULL COLLATE 'utf8_unicode_ci' AFTER `number`;

ALTER TABLE `notifications`
	DROP COLUMN `instanceId`,
	DROP FOREIGN KEY `FK_6000B0D389606A49`;

INSERT INTO `notifications` (`id`, `type`, `transport`, `subject`, `content`, `description`, `trigger`, `params`) VALUES (null, 1000, 2, 'Potwierdzenie akceptacji zlecenia', 'Zaakceptowałeś zlecenie [nr_zlecenia]. Umów pomiar z inwestorem [imię i nazwisko] [adres]', NULL, NULL, NULL);
