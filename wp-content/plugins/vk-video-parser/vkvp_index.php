<?php

require_once(ABSPATH . 'lib/phpQuery.php');

$error = null;

$client_id = get_option('vkvp_client_id');
$client_secret = get_option('vkvp_client_secret');
$access_token = get_option('vkvp_access_token');
$expires_in = get_option('vkvp_expires_in');

$redirect_url = urlencode(admin_url( 'admin.php?page=vk-video-parser/vkvp_index.php', 'http' ));
$url_vk_authorize = "https://oauth.vk.com/authorize?client_id={$client_id}&scope=video&redirect_uri={$redirect_url}&response_type=code&v=5.4";

$log = '';

if (isset($_GET['code']) && time() > $expires_in) {
    $code = $_GET['code'];
    $url_vk_access_token = "https://oauth.vk.com/access_token?client_id={$client_id}&client_secret={$client_secret}&code={$code}&redirect_uri={$redirect_url}";
    $access_token_response = json_decode(file_get_contents($url_vk_access_token), true);

    if (!empty($access_token_response)) {
        if (isset($access_token_response['access_token'])) {
            $access_token = $access_token_response['access_token'];
            update_option('vkvp_access_token', $access_token);
            $expires_in = $access_token_response['expires_in'];
            update_option('vkvp_expires_in', time() + $expires_in);
            $expires_in = get_option('vkvp_expires_in');
        } elseif (isset($access_token_response['error'])) {
            $error = $access_token_response['error'] . ' - ' . $access_token_response['error_description'];
        }
    }
}

if (!$access_token) {
    $error = 'access_token не определен (<a href="' . $url_vk_authorize . '">получить access_token</a>)';
}

if (time() > $expires_in) {
    $error = 'access_token не действителен (<a href="' . $url_vk_authorize . '">получить access_token</a>)';
}

if (isset($_GET['error'])) {
    $error = $_GET['error'] . ' - ' . $_GET['error_description'];
}

