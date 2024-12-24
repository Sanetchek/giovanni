<section id="main_posts" class="main-posts">
  <?php $item = get_field('home_posts'); ?>
  <?php if( $item ) : ?>
  <div class="main-wrap">
    <?php
      $class = 'first';
      $shadow = 1;

      /**
       * 'item' -> Group of ACF fields (names: image, title, description, link_label, link)
       * 'class' -> (string) first/second (default: first)
       * 'shadow' -> (number) 0/1 - it put shadow on left or right (default: 1)
       */
      get_template_part('template-parts/main', 'post', ['item' => $item, 'class' => $class, 'shadow' => $shadow]);
    ?>
  </div>
  <?php endif ?>

  <?php $productArray = get_field('pop_products') ?>
  <?php if ($productArray) : ?>
    <div class="container">
      <?php get_template_part('template-parts/product', 'slider', ['productArray' => $productArray]) ?>
    </div>
  <?php endif ?>

  <?php $item = get_field('home_posts_2'); ?>
  <?php if( $item ) : ?>
  <div class="main-wrap">
    <?php
      $class = 'second';
      $shadow = 0;

      /**
       * 'item' -> Group of ACF fields (names: image, title, description, link_label, link)
       * 'class' -> (string) first/second (default: first)
       * 'shadow' -> (number) 0/1 - it put shadow on left or right (default: 1)
       */
      get_template_part('template-parts/main', 'post', ['item' => $item, 'class' => $class, 'shadow' => $shadow]);
    ?>
  </div>
  <?php endif ?>
</section>