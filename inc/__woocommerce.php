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
        //$title .= '<span class="product-count">' . esc_html( $total_products ) . '</span>';

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

  function product_collection_categories() {
    global $post;
		$categories = wp_get_post_terms($post->ID, 'product_cat');

		if (!empty($categories)) {
			$target_parent = 26; // ID of the parent category to check for
			$sorted_categories = [];

			// Recursive function to check parent category
			function has_target_parent($term, $target_parent) {
				if ($term->parent == 0) {
					return false; // Reached top-level category
				}
				if ($term->parent == $target_parent) {
					return true; // Found target parent
				}
				// Get parent term and continue checking
				$parent = get_term($term->parent, 'product_cat');
				return has_target_parent($parent, $target_parent);
			}

			// Sort categories into the appropriate array
			foreach ($categories as $category) {
				if ($category->term_id === $target_parent) {
					$sorted_categories[] = $category->term_id;
				}

				if (has_target_parent($category, $target_parent)) {
					$sorted_categories[] = $category->term_id;
				}
			}

      return $sorted_categories;
		} else {
      return;
    }
  }

  /**
   * WooCommerce: Customize Breadcrumbs to Include Last Subcategory in Priority Category Hierarchy
   */

  /**
   * Get the full category hierarchy starting from the product's category, going up to the priority category.
   *
   * @param int $product_id The ID of the product.
   * @param array $priority_categories Array of priority category IDs.
   * @return array The breadcrumb items for the category hierarchy.
   */
  function get_last_subcategory_hierarchy($product_id, $priority_categories) {
    $category_hierarchy = [];
    $product_categories = get_the_terms($product_id, 'product_cat');

    if ($product_categories && !is_wp_error($product_categories)) {
      // Iterate over product categories and check if they belong to the priority categories
      foreach ($product_categories as $category) {
        // Check if the category belongs to any of the priority categories or their ancestors
        foreach ($priority_categories as $priority_category_id) {
          if ($category->term_id == $priority_category_id || term_is_ancestor_of($priority_category_id, $category->term_id, 'product_cat')) {
            // Get the full hierarchy starting from the priority category
            $category_hierarchy = get_category_hierarchy($category->term_id, $priority_category_id);
            break 2; // Stop once we find the category within the priority hierarchy
          }
        }
      }
    }

    return $category_hierarchy;
  }

  /**
   * Get the full category hierarchy (ancestors) for a given category, including the priority category.
   *
   * @param int $category_id The ID of the category.
   * @param int $priority_category_id The ID of the priority category.
   * @return array The breadcrumb items for the category hierarchy.
   */
  function get_category_hierarchy($category_id, $priority_category_id) {
    $category_hierarchy = [];
    $category = get_term($category_id, 'product_cat');

    if ($category && !is_wp_error($category)) {
      $parents = [];

      // Collect all parent categories up to the priority category
      while ($category->parent != 0) {
        $category = get_term($category->parent, 'product_cat');
        array_unshift($parents, [$category->name, get_term_link($category)]);

        // Stop once we reach the priority category
        if ($category->term_id == $priority_category_id) {
          break;
        }
      }

      // Add the priority category itself at the beginning
      array_unshift($parents, [$category->name, get_term_link($category)]);

      // Add the last subcategory (the current category)
      $parents[] = [$category->name, get_term_link($category)];

      $category_hierarchy = $parents;
    }

    return $category_hierarchy;
  }

  /**
   * Modify WooCommerce breadcrumbs to include the last subcategory in the priority category hierarchy.
   *
   * @param array $crumbs The original breadcrumb array.
   * @param object $breadcrumb The WooCommerce breadcrumb object.
   * @return array Modified breadcrumb array.
   */
  add_filter('woocommerce_get_breadcrumb', 'customize_breadcrumbs_with_last_subcategory', 20, 2);
  function customize_breadcrumbs_with_last_subcategory($crumbs, $breadcrumb) {
    global $post;

    // Check if we're on a single product page
    if (is_singular('product') && isset($post->ID)) {
      // Priority category IDs in desired order
      $priority_categories = [26, 314]; // Replace with your actual category IDs

      // Get the category hierarchy for the product
      $category_hierarchy = get_last_subcategory_hierarchy($post->ID, $priority_categories);

      // If no hierarchy was found, use the fallback (first product category)
      if (empty($category_hierarchy)) {
        $product_categories = get_the_terms($post->ID, 'product_cat');
        $first_category = reset($product_categories);
        $category_hierarchy = get_category_hierarchy($first_category->term_id, $first_category->term_id);
      }

      // Reconstruct the breadcrumbs by merging with the modified category hierarchy
      $new_crumbs = array_merge(
        [$crumbs[0]],              // "Home" breadcrumb
        array_unique($category_hierarchy, SORT_REGULAR), // Remove duplicates and merge categories
        [end($crumbs)]             // Product title
      );

      return $new_crumbs;
    }

    return $crumbs;
  }

  /**
   * Outputs a custom privacy policy checkbox to the checkout page.
   *
   * @since 1.0.0
   */
  function custom_privacy_checkbox() {
    ?>
    <p class="form-row terms">
      <label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">
        <input type="checkbox" class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" name="custom_privacy_checkbox" id="custom_privacy_checkbox">
        <span class="woocommerce-terms-and-conditions-checkbox-text"><?php _e('הנתונים האישיים שלך ישמשו כדי לעבד ולטפל בהזמנה שלך, לתמוך בחוויה שלך בכל האתר, ולמטרות אחרות המתוארות ב <a href="/privacy-policy/" target="_blank">מדיניות הפרטיות</a> שלנו.', 'giovanni'); ?>
        </span>
      </label>
    </p>
    <?php
  }
  add_action('woocommerce_review_order_before_submit', 'custom_privacy_checkbox', 20);

  /**
   * Validates the custom privacy checkbox during the WooCommerce checkout process.
   *
   * Checks if the custom privacy checkbox is set in the POST request, and if not,
   * adds an error notice prompting the user to agree to the privacy policy before proceeding.
   */

  function validate_custom_privacy_checkbox() {
    if (!isset($_POST['custom_privacy_checkbox'])) {
      wc_add_notice(__('עליך לאשר את מדיניות הפרטיות לפני שתמשיך.', 'giovanni'), 'error');
    }
  }
  add_action('woocommerce_checkout_process', 'validate_custom_privacy_checkbox');

  /**
   * Save the custom privacy checkbox field as order meta.
   *
   * @param int $order_id The ID of the order.
   */
  function save_custom_privacy_checkbox($order_id) {
    if (isset($_POST['custom_privacy_checkbox'])) {
      update_post_meta($order_id, '_custom_privacy_checkbox', 'yes');
    }
  }
  add_action('woocommerce_checkout_update_order_meta', 'save_custom_privacy_checkbox');

  /**
   * remove checkout privacy policy text
   */
  add_action('wp', function () {
    remove_action('woocommerce_checkout_terms_and_conditions', 'wc_checkout_privacy_policy_text', 20);
  });

  /**
   * Customize WooCommerce billing fields.
   *
   * This function modifies the WooCommerce billing fields to ensure that the
   * billing phone number is marked as a required field. It also adds the
   * 'validate-required' class to the billing phone field for frontend validation.
   *
   * @param array $fields The existing billing fields.
   * @return array The modified billing fields with the phone number set as required.
   */

  function custom_billing_fields( $fields ) {
    $fields['billing_phone']['required'] = true;

    return $fields;
  }
  add_filter('woocommerce_billing_fields', 'custom_billing_fields', 1000, 1);


  add_filter( 'woocommerce_blocks_product_grid_item_html', function( $html, $data, $product ) {
    // Use output buffering to capture template output
    ob_start();
    get_template_part( 'template-parts/product-card', null, ['product_id' => $product->get_id()] );
    $custom_template = ob_get_clean();

    $custom_html = "<li class=\"wc-block-grid__product\">{$custom_template}</li>";

    return $custom_html;
  }, 10, 3 );

  /**
   * Remove flat rate shipping if free shipping is available
   */
  add_filter('woocommerce_package_rates', function($rates) {
    $free_shipping_key = '';

    // Find free shipping method
    foreach ($rates as $key => $rate) {
      if ('free_shipping' === $rate->method_id) {
        $free_shipping_key = $key;
      }
    }

    // If free shipping exists, remove flat rate
    if ($free_shipping_key) {
      foreach ($rates as $key => $rate) {
        if ('flat_rate' === $rate->method_id) {
          unset($rates[$key]);
        }
      }
    }

    return $rates;
  }, 100);

  /**
   * Retrieves and merges taxonomies associated with a given product category.
   *
   * This function fetches pages and category taxonomy boxes related to a specific
   * product category, ensures they are arrays, assigns a type ('page' or 'term')
   * to each item, and merges them into a single array.
   *
   * @param int $term_id The ID of the product category term.
   * @return array Merged array of taxonomies with type indicators.
   */
  function get_merged_taxonomies($term_id) {
    $taxonomies_pages = get_field('pages_with_taxonomies', 'product_cat_' . $term_id);
    $taxonomies_category_box = get_field('category_taxonomies_boxes', 'product_cat_' . $term_id);

    // Ensure $taxonomies_pages is an array
    if (!is_array($taxonomies_pages)) {
      $taxonomies_pages = [];
    }

    // Ensure $taxonomies_category_box is an array
    if (!is_array($taxonomies_category_box)) {
      $taxonomies_category_box = [];
    }

    // Add type => 'page' to each ID in $taxonomies_pages
    $taxonomies_pages_with_type = array_map(function($id) {
      return [
        'id' => $id,
        'type' => 'page'
      ];
    }, $taxonomies_pages);

    // Add type => 'term' to each ID in $taxonomies_category_box
    $taxonomies_category_box_with_type = array_map(function($id) {
      return [
        'id' => $id,
        'type' => 'term'
      ];
    }, $taxonomies_category_box);

    // Merge the arrays
    return array_merge($taxonomies_pages_with_type, $taxonomies_category_box_with_type);
  }

  /**
 * Retrieves a paginated list of WooCommerce products ordered randomly.
   *
   * This function sets up a query to fetch published WooCommerce products,
 * ordered randomly. It also supports pagination and filters products by
 * category if on a product category archive page.
   *
   * @return WP_Query The query object containing the products.
   */
  function get_paginated_products() {
    // Determine the current page
    $paged = get_query_var('paged') ? get_query_var('paged') : 1;

    // For category archives, get the current category ID
    $category_id = false;
    if (is_product_category()) {
      $current_category = get_queried_object();

      if ($current_category && !is_wp_error($current_category)) {
        $category_id = $current_category->term_id;
      }
    }

    // Reuse the central query builder to keep sorting consistent with filters/AJAX.
    $args = build_product_query_args([
      'sort' => 'rand',
    ], $paged, $category_id);

    return new WP_Query($args);
  }

  /**
   * Filters available payment gateways based on the presence of a gift card in the cart.
   *
   * If a gift card is found in the cart, only the 'tranzilla' gateway will be available.
   *
   * @param array $available_gateways The available payment gateways.
   * @return array The filtered payment gateways.
   */
  function filter_payment_gateways_based_on_gift_card($available_gateways) {
    // Check if WooCommerce is ready and the cart is available
    if (is_admin() || !WC()->cart) {
      return $available_gateways;
    }

    // Check if the cart has any product matching the conditions
    $has_gift_card = false;

    foreach (WC()->cart->get_cart() as $cart_item) {
      $product = $cart_item['data'];
      $product_id = $cart_item['product_id'];
      $product_type = $product->get_type();
      $wps_gift_product = get_post_meta($product_id, 'wps_gift_product', true);

      // Check if it's a gift card
      if ('wgm_gift_card' === $product_type || 'gw_gift_card' === $product_type || 'on' === $wps_gift_product) {
        $has_gift_card = true;
        break;
      }
    }

    // If a gift card is found, leave only the 'tranzila' gateway
    if ($has_gift_card) {
      foreach ($available_gateways as $gateway_id => $gateway) {
        if ($gateway_id !== 'tranzila') {
          unset($available_gateways[$gateway_id]);
        }
      }
    }

    return $available_gateways;
  }
  add_filter('woocommerce_available_payment_gateways', 'filter_payment_gateways_based_on_gift_card', 10, 1);

  function debug_gift_card_product_info($order_id, $order) {
    if (!$order_id || !$order instanceof WC_Order) return;

    foreach ($order->get_items() as $item_id => $item) {
      $product_id = $item->get_product_id();
      $product = wc_get_product($product_id);

      if (!$product) continue;

      $product_type = $product->get_type();
      $wps_gift_product = get_post_meta($product_id, 'wps_gift_product', true);

      // Check if it's a gift card
      if ('wgm_gift_card' === $product_type || 'gw_gift_card' === $product_type || 'on' === $wps_gift_product) {
        // Retrieve gift coupon using both possible keys
        $giftcoupon = get_post_meta($order_id, "$order_id#$item_id", true);

        // Get all metadata for debugging
        $all_meta = wc_get_order_item_meta($item_id, '');

        $data = [
          'recipient_name' => $all_meta['To'][0],
          'sender_name' => $all_meta['From'][0],
          'message' => $all_meta['Message'][0],
          'code_list' => $giftcoupon,
          'amount' => $all_meta['_line_subtotal'][0]
        ];

        // Capture template output
        ob_start();
        get_template_part('template-parts/emails/gift', null, ['data' => $data]);
        $email_body = ob_get_clean();

        // Email details
        $to = $all_meta['Delivery Method'][0];
        $subject = __('מתנה מיוחדת עבורך', 'giovanni');
        $headers = [
          'Content-Type: text/html; charset=UTF-8',
          'From: Givanni Raspini <no-reply@giovanniraspini-shop.co.il>'
        ];

        // Send email
        wp_mail($to, $subject, $email_body, $headers);
      }
    }
  }

  add_action('woocommerce_order_status_processing', 'debug_gift_card_product_info', 10, 2);
  add_action('woocommerce_order_status_completed', 'debug_gift_card_product_info', 10, 2);
}




