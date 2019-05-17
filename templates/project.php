<main class="content__main">
    <h2 class="content__main-heading">Добавление проекта</h2>

    <form class="form" action="add-project.php" method="POST" autocomplete="off">
        <div class="form__row">
            <label class="form__label" for="project_name">Название <sup>*</sup></label>

            <input class="form__input
                <?php if ($errors['name']): ?>
                    form__input--error
                <?php endif; ?>
            " type="text" name="name" id="project_name" value="<?= esc($project_name) ;?>"
                placeholder="Введите название проекта">
            <p class="form__message">
                <?php if ($errors['name']):
                    print($errors['name']);
                ?> <?php endif; ?>
            </p>
        </div>

        <div class="form__row form__row--controls">
            <input class="button" type="submit" name="" value="Добавить">
        </div>
    </form>
</main>
