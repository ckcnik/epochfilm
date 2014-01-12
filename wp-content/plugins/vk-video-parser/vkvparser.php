<?php
/*
Plugin Name: VK Video Parser
Description: Плагин парсит видео из VK
Version: 1.0
*/

define('VKVP_INDEX_TITLE', __('VK Video Parser'));
define('VKVP_SHORT_TITLE', __('VKV Parser'));
define('VKVP_LIST_TITLE', __('List of movies'));
define('VKVP_SETTINGS_TITLE', __('Settings'));

define('COOKIES_FILE_PATH', __DIR__ . '/cookies.txt');
define('USERAGENT', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/31.0.1650.57 Safari/537.36');

add_action('admin_menu', 'vkvpInit');

function vkvpInit () {
    add_menu_page(VKVP_INDEX_TITLE, VKVP_SHORT_TITLE, 'level_8', __DIR__ . '/vkvp_index.php');
	add_submenu_page(__DIR__ . '/vkvp_index.php', VKVP_INDEX_TITLE . ' - ' . VKVP_LIST_TITLE, VKVP_LIST_TITLE, 'level_8', __DIR__ . '/vkvp_list.php');
	add_submenu_page(__DIR__ . '/vkvp_index.php', VKVP_INDEX_TITLE . ' - ' . VKVP_SETTINGS_TITLE, VKVP_SETTINGS_TITLE, 'level_8', __DIR__ . '/vkvp_settings.php');

    add_action( 'admin_init', 'vkvpRegisterSettings' );
}

function vkvpRegisterSettings() {
    register_setting( 'vkvp-settings-group', 'vkvp_client_id', 'intval' );
    register_setting( 'vkvp-settings-group', 'vkvp_client_secret', 'strval' );
    register_setting( 'vkvp-settings-group', 'vkvp_access_token', 'strval' );
    register_setting( 'vkvp-settings-group', 'vkvp_expires_in', 'intval' );
    register_setting( 'vkvp-settings-group', 'vkvp_kinopisk_login', 'strval' );
    register_setting( 'vkvp-settings-group', 'vkvp_kinopisk_password', 'strval' );
    register_setting( 'vkvp-settings-group', 'vkvp_imdb_login', 'strval' );
    register_setting( 'vkvp-settings-group', 'vkvp_imdb_password', 'strval' );
}

if (!function_exists('mb_ucfirst') && extension_loaded('mbstring')) {
    function mb_ucfirst($str, $encoding = 'UTF-8') {
        $str = mb_ereg_replace('^[\ ]+', '', $str);
        $str = mb_strtoupper(mb_substr($str, 0, 1, $encoding), $encoding).
            mb_substr($str, 1, mb_strlen($str), $encoding);
        return $str;
    }
}

function rus2translit($string) {
    $converter = array(
        'а' => 'a',   'б' => 'b',   'в' => 'v',
        'г' => 'g',   'д' => 'd',   'е' => 'e',
        'ё' => 'e',   'ж' => 'zh',  'з' => 'z',
        'и' => 'i',   'й' => 'y',   'к' => 'k',
        'л' => 'l',   'м' => 'm',   'н' => 'n',
        'о' => 'o',   'п' => 'p',   'р' => 'r',
        'с' => 's',   'т' => 't',   'у' => 'u',
        'ф' => 'f',   'х' => 'h',   'ц' => 'c',
        'ч' => 'ch',  'ш' => 'sh',  'щ' => 'sch',
        'ь' => "'",   'ы' => 'y',   'ъ' => "'",
        'э' => 'e',   'ю' => 'yu',  'я' => 'ya',
        'А' => 'A',   'Б' => 'B',   'В' => 'V',
        'Г' => 'G',   'Д' => 'D',   'Е' => 'E',
        'Ё' => 'E',   'Ж' => 'Zh',  'З' => 'Z',
        'И' => 'I',   'Й' => 'Y',   'К' => 'K',
        'Л' => 'L',   'М' => 'M',   'Н' => 'N',
        'О' => 'O',   'П' => 'P',   'Р' => 'R',
        'С' => 'S',   'Т' => 'T',   'У' => 'U',
        'Ф' => 'F',   'Х' => 'H',   'Ц' => 'C',
        'Ч' => 'Ch',  'Ш' => 'Sh',  'Щ' => 'Sch',
        'Ь' => "'",   'Ы' => 'Y',   'Ъ' => "'",
        'Э' => 'E',   'Ю' => 'Yu',  'Я' => 'Ya', ' ' => '-',
    );
    return strtr($string, $converter);
}

function getCategoryId($catName, $parentCat) {
    $args = array(
        'type'          => 'post',
        'child_of'      => 0,
        'parent'        => $parentCat,
        'orderby'       => 'name',
        'order'         => 'ASC',
        'hide_empty'    => 0,
        'hierarchical'  => 1,
        'taxonomy'      => 'category',
        'pad_counts'    => false
    );
    $categories = get_categories($args);

    $catId = 0;
    foreach($categories as $category) {
        if ($category->name == $catName) {
            $catId = $category->term_id;
        }
    }

    if (!$catId) {
        $catId = wp_insert_category(array(
            'cat_name' => $catName,
            'category_nicename' => rus2translit(strtolower($catName)),
            'category_parent' => $parentCat,
        ));
    }

    return $catId;
}

/**
 * Функция авторизации на kinopoisk.ru
 * @return bool|mixed
 */
function curlKinopoiskAuth() {
	$response = false;

	if ($curl = curl_init()) {
		curl_setopt($curl, CURLOPT_URL, "http://www.kinopoisk.ru/login/");
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($curl, CURLOPT_USERAGENT, USERAGENT);
		curl_setopt($curl, CURLOPT_AUTOREFERER, true);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, array(
			'shop_user[login]' => get_option('vkvp_kinopisk_login'),
			'shop_user[pass]' => get_option('vkvp_kinopisk_password'),
			'shop_user[mem]' => 'on',
			'auth' => '',
		));
		curl_setopt($curl, CURLOPT_COOKIEJAR, COOKIES_FILE_PATH);
		curl_setopt($curl, CURLOPT_COOKIEFILE, COOKIES_FILE_PATH);
		$response = curl_exec($curl);
		curl_close($curl);
	}

	return $response;
}

