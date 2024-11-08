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

defined( 'ABSPATH' ) || exit; ?>

<main id="primary" class="site-main">
  <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <header class="entry-header">
      <?php
        echo '<div class="my-account-header">';
          if ( is_user_logged_in() ) {
            $current_user = wp_get_current_user();
            echo '<p>' . esc_html( $current_user->display_name ) . '</p>'; // Display user name
          }
          the_title( '<h1 class="entry-title">', '</h1>' );
        echo '</div>';
      ?>
    </header><!-- .entry-header -->

    <div class="container">

      <div class="entry-content">
        <div class="profile-container">
          <div class="woocommerce">
            <?php if (is_user_logged_in()) : ?>
              <?php do_action( 'woocommerce_account_navigation' ); ?>
            <?php else : ?>
              <div class="profile-navigation">

              </div>
            <?php endif ?>

            <div class="profile-content">

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
                    <p class="message-primary"><?= __('עדיין אין לך פריטים שמורים', 'giovanni') ?></p>
                    <p class="message-secondary"><?= __('אתה צריך קצת השראה? יש לנו כמה הצעות שתאהבו.', 'giovanni') ?></p>
                  </li>
                <?php endif ?>
              </ul>

            </div>
          </div>
        </div>
      </div>
    </div>
  </article>
</main><!-- #main -->

<?php get_footer(); ?>