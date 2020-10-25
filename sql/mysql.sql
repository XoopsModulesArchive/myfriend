CREATE TABLE `{prefix}_{dirname}_friendlist` (
    `uid`        INT(8) UNSIGNED  NOT NULL DEFAULT '0',
    `friend_uid` INT(8) UNSIGNED  NOT NULL DEFAULT '0',
    `utime`      INT(11) UNSIGNED NOT NULL DEFAULT '0',
    PRIMARY KEY (`uid`, `friend_uid`)
)
    ENGINE = ISAM;

CREATE TABLE `{prefix}_{dirname}_invitation` (
    `id`     INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `uid`    INT(8) UNSIGNED  NOT NULL DEFAULT '0',
    `email`  VARCHAR(100)     NOT NULL DEFAULT '',
    `actkey` VARCHAR(50)      NOT NULL DEFAULT '',
    `utime`  INT(11) UNSIGNED NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`)
)
    ENGINE = ISAM;

CREATE TABLE `{prefix}_{dirname}_applist` (
    `id`    INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `uid`   INT(8) UNSIGNED  NOT NULL DEFAULT '0',
    `auid`  INT(8) UNSIGNED  NOT NULL DEFAULT '0',
    `utime` INT(11) UNSIGNED NOT NULL DEFAULT '0',
    `note`  TEXT,
    PRIMARY KEY (`id`)
)
    ENGINE = ISAM;
