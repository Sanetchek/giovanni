<div class="user-actions-side">
  <span class="header-action header-search">
    <svg class='icon-search' width='24' height='24'>
      <use href='<?= assets('img/sprite.svg#icon-search') ?>'></use>
    </svg>
  </span>

  <span class="header-action header-user">
    <svg class='icon-user' width='24' height='24'>
      <use href='<?= assets('img/sprite.svg#icon-user') ?>'></use>
    </svg>
  </span>

  <a href="#" class="header-action header-like">
    <span class="header-count">5</span>
    <svg class='icon-like' width='24' height='24'>
      <use href='<?= assets('img/sprite.svg#icon-like') ?>'></use>
    </svg>
  </a>

  <span class="header-action header-cart">
    <span class="header-count">1</span>
    <svg class='icon-bag' width='24' height='24'>
      <use href='<?= assets('img/sprite.svg#icon-bag') ?>'></use>
    </svg>
  </span>
</div>

<div class="page-mob">
  <?php show_burger(); ?>
</div>
