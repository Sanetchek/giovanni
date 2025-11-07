<?php

global $product;

// Get the product gallery attachment ids.
$attachment_ids = $product->get_gallery_image_ids();
?>

<div class="product-modal">
  <div class="product-modal-close">
    <svg class='icon-close' width='32' height='32'>
      <use href='<?= assets('img/sprite.svg#icon-close') ?>'></use>
    </svg>
  </div>

  <div class="product-modal__wrapper">
    <div class="slider-main">
      <?php
      // Display the main product image.
      $post_thumbnail_id = $product->get_image_id();
      $thumb = 'full';

      if ( $post_thumbnail_id ) {
        $image_url = wp_get_attachment_image_url($post_thumbnail_id, $thumb);
        echo '<div class="product-modal__image zoomable" style="background-image: url(' . esc_url($image_url) . ');">';
        echo '</div>';
      }

      // Display the gallery images.
      if ( $attachment_ids && $product->get_image_id() ) {
        foreach ( $attachment_ids as $key => $attachment_id ) {
          $image_url = wp_get_attachment_image_url($attachment_id, $thumb);
          echo '<div class="product-modal__image zoomable" style="background-image: url(' . esc_url($image_url) . ');">';
          echo '</div>';
        }
      }
      ?>
    </div>
    <div class="slider-nav">
      <?php
      // Display the main product image.
      $post_thumbnail_id = $product->get_image_id();
      $thumb = [60,60];

      if ( $post_thumbnail_id ) {
        echo '<div class="product-modal__image">';
        show_image($post_thumbnail_id, $thumb, ['class' => 'modal-thumbnail']);
        echo '</div>';
      }

      // Display the gallery images.
      if ( $attachment_ids && $product->get_image_id() ) {
        foreach ( $attachment_ids as $key => $attachment_id ) {
          echo '<div class="product-modal__image">';
          show_image($attachment_id, $thumb, ['class' => 'modal-thumbnail']);
          echo '</div>';
        }
      }
      ?>
    </div>
  </div>
</div>