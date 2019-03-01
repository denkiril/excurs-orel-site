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

		<main id="main" class="site-main archive.php">

		<?php if ( have_posts() ) : ?>

			<!-- <header class="page-header"> -->
				<?php
				// the_archive_title( '<h1 class="page-title">', '</h1>' );
				// the_archive_description( '<div class="archive-description">', '</div>' );
				?>
			<!--</header> .page-header -->
			<!-- <section><div class="row section-container"><div class="col"> -->

			<?php
			/* Start the Loop */
			while ( have_posts() ) :
				the_post();
				// get_template_part( 'template-parts/content', get_post_type() ); 
				$permalink = get_the_permalink();
				$title = esc_html( get_the_title() ); ?>		 

				<div class="row anno-card">
					<div class="col-12 col-md-4">
						<a href="<?=$permalink?>" title="Ссылка на: <?=$title?>" tabindex="-1">
						<?php the_post_thumbnail('medium'); ?>
						</a>
					</div>
					<div class="col-12 col-md-8">
						<h2 class="annocard-title"><a href="<?=$permalink?>" title="Ссылка на: <?=$title?>"><?=$title?></a></h2>
						<?php the_excerpt(); ?>
					</div>
				</div>

			<?php endwhile;

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
