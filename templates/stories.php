<?php
/**
 * Template Name: Stories
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

<main id="primary" class="site-main collectionspage page-container">

  <h1 class="title-page-stories"><?= get_the_title(); ?></h1>

  <?php get_template_part('template-parts/stories/stories', 'post') ?>

  <?php

  get_template_part('template-parts/stories/tabs', '', ['title' => get_field('tab_title')]);
  ?>

</main><!-- #main -->

<?php get_footer(); ?>