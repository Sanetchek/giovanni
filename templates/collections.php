<?php
/**
 * Template Name: Collections
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

<?php get_template_part('template-parts/collections/hero'); ?>

<main id="primary" class="site-main collectionspage page-container">

  <?php get_template_part('template-parts/collections/block', '1'); ?>
  <?php get_template_part('template-parts/collections/block', '2'); ?>
  <?php get_template_part('template-parts/collections/block', '3'); ?>

</main><!-- #main -->

<?php get_template_part('template-parts/collections/block', '4'); ?>

<?php get_footer(); ?>