if (!$error && isset($_POST['films'])) {
    $films = explode("\n", $_POST['films']);

    if ($films) {
        $log .= "Список получен\n";
        $url_vk_video = "https://api.vk.com/method/video.search?access_token={$access_token}";

        // авторизация на kinopoisk
		curlKinopoiskAuth();

        // авторизация на imdb
        if ($curl = curl_init()) {
            curl_setopt($curl, CURLOPT_URL, "https://secure.imdb.com/oauth/login?origurl=http://www.imdb.com/&show_imdb_panel=1");
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($curl, CURLOPT_USERAGENT, USERAGENT);
            curl_setopt($curl, CURLOPT_AUTOREFERER, true);
            curl_setopt($curl, CURLOPT_COOKIEJAR, COOKIES_FILE_PATH);
            curl_setopt($curl, CURLOPT_COOKIEFILE, COOKIES_FILE_PATH);
            $imdbLoginForm = curl_exec($curl);
            curl_close($curl);

            $document = phpQuery::newDocumentHTML($imdbLoginForm);
            $pq = pq($document);
            $hiddenFieldName = $pq->find('#imdb-login input[type="hidden"]')->attr('name');
            $hiddenFieldValue = $pq->find('#imdb-login input[type="hidden"]')->val();

            if ($curl = curl_init()) {
                curl_setopt($curl, CURLOPT_URL, "https://secure.imdb.com/oauth/login?origurl=http://www.imdb.com/&show_imdb_panel=1");
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($curl, CURLOPT_USERAGENT, USERAGENT);
                curl_setopt($curl, CURLOPT_AUTOREFERER, true);
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, array(
                    'login' => get_option('vkvp_imdb_login'),
                    'password' => get_option('vkvp_imdb_password'),
                    $hiddenFieldName => $hiddenFieldValue,
                ));
                curl_setopt($curl, CURLOPT_COOKIEJAR, COOKIES_FILE_PATH);
                curl_setopt($curl, CURLOPT_COOKIEFILE, COOKIES_FILE_PATH);
                $imdbLogin = curl_exec($curl);
                curl_close($curl);
            }
        }

        foreach($films as $film) {
            $film = trim($film);
            $log .= "\t'{$film}': ";

            $url_vk_video = "https://api.vk.com/method/video.search?access_token={$access_token}&q=" . urlencode($film) . "&sort=2&filters=long";
            $response = json_decode(file_get_contents($url_vk_video), true);
            $response = isset($response['response']) ? $response['response'] : array();

            if ($response) {
                $log .= count($response) . "\n";

                foreach($response as $video) {
                    if ($video) {
                        $player = file_get_contents($video['player']);
                        $document = phpQuery::newDocumentHTML($player);
                        $pq = pq($document);

                        if ($pq->find('object')->length) { // если найден плеер
                            $kinopoisk = array(
                                'id' => '',
                                'imagePath' => '',
                                'imageFileName' => '',
                                'params' => '',
                                'actors' => '',
                                'ruName' => '',
                                'engName' => '',
                                'rating' => '',
                            );
                            $imdb = array(
                                'storyline' => '',
                                'filmUrl' => '',
                                'ratingPluginHTML' => '',
                            );
                            $playerUrl = $video['player'];

                            // <kinopoisk>
                            if ($curl = curl_init()) {
                                curl_setopt($curl, CURLOPT_URL, "http://www.kinopoisk.ru/index.php?first=yes&kp_query=" . urlencode($film));
                                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                                curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
                                curl_setopt($curl, CURLOPT_USERAGENT, USERAGENT);
                                curl_setopt($curl, CURLOPT_AUTOREFERER, true);
                                curl_setopt($curl, CURLOPT_COOKIEJAR, COOKIES_FILE_PATH);
                                curl_setopt($curl, CURLOPT_COOKIEFILE, COOKIES_FILE_PATH);
                                $kinopoiskFilmPage = curl_exec($curl);
                                curl_close($curl);
                                $kinopoiskFilmPage = iconv("CP1251", "UTF-8", $kinopoiskFilmPage);

                                $document = phpQuery::newDocumentHTML($kinopoiskFilmPage, 'cp1251');
                                $pq = pq($document);

                                // получение и сохранение изображений
                                $img = $pq->find('#photoBlock .popupBigImage');
                                if ($img->length) {
                                    $imgOnclick = $img->attr('onclick');
                                    preg_match('/^.*?\(\'(.*film_big\/(.*))\'\).*$/', $imgOnclick, $match);
                                    $imgSrc = isset($match[1]) ? 'http://www.kinopoisk.ru' . $match[1] : '';
//                                    $imgSrc = str_replace('st', 'st-ua', $imgSrc);
                                    $imgFileName = $match[2];

                                    $kinopoiskFilmId = explode('.', $imgFileName);
                                    $kinopoisk['id'] = $kinopoiskFilmId[0];

                                    $uploadDir = wp_upload_dir();
                                    $kinopoisk['imagePath'] = '/wp-content/uploads' . $uploadDir['subdir'] . '/' . $imgFileName;
                                    $kinopoisk['imageFileName'] = $imgFileName;

                                    $uploadDir = $uploadDir['path'];

                                    $curl = curl_init();
                                    $file = fopen($uploadDir . '/' . $imgFileName, 'wb+');

                                    curl_setopt($curl, CURLOPT_URL, $imgSrc);
                                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                                    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
                                    curl_setopt($curl, CURLOPT_USERAGENT, USERAGENT);
                                    curl_setopt($curl, CURLOPT_AUTOREFERER, true);
                                    curl_setopt($curl, CURLOPT_COOKIEJAR, COOKIES_FILE_PATH);
                                    curl_setopt($curl, CURLOPT_COOKIEFILE, COOKIES_FILE_PATH);
                                    curl_setopt($curl, CURLOPT_FILE, $file);
                                    $image = curl_exec($curl);
                                    curl_close($curl);

                                    fclose($file);
                                    chmod($uploadDir . '/' . $imgFileName, 0664);
                                }

                                // Параметры фильма
                                $infoTableTr = $pq->find('#infoTable tr');
                                foreach($infoTableTr as $tr) {
                                    $kinopoisk['params'][trim(pq($tr)->find('td.type')->text())] = trim(pq($tr)->find('td')->eq(1)->text());
                                }

                                // Главные роли
                                $actorListLi = $pq->find('#actorList ul')->eq(0)->find('li');
                                foreach($actorListLi as $li) {
                                    $kinopoisk['actors'][] = trim(pq($li)->text());
                                }

                                // Название
                                $kinopoisk['ruName'] = trim($pq->find('#headerFilm h1')->clone()->children()->remove()->end()->text());
                                $kinopoisk['engName'] = trim($pq->find('#headerFilm span[itemprop="alternativeHeadline"]')->text());

                                // Код рейтинга
                                $kinopoisk['rating'] = "<img src='http://rating.kinopoisk.ru/{$kinopoisk['id']}.gif' border='0'/>";
                            }
                            // </kinopoisk>

                            // <imdb>
                            if ($curl = curl_init()) {
                                // Получение ссылки на странцу фильма
                                curl_setopt($curl, CURLOPT_URL, "http://www.imdb.com/find?s=tt&q=" . urlencode($kinopoisk['engName']));
                                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                                curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
                                curl_setopt($curl, CURLOPT_USERAGENT, USERAGENT);
                                curl_setopt($curl, CURLOPT_AUTOREFERER, true);
                                curl_setopt($curl, CURLOPT_COOKIEJAR, COOKIES_FILE_PATH);
                                curl_setopt($curl, CURLOPT_COOKIEFILE, COOKIES_FILE_PATH);
                                $imdbSearchPage = curl_exec($curl);
                                curl_close($curl);

                                $document = phpQuery::newDocumentHTML($imdbSearchPage);
                                $pq = pq($document);
                                $firstSearchResultUrl = $pq->find('table.findList tr')->eq(0)->find('.result_text a')->attr('href');
                                $imdbFilmUrl = 'http://www.imdb.com' . $firstSearchResultUrl;

                                if ($curl = curl_init()) {
                                    // Получение информации о фильме
                                    curl_setopt($curl, CURLOPT_URL, $imdbFilmUrl);
                                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                                    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
                                    curl_setopt($curl, CURLOPT_USERAGENT, USERAGENT);
                                    curl_setopt($curl, CURLOPT_AUTOREFERER, true);
                                    curl_setopt($curl, CURLOPT_COOKIEJAR, COOKIES_FILE_PATH);
                                    curl_setopt($curl, CURLOPT_COOKIEFILE, COOKIES_FILE_PATH);
                                    $imdbFilmPage = curl_exec($curl);
                                    curl_close($curl);

                                    $document = phpQuery::newDocumentHTML($imdbFilmPage);
                                    $pq = pq($document);

                                    // Сюжет
                                    $storyline = $pq->find('#titleStoryLine div[itemprop="description"]');
                                    pq($storyline)->find('.nobr')->remove();
                                    $imdb['storyline'] = trim(pq($storyline)->text());

                                    // URL фильма на imdb
                                    $imdb['filmUrl'] = $imdbFilmUrl;

                                    // Код рейтинга
                                    $imdb['ratingPluginHTML'] = trim($pq->find('#ratingPluginHTML textarea')->val());
                                }
                            }
                            // </imdb>

                            if ($kinopoisk['ruName']) {
                                $categoryIds = array();

                                // Категории жанров
                                $genres = explode('...', trim($kinopoisk['params']['жанр']));
                                $genres = explode(',', trim($genres[0]));

                                $genreParentCategory = 3;
                                if (in_array('мультфильм', $genres)) {
                                    $genreParentCategory = 610;
                                }

                                foreach($genres as $genre) {
                                    $genre = mb_ucfirst(strtolower(trim($genre)));
                                    $categoryIds[] = getCategoryId($genre, $genreParentCategory);
                                }

                                // Категории стран
                                $countries = explode(',', trim($kinopoisk['params']['страна']));
                                foreach($countries as $country) {
                                    $country = mb_ucfirst(strtolower(trim($country)));
                                    $categoryIds[] = getCategoryId($country, 18);
                                }

                                // Категории года
                                $year = trim($kinopoisk['params']['год']);
                                $categoryIds[] = getCategoryId($year, 19);

                                // Категории актеров
                                foreach($kinopoisk['actors'] as $actor) {
                                    $actor = trim($actor);
                                    if ($actor != '...') {
                                        $categoryIds[] = getCategoryId($actor, 20);
                                    }
                                }

                                // Категории режиссеров
                                $directors = explode(',', trim($kinopoisk['params']['режиссер']));
                                foreach($directors as $director) {
                                    $director = trim($director);
                                    if ($director != '...') {
                                        $categoryIds[] = getCategoryId($director, 21);
                                    }
                                }

                                $post = get_page_by_title($kinopoisk['ruName'], 'ARRAY_A', 'post');
                                if (!$post) {
                                    $postId = wp_insert_post(array(
                                        'comment_status' => 'closed',
                                        'ping_status' => 'closed',
                                        'post_category' => $categoryIds,
                                        'post_content' => $imdb['storyline'],
                                        'post_name' => rus2translit(strtolower($kinopoisk['ruName'])),
                                        'post_title' => $kinopoisk['ruName'],
                                    ));

                                    update_post_meta($postId, 'kinopoisk_id', $kinopoisk['id']);
                                    update_post_meta($postId, 'eng_name', $kinopoisk['engName']);
                                    update_post_meta($postId, 'image_path', $kinopoisk['imagePath']);
                                    update_post_meta($postId, 'image_file_name', $kinopoisk['imageFileName']);
                                    update_post_meta($postId, 'imdb_film_url', $imdb['filmUrl']);
                                    update_post_meta($postId, 'search_query', $film);
                                    update_post_meta($postId, 'time', $kinopoisk['params']['время']);
                                    update_post_meta($postId, 'age', $kinopoisk['params']['возраст']);
                                    update_post_meta($postId, 'kinopoisk_rating', $kinopoisk['rating']);
                                    update_post_meta($postId, 'rating_plugin_HTML', $imdb['ratingPluginHTML']);

                                } else {
                                    $postId = $post['ID'];
                                }

                                if ($postId) {
                                    update_post_meta($postId, 'vk_player_url', $playerUrl);
                                }
                            }

                            break;
                        }
                    }
                }
            }
        }
    }
}
?>

