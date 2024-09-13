<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package giovanni
 */

get_header();
?>
	<main id="primary" class="site-main archive-page">
		<?php if ( have_posts() ) : ?>

			<header class="page-header">
				<div class="container">
					<?php
					the_archive_title( '<h1 class="page-list-title">', '</h1>' );
					the_archive_description( '<div class="archive-description">', '</div>' );
					?>
				</div>
			</header><!-- .page-header -->

			<div class="article-page-list article-wrap">
				<ul class="page-list">
					<?php
						/* Start the Loop */
						while ( have_posts() ) :
							the_post();

							/*
							* Include the Post-Type-specific template for the content.
							* If you want to override this in a child theme, then include a file
							* called content-___.php (where ___ is the Post Type name) and that will be used instead.
							*/
							get_template_part('template-parts/page', 'card', ['id' => get_the_ID()]);

						endwhile; ?>
				</ul>
			</div>
		<?php
			the_posts_navigation();

		else :

			get_template_part( 'template-parts/content', 'none' );

		endif;
		?>
	</main><!-- #main -->

<?php
get_footer();
