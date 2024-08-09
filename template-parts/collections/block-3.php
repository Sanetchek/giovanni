<section id="block3" class="block3">
  <div class="main-wrap">
    <?php
      $block3_post = get_field('block3_post');

      /**
       * 'item' -> Group of ACF fields (names: image, title, description, link_label, link)
       * 'class' -> (string) first/second (default: first)
       * 'shadow' -> (number) 0/1 - it put shadow on left or right (default: 1)
       */
      get_template_part('template-parts/main', 'post', ['item' => $block3_post, 'class' => 'second', 'shadow' => 0]);
    ?>

    <div class="container">
      <div class="block1-content">
        <?php if (get_field('block3_link')) : ?>
          <a href="<?= get_field('block3_link') ?>" class="btn btn-no-border btn-hover"><?= get_field('block3_link_label') ?></a>
        <?php endif ?>
      </div>

      <?php $productArray = get_field('block3_products') ?>
      <?php if ($productArray) : ?>
        <?php get_template_part('template-parts/product', 'slider', ['productArray' => $productArray]) ?>
      <?php endif ?>
    </div>
  </div>
</section>