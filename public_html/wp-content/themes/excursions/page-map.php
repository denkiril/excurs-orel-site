<?php
/**
 * The template for displaying 'map' page
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package excursions
 */

get_header();
?>

<main id="main" class="site-main page-map">

<?php
if ( have_posts() ) :
	the_post();

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
			$num    = $key + 1;
			$img_id = $obj['image'];

			$sights[] = array(
				'lat'          => $location['lat'],
				'lng'          => $location['lng'],
				'permalink'    => '#item-' . $num,
				'title'        => esc_html( trim( $obj['title'] ) ),
				'thumb_url'    => $img_id ? wp_get_attachment_image_url( $img_id, 'thumbnail' ) : null,
				'icon_content' => $num,
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
<?php endif; ?>
</main><!-- #main -->

<?php
get_footer();
