<div class="taxonomies page-container taxonomies-category-boxes container">
  <?php
  /**
   * Hook: woocommerce_before_main_content.
   *
   * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
   * @hooked woocommerce_breadcrumb - 20
   * @hooked WC_Structured_Data::generate_website_data() - 30
   */
  do_action( 'woocommerce_before_main_content' );

  /**
   * Hook: woocommerce_shop_loop_header.
   *
   * @since 8.6.0
   *
   * @hooked woocommerce_product_taxonomy_archive_header - 10
   */
  do_action( 'woocommerce_shop_loop_header' );

  $term_id = get_queried_object_id();
  $taxs = get_merged_taxonomies($term_id);
  if (!empty($taxs)) :
    get_template_part('template-parts/sections/taxonomies_boxes', '', ['taxonomies' => $taxs]);
  endif;
  ?>
</div>