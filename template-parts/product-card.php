<?php
$product_id = $args['product_id'];
$product = wc_get_product($product_id);

// Get the second image from the product gallery
$attachment_ids = $product->get_gallery_image_ids(); // Get gallery attachment IDs
$second_image = $attachment_ids[0];

$title = $product->get_name();
$price = $product->get_price_html();
$permalink = get_permalink($product_id);
$image = get_post_thumbnail_id($product_id);
$second_image = $second_image;
?>

<?php if ($product) : ?>
  <a href="<?php echo esc_url($permalink); ?>" class="product-link">
    <div class="product-image">
      <?php show_image($image, '372-372') ?>

      <div class="product-hidden product-hidden-img">
        <?php show_image($second_image, '372-372') ?>
      </div>
    </div>

    <div class="product-info">
      <h2 class="product-title"><?php echo esc_html($title); ?></h2>
      <span class="product-price"><?php echo $price; ?></span>
    </div>

    <div class="product-hidden product-hidden-btn btn-hover white"><?= __('לקנות עכשיו', 'giovanni') ?></div>
  </a>
<?php endif ?>
