<?php while (have_posts()) : the_post(); ?>
	<?php
	setPostViews(get_the_ID());
	$custom_fields = get_post_custom($id);
	?>
	<article <?php post_class(); ?>>
		<div class="fp-screen">
			<div class="playing details-header">Воспроизведение видео: <h2><?php the_title(); ?></h2></div>
			<iframe src="<?= $custom_fields['vk_player_url'][0] ?>" frameborder="0"></iframe>
		</div>
		<div class="details">
			<div id="left-column-details" class="left-column-details">
				<div class="details-header">
					<h3>Оригинальное название: <?= $custom_fields['eng_name'][0] ?></h3>
					<span><?= isset($postViews) ? $postViews : '';?></span>
					<div id="socials">
						<script type="text/javascript">(function() {
								if (window.pluso)if (typeof window.pluso.start == "function") return;
								if (window.ifpluso==undefined) { window.ifpluso = 1;
									var d = document, s = d.createElement('script'), g = 'getElementsByTagName';
									s.type = 'text/javascript'; s.charset='UTF-8'; s.async = true;
									s.src = ('https:' == window.location.protocol ? 'https' : 'http')  + '://share.pluso.ru/pluso-like.js';
									var h=d[g]('body')[0];
									h.appendChild(s);
								}})();</script>
						<div class="pluso" data-background="transparent" data-options="small,round,line,horizontal,counter,theme=04" data-services="vkontakte,odnoklassniki,twitter,google,facebook"></div>
					</div>
				</div>
				<div class="content-wrapper-details">
					<div class="details-poster">
						<img width="183" height="259" src="<?= $custom_fields['image_path'][0] ?>"
							 alt="<?php the_title(); ?>" class="box-shadow">
					</div>
					<div class="description-film-container noselect">
						<div class="header-comments">
							<h1><?php the_title(); ?></h1>
							<div id="ratingImDb"><?= $custom_fields['rating_plugin_HTML'][0] ?></div>
						</div>
						<div class="film-history">
							<table id="">
								<thead></thead>
								<tbody>
								<tr>
									<td>Год</td>
									<td><?= getCategory($id, 19); ?></td>
								</tr>
								<tr>
									<td>Страна</td>
									<td><?= getCategory($id, 18); ?></td>
								</tr>
								<tr>
									<td>Жанр</td>
									<td><?= getCategory($id, 3); ?></td>
								</tr>
								<tr>
									<td>Режиссер</td>
									<td><?= getCategory($id, 21); ?></td>
								</tr>
								<tr>
									<td>В главных ролях</td>
									<td><?= getCategory($id, 20); ?></td>
								</tr>
								<tr>
									<td>Возраст</td>
									<td><?= $custom_fields['age'][0] ?></td>
								</tr>
								<tr>
									<td>Время воспроизведения</td>
									<td><?= $custom_fields['time'][0] ?></td>
								</tr>
								<tr>
									<td>Опубликовано</td>
									<td><?php get_template_part('templates/entry-meta'); ?></td>
								</tr>
								</tbody>
							</table>
						</div>
						<div id="film-description" class="film-description">
							<div id="film-content">
								<?php the_content(); ?>
							</div>
						</div>
						<label id="expand-film-description" class="show-more">Show More</label>
					</div>
					<div class="clear"></div>
				</div>
			</div>
			<div id="right-column-details" class="right-column-details">
				<?php $relatedPosts = getRelatedPosts($id); ?>
				<div class="details-header"><h3>Related Movies</h3></div>
				<div class="wrapper-related-movies">
					<?php foreach ($relatedPosts as $itemPost ) : ?>
					<div class="related-movie">
						<a href="<?= $itemPost['permalink']?>">
							<img alt="4 Weeks of Sunshine" src="<?= $itemPost['image_path']?>" width="90"
								 height="135" class="box-shadow">
						</a>
					</div>
					<?php endforeach; ?>
					<div class="clear"></div>
				</div>
			</div>
			<div class="clear"></div>
		</div>
		<div id="comments-to-film" class="box-shadow">
			<div class="wrapper-comments">
				<!-- Put this div tag to the place, where the Comments block will be -->
				<div id="vk_comments"></div>
				<script type="text/javascript">
					VK.Widgets.Comments("vk_comments", {limit: 10, width: "1120", attach: "*"});
				</script>
			</div>
		</div>
		<footer>
			<?php wp_link_pages(array('before' => '<nav class="page-nav"><p>' . __('Pages:', 'roots'), 'after' => '</p></nav>')); ?>
		</footer>
	</article>
<?php endwhile; ?>
