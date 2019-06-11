<section class="content__side">
    <h2 class="content__side-heading">Проекты</h2>
    <nav class="main-navigation">

        <ul class="main-navigation__list">
            <?php foreach ($categories as $key => $category): ?>
                <li class="main-navigation__list-item">
                    <a class="main-navigation__list-item-link
                        <?php if (isset($_GET['cat_id']) && (isset($category['id'])) &&  $_GET['cat_id'] === $category['id']): ?>
                            main-navigation__list-item--active
                        <?php endif; ?>
                    " href="/?cat_id=<?=$category['id'];?>"><?php if (isset($category['category_name'])): echo esc($category['category_name'] ); endif; ?></a>
                    <span class="main-navigation__list-item-count"><?php if (isset($category['category_name'])): echo countByCategory($all_tasks, $category['category_name'] ); endif; ?></span>
                </li>
            <?php endforeach; ?>
        </ul>

    </nav>
    <a class="button button--transparent button--plus content__side-button" href="add-project.php">Добавить проект</a>
</section>
