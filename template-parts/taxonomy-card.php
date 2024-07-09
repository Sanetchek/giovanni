<?php
$category_id = $args['category_id'];

// Get the category object
$category = get_term($category_id, 'product_cat');

// Check if the category exists
if ($category && !is_wp_error($category)) :
  // Get the category title
  $category_title = $category->name;

  // Get the category link
  $category_link = get_term_link($category_id, 'product_cat');

  // Get the category thumbnail ID
  $thumbnail_id = get_term_meta($category_id, 'thumbnail_id', true);

  // Get the URL of the category thumbnail
  $thumbnail_url = get_image($thumbnail_id, '350-350', ['class' => 'taxonomies-image']);

  // Get the hover image URL from ACF
  $hover_image_id = get_field('hover_image', 'product_cat_' . $category_id);
  $hover_image_url = get_image($hover_image_id, '350-350', ['class' => 'taxonomies-hover-image']);
?>
  <a href="<?= esc_url($category_link) ?>" class="taxonomies-content-link">
    <h3 class="taxonomies-content-title"><?= esc_html($category_title) ?></h3>
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
  </a>
<?php endif; ?>