<div class="wrap">
    <h2><?php echo VKVP_INDEX_TITLE ?></h2>

    <table class="form-table">
        <tr valign="top">
            <tr valign="top">
                <th scope="row">Access token:</th>
                <td><?php echo ($access_token) ? $access_token : 'NULL'; ?> (до <?php echo date("d.m.Y H:i", $expires_in) ?>)</td>
            </tr>
        </tr>
    </table>

    <?php if ($error) { ?>
        <div class="error">
            <p><strong>Ошибка:</strong> <?php echo $error; ?></p>
        </div>
    <?php } ?>

    <?php if (!$error) { ?>
        <form method="post" action="<?php echo admin_url( 'admin.php?page=vk-video-parser/vkvp_index.php', 'http' ) ?>">
            <table class="form-table">
                <tr valign="top">
                    <td colspan="2">
                        <label for="films">Фильмы (каждое название с новой сторки)</label><br/>
                        <textarea id="films" name="films" rows="10" cols="50"></textarea>
                    </td>
                </tr>
            </table>

            <p class="submit">
                <input type="submit" class="button-primary" value="Поехали" />
            </p>
        </form>
    <?php } ?>

    <?php if ($log) { ?>
        <strong>Log</strong>
        <pre><?php echo $log ?></pre>
    <?php } ?>
</div>
