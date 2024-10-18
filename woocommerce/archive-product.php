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

get_header( 'shop' ); ?>

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

			$page_id = !is_shop() ? $term : 'option';

			$banner1 = get_field('banner_1', $page_id);
			$banner2 = get_field('banner_2', $page_id);
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
			<div class="filters-sort-row-mob">
				<div class="filter-mob">
					<button type="button" class="btn btn-no-border open-filter">
						<span class="filter-label"><?= __('מסנן', 'giovanni') ?></span>
						<svg class='icon-filter' width='24' height='24'>
							<use href='<?= assets('img/sprite.svg#icon-filter') ?>'></use>
						</svg>
					</button>
				</div>
				<div class="sorting-mob">
					<?php display_sort_options_mobile(); ?>
				</div>
			</div>

			<div class="products-container">
				<?php display_product_filters(); ?>
			</div>
		</div>

		<?php
		woocommerce_product_loop_start();

		if ( wc_get_loop_prop( 'total' ) ) {
			$count = 0;

			while ( have_posts() ) {
				the_post();

				global $product;

				// Ensure visibility.
				if ( empty( $product ) || ! $product->is_visible() ) {
					return;
				}

				/**
				 * Hook: woocommerce_shop_loop.
				 */
				do_action( 'woocommerce_shop_loop' );

				// Use product-card-big for all posts except the 11th and 19th
				if ( $count === 10 && $banner1 && $banner1['enable_disable_banner'] ) {
					get_template_part('template-parts/product-card-adv', '', ['banner' => $banner1]);
				}

				if ( $count === 18 && $banner2 && $banner2['enable_disable_banner'] ) {
					get_template_part('template-parts/product-card-adv', '', ['banner' => $banner2]);
				}

				wc_get_template_part( 'content', 'product' );

				$count++;
			}
		}

		woocommerce_product_loop_end(); ?>

		<div id="page-loader" class="page-loader hidden"></div>

		<?php
		/**
		 * Hook: woocommerce_after_shop_loop.
		 *
		 * @hooked woocommerce_pagination - 10
		 */
		do_action( 'woocommerce_after_shop_loop' ); ?>

		<div class="products-container archive-bottom-text">
			<div class="archive-text-container">
				<div class="archive-text"><?= get_field('bottom_test', 'option') ?></div>
			</div>
			<button type="button" id="show-archive-text" class="btn btn-no-border btn-hover"><?= __('קרא עוד', 'giovanni') ?></button>
		</div>

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