add_action( 'woocommerce_pay_order_before_submit', 'add_coupon_field_to_order_pay' );
function add_coupon_field_to_order_pay( $order ){
    ?>
    <div id="order-pay-coupon" style="margin:20px 0; padding:15px; border:1px solid #ddd; border-radius:8px; background:#fafafa;">
        <label for="order_pay_coupon_input" style="display:block; font-weight:600; margin-bottom:8px;">
            קופון הנחה:
        </label>

        <div style="display:flex; gap:10px;">
            <input
                type="text"
                id="order_pay_coupon_input"
                name="order_pay_coupon_input"
                placeholder="הזן את הקופון כאן"
                style="flex:1; padding:10px; border-radius:6px; border:1px solid #ccc;"
            >
            <button
                type="button"
                class="button"
                id="order_pay_apply_coupon"
                style="padding:10px 18px;"
            >
                החל קופון
            </button>
        </div>

        <div id="order_pay_coupon_message" style="margin-top:10px; font-weight:600;"></div>
    </div>
    <?php
}


add_action( 'wp_ajax_apply_order_pay_coupon', 'apply_order_pay_coupon' );
add_action( 'wp_ajax_nopriv_apply_order_pay_coupon', 'apply_order_pay_coupon' );

function apply_order_pay_coupon() {

    $coupon_code = sanitize_text_field( $_POST['coupon'] );
    $order_id    = intval( $_POST['order_id'] );

    if ( empty( $coupon_code ) ) {
        wp_send_json_error( 'אנא הזן קופון' );
    }

    $order = wc_get_order( $order_id );

    if ( ! $order ) {
        wp_send_json_error( 'הזמנה לא נמצאה' );
    }

    $coupon = new WC_Coupon( $coupon_code );

    if ( ! $coupon->get_id() ) {
        wp_send_json_error( 'קופון לא קיים' );
    }

    if ( ! $coupon->is_valid() ) {
        wp_send_json_error( 'קופון לא תקף' );
    }

    $existing_coupons = array_map( 'strtolower', $order->get_coupon_codes() );

    if ( in_array( strtolower( $coupon_code ), $existing_coupons ) ) {
        wp_send_json_error( 'הקופון כבר הוזן' );
    }

    // Apply coupon to order
    $order->apply_coupon( $coupon_code );

    // Recalculate totals - WooCommerce will use our filter for progressive discounts
    $order->calculate_totals();

    // After calculation, we need to ensure coupon items have correct discount amounts
    // Recalculate progressive discounts and update coupon items
    $recalculated_discounts = calculate_progressive_order_discounts( $order );

    // Update coupon items with recalculated amounts
    foreach ( $order->get_items( 'coupon' ) as $item_id => $item ) {
        $code = $item->get_code();
        if ( isset( $recalculated_discounts[ $code ] ) ) {
            $item->set_discount( $recalculated_discounts[ $code ] );
        }
    }

    // Recalculate totals again with updated discount amounts
    $order->calculate_totals();
    $order->save();

    ob_start();
    wc_get_template( 'checkout/review-order.php', ['order' => $order] );
    $html = ob_get_clean();

    wp_send_json_success([
        'total' => $order->get_total(),
        'html'  => $html
    ]);
}



