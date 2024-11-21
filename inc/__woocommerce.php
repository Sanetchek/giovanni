<?php

/**
 * Add WooCommerce support
 *
 * @return void
 */
if ( in_array( 'woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')) ) ) {
  function add_woocommerce_support()
  {
    // Add Woocommerce Support to site
    add_theme_support('woocommerce');
    // add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
  }

  add_action('after_setup_theme', 'add_woocommerce_support');

  /**
   * Products per page
   *
   * @param [type] $cols
   * @return void
   */
  function custom_loop_shop_per_page( $cols ) {
    return 20;
  }
  add_filter( 'loop_shop_per_page', 'custom_loop_shop_per_page', 20 );

  /**
   * Add products count to Archive Page Title
   *
   * @return void
   */
  // Add to functions.php or a custom plugin file
  function custom_woocommerce_product_taxonomy_archive_header() {
    if ( ! is_search() ) {
        $title = woocommerce_page_title( false );

        // Get the current query object
        global $wp_query;

        // Get the total number of products
        $total_products = $wp_query->found_posts;

        // Modify the title to include the product count
        $title .= '<span class="product-count">' . esc_html( $total_products ) . '</span>';

        // Check ACF field use_black_title
        $use_black_title = get_field('use_black_title', get_queried_object());

        // Add class based on ACF field
        $title_class = $use_black_title ? 'black-color' : '';

        echo '<header class="woocommerce-products-header">';
        echo '<h1 class="woocommerce-products-header__title page-title ' . esc_attr($title_class) . '">' . $title . '</h1>';
        do_action( 'custom_woocommerce_archive_description' );
        echo '</header>';
    }
  }
  remove_action( 'woocommerce_shop_loop_header', 'woocommerce_product_taxonomy_archive_header', 10 );
  add_action( 'woocommerce_shop_loop_header', 'custom_woocommerce_product_taxonomy_archive_header', 10 );

  // Add a custom function for the archive description with a conditional class
  function custom_archive_description() {
      $description = term_description();
      if ( $description ) {
          // Check ACF field use_black_title
          $use_black_title = get_field('use_black_title', get_queried_object());

          // Add class based on ACF field
          $description_class = $use_black_title ? 'black-color' : 'your-custom-class';

          echo '<div class="custom-archive-description ' . esc_attr($description_class) . '">' . $description . '</div>';
      }
  }
  remove_action( 'woocommerce_archive_description', 'woocommerce_taxonomy_archive_description', 10 );
  add_action( 'custom_woocommerce_archive_description', 'custom_archive_description', 10 );

  /**
   * Remove woocommerce actions
   *
   * @return void
   */
  function remove_woocommerce_actions() {
    remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);
    remove_action('woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30);
    remove_action('woocommerce_after_shop_loop', 'woocommerce_pagination', 10);
    remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
    remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
  }
  add_action('init', 'remove_woocommerce_actions');

  /**
   * Remove woocommerce actions breadcrumb on single pproduct
   *
   * @return void
   */
  function remove_woocommerce_breadcrumb_on_product_page() {
    if ( is_product() ) {
      remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
    }
  }
  add_action( 'template_redirect', 'remove_woocommerce_breadcrumb_on_product_page' );

  /**
   * Add woocommerce actions
   *
   * @return void
   */
  function add_woocommerce_actions() {
    add_action( 'woocommerce_single_product_summary', 'woocommerce_breadcrumb', 1 );
  }
  add_action('init', 'add_woocommerce_actions');


  /**
   * Get Arguments (args) for WP_Query of Related products
   *
   * @param [type] $product_id
   * @param integer $number_of_products
   * @return void
   */
  function get_related_product_ids($product_id, $number_of_products = 8) {
    // Get the current product categories
    $categories = wp_get_post_terms($product_id, 'product_cat', array('fields' => 'ids'));

    // Get the current product tags
    $tags = wp_get_post_terms($product_id, 'product_tag', array('fields' => 'ids'));

    // Query arguments
    $args = array(
      'post_type' => 'product',
      'posts_per_page' => $number_of_products, // Set the number of related products
      'post__not_in' => array($product_id), // Exclude the current product
      'fields' => 'ids', // Only return post IDs
      'tax_query' => array(
        'relation' => 'OR',
        array(
          'taxonomy' => 'product_cat',
          'field' => 'term_id',
          'terms' => $categories,
        ),
        array(
          'taxonomy' => 'product_tag',
          'field' => 'term_id',
          'terms' => $tags,
        ),
      ),
    );

    // Get the posts
    $post_ids = get_posts($args);

    return $post_ids;
  }

  /**
   * Update Cart Count on header
   *
   * @return void
   */
  function giovanni_add_to_cart_fragment( $fragments ) {
    ob_start();
    ?>
    <span class="header-count header-cart-count"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
    <?php
    $fragments['.header-cart-count'] = ob_get_clean();

    ob_start();
    woocommerce_mini_cart( [ 'list_class' => 'header-mini-cart-list' ] );
    $fragments['.header-mini-cart-content'] = '<div class="header-mini-cart-content">' . ob_get_clean() . '</div>';

    // Debugging: Log the fragments to the console
    error_log(print_r($fragments, true));

    return $fragments;
  }
  add_filter( 'woocommerce_add_to_cart_fragments', 'giovanni_add_to_cart_fragment' );

  /**
   * Remove downloads from my-account
   *
   * @param [type] $items
   * @return void
   */
  function remove_downloads_from_my_account($items) {
    unset($items['downloads']); // Remove the Downloads tab
    return $items;
  }
  add_filter('woocommerce_account_menu_items', 'remove_downloads_from_my_account', 999);


  /**
   * Function adds black color class to the breadcrumbs when is field ACF activated.
   */
  add_action('init', 'customize_woocommerce_breadcrumbs_for_category');
  function customize_woocommerce_breadcrumbs_for_category() {
    if (function_exists('woocommerce_breadcrumb')) {
      remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
      add_action('woocommerce_before_main_content', 'custom_woocommerce_breadcrumb', 20);
    }
  }
  function custom_woocommerce_breadcrumb() {
    $breadcrumb_class = 'woocommerce-breadcrumb';
    if (is_product_category()) {
      $category = get_queried_object();

      if (function_exists('get_field') && get_field('use_black_title', 'product_cat_' . $category->term_id)) {
          $breadcrumb_class .= ' black-color';
      }
    }
    woocommerce_breadcrumb(['wrap_before' => '<nav class="' . esc_attr($breadcrumb_class) . '" aria-label="Breadcrumb">', 'wrap_after' => '</nav>']);
  }

  // Add "Favorites" link with dynamic title from page ID 616 to WooCommerce account navigation menu before Logout
  add_filter( 'woocommerce_account_menu_items', 'add_dynamic_favorites_link_before_logout', 10, 1 );
  function add_dynamic_favorites_link_before_logout( $items ) {
    // Get the title of page ID 616
    $favorites_title = get_the_title( 616 );

    // Define the new item with the dynamic title
    $new_item = array( 'favorites' => $favorites_title );

    // Insert the new item before 'customer-logout'
    $logout = $items['customer-logout'];
    unset( $items['customer-logout'] ); // Temporarily remove logout
    $items = array_merge( $items, $new_item, array( 'customer-logout' => $logout ) );

    return $items;
  }

  // Custom link for Favorites - Modify WooCommerce account URLs
  add_filter( 'woocommerce_get_endpoint_url', 'custom_favorites_link', 10, 4 );
  function custom_favorites_link( $url, $endpoint, $value, $permalink ) {
    if ( 'favorites' === $endpoint ) {
      // Redirect to /favorites page
      return site_url( '/favorites' );
    }

    return $url;
  }

  // Add 'is-active' class to the Favorites menu item when on the Favorites page
  add_filter( 'woocommerce_account_menu_item_classes', 'add_active_class_to_favorites_menu_item', 10, 2 );
  function add_active_class_to_favorites_menu_item( $classes, $endpoint ) {
    if ( 'dashboard' === $endpoint && is_page( 616 ) ) {
      // Remove 'is-active' class from all items
      $classes = array_diff( $classes, array( 'is-active' ) );
    }

    // Check if the current endpoint is 'favorites' and if we're on the 'Favorites' page (ID 616)
    if ( 'favorites' === $endpoint && is_page( 616 ) ) {
      $classes[] = 'is-active'; // Add 'is-active' class to the favorites menu item
    }

    return $classes;
  }
}