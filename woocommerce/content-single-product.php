<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 50.0.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

/**
 * Hook: woocommerce_before_single_product.
 *
 * @hooked woocommerce_output_all_notices - 10
 */
do_action( 'woocommerce_before_single_product' );

if ( post_password_required() ) {
	echo get_the_password_form(); // WPCS: XSS ok.
	return;
}

$categories_array = product_collection_categories();
?>
<div id="product-<?php the_ID(); ?>" <?php wc_product_class( '', $product ); ?>>

	<div class="product-container product-summary-wrap">
		<?php
		/**
		 * Image gallery.
		 */
		get_template_part('template-parts/product/single');
		?>

		<div class="product-summary">
			<div class="product-summary-content">
				<?php
				// Output the breadcrumbs
				woocommerce_breadcrumb();

				// Output the product title
				woocommerce_template_single_title();

				// Output the product sub title
				$sub_title = get_field('sub_title');
				$short_description = apply_filters('woocommerce_short_description', $post->post_excerpt);

				if (!empty($sub_title)) {
					echo '<p class="product-short-description">' . $sub_title . '</p>';
				} elseif (!empty($short_description)) {
					echo '<p class="product-short-description">' . $short_description . '</p>';
				}


				// Output the product price
				woocommerce_template_single_price();

				// Add Find your size Popup
				$radio_value = get_field('use_size_table_popup');
				if ( $radio_value ) {
					if ($radio_value !== 'none') {
						echo '<div class="product-size-container">';
						echo '<div class="body-s-medium-u">'. __('זמין ב', 'giovanni') .'</div>';
						echo '<a href="#" class="open-modal-btnsize">'.__('מצא את המידה שלך', 'giovanni').'</a>';
						echo '</div>';
					}
				}

				// Output the add to cart button
				woocommerce_template_single_add_to_cart();
				?>

				<div class="product-summary-additional">
					<?php echo simpleLikes(get_the_ID()) ?>

					<div class="product-share">
						<button type="button" class="product-share-btn">
							<svg class='icon-share-outline' width='24' height='24'>
								<use href='<?= assets('img/sprite.svg#icon-share-outline') ?>'></use>
							</svg>
						</button>

						<div class="share-dropdown">
							<?php
								$product_url = get_permalink( $product->get_id() );
								$product_title = $product->get_title();
								echo socialShare($product_url, $product_title);
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="single-product-content">
		<div class="product-container">
			<div class="single-product-content-wrap">
				<?php if (get_the_content()) : ?>
					<div class="single-product-description">
						<h2 class="single-product-title"><?= __('Description', 'giovanni') ?></h2>
						<?php the_content() ?>
					</div>
				<?php endif ?>

				<?php $details = get_field('details') ?>
				<?php if ($details) : ?>
					<div class="single-product-details">
						<h2 class="single-product-title"><?= __('Details', 'giovanni') ?></h2>

						<ul class="product-details-list">
							<?php foreach ($details as $item) : ?>
								<li class="product-details-item"><?= $item['text'] ?></li>
							<?php endforeach ?>
						</ul>
					</div>
				<?php endif ?>
			</div>
		</div>
	</div>

	<div class="product-user-info-wrap">
		<div class="product-container single-product-advcards">
			<div class="product-user-info">
				<?php $group = get_field('free_return', 'option') ?>
				<?php if ($group) : ?>
					<?php
						$group['icon'] = 'returns';
						$group['page_id'] = 1;
					?>
					<?php get_template_part('template-parts/info', 'card', ['group' => $group]) ?>
				<?php endif ?>

				<?php $group = get_field('fast_shipping', 'option') ?>
				<?php if ($group) : ?>
					<?php
						$group['icon'] = 'delivery';
						$group['page_id'] = 2;
					?>
					<?php get_template_part('template-parts/info', 'card', ['group' => $group]) ?>
				<?php endif ?>

				<?php $group = get_field('product_handling', 'option') ?>
				<?php if ($group) : ?>
					<?php
						$group['icon'] = 'shield';
						$group['page_id'] = 3;
					?>
					<?php get_template_part('template-parts/info', 'card', ['group' => $group]) ?>
				<?php endif ?>
			</div>
		</div>
	</div>

	<?php
		//this function shows products if they are selected in upsells or cross-sells. If nothing is set - show related items
		$product_id = $product->get_id();
		$upsell_ids = $product->get_upsell_ids();
		$cross_sell_ids = $product->get_cross_sell_ids();

		$productArray = array_merge($upsell_ids, $cross_sell_ids);
	?>
	<?php if (! empty($productArray)) : ?>
		<div class="product-container single-product-related">
			<h2 class="related-title"><?php echo __('מומלצים ביחד', 'giovanni') ?></h2>
			<?php	get_template_part('template-parts/product', 'slider', ['productArray' => $productArray]);	?>
		</div>
	<?php endif ?>

	<?php if ($categories_array) : ?>
		<div class="main-wrap">
			<div class="single-product-post">
				<?php
				foreach ($categories_array as $key => $term_id) {
					// Get post from ACF field
					$post_list = get_field('post_list', 'product_cat_' . $term_id);

					if ($post_list) {
						echo '<div class="single-product-container">';
						foreach ($post_list as $item) {
							get_template_part('template-parts/page/post', '', ['block' => $item]);
						}
						echo '</div>';
					}
				}
				?>
			</div>
		</div>
	<?php endif ?>

	<?php if ($categories_array) : ?>
    <div class="main-wrap">
			<div class="single-product-post">
				<?php
					foreach ($categories_array as $key => $term_id) {
						// Get the term object for the category
						$category = get_term($term_id, 'product_cat');

						// Get default background images
						$bg_image = get_field('background_image_default', 'option');
						$bg_image_mob = get_field('background_image_default_mob', 'option');

						// Initialize variables for the archive image logic
						$archive_image = '';
						$archive_image_mob = '';

						// Check if the term has a 'hero' field
						$category_image = get_field('category_image', 'term_' . $term_id);
						$category_image_mob = get_field('category_image_mob', 'term_' . $term_id);
						if ($category_image) {
							$archive_image = $category_image;
							$archive_image_mob = $category_image_mob;
						}

						// Determine which image to use
						$image = $archive_image
							? ($archive_image ?: $bg_image)
							: $bg_image;
						$image_mob = $archive_image
							? ($archive_image_mob ?: $archive_image ?: $bg_image_mob)
							: $bg_image_mob;

						// Retrieve other data for the item
						$title = $category->name;
						$description = term_description($term_id, 'product_cat');
						$link = get_term_link($term_id, 'product_cat');
						$link_label = __('בקר בקטגוריה', 'giovanni');

						// Create the $item array
						$item = [
							'image' => $image,
							'image_mob' => $image_mob,
							'title' => $title,
							'description' => $description,
							'link_label' => $link_label,
							'link' => $link,
						];

						// Determine class and shadow based on key
						$class = $key % 2 ? 'second' : 'first';
						$shadow = $key % 2 ? 0 : 1;

						echo '<div class="single-product-container">';
								/**
								 * 'item' -> Group of ACF fields (names: image, title, description, link_label, link)
								 * 'class' -> (string) first/second (default: first)
								 * 'shadow' -> (number) 0/1 - it puts shadow on left or right (default: 1)
								 */
								get_template_part('template-parts/main', 'post', [
										'item' => $item,
										'class' => $class,
										'shadow' => $shadow
								]);
						echo '</div>';
					}
				?>
			</div>
    </div>
	<?php endif; ?>

	<?php get_template_part('template-parts/best', 'seller') ?>

	<div class="single-product-breadcrumbs-bottom">
		<?php
			// Output the breadcrumbs
			woocommerce_breadcrumb();
		 ?>
	</div>
</div>

<?php
/**
 * Modal Gallery.
 */
get_template_part('template-parts/product/modal');
get_template_part('template-parts/product/sizes');
?>

<?php do_action( 'woocommerce_after_single_product' ); ?>
