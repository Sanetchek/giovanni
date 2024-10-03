<?php
  $children_page = $args['children_page'];
?>

<ul class="customer-dashbord-links">
  <?php foreach ($children_page as $page_id) : ?>
    <li class="customer-dashboard-item">
      <a href="<?= get_permalink($page_id) ?>" class="customer-dashboard-link">
        <?= get_the_title($page_id) ?>
      </a>
    </li>
  <?php endforeach ?>
</ul>