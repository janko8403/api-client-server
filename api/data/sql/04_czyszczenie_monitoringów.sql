ALTER TABLE `monitoring`
	DROP FOREIGN KEY `FK_BA4F975DA2CE176B`,
	DROP FOREIGN KEY `FK_monitoring_campaignDicId2`,
	DROP FOREIGN KEY `FK_monitoring_saleStageDicId2`;

ALTER TABLE `monitoring`
    DROP COLUMN `saleStageDicId`,
    DROP COLUMN `campaignDicId`,
    DROP COLUMN `acceptanceState`,
    DROP COLUMN `isDefault`,
    DROP COLUMN `isEditable`,
    DROP COLUMN `isAutomatic`,
    DROP COLUMN `isMonitoringReplySend`,
    DROP COLUMN `numberOfCycles`,
    DROP COLUMN `numberOfDaysInCycle`,
    DROP COLUMN `questionCount`,
    DROP COLUMN `isRandomizeQuestions`,
    DROP COLUMN `isRandomizeAnswers`,
    DROP COLUMN `successPercent`,
    DROP COLUMN `elearningMode`,
    DROP COLUMN `responseTime`,
    DROP COLUMN `examLevelsSuccess`,
    DROP COLUMN `examLevelsFail`,
    DROP COLUMN `sendAfterReport`,
    DROP COLUMN `commissionRateFrom`,
    DROP COLUMN `commissionRateTo`,
    DROP COLUMN `commissionRatePercentage`,
    DROP COLUMN `commissionCorrectionFactor`,
    DROP COLUMN `commissionExpress`,
    DROP COLUMN `notificationId`,
    DROP COLUMN `additionalConsentInfo`,
    DROP COLUMN `additionalConsentLink`;

ALTER TABLE monitoring ADD symbol VARCHAR(255) NOT NULL, ADD version VARCHAR(255) NOT NULL, ADD numberTemplate VARCHAR(255) NOT NULL;

CREATE TABLE cycleDepartments (cycleId INT NOT NULL, departmentId INT NOT NULL, INDEX IDX_A3ADEB438105C8EF (cycleId), INDEX IDX_A3ADEB433CDC2CC0 (departmentId), PRIMARY KEY(cycleId, departmentId)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB;
CREATE TABLE cycleHasFeatures (cycleId INT NOT NULL, featureId INT NOT NULL, INDEX IDX_E00C64748105C8EF (cycleId), INDEX IDX_E00C6474397515B7 (featureId), PRIMARY KEY(cycleId, featureId)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB;
CREATE TABLE cycleHasNotFeatures (cycleId INT NOT NULL, featureId INT NOT NULL, INDEX IDX_3444D70B8105C8EF (cycleId), INDEX IDX_3444D70B397515B7 (featureId), PRIMARY KEY(cycleId, featureId)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB;
ALTER TABLE cycleDepartments ADD CONSTRAINT FK_A3ADEB438105C8EF FOREIGN KEY (cycleId) REFERENCES cycles (id);
ALTER TABLE cycleDepartments ADD CONSTRAINT FK_A3ADEB433CDC2CC0 FOREIGN KEY (departmentId) REFERENCES payerDepartments (id);
ALTER TABLE cycleHasFeatures ADD CONSTRAINT FK_E00C64748105C8EF FOREIGN KEY (cycleId) REFERENCES cycles (id);
ALTER TABLE cycleHasFeatures ADD CONSTRAINT FK_E00C6474397515B7 FOREIGN KEY (featureId) REFERENCES features (id);
ALTER TABLE cycleHasNotFeatures ADD CONSTRAINT FK_3444D70B8105C8EF FOREIGN KEY (cycleId) REFERENCES cycles (id);
ALTER TABLE cycleHasNotFeatures ADD CONSTRAINT FK_3444D70B397515B7 FOREIGN KEY (featureId) REFERENCES features (id);
