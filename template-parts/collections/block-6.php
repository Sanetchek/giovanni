<section id="block6" class="block6">
  <div class="main-wrap">
    <div class="container">
      <div class="block1-content">
      <h2><?= get_field('block6_title') ?></h2>
      <p><?= get_field('block6_description') ?></p>
      </div>
      <div class="block1-content">
        <?php if (get_field('block6_link')) : ?>
        <div class="button-animation-effect">
          <a href="<?= get_field('block6_link') ?>" class="btn btn-no-border btn-hover">
            <div class="animation-label-container">
              <p class="cta-text"><?= get_field('block6_link_label') ?></p>
              <p aria-hidden="true" class="cta-text animation-label-text"><?= get_field('block6_link_label') ?></p>
            </div>
          </a>
        </div>
        <?php endif ?>
      </div>
    </div>
    <?php
      $block6_post = get_field('block6_post');

      /**
       * 'item' -> Group of ACF fields (names: image, title, description, link_label, link)
       * 'class' -> (string) first/second (default: first)
       * 'shadow' -> (number) 0/1 - it put shadow on left or right (default: 1)
       */
      get_template_part('template-parts/main', 'post', ['item' => $block6_post, 'class' => 'second', 'shadow' => 0]);
    ?>

    <div class="container">
      

      <?php $productArray = get_field('block6_products') ?>
      <?php if ($productArray) : ?>
        <?php get_template_part('template-parts/product', 'slider', ['productArray' => $productArray]) ?>
      <?php endif ?>
    </div>
  </div>
</section>