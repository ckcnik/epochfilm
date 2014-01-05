<?php
/**
 * Custom functions
 */

/**
 * Function added in the table postmeta counters views posts, and incrementing their
 * @param $postID
 */
function setPostViews($postID) {
	$count_key = 'post_views_count';
	$count = get_post_meta($postID, $count_key, true);
	if($count==''){
		$count = 0;
		delete_post_meta($postID, $count_key);
		add_post_meta($postID, $count_key, '0');
	}else{
		$count++;
		update_post_meta($postID, $count_key, $count);
	}
}

/**
 * Function returned number views posts
 * @param $postID
 * @return mixed|string
 */
function getPostViews($postID){
	$count_key = 'post_views_count';
	$count = get_post_meta($postID, $count_key, true);
	if($count==''){
		delete_post_meta($postID, $count_key);
		add_post_meta($postID, $count_key, '0');
		return "0";
	}
	return $count;
}

/**
 * Function returned the category name, like link
 * @param $id - post id
 * @param $catId - category id
 * @return string - category link with name
 */
function getCategory($id, $catId)
{
	$categories = get_the_category($id);
	$result = '';
	if ( !empty( $categories ) ) {
		foreach ( $categories as $category ) {
			if ( $category->parent ) {
				if ($category->parent == $catId)
					$result .= '<a href="' . esc_url( get_category_link( $category->term_id ) ) . '" title="' . esc_attr( sprintf( __( "View all posts in %s" ), $category->name ) ) . '" >' . $category->name.'</a> ';
			}
		}
	}
	return $result;
}

/**
 * Function returned related posts the current post
 * @param $id current post
 * @return array related posts
 */
function getRelatedPosts($id)
{
	$categories = get_the_category($id);
	if ($categories) {
		$category_ids = array();
		foreach($categories as $individual_category)
			$category_ids[] = $individual_category->term_id;
		$args = array(
			'category__in'		=> $category_ids,
			'post__not_in'		=> array($id),
			'showposts'			=> 6, // number of related posts
			'orderby'			=> rand,
			'caller_get_posts'	=> 1
		);
		$my_query = new wp_query($args);
		if( $my_query->have_posts() ) {
			$returnedPosts = array();
			while ($my_query->have_posts()) {
				$my_query->the_post();
				$returnedPosts[] = array(
					'image_path' 	=> get_post_custom_values('image_path')[0],
					'permalink' 	=> get_permalink(),
					'title' 		=> get_the_title(),
				);
			}
		}
		wp_reset_query();
	}
	return $returnedPosts;
}

/**
 * Создает пейджинг
 */
function pagingCreate() {
	global $wp_query;
	$big = 999999999; // need an unlikely integer
	$args = array(
		'base'			=> str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
		'format'		=> '%#%',
		'total'			=> $wp_query->max_num_pages,
		'current'		=> max(1, get_query_var('paged')),
		'show_all'		=> false,
		'prev_next'		=> True,
		'prev_text'		=> __('«'),
		'next_text'		=> __('»'),
		'type'			=> 'plain',
		'add_args'		=> False,
		'add_fragment'	=> ''
	);
	echo paginate_links($args);
}

/**
 * Function check (and update) vk-player url
 * @param $postID
 * @return bool
 * <?php global $post; checkVkPlayer($post->ID); ?>
 */
