<h2 class="content__main-heading">Список задач</h2>

<form class="search-form" action="index.php" method="GET" autocomplete="off">
    <input class="search-form__input" type="text" name="search" value="" placeholder="Поиск по задачам">

    <input class="search-form__submit" type="submit" name="" value="Искать">
</form>

<div class="tasks-controls">
    <nav class="tasks-switch">
        <a href="/?deadline=0" class="tasks-switch__item
            <?php if (isset($_GET['deadline']) &&  $_GET['deadline'] == 0): ?>
                tasks-switch__item--active
            <?php endif; ?>
        ">Все задачи</a>
        <a href="/?deadline=1" class="tasks-switch__item
            <?php if (isset($_GET['deadline']) &&  $_GET['deadline'] == 1): ?>
                tasks-switch__item--active
            <?php endif; ?>
        ">Повестка дня</a>
        <a href="/?deadline=2" class="tasks-switch__item
            <?php if (isset($_GET['deadline']) &&  $_GET['deadline'] == 2): ?>
                tasks-switch__item--active
            <?php endif; ?>
        ">Завтра</a>
        <a href="/?deadline=3" class="tasks-switch__item
            <?php if (isset($_GET['deadline']) &&  $_GET['deadline'] == 3): ?>
                tasks-switch__item--active
            <?php endif; ?>
        ">Просроченные</a>
    </nav>
    <label class="checkbox">
        <!--добавить сюда аттрибут "checked", если переменная $show_complete_tasks равна единице-->
        <input class="checkbox__input visually-hidden show_completed" type="checkbox"
            <?php if ($show_complete_tasks === 1): ?> checked <?php endif; ?>
        >
        <span class="checkbox__text">Показывать выполненные</span>
    </label>
</div>

<table class="tasks">
    <?php foreach ($tasks as $key => $val): ?>
    <tr class="tasks__item task
            <?php
            if ((isset($val['status_complete'])) && ($val['status_complete'] === '1')): echo "task--completed";
            else: isHotTask($val['deadline']);
            endif;
            ?>
        <?php if ((isset($val['status_complete'])) && ($val['status_complete'] === '1') && ($show_complete_tasks === 0)): ?>
            visually-hidden
        <?php endif; ?>">
        <td class="task__select">
            <label class="checkbox task__checkbox">
                <input class="checkbox__input visually-hidden task__checkbox"
                        <?php if ((isset($val['status_complete'])) && ($val['status_complete'] === '1')): ?>
                            checked
                        <?php endif; ?>
                        type="checkbox" value="
                        <?php if (isset($val['id'])):
                            echo $val['id'];
                        endif; ?>">
                <span class="checkbox__text"><?php if (isset($val['task_name'])): echo esc($val['task_name']); endif; ?></span>
            </label>
        </td>
        <td class="task__file">
            <a class="download-link <?php if (empty($val['file_link'])):?>visually-hidden<?php endif;?>
            "href="<?php if (isset($val['file_link'])): echo $val['file_link']; endif; ?>"><?php if (isset($val['file_link'])): echo basename($val['file_link']); endif; ?></a>
        </td>
        <td class="task__date"><?php if (isset($val['deadline'])): echo $val['deadline']; endif;?></td>
    </tr>
    <?php endforeach; ?>
    <p>
        <?php if (empty($tasks)):
            print('Ничего не найдено по вашему запросу');
        ?> <?php endif; ?>
    </p>
</table>
