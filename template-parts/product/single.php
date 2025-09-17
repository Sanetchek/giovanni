<?php

global $product;
$product_id = $product->get_id();

// Get the product gallery attachment ids.
$attachment_ids = $product->get_gallery_image_ids();
$videos = get_field('gallery_media');
$vimeo_video = get_field('vimeo_video');

?>

<div class="product-gallery">
  <div class="product-gallery__wrapper">
    <?php
    // Display the main product image.
    $post_thumbnail_id = $product->get_image_id();
    $thumb = '812-812';
    echo '<div class="product-gallery__image">';
    if ( $post_thumbnail_id ) {
      show_image($post_thumbnail_id, $thumb, ['class' => 'show-product-modal']);
    } else {
      $title = $product->get_name();
      $image_url = wc_placeholder_img_src('full');
      ?>
      <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($title); ?>" width="812" height="812" loading="lazy">
    <?php }
    echo '</div>';

    $gallery_items = [];

    // Collect gallery images.
    if ($attachment_ids && $product->get_image_id()) {
      foreach ($attachment_ids as $key => $attachment_id) {
        $gallery_items[] = [
          'type' => 'image',
          'id' => $attachment_id,
          'key' => $key
        ];
      }
    }

    // Collect videos.
    if ($videos) {
      foreach ($videos as $key => $item) {
        $gallery_items[] = [
          'type' => 'video',
          'url' => esc_url($item['video']),
          'key' => $key
        ];
      }
    }

    // Determine if "half" class should be applied.
    $has_videos = !empty($videos);

    foreach ($gallery_items as $key => $item) {
      $class = ($key > 0 && $has_videos) ? 'half' : '';
      echo '<div class="product-gallery__image ' . esc_attr($class) . '">';
      if ($item['type'] === 'image') {
        show_image($item['id'], $thumb, ['class' => 'show-product-modal']);
      } elseif ($item['type'] === 'video') {
        show_video($item['url']);
      }
      echo '</div>';
    }

    // Show the 360-view element with "half" class only if videos exist.
    $class = $has_videos ? 'half' : '';
    $has_360_view = get_post_meta($product_id, '_wp360view_images', true);

    if (!empty($has_360_view)) {
      // If images are set, display the 360 view
      echo '<div class="product-gallery__image ' . esc_attr($class) . '">';
      echo do_shortcode('[wp360view product_id=' . $product_id . ']');
      echo '</div>';
    }
  
    if ($vimeo_video) {
        preg_match('/(\d+)/', $vimeo_video, $matches);
        $vimeo_id = $matches[1] ?? '';

        if ($vimeo_id) {
            echo '<div class="product-gallery__image full">';
            echo '<iframe 
                    src="https://player.vimeo.com/video/' . $vimeo_id . '?autoplay=1&muted=1&loop=1&background=0" 
                    width="812" height="457" 
                    frameborder="0" 
                    allow="autoplay; fullscreen; picture-in-picture" 
                    allowfullscreen>
                  </iframe>';
            echo '</div>';
        }
    }


    ?>
  </div>
</div>
