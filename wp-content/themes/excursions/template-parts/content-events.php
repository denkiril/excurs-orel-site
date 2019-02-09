<?php
/**
 * Template part for displaying 'events' posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package excursions
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php
		if ( is_singular() ) :
			the_title( '<h1 class="entry-title">', '</h1>' );
		else :
			the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
		endif; ?>
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php
		
		// the_post_thumbnail();
		the_post_thumbnail('large', 'class=events-image');
		// the_post_thumbnail( array(420, 420) );
		// the_post_thumbnail('medium');
		// the_post_thumbnail('thumbnail');

		the_content();

		wp_link_pages( array(
			'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'excursions' ),
			'after'  => '</div>',
		) );

		
		?>
		<p>end of entry-content</p>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php //excursions_entry_footer(); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->
