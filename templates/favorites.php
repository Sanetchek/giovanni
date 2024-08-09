<?php
/**
 * Template Name: Favorites
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

<main id="primary" class="site-main favoritespage">

  <ul id="product-list" class="products products-list products-container">
    <?php $favorites = query_user_liked_posts(); ?>

    <?php if ($favorites['query']) : ?>
      <?php foreach ($favorites['query'] as $item) : ?>

        <li class="product-card">

          <?php get_template_part('template-parts/product', 'card', ['product_id' => $item->ID]); ?>

        </li>

      <?php endforeach ?>
    <?php else : ?>
      <li class="no-favorites-message">
        <p><?= __('אין מוצרים במועדפים שלך', 'giovanni') ?></p>
      </li>
    <?php endif ?>
  </ul>

</main><!-- #main -->

<?php get_footer(); ?>