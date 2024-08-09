<?php

global $product;
$product_id = $product->get_id();

// Get the product gallery attachment ids.
$attachment_ids = $product->get_gallery_image_ids();
$videos = get_field('gallery_media');
?>

<div class="product-gallery">
  <div class="product-gallery__wrapper">
    <?php
    // Display the main product image.
    $post_thumbnail_id = $product->get_image_id();
    $thumb = '880-880';
    if ( $post_thumbnail_id ) {
      echo '<div class="product-gallery__image">';
      show_image($post_thumbnail_id, $thumb, ['class' => 'show-product-modal']);
      echo '</div>';
    }

    $gallery_items = [];

    if ($attachment_ids && $product->get_image_id()) {
      foreach ($attachment_ids as $key => $attachment_id) {
        $gallery_items[] = [
          'type' => 'image',
          'id' => $attachment_id,
          'key' => $key
        ];
      }
    }

    if ($videos) {
      foreach ($videos as $key => $item) {
        $gallery_items[] = [
          'type' => 'video',
          'url' => esc_url($item['video']),
          'key' => $key
        ];
      }
    }

    foreach ($gallery_items as $key => $item) {
      $class = $key > 0 ? 'half' : '';
      echo '<div class="product-gallery__image ' . $class . '">';
      if ($item['type'] === 'image') {
        show_image($item['id'], $thumb, ['class' => 'show-product-modal']);
      } elseif ($item['type'] === 'video') {
        show_video($item['url']);
      }
      echo '</div>';
    }

    echo '<div class="product-gallery__image half">';
    echo do_shortcode( '[wp360view product_id=' .  $product_id . ']');
    echo '</div>';
    ?>
  </div>
</div>
