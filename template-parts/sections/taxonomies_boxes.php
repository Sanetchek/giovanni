<?php
// Check if 'taxonomies' argument is passed; assign it to $taxs
$taxs = isset($args['taxonomies']) ? $args['taxonomies'] : false;
?>

<div class="taxonomies-container">
  <?php if (!empty($taxs) && is_array($taxs)) : ?>
    <ul class="taxonomies-wrap">
      <?php foreach ($taxs as $key => $category_id) :
        $all_cat_count = count($taxs);
        $count = $key + 1;
        $is_last = $count === $all_cat_count && $count % 2 !== 0;
      ?>
        <li class="taxonomies-content <?= $is_last ? 'last' : '' ?>">
          <?php
          $args = ['category_id' => $category_id, 'all_cat_count' => $all_cat_count, 'count' => $count];
          get_template_part('template-parts/taxonomy', 'card', $args);
          ?>
        </li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>
</div>
