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

		<section role="announcement">
            <div class="row section-container">
			<div class="col">

		<?php  
		the_post();
		the_content(); ?>

		<h2>ПРЕДСТОЯЩИЕ МЕРОПРИЯТИЯ</h2>
		
		<?php 
		$myposts = get_posts();
		foreach( $myposts as $post ){
			setup_postdata( $post ); ?>

			<div class="row anno-card">
                <div class="col-12 col-md-4">
					<a href="<?php the_permalink() ?>" title="Ссылка на: <?php the_title_attribute(); ?>">
						<?php the_post_thumbnail('medium'); ?>
					</a>
				</div>
				<div class="col-12 col-md-8">
					<?php the_title('<h3>', '</h3>'); ?>
					<?php // echo get_the_excerpt() . '<a href="' . get_permalink() .'"> [ Подробнее... ]</a>'; ?>
					<p>
						<?php echo get_the_excerpt() . '  ' ?> 
						<a href="<?php the_permalink() ?>">Подробнее...</a>
					</p>
				</div>
			</div>

			<?php 
			}
			wp_reset_postdata(); ?>

		<?php 
		// $myposts = get_posts('post_type=attachment');
		// foreach( $myposts as $post ){
		// 	setup_postdata( $post ); 
		// 	echo wp_get_attachment_image( $post->ID, 'medium' );
		// 	}
		// 	wp_reset_postdata(); 
		?>

			</div>
			</div>
		</section> <!-- / announcement section -->
		
		</main><!-- #main -->
		</div> <!-- .main-container -->
	</div><!-- #primary -->

<?php
// get_sidebar();
get_footer();