add_action( 'wp_footer', 'order_pay_coupon_js_loader' );
function order_pay_coupon_js_loader() {

    if ( ! is_wc_endpoint_url( 'order-pay' ) ) {
        return;
    }
    ?>

    <script>
    jQuery(function($){

        $('#order_pay_apply_coupon').on('click', function(){

            var coupon = $('#order_pay_coupon_input').val();

            var orderIdMatch = window.location.pathname.match(/order-pay\/(\d+)/);
            var orderId = orderIdMatch ? orderIdMatch[1] : null;

            if(!orderId){
                $('#order_pay_coupon_message').html('<span style="color:red">מספר הזמנה לא נמצא</span>');
                return;
            }

            $.ajax({
                url: '/wp-admin/admin-ajax.php',
                type: 'POST',
                data: {
                    action: 'apply_order_pay_coupon',
                    coupon: coupon,
                    order_id: orderId
                },
                success: function(response) {

                    if(response.success){
                        $('#order_pay_coupon_message').html('<span style="color:green">הקופון הופעל, מעדכן...</span>');

                        setTimeout(function(){
                            location.reload();
                        }, 700);

                    } else {
                        $('#order_pay_coupon_message').html('<span style="color:red">'+response.data+'</span>');
                    }
                }
            });

        });

    });
    </script>

    <?php
}

