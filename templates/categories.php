<?php foreach ($categories as $key => $category): ?>
<li class="main-navigation__list-item">
    <a class="main-navigation__list-item-link" href="#"><?=esc($category['category_name'] );?></a>
    <span class="main-navigation__list-item-count"><?=countByCategory($tasks,$category['category_name'] );?></span>
</li>
<?php endforeach; ?>
