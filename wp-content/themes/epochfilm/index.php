<div class="presentation">
	<h3 class="widget-title">Самое просматриваемое</h3>
	<ul>
		<?php
		$args = array(
			'posts_per_page'	=> countFilmsInRow(),
			'meta_key'			=> 'post_views_count',
			'orderby'			=> 'meta_value_num',
			'order'				=> 'DESC'
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

<h3 class="widget-title">Новое на сайте</h3>
<ul class="presentation">
	<?php while (have_posts()) : the_post(); ?>
		<?php get_template_part('templates/content', get_post_format()); ?>
	<?php endwhile; ?>
</ul>

<?php if ($wp_query->max_num_pages > 1) : ?>
	<nav class="post-nav">
		<ul class="pager">
			<li class="previous"><?php next_posts_link(__('&larr; Older posts', 'roots')); ?></li>
			<li class="next"><?php previous_posts_link(__('Newer posts &rarr;', 'roots')); ?></li>
		</ul>
	</nav>
<?php endif; ?>
