<?php
$custom_fields = get_post_custom($id);
?>
<li>
	<article <?php post_class(); ?>>
		<div class="main-poster">
			<a href="<?php the_permalink(); ?>">
				<img width="183" height="259" src="<?= $custom_fields['image_path'][0] ?>">
			</a>
		</div>
	<header>
		<h5 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
		<time class="year-film">Год</time>
	</header>

	</article>
</li>
