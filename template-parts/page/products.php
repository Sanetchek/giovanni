<?php $productArray = $args['productArray'] ?>

<div class="article-collections collections-container">
  <ul class="products-list">
    <?php
    foreach ($productArray as $product_id) : ?>
    <li class="product-card" data-id="<?= $product_id ?>">
      <?php $args = ['product_id' => $product_id]; ?>
      <?php get_template_part( 'template-parts/product', 'card', $args ) ?>
    </li>
    <?php
    endforeach;
    ?>
  </ul>
</div>