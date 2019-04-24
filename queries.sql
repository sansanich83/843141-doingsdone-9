-- Заполняет таблицу users
INSERT INTO `doingsdone`.`users` (`date_register`, `email`, `user_name`, `hash_pass`) VALUES ('2019-04-23 15:09:13', 'admin@mail.ru', 'admin', '111');
INSERT INTO `doingsdone`.`users` (`date_register`, `email`, `user_name`, `hash_pass`) VALUES ('2019-04-23 15:11:38', 'user@mail.ru', 'user', '222');

-- Заполняет таблицу categories
INSERT INTO `doingsdone`.`categories` (`category_name`, `user_id`) VALUES ('Входящие', '1');
INSERT INTO `doingsdone`.`categories` (`category_name`, `user_id`) VALUES ('Учеба', '1');
INSERT INTO `doingsdone`.`categories` (`category_name`, `user_id`) VALUES ('Работа', '2');
INSERT INTO `doingsdone`.`categories` (`category_name`, `user_id`) VALUES ('Домашние дела', '1');
INSERT INTO `doingsdone`.`categories` (`category_name`, `user_id`) VALUES ('Авто', '2');

-- Заполняет таблицу tasks
INSERT INTO `doingsdone`.`tasks` (`task_name`, `category_id`, `date_create`, `deadline`) VALUES ('Собеседование в IT компании', '3', '2019-04-23 16:03:39', '2019-05-20');
INSERT INTO `doingsdone`.`tasks` (`task_name`, `category_id`, `date_create`, `deadline`) VALUES ('Выполнить тестовое задание', '3', '2019-04-23 16:06:27', '2018-12-21');
INSERT INTO `doingsdone`.`tasks` (`task_name`, `category_id`, `date_create`, `deadline`) VALUES ('Сделать задание первого раздела', '2', '2019-04-23 16:07:27', '2018-12-21');
INSERT INTO `doingsdone`.`tasks` (`task_name`, `category_id`, `date_create`, `deadline`) VALUES ('Собеседование в IT компании', '1', '2019-04-23 16:03:39', '2019-05-20');
INSERT INTO `doingsdone`.`tasks` (`task_name`, `category_id`, `date_create`, `deadline`) VALUES ('Купить корм для кота', '4', '2019-04-23 16:07:27', '2019-04-23');
INSERT INTO `doingsdone`.`tasks` (`task_name`, `category_id`, `date_create`, `deadline`) VALUES ('Заказать пиццу', '4', '2019-04-23 16:10:43', '2019-04-23');

-- Пометить задачу как выполненную
UPDATE `doingsdone`.`tasks` SET `status_complete`='1' WHERE  `id`=3;

-- Обновить название задачи по её идентификатору
UPDATE `doingsdone`.`tasks` SET `task_name`='Купить корм для кота и пса' WHERE  `id`=5;

-- Список из всех категорий(проектов) для одного пользователя,  имя категории(проекта)
SELECT category_name, task_name FROM categories c
LEFT JOIN tasks t
ON c.id = t.category_id
WHERE c.user_id = 1
;

-- Список из всех категорий(проектов) для одного пользователя, Список всех категорий и количество задач в каждой категории
SELECT category_name, count(task_name) AS num_task FROM categories c
LEFT JOIN tasks t
ON c.id = t.category_id
-- WHERE c.user_id = 1
GROUP BY category_name;

-- Список из всех задач для одной категории (проекта)
SELECT category_name,task_name FROM categories c
INNER JOIN tasks t
ON c.id = t.category_id
WHERE c.id = 1;
