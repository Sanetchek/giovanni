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
$image_url = $image ? wp_get_attachment_image_url($image, '812-812') : wc_placeholder_img_src('full');

// Get the second image from the product gallery, if available
$attachment_ids = $product->get_gallery_image_ids();
$second_image_id = !empty($attachment_ids) ? $attachment_ids[0] : '';
$second_image_url = $second_image_id ? wp_get_attachment_image_url($second_image_id, '812-812') : null;
?>

<?php if ($product) : ?>
<div class="product-link">
  <div class="product-image">
    <a href="<?php echo esc_url($permalink); ?>">
      <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($title); ?>">

      <div class="product-hidden product-hidden-img">
        <?php echo simpleLikes($product_id); ?>
        <?php if ($second_image_url) : ?>
        <img src="<?php echo esc_url($second_image_url); ?>" alt="<?php echo esc_attr($title); ?>">
        <?php endif ?>
      </div>
    </a>
  </div>

  <a href="<?php echo esc_url($permalink); ?>">
    <div class="product-info">
      <h2 class="product-title"><?php echo esc_html($title); ?></h2>
      <span class="product-price" dir="ltr"><?php echo $price; ?></span>
    </div>
  </a>

  <a href="<?php echo esc_url($permalink); ?>">
    <span class="product-hidden product-hidden-btn btn-hover white">
      <?php echo __('לקנות עכשיו', 'giovanni'); ?>
    </span>
  </a>
</div>
<?php endif; ?>