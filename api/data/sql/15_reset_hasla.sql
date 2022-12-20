ALTER TABLE `user` DROP FOREIGN KEY `FK_8D93D64989606A49`;
ALTER TABLE `user` DROP COLUMN `instanceId`;
ALTER TABLE `user` ADD COLUMN `passwordToken` varchar(255) NULL COMMENT '';
ALTER TABLE `user`CHANGE COLUMN `password` `password` VARCHAR(255) NOT NULL DEFAULT '' COLLATE 'utf8_polish_ci' AFTER `isTester`;

INSERT INTO `notifications` (`type`, `transport`, `subject`, `content`) VALUES ('1', '1', 'Reset hasła', 'W celu zresetowania hasła, wejdź w odnośnik: [link]');
INSERT INTO `notificationRecipients` (`strategy`, `notificationId`) VALUES ('Notifications\\RecipientStrategy\\UserStrategy', '2');
