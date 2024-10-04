<?php
  $children_page = $args['children_page'];
  $current_page_id = get_the_ID(); // Get the current page ID
?>


  <?php foreach ($children_page as $page_id) :
    $is_current = ($current_page_id === $page_id) ? 'current' : '';
  ?>
    <li class="customer-menu-item <?= $is_current; ?>">
      <a href="<?= get_permalink($page_id) ?>" class="customer-menu-link">
        <span><?= get_the_title($page_id) ?></span>
      </a>
    </li>
  <?php endforeach; ?>