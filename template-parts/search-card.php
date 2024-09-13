<?php
  $post_id = $args['id'];
  $count = $args['count'];
?>

<li class="article-list-item one-in-the-row">

  <?php $count += 1; ?>
  <span class="list-item-number"><?= $count = $count < 10 ? '0' . $count : $count; ?></span>

  <div class="list-item-wrap pad">

    <div class="list-item-side list-item-content full-content">
      <h2 class="list-title"><?= get_the_title($post_id) ?></h2>

      <a href="<?= get_permalink( $post_id ) ?>" class="btn btn-hover list-link" target="_blank" rel="noopener noreferrer"><?= __('קרא עוד', 'giovanni') ?></a>
    </div>

  </div>
</li>