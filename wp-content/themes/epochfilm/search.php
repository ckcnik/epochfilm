<?php if (!have_posts()) : ?>
	<div class="alert alert-warning">
		<?php _e('Sorry, no results were found.', 'roots'); ?>
	</div>
<?php endif; ?>

<h3 class="widget-title">Найденное на сайте</h3>
<ul id="maim-list-films">
	<?php while (have_posts()) : the_post(); ?>
		<?php get_template_part('templates/content', get_post_format()); ?>
	<?php endwhile; ?>
</ul>