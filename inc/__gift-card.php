<?php

function add_gift_card_to_cart() {
  // Verify nonce
  if (!isset($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], 'add_gift_card_action')) {
    wp_send_json_error(['message' => __('Nonce verification failed', 'giovanni')]);
    return;
  }

  $giftAmount = sanitize_text_field($_POST['giftAmount']);
  $senderName = sanitize_text_field($_POST['senderName']);
  $reciverName = sanitize_text_field($_POST['reciverName']);
  $reciverEmail = sanitize_email($_POST['reciverEmail']);
  $message = sanitize_textarea_field($_POST['message']);

  // Add the gift card to the WooCommerce cart
  $product_id = 8188; // Replace with your gift card product ID
  WC()->cart->add_to_cart($product_id, 1, 0, [], [
    'giftAmount' => $giftAmount,
    'senderName' => $senderName,
    'reciverName' => $reciverName,
    'reciverEmail' => $reciverEmail,
    'message' => $message,
  ]);

  wp_send_json_success(['message' => __('Gift card added to cart!', 'giovanni')]);
}
add_action('wp_ajax_add_gift_card_to_cart', 'add_gift_card_to_cart');
add_action('wp_ajax_nopriv_add_gift_card_to_cart', 'add_gift_card_to_cart');
