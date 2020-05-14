<?php
/**
 * The template for displaying 'citata' page
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

<style>
	.citata-source { font-size: 0.9em; color: #3f3f3f; }
	.citata-comment { font-size: 0.9em; }
</style>

<main id="main" class="site-main page-citata">

<?php

if ( have_posts() ) :
	the_post();

	$sources_posts = get_posts( array( 'post_type' => 'sources' ) );
	$citata_list   = array();
	foreach ( $sources_posts as $sources_post ) {
		$source_id       = $sources_post->ID;
		$add_citata_list = carbon_get_post_meta( $source_id, 'citata_list' );

		if ( $add_citata_list ) {
			$source = carbon_get_post_meta( $source_id, 'source_text' );
			$date   = carbon_get_post_meta( $source_id, 'date' );
			foreach ( $add_citata_list as $citata ) {
				$citata['source'] = $source;
				$citata['date']   = $date;
				$citata_list[]    = $citata;
			}
		}
	}

	$field = 'date';
	usort(
		$citata_list,
		function( $a, $b ) use ( $field ) {
			return strcmp( $a[ $field ], $b[ $field ] );
		}
	);
	$gallery = false;
	?>

	<div style="margin-bottom: 2em;">
		<?php the_content(); ?>
		<hr />
	</div>

	<?php foreach ( $citata_list as $key => $citata ) : ?>

		<div class="row anno-card">
			<div class="col-12 col-md-4">
				<?php
				$img_id = $citata['image'];
				if ( $img_id ) {
					$full_img_url = wp_get_attachment_image_url( $img_id, 'full' );
					$gallery      = true;

					echo markup_fancy_figure( $img_id, 'gallery', $full_img_url, null, 'medium_large', 'lazy' );
				} else {
					echo '<hr />';
				}
				?>
			</div>

			<div class="col-12 col-md-8">
				<?php echo wpautop( trim( $citata['text'] ) ); ?>
				<p class="citata-source"><?php echo esc_html( trim( $citata['source'] ) ); ?></p>
			</div>

			<p class="col-12 citata-comment"><?php echo trim( $citata['comment'] ); ?></p>
		</div>

		<?php
	endforeach;

	if ( $gallery ) {
		do_action( 'add_gallery_scripts' );
	}
	?>
<?php endif; ?>
</main><!-- #main -->

<?php
get_footer();
