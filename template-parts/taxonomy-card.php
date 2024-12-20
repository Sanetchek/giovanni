<?php
$category_id = $args['category_id'];
$all_cat_count = $args['all_cat_count'];
$count = $args['count'];

// Get the category object
$category = get_term($category_id, 'product_cat');

// Check if the category exists
if ($category && !is_wp_error($category)) :
  // Get the category title
  $category_title = $category->name;

  // Get the category link
  $category_link = get_term_link($category_id, 'product_cat');

  // Check if it is the last element
  $is_last = $count === $all_cat_count && $count % 2 !== 0;

  // Get the category thumbnail ID
  $thumbnail_id = get_term_meta($category_id, 'thumbnail_id', true);
  $thumbnail_url = generate_picture_element($thumbnail_id, $is_last, 'taxonomies-image');

  // Get the hover image URL from ACF
  $hover_image_id = get_field('hover_image', 'product_cat_' . $category_id);
  $hover_image_url = generate_picture_element($hover_image_id, $is_last, 'taxonomies-hover-image');
?>
<a href="<?= esc_url($category_link) ?>" class="taxonomies-content-link">
  <div class="taxonomies-content-image">
    <?= $thumbnail_url ?>
  </div>
  <div class="taxonomies-hidden">
    <div class="taxonomies-hidden-img">
      <?= $hover_image_url ?>
    </div>
    <h3 class="taxonomies-hidden-title btn-hover white">
      <?= esc_html($category_title) ?>
    </h3>
  </div>
  <div class="editorial-list-item animation-container">
    <h3 class="taxonomies-content-title">
      <span><?= esc_html($category_title) ?></span>
    </h3>
    <h3 class="taxonomies-content-title animation-text" aria-hidden="true">
      <span><?= esc_html($category_title) ?></span>
    </h3>
  </div>
</a>
<?php endif; ?>
