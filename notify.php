<?php

require_once('vendor/autoload.php');
require_once('functions.php');
require_once('config/db.php');

$connect = mysqli_connect($db['host'], $db['user'], $db['password'], $db['database']);
mysqli_set_charset($connect, "utf8");

if (!$connect) {
    $error = mysqli_connect_error();
    print($error);
    exit;
}

$sql = 'SELECT `email`, `id`, `user_name` FROM users';
$res = mysqli_query($connect, $sql);
$users_email = mysqli_fetch_all($res, MYSQLI_ASSOC);

foreach($users_email as $val) {
    $user_email = $val['email'];
    $user_name = $val['user_name'];
    $tasks = getTasks($connect, $val['id'], 0, 1, 1);

    if (count($tasks)) {
        $msg_content = include_template('mail-notify.php', [
            'user_name' => $user_name,
            'tasks' => $tasks
        ]);
    }

    // Конфигурация транспорта
    $transport = new Swift_SmtpTransport("phpdemo.ru", 25);
    $transport->setUsername("keks@phpdemo.ru");
    $transport->setPassword("htmlacademy");
    // Формирование сообщения
    $message = new Swift_Message();
    $message->setSubject('Уведомление от сервиса "Дела в порядке"');
    $message->setTo([$user_email => $user_name]);
    $message->setBody($msg_content, 'text/html');
    $message->setFrom("keks@phpdemo.ru", "doingsdone");
    // Отправка сообщения
    $mailer = new Swift_Mailer($transport);
    $mailer->send($message);

}
