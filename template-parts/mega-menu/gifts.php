<div id="gifts_menu" class="mega-menu">
  <div class="page-mob">
    <button class="mega-menu-header">
      <?= __('מתנות', 'giovanni') ?>
    </button>
  </div>

  <div class="mega-menu-container">
    <div class="menu-side">
      <div class="menu-wrap">
        <div class="menu-1">
          <p class="menu-title"><?= __('מתנות עבור', 'giovanni') ?></p>

          <?php
            wp_nav_menu([
              'theme_location' => 'gifts',
              'menu_id'        => 'gifts-menu',
              'container'			 => ''
            ]);
          ?>
        </div>

        <div class="menu-2">
          <p class="menu-title"><?= __('מקרים', 'giovanni') ?></p>

          <?php
            wp_nav_menu([
              'theme_location' => 'occasions',
              'menu_id'        => 'occasions-menu',
              'container'			 => ''
            ]);
          ?>
        </div>
      </div>

      <?php $btns = get_field('menu_gifts_btns', 'options'); ?>
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
        $links = get_field('menu_gifts', 'options');
        get_template_part('template-parts/mega-menu/links', '', ['links' => $links]);
      ?>
    </div>
  </div>
</div>


<div id="gifts_menu_mobile" class="mobile-gifts mobile-dropdown-menu">

  <div class="page-mob">
    <button class="mega-menu-header">
      <?= __('מתנות', 'giovanni') ?>
    </button>
  </div>


      <div class="menu-1">
        <p class="menu-title"><?= __('מתנות עבור', 'giovanni') ?></p>

        <?php
          wp_nav_menu([
            'theme_location' => 'gifts',
            'menu_id'        => 'gifts-menu',
            'container'			 => ''
          ]);
        ?>

        <p class="menu-title"><?= __('מקרים', 'giovanni') ?></p>

        <?php
          wp_nav_menu([
            'theme_location' => 'occasions',
            'menu_id'        => 'occasions-menu',
            'container'			 => ''
          ]);
        ?>
      </div>



    <div class="links-wrap mobile-links-wrap">
    <?php
      $links = get_field('menu_gifts', 'options');
      get_template_part('template-parts/mega-menu/links-mobile', '', ['links' => $links]);
    ?>
  </div>


</div>