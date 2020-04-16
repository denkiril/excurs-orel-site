<?php
/**
 * The template for displaying front page
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

<main id="main" class="site-main">
<?php
if ( have_posts() ) :
	the_post();
	the_content();

	// [newscards section_title='Путеводитель по Орлу' section_link=/guidebook/ promo_gba=1].
	$nc2_title = trim( carbon_get_the_post_meta( 'nc2_title' ) );
	$nc2_posts = carbon_get_the_post_meta( 'nc2_posts' );
	if ( $nc2_title || count( $nc2_posts ) ) :
		?>
		<section class="section-container">

		<?php
		if ( $nc2_title ) :
			$nc2_title_link = trim( carbon_get_the_post_meta( 'nc2_title_link' ) );
			// echo '<h2>' . esc_html( $nc2_title ) . '</h2>';.
			?>
			<h2>
				<?php if ( $nc2_title_link ) : ?>
					<a href="<?php echo esc_attr( $nc2_title_link ); ?>">
				<?php endif; ?>
				<?php echo esc_html( $nc2_title ); ?>
				<?php if ( $nc2_title_link ) : ?>
					</a>
				<?php endif; ?>
			</h2>
		<?php endif; ?>

		<?php if ( count( $nc2_posts ) ) : ?>
			<div class="row">

			<?php
			// print_r2( $nc2_posts );.
			foreach ( $nc2_posts as $crb_complex ) {
				echo excurs_get_newscard_html( $crb_complex['post'][0]['id'], $crb_complex['card_alt_title'] );
			}
			?>

			</div>
		<?php endif; ?>

		</section>
	<?php endif; ?>

	<?php /* [socwidgets section_title="Мы в соцсетях" vk_mode="3" vk_width="300" vk_height="400" vk_id="94410363" fb=1]. */ ?>
	<section id="soc-section" class="row section-container">
		<div class="col">
			<h2>Мы в соцсетях</h2>

			<div class="row">
				<div class="col-md-6">
					<!-- VK Widget -->
					<div class="socwidget" id="vk_groups" data-id="94410363" data-mode="3" data-width="300" data-height="400"></div>
				</div>
				<div class="col-md-6">
					<div id="fb-root"></div>
					<div class="socwidget fb-group" data-href="https://www.facebook.com/groups/excursorel" data-width="300" data-show-social-context="true" data-show-metadata="false"></div>
				</div>
			</div>
		</div>
	</section>

<?php endif; ?>
</main><!-- #main -->

<?php
get_footer();