function checkVkPlayer($postId) {
    $checkDatePlayer = get_post_meta($postId, 'check_date_player');
    if (date('dmY') == $checkDatePlayer[0]) {
        return true;
    }

    require_once(ABSPATH . 'lib/phpQuery.php');

    $vkPlayerUrl = get_post_meta($postId, 'vk_player_url');
    $vkPlayerUrl = $vkPlayerUrl[0];

    if ($vkPlayerUrl) {
        $player = file_get_contents($vkPlayerUrl);
        $document = phpQuery::newDocumentHTML($player);
        $pq = pq($document);

        if (!$pq->find('object')->length) { // если не найден плеер
            $code = '';

            $clientId = get_option('vkvp_client_id');
            $redirectUrl = urlencode(home_url() . '/wp-admin/admin-ajax.php');
            $urlVkAuthorize = "https://oauth.vk.com/authorize?client_id={$clientId}&scope=video&redirect_uri={$redirectUrl}&response_type=code&v=5.4";

            // Получение 'code'
            if ($curl = curl_init()) {
                curl_setopt($curl, CURLOPT_URL, $urlVkAuthorize);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/31.0.1650.57 Safari/537.36");
                curl_setopt($curl, CURLOPT_AUTOREFERER, true);
                curl_setopt($curl, CURLOPT_COOKIEJAR, TEMPLATEPATH . '/assets/cookies.txt');
                curl_setopt($curl, CURLOPT_COOKIEFILE, TEMPLATEPATH . '/assets/cookies.txt');
                curl_setopt($curl, CURLOPT_HEADER, true);
                curl_setopt($curl, CURLOPT_REFERER, $urlVkAuthorize);
                curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
                $response = curl_exec($curl);
                curl_close($curl);

                $response = explode("\r\n\r\n", $response);

                if (count($response) <= 2) { // Пользователь не авторизован. Авторизация.
                    $body = $response[1];

                    $document = phpQuery::newDocumentHTML($body);
                    $pq = pq($document);

                    $args = array(
                        'ip_h' => $pq->find('input[name="ip_h"]')->val(),
                        '_origin' => $pq->find('input[name="_origin"]')->val(),
                        'to' => $pq->find('input[name="to"]')->val(),
                        'expire' => $pq->find('input[name="expire"]')->val(),
                        'email' => VK_LOGIN,
                        'pass' => VK_PASS,
                        'submit' => '',
                    );
                    $loginUrl = $pq->find('#login_submit')->attr('action');

                    if ($curl = curl_init()) {
                        curl_setopt($curl, CURLOPT_URL, $loginUrl);
                        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
                        curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/31.0.1650.57 Safari/537.36");
                        curl_setopt($curl, CURLOPT_AUTOREFERER, true);
                        curl_setopt($curl, CURLOPT_COOKIEJAR, TEMPLATEPATH . '/assets/cookies.txt');
                        curl_setopt($curl, CURLOPT_COOKIEFILE, TEMPLATEPATH . '/assets/cookies.txt');
                        curl_setopt($curl, CURLOPT_HEADER, true);
                        curl_setopt($curl, CURLOPT_POST, true);
                        curl_setopt($curl, CURLOPT_POSTFIELDS, $args);
                        curl_setopt($curl, CURLOPT_REFERER, $urlVkAuthorize);
                        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
                        $response = curl_exec($curl);
                        curl_close($curl);

                        $response = explode("\r\n\r\n", $response);
                    }
                }

                if (count($response) > 2) { // Пользователь авторизован. Редирект в curl с полученным 'code'
                    $index = 0;

                    for($i = 0; $i < count($response); $i++) {
                        if (preg_match("/200 OK/", $response[$i])) {
                            $index = $i - 1;
                            break;
                        }
                    }

                    $headers = explode("\r\n", $response[$index]);
                    foreach($headers as $header) {
                        $header = explode(': ', $header);
                        if ($header[0] == 'Location') {
                            preg_match('/^.*?code=(.*)$/', $header[1], $match);
                            $code = $match[1];
                            break;
                        }
                    }
                }
            }

            // Получение access_token
            $accessToken = '';

            $clientSecret = get_option('vkvp_client_secret');
            $urlVkAccessToken = "https://oauth.vk.com/access_token?client_id={$clientId}&client_secret={$clientSecret}&code={$code}&redirect_uri={$redirectUrl}";
            $accessTokenResponse = json_decode(file_get_contents($urlVkAccessToken), true);

            if (!empty($accessTokenResponse) && isset($accessTokenResponse['access_token'])) {
                $accessToken = $accessTokenResponse['access_token'];
            }

            // Получение нового плеера
            if ($accessToken) {
                $engName = get_post_meta($postId, 'eng_name');

                $categories = get_the_category($postId);
                $year = '';
                if ( !empty( $categories ) ) {
                    foreach ( $categories as $category ) {
                        if ( $category->parent && $category->parent == 19 ) {
                            $year = $category->name;
                            break;
                        }
                    }
                }

                $film = get_the_title($postId) . ' ' . $engName[0] . ' ' . $year;

                $urlVkVideo = "https://api.vk.com/method/video.search?access_token={$accessToken}&q=" . urlencode($film) . "&sort=2&filters=long";
                $response = json_decode(file_get_contents($urlVkVideo), true);
                $response = isset($response['response']) ? $response['response'] : array();

                if ($response) {
                    foreach($response as $video) {
                        if ($video) {
                            $player = file_get_contents($video['player']);
                            $document = phpQuery::newDocumentHTML($player);
                            $pq = pq($document);

                            if ($pq->find('object')->length) { // если найден плеер
                                $playerUrl = $video['player'];
                                update_post_meta($postId, 'vk_player_url', $playerUrl);
                                return true;
                            }
                        }
                    }
                }
            }
        }
    }

    update_post_meta($postId, 'check_date_player', date('dmY'));

    return false;
}