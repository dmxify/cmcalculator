ALTER TABLE `user` ADD `password_reset_token` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NULL DEFAULT NULL ;

ALTER TABLE `user` ADD `password_reset_timeout` DATETIME NULL DEFAULT NULL ;