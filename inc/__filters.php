<?php

/**
 * Get products with attributes based on current query and filters.
 *
 * @return array
 */
function get_products_with_attributes($search_query = '') {
  $args = build_query_args($search_query);

  // Query products
  $products = new WP_Query($args);

  // Collect product attributes
  $attributes = collect_product_attributes($products);

  wp_reset_postdata();
  return $attributes;
}

/**
 * Build query arguments for fetching products.
 *
 * @return array
 */
function build_query_args($search_query = '') {
  $args = [
    'post_type'      => 'product',
    'posts_per_page' => -1,
    'post_status'    => 'publish',
    'tax_query'      => [],
  ];

  if ($search_query) {
    $args['s'] = $search_query;
  }

  // Check if current page is a product category page
  if (is_product_category()) {
    $current_term = get_queried_object();
    $args['tax_query'][] = [
      'taxonomy' => 'product_cat',
      'field'    => 'term_id',
      'terms'    => $current_term->term_id,
    ];
  }

  // Add attribute filters from URL
  foreach ($_GET as $key => $value) {
    if (strpos($key, 'filter_') === 0 && !empty($value)) {
      $taxonomy = str_replace('filter_', '', $key);
      $args['tax_query'][] = [
        'taxonomy' => $taxonomy,
        'field'    => 'slug',
        'terms'    => explode(',', sanitize_text_field($value)),
        'operator' => 'IN',
      ];
    }
  }

  return $args;
}

/**
 * Collect attributes from the queried products.
 *
 * @param WP_Query $products
 * @return array
 */
function collect_product_attributes($products) {
  $attributes = [];

  if ($products->have_posts()) {
    while ($products->have_posts()) {
      $products->the_post();
      $product = wc_get_product();

      if (!$product) continue;

      foreach ($product->get_attributes() as $attribute) {
        $attribute_name = $attribute->get_name();
        $attribute_label = wc_attribute_label($attribute_name);
        $attribute_options = $attribute->get_options();

        if (!isset($attributes[$attribute_name])) {
          $attributes[$attribute_name] = [
            'label'   => $attribute_label,
            'options' => [],
          ];
        }

        foreach ($attribute_options as $option) {
          if (!in_array($option, $attributes[$attribute_name]['options'])) {
            $attributes[$attribute_name]['options'][] = $option;
          }
        }
      }
    }
  }

  return $attributes;
}

/**
 * Display product filters form.
 */
function display_product_filters($search_query = '') {
  $attributes = get_products_with_attributes($search_query);

  echo '<form id="product-filters" method="get">';
  echo '<div class="products-filter-container">';
  if ($search_query) {
    echo '<input type="hidden" name="s" value="'.$search_query.'">';
  }

  if (!empty($attributes)) {
    echo '<div class="filter-side">';
    foreach ($attributes as $attribute_name => $attribute_data) {
      display_filter_options($attribute_name, $attribute_data);
    }
    echo '</div>';
    echo '<div class="mobile-header"><button class="reset js-reset"> אִתחוּל</button><div class="title">סינון לפי:</div><button class="js-close-filters icon-close" aria-label="close"></button></div>';
  }

  display_sort_options();

  echo '</input>';
  echo '</form>';
}

/**
 * Display individual filter options for attributes.
 *
 * @param string $attribute_name
 * @param array $attribute_data
 */
function display_filter_options($attribute_name, $attribute_data) {
  echo '<div class="filter-wrap filter-collection">';
  echo '<button type="button" class="btn btn-no-border btn-filter">';
  echo '<span>' . esc_html($attribute_data['label']) . '</span>';
  echo '<svg class="icon-chevron-down" width="24" height="24">';
  echo '<use href="' . esc_url(assets('img/sprite.svg#icon-chevron-down')) . '"></use>';
  echo '</svg>';
  echo '</button>';
  echo '<div class="filter-dropdown">';

  foreach ($attribute_data['options'] as $term_id) {
    $term = get_term($term_id);
    if ($term && !is_wp_error($term)) {
      echo '<label>';
      echo '<input type="checkbox" name="filter_' . esc_attr($attribute_name) . '[]" value="' . esc_attr($term->slug) . '" class="filter-checkbox"> ';
      echo '<span class="fake-checkbox"></span>';
      echo esc_html($term->name);
      echo '</label>';
    }
  }

  echo '</div></div>';
}

