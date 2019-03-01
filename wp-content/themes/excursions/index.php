<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package excursions
 */

get_header();
?>

	<!-- <div class="container main-container index.php"> -->
		<main id="main" class="site-main">

		<?php
		// $headertitle = single_post_title(null, false);
		// $postmeta = get_post_meta( get_queried_object()->ID, 'header-h1', true ); 
		// if($postmeta) $headertitle = $postmeta;

		// the_widget( 'WP_Widget_Tag_Cloud' );

		if ( have_posts() ) :
			// if ( is_home() && ! is_front_page() ) :
				?>
				<!-- <header> -->
					<!-- <h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1> -->
					<!-- <h1 class="page-title"><?= $headertitle ?></h1> -->
				<!-- </header> -->
				<?php
			// endif; ?>

			<?php echo do_shortcode('[annocards post_type="post"]');

			/* Start the Loop */
			// while ( have_posts() ) :
			// 	the_post();
				// the_content();
				/*
				 * Include the Post-Type-specific template for the content.
				 * If you want to override this in a child theme, then include a file
				 * called content-___.php (where ___ is the Post Type name) and that will be used instead.
				 */
				// get_template_part( 'template-parts/content', get_post_type() );
			// endwhile;
			// the_posts_navigation();
		else :
			get_template_part( 'template-parts/content', 'none' );
		endif;
		?>

		</main><!-- #main -->

<?php
// get_sidebar();
get_footer();
