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
	<header>
		<p class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></p>
		<time class="year-film"><?= getCategory($id, 19); ?></time>
	</header>
	</article>
</li>
