<section id="block1" class="block1">
  <div class="container">
    <div class="block1-content">
      <h2><?= get_field('block1_title') ?></h2>
      <p><?= get_field('block1_description') ?></p>

      <?php if (get_field('block1_link')) : ?>
        <div class="button-animation-effect">
          <a href="<?= get_field('block1_link') ?>" class="btn btn-no-border btn-hover">
            <div class="animation-label-container">
              <p class="cta-text"><?= get_field('block1_link_label') ?></p>
              <p aria-hidden="true" class="cta-text animation-label-text"><?= get_field('block1_link_label') ?></p>
            </div>
          </a>
        </div>
      <?php endif ?>
    </div>

    <?php $productArray = get_field('block1_products') ?>
    <?php if ($productArray) : ?>
      <?php get_template_part('template-parts/product', 'slider', ['productArray' => $productArray]) ?>
    <?php endif ?>
  </div>
</section>



