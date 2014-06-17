CREATE TABLE user
(
    user_id       int(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
    username      VARCHAR(255) DEFAULT NULL UNIQUE,
    email         VARCHAR(255) DEFAULT NULL UNIQUE,
    display_name  VARCHAR(50) DEFAULT NULL,
    password      VARCHAR(128) NOT NULL,
    state         SMALLINT
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
