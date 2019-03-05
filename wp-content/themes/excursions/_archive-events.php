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

<main id="main" class="site-main archive-events">
<!-- <div class="row section-container"> -->
	<!-- <div class="col"> -->

	<?php 
		// global $query_string; // параметры базового запроса
		// query_posts( $query_string .'&orderby=meta_value&meta_key=event_info_event_date&posts_per_page=5' );
		// query_posts( 'post_type=events&orderby=event_info_event_date&order=DESC&posts_per_page=10' );
		// query_posts( 
		// 	array ( 'post_type' => 'events', 'orderby' => 'meta_value', 'meta_key' => 'event_info_event_date', 'posts_per_page' => '5' )
		// );

		// $paged = get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1;
		// $args = array( 
		// 	'post_type' 	=> 'events', 
		// 	'orderby' 		=> 'meta_value', 
		// 	'meta_key' 		=> 'event_info_event_date', 
		// 	'posts_per_page'=> '5',
		// 	'paged'         => $paged,
		// );
		// global $post;
		// $myposts = get_posts( $args );

		// if ( $myposts ):
		// 	foreach( $myposts as $post ):
		// 	setup_postdata( $post );

		// $query1 = new WP_Query( $args ); 

		// if( $query1->have_posts() ):
		// 	while( $query1->have_posts() ):
		// 	$query1->the_post();
		
		if( have_posts() ):

			// <header class="page-header"> 
			// the_archive_title( '<h1 class="page-title">', '</h1>' );
			// the_archive_description( '<div class="archive-description">', '</div>' );
			// </header>

			while ( have_posts() ):
			the_post();
		
			// echo do_shortcode('[annocards post_type="events" section_title="Актуальные события" date="future" exclude="312"]');
			// echo do_shortcode('[annocards post_type="events" section_title="Прошедшие события" date="past"]');
			$permalink = get_the_permalink();
			$title = esc_html( get_the_title() ); 
			$event_date = markup_event_date();
			// $ev_date = get_field('event_info_event_date', false, false);
			// $today = date('Ymd');
			$show_attention = get_field('event_info_event_date', false, false) >= date('Ymd');
			?>		 

			<div class="row anno-card">
				<div class="col-12 col-md-4">
					<a href="<?=$permalink?>" title="Ссылка на: <?=$title?>" tabindex="-1">
					<?php the_post_thumbnail('medium'); ?>
					</a>
				</div>
				<div class="col-12 col-md-8">
					<h2 class="annocard-title"><a href="<?=$permalink?>" title="Ссылка на: <?=$title?>"><?=$title?></a></h2>
					<?php if( $show_attention ): ?>
						<p class="attention">Не пропустите!</p>
					<?php endif; ?>
					<p><?php echo $event_date . get_the_excerpt() ?></p>
				</div>
			</div>

			<?php 
			endwhile; 
			
			the_posts_pagination();

			// wp_reset_postdata();
			// posts_nav_link();
			// пагинация для произвольного запроса
			// $big = 999999999; // уникальное число

			// echo paginate_links( array(
			// 	'base'    => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
			// 	'format'  => '?paged=%#%',
			// 	'current' => max( 1, get_query_var('paged') ),
			// 	'total'   => $query1->max_num_pages
			// ) );
	
		else :

			get_template_part( 'template-parts/content', 'none' );

		endif;

		// wp_reset_query(); // сброс запроса
	?>
	
	<!-- </div> -->
<!--</div>  row section-container -->
</main><!-- #main -->

<?php
// get_sidebar();
get_footer();
