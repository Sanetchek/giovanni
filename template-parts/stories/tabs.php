<?php
  $title = $args['title'];
?>

<section class="tabs">
  <h2 class="tab-title"><?= $title ?></h2>

  <?php
  // Get all categories
  $categories = get_categories();

  // Display category tabs
  echo '<ul class="category-tabs">';
  echo '<li><a href="#tab-all">'. __('הכל', 'giovanni') .'</a></li>'; // Add "All" tab

  foreach ($categories as $category) {
    echo '<li><a href="#tab-' . esc_attr($category->slug) . '">' . esc_html($category->name) . '</a></li>';
  }
  echo '</ul>';

  // Display "All" posts
  echo '<div id="tab-all" class="tab-content">';

  // Query all posts
  $query_all = new WP_Query(array(
      'posts_per_page' => -1, // Get all posts
  ));

  if ($query_all->have_posts()) {
      echo '<ul class="page-list">';
      while ($query_all->have_posts()) {
          $query_all->the_post();
          get_template_part('template-parts/page', 'card', ['id' => get_the_ID()]);
      }
      echo '</ul>';
  } else {
      echo '<p>No posts found.</p>';
  }

  // Reset post data
  wp_reset_postdata();

  echo '</div>';

  // Display posts for each category
  foreach ($categories as $category) {
    echo '<div id="tab-' . esc_attr($category->slug) . '" class="tab-content">';

    // Query posts for the current category
    $query = new WP_Query(array(
      'cat' => $category->term_id,
      'posts_per_page' => -1, // Get all posts in this category
    ));

    if ($query->have_posts()) {
      echo '<ul class="page-list">';
      while ($query->have_posts()) {
        $query->the_post();
        get_template_part('template-parts/page', 'card', ['id' => get_the_ID()]);
      }
      echo '</ul>';
    } else {
      echo '<p>No posts found in this category.</p>';
    }

    // Reset post data
    wp_reset_postdata();

    echo '</div>';
  }
  ?>
  <div class="tabs-container">
    <ul class="tab-btns">
      <li class="tab-button" data-content="1"></li>
    </ul>
  </div>
</section>