<?php
  $post_id = $args['id'];
  $product = wc_get_product($post_id);
?>

<li class="article-list-item one-in-the-row">



  <div class="list-item-wrap pad">

    <div class="list-item-side list-item-content full-content">

      <?php if (has_post_thumbnail($post_id)) : ?>
        <a href="<?= get_permalink( $post_id ) ?>" class="product-thumbnail-link" target="_blank" rel="noopener noreferrer">
          <?php
          $data = [
            'thumb' => [400, 400],
            'max' => [
              '390' => [371, 371],
            ],
            'args' => [
              'alt' => esc_attr(get_the_title($post_id)),
              'class' => 'product-thumbnail',
            ],
          ];
          echo liteimage(get_post_thumbnail_id($post_id), $data);
          ?>
        </a>
      <?php endif; ?>
      <h2 class="search-result-title"><?= get_the_title($post_id) ?></h2>
      <?php if ($product) : ?>
        <p class="product-price"><?= $product->get_price_html(); ?></p>
      <?php endif; ?>


    </div>

  </div>
</li>
