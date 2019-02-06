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

	<!-- <div id="primary" class="content-area"> -->
	<div class="container main-container">
		<main id="main">

		<!-- <section role="announcement">
            <div class="row section-container">
			<div class="col"> -->

		<?php  
		the_post();
		the_content(); 

		do_action( 'anno-cards', 'event', 'Приходите' );

		do_action( 'anno-cards', 'promo', 'Обратите внимание' );

		// $args = array( 'category_name' => 'event' );
		// $myposts = get_posts( $args );
		// if ( $myposts ): 
		// 	echo '<h2>Приходите</h2>';

		// 	foreach( $myposts as $post ){
		// 		setup_postdata( $post ); 
				// do_action( 'anno-card' );
				// $permalink = get_the_permalink(); 
				// $title = esc_html( get_the_title() );
				// $echo = '<div class="row anno-card"><div class="col-12 col-md-4"><a href="' . $permalink . '" title="Ссылка на: '; 
				// $echo .=  $title . '">';
				// echo $echo;
				// the_post_thumbnail('medium');
				// $echo = '</a></div><div class="col-12 col-md-8"><h3>' . $title . '</h3><p>' . get_the_excerpt() . '  ';
				// $echo .= '<a href="' . $permalink . '">Подробнее...</a></p></div></div>';
				// echo $echo; 
		// 		wp_reset_postdata(); 
		// 		}
		// endif; 
		?>

		<?php 
		

		// $args = array( 'category_name' => 'promo' );
		// $myposts = get_posts( $args );
		// if ( $myposts ): 
		?>
		<!-- <h2>Обратите внимание</h2> -->
		<?php 
		// 	foreach( $myposts as $post ){
		// 		setup_postdata( $post ); 
		// 		do_action( 'anno-card' ); 
		// 		}
		// 		wp_reset_postdata(); 
		// endif; 
		?>

		<!-- </div></div></section>  -->
		
		</main><!-- #main -->
	</div> <!-- .main-container -->
	<!--</div> #primary -->

<?php
// get_sidebar();
get_footer();
