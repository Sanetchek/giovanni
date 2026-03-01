<?php
  $block = $args['block'];
  $direction = isset($block['direction']) && $block['direction'] === 'rtl' ? 'reverse' : '';
  $text = isset($block['text']) ? $block['text'] : ''; // Set a default empty string if 'text' is not set
?>

<div class="article-post">
  <div class="container">
    <div class="post-wrap <?php echo esc_attr($direction); ?>">
      <div class="post-side">
        <?php
        $data = [
          'thumb' => [800, 0],
          'args' => [
            'class' => 'post-image',
          ],
        ];
        echo liteimage( $block['image'], $data );
        ?>
      </div>

      <div class="post-side">
        <h2 class="post-title"><?php echo esc_html($block['title']); ?></h2>
        <div class="post-content"><?php echo wp_kses_post($text); ?></div> <!-- Use the variable $text here -->

        <?php if (isset($block['link']) && $block['link']) : ?>
          <div class="button-animation-effect paragraph-link">
            <a href="<?php echo esc_url($block['link']); ?>" class="btn btn-hover">
              <div class="animation-label-container">
                <p class="cta-text"><?php echo esc_html($block['link_label']); ?></p>
                <p aria-hidden="true" class="cta-text animation-label-text"><?php echo esc_html($block['link_label']); ?></p>
              </div>
            </a>
          </div>
        <?php endif ?>
      </div>
    </div>
  </div>
</div>