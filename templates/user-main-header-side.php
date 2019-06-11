<div class="main-header__side">
    <a class="main-header__side-item button button--plus open-modal" href="add.php">Добавить задачу</a>
    <div class="main-header__side-item user-menu">
        <div class="user-menu__data">
            <p>
                <?php if (!empty($user['user_name'])):
                   echo esc($user['user_name']);
                endif; ?>
            </p>
            <a href="logout.php">Выйти</a>
        </div>
    </div>
</div>
