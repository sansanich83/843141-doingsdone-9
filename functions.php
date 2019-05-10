<?php

function countByCategory ($list, $category) {
    $count = 0;
    foreach ($list as $key => $val) {
        if ($category === $val['category_name']) {
            $count ++;
        }
    }
    return $count;
}

function include_template($name, array $data = []) {
    $name = 'templates/' . $name;
    $result = '';

    if (!is_readable($name)) {
        return $result;
    }

    ob_start();
    extract($data);
    require $name;

    $result = ob_get_clean();

    return $result;
}

function esc($str) {
	$text = htmlspecialchars($str);

	return $text;
}

function isHotTask($task_date) {
    $curdate = time();
    $task_ts = strtotime($task_date);
    $ts_diff = ($task_ts - $curdate);
    if ($ts_diff < 86400) {
        print("task--important");
    }
}

function is_old_task_date($task_date) {
    $curdate = time();
    $task_ts = (strtotime($task_date) + 86399);
    if ($task_ts < $curdate) {
        return true;
    }
    return false;
}

function is_date_valid(string $date) : bool {
    $format_to_check = 'Y-m-d';
    $dateTimeObj = date_create_from_format($format_to_check, $date);

    return $dateTimeObj !== false && array_sum(date_get_last_errors()) === 0;
}

function db_get_prepare_stmt($link, $sql, $data = []) {
    $stmt = mysqli_prepare($link, $sql);

    if ($stmt === false) {
        $errorMsg = 'Не удалось инициализировать подготовленное выражение: ' . mysqli_error($link);
        die($errorMsg);
    }

    if ($data) {
        $types = '';
        $stmt_data = [];

        foreach ($data as $value) {
            $type = 's';

            if (is_int($value)) {
                $type = 'i';
            }
            else if (is_string($value)) {
                $type = 's';
            }
            else if (is_double($value)) {
                $type = 'd';
            }

            if ($type) {
                $types .= $type;
                $stmt_data[] = $value;
            }
        }

        $values = array_merge([$stmt, $types], $stmt_data);

        $func = 'mysqli_stmt_bind_param';
        $func(...$values);

        if (mysqli_errno($link) > 0) {
            $errorMsg = 'Не удалось связать подготовленное выражение с параметрами: ' . mysqli_error($link);
            die($errorMsg);
        }
    }

    return $stmt;
}

function getCategories(mysqli $connect, $user_id = 0)
{
    $sql = 'SELECT id, category_name FROM categories
            WHERE user_id = ' . $user_id;
    $result = mysqli_query($connect, $sql);
    if ($result) {
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    return [];
}

function getTasks(mysqli $connect, $user_id = 0, int $project_id = 0)
{
    $sql = 'SELECT task_name, deadline, status_complete, category_id, category_name, file_link FROM tasks t
            INNER JOIN categories c
            ON t.category_id = c.id
            WHERE c.user_id = ' . $user_id;
    if ($project_id > 0) {
        $sql .= ' AND c.id = ' . $project_id;
    }
    $sql .= ' ORDER BY t.id DESC';
    $result = mysqli_query($connect, $sql);
    if ($result) {
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    return [];
}
