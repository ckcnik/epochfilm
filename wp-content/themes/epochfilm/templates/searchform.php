<form role="search" method="get" class="search-form form-inline" action="<?php echo home_url('/'); ?>">
  <div class="input-group">
    <input type="search" value="<?php if (is_search()) { echo get_search_query(); } ?>" name="s" class="search-field form-control" placeholder="<?php _e('Search', 'roots'); ?> ">
    <label class="hide"><?php _e('Search for:', 'roots'); ?></label>
      <button type="submit" class="search-submit"></button>
	  <i class="icon-search"></i>
  </div>
</form>
