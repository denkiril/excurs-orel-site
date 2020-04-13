<?php
/**
 * AJAX functions for Excursions theme
 *
 * @package excursions
 */

/**
 * AJAX action for get 'events' posts
 *
 * @return void
 */
function get_events() {
	$args = array(
		'post_type'   => 'events',
		'orderby'     => 'meta_value',
		'meta_key'    => 'event_info_event_date',
		'order'       => 'DESC',
		'exclude'     => '312',
		'numberposts' => -1,
	);

	$events  = array();
	$myposts = get_posts( $args );
	if ( $myposts ) {
		foreach ( $myposts as $mypost ) {
			$post_id   = $mypost->ID;
			$permalink = get_the_permalink( $post_id );
			$title     = esc_html( get_field( 'event_info_event_date', $post_id ) . ' ' . get_the_title( $post_id ) );
			$location  = get_field( 'event_info_event_place_map', $post_id );

			$events[] = array(
				'post_id'   => $post_id,
				'permalink' => $permalink,
				'title'     => $title,
				'lat'       => $location['lat'],
				'lng'       => $location['lng'],
			);
		}
	}

	echo wp_json_encode( $events );
	wp_die(); // выход нужен для того, чтобы в ответе не было ничего лишнего, только то что возвращает функция.
}

/**
 * AJAX action for get Guidebook posts
 * Use 'slug' query parameter for get posts by slug. Default: sights & museums.
 *
 * @return void
 */
function get_sights() {
	check_ajax_referer( 'myajax-gb-nonce', 'nonce_code' );

	$slug = isset( $_GET['slug'] ) ? sanitize_key( $_GET['slug'] ) : null;
	if ( ! $slug ) {
		$myposts   = get_guidebook_posts( null, -1 );
		$mus_posts = get_guidebook_posts( 'museums', -1 );
		$myposts   = array_merge( $myposts, $mus_posts );
	} else {
		$myposts = get_guidebook_posts( $slug, -1 );
	}

	$sights = array();
	if ( $myposts ) {
		foreach ( $myposts as $mypost ) {
			$post_id   = $mypost->ID;
			$permalink = get_the_permalink( $post_id );
			$title     = esc_html( get_the_title( $post_id ) );
			$location  = get_field( 'obj_info_geolocation', $post_id );
			$thumb_url = get_the_post_thumbnail_url( $post_id, 'thumbnail' );

			$sights[] = array(
				'post_id'   => $post_id,
				'permalink' => $permalink,
				'title'     => $title,
				'lat'       => $location['lat'],
				'lng'       => $location['lng'],
				'thumb_url' => $thumb_url,
			);
		}
	}

	echo wp_json_encode( $sights );
	wp_die();
}

/**
 * AJAX action for get apikies etc.
 *
 * @return void
 */
function get_apikey() {
	check_ajax_referer( 'myajax-script-nonce', 'nonce_code' );

	$apikey = null;
	if ( isset( $_GET['apikeyname'] ) ) {
		switch ( $_GET['apikeyname'] ) {
			case 'GOOGLE_MAPS_API':
				$apikey = GOOGLE_MAPS_API;
				break;
			case 'YANDEX_MAPS_API':
				$apikey = YANDEX_MAPS_API;
				break;
			case 'FB_APPID':
				$apikey = FB_APPID;
				break;
		}
	}
	echo wp_json_encode( $apikey );
	wp_die();
}

// Подключаем AJAX обработчики, только когда в этом есть смысл.
if ( wp_doing_ajax() ) {
	add_action( 'wp_ajax_get_events', 'get_events' );
	add_action( 'wp_ajax_nopriv_get_events', 'get_events' );
	add_action( 'wp_ajax_get_sights', 'get_sights' );
	add_action( 'wp_ajax_nopriv_get_sights', 'get_sights' );
	add_action( 'wp_ajax_get_apikey', 'get_apikey' );
	add_action( 'wp_ajax_nopriv_get_apikey', 'get_apikey' );
}
