<?php
  $block = $args['block'];
?>

<div class="article-page-list article-wrap">
  <?php if ($block['title']) : ?>
    <h2 class="page-list-title"><?= $block['title'] ?></h2>
  <?php endif ?>

  <ul class="page-list">
    <?php foreach ($block['pages'] as $item_id) : ?>
      <?php get_template_part('template-parts/page', 'card', ['id' => $item_id]); ?>
    <?php endforeach ?>
  </ul>
</div>