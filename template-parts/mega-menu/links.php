<?php $links = $args['links']; ?>

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