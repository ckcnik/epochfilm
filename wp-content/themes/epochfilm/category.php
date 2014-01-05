<?php
	$cat = get_category_by_slug( $category_name );
?>
	<h3 class="widget-title">Найденное в категории <?= $cat->name?></h3>
<ul class="presentation">
	<?php while (have_posts()) : the_post(); ?>
		<?php get_template_part('templates/content', get_post_format()); ?>
	<?php endwhile; ?>
</ul>