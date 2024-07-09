<div id="collection_menu" class="mega-menu">
  <div class="page-mob">
    <button class="mega-menu-header">
      <?= __('אוספים', 'giovanni') ?>
    </button>
  </div>

  <div class="mega-menu-container">
    <div class="menu-wrap">
      <div class="menu-1">
        <p class="menu-title"><?= __('ערכות נושא', 'giovanni') ?></p>

        <?php
          wp_nav_menu([
            'theme_location' => 'themes',
            'menu_id'        => 'themes-menu',
            'container'			 => ''
          ]);
        ?>

        <div class="menu-btns">
          <a href="#" class="btn-black"><?= __('פופולרי', 'giovanni') ?></a>
          <a href="#" class="btn-black"><?= __('חדש ב', 'giovanni') ?></a>
        </div>
      </div>

      <div class="menu-2">
        <p class="menu-title"><?= __('מומלצים', 'giovanni') ?></p>

        <?php
          wp_nav_menu([
            'theme_location' => 'featured',
            'menu_id'        => 'featured-menu',
            'container'			 => ''
          ]);
        ?>
      </div>
    </div>

    <div class="links-wrap">
      <?php $links = get_field('menu_collections', 'options'); ?>

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