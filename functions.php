<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

/**
 * Считает количество элментов в ассоциативный массиве с одинаковым значением
 * @param array $list исходный массив
 * @param string $значение по которому идет подсчет
 * @return int Итоговое значение
 */
function countByCategory ($list, $category) {
    $count = 0;
    foreach ($list as $key => $val) {
        if ($category === $val['category_name']) {
            $count ++;
        }
    }
    return $count;
}

/**
 * Подключает шаблон, передает туда данные и возвращает итоговый HTML контент
 * @param string $name Путь к файлу шаблона относительно папки templates
 * @param array $data Ассоциативный массив с данными для шаблона
 * @return string Итоговый HTML
 */
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

/**
 * Переводит текст в безопасное отображение - без тегов
 * @param string $str исходный текст
 * @return string $text безопасный текст
 */
function esc($str) {
	$text = htmlspecialchars($str);

	return $text;
}

/**
 * Проверяет дату на соответствие сегодняшней, и в случае истины выводит соответствующий класс
 * @param string $task_date проверяемая дата
 */
function isHotTask($task_date) {
    $curdate = time();
    $task_ts = strtotime($task_date);
    $ts_diff = ($task_ts - $curdate);
    if ($ts_diff < 86400) {
        print("task--important");
    }
}

/**
 * Проверяет дату на то что она уже прошла, возвращает истина или ложь
 * @param string $task_date проверяемая дата
 */
function is_old_task_date($task_date) {
    $curdate = time();
    $task_ts = (strtotime($task_date) + 86399);
    if ($task_ts < $curdate) {
        return true;
    }
    return false;
}

/**
 * Проверяет переданную дату на соответствие формату 'ГГГГ-ММ-ДД'
 *
 * Примеры использования:
 * is_date_valid('2019-01-01'); // true
 * is_date_valid('2016-02-29'); // true
 * is_date_valid('2019-04-31'); // false
 * is_date_valid('10.10.2010'); // false
 * is_date_valid('10/10/2010'); // false
 *
 * @param string $date Дата в виде строки
 *
 * @return bool true при совпадении с форматом 'ГГГГ-ММ-ДД', иначе false
 */
function is_date_valid(string $date) : bool {
    $format_to_check = 'Y-m-d';
    $dateTimeObj = date_create_from_format($format_to_check, $date);

    return $dateTimeObj !== false && array_sum(date_get_last_errors()) === 0;
}

/**
 * Создает подготовленное выражение на основе готового SQL запроса и переданных данных
 *
 * @param $link mysqli Ресурс соединения
 * @param $sql string SQL запрос с плейсхолдерами вместо значений
 * @param array $data Данные для вставки на место плейсхолдеров
 *
 * @return mysqli_stmt Подготовленное выражение
 */
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

/**
 * Делает запрос в БД и возвращает список категорий задач для указанного пользователя
 *
 * @param $connect Ресурс соединения
 * @param $user_id id пользователя
 */
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

/**
 * Делает запрос в БД и возвращает список задач для пользователя, и если указан вид сортировки, то возвращает соответствующие задачи
 *
 * @param $connect Ресурс соединения
 * @param $user_id id пользователя
 * @param $project_id id категории задач
 * @param $dateSort если указано:
 *      1 - задачи на сегодня
 *      2 - задачи на завтра
 *      3 - задачи просроченные
 * @param $status_actual статус выполнения задачи
 */
function getTasks(mysqli $connect, $user_id = 0, int $project_id = 0, $dateSort = 0, $status_actual = 0)
{
    $sql = 'SELECT t.id, task_name, deadline, status_complete, category_id, category_name, file_link FROM tasks t
            INNER JOIN categories c
            ON t.category_id = c.id
            WHERE c.user_id = ' . $user_id;
    if ($project_id > 0) {
        $sql .= ' AND c.id = ' . $project_id;
    }
    if ($dateSort == 1) {
        $sql .= ' AND t.deadline = CURDATE()';
    }
    if ($dateSort == 2) {
        $sql .= ' AND t.deadline = CURDATE() + INTERVAL 1 DAY ';
    }
    if ($dateSort == 3) {
        $sql .= ' AND t.deadline < CURDATE()';
    }
    if ($status_actual == 1) {
        $sql .= 'AND status_complete = 0';
    }

    $sql .= ' ORDER BY t.id DESC';
    $result = mysqli_query($connect, $sql);
    if ($result) {
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    return [];
}

/**
 * Меняет статут выполнения задачи с 1 на 0, либо с 0 на 1
 *
 * @param $connect Ресурс соединения
 * @param $t_id id задачи
 */
function toggleCompleteStatus(mysqli $connect, $t_id) {
    $sql = 'UPDATE `doingsdone`.`tasks` SET `status_complete`= ABS(`status_complete` - 1) WHERE  `id`=' . $t_id;
    mysqli_query($connect, $sql);
    header("location: index.php");
}
