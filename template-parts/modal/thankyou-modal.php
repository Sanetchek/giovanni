<div id="thankyou_modal" class="thankyou-modal">
  <div class="thankyou-modal-close">
    <svg class='icon-close' width='24' height='24'>
      <use href='<?= assets('img/sprite.svg#icon-close') ?>'></use>
    </svg>
  </div>

  <?php
    $image = get_field('thku_image', 'option');
    if ($image) {
      $data = [
        'thumb' => [400, 0],
      ];
      echo liteimage( $image, $data );
    }
  ?>

  <div class="thankyou-modal-content">
    <?php if (get_field('thku_title', 'option')) : ?>
      <h2 class="thankyou-modal-title"><?= get_field('thku_title', 'option') ?></h2>
    <?php endif ?>

    <?php if (get_field('thku_text', 'option')) : ?>
      <div class="thankyou-modal-text">
        <?= get_field('thku_text', 'option') ?>
      </div>
    <?php endif ?>

    <?php if (get_field('thku_link', 'option')) : ?>
      <a href="<?= get_field('thku_link', 'option') ?>" class="button button-black button-hover white"><?= get_field('thku_button_label', 'option') ?></a>
    <?php endif ?>
  </div>
</div>
<div id="thankyou_overlay" class="thankyou-overlay"></div>