/**
 * Global variable to track running discounted prices per cart item.
 * This enables progressive coupon stacking - each coupon applies to the already-discounted price.
 */
$GLOBALS['wc_progressive_discount_prices'] = [];
$GLOBALS['wc_progressive_cart_subtotal'] = 0;
$GLOBALS['wc_applied_coupons_order'] = [];

/**
 * Reset tracked prices when cart totals are recalculated.
 */
add_action( 'woocommerce_before_calculate_totals', function ( $cart ) {
    if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
        return;
    }
    // Reset tracked prices when cart totals are recalculated.
    $GLOBALS['wc_progressive_discount_prices'] = [];
    $GLOBALS['wc_applied_coupons_order'] = [];
    // Store original cart subtotal for progressive calculation
    if ( $cart ) {
        $GLOBALS['wc_progressive_cart_subtotal'] = (float) $cart->get_subtotal();
        // Track coupon order
        $applied_coupons = $cart->get_applied_coupons();
        foreach ( $applied_coupons as $coupon_code ) {
            if ( ! in_array( $coupon_code, $GLOBALS['wc_applied_coupons_order'] ) ) {
                $GLOBALS['wc_applied_coupons_order'][] = $coupon_code;
            }
        }
    }
}, 1 );

/**
 * Calculate all coupon discounts from the current price (already with discounts applied).
 * This ensures coupons stack progressively - each coupon applies to the already-discounted price.
 */
