<div class="user-actions-side">
  <div class="header-action header-search">
    <svg class='icon-search' width='24' height='24'>
      <use href='<?= assets('img/sprite.svg#icon-search') ?>'></use>
    </svg>

    <?php get_template_part('template-parts/header/searchform') ?>
  </div>

  <?php
    $login = !is_user_logged_in() ? 'show-login-form' : '';
    $url = !is_user_logged_in() ? '#' : '/my-account';
    $login_label = !is_user_logged_in() ? __('Login or create account', 'giovanni') : __('My account', 'giovanni');
  ?>
  <a href="<?= $url ?>" class="header-login-link <?= $login ?>" aria-label="<?php echo esc_attr($login_label); ?>">
    <span class="header-action header-user">
      <svg class='icon-user' width='24' height='24' aria-hidden="true">
        <use href='<?= assets('img/sprite.svg#icon-user') ?>'></use>
      </svg>
    </span>
  </a>

  <?php
    $likes = query_user_liked_posts();
    $likes_count = $likes['count'];
    // translators: %s: Number of favorite items
    $favorites_label = sprintf(__('Favorites (%s items)', 'giovanni'), $likes_count);
  ?>
  <a href="/favorites" class="header-action header-like" aria-label="<?php echo esc_attr($favorites_label); ?>">
    <span class="header-count" aria-hidden="true">
      <?php echo $likes_count; ?>
    </span>
    <svg class='icon-like' width='24' height='24' aria-hidden="true">
      <use href='<?= assets('img/sprite.svg#icon-like') ?>'></use>
    </svg>
  </a>

  <?php
    $cart_count = WC()->cart->get_cart_contents_count();
    // translators: %s: Number of items in shopping cart
    $cart_label = sprintf(_n('Shopping cart (%s item)', 'Shopping cart (%s items)', $cart_count, 'giovanni'), $cart_count);
  ?>
  <a href="<?php echo wc_get_cart_url() ?>" class="header-action header-cart" aria-label="<?php echo esc_attr($cart_label); ?>">
    <span class="header-count header-cart-count" aria-hidden="true"><?php echo $cart_count ?></span>
    <svg class='icon-bag' width='24' height='24' aria-hidden="true">
      <use href='<?= assets('img/sprite.svg#icon-bag') ?>'></use>
    </svg>
  </a>
</div>

<div class="page-mob">
  <div class="header-actions-burger">
    <?php show_burger(); ?>
  </div>
</div>
