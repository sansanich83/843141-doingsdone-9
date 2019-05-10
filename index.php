<?php
session_start();
$user = $_SESSION['user'];
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
    exit;
}

$categories = getCategories($connect, $user['id']);
$tasks = getTasks($connect, $user['id']);

$all_tasks = $tasks;

if (isset($_GET['cat_id'])) {
    $cat_id = (int) $_GET['cat_id'];
    $tasks = getTasks($connect, $user['id'], $cat_id);
}

if (!($_SESSION['user'])) {
    $content = include_template('guest.php', [
    ]);
    $main_header_side = include_template('anonim-main-header-side.php', [
    ]);
}
else {
    $content = include_template('content.php', [
        'tasks' => $tasks,
        'show_complete_tasks' => $show_complete_tasks
    ]);
    $main_header_side = include_template('user-main-header-side.php', [
        'user' => $user
    ]);
    $user_content_side = include_template('user-content-side.php', [
        'categories' => $categories,
        'tasks' => $tasks,
        'all_tasks' => $all_tasks
    ]);
    $sidebar = 1;
}

$layout_content = include_template('layout.php', [
    'main_header_side' => $main_header_side,
    'page_name' => $page_name,
    'user_content_side' => $user_content_side,
    'content' => $content,
    'user' => $user,
    'sidebar' => $sidebar
]);

    print($layout_content);
