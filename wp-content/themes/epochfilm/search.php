<h3 class=" headers"><?= roots_title()?></h3>
	<ul id="search-page" >
	<?php if (!have_posts()) : ?>
		<?php _e('Sorry, no results were found.', 'roots'); ?>
	<?php endif; ?>
	<?php while (have_posts()) : the_post(); ?>
		<?php
		$custom_fields = get_post_custom($id);
		?>
		<li >
			<a class="search-poster" href="<?php the_permalink(); ?>">
				<img src="<?= $custom_fields['image_path'][0] ?>">
			</a>
			<div class="film-info">
				<header >
					<h5 class="headers"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
					<div class="year-film"><?= getCategory($id, 19); ?>, <?= getCategory($id, 18); ?></div>
				</header>
				<div id="film-content">
					<?= !empty(get_the_content()) ? get_the_content() : "<div class='error'>нет описания к фильму</div>"; ?>
				</div>
			</div>
		</li>
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