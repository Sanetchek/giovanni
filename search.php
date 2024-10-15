<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package giovanni
 */

get_header();

// Prepare WooCommerce search query
$args = array(
	'post_type'      => 'product',
	's'              => get_search_query(),
	'posts_per_page' => -1,
);

$query = new WP_Query($args);

// Get the total number of posts
$post_count = $query->found_posts;
?>

	<main id="primary" class="site-main search-page">
		<div class="container">
			<header class="page-header">
				<h1 class="page-title main-title search-title">
					<?= __('תוצאות חיפוש', 'giovanni'); ?>
				</h1>
				<p class="search-result-count">
					<?php
						/* translators: %s: search query. */
						echo $post_count . __( ' תוצאות עבור ', 'giovanni' ) . '<span class="search-keywords">' . get_search_query() . '</span>';
					?>
				</p>
			</header><!-- .page-header -->
		</div>

		<div id="products-filter" class="products-filter">
			<div class="filter-mob">
				<button type="button" class="btn btn-no-border open-filter">
					<span class="filter-label"><?= __('מסנן', 'giovanni') ?></span>
					<svg class='icon-filter' width='24' height='24'>
						<use href='<?= assets('img/sprite.svg#icon-filter') ?>'></use>
					</svg>
				</button>
			</div>

			<div class="products-container">
				<?php display_product_filters(get_search_query()); ?>
			</div>
		</div>

		<ul id="product-list" class="search-modal-content">
			<?php
				if ($query->have_posts()) {
					while ($query->have_posts()) {
						$query->the_post();

						wc_get_template_part( 'content', 'product' );
					}
				} else {
					echo '<li class="results-not-found">' . __('לא נמצאו תוצאות', 'giovanni') . '</li>';
				}
			?>
		</ul>

	</main><!-- #main -->

<?php
get_footer();