function getMovieList($url, $endPage = 0) {
	require_once(ABSPATH . 'lib/phpQuery.php');

	if ($curl = curl_init()) {
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($curl, CURLOPT_USERAGENT, USERAGENT);
		curl_setopt($curl, CURLOPT_AUTOREFERER, true);
		curl_setopt($curl, CURLOPT_COOKIEJAR, COOKIES_FILE_PATH);
		curl_setopt($curl, CURLOPT_COOKIEFILE, COOKIES_FILE_PATH);
		$kinopoiskResultPage = curl_exec($curl);
		curl_close($curl);
		$kinopoiskResultPage = iconv("CP1251", "UTF-8", $kinopoiskResultPage);

		$document = phpQuery::newDocumentHTML($kinopoiskResultPage, 'cp1251');
		$pq = pq($document);

		$filmsData = array();

		$films = $pq->find('#itemList div._NO_HIGHLIGHT_');
		foreach($films as $film) {
			$ruName = pq($film)->find('div.info .name a')->text();
			$ruName = trim(preg_replace('/\(видео\)|\(ТВ\)|\(сериал\)/i', '', $ruName));
			$engNameAndYear = trim(pq($film)->find('div.info .name span')->clone()->children()->remove()->end()->text());

			$filmsData[] = $ruName . ' / ' . $engNameAndYear . "\n";
		}

		$file = fopen(__DIR__ . '/list.txt', 'a+');
		foreach($filmsData as $film) {
			fwrite($file, $film);
		}
		fclose($file);

		$nextPage = 0;
		$currentPage = (int) $pq->find('#results .navigator .list li span')->text();

		if (!$endPage || $currentPage != $endPage) {
			$anotherPages = $pq->find('#results .navigator .list li a');
			foreach($anotherPages as $page) {
				$page = pq($page);
				$pageText = (int) $page->text();
				if ($pageText == $currentPage + 1) {
					$nextPage = $page->attr('href');
					break;
				}
			}

			if ($nextPage) {
				$nextPage = 'http://www.kinopoisk.ru' . $nextPage;
				getMovieList($nextPage, $endPage);
			}
		}
	}
}