<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package excursions
 */

get_header();
?>

<main id="main" class="site-main single">

	<?php
	if (have_posts()) {
		// while (have_posts()) {
		the_post();

		$post_type = get_post_type();

		get_template_part( 'template-parts/content', $post_type );

		// if ( $post_type == 'events' && ( comments_open() || get_comments_number() ) ) :
		// if ( $post->ID == 400 ) :
		if ( $post_type == 'events' ) {
			// echo '<hr />';
			comments_template();
			// echo do_shortcode('[anycomment include="true"]');
		}

		// } // End of the loop.
	}
	?>

</main><!-- #main -->

<?php
// if ( $post_type != 'events' ) :
// <!-- Yandex.RTB R-A-414612-1 -->
// <div id="yandex_rtb_R-A-414612-1" style="padding: 20px 0 40px 0;"></div>
// endif;

get_footer();
