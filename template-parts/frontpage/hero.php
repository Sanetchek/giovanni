<?php 
$bg = get_field('hero_image');
$bg_mob = get_field('hero_image_mob');
?>

<section id="hero" class="hero">
  <div class="hero-image main-wrap">
    <?php get_template_part('template-parts/page', 'hero', ['hero_image' => $bg, 'hero_image_mob' => $bg_mob]) ?>

    <div class="hero-wrap">
      <div class="hero-content">
        <?php
          $title = get_field('hero_title');
          $label = get_field('hero_label');
          $link_label = get_field('hero_link_label');
          $link = get_field('hero_link');
        ?>

        <h1>
          <?php if ($label): ?>
            <span class="hero-label"><?= $label ?></span>
            <span class="hero-spacer">|</span>
          <?php endif; ?>
          <span class="hero-title"><?= $title ?></span>
        </h1>

        <?php if ($link) : ?>
          <a href="<?= $link ?>" class="btn hero-link btn-hover">
            <span><?= $link_label ?></span>
          </a>
        <?php endif ?>
      </div>
    </div>

  </div>
</section>
