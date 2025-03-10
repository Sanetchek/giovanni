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