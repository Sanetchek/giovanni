<section id="collections" class="collections">
  <div class="collections-image main-wrap">
    <?php
      $img = get_field('new_col_image');
      show_image($img, '1920-920', ['class' => 'collections-bg'])
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

        foreach ($products as $product_id) {
          $product = wc_get_product($product_id); // Get the product object
          if ($product) {
            $title = $product->get_name(); // Get the product title
            $price = $product->get_price_html(); // Get the price (including sale price if applicable)
            $image = wp_get_attachment_image_src(get_post_thumbnail_id($product_id), '372-372'); // Get the featured image URL
            $permalink = get_permalink($product_id); // Get the product permalink
            ?>
            <li class="product-card">
              <a href="<?php echo esc_url($permalink); ?>" class="product-link">
                <div class="product-image">
                  <img src="<?php echo esc_url($image[0]); ?>" alt="<?php echo esc_attr($title); ?>">
                </div>
                <div class="product-info">
                  <h2 class="product-title"><?php echo esc_html($title); ?></h2>
                  <span class="product-price"><?php echo $price; ?></span>
                </div>
              </a>
            </li>
            <?php
          }
        }
        ?>
      </ul>
    </div>
  </div>
</section>