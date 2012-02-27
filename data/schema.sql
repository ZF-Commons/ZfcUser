CREATE TABLE user
(
    user_id       INTEGER PRIMARY KEY AUTO_INCREMENT NOT NULL,
    username      VARCHAR(255) DEFAULT NULL UNIQUE,
    email         VARCHAR(255) NOT NULL UNIQUE,
    display_name  VARCHAR(50) DEFAULT NULL,
    password      VARCHAR(128) NOT NULL,
    last_login    DATETIME DEFAULT NULL,
    last_ip       INTEGER DEFAULT NULL,
    register_time DATETIME NOT NULL,
    register_ip   INTEGER NOT NULL,
    active        TINYINT(1) NOT NULL,
    enabled       TINYINT(1) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE user_activation
(
    id            INTEGER PRIMARY KEY AUTO_INCREMENT NOT NULL,
    user_id       INTEGER NOT NULL,
    token         VARCHAR(16) NOT NULL,
    request_time  DATETIME DEFAULT NULL,
    valid_to      DATETIME NOT NULL,
    request_ip    INTEGER NOT NULL,
    confirm_time  DATETIME NOT NULL,
    confirm_ip    INTEGER NOT NULL,
    active        TINYINT(1) NOT NULL
    KEY `token_uq` (token),
    KEY `fk_user_activation_user` (user_id),
    CONSTRAINT `fk_user_activation_user` FOREIGN KEY (user_id) REFERENCES `user` (user_id)
) ENGINE=InnoDB;

CREATE TABLE user_meta
(
    meta_key VARCHAR(255) NOT NULL,
    user_id  INTEGER NOT NULL,
    meta     LONGTEXT NOT NULL,
    PRIMARY KEY(meta_key, user_id),
    FOREIGN KEY (user_id) REFERENCES user (user_id)
) ENGINE=InnoDB;
