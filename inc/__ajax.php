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

/**
 * Handle AJAX Search Suggestion
 */
function giovanni_search_suggestions() {
  check_ajax_referer('giovanni_search_nonce', 'nonce');

  $search_query = sanitize_text_field($_GET['search']);

  // Modify the query to search for products (assuming 'product' is the post type)
  $args = array(
    'post_type' => 'product',
    's' => $search_query,
    'posts_per_page' => 3,
  );

  $query = new WP_Query($args);

  if ($query->have_posts()) {
    echo '<h2 class="search-header">'. __('הצעות:', 'giovanni') .'</h2>';
    echo '<ul class="product-results">'; // Start product list
    while ($query->have_posts()) {
      $query->the_post();

      // Create the product object
      $product = wc_get_product(get_the_ID());
      $name = get_the_title();
      $thumbnail = get_the_post_thumbnail(get_the_ID(), 'thumbnail') ?: '<img src="' . wc_placeholder_img_src('full') . '" alt="'.$name.'" />';
      $price_html = $product->get_price_html(); // Get formatted price
      $permalink = get_permalink(); // Get the product permalink

      // Output product details with link
      echo '<li>';
      echo '<a href="' . esc_url($permalink) . '">'; // Link to the product
      echo $thumbnail; // Product thumbnail
      echo '<div>'; // Product name
      echo '<h2>' . esc_html($name) . '</h2>'; // Product name
      echo '<span>' . $price_html . '</span>'; // Product price
      echo '</div>'; // Product price
      echo '</a>'; // Close link
      echo '</li>'; // Close product list item
    }
    echo '</ul>'; // Close product list

    // Now fetch categories related to the search query
    $categories = get_terms(array(
      'taxonomy' => 'product_cat', // Assuming 'product_cat' is the taxonomy for product categories
      'name__like' => $search_query, // Search for categories similar to the search query
      'number' => 3, // Limit to 3 categories
      'hide_empty' => true, // Hide empty categories
    ));

    if (!empty($categories) && !is_wp_error($categories)) {
      echo '<ul class="category-results">'; // Start category list
      foreach ($categories as $category) {
        $category_link = get_term_link($category); // Get link for the category
        echo '<li>';
        echo '<a href="' . esc_url($category_link) . '">';
        echo '<span>' . esc_html($category->name) . '</span>';
        echo '<svg class="icon-chevron-right" width="24" height="24"><use href="'.assets('img/sprite.svg#icon-chevron-right').'"></use></svg>';
        echo '</a>';
        echo '</li>';
      }
      echo '</ul>'; // Close category list
    }
  } else {
    echo '<li class="results-not-found">' . __('לא נמצאו תוצאות', 'giovanni') . '</li>';
  }

  wp_reset_postdata();
  wp_die();
}
add_action('wp_ajax_giovanni_search_suggestions', 'giovanni_search_suggestions');
add_action('wp_ajax_nopriv_giovanni_search_suggestions', 'giovanni_search_suggestions');


