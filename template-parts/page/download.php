<?php
  $block = $args['block'];
?>

<div class="article-downloads">
  <div class="container">
    <h2 class="paragraph-title"><?= $block['title'] ?></h2>

    <?php if ($block['link']) : ?>
    <a href="<?= $block['link'] ?>" class="download-link" download>
      <span><?= $block['link_label'] ?></span>
      <img src="<?= assets('img/download.png') ?>" alt="download" class="download-image" width="24" height="24" loading="lazy">
    </a>
    <?php endif ?>
  </div>
</div>