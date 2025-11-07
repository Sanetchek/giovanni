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
    'posts_per_page' => -1,
    'post_type' => 'product',
    's' => $search_query,
    'meta_query' => array(
      'relation' => 'OR',
      array(
        'key' => '_sku',
        'value' => $search_query,
        'compare' => 'LIKE'
      ),
    ),
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
/**
 * Handle AJAX Search Suggestion
 */
function giovanni_search_suggestions() {
  check_ajax_referer('giovanni_search_nonce', 'nonce');

  $search_query = sanitize_text_field($_POST['search']);

  if (empty($search_query)) {
    wp_die();
  }

  $args_title_search = array(
    'post_type' => 'product',
    'posts_per_page' => 3,
    's' => $search_query,
  );

  $args_sku_search = array(
    'post_type' => 'product',
    'posts_per_page' => 3,
    'meta_query' => array(
      array(
        'key' => '_sku',
        'value' => $search_query,
        'compare' => 'LIKE',
      ),
    ),
  );

  $query_title = new WP_Query($args_title_search);
  $query_sku = new WP_Query($args_sku_search);

  if ($query_title->have_posts() || $query_sku->have_posts()) {
    echo '<div class="search-header">' . __('הצעות:', 'giovanni') . '</div>';
    echo '<ul class="product-results">';

    // Выводим результаты поиска по названию
    while ($query_title->have_posts()) {
      $query_title->the_post();
      $product = wc_get_product(get_the_ID());
      $name = get_the_title();
      $thumbnail_id = get_the_post_thumbnail_id(get_the_ID());
      $data = [
        'thumb' => [150, 150],
        'args' => [
          'alt' => esc_attr($name),
        ],
      ];
      $thumbnail = $thumbnail_id
        ? liteimage($thumbnail_id, $data)
        : get_placeholder_image($name);
      $price_html = $product->get_price_html();
      $permalink = get_permalink();

      echo '<li>';
      echo '<a href="' . esc_url($permalink) . '">';
      echo $thumbnail;
      echo '<div>';
      echo '<h5>' . esc_html($name) . '</h5>';
      echo '<span>' . $price_html . '</span>';
      echo '</div>';
      echo '</a>';
      echo '</li>';
    }

    while ($query_sku->have_posts()) {
      $query_sku->the_post();
      $product = wc_get_product(get_the_ID());
      $name = get_the_title();
      $thumbnail_id = get_the_post_thumbnail_id(get_the_ID());
      $data = [
        'thumb' => [150, 150],
        'args' => [
          'alt' => esc_attr($name),
        ],
      ];
      $thumbnail = $thumbnail_id
        ? liteimage($thumbnail_id, $data)
        : get_placeholder_image($name);
      $price_html = $product->get_price_html();
      $permalink = get_permalink();

      echo '<li>';
      echo '<a href="' . esc_url($permalink) . '">';
      echo $thumbnail;
      echo '<div>';
      echo '<h5>' . esc_html($name) . '</h5>';
      echo '<span>' . $price_html . '</span>';
      echo '</div>';
      echo '</a>';
      echo '</li>';
    }

    echo '</ul>';

    $categories = get_terms(array(
      'taxonomy' => 'product_cat',
      'name__like' => $search_query,
      'number' => 3,
      'hide_empty' => false,
    ));

    if (!is_wp_error($categories) && !empty($categories)) {
      echo '<ul class="category-results">';
      foreach ($categories as $category) {
        $category_link = get_term_link($category);
        echo '<li>';
        echo '<a href="' . esc_url($category_link) . '">';
        echo '<span>' . esc_html($category->name) . '</span>';
        echo '<svg class="icon-chevron-right" width="24" height="24">';
        echo '<use xlink:href="' . esc_url(assets('img/sprite.svg#icon-chevron-right')) . '"></use>';
        echo '</svg>';
        echo '</a>';
        echo '</li>';
      }
      echo '</ul>';
    }
  } else {
    echo '<li class="results-not-found">' . __('לא נמצאו תוצאות', 'giovanni') . '</li>';
  }

  wp_reset_postdata();
  wp_die();
}
add_action('wp_ajax_giovanni_search_suggestions', 'giovanni_search_suggestions');
add_action('wp_ajax_nopriv_giovanni_search_suggestions', 'giovanni_search_suggestions');

