<?php
$bg = get_field('block8_image');
$bg_mob = get_field('block8_image_mob');
?>

<section id="block8" class="block8">
  <div class="hero">
    <div class="hero-image main-wrap">
      <?php generate_picture_source($bg, $bg_mob); ?>
      <h1 class="visually-hidden"><?= __('Collections Page', 'giovanni') ?></h1>
    </div>
  </div>

  <div class="taxonomies page-container">
    <?php
      $title = get_field('block8_taxonomies_title');
      $taxonomies = get_field('block8_taxonomies');

      get_template_part('template-parts/sections/taxonomies', '', ['title' => $title, 'taxonomies' => $taxonomies]);
    ?>
  </div>
</section>