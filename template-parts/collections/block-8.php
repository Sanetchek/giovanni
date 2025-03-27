<?php
$bg = get_field('block8_image');
$bg_mob = get_field('block8_image_mob');
?>

<section id="block8" class="block8">
  <div class="hero">
    <?php $has_link = get_field('block8_use_link') ?>
    <?php if ($has_link) : ?>
      <a href="<?= get_field('block8_link_for_banner') ?>">
    <?php endif ?>
        <div class="hero-image main-wrap">
          <?php generate_picture_source($bg, $bg_mob); ?>
        </div>
    <?php if ($has_link) : ?>
      </a>
    <?php endif ?>
  </div>

  <div class="taxonomies page-container">
    <?php
      $title = get_field('block8_taxonomies_title');
      $taxonomies = get_field('block8_taxonomies');

      get_template_part('template-parts/sections/taxonomies', '', ['title' => $title, 'taxonomies' => $taxonomies]);
    ?>
  </div>
</section>