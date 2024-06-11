<form action="/" method="get" class="label-top search">
  <input type="text" name="s" id="search" value="<?php the_search_query(); ?>" />
  <label for="search"><?php _e('Search by keyword', 'giovanni') ?></label>
  <button class="btn btn__green search__btn"><?php _e('Search', 'giovanni') ?></button>
</form>
