<?php
// Check if 'taxonomies' argument is passed; assign it to $taxs
$taxs = isset($args['taxonomies']) ? $args['taxonomies'] : false;
?>

<div class="taxonomies-container-boxes slick-slider-boxes">
  <?php if ($taxs) : ?>
    
      <?php foreach ($taxs as $key => $category_id) :
        // Calculate the total and the current count
        $all_cat_count = count($taxs);
        $count = $key + 1;

        // Pass parameters to 'taxonomy-card' template part
        $args = [
          'category_id' => $category_id,
          'all_cat_count' => $all_cat_count,
          'count' => $count
        ];
      ?>
 
        <?php get_template_part('template-parts/taxonomy', 'cardboxes', $args); ?>
     
      <?php endforeach; ?>
    
  <?php endif; ?>
</div>
