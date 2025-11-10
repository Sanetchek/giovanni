<section id="collections" class="collections">
  <div class="collections-image main-wrap">
    <?php
      $img_mob = get_field('new_col_image_mob');
      $img = get_field('new_col_image');

      $data = [
        'thumb' => [1920, 920],
        'max' => [
          '768' => [768, 0],
        ],
        'args' => [
          'class' => 'collections-bg',
        ],
      ];
      echo liteimage( $img, $data, $img_mob );
    ?>
  </div>

  <div class="collections-content">
    <div class="collections-body">
      <div class="collections-container">
        <h2><?= get_field('new_col_title') ?></h2>
        <p><?= get_field('new_col_subtitle') ?></p>

        <?php if (get_field('new_col_link')) : ?>
          <div class="button-animation-effect">
            <a href="<?= get_field('new_col_link') ?>" class="btn btn-hover">
              <div class="animation-label-container">
                <p class="cta-text"><?= get_field('new_col_link_label') ?></p>
                <p aria-hidden="true" class="cta-text animation-label-text"><?= get_field('new_col_link_label') ?></p>
              </div>
            </a>
          </div>
        <?php endif ?>
      </div>

      <ul class="products-list">
        <?php
        $products = get_field('new_col_products');

        foreach ($products as $product_id) : ?>
          <li class="product-card" data-id="<?= $product_id ?>">
            <?php $args = ['product_id' => $product_id]; ?>
            <?php get_template_part( 'template-parts/product', 'card', $args ) ?>
          </li>
          <?php
        endforeach;
        ?>
      </ul>
    </div>
  </div>
</section>