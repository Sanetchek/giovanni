<div class="product-container single-product-bestsellers">
  <h2 class="bestsellers-title related-title"><?php echo __('מוצרים הנמכרים ביותר', 'giovanni'); ?></h2>

  <?php
  // Query for best-selling products
  $args = [
    'post_type' => 'product',
    'posts_per_page' => 10, // Adjust the number of products to display
    'meta_key' => 'total_sales',
    'orderby' => 'meta_value_num',
    'order' => 'DESC',
    'meta_query' => [
      [
        'key' => '_stock_status',
        'value' => 'instock',
        'compare' => '='
      ]
    ]
  ];

  $bestsellers_query = new WP_Query($args);

  $productArray = [];

  if ($bestsellers_query->have_posts()) {
    while ($bestsellers_query->have_posts()) {
      $bestsellers_query->the_post();
      $productArray[] = get_the_ID(); // Collect product IDs
    }
    wp_reset_postdata();
  }

  if (!empty($productArray)) {
    // Pass the productArray to your slider template
    get_template_part('template-parts/product', 'slider', ['productArray' => $productArray]);
  } else {
    echo '<p>' . __('לא נמצאו מוצרים נמכרים ביותר.', 'giovanni') . '</p>';
  }
  ?>
</div>