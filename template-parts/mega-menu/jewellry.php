<div id="jewellery_menu" class="mega-menu">
  <div class="page-mob">
    <button class="mega-menu-header">
      <?= __('תכשיטים', 'giovanni') ?>
    </button>
  </div>

  <div class="mega-menu-container">
    <div class="menu-wrap">
      <div class="menu-1">
        <p class="menu-title"><?= __('נשים', 'giovanni') ?></p>

        <?php
          wp_nav_menu([
            'theme_location' => 'women',
            'menu_id'        => 'women-menu',
            'container'			 => ''
          ]);
        ?>

        <div class="menu-btns">
          <a href="#" class="btn-black"><?= __('פופולרי', 'giovanni') ?></a>
          <a href="#" class="btn-black"><?= __('חדש ב', 'giovanni') ?></a>
        </div>
      </div>

      <div class="menu-2">
        <p class="menu-title"><?= __('גברים', 'giovanni') ?></p>

        <?php
          wp_nav_menu([
            'theme_location' => 'men',
            'menu_id'        => 'men-menu',
            'container'			 => ''
          ]);
        ?>
      </div>
    </div>

    <div class="links-wrap">
      <?php $links = get_field('menu_jewellery', 'options'); ?>

      <?php if ($links) : ?>
        <?php foreach ($links as $item) : ?>
          <?php if ($item['link']) : ?>
            <a href="<?= $item['link'] ?>" class="link-item">
              <?php show_image($item['image'], '424-307') ?>

              <p class="link-item-title"><?= $item['title'] ?></p>
              <p><?= $item['description'] ?></p>
              <p>
                <svg class='icon-arrow-left' width='24' height='24'>
                  <use href='<?= assets('img/sprite.svg#icon-arrow-left') ?>'></use>
                </svg>
              </p>
            </a>
          <?php endif ?>
        <?php endforeach ?>
      <?php endif ?>
    </div>
  </div>
</div>