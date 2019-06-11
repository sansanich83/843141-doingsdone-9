<?php
$page_name = 'Дела в порядке добавление задачи';
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

if (isset($_SESSION['user'])) {
    $categories = getCategories($connect, $user['id']);
    $tasks = getTasks($connect, $user['id']);
}


$all_tasks = $tasks;
$task_name = '';
$project_id = '';
$task_deadline = '';
$errors = [
    'name' => '',
    'project' => '',
    'date' => ''
];

$content = include_template('add-task.php', [
    'categories' => $categories,
    'task_name' => $task_name,
    'project_id' => $project_id,
    'task_deadline' => $task_deadline,
    'errors' => $errors
]);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $required_fields = ['name', 'project'];
    $errors = [];

    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $errors[$field] = 'Поле обязательно для заполнения';
        }
    }
    if ((!empty($_POST['date'])) && (!is_date_valid($_POST['date']))) {
        $errors['date'] = 'Нужно ввести дату в формате ГГГГ-ММ-ДД';
    } else if (!empty(($_POST['date'])) && (is_old_task_date($_POST['date']))) {
        $errors['date'] = 'Дата должна быть больше или равна текущей';
    }

    if (isset($_POST['name'])) {
        $task_name = $_POST['name'];
    }

    if(!isset($_POST['project'])){
        $_POST['project'] = 0;
    }

    if (isset($_POST['project'])){
        $project_id = $_POST['project'];
    }

    if (isset($_POST['date'])){
        $task_deadline = $_POST['date'];
    }

    if (isset($_FILES['file'])) {
        $file_name = $_FILES['file']['name'];
        $file_path = __DIR__ . '/uploads/';
        $file_url = '/uploads/' . $file_name;
        move_uploaded_file($_FILES['file']['tmp_name'], $file_path . $file_name);
    }
    if ((isset($_POST['name'])) && (strlen($_POST['name']) > 50)) {
        $errors['name'] = 'Слишком длинное название';
    }

    if (count($errors)) {
        $content = include_template('add-task.php', [
            'task_name' => $task_name,
            'project_id' => $project_id,
            'task_deadline' => $task_deadline,
            'categories' => $categories,
            'errors' => $errors,
            'file_name' => $file_name
        ]);

    } else {
        if (!$task_deadline) {$task_deadline = NULL;}
        if (!$file_name) {$file_url = NULL;}
        $sql_add_task = 'INSERT INTO `doingsdone`.`tasks` (`task_name`, `category_id`, `deadline`, `file_link`)
        VALUES (?, ?, ?, ?)';

        $stmt = db_get_prepare_stmt($connect, $sql_add_task, [$task_name, $project_id, $task_deadline, $file_url]);

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
