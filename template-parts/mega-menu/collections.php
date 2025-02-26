<div id="collection_menu" class="mega-menu">
  <?php $mob_title = get_field('mob_collection_title', 'options') ?>
  <?php if ($mob_title) : ?>
    <div class="page-mob">
      <button class="mega-menu-header"><?= $mob_title ?></button>
    </div>
  <?php endif ?>

  <div class="mega-menu-container">
    <div class="menu-side">
      <div class="menu-wrap">
        <div class="menu-1">
          <?php $title1 = get_field('menu_collection_title_1', 'options'); ?>
          <?php if ($title1) : ?>
            <p class="menu-title"><?= $title1 ?></p>
          <?php endif ?>

          <?php
            wp_nav_menu([
              'theme_location' => 'themes',
              'menu_id'        => 'themes-menu',
              'container'			 => ''
            ]);
          ?>
        </div>

        <div class="menu-2">
          <?php $title2 = get_field('menu_collection_title_2', 'options'); ?>
          <?php if ($title2) : ?>
            <p class="menu-title"><?= $title2 ?></p>
          <?php endif ?>

          <?php
            wp_nav_menu([
              'theme_location' => 'featured',
              'menu_id'        => 'featured-menu',
              'container'			 => ''
            ]);
          ?>
        </div>
      </div>

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

    <div class="links-wrap">
      <?php
        $links = get_field('menu_collections', 'options');
        get_template_part('template-parts/mega-menu/links', '', ['links' => $links]);
      ?>
    </div>
  </div>
</div>


<div id="collections_menu_mobile" class="mobile-collections mobile-dropdown-menu">
  <?php if ($mob_title) : ?>
    <div class="page-mob">
      <button class="mega-menu-header"><?= $mob_title ?></button>
    </div>
  <?php endif ?>

  <div class="menu-1">
    <?php if ($title1) : ?>
      <p class="menu-title"><?= $title1 ?></p>
    <?php endif ?>

    <?php
      wp_nav_menu([
        'theme_location' => 'themes',
        'menu_id'        => 'themes-menu',
        'container'			 => ''
      ]);
    ?>

    <?php if ($title2) : ?>
      <p class="menu-title"><?= $title2 ?></p>
    <?php endif ?>
    <?php
      wp_nav_menu([
        'theme_location' => 'featured',
        'menu_id'        => 'featured-menu',
        'container'			 => ''
      ]);
    ?>

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

  <div class="links-wrap mobile-links-wrap">
    <?php
      get_template_part('template-parts/mega-menu/links-mobile', '', ['links' => $links]);
    ?>
  </div>

</div>