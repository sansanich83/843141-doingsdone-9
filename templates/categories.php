<?php foreach ($categories as $key => $category): ?>
<li class="main-navigation__list-item">
    <a class="main-navigation__list-item-link" href="#"><?=esc($category);?></a>
    <span class="main-navigation__list-item-count"><?=countByCategory($tasks,$category);?></span>
</li>
<?php endforeach; ?>
