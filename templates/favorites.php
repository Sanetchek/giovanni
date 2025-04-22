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
          <?php $reverse = !is_user_logged_in() ? 'profile-container--reverse' : '' ?>
          <div class="woocommerce <?php echo $reverse ?>">
            <?php if (is_user_logged_in()) : ?>
              <?php do_action( 'woocommerce_account_navigation' ); ?>
            <?php else : ?>
              <nav class="profile-navigation" aria-label="<?php esc_html_e( 'Account pages', 'woocommerce' ); ?>">
                <?php $group = get_field('section_not_registered_users') ?>
                <?php if ($group) : ?>
                  <div class="my-account_sidebar--menu">
                    <h3 class="sidebar-empty-title"><?= $group['title'] ?></h3>
                    <?php if ($group['list']) : ?>
                      <ul class="wishlist-create-account--description-empty">
                        <?php foreach ($group['list'] as $item) : ?>
                          <li><div class="disc"></div><p class="disc-list"><?= $item['text'] ?></p></li>
                        <?php endforeach ?>
                      </ul>
                    <?php endif ?>

                    <a class="button-wishlist button-hover" href="/my-account" role="button" tabindex="-1"><?= $group['link_label'] ?></a>
                  </div>
                <?php endif ?>
              </nav>
            <?php endif ?>

            <div class="profile-content">

              <?php $favorites = query_user_liked_posts(); ?>
              <ul id="product-list" class="products products-list products-container">

                <?php if ($favorites['query']) : ?>
                  <?php foreach ($favorites['query'] as $item) : ?>

                    <li class="product-card">

                      <?php get_template_part('template-parts/product', 'card', ['product_id' => $item->ID]); ?>

                    </li>

                  <?php endforeach ?>
                <?php endif ?>
              </ul>

              <div class="no-favorites-message" <?= $favorites['query'] ? 'style="display: none;"' : '' ?>>
                <p class="message-primary"><?= __('עדיין אין לך פריטים שמורים', 'giovanni') ?></p>
                <p class="message-secondary"><?= __('אתה צריך קצת השראה? יש לנו כמה הצעות שתאהבו.', 'giovanni') ?></p>
              </div>

            </div>
          </div>
        </div>
      </div>
    </div>
  </article>
</main><!-- #main -->

<?php get_footer(); ?>