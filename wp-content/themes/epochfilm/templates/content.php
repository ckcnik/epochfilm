<?php
$custom_fields = get_post_custom($id);
?>
<li class="new-film">
	<article <?php post_class(); ?>>
		<div>
			<header class="video-title">
				<h5><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
<!--				<time class="year-film">--><?//= getCategory($id, 19); ?><!--</time>-->
			</header>
			<div class="main-poster">
				<a href="<?php the_permalink(); ?>">
					<img src="<?= $custom_fields['image_path'][0] ?>">
					<span class="start-play"></span>
				</a>
			</div>
		</div>
	</article>
</li>
