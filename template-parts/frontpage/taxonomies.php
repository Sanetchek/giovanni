<section id="taxonomies" class="taxonomies">
  <?php
    $title = get_field('category_title');
    $taxs = get_field('category_taxonomies');
  ?>

  <div class="container">
    <h2 class="taxonomies-title"><?= $title ?></h2>

    <ul class="taxonomies-wrap">
      <?php foreach ($taxs as $category_id) : ?>
        <?php
          // Get the category object
          $category = get_term($category_id, 'product_cat');

          // Check if the category exists
          if ($category && !is_wp_error($category)) : ?>
          <?php
            // Get the category title
            $category_title = $category->name;

            // Get the category thumbnail ID
            $thumbnail_id = get_term_meta($category_id, 'thumbnail_id', true);

            // Get the URL of the category thumbnail
            $thumbnail_url = get_image($thumbnail_id, '350-350', ['class' => 'taxonomies-image']);
          ?>
            <li class="taxonomies-content">
              <h3 class="taxonomies-content-title"><?= $category_title ?></h3>

              <div class="taxonomies-content-image">
                <?= $thumbnail_url ?>
              </div>
            </li>
          <?php endif; ?>

      <?php endforeach ?>
    </ul>
  </div>
</section>