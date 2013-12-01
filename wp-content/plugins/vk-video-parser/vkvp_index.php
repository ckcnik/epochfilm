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
        if ($curl = curl_init()) {
            curl_setopt($curl, CURLOPT_URL, "http://www.kinopoisk.ru/login/");
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/31.0.1650.57 Safari/537.36");
            curl_setopt($curl, CURLOPT_AUTOREFERER, true);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, array(
                'shop_user[login]' => get_option('vkvp_kinopisk_login'),
                'shop_user[pass]' => get_option('vkvp_kinopisk_password'),
                'shop_user[mem]' => 'on',
                'auth' => '',
            ));
            curl_setopt($curl, CURLOPT_COOKIEJAR, __DIR__ . '/cookies.txt');
            curl_setopt($curl, CURLOPT_COOKIEFILE, __DIR__ . '/cookies.txt');
            $kinopoiskLogin = curl_exec($curl);
            curl_close($curl);
        }

        // авторизация на imdb
        if ($curl = curl_init()) {
            curl_setopt($curl, CURLOPT_URL, "https://secure.imdb.com/oauth/login?origurl=http://www.imdb.com/&show_imdb_panel=1");
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/31.0.1650.57 Safari/537.36");
            curl_setopt($curl, CURLOPT_AUTOREFERER, true);
            curl_setopt($curl, CURLOPT_COOKIEJAR, __DIR__ . '/cookies.txt');
            curl_setopt($curl, CURLOPT_COOKIEFILE, __DIR__ . '/cookies.txt');
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
                curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/31.0.1650.57 Safari/537.36");
                curl_setopt($curl, CURLOPT_AUTOREFERER, true);
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, array(
                    'login' => get_option('vkvp_imdb_login'),
                    'password' => get_option('vkvp_imdb_password'),
                    $hiddenFieldName => $hiddenFieldValue,
                ));
                curl_setopt($curl, CURLOPT_COOKIEJAR, __DIR__ . '/cookies.txt');
                curl_setopt($curl, CURLOPT_COOKIEFILE, __DIR__ . '/cookies.txt');
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
                        if ($pq->find('object')->length) {
                            $engFilmName = '';

                            // <kinopoisk>
                            if ($curl = curl_init()) {
                                curl_setopt($curl, CURLOPT_URL, "http://www.kinopoisk.ru/index.php?first=yes&kp_query=" . urlencode($film));
                                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                                curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
                                curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/31.0.1650.57 Safari/537.36");
                                curl_setopt($curl, CURLOPT_AUTOREFERER, true);
                                curl_setopt($curl, CURLOPT_COOKIEJAR, __DIR__ . '/cookies.txt');
                                curl_setopt($curl, CURLOPT_COOKIEFILE, __DIR__ . '/cookies.txt');
                                $kinopoiskFilmPage = curl_exec($curl);
                                curl_close($curl);
                                $kinopoiskFilmPage = iconv("CP1251", "UTF-8", $kinopoiskFilmPage);

                                $document = phpQuery::newDocumentHTML($kinopoiskFilmPage, 'cp1251');
                                $pq = pq($document);

                                $img = $pq->find('#photoBlock .popupBigImage');
                                if ($img->length) {
                                    $imgOnclick = $img->attr('onclick');
                                    preg_match('/^.*?\(\'(.*film_big\/(.*))\'\).*$/', $imgOnclick, $match);
                                    $imgSrc = isset($match[1]) ? $match[1] : '';
                                    $imgSrc = str_replace('st', 'st-ua', $imgSrc);
                                    $imgFileName = $match[2];

                                    $uploadDir = wp_upload_dir();
                                    $uploadDir = $uploadDir['path'];

                                    $curl = curl_init();
                                    $file = fopen($uploadDir . '/' . $imgFileName, 'wb+');

                                    curl_setopt($curl, CURLOPT_URL, $imgSrc);
                                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                                    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
                                    curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/31.0.1650.57 Safari/537.36");
                                    curl_setopt($curl, CURLOPT_AUTOREFERER, true);
                                    curl_setopt($curl, CURLOPT_COOKIEJAR, __DIR__ . '/cookies.txt');
                                    curl_setopt($curl, CURLOPT_COOKIEFILE, __DIR__ . '/cookies.txt');
                                    curl_setopt($curl, CURLOPT_FILE, $file);
                                    $image = curl_exec($curl);
                                    curl_close($curl);

                                    fclose($file);
                                    chmod($uploadDir . '/' . $imgFileName, 0664);
                                }

                                $infoTableTr = $pq->find('#infoTable tr');
                                foreach($infoTableTr as $tr) {
                                    pq($tr)->find('td.type')->text(); // param name
                                    pq($tr)->find('td')->eq(1)->text(); // param value
                                }

                                $actorListLi = $pq->find('#actorList ul')->eq(0)->find('li');
                                $actors = array();
                                foreach($actorListLi as $li) {
                                    $actors[] = pq($li)->text();
                                }

                                $engFilmName = $pq->find('#headerFilm span')->text();
                            }
                            // </kinopoisk>

                            // <imdb>
                            if ($curl = curl_init()) {
                                curl_setopt($curl, CURLOPT_URL, "http://www.imdb.com/find?s=tt&q=" . urlencode($engFilmName));
                                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                                curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
                                curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/31.0.1650.57 Safari/537.36");
                                curl_setopt($curl, CURLOPT_AUTOREFERER, true);
                                curl_setopt($curl, CURLOPT_COOKIEJAR, __DIR__ . '/cookies.txt');
                                curl_setopt($curl, CURLOPT_COOKIEFILE, __DIR__ . '/cookies.txt');
                                $imdbSearchPage = curl_exec($curl);
                                curl_close($curl);

                                $document = phpQuery::newDocumentHTML($imdbSearchPage);
                                $pq = pq($document);
                                $firstSearchResultUrl = $pq->find('table.findList tr')->eq(0)->find('.result_text a')->attr('href');

                                $imdbFilmUrl = 'http://www.imdb.com' . $firstSearchResultUrl;

                                if ($curl = curl_init()) {
                                    curl_setopt($curl, CURLOPT_URL, $imdbFilmUrl);
                                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                                    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
                                    curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/31.0.1650.57 Safari/537.36");
                                    curl_setopt($curl, CURLOPT_AUTOREFERER, true);
                                    curl_setopt($curl, CURLOPT_COOKIEJAR, __DIR__ . '/cookies.txt');
                                    curl_setopt($curl, CURLOPT_COOKIEFILE, __DIR__ . '/cookies.txt');
                                    $imdbFilmPage = curl_exec($curl);
                                    curl_close($curl);

                                    $document = phpQuery::newDocumentHTML($imdbFilmPage);
                                    $pq = pq($document);
                                    $storyline = $pq->find('#titleStoryLine div[itemprop="description"]');
                                    pq($storyline)->find('.nobr')->remove();
                                    $storyline = pq($storyline)->text();

                                    $ratingPluginHTML = $pq->find('#ratingPluginHTML textarea')->val();
                                }
                            }
                            // </imdb>

                            // echo '<iframe width="700" height="500" src="' . $video['player'] . '"></iframe>';

                            sleep(1);
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
                        <textarea id="films" name="films" rows="10" cols="50">Побег из Шоушенка (1994)
Зеленая миля (1999)
Форрест Гамп (1994)
Intouchables (2011)</textarea>
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
