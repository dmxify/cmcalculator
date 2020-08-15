ALTER TABLE `user`
  DROP `last_login`;

ALTER TABLE `user` ADD `user_login_id` INT NULL DEFAULT NULL AFTER `password_reset_timeout`;