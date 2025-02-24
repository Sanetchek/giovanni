<div id="about_menu" class="mega-menu">
  <div class="page-mob">
    <button class="mega-menu-header">
      <?= __('אוֹדוֹת', 'giovanni') ?>
    </button>
  </div>

  <div class="mega-menu-container">
    <div class="menu-side">
      <div class="menu-wrap">
        <div class="menu-1">

          <?php
            wp_nav_menu([
              'theme_location' => 'about',
              'menu_id'        => 'about-menu',
              'container'			 => ''
            ]);
          ?>
        </div>
      </div>

      <?php $btns = get_field('menu_about_btns', 'options'); ?>
      <?php if ($btns) : ?>
        <div class="menu-btns">
          <?php foreach ( $btns as $btn ) : ?>
            <?php if ($btn['link']) : ?>
              <a href="<?= $btn['link'] ?>" class="btn-black"><?= $btn['label'] ?></a>
            <?php endif ?>
          <?php endforeach ?>
        </div>
      <?php endif ?>
    </div>

    <div class="links-wrap">
      <?php
        $links = get_field('menu_about', 'options');
        get_template_part('template-parts/mega-menu/links', '', ['links' => $links]);
      ?>
    </div>
  </div>
</div>


<div id="about_menu_mobile" class="mobile-about mobile-dropdown-menu">

  <div class="page-mob">
    <button class="mega-menu-header">
      <?= __('אוֹדוֹת', 'giovanni') ?>
    </button>
  </div>

  <div class="menu-1">
      <?php
        wp_nav_menu([
          'theme_location' => 'about',
          'menu_id'        => 'about-menu',
          'container'			 => ''
        ]);
      ?>
  </div>

  <div class="links-wrap mobile-links-wrap">
    <?php
      $links = get_field('menu_about', 'options');
      get_template_part('template-parts/mega-menu/links-mobile', '', ['links' => $links]);
    ?>
  </div>

</div>