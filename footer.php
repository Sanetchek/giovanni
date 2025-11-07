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
					<?php $pinterest = get_field('pinterest', 'option'); ?>
					<?php if ($pinterest) : ?>
						<a rel="noreferrer" href="<?php echo esc_html($pinterest)?>" target="_blank" class="icon-pinterest" aria-label="pinterest"></a>
					<?php endif ?>

					<?php $youtube = get_field('youtube', 'option'); ?>
					<?php if ($youtube) : ?>
						<a rel="noreferrer" href="<?php echo esc_html($youtube)?>" target="_blank" class="icon-yt" aria-label="youtube"></a>
					<?php endif ?>

					<?php $instagram = get_field('instagram', 'option'); ?>
					<?php if ($instagram) : ?>
						<a rel="noreferrer" href="<?php echo esc_html($instagram)?>" target="_blank" class="icon-is" aria-label="istangram"></a>
					<?php endif ?>

					<?php $facebook = get_field('facebook', 'option'); ?>
					<?php if ($facebook) : ?>
						<a rel="noreferrer" href="<?php echo esc_html($facebook)?>" target="_blank" class="icon-fb" aria-label="facebook"></a>
					<?php endif ?>

					<?php $tiktok = get_field('tiktok', 'option'); ?>
					<?php if ($tiktok) : ?>
						<a rel="noreferrer" href="<?php echo esc_html($tiktok)?>" target="_blank" class="icon-tiktok" aria-label="tiktok"></a>
					<?php endif ?>
				</div>

				<p class="copyrights">
					Built by <a href="https://webeffect.co.il/" style="text-decoration: underline;">Webeffect</a> | Copyright © 2025 Tilion Studio LTD
				</p>

				<hr style="border:none; border-top:1px solid #eee; margin:15px auto; width:80%;">

				<p style="text-align:center; font-size:13px; color:#444; margin:4px 0; line-height:1.5; direction:ltr;">
					Giovanni Raspini Israel – Operated by <strong style="color:#000;">Tilion Studio LTD</strong><br>
					Lev ha-Ir St 2, Azrieli Mall, Modi'in, Israel 7177806 | ☎ ‎054-683-0101 | ✉
					<a href="mailto:customercare@giovanniraspini-shop.co.il" style="color:#000; text-decoration:underline;">
						customercare@giovanniraspini-shop.co.il
					</a>
				</p>

				<p style="text-align:center; font-size:13px; color:#444; margin:2px 0 8px; line-height:1.5; direction:rtl; font-family:'Assistant',sans-serif;">
					<strong style="color:#000;">Tilion Studio LTD</strong>, המפעילה את מותג <strong style="color:#000;">Imeretti</strong> – המשווק הרשמי של תכשיטי <strong style="color:#000;">Giovanni Raspini</strong> בישראל
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



  <?php
    // Modal mini cart
    get_template_part('template-parts/modal/cart', 'modal');

    // Modal Thankyou
    get_template_part('template-parts/modal/thankyou', 'modal');

    // Modal product
    get_template_part('template-parts/modal/product', 'modal');

    // Login search
    if (!is_user_logged_in()) {
      get_template_part('template-parts/modal/login', 'modal');
    }

	// Modal subscription
	get_template_part('template-parts/modal/subscription', 'modal');
  ?>

	<div id="modal-subscribe-overlay"></div>
	<div id="modal-overlay" class="modal-overlay"></div>
	<div id="modal-menu-overlay" class="modal-menu-overlay"></div>

	<button id="scrollToTop">▲</button>

<?php wp_footer(); ?>
<script src="https://player.vimeo.com/api/player.js"></script>
</body>
</html>
