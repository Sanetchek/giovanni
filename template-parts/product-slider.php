<?php $productArray = $args['productArray'] ?>

<div class="slider-container">
  <div class="popular-nav">
    <div class="slick-prev slick-arrow"><span>
        <svg class='icon-arrow-left' width='36' height='6'>
          <use href='<?= assets('img/sprite.svg#icon-arrow-left') ?>'></use>
        </svg>
      </span></div>
    <div class="nav-indicator">
      <span class="slide-indicator">1</span>/<span class="slide-total">2</span>
    </div>
    <div class="slick-next slick-arrow"><span>
        <svg class='icon-arrow-right' width='36' height='6'>
          <use href='<?= assets('img/sprite.svg#icon-arrow-right') ?>'></use>
        </svg>
      </span></div>
  </div>

  <?php if ($productArray) : ?>
    <div class="popular-product">
      <?php foreach ($productArray as $product_id) :
        $product = wc_get_product($product_id); ?>
        <div class="product-item">
          <?php $args = ['product_id' => $product_id]; ?>
          <?php get_template_part( 'template-parts/product', 'card-slide', $args ) ?>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif ?>
</div>