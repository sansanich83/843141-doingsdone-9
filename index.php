<?php
// показывать или нет выполненные задачи
$show_complete_tasks = rand(0, 1);
$page_name = 'Дела в порядке';

require_once('functions.php');
require_once('config/db.php');

$connect = mysqli_connect($db['host'], $db['user'], $db['password'], $db['database']);
mysqli_set_charset($connect, "utf8");

if (!$connect) {
    $error = mysqli_connect_error();
    print($error);
}
else {
    $sql_cat_list = 'SELECT category_name FROM categories c
    INNER JOIN users u
    ON c.user_id = u.id
    WHERE u.id = 1';

    $sql_task_list = 'SELECT task_name, deadline, status_complete, category_id, category_name FROM tasks t
    INNER JOIN categories c
    ON t.category_id = c.id
    WHERE c.user_id = 1';

    $task_list = mysqli_query($connect, $sql_task_list);
    $cat_list = mysqli_query($connect, $sql_cat_list);
    if ($cat_list) {
        $categories = mysqli_fetch_all($cat_list, MYSQLI_ASSOC);
    }
    if ($task_list) {
        $tasks = mysqli_fetch_all($task_list, MYSQLI_ASSOC);
    }
}


$content = include_template('content.php', [
    'tasks' => $tasks,
    'show_complete_tasks' => $show_complete_tasks
]);
$categories = include_template('categories.php', [
    'categories' => $categories,
    'tasks' => $tasks
]);
$layout_content = include_template('layout.php', [
    'page_name' => $page_name,
    'categories' => $categories,
    'content' => $content
]);

print($layout_content);
