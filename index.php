<?php

require_once('vendor/autoload.php');
require_once('functions.php');
require_once('config/db.php');

$user_content_side = '';
$sidebar = '';
$search = '';
$user = [
    'user_name' => ''
];

session_start();

if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
}

$show_complete_tasks = 1;
if (isset($_SESSION['show_completed'])) {
    $show_complete_tasks = $_SESSION['show_completed'];
}

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

$connect = mysqli_connect($db['host'], $db['user'], $db['password'], $db['database']);
mysqli_set_charset($connect, "utf8");

if (!$connect) {
    $error = mysqli_connect_error();
    print($error);
    exit;
}

if (isset($_SESSION['user'])) {
    $categories = getCategories($connect, $user['id']);
    $tasks = getTasks($connect, $user['id']);

    $all_tasks = $tasks;
}

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

if (isset($_GET['search'])) {
    $search = $_GET['search'] ?? '';
}

if ($search) {
    $safe_user_id = mysqli_real_escape_string($connect, $user['id']);
    $sql = 'SELECT task_name, deadline, status_complete, category_id, category_name, file_link FROM tasks t
    INNER JOIN categories c
    ON t.category_id = c.id
    WHERE c.user_id ='. $safe_user_id .'
    AND MATCH(task_name) AGAINST(?)';

    $stmt = db_get_prepare_stmt($connect, $sql, [$search]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $tasks = mysqli_fetch_all($result, MYSQLI_ASSOC);
}

if (!isset(($_SESSION['user']))) {
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
