<div id="about_menu" class="mega-menu">
  <div class="page-mob">
    <button class="mega-menu-header">
      <?= __('ABOUT', 'giovanni') ?>
    </button>
  </div>

  <div class="mega-menu-container">
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

    <div class="links-wrap">
      <?php
        $links = get_field('menu_about', 'options');
        get_template_part('template-parts/mega-menu/links', '', ['links' => $links]);
      ?>
    </div>
  </div>
</div>