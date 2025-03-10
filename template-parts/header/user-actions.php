<div class="user-actions-side">
  <span class="header-action header-search">
    <svg class='icon-search' width='24' height='24'>
      <use href='<?= assets('img/sprite.svg#icon-search') ?>'></use>
    </svg>

    <?php get_template_part('template-parts/header/searchform') ?>
  </span>

  <?php
    $login = !is_user_logged_in() ? 'show-login-form' : '';
    $url = !is_user_logged_in() ? '#' : '/my-account';
  ?>
  <a href="<?= $url ?>" class="header-login-link <?= $login ?>">
    <span class="header-action header-user">
      <svg class='icon-user' width='24' height='24'>
        <use href='<?= assets('img/sprite.svg#icon-user') ?>'></use>
      </svg>
    </span>
  </a>

  <a href="/favorites" class="header-action header-like">
    <span class="header-count">
      <?php
        $likes = query_user_liked_posts();
        echo $likes['count'];
      ?>
    </span>
    <svg class='icon-like' width='24' height='24'>
      <use href='<?= assets('img/sprite.svg#icon-like') ?>'></use>
    </svg>
  </a>

  <a href="<?php echo wc_get_cart_url() ?>" class="header-action header-cart">
    <span class="header-count header-cart-count"><?php echo WC()->cart->get_cart_contents_count() ?></span>
    <svg class='icon-bag' width='24' height='24'>
      <use href='<?= assets('img/sprite.svg#icon-bag') ?>'></use>
    </svg>
  </a>
</div>

<div class="page-mob">
  <div class="header-actions-burger">
    <?php show_burger(); ?>
  </div>
</div>
