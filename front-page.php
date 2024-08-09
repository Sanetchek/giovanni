<?php
/**
 * The front-page template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package giovanni
 */

get_header();
?>

<?php get_template_part('template-parts/frontpage/hero'); ?>

<main id="primary" class="site-main frontpage page-container">

  <?php get_template_part('template-parts/frontpage/taxonomies'); ?>
  <?php get_template_part('template-parts/frontpage/collections'); ?>
  <?php get_template_part('template-parts/frontpage/home-posts'); ?>
  <?php get_template_part('template-parts/frontpage/exhibition'); ?>
  <?php get_template_part('template-parts/frontpage/popular'); ?>

</main><!-- #main -->

<?php
get_footer();