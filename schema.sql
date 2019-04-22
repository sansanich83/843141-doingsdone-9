CREATE DATABASE `doingsdone`;

USE `doingsdone`;

CREATE TABLE `categories` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`category` CHAR(50) NOT NULL,
	PRIMARY KEY (`id`),
	UNIQUE INDEX `category` (`category`)
)
COLLATE='utf8_general_ci'
;


CREATE TABLE `tasks` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`task_name` VARCHAR(50) NOT NULL,
	`date_create` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`status_complete` TINYINT(1) NULL DEFAULT NULL,
	`file_link` VARCHAR(50) NULL DEFAULT NULL,
	`deadline` DATE NOT NULL,
	PRIMARY KEY (`id`)
)
COLLATE='utf8_general_ci'
;

CREATE TABLE `users` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`date_register` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`email` VARCHAR(50) NOT NULL,
	`user_name` VARCHAR(50) NOT NULL,
	`hash_pass` FLOAT NOT NULL,
	PRIMARY KEY (`id`),
	UNIQUE INDEX `email` (`email`)
)
COLLATE='utf8_general_ci'
;

