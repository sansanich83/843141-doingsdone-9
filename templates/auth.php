<main class="content__main">
    <h2 class="content__main-heading">Вход на сайт</h2>
    <form class="form" action="authorization.php" method="POST" autocomplete="off">
        <div class="form__row">
            <label class="form__label" for="email">E-mail <sup>*</sup></label>

            <input class="form__input
            <?php if ($errors['email']): ?>
                form__input--error
            <?php endif; ?>
        " type="text" name="email" id="email" value="<?= $user_email ;?>" placeholder="Введите e-mail">
            <p class="form__message">
                <?php if ($errors['email']):
                print($errors['email']);
            ?> <?php endif; ?>
            </p>
        </div>

        <div class="form__row">
            <label class="form__label" for="password">Пароль <sup>*</sup></label>
            <input class="form__input
            <?php if ($errors['password']): ?>
                form__input--error
            <?php endif; ?>
        " type="password" name="password" id="password" value="<?= $user_password ;?>" placeholder="Введите пароль">
            <p class="form__message">
                <?php if ($errors['password']):
                print($errors['password']);
            ?> <?php endif; ?>
            </p>
        </div>

        <div class="form__row form__row--controls">
            <input class="button" type="submit" name="" value="Войти">
        </div>
    </form>
</main>
