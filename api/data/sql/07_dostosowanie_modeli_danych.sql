CREATE TABLE assemblyOrders (id INT AUTO_INCREMENT NOT NULL, idMeasurementOrder INT NOT NULL, idInstallationOrder INT DEFAULT NULL, idParentInstallationOrder INT DEFAULT NULL, measurementStatus VARCHAR(255) DEFAULT NULL, installationStatus VARCHAR(255) DEFAULT NULL, idStore VARCHAR(255) NOT NULL, idUser INT DEFAULT NULL, assemblyCreatorName VARCHAR(255) DEFAULT NULL, creationDateTime DATETIME NOT NULL, updateDateTime DATETIME DEFAULT NULL, deliveryDateTime DATETIME DEFAULT NULL, expectedContactDateTime DATETIME DEFAULT NULL, expectedInstallationDateTime DATETIME DEFAULT NULL, acceptedInstallationDateTime DATETIME DEFAULT NULL, floorCarpetMeters INT DEFAULT NULL, floorPanelMeters INT DEFAULT NULL, floorWoodMeters INT DEFAULT NULL, doorNumber INT DEFAULT NULL, notificationEmail VARCHAR(255) DEFAULT NULL, estimatedCostNet DOUBLE PRECISION NOT NULL, installationCity VARCHAR(255) NOT NULL, installationAddress VARCHAR(255) NOT NULL, installationZipCode VARCHAR(255) DEFAULT NULL, installationName VARCHAR(255) NOT NULL, installationPhoneNumber VARCHAR(255) DEFAULT NULL, installationEmail VARCHAR(255) DEFAULT NULL, installationNote VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB;
CREATE TABLE assemblyOrderRankings (id INT AUTO_INCREMENT NOT NULL, position INT NOT NULL, orderId INT DEFAULT NULL, INDEX IDX_C36EC8EDFA237437 (orderId), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB;
ALTER TABLE assemblyOrderRankings ADD CONSTRAINT FK_C36EC8EDFA237437 FOREIGN KEY (orderId) REFERENCES assemblyOrders (id);

ALTER TABLE `user`	DROP FOREIGN KEY `FK_8D93D649ED9296FD`;
ALTER TABLE user DROP INDEX FK_user_regionDicId2, ADD INDEX IDX_8D93D6498809F245 (regionDicId);
ALTER TABLE user DROP INDEX fk_user_positionDicId, ADD INDEX IDX_8D93D6494237CB2C (positionDicId);
ALTER TABLE user DROP FOREIGN KEY FK_8D93D6492DAA9624;
ALTER TABLE user DROP FOREIGN KEY FK_8D93D64946AE89A8;
ALTER TABLE user DROP FOREIGN KEY FK_8D93D64996D7286D;
ALTER TABLE user DROP FOREIGN KEY FK_8D93D649F973C7AC;
DROP INDEX IDX_8D93D64996D7286D ON user;
DROP INDEX userid_mandantId ON user;
DROP INDEX mandantId_login ON user;
DROP INDEX lng ON user;
DROP INDEX lat ON user;
DROP INDEX isActivePartner ON user;
DROP INDEX IDX_8D93D649F973C7AC ON user;
DROP INDEX IDX_8D93D649ED9296FD ON user;
DROP INDEX affiliateCode ON user;
DROP INDEX IDX_8D93D64946AE89A8 ON user;
DROP INDEX IDX_8D93D6492DAA9624 ON user;
DROP INDEX FK_user_regionDicId ON user;
DROP INDEX fk__user_positionDicId ON user;
ALTER TABLE user ADD npsValue INT DEFAULT NULL, ADD dailyProductivity INT DEFAULT NULL, ADD listOfCategory VARCHAR(255) DEFAULT NULL, DROP lat, DROP lng, DROP loginToken, DROP loginLink, DROP tokenValidFrom, DROP tokenValidTo, DROP netTotalReduced, DROP timesMarginTotal, DROP profitability, DROP profitabilityThreshold, DROP onlyS3, DROP referer, DROP affiliateCode, DROP affiliatedUserId, DROP status, DROP isPremium, DROP category, DROP noTaxStatement, DROP premiumStatus, DROP isLumpSum, DROP campaign, DROP analyticsUid, DROP messageDate, DROP campaignId, DROP inviteId, DROP distance, DROP getResponseId, DROP transferType, DROP smsGrade, DROP splitDegree, DROP processStatus, DROP postCode, DROP processStatusLastUpdate, DROP isActivePartner, DROP assignedUserId, DROP locationId, DROP isAlumn, CHANGE supervisorId supervisorId INT DEFAULT NULL, CHANGE login login VARCHAR(20) NOT NULL, CHANGE phoneNumber phoneNumber VARCHAR(11) DEFAULT NULL;
ALTER TABLE user ADD CONSTRAINT FK_8D93D649E8D997BE FOREIGN KEY (supervisorId) REFERENCES user (id);
ALTER TABLE user RENAME INDEX fk_user_user TO IDX_8D93D649E8D997BE;
ALTER TABLE user RENAME INDEX fk_8d93d649f5a78c1c TO IDX_8D93D6496CD4B8D1;

DROP TRIGGER `userBlockedData_bi`;
DROP TRIGGER `user_BU`;

ALTER TABLE `customer`
	DROP COLUMN `reported`,
	DROP COLUMN `numberOfVisits`,
	DROP COLUMN `isEmailConfirmationRequired`,
	DROP COLUMN `isTransactive`,
	DROP COLUMN `latitude`,
	DROP COLUMN `longitude`,
	DROP COLUMN `creditLimit`,
	DROP COLUMN `acreage`,
	DROP COLUMN `rotation`,
	DROP COLUMN `netTotalReduced`,
	DROP COLUMN `baseTotalReduced`,
	DROP COLUMN `timesMarginTotal`,
	DROP COLUMN `profitability`,
	DROP COLUMN `profitabilityThreshold`,
	DROP COLUMN `www`,
	DROP COLUMN `creditLimitInfo`,
	DROP COLUMN `isPremium`,
	DROP COLUMN `fte`,
	DROP COLUMN `splitDegree`,
	DROP COLUMN `blockPayments`,
	DROP COLUMN `isNew`,
	DROP COLUMN `hotLead`,
	DROP COLUMN `keyAccountType`;
ALTER TABLE `customer`
	DROP COLUMN `campaignId`,
	DROP FOREIGN KEY `FK_81398E09ED9296FD`;

-- zmienić nazwę resource autocomplete na hr

