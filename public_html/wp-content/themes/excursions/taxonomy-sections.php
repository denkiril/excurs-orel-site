<?php
/**
 * The template for displaying 'guidebook_' taxonomy pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package excursions
 */

get_header();
?>

<main id="main" class="site-main taxonomy-sections">

<?php
if ( have_posts() ) :
	$sections_term = get_queried_object();
	if ( 'museums' === $sections_term->slug ) {
		echo do_shortcode( '[guidebook_map add_classes="noautoopen" slug=museums]' );
	}
	?>

	<div class="row">
		<?php
		while ( have_posts() ) :
			the_post();
			?>

			<div class="anno-card col-6 col-sm-6 col-md-4 col-lg-3">
				<a href="<?php the_permalink(); ?>" <?php excurs_the_title_attr(); ?> tabindex="-1">
					<?php the_attachment_picture( get_post_thumbnail_id(), 'medium', false, null, true, true ); ?>
				</a>

				<h2 class="annocard-title">
					<a href="<?php the_permalink(); ?>" <?php excurs_the_title_attr(); ?>>
						<?php the_title(); ?>
					</a>
				</h2>
			</div>

		<?php endwhile; ?>
	</div>

		<?php
		the_posts_pagination();

		the_field( 'gbs_content', $sections_term );

else :

	get_template_part( 'template-parts/content', 'none' );

endif;
?>

</main><!-- #main -->

<?php
get_footer();
