<?php
// показывать или нет выполненные задачи
$show_complete_tasks = rand(0, 1);
$page_name = 'Дела в порядке';
$cat_id = '0';

require_once('functions.php');
require_once('config/db.php');

$connect = mysqli_connect($db['host'], $db['user'], $db['password'], $db['database']);
mysqli_set_charset($connect, "utf8");

if (!$connect) {
    $error = mysqli_connect_error();
    print($error);
}
else {
    $sql_categories = 'SELECT category_name, c.id FROM categories c
    INNER JOIN users u
    ON c.user_id = u.id
    WHERE u.id = 1';

    $sql_tasks = 'SELECT task_name, deadline, status_complete, category_id, category_name FROM tasks t
    INNER JOIN categories c
    ON t.category_id = c.id
    WHERE c.user_id = 1';

    $tasks_res = mysqli_query($connect, $sql_tasks);
    $categories_res = mysqli_query($connect, $sql_categories);
    if ($categories_res) {
        $categories = mysqli_fetch_all($categories_res, MYSQLI_ASSOC);
    }
    if ($tasks_res) {
        $tasks = mysqli_fetch_all($tasks_res, MYSQLI_ASSOC);
        $all_tasks = $tasks;
    }

    if (isset($_GET['cat_id'])) {
        $cat_id = $_GET['cat_id'];
        $sql_tasks = 'SELECT task_name, deadline, status_complete, category_id, category_name FROM tasks t
        INNER JOIN categories c
        ON t.category_id = c.id
        WHERE c.user_id = 1 AND c.id =' . $cat_id;

        $tasks_res = mysqli_query($connect, $sql_tasks);
        if ($tasks_res) {
            $tasks = mysqli_fetch_all($tasks_res, MYSQLI_ASSOC);
        }
    }
}

$content = include_template('content.php', [
    'tasks' => $tasks,
    'show_complete_tasks' => $show_complete_tasks
]);
$categories = include_template('categories.php', [
    'categories' => $categories,
    'tasks' => $tasks,
    'all_tasks' => $all_tasks
]);
$layout_content = include_template('layout.php', [
    'page_name' => $page_name,
    'categories' => $categories,
    'content' => $content
]);

$task_count = count($tasks);

if ($task_count == 0) {
    http_response_code(404);
} else {
    print($layout_content);
}