/**
 * Display sorting options in the filter form.
 */
function display_sort_options() {
  echo '<div class="filter-side">';
  echo '<div class="filter-wrap filter-sort">';
  echo '<span class="filter-label">' . __('מיין לפי', 'giovanni') . '</span>';
  echo '<button type="button" class="btn btn-no-border btn-filter">';
  echo '<span class="filter-sort-text">' . __('המלצות', 'giovanni') . '</span>';
  echo '<svg class="icon-chevron-down" width="20" height="20" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 12 16"><path fill-rule="evenodd" clip-rule="evenodd" d="M6 13.293l3.646-3.647.707.708L6 14.707l-4.354-4.353.708-.708L6 13.293zM6 2.707L2.354 6.354l-.708-.708L6 1.293l4.354 4.353-.708.708L6 2.707z" fill="black"/></svg>';
  echo '</button>';
  echo '<input type="hidden" name="sort" id="sort" value="popularity">';
  echo '<div class="filter-dropdown"><ul>';
  $sort_options = [
      'menu_order' => __('מומלץ', 'giovanni'),
      'date'       => __('חדשים ביותר', 'giovanni'),
      'price'      => __('מחיר: מהנמוך לגבוה', 'giovanni'),
      'price-desc' => __('מחיר: מהגבוה לנמוך', 'giovanni'),
  ];
  foreach ($sort_options as $value => $label) {
      echo '<li><button type="button" data-sort="' . esc_attr($value) . '" class="filter-sort-item">' . esc_html($label) . '</button></li>';
  }
  echo '</ul></div></div></div>';
}
/**
 * Display sorting options in the filter form.
 * Separate code for mobile version.
 */
function display_sort_options_mobile() {
    echo '<div class="filter-side">';
    echo '<div class="filter-wrap filter-sort">';
    echo '<span class="filter-label">' . __('מיין לפי', 'giovanni') . '</span>';
    echo '<button type="button" class="btn btn-no-border btn-filter">';
    echo '<span class="filter-sort-text">' . __('המלצות', 'giovanni') . '</span>';
    echo '<svg class="icon-chevron-down" width="20" height="20" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 12 16"><path fill-rule="evenodd" clip-rule="evenodd" d="M6 13.293l3.646-3.647.707.708L6 14.707l-4.354-4.353.708-.708L6 13.293zM6 2.707L2.354 6.354l-.708-.708L6 1.293l4.354 4.353-.708.708L6 2.707z" fill="black"/></svg>';
    echo '</button>';
    echo '<input type="hidden" name="sort" id="sort-mobile" value="popularity">';
    echo '<div class="filter-dropdown"><ul>';
    $sort_options = [
        'menu_order' => __('מומלץ', 'giovanni'),
        'date'       => __('חדשים ביותר', 'giovanni'),
        'price'      => __('מחיר: מהנמוך לגבוה', 'giovanni'),
        'price-desc' => __('מחיר: מהגבוה לנמוך', 'giovanni'),
    ];
    foreach ($sort_options as $value => $label) {
        echo '<li><button type="button" data-sort="' . esc_attr($value) . '" class="filter-sort-item filter-sort-item-mobile">' . esc_html($label) . '</button></li>';
    }
    echo '</ul></div></div></div>';
}

/**
 * AJAX Callback for Loading More Products
 *
 * @return void
 */
function load_more_products() {
  check_ajax_referer('giovanni_product_filter_nonce', 'nonce');

  $paged       = isset($_POST['page']) ? $_POST['page'] + 1 : 1;
  $category_id = isset($_POST['category_id']) ? $_POST['category_id'] : false;
  $decoded_data = isset($_POST['formData']) ? urldecode($_POST['formData']) : '';
  parse_str($decoded_data, $form_data);

  // Build query arguments
  $args  = build_product_query_args($form_data, $paged, $category_id);
  $query = new WP_Query($args);

  display_products($query);
  wp_die();
}

