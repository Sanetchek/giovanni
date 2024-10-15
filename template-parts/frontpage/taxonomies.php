<section id="taxonomies" class="container taxonomies">
  <?php
    $title = get_field('category_title');
    $taxonomies = get_field('category_taxonomies');

    get_template_part('template-parts/sections/taxonomies', '', ['title' => $title, 'taxonomies' => $taxonomies]);
  ?>
</section>