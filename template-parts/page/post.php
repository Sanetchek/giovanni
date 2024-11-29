<?php
  $block = $args['block'];
  $direction = $block['direction'] === 'rtl' ? 'reverse' : '';
?>

<div class="article-post">
  <div class="container">
    <div class="post-wrap <?= $direction ?>">
      <div class="post-side">
        <?php show_image($block['image'], '800-full', ['class'=> 'post-image']) ?>
      </div>

      <div class="post-side">
        <h2 class="post-title"><?= $block['title'] ?></h2>
        <div class="post-content"><?= $block['text'] ?></div>

        <?php if ($block['link']) : ?>
          <div class="button-animation-effect paragraph-link">
            <a href="<?php echo $block['link'] ?>" class="btn btn-hover">
              <div class="animation-label-container">
                <p class="cta-text"><?php echo $block['link_label'] ?></p>
                <p aria-hidden="true" class="cta-text animation-label-text"><?php echo $block['link_label'] ?></p>
              </div>
            </a>
          </div>
        <?php endif ?>
      </div>
    </div>
  </div>
</div>