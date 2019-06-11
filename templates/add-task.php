<main class="content__main">
    <h2 class="content__main-heading">Добавление задачи</h2>

    <form class="form" action="add.php" method="POST" autocomplete="off" enctype="multipart/form-data">
        <div class="form__row">
            <label class="form__label" for="name">Название <sup>*</sup></label>

            <input class="form__input
                <?php if (!empty($errors['name'])): ?>
                    form__input--error
                <?php endif; ?>
            " type="text" name="name" id="name" value="<?= esc($task_name) ;?>" placeholder="Введите название">
            <p class="form__message">
                <?php if (isset($errors['name'])):
                    print($errors['name']);
                ?> <?php endif; ?>
            </p>
        </div>

        <div class="form__row">
            <label class="form__label" for="project">Проект <sup>*</sup></label>

            <select class="form__input form__input--select
                <?php if (!empty($errors['project'])): ?>
                    form__input--error
                <?php endif; ?>
            " name="project" id="project" value="<?= $project_id ;?>">
            <option value="" disabled selected style='display:none;'>Обязательно выберите из списка</option>
                <?php foreach ($categories as $key => $category): ?>
                <option value="<?php if (isset($category['id'])): echo $category['id']; endif; ?>" <?php if ((isset($category['id'])) && ($project_id == $category['id'])): ?> selected
                    <?php endif; ?>><?php if (isset($category['category_name'])): echo esc($category['category_name']); endif; ?></option>
                <?php endforeach; ?>
            </select>
            <p class="form__message">
                <?php if (isset($errors['project'])):
                    print($errors['project']);
                ?> <?php endif; ?>
            </p>
        </div>

        <div class="form__row">
            <label class="form__label" for="date">Дата выполнения</label>

            <input class="form__input form__input--date
                <?php if (!empty($errors['date'])): ?>
                    form__input--error
                <?php endif; ?>
            " type="text" name="date" id="date" value="<?= esc($task_deadline) ;?>"
                placeholder="Введите дату в формате ГГГГ-ММ-ДД">
            <p class="form__message">
                <?php if (isset($errors['date'])):
                    print($errors['date']);
                ?> <?php endif; ?>
            </p>
        </div>

        <div class="form__row">
            <label class="form__label" for="file">Файл</label>

            <div class="form__input-file">
                <input class="visually-hidden" type="file" name="file" id="file" value="">

                <label class="button button--transparent" for="file">
                    <span>Выберите файл</span>
                </label>
            </div>
        </div>

        <div class="form__row form__row--controls">
            <input class="button" type="submit" name="" value="Добавить">
        </div>
    </form>
</main>
