<?php
/**
 * Template part for displaying guidebook posts 
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package excursions
 */

?>

<?php

$is_routes = false;
$post_terms = get_the_terms( $post->ID, 'sections' );
foreach ( $post_terms as $term ) {
	if ( $term->slug == 'routes' ) {
		$is_routes = true;
		break;
	}
}

if ( $is_routes ) {
	get_template_part( 'template-parts/content-guidebook-routes' );
} else {
	get_template_part( 'template-parts/content-guidebook-default' );
}

?>