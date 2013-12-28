<?php
$custom_fields = get_post_custom($id);
?>
<li class="new-film box-shadow">
	<article <?php post_class(); ?>>
		<div>
			<header class="video-title">
				<h5><a href="<?php the_permalink(); ?>"><?php the_title(); ?>
						<?php
							$category = get_the_category();
							echo "(".$category[0]->cat_name.")";
						?>
					</a>
				</h5>
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
