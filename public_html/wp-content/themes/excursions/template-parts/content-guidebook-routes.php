<?php
/**
 * Template part for displaying guidebook posts taxonomy sections/routes
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package excursions
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

<header class="entry-header">
	<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
</header><!-- .entry-header -->

<div class="entry-content">

<?php
$thumb_id = get_post_thumbnail_id();
if ( $thumb_id ) {
	do_action( 'add_gallery_scripts' );
	$full_image_url = wp_get_attachment_image_url( $thumb_id, 'full' );
	echo markup_fancy_figure( $thumb_id, 'gallery', $full_image_url, null, 'medium_large', false, get_the_title( $thumb_id ), 'events-image' );
}

echo wiki_parse_text( do_shortcode( carbon_get_the_post_meta( 'gbr_intro' ) ) );

$sights     = array();
$gbr_sights = carbon_get_the_post_meta( 'gbr_sights' );

foreach ( $gbr_sights as $key => $obj ) {
	$location = null;

	// 1. try get location from gbr_sights post meta.
	$latlng_text = $obj['latlng_text'];
	if ( $latlng_text ) {
		$geo = explode( ',', $latlng_text, 2 );
		if ( count( $geo ) === 2 && is_numeric( trim( $geo[0] ) ) && is_numeric( trim( $geo[1] ) ) ) {
			$lat = (float) trim( $geo[0] );
			$lng = (float) trim( $geo[1] );
			if ( $lat >= -90 && $lat <= 90 && $lng >= -180 && $lng <= 180 ) {
				$location = array(
					'lat' => $lat,
					'lng' => $lng,
				);
			}
		}
	}

	// 2. try get location from gbr_sights/gba post meta.
	if ( ! $location ) {
		$gba_postid = count( $obj['gba'] ) ? $obj['gba'][0]['id'] : null;
		$location   = get_field( 'obj_info', $gba_postid )['geolocation'];
	}

	if ( $location ) {
		$img_id    = $obj['image'];
		$thumb_url = $img_id ? wp_get_attachment_image_url( $img_id, 'thumbnail' ) : null;
		$item_link = '#item-' . intval( $key + 1 );

		$sights[] = array(
			'lat'       => $location['lat'],
			'lng'       => $location['lng'],
			'permalink' => $item_link,
			'title'     => esc_html( trim( $obj['title'] ) ),
			'thumb_url' => $thumb_url,
		);
	}
}

if ( count( $sights ) ) :
	do_action( 'event_map_scripts' );
	?>

	<div class="mini-map pregrow" data-sights="<?php echo esc_html( wp_json_encode( $sights ) ); ?>">
		<noscript>Если включите JavaScript, здесь отобразится карта.</noscript>
		<div class="map-cover"></div>
		<button id="OpenMap_btn" class="ref_btn shake">[ Показать на карте ]</button>
	</div>

<?php endif; ?>

	<div class="info-block">
		<?php echo wiki_parse_text( do_shortcode( carbon_get_the_post_meta( 'gbr_content' ) ) ); ?>
	</div>
</div><!-- .entry-content -->

</article><!-- #post-<?php the_ID(); ?> -->
