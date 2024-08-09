<section id="popular" class="popular">
  <div class="container">
    <h2 class="popular-title"><?= get_field('pop_title') ?></h2>

    <?php $productArray = get_field('pop_products') ?>
    <?php if ($productArray) : ?>
      <?php get_template_part('template-parts/product', 'slider', ['productArray' => $productArray]) ?>
    <?php endif ?>
  </div>
</section>