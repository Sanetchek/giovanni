<?php
  $block = $args['block'];
?>

<div class="article-paragraph">
  <div class="container">
    <h2 class="paragraph-title"><?= $block['title'] ?></h2>

    <div class="paragraph-text"><?= $block['text'] ?></div>

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