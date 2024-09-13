<?php
  $block = $args['block'];
?>

<?php if ($block['video']) : ?>

  <div class="article-video">
    <div class="container">
      <video src="<?= $block['video'] ?>" controls loop muted></video>
    </div>
  </div>

<?php endif ?>