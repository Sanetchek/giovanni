<div id="charms_menu" class="mega-menu">
  <div class="page-mob">
    <button class="mega-menu-header">
      <?= __('CHARMS', 'giovanni') ?>
    </button>
  </div>

  <div class="mega-menu-container">
    <div class="menu-side">
      <div class="menu-wrap">
        <div class="menu-1">
          <!-- <p class="menu-title"><?= __('נשים', 'giovanni') ?></p> -->

          <?php
            wp_nav_menu([
              'theme_location' => 'charms',
              'menu_id'        => 'charms-menu',
              'container'			 => ''
            ]);
          ?>
        </div>

        <div class="menu-2">
           <!-- <p class="menu-title"><?= __('גברים', 'giovanni') ?></p>-->

          <?php
            wp_nav_menu([
              'theme_location' => 'charms-2',
              'menu_id'        => 'charms-2',
              'container'			 => ''
            ]);
          ?>
        </div>
      </div>

      <?php $btns = get_field('menu_charms_btns', 'options'); ?>
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
        $links = get_field('menu_charms', 'options');
        get_template_part('template-parts/mega-menu/links', '', ['links' => $links]);
      ?>
    </div>
  </div>
</div>


<div id="charms_menu_mobile" class="mobile-charms mobile-dropdown-menu">

  <div class="page-mob">
    <button class="mega-menu-header">
      <?= __('CHARMS', 'giovanni') ?>
    </button>
  </div>

  <div class="menu-1">
      <?php
        wp_nav_menu([
          'theme_location' => 'charms',
          'menu_id'        => 'charms-menu',
          'container'			 => ''
        ]);
      ?>
      <?php
        wp_nav_menu([
          'theme_location' => 'charms-2',
          'menu_id'        => 'charms-2',
          'container'			 => ''
        ]);
      ?>
  </div>

  <div class="links-wrap mobile-links-wrap">
    <?php
      $links = get_field('menu_charms', 'options');
      get_template_part('template-parts/mega-menu/links-mobile', '', ['links' => $links]);
    ?>
  </div>

</div>