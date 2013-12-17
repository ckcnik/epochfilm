<?php
/*
Template Name: Films Template
*/
?>
<div class="sub-menu">
<ul>
	<?php
	$notInCategory = '194';
	$args = array(
		'type'			=> 'post',
		'child_of'		=> 0,
		'parent'		=> '3',
		'orderby'		=> 'name',
		'order'			=> 'ASC',
		'hide_empty'	=> 1,
		'hierarchical'	=> 1,
		'exclude'		=> $notInCategory,
		'taxonomy'		=> 'category',
		'pad_counts'	=> false
	);
	$genreCategories = get_categories( $args ) ?>
	<?php foreach ($genreCategories as $catObj):?>
		<li><a href="<?= get_category_link( $catObj->cat_ID)?>"><?= $catObj->name?></a></li>
	<?php endforeach;?>
</ul>
</div>

<div class="presentation">
	<h3 class="headers">Самое просматриваемое</h3>
	<ul>
		<?php
		$args = array(
			'posts_per_page' 		=> countFilmsInRow(),
			'category__not_in'	=> $notInCategory,
			'meta_key' 			=> 'post_views_count',
			'orderby' 			=> 'meta_value_num',
			'order' 			=> 'DESC',
		);
		query_posts($args);
		while ( have_posts() ) : the_post();
			?>
			<?php
			$custom_fields = get_post_custom($id);
			?>
			<li>
				<article <?php post_class(); ?>>
					<div class="main-poster">
						<a href="<?php the_permalink(); ?>">
							<img src="<?= $custom_fields['image_path'][0] ?>">
							<span class="start-play"></span>
						</a>
					</div>

				</article>
			</li>
		<?php endwhile; wp_reset_query(); ?>
	</ul>
</div>

<h3 class="widget-title headers">Новое на сайте</h3>
<ul class="presentation">
	<?php
	$args = array(
		'category__not_in'	=> $notInCategory,
		'post_type'			=> 'post'
	);
	query_posts($args);
	while (have_posts()) : the_post(); ?>
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
