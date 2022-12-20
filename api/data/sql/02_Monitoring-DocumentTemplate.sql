ALTER TABLE `monitoring`
ADD COLUMN `documentTemplateId` INT NULL DEFAULT NULL AFTER `additionalConsentLink`;
ALTER TABLE `monitoring`
ADD CONSTRAINT `FK_monitoring_documentTemplate` FOREIGN KEY (`documentTemplateId`) REFERENCES `documentTemplates` (`id`) ON UPDATE NO ACTION ON DELETE NO ACTION;

ALTER TABLE `monitoringQuestionTemplateJoint`
ADD COLUMN `rewriteTag` VARCHAR(50) NULL DEFAULT NULL AFTER `actionId`;