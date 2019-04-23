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
	while ( have_posts() ) :
		the_post();

		get_template_part( 'template-parts/content', get_post_type() );

	endwhile; // End of the loop.
	?>

</main><!-- #main -->

<?php
// echo get_post_type();
// get_sidebar();
get_footer();
