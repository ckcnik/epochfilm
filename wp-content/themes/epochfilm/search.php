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
			<a class="watch-button" href="<?php the_permalink(); ?>">Смотреть</a>
		</li>
	<?php endwhile; ?>
</ul>

<?php if ($wp_query->max_num_pages > 1) : ?>
	<nav class="post-nav">
		<?php pagingCreate(); ?>
	</nav>
<?php endif; ?>