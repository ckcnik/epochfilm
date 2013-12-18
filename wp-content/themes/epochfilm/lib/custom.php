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
