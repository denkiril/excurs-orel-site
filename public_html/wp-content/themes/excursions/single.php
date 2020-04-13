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
if ( have_posts() ) {
	the_post();

	get_template_part( 'template-parts/content', get_post_type() );

	// if ('events' === get_post_type()) comments_template(); // anycomment.
}
?>

</main><!-- #main -->

<?php
get_footer();
