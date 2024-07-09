<section id="taxonomies" class="taxonomies">
  <?php
    $title = get_field('category_title');
    $taxs = get_field('category_taxonomies');
  ?>

  <div class="container">
    <h2 class="taxonomies-title"><?= $title ?></h2>

    <ul class="taxonomies-wrap">
      <?php foreach ($taxs as $category_id) : ?>
        <li class="taxonomies-content">
          <?php $args = ['category_id' => $category_id]; ?>
          <?php get_template_part( 'template-parts/taxonomy', 'card', $args ) ?>
        </li>
      <?php endforeach ?>
    </ul>
  </div>
</section>