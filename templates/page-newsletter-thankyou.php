<?php
/**
 * Template Name: Thank You Page for Newsletter
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package giovanni
 */

get_header();
?>

	<main id="primary" class="site-main">

		<?php
		while ( have_posts() ) :
			the_post();

			get_template_part( 'template-parts/content', 'page' );

		endwhile; // End of the loop.
		?>

		<?php $link = get_field('link') ?>
		<?php if ($link) : ?>
			<a href="<?= $link ?>" class="button button-black button-hover white btn-newslatter"><?= get_field('label') ?></a>
		<?php endif ?>

	</main><!-- #main -->

<?php
get_footer();
