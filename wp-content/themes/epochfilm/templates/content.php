<?php
$custom_fields = get_post_custom($id);
?>
<li>
	<article <?php post_class(); ?>>
		<div class="main-poster">
			<a href="<?php the_permalink(); ?>">
				<img width="154" height="230" src="<?= $custom_fields['image_path'][0] ?>">
			</a>
		</div>
	<header>
		<p class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></p>
		<time class="year-film"><?= getCategory($id, 19); ?></time>
	</header>
	</article>
</li>
