CREATE DATABASE `doingsdone`;

USE `doingsdone`;

CREATE TABLE `users` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`date_register` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`email` VARCHAR(50) NOT NULL,
	`user_name` VARCHAR(50) NOT NULL,
	`hash_pass` FLOAT NOT NULL,
	PRIMARY KEY (`id`),
	UNIQUE INDEX `email` (`email`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
;

CREATE TABLE `categories` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`category` CHAR(50) NOT NULL,
	`user_id` INT(11) NOT NULL,
	PRIMARY KEY (`id`),
	UNIQUE INDEX `user_id` (`user_id`),
	CONSTRAINT `FK_categories_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
)
COLLATE='utf8_general_ci'
;

CREATE TABLE `tasks` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`task_name` VARCHAR(50) NOT NULL,
	`user_id` INT(11) NOT NULL,
	`category_id` INT(11) NOT NULL,
	`date_create` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`status_complete` TINYINT(1) NULL DEFAULT NULL,
	`file_link` VARCHAR(50) NULL DEFAULT NULL,
	`deadline` DATE NOT NULL,
	PRIMARY KEY (`id`),
	INDEX `FK_tasks_users` (`user_id`),
	INDEX `FK_tasks_categories` (`category_id`),
	CONSTRAINT `FK_tasks_categories` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
	CONSTRAINT `FK_tasks_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
;
