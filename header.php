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

				<?php $bar = get_field('header_bar', 'option'); ?>
				<?php if ($bar) : ?>

					<div class="header-bar">
						<?= __($bar, 'giovanni'); ?>
					</div>

				<?php endif ?>

				<div class="container header-wrap">
					<div class="header-top">

						<div id="burger-menu" class="burger-menu"></div>

						<a href="/" class="site-branding" rel="home" aria-current="page" tabindex="0">

							<?php $logo = get_field('header_logo_image', 'option'); ?>

							<?php if ($logo['image']) : ?>

								<?php show_image( $logo['image'], [300, 50], ['class' => 'site-logo'] ); ?>

							<?php else : ?>
								<?php $logo = get_field('header_logo_name', 'option'); ?>

								<?php if ($logo['name']) : ?>
									<?= $logo['name'] ?>
								<?php endif ?>

								<?php if ($logo['label']) : ?>
									<span class="branding-label"><?= $logo['label'] ?></span>
								<?php endif ?>
							<?php endif; ?>

						</a><!-- .site-branding -->

						<div class="user-actions">
							<?php get_template_part('template-parts/header/user-actions') ?>
						</div>

					</div>

					<nav class="main-navigation">

						<?php
							wp_nav_menu([
								'theme_location' => 'menu-1',
								'menu_id'        => 'primary-menu',
								'container'			 => ''
							]);
						?>

					</nav><!-- #site-navigation -->
				</div>

			</div>
		</header><!-- #masthead -->
