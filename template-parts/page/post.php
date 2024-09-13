<?php
  $block = $args['block'];
  $direction = $block['direction'] === 'rtl' ? 'reverse' : '';
?>

<div class="article-post">
  <div class="container">
    <div class="post-wrap <?= $direction ?>">
      <div class="post-side">
        <?php show_image($block['image'], '800-full', ['class'=> 'post-image']) ?>
      </div>

      <div class="post-side">
        <h2 class="post-title"><?= $block['title'] ?></h2>
        <div class="post-content"><?= $block['text'] ?></div>

        <?php if ($block['link']) : ?>
          <a href="<?= $block['link'] ?>" class="paragraph-link">
            <div class="paragraph-link-animation">
              <span><?= $block['link_label'] ?></span>
              <span><?= $block['link_label'] ?></span>
            </div>
          </a>
        <?php endif ?>
      </div>
    </div>
  </div>
</div>