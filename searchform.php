<form action="/" method="get" class="search-form search">
  <div class="search-form-wrap">
    <input type="text" name="s" id="search" class="search-form-input" value="<?php the_search_query(); ?>" />
    <button class="button button-hover white search-form-submit"><?php _e('חפש', 'giovanni') ?></button>
  </div>
</form>
