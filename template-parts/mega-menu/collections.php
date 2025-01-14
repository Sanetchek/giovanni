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

        <?php $btns = get_field('menu_collections_btns', 'options'); ?>
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
      <?php
        $links = get_field('menu_collections', 'options');
        get_template_part('template-parts/mega-menu/links', '', ['links' => $links]);
      ?>
    </div>
  </div>
</div>


<div id="collections_menu_mobile" class="mobile-collections mobile-dropdown-menu">

  <div class="page-mob">
    <button class="mega-menu-header">
      <?= __('אוספים', 'giovanni') ?>
    </button>
  </div>

  <div class="menu-1">
    <p class="menu-title"><?= __('ערכות נושא', 'giovanni') ?></p>
    <?php
      wp_nav_menu([
        'theme_location' => 'themes',
        'menu_id'        => 'themes-menu',
        'container'			 => ''
      ]);
    ?>

    <p class="menu-title"><?= __('מומלצים', 'giovanni') ?></p>
    <?php
      wp_nav_menu([
        'theme_location' => 'featured',
        'menu_id'        => 'featured-menu',
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
      $links = get_field('menu_collections', 'options');
      get_template_part('template-parts/mega-menu/links-mobile', '', ['links' => $links]);
    ?>
  </div>

</div>