<div class="header-mini-cart">
  <div class="header-mini-cart-close">
    <svg class='icon-close' width='24' height='24'>
      <use href='<?= assets('img/sprite.svg#icon-close') ?>'></use>
    </svg>
  </div>

  <div class="header-mini-cart-content">
    <?php woocommerce_mini_cart( [ 'list_class' => 'header-mini-cart-list' ] ); ?>
  </div>
</div>