<?php
/*
Template Name: Cartoon Template
*/
?>
<div class="sub-menu">
	<ul>
		<?php
		$haveInCategory = '194';
		$args = array(
			'type'			=> 'post',
			'child_of'		=> 0,
			'parent'		=> '3',
			'orderby'		=> 'name',
			'order'			=> 'ASC',
			'hide_empty'	=> 1,
			'hierarchical'	=> 1,
			'include'		=> '193',
			'taxonomy'		=> 'category',
			'pad_counts'	=> false
		);
		$genreCategories = get_categories( $args ) ?>
		<?php foreach ($genreCategories as $catObj):?>
			<li><a href="<?= get_category_link( $catObj->cat_ID)?>"><?= $catObj->name?></a></li>
		<?php endforeach;?>
		<li><a href="#">наши</a></li>
		<li><a href="#">Союзультфильм</a></li>
		<li><a href="#">Зарубежные</a></li>
	</ul>
</div>

<div class="widget-popular">
	<h3 class="widget-title">Самые просматриваемые мультфильмы</h3>
	<ul>
		<?php
		$args = array(
			'posts_per_page' 	=> 6,
			'category__in'		=> $haveInCategory,
			'meta_key' 			=> 'post_views_count',
			'orderby' 			=> 'meta_value_num',
			'order' 			=> 'DESC',
		);
		query_posts($args);
		while ( have_posts() ) : the_post();
			?>
			<?php get_template_part('templates/content', get_post_format()); ?>
		<?php endwhile; wp_reset_query(); ?>
	</ul>
</div>

<h3 class="widget-title">Новые мультфильмы</h3>
<ul id="maim-list-films">
	<?php
	$args = array(
		'category__in'	=> $haveInCategory,
		'post_type'		=> 'post'
	);
	query_posts($args);
	while (have_posts()) : the_post(); ?>
		<?php get_template_part('templates/content', get_post_format()); ?>
	<?php endwhile; wp_reset_query(); ?>
</ul>

<?php if ($wp_query->max_num_pages > 1) : ?>
	<nav class="post-nav">
		<ul class="pager">
			<li class="previous"><?php next_posts_link(__('&larr; Older posts', 'roots')); ?></li>
			<li class="next"><?php previous_posts_link(__('Newer posts &rarr;', 'roots')); ?></li>
		</ul>
	</nav>
<?php endif; ?>
