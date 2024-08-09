<?php
  $item = isset($args['item']) ? $args['item'] : false;
  $class = isset($args['class']) ? $args['class'] : 'first';
  $shadow = isset($args['shadow']) ? $args['shadow'] : 1;
?>

<?php if ($item) : ?>
  <div class="main-posts-art <?php echo $class ?>">
    <div class="main-posts-picture">
      <?php show_image($item['image'], '1184-865', ['class'=> 'main-post-image']) ?>
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
            <a href="<?php echo $item['link'] ?>" class="btn btn-hover"><?php echo $item['link_label'] ?></a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
<?php endif ?>