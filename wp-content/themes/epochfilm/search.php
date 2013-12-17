<?php if (!have_posts()) : ?>
	<div class="alert alert-warning">
		<?php _e('Sorry, no results were found.', 'roots'); ?>
	</div>
<?php endif; ?>

<h3 class="widget-title headers">Найденное на сайте</h3>
<ul class="presentation">
	<?php while (have_posts()) : the_post(); ?>
		<?php get_template_part('templates/content', get_post_format()); ?>
	<?php endwhile; ?>
</ul>

<?php if ($wp_query->max_num_pages > 1) : ?>
	<nav class="post-nav">
		<?php
		global $wp_query;
		$big = 999999999; // need an unlikely integer
		$args = array(
			'base'			=> str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
			'format'		=> '/page/%#%/',
			'total'			=> $wp_query->max_num_pages,
			'current'		=> max(1, get_query_var('paged')),
			'show_all'		=> true,
			'prev_next'		=> True,
			'prev_text'		=> __('«'),
			'next_text'		=> __('»'),
			'type'			=> 'plain',
			'add_args'		=> False,
			'add_fragment'	=> ''
		); ?>
		<?php echo paginate_links($args); ?>
	</nav>
<?php endif; ?>