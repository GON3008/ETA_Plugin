CREATE TABLE IF NOT EXISTS `estimate_task` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `task_id` int(11) NOT NULL,
    `user_id` int(11) NOT NULL,
    `time` int(11) NOT NULL,
    `deleted` tinyint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; #

CREATE TABLE IF NOT EXISTS `confirm_estimate_task` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `estimate_id` int(11) NOT NULL,
    `confirm_user_id` int(11) NOT NULL,
    `confirm_at` datetime NOT NULL,
    `deleted` tinyint(1) NOT NULL DEFAULT '0',
    `confirmed` tinyint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; #
