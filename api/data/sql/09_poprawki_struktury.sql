DROP TRIGGER `updateUserLoginDate`;
delimiter //
CREATE TRIGGER `updateUserLoginDate` AFTER INSERT ON `oauth_access_tokens` FOR EACH ROW begin
update user set lastLogin = now() where login = NEW.user_id;
END//
delimiter ;

ALTER TABLE assemblyOrderRankings ADD userId INT DEFAULT NULL;
ALTER TABLE assemblyOrderRankings ADD CONSTRAINT FK_C36EC8ED64B64DCC FOREIGN KEY (userId) REFERENCES user (id);
CREATE INDEX IDX_C36EC8ED64B64DCC ON assemblyOrderRankings (userId);
