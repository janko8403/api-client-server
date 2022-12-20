CREATE TABLE notificationsCycles (id INT AUTO_INCREMENT NOT NULL, delay SMALLINT NOT NULL, display SMALLINT NOT NULL, rankingFrom SMALLINT NOT NULL, rankingTo SMALLINT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB;

INSERT INTO `notificationsCycles` (`id`, `delay`, `display`, `rankingFrom`, `rankingTo`) VALUES
	(1, 0, 30, 1, 1),
	(2, 30, 30, 1, 2),
	(3, 60, 30, 1, 3),
	(4, 90, 30, 1, 4),
	(5, 120, 30, 1, 5);

-- mvc:notifications:cycle Cykle komunikacji notifications/cycles main_menu ^^jmc:configuration:header
