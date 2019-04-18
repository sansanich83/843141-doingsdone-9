<?php
// показывать или нет выполненные задачи
$show_complete_tasks = rand(0, 1);
$page_name = 'Дела в порядке';

require_once('functions.php');
require_once('data.php');

$content = include_template('content.php', [
    'tasks' => $tasks,
    'show_complete_tasks' => $show_complete_tasks
]);
$categories = include_template('categories.php', [
    'categories' => $categories,
    'tasks' => $tasks
]);
$layout_content = include_template('layout.php', [
    'page_name' => $page_name,
    'categories' => $categories,
    'content' => $content
]);

print($layout_content);
