
<?php
$category_id = $args['category_id'];
$all_cat_count = $args['all_cat_count'];
$count = $args['count'];

$category = get_term($category_id, 'product_cat');

if ($category && !is_wp_error($category)) :
  $category_title = $category->name;

  $category_link = get_term_link($category_id, 'product_cat');

  $is_last = $count === $all_cat_count && $count % 2 !== 0;

  $thumbnail_id = get_term_meta($category_id, 'thumbnail_id', true);
  $thumbnail_url = generate_picture_element($thumbnail_id, $is_last, 'taxonomies-image');

  $hover_image_id = get_field('hover_image', 'product_cat_' . $category_id);
  $hover_image_url = generate_picture_element($hover_image_id, $is_last, 'taxonomies-hover-image');

  $mobile_image_id = get_field('taxonomy_image_mob', 'product_cat_' . $category_id);
  $mobile_image_url = wp_get_attachment_image($mobile_image_id, 'full', false, ['class' => 'mobile-image']);
?>
  <div class="slick-item">
    <a href="<?= esc_url($category_link) ?>" class="taxonomies-content-link">
      <div class="taxonomies-content-image">
        <?= $thumbnail_url ?>
      </div>

      <?php if ($mobile_image_url): ?>
        <div class="mobile-only">
          <?= $mobile_image_url ?>
        </div>
      <?php endif; ?>

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
  </div>
<?php endif; ?>
