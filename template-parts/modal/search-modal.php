<div id="search_modal" class="search-modal">
  <div class="search-modal-close">
    <svg class='icon-close' width='24' height='24'>
      <use href='<?= assets('img/sprite.svg#icon-close') ?>'></use>
    </svg>
  </div>

  <?php get_template_part('template-parts/search/search', 'form'); ?>
</div>
