<?php
$page_name = 'Дела в порядке';
$cat_id = '0';
session_start();

if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
}

require_once('functions.php');
require_once('config/db.php');

$connect = mysqli_connect($db['host'], $db['user'], $db['password'], $db['database']);
mysqli_set_charset($connect, "utf8");

if (!$connect) {
    $error = mysqli_connect_error();
    print($error);
    exit;
}

if (isset($user['id'])) {
    $categories = getCategories($connect, $user['id']);
    $tasks = getTasks($connect, $user['id']);
}


$all_tasks = $tasks;
$project_name = '';
$errors = ['name' => ''];

$content = include_template('project.php', [
    'project_name' => $project_name,
    'errors' => $errors
]);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $required_fields = ['name'];
    $errors = [];

    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $errors[$field] = 'Поле обязательно для заполнения';
        }
    }

    if ((isset($_POST['name'])) && (strlen($_POST['name']) > 50)) {
        $errors['name'] = 'Слишком длинное название';
    }

    if (isset($_POST['name'])) {
       $project_name = $_POST['name'];
    }

    if (count($errors)) {
        $content = include_template('project.php', [
            'project_name' => $project_name,
            'errors' => $errors
        ]);

    } else {
        $sql_add_project = 'INSERT INTO `doingsdone`.`categories` (`category_name`, `user_id`)
        VALUES (?, ?)';

        $stmt = db_get_prepare_stmt($connect, $sql_add_project, [$project_name, $user['id']]);

        $add_task_res = mysqli_stmt_execute($stmt);
        header("location: index.php");
    }
}
$sidebar = 1;
$user_content_side = include_template('user-content-side.php', [
    'categories' => $categories,
    'tasks' => $tasks,
    'all_tasks' => $all_tasks
]);
$main_header_side = include_template('user-main-header-side.php', [
    'user' => $user
]);
$layout_content = include_template('layout.php', [
    'main_header_side' => $main_header_side,
    'page_name' => $page_name,
    'user_content_side' => $user_content_side,
    'content' => $content,
    'sidebar' => $sidebar
]);

print($layout_content);
