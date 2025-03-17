<?php
$bg = get_field('hero_image');
$bg_mob = get_field('hero_image_mob');
$link = get_field('hero_link');
?>

<section id="hero" class="hero">
  <?php if ($link) : ?>
    <a href="<?= $link ?>">
  <?php endif ?>

  <div class="hero-image main-wrap">
    <?php generate_picture_source($bg, $bg_mob); ?>

    <h1 class="visually-hidden">Collections Page</h1>
  </div>

  <?php if ($link) : ?>
    </a>
  <?php endif ?>
</section>