<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package giovanni
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<!-- Preloader Start -->
	<section>
		<div id="preloader">
			<div id="ctn-preloader" class="ctn-preloader">
				<div class="animation-preloader">
					<div class="icon">
						<?php if (theme_logo()) : ?>
							<?= theme_logo(); // you can set logo in Appearance -> Customize -> Site Identity -> Logo field ?>
						<?php else : ?>
							<img src="<?= assets('img/logo.svg') ?>" alt="logo" width="38">
						<?php endif; ?>
					</div>
					<div class="txt-loading mt-2">
						<?php
							$text = strtoupper(get_bloginfo('name'));
							$name = str_split( $text );
						?>
						<?php foreach ($name as $letter) : ?>
							<span data-text-preloader="<?= $letter ?>" class="letters-loading">
								<?= $letter ?>
							</span>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- Preloader End -->

	<?php wp_body_open(); ?>
	<div id="page" class="site">

		<header id="masthead">
			<div class="site-header">
				<div class="container header__wrap">
					<?php if (theme_logo()) : ?>
						<?= theme_logo(); // you can set logo in Appearance -> Customize -> Site Identity -> Logo field ?>
					<?php else : ?>
						<a href="/" class="site-branding">
							<img src="<?= assets('img/logo.svg') ?>" alt="logo" width="38">
						</a><!-- .site-branding -->
					<?php endif; ?>

					<nav class="main-navigation">
						<?php
						wp_nav_menu(
							array(
								'theme_location' => 'menu-1',
								'menu_id'        => 'primary-menu',
							)
						);
						?>
					</nav><!-- #site-navigation -->

					<div id="burger-menu" class="burger-menu">
						<div class="bar1"></div>
						<div class="bar2"></div>
						<div class="bar3"></div>
					</div>
				</div>

			</div>
		</header><!-- #masthead -->
