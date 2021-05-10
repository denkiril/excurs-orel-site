<?php
/**
 * REST functions for Excursions theme
 *
 * @package excursions
 */

/**
 * REST GET guidebook posts: all (default) or by term slug
 *
 * @param WP_REST_Request $request Optional. WP_REST_Request args.
 * @return array Posts array.
 */
function get_guidebook_posts_wprest( WP_REST_Request $request ) {
	$myposts = get_guidebook_posts( null, -1 );

	return $myposts;
}

/**
 * REST GET get_sights
 *
 * @param WP_REST_Request $request Optional. WP_REST_Request args.
 * @return array Posts array.
 */
function get_sights_wprest( WP_REST_Request $request ) {
	$slug = null;

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
			$post_slug = in_array( $mypost, $mus_posts, true ) ? 'museums' : 'sights';

			$sights[] = array(
				'post_id'   => $post_id,
				'permalink' => $permalink,
				'title'     => $title,
				'lat'       => $location['lat'],
				'lng'       => $location['lng'],
				'thumb_url' => $thumb_url,
				'slug'      => $post_slug,
			);
		}
	}

	return wp_json_encode( $sights );
}

/**
 * REST routes registrator.
 *
 * @return void
 */
function excurs_wprest_registrator() {
	register_rest_route(
		'guidebook/v1',
		'/gb-posts',
		array(
			'methods'  => 'GET',
			'callback' => 'get_guidebook_posts_wprest',
		)
	);

	register_rest_route(
		'guidebook/v1',
		'/gb-sights',
		array(
			'methods'  => 'GET',
			'callback' => 'get_sights_wprest',
		)
	);
}

add_action( 'rest_api_init', 'excurs_wprest_registrator' );
