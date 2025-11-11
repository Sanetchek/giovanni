<?php
  $item = isset($args['item']) ? $args['item'] : false;
  $image = isset($item['image']) ? $item['image'] : '';
  $image_mob = isset($item['image_mob']) ? $item['image_mob'] : $image; // Check if 'image_mob' exists, otherwise use 'image'
  $class = isset($args['class']) ? $args['class'] : 'first';
  $shadow = isset($args['shadow']) ? $args['shadow'] : 1;
  $link_for_image = isset($item['link_for_image']) ? $item['link_for_image'] : false;
?>

<?php if ($item) : ?>
  <div class="main-posts-art <?php echo $class ?>">
    <?php if ($image) : ?>
        <div class="main-posts-picture">
          <?php if ($link_for_image && $item['link']) : ?>
            <a href="<?php echo $item['link'] ?>" class="link-for-image">
          <?php endif ?>

          <?php
          $data = [
            'thumb' => [1280, 0],
            'max' => [
              '1024' => [1024, 0],
              '768' => [768, 0],
              '576' => [576, 0],
              '412' => [412, 0],
            ],
            'args' => [
              'class' => 'main-post-image',
            ],
          ];
          echo liteimage( $image, $data, $image_mob);
          ?>

          <?php if ($link_for_image && $item['link']) : ?>
            </a>
          <?php endif ?>
        </div>
    <?php endif ?>

    <div class="main-posts-content">
      <div class="main-posts-container">
        <div class="main-posts-wrap">
          <h2><?php echo $item['title']; ?></h2>
          <p><?php echo $item['description']; ?></p>

          <?php if ($item['link']) : ?>
            <div class="button-animation-effect">
              <a href="<?php echo $item['link'] ?>" class="btn btn-hover">
                <div class="animation-label-container">
                  <p class="cta-text"><?php echo $item['link_label'] ?></p>
                  <p aria-hidden="true" class="cta-text animation-label-text"><?php echo $item['link_label'] ?></p>
                </div>
              </a>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
<?php endif ?>
