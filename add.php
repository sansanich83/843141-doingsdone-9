<?php
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

$sql_categories = 'SELECT category_name, c.id FROM categories c
INNER JOIN users u
ON c.user_id = u.id
WHERE u.id = 1';

$sql_tasks = 'SELECT task_name, deadline, status_complete, category_id, category_name, file_link FROM tasks t
INNER JOIN categories c
ON t.category_id = c.id
WHERE c.user_id = 1
ORDER BY t.id DESC';

$tasks_res = mysqli_query($connect, $sql_tasks);
$categories_res = mysqli_query($connect, $sql_categories);
if ($categories_res) {
    $categories = mysqli_fetch_all($categories_res, MYSQLI_ASSOC);
}
if ($tasks_res) {
    $tasks = mysqli_fetch_all($tasks_res, MYSQLI_ASSOC);
    $all_tasks = $tasks;
}

$content_add_task = include_template('add-task.php', [
    'categories' => $categories
]);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $required_fields = ['name', 'project'];
    $errors = [];

    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $errors[$field] = 'Поле обязательно для заполнения';
        }
    }
    if (($_POST['date']) && (!is_date_valid($_POST['date']))) {
        $errors['date'] = 'Нужно ввести дату в формате ГГГГ-ММ-ДД';
    } else if (($_POST['date']) && (is_old_task_date($_POST['date']))) {
        $errors['date'] = 'Дата должна быть больше или равна текущей';
    }

    $task_name = $_POST['name'];
    $project_id = $_POST['project'];
    $task_deadline = $_POST['date'];

    if (isset($_FILES['file'])) {
        $file_name = $_FILES['file']['name'];
        $file_path = __DIR__ . '/uploads/';
        $file_url = '/uploads/' . $file_name;
        move_uploaded_file($_FILES['file']['tmp_name'], $file_path . $file_name);
    }

    if (count($errors)) {
        $content_add_task = include_template('add-task.php', [
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
        VALUES (?, 4, ?, ?)';

        $stmt = db_get_prepare_stmt($connect, $sql_add_task, [$task_name, $task_deadline, $file_url]);

        $add_task_res = mysqli_stmt_execute($stmt);
        header("location: index.php");
    }
}

$categories = include_template('categories.php', [
    'categories' => $categories,
    'tasks' => $tasks,
    'all_tasks' => $all_tasks
]);
$layout_content = include_template('layout.php', [
    'page_name' => $page_name,
    'categories' => $categories,
    'content_add_task' => $content_add_task
]);

if (count($tasks) === 0) {
    http_response_code(404);
} else {
    print($layout_content);
}
