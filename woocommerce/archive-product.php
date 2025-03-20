<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 50.0.0
 */
defined( 'ABSPATH' ) || exit;
get_header( 'shop' );

//show category boxes instead of .archive-header
$category = get_queried_object();
$term_id = get_queried_object_id();
$is_category_box = false;

if ($category && is_a($category, 'WP_Term')) {
	$term_id = $category->term_id;  // Correctly accessing term_id
	$is_category_box = get_field('activate_category_boxes', 'product_cat_' . $category->term_id);
}

$page_id = !is_shop() ? 'product_cat_' . $term_id : 'option';

$banner1 = get_field('banner_1', $page_id);
$banner2 = get_field('banner_2', $page_id);

// Hero Section
if ($is_category_box) {
	get_template_part( 'template-parts/woocommerce/archive/header', 'category-box' );
} else {
	get_template_part( 'template-parts/woocommerce/archive/header', 'default' );
} ?>

</div>

<?php
if ( woocommerce_product_loop() ) { ?>

	<div class="products-container">
		<?php
		/**
		 * Hook: woocommerce_before_shop_loop.
		 *
		 * @hooked woocommerce_output_all_notices - 10
		 * @hooked woocommerce_result_count - 20
		 * @hooked woocommerce_catalog_ordering - 30
		 */
		do_action( 'woocommerce_before_shop_loop' ); ?>

	</div>

	<!-- Taxonomies for shop page -->
	<?php if (is_shop()) : ?>
		<div class="shop-taxonomies">
			<?php
				$shop_page_id = get_option( 'woocommerce_shop_page_id' );
				$taxonomies = get_field('taxonomy_filter', $shop_page_id);
				get_template_part('template-parts/sections/taxonomies', '', ['title' => '', 'taxonomies' => $taxonomies]);
			?>
		</div>
	<?php endif ?>

	<!-- Filter section -->
	<div id="products-filter" class="products-filter">
		<div class="products-container">
			<?php display_product_filters(); ?>
		</div>
	</div>

	<!-- Loop products -->
	<?php $category_id = 314; // ID charms category ?>
	<div class="product-container <?php if ( has_term( $category_id, 'product_cat' ) || has_term( get_term_children( $category_id, 'product_cat' ), 'product_cat' ) ) { echo 'charms-cat'; } ?>">

		<?php
		// Detect if we're on the Shop page or a Product Category archive.
		if (is_shop() || is_product_category()) {
			// Custom query for products.
			$query = get_paginated_products();

			if ($query->have_posts()) {
				woocommerce_product_loop_start();

				$count = 0;

				while ($query->have_posts()) {
					$query->the_post();

					global $product;

					// Ensure visibility.
					if (empty($product) || !$product->is_visible()) {
						continue;
					}

					/**
					 * Hook: woocommerce_shop_loop.
					 */
					do_action('woocommerce_shop_loop');

					// Use product-card-adv for specific positions.
					if ($count === 10 && isset($banner1['enable_disable_banner']) && $banner1['enable_disable_banner']) {
						get_template_part('template-parts/product-card-adv', '', ['banner' => $banner1]);
					}

					if ($count === 18 && isset($banner2['enable_disable_banner']) && $banner2['enable_disable_banner']) {
						get_template_part('template-parts/product-card-adv', '', ['banner' => $banner2]);
					}

					// Default product template.
					wc_get_template_part('content', 'product');

					$count++;
				}

				woocommerce_product_loop_end();
			} else {
				/**
				 * Hook: woocommerce_no_products_found.
				 */
				do_action('woocommerce_no_products_found');
			}

			// Restore original Post Data.
			wp_reset_postdata();
		} else {
			// If not on Shop or Category, fallback to default WooCommerce behavior.
			woocommerce_product_loop_start();

			if (wc_get_loop_prop('total')) {
				while (have_posts()) {
					the_post();

					/**
					 * Hook: woocommerce_shop_loop.
					 */
					do_action('woocommerce_shop_loop');

					wc_get_template_part('content', 'product');
				}
			} else {
				/**
				 * Hook: woocommerce_no_products_found.
				 */
				do_action('woocommerce_no_products_found');
			}

			woocommerce_product_loop_end();
		}
		?>

	</div>
	<div id="page-loader" class="page-loader hidden"></div>

	<?php
	/**
	 * Hook: woocommerce_after_shop_loop.
	 *
	 * @hooked woocommerce_pagination - 10
	 */
	do_action( 'woocommerce_after_shop_loop' ); ?>

	<?php
} else {
	/**
	 * Hook: woocommerce_no_products_found.
	 *
	 * @hooked wc_no_products_found - 10
	 */
	do_action( 'woocommerce_no_products_found' );
}

/**
 * Hook: woocommerce_after_main_content.
 *
 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
 */
do_action( 'woocommerce_after_main_content' ); ?>

<?php
get_footer( 'shop' );
