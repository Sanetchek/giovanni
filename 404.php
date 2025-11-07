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
		<div class="error-404-image">
			<?php
				$bg = get_field('error_image', 'option');
				$bg_mob = get_field('error_image_mob', 'option');

				show_image($bg, '1440-400', ['class' => 'page-desk']);
				$bg_mob = $bg_mob ? $bg_mob : $bg;
				show_image($bg_mob, '768-400', ['class' => 'page-mob']);
			?>
		</div>

		<div class="container">
			<section class="error-404 not-found">

				<header class="page-header">

					<h1 class="page-title"><?= get_field('error_title', 'option') ?></h1>
				</header><!-- .page-header -->

				<div class="page-content">
					<p dir="ltr"><?= get_field('error_description', 'option') ?></p>

					<?php if (get_field('error_link', 'option')) : ?>
						<p class="error-404-link"><a href="<?= get_field('error_link', 'option') ?>" class="button button-hover error-link-button"><?= get_field('error_link_label', 'option') ?></a></p>
					<?php endif ?>

				</div><!-- .page-content -->

			</section><!-- .error-404 -->
		</div><!-- .container -->

	</main><!-- #main -->

<?php
get_footer();
