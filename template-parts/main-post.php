<?php
  $item = isset($args['item']) ? $args['item'] : false;
  $class = isset($args['class']) ? $args['class'] : 'first';
  $shadow = isset($args['shadow']) ? $args['shadow'] : 1;
?>

<?php if ($item) : ?>
  <div class="main-posts-art <?php echo $class ?>">
    <div class="main-posts-picture">
      <?php show_image($item['image'], '1280-full', ['class'=> 'main-post-image']) ?>
    </div>

    <div class="main-posts-content">
      <div class="main-posts-shadow">
        <?php get_shadow($shadow); ?>
      </div>

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