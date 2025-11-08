<?php
  $block = $args['block'];
?>

<div class="article-slider">
  <div class="article-slider-list" dir="ltr">
    <?php foreach ($block['images_list'] as $item) : ?>
      <div class="article-slider-item">
        <?php
        $data = [
          'thumb' => [1920, 865],
          'args' => [
            'class' => 'article-slider-item-img',
          ],
        ];
        echo liteimage( $item['image'], $data );
        ?>
      </div>
    <?php endforeach ?>
  </div>

  <div class="popular-nav">
    <div class="slick-prev slick-arrow"><span>
      <svg class='icon-arrow-left' width='36' height='6'>
        <use href='<?= assets('img/sprite.svg#icon-arrow-left') ?>'></use>
      </svg>
    </span></div>
    <div class="nav-indicator">
      <span class="slide-indicator">1</span>/<span class="slide-total">2</span>
    </div>
    <div class="slick-next slick-arrow"><span>
      <svg class='icon-arrow-right' width='36' height='6'>
        <use href='<?= assets('img/sprite.svg#icon-arrow-right') ?>'></use>
      </svg>
    </span></div>
  </div>
</div>