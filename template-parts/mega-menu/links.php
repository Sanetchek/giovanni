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

        <p class="link-item-title"><?= $item['title'] ?></p>
        <p><?= $item['description'] ?></p>
        <div class="arrow-animation-container">
        </div>
      </a>
    <?php endif ?>
  <?php endforeach ?>
<?php endif ?>