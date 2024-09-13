<?php

/**
 * Ajax Add to Cart product on Single Product Page
 *
 * @return void
 */
function giovanni_ajax_add_to_cart() {
  $product_id = apply_filters('woocommerce_add_to_cart_product_id', absint($_POST['product_id']));
  $quantity = empty($_POST['quantity']) ? 1 : wc_stock_amount($_POST['quantity']);
  $variation_id = $_POST['variation_id'];
  $passed_validation = apply_filters('woocommerce_add_to_cart_validation', true, $product_id, $quantity);
  $product_status = get_post_status($product_id);

  if ($passed_validation && WC()->cart->add_to_cart($product_id, $quantity, $variation_id) && 'publish' === $product_status) {
    do_action('woocommerce_ajax_added_to_cart', $product_id);

    if ('yes' === get_option('woocommerce_cart_redirect_after_add')) {
      wc_add_to_cart_message(array($product_id => $quantity), true);
    }

    WC_AJAX::get_refreshed_fragments();
  } else {
    $data = array(
      'error' => true,
      'product_url' => apply_filters('woocommerce_cart_redirect_after_error', get_permalink($product_id), $product_id)
    );
    echo wp_send_json($data);
  }

  wp_die();
}
add_action('wp_ajax_woocommerce_ajax_add_to_cart', 'giovanni_ajax_add_to_cart');
add_action('wp_ajax_nopriv_woocommerce_ajax_add_to_cart', 'giovanni_ajax_add_to_cart');

/**
 * Handle AJAX Search
 */
function giovanni_ajax_search() {
  check_ajax_referer('giovanni_search_nonce', 'nonce');

  $search_query = sanitize_text_field($_GET['s_modal']);

  $args = array(
    's' => $search_query,
    'posts_per_page' => -1,
  );

  $query = new WP_Query($args);

  if ($query->have_posts()) {
    $count = 0;

    while ($query->have_posts()) {
      $query->the_post();

      get_template_part('template-parts/search', 'card', ['id' => get_the_ID(), 'count' => $count]);
      $count++;
    }
  } else {
    echo '<li class="results-not-found">' . __('לא נמצאו תוצאות', 'giovanni') . '</li>';
  }

  wp_reset_postdata();
  wp_die();
}
add_action('wp_ajax_giovanni_search', 'giovanni_ajax_search');
add_action('wp_ajax_nopriv_giovanni_search', 'giovanni_ajax_search');