add_filter(
    'woocommerce_coupon_get_discount_amount',
    function ( $discount, $discounting_amount, $cart_item, $single, $coupon ) {

        // Get cart item key for tracking.
        $item_key = null;
        if ( is_array( $cart_item ) && isset( $cart_item['key'] ) ) {
            $item_key = $cart_item['key'];
        } elseif ( is_array( $cart_item ) && isset( $cart_item['product_id'] ) ) {
            $variation_id = isset( $cart_item['variation_id'] ) ? $cart_item['variation_id'] : 0;
            $item_key     = $cart_item['product_id'] . '_' . $variation_id;
        }

        if ( ! $item_key ) {
            return $discount;
        }

        // Initialize global if not set.
        if ( ! isset( $GLOBALS['wc_progressive_discount_prices'] ) ) {
            $GLOBALS['wc_progressive_discount_prices'] = [];
        }

        // Get the base price (original product price before any coupon discounts).
        $base_price = 0;
        if ( is_array( $cart_item ) ) {
            // For cart items, use line_subtotal (price before discounts) if available
            if ( isset( $cart_item['line_subtotal'] ) && isset( $cart_item['quantity'] ) && $cart_item['quantity'] > 0 ) {
                $base_price = (float) $cart_item['line_subtotal'] / (float) $cart_item['quantity'];
            } elseif ( isset( $cart_item['data'] ) && is_callable( [ $cart_item['data'], 'get_price' ] ) ) {
                $base_price = (float) $cart_item['data']->get_price();
            }
        } elseif ( $cart_item instanceof WC_Order_Item ) {
            $product = $cart_item->get_product();
            if ( $product ) {
                $base_price = (float) $product->get_price();
            }
        }

        // Fallback to discounting_amount if base_price is 0
        if ( $base_price <= 0 ) {
            $base_price = (float) $discounting_amount;
        }

        // Get current coupon code
        $coupon_code = $coupon->get_code();

        // Initialize price tracking for this item if not exists - use base price
        if ( ! isset( $GLOBALS['wc_progressive_discount_prices'][ $item_key ] ) ) {
            $GLOBALS['wc_progressive_discount_prices'][ $item_key ] = $base_price;
        }

        // Get the order of coupons to determine which ones were applied before this one
        $current_coupon_index = false;
        if ( ! empty( $GLOBALS['wc_applied_coupons_order'] ) ) {
            $current_coupon_index = array_search( $coupon_code, $GLOBALS['wc_applied_coupons_order'] );
        }

        // Determine current price based on coupon order
        if ( $current_coupon_index === false ) {
            // Coupon order unknown - use tracked price (may not be accurate)
            $current_price = $GLOBALS['wc_progressive_discount_prices'][ $item_key ];
        } elseif ( $current_coupon_index === 0 ) {
            // This is the first coupon - use base price
            $current_price = $base_price;
            $GLOBALS['wc_progressive_discount_prices'][ $item_key ] = $base_price;
        } else {
            // There are previous coupons - recalculate price from base considering all previous coupons
            $current_price = $base_price;

            // Apply all previous coupons in order
            for ( $i = 0; $i < $current_coupon_index; $i++ ) {
                $prev_coupon_code = $GLOBALS['wc_applied_coupons_order'][ $i ];
                $prev_coupon = new WC_Coupon( $prev_coupon_code );

                if ( $prev_coupon->get_id() ) {
                    $prev_coupon_type = $prev_coupon->get_discount_type();
                    $prev_discount = 0;

                    if ( in_array( $prev_coupon_type, [ 'percent', 'percent_product' ] ) ) {
                        $prev_percent = (float) $prev_coupon->get_amount();
                        $prev_discount = $current_price * ( $prev_percent / 100 );
                    } elseif ( $prev_coupon_type === 'fixed_product' ) {
                        $prev_fixed = (float) $prev_coupon->get_amount();
                        $prev_discount = min( $prev_fixed, $current_price );
                    } elseif ( $prev_coupon_type === 'fixed_cart' ) {
                        // For fixed_cart, we need to calculate proportionally
                        $prev_fixed = (float) $prev_coupon->get_amount();
                        $cart_subtotal = isset( $GLOBALS['wc_progressive_cart_subtotal'] ) && $GLOBALS['wc_progressive_cart_subtotal'] > 0
                            ? $GLOBALS['wc_progressive_cart_subtotal']
                            : ( WC()->cart ? (float) WC()->cart->get_subtotal() : $base_price );
                        if ( $cart_subtotal > 0 ) {
                            $prev_discount = ( $current_price / $cart_subtotal ) * $prev_fixed;
                        }
                    }

                    $current_price = max( 0, $current_price - $prev_discount );
                }
            }

            // Update tracked price
            $GLOBALS['wc_progressive_discount_prices'][ $item_key ] = $current_price;
        }

        // Recalculate discount based on coupon type using the current discounted price.
        $coupon_type = $coupon->get_discount_type();

        switch ( $coupon_type ) {
            case 'percent':
            case 'percent_product':
                // Percentage discounts: calculate from current discounted price.
                $percent  = (float) $coupon->get_amount();
                $discount = $current_price * ( $percent / 100 );
                break;

            case 'fixed_cart':
                // Fixed cart discount: distribute proportionally based on current discounted cart total.
                if ( WC()->cart ) {
                    // Use progressive cart subtotal if available, otherwise use current subtotal
                    $cart_subtotal = isset( $GLOBALS['wc_progressive_cart_subtotal'] ) && $GLOBALS['wc_progressive_cart_subtotal'] > 0
                        ? $GLOBALS['wc_progressive_cart_subtotal']
                        : (float) WC()->cart->get_subtotal();

                    if ( $cart_subtotal > 0 ) {
                        $fixed_amount = (float) $coupon->get_amount();
                        // Distribute the fixed discount proportionally based on item's share of cart.
                        // Use current_price (already discounted) for proportional distribution
                        $discount = ( $current_price / $cart_subtotal ) * $fixed_amount;

                        // Update progressive cart subtotal after this discount
                        $GLOBALS['wc_progressive_cart_subtotal'] = max( 0, $cart_subtotal - $fixed_amount );
                    } else {
                        $discount = min( $discount, $current_price );
                    }
                } else {
                    // Fallback: cap at current price.
                    $discount = min( $discount, $current_price );
                }
                break;

            case 'fixed_product':
                // Fixed product discount: apply fixed amount, cap at current price.
                $fixed_amount = (float) $coupon->get_amount();
                $discount     = min( $fixed_amount, $current_price );
                break;

            default:
                // For other coupon types, ensure discount doesn't exceed current price.
                $discount = min( $discount, $current_price );
                break;
        }

        // Update tracked price: subtract this discount from current price.
        $GLOBALS['wc_progressive_discount_prices'][ $item_key ] = max( 0, $current_price - $discount );

        return $discount;
    },
    10,
    5
);

