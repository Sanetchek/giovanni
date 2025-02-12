<?php
// Check if 'taxonomies' argument is passed; assign it to $items
$items = isset($args['taxonomies']) ? $args['taxonomies'] : false;
?>

<div class="taxonomies-container">
  <?php if (!empty($items) && is_array($items)) : ?>
    <ul class="taxonomies-wrap">
      <?php foreach ($items as $key => $item) :
        // Extract id and type from the current item
        $item_id = $item['id'];
        $item_type = $item['type'];

        $all_item_count = count($items);
        $count = $key + 1;
        $is_last = $count === $all_item_count && $count % 2 !== 0;
      ?>
        <li class="taxonomies-content <?= $is_last ? 'last' : '' ?>">
          <?php
          // Prepare arguments for the template part
          $template_args = [
            'category_id' => $item_type === 'term' ? $item_id : null,
            'page_id' => $item_type === 'page' ? $item_id : null,
            'all_cat_count' => $all_item_count,
            'count' => $count,
          ];

          // Determine which template to load based on the type
          $template_name = $item_type === 'term' ? 'taxonomy' : 'page-post';
          get_template_part('template-parts/'. $template_name, 'card', $template_args);
          ?>
        </li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>
</div>
