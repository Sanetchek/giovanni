<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package giovanni
 */

get_header();
?>

	<main id="primary" class="site-main search-page">

		<div class="container">
			<header class="page-header">
				<?php get_search_form(); ?>

				<h1 class="page-title main-title search-title">
					<?php
					/* translators: %s: search query. */
					printf( esc_html__( 'תוצאות חיפוש עבור: %s', 'giovanni' ), '"<span> ' . get_search_query() . ' </span>"' );
					?>
				</h1>
			</header><!-- .page-header -->

			<ul class="search-modal-content">
				<?php
					if (have_posts()) {
						$count = 0;

						while (have_posts()) {
							the_post();

							get_template_part('template-parts/search', 'card', ['id' => get_the_ID(), 'count' => $count]);
							$count++;
						}
					} else {
						echo '<li class="results-not-found">' . __('לא נמצאו תוצאות', 'giovanni') . '</li>';
					}
				?>
			</ul>
		</div>

	</main><!-- #main -->

<?php
get_footer();
