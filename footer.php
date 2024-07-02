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

				<div class="footer-menu">
					<?php
						wp_nav_menu([
							'theme_location' => 'footer',
							'menu_id'        => 'footer-menu',
							'container'			 => ''
						]);
					?>
				</div>

				<ul class="footer-social">
					<li class="social-item">
						<a href="#">
							<svg class='icon-pinterest' width='24' height='24'>
								<use href='<?= assets('img/sprite.svg#icon-pinterest') ?>'></use>
							</svg>
						</a>
					</li>
					<li class="social-item">
						<a href="#">
							<svg class='icon-youtube' width='24' height='24'>
								<use href='<?= assets('img/sprite.svg#icon-youtube') ?>'></use>
							</svg>
						</a>
					</li>
					<li class="social-item">
						<a href="#">
							<svg class='icon-instagram' width='24' height='24'>
								<use href='<?= assets('img/sprite.svg#icon-instagram') ?>'></use>
							</svg>
						</a>
					</li>
					<li class="social-item">
						<a href="#">
							<svg class='icon-facebook' width='24' height='24'>
								<use href='<?= assets('img/sprite.svg#icon-facebook') ?>'></use>
							</svg>
						</a>
					</li>
				</ul>

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
