<?php
  $block = $args['block'];
  $heading_tag = $block['heading_tag'] ?? 'h2';
  $container_class = ($heading_tag === 'h1') ? 'article-paragraph main-title-container' : 'article-paragraph';
?>

<div class="<?= esc_attr($container_class) ?>">
  <div class="container">

    <?php if ($heading_tag === 'h1'): ?>
    <nav class="breadcrumbs">
      <a href="/">עמוד הבית </a> /
      <span><?= esc_html($block['title']) ?></span>
    </nav>
    <?php endif; ?>

    <<?= esc_html($heading_tag) ?> class="paragraph-title"><?= esc_html($block['title']) ?></<?= esc_html($heading_tag) ?>>

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