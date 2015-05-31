CREATE TABLE `user`
(
    `user`          INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `username`      VARCHAR(255) DEFAULT NULL UNIQUE,
    `email`         VARCHAR(255) DEFAULT NULL UNIQUE,
    `display_name`  VARCHAR(50) DEFAULT NULL,
    `password`      VARCHAR(128) NOT NULL,
    `state`         SMALLINT UNSIGNED
) ENGINE=InnoDB CHARSET="utf8";