/**
 * Calculate progressive discounts for an order
 * Each coupon applies to the already-discounted amount
 *
 * @param WC_Order $order The order object
 * @return array Array of coupon codes => discount amounts
 */
function calculate_progressive_order_discounts($order) {
  $coupons = $order->get_coupon_codes();

  if (empty($coupons)) {
    return [];
  }

  // Get base subtotal (before any discounts)
  $current_amount = (float) $order->get_subtotal();
  $discounts = [];

  // Apply each coupon progressively
  foreach ($coupons as $code) {
    $coupon = new WC_Coupon($code);

    if (!$coupon->get_id()) {
      continue;
    }

    $coupon_type = $coupon->get_discount_type();
    $discount_amount = 0;

    switch ($coupon_type) {
      case 'percent':
      case 'percent_product':
        // Percentage discount: calculate from current discounted amount
        $percent = (float) $coupon->get_amount();
        $discount_amount = $current_amount * ($percent / 100);
        break;

      case 'fixed_cart':
        // Fixed cart discount: apply fixed amount
        $fixed_amount = (float) $coupon->get_amount();
        $discount_amount = min($fixed_amount, $current_amount);
        break;

      case 'fixed_product':
        // Fixed product discount: apply fixed amount per product
        // For orders, we need to calculate proportionally
        $fixed_amount = (float) $coupon->get_amount();
        $subtotal = (float) $order->get_subtotal();

        if ($subtotal > 0) {
          // Distribute proportionally based on current amount
          $discount_amount = ($current_amount / $subtotal) * $fixed_amount;
        } else {
          $discount_amount = min($fixed_amount, $current_amount);
        }
        break;

      default:
        // For other types, try to get from order item
        foreach ($order->get_items('coupon') as $item) {
          if (strtolower($item->get_code()) === strtolower($code)) {
            $discount_amount = (float) $item->get_discount();
            break;
          }
        }
        // Cap at current amount
        $discount_amount = min($discount_amount, $current_amount);
        break;
    }

    // Update current amount after this discount
    $current_amount = max(0, $current_amount - $discount_amount);

    // Store discount for this coupon
    $discounts[$code] = $discount_amount;
  }

  return $discounts;
}

