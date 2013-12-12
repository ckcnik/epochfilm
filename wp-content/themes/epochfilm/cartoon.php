<?php
/*
Template Name: Cartoon Template
*/
?>
<div class="sub-menu">

</div>

<div class="widget-popular">
	<h3 class="widget-title">Самое просматриваемое</h3>
	<ul>
		<?php
		$args = array(
			'numberposts' 	=> 5,
			'meta_key' 		=> 'post_views_count',
			'orderby' 		=> 'meta_value_num',
			'order' 		=> 'DESC',
			'meta_query' => array(
				array('key' => 'movie_type', 'value'=>'cartoon'
				)
			)
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
		'meta_query' => array(
			array('key' => 'movie_type', 'value'=>'cartoon'
			)
		),
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
