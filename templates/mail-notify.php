<div>
    <h3>Уведомление о сегодняшних задачах</h3>
    <p>Уважаемый <?=$user_name;?>. У вас запланированы задачи</p>
    <?php foreach ($tasks as $key => $val): ?>
        <p><?=$key +1 . '. ';?> <?php if (isset($val['task_name'])): echo esc($val['task_name']); endif; ?> на <?php if (isset($val['deadline'])): echo esc($val['deadline']); endif; ?> </p>
    <?php endforeach; ?>
</div>
