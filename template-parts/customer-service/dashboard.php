<?php
  $children_page = $args['children_page'];
?>

<ul class="customer-dashbord-links">
  <?php foreach ($children_page as $page_id) : ?>
    <li class="customer-dashboard-item">
      <a href="<?= get_permalink($page_id) ?>" class="customer-dashboard-link">
        <span><?= get_the_title($page_id) ?></span>

        <svg class='icon-arrow-left' width='24' height='24'>
          <use href='<?= assets('img/sprite.svg#icon-arrow-left') ?>'></use>
        </svg>
      </a>
    </li>
  <?php endforeach ?>
</ul>