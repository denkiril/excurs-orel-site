<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package excursions
 */

get_header();
?>

	<main id="main">

		<section class="error-404 not-found">
			<div class="row section-container">
				<div class="col">
					<header class="page-header">
						<?php // esc_html_e( 'Oops! That page can&rsquo;t be found.', 'excursions' ); ?>
						<h1 class="page-title">Страницы с таким адресом нет на сайте</h1>
					</header><!-- .page-header -->

					<?php // esc_html_e( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'excursions' ); ?>
					<p>Но у нас есть много других, тоже хороших. Ищите :)</p>

					<?php
					// get_search_form();
					// the_widget( 'WP_Widget_Recent_Posts' );
					/* translators: %1$s: smiley */
					// $excursions_archive_content = '<p>' . sprintf( esc_html__( 'Try looking in the monthly archives. %1$s', 'excursions' ), convert_smilies( ':)' ) ) . '</p>';
					// the_widget( 'WP_Widget_Archives', 'dropdown=1', "after_title=</h2>$excursions_archive_content" );
					// the_widget( 'WP_Widget_Tag_Cloud' );
					?>
				</div>
			</div><!-- .section-container -->
		</section><!-- .error-404 -->

		<?=do_shortcode('[newscards section_id="announcement" future_events="1" promo_posts="2" promo_events="3" read_more="Подробнее..." exclude="312"] ');?>

	</main><!-- #main -->

<?php
get_footer();
