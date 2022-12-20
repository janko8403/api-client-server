CREATE TABLE settingsNotificationHours (id INT AUTO_INCREMENT NOT NULL, day INT NOT NULL, `from` TIME NOT NULL, `to` TIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB;
INSERT INTO settingsNotificationHours (`day`, `from`, `to`) VALUES (1, '08:00:00', '17:00:00');
INSERT INTO settingsNotificationHours (`day`, `from`, `to`) VALUES (2, '08:00:00', '17:00:00');
INSERT INTO settingsNotificationHours (`day`, `from`, `to`) VALUES (3, '08:00:00', '17:00:00');
INSERT INTO settingsNotificationHours (`day`, `from`, `to`) VALUES (4, '08:00:00', '17:00:00');
INSERT INTO settingsNotificationHours (`day`, `from`, `to`) VALUES (5, '08:00:00', '17:00:00');
INSERT INTO settingsNotificationHours (`day`, `from`, `to`) VALUES (6, '08:00:00', '17:00:00');
INSERT INTO settingsNotificationHours (`day`, `from`, `to`) VALUES (7, '08:00:00', '17:00:00');

-- mvc:settings:notificationhours Godziny powiadomień settings/notification-hours main-menu ^jmc:configuration:header
