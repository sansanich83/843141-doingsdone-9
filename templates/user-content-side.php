<section class="content__side">
    <h2 class="content__side-heading">Проекты</h2>
    <nav class="main-navigation">

        <ul class="main-navigation__list">
            <?php foreach ($categories as $key => $category): ?>
                <li class="main-navigation__list-item">
                    <a class="main-navigation__list-item-link
                        <?php if (isset($_GET['cat_id']) &&  $_GET['cat_id'] === $category['id']): ?>
                            main-navigation__list-item--active
                        <?php endif; ?>
                    " href="/?cat_id=<?=$category['id'];?>"><?= esc($category['category_name'] ); ?></a>
                    <span class="main-navigation__list-item-count"><?= countByCategory($all_tasks, $category['category_name'] ); ?></span>
                </li>
            <?php endforeach; ?>
        </ul>

    </nav>
    <a class="button button--transparent button--plus content__side-button" href="add-project.php">Добавить проект</a>
</section>
