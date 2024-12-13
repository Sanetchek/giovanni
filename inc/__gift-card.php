<?php

function add_gift_card_to_cart() {
  // Verify nonce
  if (!isset($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], 'add_gift_card_action')) {
    wp_send_json_error(['message' => __('Nonce verification failed', 'giovanni')]);
    return;
  }

  $giftAmount = sanitize_text_field($_POST['giftAmount']);
  $senderName = sanitize_text_field($_POST['senderName']);
  $reciverEmail = sanitize_email($_POST['reciverEmail']);
  $message = sanitize_textarea_field($_POST['message']);

  // Get the gift card product ID (make sure this is the correct product ID for gift cards)
  $product_id = 8188; // Replace with your gift card product ID

  // Prepare the post data for the plugin's add to cart method
  $_POST['wps_wgm_single_nonce_field'] = wp_create_nonce('wps_wgm_single_nonce');
  $_POST['wps_wgm_send_giftcard'] = 'Mail to recipient'; // or another delivery method supported by the plugin
  $_POST['wps_wgm_to_email'] = $reciverEmail;
  $_POST['wps_wgm_from_name'] = $senderName;
  $_POST['wps_wgm_message'] = $message;
  $_POST['wps_wgm_price'] = $giftAmount;

  // Optional: Select a template if required
  // $_POST['wps_wgm_selected_temp'] = 'template_id';

  // Add the gift card to the cart
  WC()->cart->add_to_cart($product_id, 1);

  wp_send_json_success(['message' => __('Gift card added to cart!', 'giovanni')]);
}
add_action('wp_ajax_add_gift_card_to_cart', 'add_gift_card_to_cart');
add_action('wp_ajax_nopriv_add_gift_card_to_cart', 'add_gift_card_to_cart');