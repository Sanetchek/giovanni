<section id="main_posts" class="main-posts">
  <div class="main-wrap">
    <?php
    $postsArray = get_field('home_posts');
    ?>

    <?php if( $postsArray ) : ?>
      <?php foreach( $postsArray as $key => $item ) : ?>
        <?php
          $class = $key % 2 ? 'second' : 'first';
          $shadow = $key % 2 ? 0 : 1;
        ?>

        <div class="main-posts-art <?php echo $class ?>">
          <div class="main-posts-picture">
            <?php show_image($item['image'], '1184-865', ['class'=> 'main-post-image']) ?>
          </div>

          <div class="main-posts-content">
            <div class="main-posts-shadow">
              <?php get_shadow($shadow); ?>
            </div>

            <div class="main-posts-wrap">
              <h2><?php echo $item['title']; ?></h2>
              <p><?php echo $item['description']; ?></p>

              <?php if ($item['link']) : ?>
                <a href="<?php echo $item['link'] ?>" class="btn"><?php echo $item['button_label'] ?></a>
              <?php endif; ?>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php endif ?>
  </div>
</section>

<?php
function get_shadow($shadow) {
  if ($shadow) : ?>
    <svg xmlns="http://www.w3.org/2000/svg" width="782" height="542" fill="none" viewBox="0 0 862 622">
      <g filter="url(#a)" transform="translate(-70, -34) scale(1.12)">
        <path fill="#000" fill-opacity=".2" d="M822 40 40 76.41c23 66.749 43.193 223.328 43.193 345.383V582h702.26V460.284c0-88.871.77-298.706 36.547-420.284Z"/>
      </g>
      <defs>
        <filter id="a" width="862" height="622" x="0" y="0" color-interpolation-filters="sRGB" filterUnits="userSpaceOnUse">
          <feFlood flood-opacity="0" result="BackgroundImageFix"/>
          <feBlend in="SourceGraphic" in2="BackgroundImageFix" result="shape"/>
          <feGaussianBlur result="effect1_foregroundBlur_18_653" stdDeviation="20"/>
        </filter>
      </defs>
    </svg>
  <?php else: ?>
    <svg xmlns="http://www.w3.org/2000/svg" width="782" height="542" fill="none" viewBox="0 0 861 696">
      <g filter="url(#a)" transform="translate(-105, -30) scale(1.12)">
        <path fill="#000" fill-opacity=".2" d="m40 40 781 41.382c-22.97 75.862-43.138 253.819-43.138 392.537V656H76.5V517.666C76.5 416.661 75.731 178.177 40 40Z"/>
      </g>
      <defs>
        <filter id="a" width="861" height="696" x="0" y="0" color-interpolation-filters="sRGB" filterUnits="userSpaceOnUse">
          <feFlood flood-opacity="0" result="BackgroundImageFix"/>
          <feBlend in="SourceGraphic" in2="BackgroundImageFix" result="shape"/>
          <feGaussianBlur result="effect1_foregroundBlur_18_669" stdDeviation="20"/>
        </filter>
      </defs>
    </svg>
  <?php endif;
}
?>