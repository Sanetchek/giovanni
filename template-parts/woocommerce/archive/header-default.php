<div class="archive-header">
  <?php
    $category = get_queried_object();
    $bg_image = get_field('background_image_default', 'option');
    $bg_image_mob = get_field('background_image_default_mob', 'option');
    $archive_image = '';
    $archive_image_mob = '';
    $use_archive_image = false;

    if (!is_shop()) {
      $hero = get_field('hero', $category);
      if ($hero) {
        $archive_image = $hero['page_background'];
        $archive_image_mob = $hero['page_background_mob'];
        $use_archive_image = $hero['use_page_background'];
      }
    }
  ?>

  <?php if ($use_archive_image) : ?>
    <?php
      $bg_mob = $archive_image_mob ? $archive_image_mob : $archive_image;
      generate_archive_picture_source($archive_image, $bg_mob);
    ?>
  <?php else: ?>
    <?php
      $bg_mob = $bg_image_mob ? $bg_image_mob : $bg_image;
      generate_archive_picture_source($bg_image, $bg_mob);
    ?>
  <?php endif; ?>

  <div class="archive-header-content">
    <div class="container">
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
      ?>
    </div>
  </div>
</div>