<?php

set_time_limit(0);

if (isset($_POST['url']) && $_POST['url']) {
	$url = $_POST['url'];
	$endPage = isset($_POST['end_page']) ? (int) $_POST['end_page'] : 0;

	// авторизация на kinopoisk
	curlKinopoiskAuth();

	// Парсинг списка
	getMovieList($url, $endPage);

	echo 'Парсинг завершен';
}
?>

<div class="wrap">
	<h2><?php echo VKVP_LIST_TITLE ?></h2>

	<form method="post" action="<?php echo admin_url( 'admin.php?page=vk-video-parser/vkvp_list.php', 'http' ) ?>">
		<table class="form-table">
			<tr valign="top">
				<th scope="row">URL:</th>
				<td><input style="width: 100%" type="text" name="url" value="http://www.kinopoisk.ru/top/navigator/m_act%5Begenre%5D/21/m_act%5Bnum_vote%5D/10/m_act%5Brating%5D/1:/m_act%5Bis_film%5D/on/m_act%5Bis_mult%5D/on/order/rating/#results"/></td>
			</tr>
			<tr valign="top">
				<th scope="row">Количество страниц:</th>
				<td><input style="width: 50px" type="text" name="end_page" value="150"/></td>
			</tr>
		</table>

		<p class="submit">
			<input type="submit" class="button-primary" value="Поехали" />
		</p>
	</form>

</div>