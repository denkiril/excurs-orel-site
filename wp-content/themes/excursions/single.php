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

	<?php if(function_exists('bcn_display') /*&& !is_page(PAGE_ID1) && !is_page(PAGE_ID2)*/ ): ?>
		<div class="breadcrumbs container" typeof="BreadcrumbList" vocab="https://schema.org/">
			<?php bcn_display(); ?>
		</div>
	<?php endif; ?>

	<!-- <div id="primary" class="content-area"> -->
	<div class="container main-container">
		<main id="main" class="site-main">

		<?php
		while ( have_posts() ) :
			the_post();

			get_template_part( 'template-parts/content', get_post_type() );

			echo 'single.php, post_type = ' . get_post_type();

			// the_post_navigation();

		endwhile; // End of the loop.
		?>

		</main><!-- #main -->
	</div> <!-- .main-container -->
	<!-- </div> #primary -->

<?php
// get_sidebar();
get_footer();
