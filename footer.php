<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package giovanni
 */

?>

	<footer id="colophon" class="site-footer">
		<div class="footer-subscribe">
			<div class="subscribe-container">
				<?php
					$s_title = get_field('subscribe_title', 'option');
					$s_desc = get_field('subscribe_description', 'option');
					$s_subform = get_field('subscribe_form_shortcode', 'option');
				?>

				<?php if ($s_title) : ?>
					<h3 class="subscribe-title"><?= $s_title ?></h3>
				<?php endif ?>

				<?php if ($s_desc) : ?>
					<p class="subscribe-text"><?= $s_desc ?></p>
				<?php endif ?>

				<?php if ($s_subform) : ?>
					<div class="subscribe-form"><?= do_shortcode( $s_subform ) ?></div>
				<?php endif ?>
			</div>
		</div>

		<div class="container">

			<div class="site-info container">

				<div class="footer-four-menu">
					<?php
					if ( is_active_sidebar( 'footer-1' ) ) {
						dynamic_sidebar( 'footer-1' );
					}
					if ( is_active_sidebar( 'footer-2' ) ) {
						dynamic_sidebar( 'footer-2' );
					}
					if ( is_active_sidebar( 'footer-3' ) ) {
						dynamic_sidebar( 'footer-3' );
					}
					if ( is_active_sidebar( 'footer-4' ) ) {
						dynamic_sidebar( 'footer-4' );
					}
					?>
				</div>

				<div class="footer-socials">
					<a rel="noreferrer" href="https://www.facebook.com/giovanniraspini" target="_blank" class="icon-fb" aria-label="facebook"></a>
					<a rel="noreferrer" href="https://www.instagram.com/giovanniraspini" target="_blank" class="icon-is" aria-label="istangram"></a>
					<a rel="noreferrer" href="https://www.youtube.com/giovanniraspinigioielli" target="_blank" class="icon-yt" aria-label="youtube"></a>
					<a rel="noreferrer" href="https://www.pinterest.it/giovanni_raspini/" target="_blank" class="icon-pinterest" aria-label="pinterest"></a>
					<a rel="noreferrer" href="https://www.tiktok.com/@giovanni_raspini" target="_blank" class="icon-tiktok" aria-label="tiktok"></a>
				</div>

				<?php $copyrights = get_field('copyrights', 'option'); ?>

				<?php if ($copyrights) : ?>
					<p class="copyrights">
						<?= $copyrights ?>
					</p>
				<?php endif ?>

				<div class="footer-menu-bottom">
					<?php
						wp_nav_menu([
							'theme_location' => 'footer-2',
							'menu_id'        => 'footer-menu-bottom',
							'container'			 => ''
						]);
					?>
				</div>
			</div><!-- .site-info -->
		</div>
	</footer><!-- #colophon -->

<?php wp_footer(); ?>

</body>
</html>
