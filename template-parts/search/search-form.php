<form id="search_form_modal" class="search-form">
  <div class="search-form-wrap">
    <input type="text" name="s_modal" id="search_modal" class="search-form-input" placeholder="<?php _e('חפש לפי מילת מפתח', 'giovanni') ?>" />
    <button class="btn search-form-submit"><?php _e('חפש', 'giovanni') ?></button>
  </div>
</form>

<ul id="search_modal_content" class="search-modal-content"></ul>
<p class="search-modal-error"><?= __('אירעה שגיאה ', 'giovanni') ?></p>