<?php
/**
 * The template for displaying all pages
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

	<div id="primary" class="content-area">
		<div class="container main-container">
		<main id="main">

		<?php
		// while ( have_posts() ) :
		// 	the_post();

		// 	get_template_part( 'template-parts/content', 'page' );

		// 	if ( comments_open() || get_comments_number() ) :
		// 		comments_template();
		// 	endif;

		// endwhile; 
		?>

		<?php the_post(); ?>
		<?php the_content(); ?>

		</main><!-- #main -->
		</div> <!-- .main-container -->
	</div><!-- #primary -->

<?php
// get_sidebar();
get_footer();
