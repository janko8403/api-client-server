ALTER VIEW `oauth_users` AS select `u`.`login` AS `username`,`u`.`password` AS `password`,`u`.`name` AS `first_name`,`u`.`surname` AS `last_name`,if((`u`.`configurationPositionId` = 7),'partner','client') AS `scope` from `user` `u` where (`u`.`isActive` = 1) ;
ALTER TABLE `user`
	DROP COLUMN `passwordBC`;
ALTER TABLE user ADD company VARCHAR(255) DEFAULT NULL;
UPDATE `configurationResources` SET `name`='mvc:hr:autocomplete' WHERE  `id`=103;
