<section id="block10" class="block10">
   <div class="taxonomies page-container">
    <?php
      $title = get_field('block10_taxonomies_title');
      $taxonomies = get_field('block10_taxonomies');

      get_template_part('template-parts/sections/taxonomies', '', ['title' => $title, 'taxonomies' => $taxonomies]);
    ?>
  </div>
</section>