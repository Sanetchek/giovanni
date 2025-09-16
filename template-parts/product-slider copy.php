<?php $productArray = $args['productArray'] ?>

<div class="slider-container">
  <div class="swiper-container">
    <div class="popular-nav">
      <div class="swiper-arrow swiper-prev">
        <span><svg class='icon-arrow-left' width='36' height='6'><use href='<?= assets('img/sprite.svg#icon-arrow-left') ?>'></use></svg></span>
      </div>
      <div class="nav-indicator">
        <span class="slide-indicator">1</span>/<span class="slide-total">2</span>
      </div>
      <div class="swiper-arrow swiper-next">
        <span><svg class='icon-arrow-right' width='36' height='6'><use href='<?= assets('img/sprite.svg#icon-arrow-right') ?>'></use></svg></span>
      </div>
    </div>

    <div class="swiper-wrapper">
      <?php for ($i = 0; $i < 3; $i++) : ?>
        <?php foreach ($productArray as $product_id) :
          $product = wc_get_product($product_id); ?>
          <div class="swiper-slide swiper-product-item">
            <?php $args = ['product_id' => $product_id]; ?>
            <?php get_template_part( 'template-parts/product', 'card-slide', $args ) ?>
          </div>
        <?php endforeach; ?>
      <?php endfor ?>
    </div>
    <!-- Add Pagination -->
    <div class="swiper-pagination"></div>
  </div>
</div>
