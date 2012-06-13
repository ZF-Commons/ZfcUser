CREATE TABLE user
(
    user_id       INTEGER PRIMARY KEY AUTO_INCREMENT NOT NULL,
    username      VARCHAR(255) DEFAULT NULL UNIQUE,
    email         VARCHAR(255) NOT NULL UNIQUE,
    display_name  VARCHAR(50) DEFAULT NULL,
    password      VARCHAR(128) NOT NULL,
    last_login    DATETIME DEFAULT NULL,
    last_ip       VARCHAR(45) DEFAULT NULL,
    register_time DATETIME NOT NULL,
    register_ip   VARCHAR(45) NOT NULL,
    active        TINYINT(1) NOT NULL,
    enabled       TINYINT(1) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE user_meta 
(
    meta_key VARCHAR(255) NOT NULL,
    user_id  INTEGER NOT NULL,
    meta     LONGTEXT NOT NULL,
    PRIMARY KEY(meta_key, user_id),
    FOREIGN KEY (user_id) REFERENCES user (user_id)
) ENGINE=InnoDB;
