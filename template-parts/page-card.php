<?php
$id = $args['id'];
?>

<li class="page-item">
  <a href="<?= get_permalink($id) ?>" target="_blank">
    <?php
      $image = get_field('background_image', $id);
      show_image($image, '382-382', ['class'=> 'page-item-image']);
    ?>

    <?php if (!$image) : ?>
      <div class="page-item-replacer">
        <img src="<?= assets('img/replacer.jpg') ?>" alt="replacer">
      </div>
    <?php endif ?>
  </a>

  <div class="page-item-info">
    <span class="page-item-category">
      <?= get_the_category_list(', ', '', $id); ?>
    </span>
    <span class="page-item-time">
      <?= get_the_date('j M Y', $id); ?>
    </span>
  </div>
  <h2 class="page-item-title">
    <a href="<?= get_permalink($id) ?>" target="_blank">
      <?= get_the_title($id) ?>
    </a>
  </h2>
</li>