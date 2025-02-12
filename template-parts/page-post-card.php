<?php
$page_id = $args['page_id'];
$all_page_count = $args['all_cat_count'];
$count = $args['count'];

// Check if the page exists
if ($page = get_post($page_id)) :
  // Get the page title
  $page_title = get_the_title($page_id);

  // Get the page link
  $page_link = get_permalink($page_id);

  // Check if it is the last element
  $is_last = $count === $all_page_count && $count % 2 !== 0;

  // Get the featured image (thumbnail) URL
  $thumbnail_id = get_post_thumbnail_id($page_id);
  $thumbnail_url = generate_picture_element($thumbnail_id, $is_last, 'taxonomies-image');

  // Get the hover image URL from ACF
  $hover_image_id = get_field('hover_image', $page_id); // Ensure 'hover_image' field is configured in ACF for pages
  $hover_image_url = generate_picture_element($hover_image_id, $is_last, 'taxonomies-hover-image');
?>
<a href="<?= esc_url($page_link) ?>" class="taxonomies-content-link">
  <div class="taxonomies-content-image">
    <?= $thumbnail_url ?>
  </div>
  <div class="taxonomies-hidden">
    <div class="taxonomies-hidden-img">
      <?= $hover_image_url ?>
    </div>
    <h3 class="taxonomies-hidden-title btn-hover white">
      <?= esc_html($page_title) ?>
    </h3>
  </div>
  <div class="editorial-list-item animation-container">
    <h3 class="taxonomies-content-title">
      <span><?= esc_html($page_title) ?></span>
    </h3>
    <h3 class="taxonomies-content-title animation-text" aria-hidden="true">
      <span><?= esc_html($page_title) ?></span>
    </h3>
  </div>
</a>
<?php endif; ?>
