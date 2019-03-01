<?php
/**
 * Template part for displaying results in search pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package excursions
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	
	<?php
	$permalink = get_the_permalink();
	$title = esc_html( get_the_title() ); 
	?>

	<div class="row anno-card">
		<div class="col-12 col-md-4">
			<a href="<?=$permalink?>" title="Ссылка на: <?=$title?>" tabindex="-1">
			<?php the_post_thumbnail('medium'); ?>
			</a>
		</div>
		<div class="col-12 col-md-8">
			<h2 class="annocard-title"><a href="<?=$permalink?>" title="Ссылка на: <?=$title?>"><?=$title?></a></h2>
			<p><?php the_excerpt() ?></p>
		</div>
	</div>

</article><!-- #post-<?php the_ID(); ?> -->
