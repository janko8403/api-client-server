-- every application has its own current level number (from levelConfigurations)
-- which determines to whom it should be visible to accept or decline
-- the default is Entity\LevelConfiguration::FIRST_LEVEL_NUMBER
ALTER TABLE `applications`
ADD COLUMN `currentLevelNumber` INT NOT NULL DEFAULT 0 AFTER `number`;

-- brings all level configurations into one set and gives them a name
CREATE TABLE `levels` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(50) NOT NULL,
    PRIMARY KEY (`id`)
);

CREATE TABLE `levelConfigurations` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `levelId` INT NOT NULL,
    `levelNumber` INT NOT NULL,
    `supervisor` TINYINT NOT NULL DEFAULT 0,
    `cycleId` INT NULL DEFAULT NULL,
    `isTerminal` TINYINT NOT NULL DEFAULT 0,
    PRIMARY KEY (`id`)
);

ALTER TABLE `levelConfigurations`
ADD CONSTRAINT `FK_levelConfiguration_level` FOREIGN KEY (`levelId`) REFERENCES `levels` (`id`) ON UPDATE NO ACTION ON DELETE NO ACTION;

-- each level set must contain every level number only once
ALTER TABLE `levelConfigurations`
ADD UNIQUE INDEX `UNIQUE_levelId_levelNumber` (`levelId`, `levelNumber`);

-- cycleId can be null if supervisor is not null
-- or if application is on level 0 and should be accepted or declined by user who created it
ALTER TABLE `levelConfigurations`
ADD CONSTRAINT `FK_levelConfiguration_cycle` FOREIGN KEY (`cycleId`) REFERENCES `cycles` (`id`) ON UPDATE NO ACTION ON DELETE NO ACTION;

-- every monitoring has to have a defined level
ALTER TABLE `monitoring`
ADD COLUMN `levelId` INT NULL DEFAULT NULL AFTER `newCycleId`;

ALTER TABLE `monitoring`
ADD CONSTRAINT `FK_monitoring_level` FOREIGN KEY (`levelId`) REFERENCES `levels` (`id`) ON UPDATE NO ACTION ON DELETE NO ACTION;

CREATE TABLE `applicationsHistory` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `applicationId` INT NOT NULL,
    `action` INT NOT NULL,
    `userId` INT NOT NULL,
    `dateTime` DATETIME NOT NULL,
    `fromLevelNumber` INT NOT NULL,
    `toLevelNumber` INT NULL,
    PRIMARY KEY (`id`)
);

ALTER TABLE `applicationsHistory`
ADD CONSTRAINT `FK_applicationsHistory_application` FOREIGN KEY (`applicationId`) REFERENCES `applications` (`id`) ON UPDATE NO ACTION ON DELETE NO ACTION,
ADD CONSTRAINT `FK_applicationsHistory_user` FOREIGN KEY (`userId`) REFERENCES `user` (`id`) ON UPDATE NO ACTION ON DELETE NO ACTION;

-- levels resource
INSERT INTO `configurationResources` (`name`, `label`, `route`, `routeParams`, `icon`, `sequence`, `isUserMenu`, `isCustomerMenu`, `isHidden`, `settingSmall`, `settingMedium`, `settingLarge`, `parentId`, `cookie`, `isUserDetailsMenu`)
VALUES ('mvc:levels', 'Poziomy', 'levels', 's:0:"";', 'refresh', '11', '1', '0', '0', '1', '1', '1', '119', 'a6b21', '0');

INSERT INTO `configurationResourcePositions` (`settingSmall`, `settingMedium`, `settingLarge`, `resourceId`, `positionId`)
VALUES (1, 1, 1, 225, 1);
INSERT INTO `configurationResourcePositions` (`settingSmall`, `settingMedium`, `settingLarge`, `resourceId`, `positionId`)
VALUES (2, 2, 2, 225, 3);
INSERT INTO `configurationResourcePositions` (`settingSmall`, `settingMedium`, `settingLarge`, `resourceId`, `positionId`)
VALUES (2, 2, 2, 225, 4);
INSERT INTO `configurationResourcePositions` (`settingSmall`, `settingMedium`, `settingLarge`, `resourceId`, `positionId`)
VALUES (2, 2, 2, 225, 5);
INSERT INTO `configurationResourcePositions` (`settingSmall`, `settingMedium`, `settingLarge`, `resourceId`, `positionId`)
VALUES (2, 2, 2, 225, 6);
INSERT INTO `configurationResourcePositions` (`settingSmall`, `settingMedium`, `settingLarge`, `resourceId`, `positionId`)
VALUES (1, 1, 1, 225, 8);
INSERT INTO `configurationResourcePositions` (`settingSmall`, `settingMedium`, `settingLarge`, `resourceId`, `positionId`)
VALUES (1, 1, 1, 225, 7);
INSERT INTO `configurationResourcePositions` (`settingSmall`, `settingMedium`, `settingLarge`, `resourceId`, `positionId`)
VALUES (1, 1, 1, 225, 9);
INSERT INTO `configurationResourcePositions` (`settingSmall`, `settingMedium`, `settingLarge`, `resourceId`, `positionId`)
VALUES (1, 1, 1, 225, 2);
INSERT INTO `configurationResourcePositions` (`settingSmall`, `settingMedium`, `settingLarge`, `resourceId`, `positionId`)
VALUES (1, 1, 1, 225, 11);
INSERT INTO `configurationResourcePositions` (`settingSmall`, `settingMedium`, `settingLarge`, `resourceId`, `positionId`)
VALUES (2, 2, 2, 225, 14);
INSERT INTO `configurationResourcePositions` (`settingSmall`, `settingMedium`, `settingLarge`, `resourceId`, `positionId`)
VALUES (2, 2, 2, 225, 10);
INSERT INTO `configurationResourcePositions` (`settingSmall`, `settingMedium`, `settingLarge`, `resourceId`, `positionId`)
VALUES (2, 2, 2, 225, 12);
INSERT INTO `configurationResourcePositions` (`settingSmall`, `settingMedium`, `settingLarge`, `resourceId`, `positionId`)
VALUES (2, 2, 2, 225, 13);
INSERT INTO `configurationResourcePositions` (`settingSmall`, `settingMedium`, `settingLarge`, `resourceId`, `positionId`)
VALUES (2, 2, 2, 225, 15);
INSERT INTO `configurationResourcePositions` (`settingSmall`, `settingMedium`, `settingLarge`, `resourceId`, `positionId`)
VALUES (2, 2, 2, 225, 16);

