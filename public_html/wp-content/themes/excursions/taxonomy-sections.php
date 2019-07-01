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

	<?php if (have_posts()) :
	$term = get_queried_object();
	if ( $term->slug == 'museums' ) : 
		do_action( 'guidebook_map_scripts' );
		?>
		<div class="obj_map noautoopen" data-state="init" data-slug="museums">
		<div class="om_block omb_panel" style="display: none;">
			<button class="OpenMap_btn">
				<span class="state_init">[ Показать на карте ]</span>
				<span class="state_open">[ Закрыть карту ]</span>
				<span class="state_close">[ Открыть карту ]</span>
			</button>
		</div>
		<div class="om_content"></div>
	</div>
	<?php endif; ?>

	<div class="row">
		<?php while( have_posts() ):
			the_post();
			
			$permalink = get_the_permalink();
			$title = esc_html( get_the_title() ); 
			// $title = get_field('gba_rating').' '.$title;
			?>		 
				<div class="anno-card col-6 col-sm-6 col-md-4 col-lg-3">
					<?php 
						// the_post_thumbnail('medium'); 
						$thumb_id = get_post_thumbnail_id();
						echo get_attachment_picture( $thumb_id, 'medium', false, null, true, true ); // medium_large
					?>
					<h2 class="annocard-title"><a href="<?=$permalink?>" title="Ссылка на: <?=$title?>"><?=$title?></a></h2>
				</div>

		<?php endwhile; ?>
	</div>

		<?php the_posts_pagination();

		echo term_description();

	else :

		get_template_part( 'template-parts/content', 'none' );

	endif;
	?>

</main><!-- #main -->

<?php
get_footer();
