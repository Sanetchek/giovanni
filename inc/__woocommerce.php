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

      echo '<header class="woocommerce-products-header">';
			echo '<h1 class="woocommerce-products-header__title page-title">' . $title . '</h1>';
      do_action( 'woocommerce_archive_description' );
	    echo '</header>';
    }
  }
  remove_action( 'woocommerce_shop_loop_header', 'woocommerce_product_taxonomy_archive_header', 10 );
  add_action( 'woocommerce_shop_loop_header', 'custom_woocommerce_product_taxonomy_archive_header', 10 );

  /**
   * Infinite Scroll for Products Loop
   *
   * @return void
   */
  function load_more_products() {
    $paged = $_POST['page'] + 1; // next page

    $args = array(
      'post_type' => 'product',
      'paged' => $paged
    );

    $query = new WP_Query( $args );

    if( $query->have_posts() ) :
      while( $query->have_posts() ): $query->the_post();

        wc_get_template_part( 'content', 'product' );

      endwhile;
    endif;
    wp_reset_postdata();
    wp_die();
  }
  add_action('wp_ajax_load_more_products', 'load_more_products');
  add_action('wp_ajax_nopriv_load_more_products', 'load_more_products');

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
}