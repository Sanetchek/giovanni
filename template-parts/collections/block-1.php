<section id="block1" class="block1">
  <div class="container">
    <div class="block1-content">
      <h2><?= get_field('block1_title') ?></h2>
      <p><?= get_field('block1_description') ?></p>

      <?php if (get_field('block1_link')) : ?>
        <a href="<?= get_field('block1_link') ?>" class="btn btn-no-border btn-hover"><?= get_field('block1_link_label') ?></a>
      <?php endif ?>
    </div>

    <?php $productArray = get_field('block1_products') ?>
    <?php if ($productArray) : ?>
      <?php get_template_part('template-parts/product', 'slider', ['productArray' => $productArray]) ?>
    <?php endif ?>
  </div>
</section>