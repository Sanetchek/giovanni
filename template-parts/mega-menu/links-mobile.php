<?php $links = $args['links']; ?>

<?php if ($links) : ?>
  <?php foreach ($links as $item) : ?>
    <?php if ($item['link']) : ?>
      <a href="<?= $item['link'] ?>" class="link-item">
        <?php
        $data = [
          'thumb' => [424, 307],
        ];
        echo liteimage( $item['image'], $data );
        ?>

        <div class="links-mobile-column">
          <p class="link-item-title"><?= $item['title'] ?></p>
          <p><?= $item['description'] ?></p>
          <p>
            <svg class='icon-arrow-left' width='24' height='24'>
              <use href='<?= assets('img/sprite.svg#icon-arrow-left') ?>'></use>
            </svg>
          </p>
        </div>
      </a>
    <?php endif ?>
  <?php endforeach ?>
<?php endif ?>