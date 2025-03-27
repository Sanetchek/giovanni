<?php
$bg = get_field('hero_image');
$bg_mob = get_field('hero_image_mob');
$link = get_field('hero_link');
?>

<section id="hero" class="hero">
  <?php $has_link = get_field('hero_use_link') ?>
  <?php if ($has_link) : ?>
    <a href="<?= get_field('hero_link_for_banner') ?>">
  <?php endif ?>

    <div class="hero-image main-wrap">
      <?php generate_picture_source($bg, $bg_mob); ?>
    </div>

  <?php if ($has_link) : ?>
    </a>
  <?php endif ?>
</section>