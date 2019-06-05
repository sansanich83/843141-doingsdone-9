<?php

require_once('functions.php');
require_once('config/db.php');

$connect = mysqli_connect($db['host'], $db['user'], $db['password'], $db['database']);
mysqli_set_charset($connect, "utf8");

if (!$connect) {
    $error = mysqli_connect_error();
    print($error);
    exit;
}

session_start();

if (isset($_SESSION['user'])) {
    header("location: index.php");
}

$page_name = 'Дела в порядке авторизация';
$user_password = '';
$user_email = '';
$errors = [
    'email' => '',
    'password' => ''
];

$authorization = include_template('auth.php', [
    'user_email' => $user_email,
    'user_password' => $user_password,
    'errors' => $errors
]);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $required_fields = ['email', 'password'];
    $errors = [];

    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $errors[$field] = 'Поле обязательно для заполнения';
        }
    }
    if (($_POST['email']) && (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))) {
        $errors['email'] = 'Введите валидный емэйл';
    }

    $user_email = $_POST['email'];
    $user_password = $_POST['password'];
    $hash_pass = password_hash($_POST['password'], PASSWORD_DEFAULT);

    if (count($errors)) {

        $authorization = include_template('auth.php', [
            'errors' => $errors,
            'user_email' => $user_email,
            'user_password' => $user_password
        ]);

    } else {
        $email = mysqli_real_escape_string($connect, $_POST['email']);
        $sql = "SELECT * FROM users WHERE email = '$email'";
        $res = mysqli_query($connect, $sql);
        $user = $res ? mysqli_fetch_array($res, MYSQLI_ASSOC) : null;

        if ($user) {
            if (password_verify($_POST['password'], $user['hash_pass'])) {
                $_SESSION['user'] = $user;

                if (isset($_SESSION['user'])) {
                    header("location: index.php");
                }

            }
            else {
                $errors['password'] = 'Неверный пароль';
                $errors['email'] = '';
            }
        }
        else {
            $errors['email'] = 'Такой пользователь не найден';
            $errors['password'] = '';
        }

        $authorization = include_template('auth.php', [
            'errors' => $errors,
            'user_email' => $user_email,
            'user_password' => $user_password
        ]);
    }
}

$content = $authorization;
$user_content_side = include_template('anonim-content-side.php', [
]);

$sidebar = 1;
$main_header_side = include_template('anonim-main-header-side.php', [
]);

$layout_content = include_template('layout.php', [
    'main_header_side' => $main_header_side,
    'page_name' => $page_name,
    'user_content_side' => $user_content_side,
    'content' => $content,
    'sidebar' => $sidebar
]);

print($layout_content);
