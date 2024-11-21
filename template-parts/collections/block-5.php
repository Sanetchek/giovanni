<section id="block5" class="block5">
  <div class="main-wrap">
    <?php
      $block5_post = get_field('block5_post');

      /**
       * 'item' -> Group of ACF fields (names: image, title, description, link_label, link)
       * 'class' -> (string) first/second (default: first)
       * 'shadow' -> (number) 0/1 - it put shadow on left or right (default: 1)
       */
      get_template_part('template-parts/main', 'post', ['item' => $block5_post]);
    ?>

    <div class="container">
      <div class="block1-content">
        <?php if (get_field('block5_link')) : ?>
        <div class="button-animation-effect">
          <a href="<?= get_field('block5_link') ?>" class="btn btn-no-border btn-hover">
            <div class="animation-label-container">
              <p class="cta-text"><?= get_field('block5_link_label') ?></p>
              <p aria-hidden="true" class="cta-text animation-label-text"><?= get_field('block5_link_label') ?></p>
            </div>
          </a>
        </div>
        <?php endif ?>
      </div>

      <?php $productArray = get_field('block5_products') ?>
      <?php if ($productArray) : ?>
        <?php get_template_part('template-parts/product', 'slider', ['productArray' => $productArray]) ?>
      <?php endif ?>
    </div>
  </div>
</section>