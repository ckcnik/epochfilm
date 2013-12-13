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

<div class="widget-popular">
	<h3 class="widget-title">Самое просматриваемое</h3>
	<ul>
		<?php
		$args = array(
			'posts_per_page' 		=> 6,
			'category__not_in'	=> $notInCategory,
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

<h3 class="widget-title">Новое на сайте</h3>
<ul id="maim-list-films">
	<?php
	$args = array(
		'category__not_in'	=> $notInCategory,
		'post_type'			=> 'post'
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
