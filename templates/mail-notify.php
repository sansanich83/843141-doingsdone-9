<div>
    <h3>Уведомление о сегодняшних задачах</h3>
    <p>Уважаемый <?=$user_name;?>. У вас запланированы задачи</p>
    <?php foreach ($tasks as $key => $val): ?>
        <p><?=$key +1 . '. ';?> <?=$val['task_name'];?> на <?=$val['deadline'];?> </p>
    <?php endforeach; ?>
</div>
