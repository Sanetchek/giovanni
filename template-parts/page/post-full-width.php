<?php
  $block = $args['block'];
?>

<div id="main_posts" class="main-posts article-full-post">
  <div class="main-wrap">
    <?php
      $class = $block['direction'] === 'rtl' ? 'second' : 'first';
      $shadow = $block['direction'] === 'rtl' ? 0 : 1;

      /**
       * 'item' -> Group of ACF fields (names: image, title, description, link_label, link)
       * 'class' -> (string) first/second (default: first)
       * 'shadow' -> (number) 0/1 - it put shadow on left or right (default: 1)
       */
      get_template_part('template-parts/main', 'post', ['item' => $block, 'class' => $class, 'shadow' => $shadow]);
    ?>
  </div>
</div>