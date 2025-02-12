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


	$category_id = 314; // ID charms category
	$current_term = get_queried_object();
	$has_attributes = false;
	if ($current_term && is_a($current_term, 'WP_Term')) {
		$args = array(
			'post_type' => 'product',
			'posts_per_page' => -1,
			'tax_query' => array(
				array(
					'taxonomy' => 'product_cat',
					'field'    => 'term_id',
					'terms'    => $current_term->term_id,
				),
			),
		);
		$products = new WP_Query($args);

		if ($products->have_posts()) {
			while ($products->have_posts()) {
				$products->the_post();
				$product = wc_get_product(get_the_ID());

				if ($product && $product->get_attributes()) {
					$has_attributes = true;
					break;
				}
			}
		}
		wp_reset_postdata();
	}


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

	if ($is_category_box) {
	?>
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
			$taxs = array_merge($taxonomies_pages_with_type, $taxonomies_category_box_with_type);

			if (!empty($taxs)) :
				get_template_part('template-parts/sections/taxonomies_boxes', '', ['taxonomies' => $taxs]);
			endif;
			?>
		</div>

	<?php } else { ?>

		<div class="archive-header">
			<?php
				$term = get_queried_object();

				$bg_image = get_field('background_image_default', 'option');
				$bg_image_mob = get_field('background_image_default_mob', 'option');
				$archive_image = '';
				$archive_image_mob = '';
				$use_archive_image = false;

				if (!is_shop()) {
					$hero = get_field('hero', $term);
					if ($hero) {
						$archive_image = $hero['page_background'];
						$archive_image_mob = $hero['page_background_mob'];
						$use_archive_image = $hero['use_page_background'];
					}
				}
			?>

			<?php if ($use_archive_image) : ?>
				<?php show_image($archive_image, '1920-400', ['class' => 'page-desk']) ?>
				<?php
					$bg_mob = $archive_image_mob ? $archive_image_mob : $archive_image;
					show_image($bg_mob, '768-400', ['class' => 'page-mob']);
				?>
			<?php else: ?>
				<?php show_image($bg_image, '1920-400', ['class' => 'page-desk']) ?>
				<?php
					$bg_mob = $bg_image_mob ? $bg_image_mob : $bg_image;
					show_image($bg_mob, '768-400', ['class' => 'page-mob']);
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
	<?php } ?>

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

		<?php if (is_shop()) : ?>
			<div class="shop-taxonomies">
				<?php
					$shop_page_id = get_option( 'woocommerce_shop_page_id' );
					$taxonomies = get_field('taxonomy_filter', $shop_page_id);
					get_template_part('template-parts/sections/taxonomies', '', ['title' => '', 'taxonomies' => $taxonomies]);
				?>
			</div>
		<?php endif ?>

		<div id="products-filter" class="products-filter">
			<div class="products-container">
				<?php display_product_filters(); ?>
			</div>
		</div>

		<div class="product-container <?php if ( has_term( $category_id, 'product_cat' ) || has_term( get_term_children( $category_id, 'product_cat' ), 'product_cat' ) ) { echo 'charms-cat'; } ?>">

		<?php
		// Detect if we're on the Shop page or a Product Category archive.
		if (is_shop() || is_product_category()) {
			// Determine the current page
			$paged = get_query_var('paged') ? get_query_var('paged') : 1; // Default to page 1

			$args = array(
				'post_type'      => 'product',
				'posts_per_page' => 20, // You can modify this value
				'paged'          => $paged, // Pagination parameter
				'post_status'    => 'publish',
				'orderby'  => 'meta_value_num',
				'meta_key' => 'total_sales',
				'order'    => 'DESC',
			);

			// For category archives, add a tax_query to filter by category.
			if (is_product_category()) {
				$current_category = get_queried_object();

				if ($current_category && !is_wp_error($current_category)) {
					$category_id = $current_category->term_id; // Get the current category ID.
					$args['tax_query'] = array(
						'relation' => 'AND',
						array(
							'taxonomy' => 'product_cat',
							'field'    => 'term_id',
							'terms'    => $category_id,
						),
					);
				}
			}

			// Custom query for products.
			$query = new WP_Query($args);

			if (defined('WP_DEBUG') && WP_DEBUG) {
				error_log('Query Args First Load: ' . print_r($args, true));
			}

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
		/* hidden block */
		/*
		<div class="products-container archive-bottom-text">
			<div class="archive-text-container">
				<div class="archive-text"><?= get_field('bottom_test', 'option') ?></div>
			</div>
			<button type="button" id="show-archive-text" class="btn btn-no-border btn-hover"><?= __('קרא עוד', 'giovanni') ?></button>
		</div>
		*/
		?>

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
