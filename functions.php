<?php

function countByCategory ($list, $category) {
    $count = 0;
    foreach ($list as $key => $val) {
        if ($category === $val['Категория']) {
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
