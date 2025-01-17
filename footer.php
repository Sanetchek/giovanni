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

				<p class="copyrights">
					Built by <a href="https://webeffect.co.il/">Webeffect</a> | Copyright © 2025 Tilion Studio LTD
				</p>

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

	<div id="modal_subscription" class="modal-subscription">
		<span class="modal-close"></span>
		<div class="modal-content">
			<div class="modal-side modal-side-image">
				<?php show_image(get_field('subscribe_image', 'option'), '800-full', ['class'=> 'modal-subscribe-image']) ?>
			</div>

			<div class="modal-side modal-side-content">
				<h2 class="modal-subscribe-title"><?= get_field('subscribe_title', 'option') ?></h2>
				<p class="modal-subscribe-text"><?= get_field('subscribe_description', 'option') ?></p>

				<div class="modal-subscribe-form">
					<?php echo do_shortcode( get_field('subscibe_form_short_code', 'option') ) ?>
				</div>
			</div>
		</div>
	</div>



  <?php
    // Modal mini cart
    get_template_part('template-parts/modal/cart', 'modal');

    // Modal product
    get_template_part('template-parts/modal/product', 'modal');

    // Modal search
    // get_template_part('template-parts/modal/search', 'modal');

    // Login search
    if (!is_user_logged_in()) {
      get_template_part('template-parts/modal/login', 'modal');
    }
  ?>

	<div id="modal-subscribe-overlay"></div>
	<div id="modal-overlay" class="modal-overlay"></div>
	<div id="modal-menu-overlay" class="modal-menu-overlay"></div>

<?php wp_footer(); ?>

</body>
</html>
