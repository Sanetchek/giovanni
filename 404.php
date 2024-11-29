<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package giovanni
 */

get_header();
?>

	<main id="primary" class="site-main">
		<div class="container">
			<section class="error-404 not-found">

				<header class="page-header">
					<h1 class="page-title"><?php esc_html_e( 'אופס! לא ניתן למצוא את הדף הזה.', 'giovanni' ); ?></h1>
				</header><!-- .page-header -->

				<div class="page-content">
					<p dir="ltr"><?php esc_html_e( 'נראה ששום דבר לא נמצא במקום הזה. אולי נסה אחד מהקישורים למטה או חיפוש?', 'giovanni' ); ?></p>

						<?php get_search_form(); ?>

				</div><!-- .page-content -->

			</section><!-- .error-404 -->
		</div><!-- .container -->

	</main><!-- #main -->

<?php
get_footer();
