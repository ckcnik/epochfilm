<?php
/*
Template Name: Films Template
*/
?>
<?php
$filmsPageId = 4;
$cartoonsPageId = 2;
$mainPageId = 28;
$currentPage = $wp_query->get_queried_object_id();
?>
<div class="films-wrapper">
	<?php if ($currentPage != $mainPageId) : ?>
	<div class="sub-menu box-shadow">
	<ul>
		<?php
		if ($currentPage == $filmsPageId) {
			$parentCategoryId = '3';
//			$notInCategory = '194,193';
		} elseif($currentPage == $cartoonsPageId) {
			$parentCategoryId = '610';
//			$haveInCategory = '194';
		}

		$args = array(
			'type'			=> 'post',
			'child_of'		=> 0,
			'parent'		=> $parentCategoryId,
			'orderby'		=> 'name',
			'order'			=> 'ASC',
			'hide_empty'	=> 0,
			'hierarchical'	=> 1,
//			'exclude'		=> isset($notInCategory) ? $notInCategory : '194',
			'taxonomy'		=> 'category',
			'pad_counts'	=> false
		);
		$genreCategories = get_categories( $args )
		?>
		<?php foreach ($genreCategories as $catObj):?>
			<li><span class="glyphicon glyphicon-chevron-right"></span><a href="<?= get_category_link( $catObj->cat_ID)?>"><?= $catObj->name?></a></li>
		<?php endforeach;?>
	</ul>
	</div>
	<?php endif; ?>
	<div class="presentation">
		<h3 class="headers">Самое просматриваемое</h3>
		<div class="jcarousel-wrapper">
			<div class="jcarousel">
				<ul class="most-popular box-shadow">
					<?php
					$args = array(
						'posts_per_page' 	=> countFilmsInRow(),
						'category__not_in'	=> isset($notInCategory) ? $notInCategory : '',
						'category__in'		=> isset($haveInCategory) ? $haveInCategory : '',
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
			<a href="#" class="jcarousel-control-prev">&lsaquo;</a>
			<a href="#" class="jcarousel-control-next">&rsaquo;</a>
		</div>
	</div>

	<h3 class="widget-title headers">Новое на сайте</h3>
	<ul class="presentation">
		<?php
		$args = array(
			'category__not_in'	=> isset($notInCategory) ? $notInCategory : '',
			'category__in'		=> isset($haveInCategory) ? $haveInCategory : '',
			'post_type'			=> 'post',
			'paged' => (get_query_var('paged')) ? get_query_var('paged') : $page,
		);
		query_posts($args);
		while (have_posts()) : the_post(); ?>
			<?php get_template_part('templates/content', get_post_format()); ?>
		<?php endwhile; ?>
	</ul>

	<?php if ($wp_query->max_num_pages > 1) : ?>
		<nav class="post-nav">
			<?php pagingCreate(); ?>
		</nav>
	<?php endif; ?>
</div>