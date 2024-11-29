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
      <div class="button-animation-effect paragraph-link">
        <a href="<?php echo $block['link'] ?>" class="btn btn-hover">
          <div class="animation-label-container">
            <p class="cta-text"><?php echo $block['link_label'] ?></p>
            <p aria-hidden="true" class="cta-text animation-label-text"><?php echo $block['link_label'] ?></p>
          </div>
        </a>
      </div>
    <?php endif ?>
  </div>
</div>