<?php
$block = $args['block'];
$columns = $block['columns'];
$use_numeration = $block['use_numeration'];
$rows = $block['row'];

switch($columns) {
  case 'two':
    $column_class = 'two-in-a-row';
    break;
  case 'three':
    $column_class = 'three-in-a-row';
    break;
  default:
    $column_class = 'one-in-a-row';
    break;
}
?>

<div class="article-list">

  <div class="container center-mode-box">
    <ul class="article-list-wrap">
      <?php foreach ($rows as $key => $item) : ?>
        <?php $padding = ($use_numeration) ? 'pad' : ''; ?>

        <li class="article-list-item <?= $column_class ?>">
          <?php if ($use_numeration) : ?>
            <?php $key += 1; ?>
            <span class="list-item-number"><?= $key = $key < 10 ? '0' . $key : $key; ?></span>
          <?php endif ?>

          <div class="list-item-wrap <?= $padding ?>">
            <?php if ($item['image']) : ?>
              <?php
                if ($item['title'] || $item['text'] || $item['link']) {
                  if ($columns !== 'one') {
                    $full = 'full-content';
                  } else {
                    $full = '';
                  }
                } else {
                  $full = 'full-content';
                }

                $thumb = ($item['title'] || $item['text'] || $item['link'] || $columns !== 'one') ? [768, 0] : [1780, 0];  ?>
              <div class="list-item-side list-item-image <?= $full ?>">
                <?php
                $data = [
                  'thumb' => $thumb,
                ];
                echo liteimage( $item['image'], $data );
                ?>
              </div>
            <?php endif ?>

            <?php $full = !$item['image'] ? 'full-content' : ''; ?>
            <?php if ($item['title'] || $item['text'] || $item['link']) : ?>
              <div class="list-item-side list-item-content <?= $full ?>">
                <h2 class="list-title"><?= $item['title'] ?></h2>
                <div class="list-description"><?= $item['text'] ?></div>

                <?php if ($item['link']) : ?>
                  <a href="<?= $item['link'] ?>" class="btn btn-hover list-link" target="_blank" rel="noopener noreferrer"><?= $item['link_label'] ?></a>
                <?php endif ?>
              </div>
            <?php endif ?>

          </div>
        </li>

      <?php endforeach ?>
    </ul>
  </div>

</div>