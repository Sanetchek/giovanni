<section id="popular" class="popular">
  <div class="container">
    <h2 class="popular-title"><?= get_field('pop_title') ?></h2>

    <?php $productArray = get_field('pop_products') ?>
  <?php if ($productArray) : ?>
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

    <div class="popular-product">
      <?php foreach ($productArray as $product_id) :
        $product = wc_get_product($product_id); ?>
        <div class="product-item">
          <a href="<?php echo get_permalink($product_id); ?>">
            <?php echo get_the_post_thumbnail($product_id, '372x372'); ?>
            <h3><?php echo $product->get_name(); ?></h3>
            <p class="popular-price"><?php echo $product->get_price_html(); ?></p>
          </a>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif ?>
  </div>
</section>