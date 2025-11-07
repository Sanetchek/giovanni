<?php
// Determine the product ID from arguments or fallback to the current post ID
$product_id = isset($args['product_id']) ? $args['product_id'] : get_the_ID();

// Attempt to get the WooCommerce product object
$product = wc_get_product($product_id);

// Check if a valid product object is returned
if (!$product) {
  // If not, handle the error gracefully
  echo '<p>' . __('המוצר לא נמצא', 'giovanni') . '</p>';
  return; // Exit the script to prevent further errors
}

// Get the product details
$title = $product->get_name();
$price = $product->get_price_html();
$permalink = get_permalink($product_id);

// Get the main image ID or set to WooCommerce placeholder if no image exists
$image = get_post_thumbnail_id($product_id);
$data = [
  'thumb' => [400, 400],
  'args' => [
    'alt' => esc_attr($title),
  ],
];
$image = $image ? liteimage($image, $data) : get_placeholder_image($title);

// Get the second image from the product gallery, if available
$attachment_ids = $product->get_gallery_image_ids();
$second_image_id = !empty($attachment_ids) ? $attachment_ids[0] : '';
$data = [
  'thumb' => [400, 400],
  'args' => [
    'alt' => esc_attr($title),
  ],
];
$second_image = $second_image_id ? liteimage($second_image_id, $data) : get_placeholder_image($title);
$favorite_class = is_page_template('templates/favorites.php') ? 'favorite-product' : '';
?>

<?php if ($product) : ?>
<div class="product-link <?php echo $favorite_class; ?>">
  <div class="product-image">
    <?php
      // translators: %s: Product name
      $image_link_label = sprintf(__('View %s product details', 'giovanni'), esc_attr($title));
    ?>
    <a href="<?php echo esc_url($permalink); ?>" aria-label="<?php echo esc_attr($image_link_label); ?>">
      <?php echo $image; ?>
    </a>

    <a href="<?php echo esc_url($permalink); ?>" aria-label="<?php echo esc_attr($image_link_label); ?>">
      <div class="product-hidden product-hidden-img" aria-hidden="true">
        <?php echo simpleLikes($product_id); ?>

        <?php if ($second_image) : ?>
          <?php echo $second_image; ?>
        <?php endif ?>
      </div>
    </a>
  </div>

  <a href="<?php echo esc_url($permalink); ?>" class="product-info-box" aria-label="<?php
    // translators: %s: Product name
    echo esc_attr(sprintf(__('View %s product details and price', 'giovanni'), esc_attr($title))); ?>">
    <div class="product-info">
      <h3 class="product-title"><?php echo esc_html($title); ?></h3>
      <span class="product-price" dir="ltr"><?php echo $price; ?></span>
    </div>
  </a>

  <?php
    // translators: %s: Product name
    $cart_button_label = sprintf(__('Buy %s now', 'giovanni'), esc_attr($title));
  ?>
  <a href="<?php echo esc_url($permalink); ?>" class="product-button-cart" aria-label="<?php echo esc_attr($cart_button_label); ?>">
    <span class="product-hidden product-hidden-btn button button-hover white" aria-hidden="true">
      <?php echo __('לקנות עכשיו', 'giovanni'); ?>
    </span>
  </a>

  <?php if (is_page_template('templates/favorites.php')) : ?>
    <button class="product-remove-like">
      <span class="product-hidden product-remove-btn btn">
        <?php echo __('להסיר מוצר', 'giovanni'); ?>
      </span>
    </button>
  <?php endif ?>
</div>
<?php endif; ?>