add_action('wp_ajax_load_more_products', 'load_more_products');
add_action('wp_ajax_nopriv_load_more_products', 'load_more_products');

/**
 * AJAX Callback for Filtering Products
 *
 * @return void
 */
function handle_filter_products() {
  check_ajax_referer('giovanni_product_filter_nonce', 'nonce');

  $category_id = isset($_POST['category_id']) ? $_POST['category_id'] : false;
  $decoded_data = urldecode($_POST['formData']);
  parse_str($decoded_data, $form_data);

  // Build query arguments
  $query_args = build_product_query_args($form_data, 1, $category_id);
  $query      = new WP_Query($query_args);

  // Start output buffering to capture the HTML
  ob_start();
  display_products($query);
  $response = ob_get_clean();

  // Return the HTML to the AJAX call
  echo $response;
  wp_die();
}

add_action('wp_ajax_filter_products', 'handle_filter_products');
add_action('wp_ajax_nopriv_filter_products', 'handle_filter_products');

/**
 * Build Query Arguments for Product Filtering and Sorting
 *
 * @param array $form_data Form data from the AJAX request.
 * @param int   $paged     Current page number for pagination.
 * @return array Query arguments.
 */
function build_product_query_args($form_data = [], $paged = 1, $category_id = false) {
  $args = [
    'post_type'      => 'product',
    'paged'          => $paged,
    'posts_per_page' => !empty($form_data['posts_per_page']) ? $form_data['posts_per_page'] : 20,
    'meta_query'     => [],
    'tax_query'      => [
      'relation' => 'AND', // Ensure all conditions are met
    ],
  ];

  if (!empty($form_data['s'])) {
    $args['s'] = $form_data['s'];
  }

  // Add category filtering if category_id is provided
  if ($category_id) {
    $args['tax_query'][] = [
      'taxonomy' => 'product_cat',
      'field'    => 'term_id',
      'terms'    => $category_id,
    ];
  }

  // Handle sorting options
  if (!empty($form_data['sort'])) {
    switch ($form_data['sort']) {
      case 'popularity':
        $args['orderby']  = 'meta_value_num';
        $args['meta_key'] = 'total_sales';
        break;
      case 'rating':
        $args['orderby']  = 'meta_value_num';
        $args['meta_key'] = '_wc_average_rating';
        break;
      case 'date':
        $args['orderby'] = 'date';
        $args['order']   = 'DESC';
        break;
      case 'price':
        $args['orderby']  = 'meta_value_num';
        $args['meta_key'] = '_price';
        $args['order']    = 'ASC';
        break;
      case 'price-desc':
        $args['orderby']  = 'meta_value_num';
        $args['meta_key'] = '_price';
        $args['order']    = 'DESC';
        break;
      default:
        $args['orderby'] = 'menu_order';
        $args['order']   = 'ASC';
        break;
    }
  }

  // Handle attribute filtering
  $attribute_tax_query = [];
  foreach ($form_data as $key => $value) {
    if (strpos($key, 'filter_') === 0 && !empty($value)) {
      $taxonomy = str_replace('filter_', '', $key);
      $attribute_tax_query[] = [
        'taxonomy' => $taxonomy,
        'field'    => 'slug',
        'terms'    => $value,
      ];
    }
  }

  // Merge attribute filters into tax_query
  if (!empty($attribute_tax_query)) {
    $args['tax_query'] = array_merge($args['tax_query'], $attribute_tax_query);
  }

  return $args;
}



/**
 * Display Products Based on Query
 *
 * @param WP_Query $query The WP_Query object.
 * @return void
 */
function display_products($query) {
  if ($query->have_posts()) {
    while ($query->have_posts()) {
      $query->the_post();
      wc_get_template_part('content', 'product');
    }
  } else {
    echo '';
  }
  wp_reset_postdata();
}