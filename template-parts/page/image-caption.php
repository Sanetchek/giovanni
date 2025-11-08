<?php
  $block = $args['block'];
?>

<div class="article-image-caption">
  <div class="article-wrap">

    <ul class="image-caption-list">
      <?php foreach ($block['list'] as $item) : ?>
      <?php
          $width = $item['width'];
          $thumb = $item['width'] === 'full' ? [1600, 0] : [800, 980];
        ?>
      <li class="image-caption-item <?= $width ?>">
        <figure>
          <?php
          $data = [
            'thumb' => $thumb,
            'args' => [
              'class' => 'image-caption',
            ],
          ];
          echo liteimage( $item['image'], $data );
          ?>

          <?php if ($item['text']) : ?>
          <figcaption class="caption"><?= $item['text'] ?></figcaption>
          <?php endif ?>
        </figure>
      </li>
      <?php endforeach ?>
    </ul>

  </div>
</div>