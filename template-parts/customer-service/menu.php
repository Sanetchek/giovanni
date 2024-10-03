<?php
  $children_page = $args['children_page'];
  $dashbord_name = $args['dashbord_name'];
?>

<ul class="customer-menu-links">
  <li class="customer-menu-item">
    <a href="<?= get_permalink() ?>" class="customer-menu-link">
      <?= $dashbord_name ?>
    </a>
  </li>
  <?php foreach ($children_page as $page_id) : ?>
    <li class="customer-menu-item">
      <a href="<?= get_permalink($page_id) ?>" class="customer-menu-link">
        <?= get_the_title($page_id) ?>
      </a>
    </li>
  <?php endforeach ?>
</ul>