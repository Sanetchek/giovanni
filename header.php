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
	<?php wp_body_open(); ?>

	<div id="page" class="site">

		<header id="masthead">
			<div class="site-header">

				<?php get_template_part('template-parts/header/bar') ?>

				<div class="main-wrap header-wrap">
					<div class="header-top">

						<?php show_logo('header_logo_image', 'header_logo_name') ?>

						<div class="user-actions">
							<?php get_template_part('template-parts/header/user-actions') ?>
						</div>

					</div>

					<nav class="main-navigation">
						<div class="page-mob">
							<div class="main-navigation-mob">
								<?php show_logo('header_logo_image', 'header_logo_name') ?>
								<?php show_burger(true); ?>
							</div>
						</div>

						<?php
							wp_nav_menu([
								'theme_location' => 'menu-1',
								'menu_id'        => 'primary-menu',
								'container'			 => ''
							]);
						?>

					</nav><!-- #site-navigation -->
				</div>

				<?php get_template_part('template-parts/mega-menu/collections') ?>
				<?php get_template_part('template-parts/mega-menu/jewellry') ?>
				<?php get_template_part('template-parts/mega-menu/charms') ?>
				<?php get_template_part('template-parts/mega-menu/gifts') ?>
				<?php get_template_part('template-parts/mega-menu/about') ?>
			</div><!-- .site-header -->
		</header><!-- #masthead -->

