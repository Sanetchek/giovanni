<form action="/" method="get" id="header_form_search" class="header-form-search">
  <div class="header-form-container">
    <input type="text" name="s" id="header_search" placeholder="<?= __('מה אתה מחפש?', 'giovanni') ?>">
    <div class="header-form-btns">
      <div class="header-form-btns-container">
        <button type="button" id="header_form_clear">
          <svg class='icon-close' width='10' height='10'>
            <use href='<?= assets('img/sprite.svg#icon-close') ?>'></use>
          </svg>
        </button>
        <button type="submit" id="header_form_submit">
          <svg class='icon-arrow-left' width='22' height='10'>
            <use href='<?= assets('img/sprite.svg#icon-arrow-left') ?>'></use>
          </svg>
        </button>
      </div>
    </div>
  </div>
</form>
<div id="search_results"></div>