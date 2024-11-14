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
      <?php
        $links = get_field('menu_jewellery', 'options');
        get_template_part('template-parts/mega-menu/links', '', ['links' => $links]);
      ?>
    </div>
  </div>
</div>




<div id="jewellery_menu_mobile" class="mobile-jewellery mobile-dropdown-menu">

  <div class="page-mob">
    <button class="mega-menu-header">
      <?= __('תכשיטים', 'giovanni') ?>
    </button>
  </div>

  <div class="menu-1">
    <p class="menu-title"><?= __('נשים', 'giovanni') ?></p>

    <?php
      wp_nav_menu([
        'theme_location' => 'women',
        'menu_id'        => 'women-menu',
        'container'			 => ''
      ]);
    ?>

    <p class="menu-title"><?= __('גברים', 'giovanni') ?></p>

    <?php
      wp_nav_menu([
        'theme_location' => 'men',
        'menu_id'        => 'men-menu',
        'container'			 => ''
      ]);
    ?>

    <div class="menu-btns">
      <a href="#" class="btn-black"><?= __('פופולרי', 'giovanni') ?></a>
      <a href="#" class="btn-black"><?= __('חדש ב', 'giovanni') ?></a>
    </div>
  </div>

  <div class="links-wrap mobile-links-wrap">
    <?php
      $links = get_field('menu_gifts', 'options');
      get_template_part('template-parts/mega-menu/links-mobile', '', ['links' => $links]);
    ?>
  </div>

</div>