<h2 class="content__main-heading">Список задач</h2>

<form class="search-form" action="index.php" method="post" autocomplete="off">
    <input class="search-form__input" type="text" name="" value="" placeholder="Поиск по задачам">

    <input class="search-form__submit" type="submit" name="" value="Искать">
</form>

<div class="tasks-controls">
    <nav class="tasks-switch">
        <a href="/" class="tasks-switch__item tasks-switch__item--active">Все задачи</a>
        <a href="/" class="tasks-switch__item">Повестка дня</a>
        <a href="/" class="tasks-switch__item">Завтра</a>
        <a href="/" class="tasks-switch__item">Просроченные</a>
    </nav>
    <label class="checkbox">
        <!--добавить сюда аттрибут "checked", если переменная $show_complete_tasks равна единице-->
        <input class="checkbox__input visually-hidden show_completed" type="checkbox"
            <?php if ($show_complete_tasks === 1): ?> checked <?php endif; ?>>
        <span class="checkbox__text">Показывать выполненные</span>
    </label>
</div>

<table class="tasks">
    <?php foreach ($tasks as $key => $val): ?>
    <tr class="tasks__item task
            <?php
            if ($val['status_complete'] === '1'): echo "task--completed";
            else: isHotTask($val['deadline']);
            endif;
            ?>
        <?php if (($val['status_complete'] === '1') && ($show_complete_tasks === 0)): ?>
            visually-hidden
        <?php endif; ?>">
        <td class="task__select">
            <label class="checkbox task__checkbox">
                <input class="checkbox__input visually-hidden task__checkbox"
                        <?php if ($val['status_complete'] === '1'): ?>
                            checked
                        <?php endif; ?>
                    type="checkbox" value="1">
                <span class="checkbox__text"><?=esc($val['task_name']); ?></span>
            </label>
        </td>
        <td class="task__file">
            <a class="download-link <?php if ($val['file_link'] === NULL):?>visually-hidden<?php endif;?>
            "href="<?= $val['file_link'] ;?>"><?= basename($val['file_link']) ;?></a>
        </td>
        <td class="task__date"><?= $val['deadline'] ;?></td>
    </tr>
    <?php endforeach; ?>
</table>
