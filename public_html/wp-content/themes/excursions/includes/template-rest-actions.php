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
			$post_id     = $mypost->ID;
			$obj_info    = get_field( 'obj_info', $post_id );
			$geolocation = $obj_info['geolocation'];
			$location    = $obj_info['location'];
			$category    = $obj_info['protection_category'];
			$type        = $obj_info['okn_type'];
			$okn_id      = $obj_info['okn_id'];
			$permalink   = get_the_permalink( $post_id );
			$title       = esc_html( get_the_title( $post_id ) );
			$thumb_url   = get_the_post_thumbnail_url( $post_id, 'thumbnail' );
			$sets        = in_array( $mypost, $mus_posts, true ) ? 'mus' : 'main';

			$sight = array(
				'post_id'     => $post_id,
				'permalink'   => $permalink,
				'title'       => $title,
				'geolocation' => $geolocation,
				'location'    => $location,
				'thumb_url'   => $thumb_url,
			);

			if ( $sets ) {
				$sight['sets'] = array( $sets );
			}

			if ( $type ) {
				$sight['type'] = array( $type );
			}

			if ( $category ) {
				$sight['category'] = array( $category );
			}

			if ( $okn_id ) {
				$sight['okn_id'] = $okn_id;
			}

			$sights[] = $sight;
		}
	}

	return $sights;
}

/**
 * REST GET get_sights
 *
 * @param WP_REST_Request $request Optional. WP_REST_Request args.
 * @return string Post.
 */
function get_sight_by_id_wprest( WP_REST_Request $request ) {
	$mypost = get_post( $request['id'] );

	if ( ! $mypost ) {
		return new WP_Error( 'no_post', 'Запись не найдена', array( 'status' => 404 ) );
	}

	$post_id  = $mypost->ID;
	$obj_info = get_field( 'obj_info', $post_id );

	$sight = array(
		'post_id'     => $post_id,
		'permalink'   => get_the_permalink( $post_id ),
		'title'       => get_the_title( $post_id ),
		'geolocation' => $obj_info['geolocation'],
		'location'    => $obj_info['location'],
		// 'thumb_url'   => get_the_post_thumbnail_url( $post_id, 'thumbnail' ),
	);

	$sets = in_array( $mypost, $mus_posts, true ) ? 'mus' : 'main';
	if ( $sets ) {
		$sight['sets'] = array( $sets );
	}

	if ( $obj_info['okn_type'] ) {
		$sight['type'] = array( $obj_info['okn_type'] );
	}

	if ( $obj_info['protection_category'] ) {
		$sight['category'] = array( $obj_info['protection_category'] );
	}

	if ( $obj_info['okn_id'] ) {
		$sight['okn_id'] = $obj_info['okn_id'];
	}

	if ( $obj_info['registry_name'] ) {
		$sight['okn_title'] = $obj_info['registry_name'];
	}

	if ( $obj_info['registry_date'] ) {
		$sight['registry_date'] = $obj_info['registry_date'];
	}

	if ( $obj_info['okn_date'] ) {
		$sight['okn_date'] = $obj_info['okn_date'];
	}

	if ( $obj_info['district'] ) {
		$sight['district'] = $obj_info['district'];
	}

	if ( $obj_info['founding_date'] ) {
		$sight['founding_date'] = $obj_info['founding_date'];
	}

	if ( $obj_info['site'] ) {
		$sight['site'] = $obj_info['site'];
	}

	$sight['intro'] = get_field( 'gba_intro', $post_id );
	// $sight['intro'] = carbon_get_post_meta( $post_id, 'gba_intro_2' );

	$images   = array();
	$thumb_id = get_post_thumbnail_id( $post_id );
	if ( $thumb_id ) {
		$metadata = wp_get_attachment_metadata( $thumb_id );
		unset( $metadata['image_meta'] );

		$images[] = array(
			'title'   => get_the_title( $thumb_id ),
			'full'    => wp_get_attachment_image_url( $thumb_id, 'full' ),
			'meta'    => $metadata,
			'alt'     => get_post_meta( $thumb_id, '_wp_attachment_image_alt', true ),
			'caption' => wp_get_attachment_caption( $thumb_id ),
		);
	}

	$gal_images = acf_photo_gallery( 'gallery_gal', $post_id );
	foreach ( $gal_images as $img ) {
		$img_id = $img['id'];
		if ( $img_id ) {
			$metadata = wp_get_attachment_metadata( $img_id );
			unset( $metadata['image_meta'] );

			$images[] = array(
				'title'   => $img['title'],
				'full'    => $img['full_image_url'],
				'meta'    => $metadata,
				'alt'     => get_post_meta( $img_id, '_wp_attachment_image_alt', true ),
				'caption' => $img['caption'],
			);
		}
	}

	$sight['images'] = $images;

	return $sight;
}

/**
 * REST GET guidebook posts data by slugs
 *
 * @param WP_REST_Request $request Optional. WP_REST_Request args.
 * @return array Posts data array.
 */
function get_sights_by_links_wprest( WP_REST_Request $request ) {
	$parameters = $request->get_query_params();
	$slugs_arr  = explode( ',', $parameters['slugs'] );

	$posts = array();
	foreach ( $slugs_arr as $slug ) {
		$post_obj = get_page_by_path( $slug, OBJECT, 'guidebook' );
		if ( $post_obj ) {
			$posts[] = array(
				'slug'    => $slug,
				'post_id' => (int) $post_obj->ID,
				'title'   => $post_obj->post_title,
			);
		}
	}

	return $posts;
}

/**
 * REST routes registrator.
 *
 * @return void
 */
function excurs_wprest_registrator() {
	register_rest_route(
		'api',
		'/gb-posts',
		array(
			'methods'  => 'GET',
			'callback' => 'get_guidebook_posts_wprest',
		)
	);

	register_rest_route(
		'api',
		'/sights',
		array(
			'methods'  => 'GET',
			'callback' => 'get_sights_wprest',
		)
	);

	register_rest_route(
		'api',
		'/sights/(?P<id>\d+)',
		array(
			'methods'  => 'GET',
			'callback' => 'get_sight_by_id_wprest',
		)
	);

	register_rest_route(
		'api',
		'/sight-links',
		array(
			'methods'  => 'GET',
			'callback' => 'get_sights_by_links_wprest',
		)
	);
}

add_action( 'rest_api_init', 'excurs_wprest_registrator' );
