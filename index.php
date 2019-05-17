<?php
session_start();
$user = $_SESSION['user'];
$show_complete_tasks = $_SESSION['show_completed'];
if ((isset($_GET['show_completed'])) && ($_GET['show_completed'] === '0')) {
    $show_complete_tasks = 0;
    $_SESSION['show_completed'] = 0;
}
else if ((isset($_GET['show_completed'])) && ($_GET['show_completed'] === '1')) {
    $show_complete_tasks = 1;
    $_SESSION['show_completed'] = 1;
}

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

if (isset($_GET['task_id']) && isset(($_GET['check']))) {
    toggleCompleteStatus($connect, $_GET['task_id']);
}

if (isset($_GET['deadline'])) {
    $curDate = $_GET['deadline'];
    $tasks = getTasks($connect, $user['id'], 0, $curDate);
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
