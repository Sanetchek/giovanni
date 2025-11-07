<?php
  $block = $args['block'];
?>

<div id="exhibition" class="article-exhibition exhibition">
  <?php
  $direction = $block['direction'] === 'rtl' ? 'reversed' : '';
  $image = $block['image'];
  $title = $block['title'];
  $desc = $block['text'];
  $label = $block['link_label'];
  $link = $block['link'];
  $signature = $block['signature'];
  ?>
  <div class="main-wrap exhibition-container <?= $direction ?>">
    <div class="exhibition-wrap <?= $direction ?>">
      <div class="exhibition-side exhibition-content-wrap">
        <div class="exhibition-content">
          <h2><?= $title ?></h2>
          <div><?= $desc ?></div>

          <?php if ($link) : ?>
            <a href="<?= $link ?>" class="btn btn-hover"><?= $label ?></a>
          <?php endif ?>

          <?php if ($signature) : ?>
            <?php show_image($signature, 'medium', ['class'=> 'signature']) ?>
          <?php endif ?>
        </div>
      </div>

      <div class="exhibition-side exhibition-image-wrap">
        <?php show_image($image, '424-424', ['class'=> 'exhibition-image']) ?>
      </div>
    </div>
  </div>
</div>