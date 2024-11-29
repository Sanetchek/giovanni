<?php
  $group = $args['group'];
?>

<div class="product-info-item">
  <div class="product-info-shadow">
    <svg width="579" height="270" viewBox="0 0 579 270" fill="none" xmlns="http://www.w3.org/2000/svg">
      <g filter="url(#filter0_f_79_2102)">
        <path d="M539 40H40C53.793 62.6596 74.31 134.179 74.31 175.613V230H515.5V189.877C515.5 159.707 517.544 81.2728 539 40Z" fill="black" fill-opacity="0.1"/>
      </g>
      <defs>
        <filter id="filter0_f_79_2102" x="0" y="0" width="579" height="270" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
          <feFlood flood-opacity="0" result="BackgroundImageFix"/>
          <feBlend mode="normal" in="SourceGraphic" in2="BackgroundImageFix" result="shape"/>
          <feGaussianBlur stdDeviation="20" result="effect1_foregroundBlur_79_2102"/>
        </filter>
      </defs>
    </svg>
  </div>

  <div class="product-info-content">
    <svg class='icon-<?= $group['icon'] ?>' width='24' height='24'>
      <use href='<?= assets('img/sprite.svg#icon-' . $group['icon']) ?>'></use>
    </svg>

    <h2 class="product-info-title"><?= $group['title'] ?></h2>

    <?php if ($group['link']) : ?>
      <div class="button-animation-effect">
        <a href="<?php echo $group['link'] ?>" class="btn btn-hover">
          <div class="animation-label-container">
            <p class="cta-text"><?php echo $group['link_label'] ?></p>
            <p aria-hidden="true" class="cta-text animation-label-text"><?php echo $group['link_label'] ?></p>
          </div>
        </a>
      </div>
    <?php endif ?>
  </div>
</div>