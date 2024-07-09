<?php
$bg = get_field('hero_image');
$bg_mob = get_field('hero_image_mob');
?>

<section id="hero" class="hero">
  <div class="hero-image main-wrap">
    <?php show_image($bg, '1920-865', ['class' => 'page-desk']) ?>
    <?php
      $bg_mob = $bg_mob ? $bg_mob : $bg;
      show_image($bg_mob, '880-880', ['class' => 'page-mob']);
    ?>

    <div class="hero-wrap">
      <div class="hero-content">
        <?php
          $title = get_field('hero_title');
          $label = get_field('hero_label');
          $link_label = get_field('hero_link_label');
          $link = get_field('hero_link');
        ?>

        <h1>
          <span class="hero-label"><?= $label ?></span>
          <span class="hero-spacer">|</span>
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