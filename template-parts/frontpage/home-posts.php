<section id="main_posts" class="main-posts">
  <div class="main-wrap">
    <?php
    $postsArray = get_field('home_posts');
    ?>

    <?php if( $postsArray ) : ?>
      <?php foreach( $postsArray as $key => $item ) : ?>
        <?php
          $class = $key % 2 ? 'second' : 'first';
          $shadow = $key % 2 ? 0 : 1;

          /**
           * 'item' -> Group of ACF fields (names: image, title, description, link_label, link)
           * 'class' -> (string) first/second (default: first)
           * 'shadow' -> (number) 0/1 - it put shadow on left or right (default: 1)
           */
          get_template_part('template-parts/main', 'post', ['item' => $item, 'class' => $class, 'shadow' => $shadow]);
        ?>
      <?php endforeach; ?>
    <?php endif ?>
  </div>
</section>