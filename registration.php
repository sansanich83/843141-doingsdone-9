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

$registration = include_template('register.php', []);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $required_fields = ['email', 'password', 'name'];
    $errors = [];

    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $errors[$field] = 'Поле обязательно для заполнения';
        }
    }
    if (($_POST['email']) && (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))) {
        $errors['email'] = 'Введите валидный емэйл';
    }
    if (empty($errors)) {
        $email = mysqli_real_escape_string($connect, $_POST['email']);
        $sql = "SELECT id FROM users WHERE email = '$email'";
        $res = mysqli_query($connect, $sql);
        if (mysqli_num_rows($res) > 0) {
            $errors['email'] = 'Пользователь с этим email уже зарегистрирован';
        }
    }

    $user_name = $_POST['name'];
    $user_email = $_POST['email'];
    $user_password = $_POST['password'];
    $fix = 'Пожалуйста, исправьте ошибки в форме';
    $hash_pass = password_hash($_POST['password'], PASSWORD_DEFAULT);

    if (count($errors)) {

        $registration = include_template('register.php', [
            'fix' => $fix,
            'errors' => $errors,
            'user_name' => $user_name,
            'user_email' => $user_email,
            'user_password' => $user_password
        ]);

    } else {
        $sql_add_user = 'INSERT INTO `doingsdone`.`users` (`email`, `user_name`, `hash_pass`) VALUES (?, ?, ?)';

        $stmt = db_get_prepare_stmt($connect, $sql_add_user, [$user_email, $user_name, $hash_pass]);

        $add_user_res = mysqli_stmt_execute($stmt);

        header("location: index.php");
    }
}

print($registration);
