<?php while (have_posts()) : the_post(); ?>
	<?php
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
					<div class="description-film-container">
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
									<td><?= $custom_fields['time'][0] ?></td>
								</tr>
								<tr>
									<td>Страна</td>
									<td><?= $custom_fields['time'][0] ?></td>
								</tr>
								<tr>
									<td>Жанр</td>
									<td><?= $custom_fields['time'][0] ?></td>
								</tr>
								<tr>
									<td>Режиссер</td>
									<td><?= $custom_fields['time'][0] ?></td>
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
						<!--                          <a href="#" id="expand-film-description" class="show-more">Show More</a>-->
						<label id="expand-film-description" class="show-more">Show More</label>
					</div>
					<div class="clear"></div>
				</div>
			</div>
			<div id="right-column-details" class="right-column-details">
				<div class="details-header"><h3>Related Movies</h3></div>
				<div class="wrapper-related-movies">
					<div class="related-movie">
						<a href="http://www.viewster.com/movie/1055-10791-000/4-weeks-of-sunshine">
							<img alt="4 Weeks of Sunshine" src="/wp-content/uploads/2013/12/1919.jpg" width="90"
								 height="135" class="box-shadow">
						</a>
					</div>
					<div class="related-movie">
						<a href="http://www.viewster.com/movie/1055-10791-000/4-weeks-of-sunshine">
							<img alt="4 Weeks of Sunshine" src="/wp-content/uploads/2013/12/111.jpg" width="90"
								 height="135" class="box-shadow">
						</a>
					</div>
					<div class="related-movie">
						<a href="http://www.viewster.com/movie/1055-10791-000/4-weeks-of-sunshine">
							<img alt="4 Weeks of Sunshine" src="/wp-content/uploads/2013/12/2222.jpg" width="90"
								 height="135" class="box-shadow">
						</a>
					</div>
					<div class="related-movie">
						<a href="http://www.viewster.com/movie/1055-10791-000/4-weeks-of-sunshine">
							<img alt="4 Weeks of Sunshine" src="/wp-content/uploads/2013/12/333.jpg" width="90"
								 height="135" class="box-shadow">
						</a>
					</div>
					<div class="related-movie">
						<a href="http://www.viewster.com/movie/1055-10791-000/4-weeks-of-sunshine">
							<img alt="4 Weeks of Sunshine" src="/wp-content/uploads/2013/12/444.jpg" width="90"
								 height="135" class="box-shadow">
						</a>
					</div>
					<div class="related-movie">
						<a href="http://www.viewster.com/movie/1055-10791-000/4-weeks-of-sunshine">
							<img alt="4 Weeks of Sunshine" src="/wp-content/uploads/2013/12/666.jpg" width="90"
								 height="135" class="box-shadow">
						</a>
					</div>
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
