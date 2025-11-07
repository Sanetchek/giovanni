<?php
  $block = $args['block'];
  $direction = isset($block['direction']) && $block['direction'] === 'rtl' ? 'reverse' : '';
  $text = isset($block['text']) ? $block['text'] : ''; // Set a default empty string if 'text' is not set
?>

<div class="article-post">
  <div class="container">
    <div class="post-wrap <?= $direction ?>">
      <div class="post-side">
        <?php show_image($block['image'], '800-full', ['class'=> 'post-image']) ?>
      </div>

      <div class="post-side">
        <h2 class="post-title"><?= $block['title'] ?></h2>
        <div class="post-content"><?= $text ?></div> <!-- Use the variable $text here -->

        <?php if (isset($block['link']) && $block['link']) : ?>
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