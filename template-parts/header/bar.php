<?php $bar = get_field('header_bar', 'option'); ?>
<?php if ($bar) : ?>
  <?php $link = get_field('header_bar_link', 'option') ?>
  <?php if ($link) : ?>
    <a href="<?= $link ?>" class="header-bar-link">
  <?php endif ?>

    <div class="header-bar">
      <?= __($bar, 'giovanni'); ?>
    </div>

  <?php if ($link) : ?>
    </a>
  <?php endif ?>
<?php endif ?>