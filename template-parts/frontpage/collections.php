<section id="collections" class="collections">
  <div class="collections-image main-wrap">
    <?php
      $img_mob = get_field('new_col_image_mob');
      $img = get_field('new_col_image');

      show_image($img, '1920-920', ['class' => 'collections-bg page-desk']);

      $img_mob = $img_mob ? $img_mob : $img;
      show_image($img_mob, '880-880', ['class' => 'page-mob']);
    ?>
  </div>

  <div class="collections-content">
    <div class="collections-shadow">
      <svg xmlns="http://www.w3.org/2000/svg" width="1711" height="618" fill="none">
        <g filter="url(#a)">
          <path fill="#000" fill-opacity=".1" d="M1671 40H40c45.083 64.162 112.144 266.675 112.144 384v154H1561.32V464.388c0-85.427 39.55-307.521 109.68-424.388Z"/>
        </g>
        <defs>
          <filter id="a" width="1711" height="618" x="0" y="0" color-interpolation-filters="sRGB" filterUnits="userSpaceOnUse">
            <feFlood flood-opacity="0" result="BackgroundImageFix"/>
            <feBlend in="SourceGraphic" in2="BackgroundImageFix" result="shape"/>
            <feGaussianBlur result="effect1_foregroundBlur_14_606" stdDeviation="20"/>
          </filter>
        </defs>
      </svg>
    </div>

    <div class="collections-container">
      <h2><?= get_field('new_col_title') ?></h2>
      <p><?= get_field('new_col_subtitle') ?></p>

      <ul class="collections-list">
        <?php
        $products = get_field('new_col_products');

        foreach ($products as $product_id) : ?>
          <li class="product-card">
            <?php $args = ['product_id' => $product_id]; ?>
            <?php get_template_part( 'template-parts/product', 'card', $args ) ?>
          </li>
          <?php
        endforeach;
        ?>
      </ul>
    </div>
  </div>
</section>