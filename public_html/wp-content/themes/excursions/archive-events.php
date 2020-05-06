<?php
/**
 * The template for displaying events archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package excursions
 */

get_header();
?>

<main id="main" class="site-main archive-events">

	<?php
	if ( have_posts() ) :
		do_action( 'events_map_scripts' );
		?>

		<div class="events_map" data-state="init" style="display: none;">
			<div class="events_block events_block_panel">
				<button class="NewEventsMap_btn">
					<span class="state_init">[ Показать на карте ]</span>
					<span class="state_open">[ Закрыть карту ]</span>
					<span class="state_close">[ Открыть карту ]</span>
				</button>
			</div>
		</div>

		<?php
		date_default_timezone_set( 'Europe/Moscow' );
		$today        = date( 'Ymd' );
		$current_time = date( 'H:i' );

		while ( have_posts() ) :
			the_post();

			$event_date     = get_field( 'event_info_event_date', false, false );
			$show_attention = $event_date > $today;
			if ( $event_date === $today ) {
				$event_time     = get_field( 'event_info_event_time' );
				$show_attention = $event_time > $current_time;
			}
			?>

			<div class="row anno-card">
				<div class="col-12 col-md-4">
					<a href="<?php the_permalink(); ?>" <?php excurs_the_title_attr(); ?> tabindex="-1">
						<?php the_attachment_picture( get_post_thumbnail_id(), 'medium_large' ); ?>
					</a>
				</div>
				<div class="col-12 col-md-8">
					<h2 class="annocard-title">
						<a href="<?php the_permalink(); ?>">
							<?php the_title(); ?>
						</a>
					</h2>
					<?php if ( $show_attention ) : ?>
						<p class="attention">Не пропустите!</p>
					<?php endif; ?>
					<p>
						<?php echo markup_event_date() . esc_html( get_the_excerpt() ); ?>
					</p>
				</div>
			</div>

			<?php
		endwhile;

		the_posts_pagination();

	else :

		get_template_part( 'template-parts/content', 'none' );

	endif;
	?>

</main><!-- #main -->

<?php
get_footer();