/**
 * Get coupon label from multiple sources
 *
 * @param WC_Coupon $coupon The coupon object
 * @param string $code The coupon code
 * @return string The coupon label
 */
function get_coupon_label($coupon, $code) {
  $coupon_label = '';

  // 1. Try coupon description
  $coupon_label = $coupon->get_description();

  // 2. Check coupon post title
  if (empty($coupon_label)) {
    $coupon_id = $coupon->get_id();
    if ($coupon_id) {
      $post_title = get_the_title($coupon_id);
      if (!empty($post_title) && $post_title !== $code) {
        $coupon_label = $post_title;
      }
    }
  }

  // 3. Fallback to coupon code
  if (empty($coupon_label)) {
    $coupon_label = $code;
  }

  return $coupon_label;
}

/**
 * Modify discount row to show individual coupon labels on order-pay page
 * Recalculate discounts progressively instead of using saved values
 */
add_filter('woocommerce_get_order_item_totals', function($total_rows, $order, $tax_display) {
  // Only modify on order-pay page
  if (!is_wc_endpoint_url('order-pay')) {
    return $total_rows;
  }

  // Check if discount row exists
  if (!isset($total_rows['discount'])) {
    return $total_rows;
  }

  $coupons = $order->get_coupon_codes();

  if (empty($coupons)) {
    return $total_rows;
  }

  // Recalculate discounts progressively
  $discounts = calculate_progressive_order_discounts($order);

  // Build custom discount display with coupon labels
  $discount_html = '';

  foreach ($coupons as $code) {
    $coupon = new WC_Coupon($code);

    if (!$coupon->get_id()) {
      continue;
    }

    // Get coupon label
    $coupon_label = get_coupon_label($coupon, $code);

    // Get recalculated discount amount
    $discount_amount = isset($discounts[$code]) ? $discounts[$code] : 0;

    // Add coupon line
    $discount_html .= '<div class="discount-line">';
    $discount_html .= '<strong>' . esc_html($coupon_label) . '</strong>';
    $discount_html .= ' – ' . wc_price($discount_amount);
    $discount_html .= '</div>';
  }

  // Replace discount value with our custom HTML
  $total_rows['discount']['value'] = $discount_html;

  return $total_rows;
}, 10, 3);

/**
 * Get the amount of a specific coupon for an order
 */
function wc_get_coupon_discount_amount($order, $coupon)
{
  $discount = 0;

  foreach ($order->get_items('coupon') as $item) {
    if (strtolower($item->get_code()) === strtolower($coupon->get_code())) {
      $discount += $item->get_discount();
    }
  }

  return $discount;
}


