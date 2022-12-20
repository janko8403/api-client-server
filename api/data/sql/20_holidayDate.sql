CREATE TABLE `holidayDates` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `date` DATE NOT NULL,
    PRIMARY KEY (`id`)
)
;

INSERT INTO `configurationResources` (`name`, `label`, `route`, `routeParams`, `icon`, `sequence`, `isUserMenu`, `isCustomerMenu`, `isHidden`, `settingSmall`, `settingMedium`, `settingLarge`, `parentId`, `cookie`, `monitoringCategoryId`, `isShortcutMenu`, `isUserDetailsMenu`)
VALUES ('mvc:configuration:holidayDates', 'Dni wolne od pracy', 'configuration/holidayDates', 's:0:"";', NULL, 101, 1, 0, 0, 1, 1, 1, 118, 'a33', NULL, 0, 0);
