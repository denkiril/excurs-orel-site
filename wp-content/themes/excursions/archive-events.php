<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package excursions
 */

get_header();
?>

	<main id="main" class="archive-events.php">

	<?php if ( have_posts() ) : 
		
		echo do_shortcode('[annocards post_type="events" section_title="Актуальные события" date="future"]');
		
		echo do_shortcode('[annocards post_type="events" section_title="Прошедшие события" date="past"]');

		// the_posts_navigation();
		the_posts_pagination();

	else :

		get_template_part( 'template-parts/content', 'none' );

	endif;
	?>

	</main><!-- #main -->

<?php
// get_sidebar();
get_footer();
