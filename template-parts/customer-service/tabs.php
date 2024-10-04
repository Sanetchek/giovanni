<?php $tabs = $args['tabs']; ?>

<?php if ($tabs) : ?>
  <ul class="tabs-list">
    <?php foreach ($tabs as $key => $tab) : ?>
      <li class="tab-item <?= $key===0 ? 'active' : ''; ?>">
        <div class="tab-btn">
          <span><?= $tab['title'] ?></span>
          <svg class='icon-chevron-down' width='24' height='24'>
            <use href='<?= assets('img/sprite.svg#icon-chevron-down') ?>'></use>
          </svg>
        </div>
        <div class="tab-content"><?= $tab['description'] ?></div>
      </li>
    <?php endforeach ?>
  </ul>
<?php endif ?>