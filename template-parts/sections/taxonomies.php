<?php
  $title = isset($args['title']) ? $args['title'] : false;
  $taxs = isset($args['taxonomies']) ? $args['taxonomies'] : false;
?>

<div class="container">
  <?php if ($title) : ?>
    <h2 class="taxonomies-title"><?= $title ?></h2>
  <?php endif ?>

  <?php if ($taxs) : ?>
    <ul class="taxonomies-wrap">
      <?php foreach ($taxs as $category_id) : ?>
        <li class="taxonomies-content">
          <?php $args = ['category_id' => $category_id]; ?>
          <?php get_template_part( 'template-parts/taxonomy', 'card', $args ) ?>
        </li>
      <?php endforeach ?>
    </ul>
  <?php endif ?>
</div>