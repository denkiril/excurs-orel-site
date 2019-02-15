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
		$title = get_the_title();
		$event_info = get_field('event_info');
		$date_in_title = $event_info['event_date_in_htitle'];
		$event_date = $event_info['event_date'];
		if( $date_in_title && $event_date ) $title .= ' / ' . $event_date;
		?>
		<h1 class="entry-title"><?php echo esc_html( $title ); ?></h1>
	</header><!-- .entry-header -->

	<div class="entry-content">
		<div class="row">
			<div class="col">
				<?php
				the_post_thumbnail('large', 'class=events-image'); 
				the_field('event_description');
				// the_post_thumbnail();
				// the_post_thumbnail( array(420, 420) );
				// the_post_thumbnail('medium');
				// the_post_thumbnail('thumbnail');
				// if( $event_date ) echo '<p>' . $event_date . '</p>'; ?>
			</div>
		</div> <!-- row -->
		<hr /> 
		<?php 
		$location = $event_info['event_place_map'];
		$show_map = $event_info['show_map'] && !empty($location);
		$col_sfx = $show_map ? '-lg-6' : '';
		?>
		<div class="event-info row">
			<div class="col<?=$col_sfx?>">
				<?php 
				$event_info_is = false;
				$echo = '';
				if( $event_info['show_event_date']  && $event_date )
					$echo .= esc_html( $event_info['event_date_label'] ) . ' ' . esc_html( $event_date ) . '<br />';
				if( $event_info['show_event_time'] && $event_info['event_time'] )
					$echo .= esc_html( $event_info['event_time_label'] ) . ' ' . esc_html( $event_info['event_time'] ) . '<br />';
				if( $event_info['show_event_place'] && $event_info['event_place_text'] )
					$echo .= esc_html( $event_info['event_place_label'] ) . ' ' . esc_html( $event_info['event_place_text'] ) . '<br />';
				if( $event_info['show_guide'] && $event_info['guide_text'] )
					$echo .= esc_html( $event_info['guide_label'] ) . ' ' . esc_html( $event_info['guide_text'] ) . '<br />';

				if( $echo ) {
					$echo = '<p>' . $echo . '</p>';
					$event_info_is = true;
				}
				echo $echo; ?>
			</div>
			<?php if( $show_map ): ?>
				<div class="col<?=$col_sfx?>">
					<div class="acf-map">
						<div class="marker" data-lat="<?php echo $location['lat']; ?>" data-lng="<?php echo $location['lng']; ?>"></div>
						<!-- <div class="marker" data-lat="52.967580" data-lng="36.069585"></div> -->
					</div>
				</div>
			<?php endif; ?>
		</div> <!-- event_info row -->

		<?php if( $event_info_is || $show_map ) echo '<hr />'; ?>

		<div class="event-info row">
			<div class="col">
				<?php
				$offer = get_field('offer');
				if( $offer['is_free'] ) echo $offer['free_text'];
				if( $offer['is_paid'] ) echo $offer['paid_text'];

				if( $offer['show_registration'] ): 
					echo $offer['registration_text'];
					echo '<ul>';
					if( $offer['show_vk'] && $offer['vk_ref'] )
					{
						$ref_text = $offer['vk_ref_text'] ? $offer['vk_ref_text'] : $offer['vk_ref'];
						echo '<li>' . esc_html( $offer['vk_registration_text'] ) . ' <a href="' . esc_url( $offer['vk_ref'] ) . '" target="_blank">' . $ref_text . '</a></li>';
					}
					if( $offer['show_fb'] && $offer['fb_ref'] )
					{
						$ref_text = $offer['fb_ref_text'] ? $offer['fb_ref_text'] : $offer['fb_ref'];					
						echo '<li>' . esc_html( $offer['fb_registration_text'] ) . ' <a href="' . esc_url( $offer['fb_ref']) . '" target="_blank">' . $ref_text . '</a></li>';
					}
					if( $offer['show_form'] )
						echo '<li>' . esc_html( $offer['form_registration_text'] ) . ' <a href="' . esc_url( $offer['vk_ref']) . '" target="_blank">[ Записаться на экскурсию ]</a></li>';
					echo '</ul>';
				endif; ?>
			</div>
		</div> <!-- offer row -->

		
		<?php
		// the_content();

		//Get the images ids from the post_metadata
		$images = acf_photo_gallery('anno_gallery', $post->ID);
		//Check if return array has anything in it
		if( count($images) ): ?>
			<div class="acf_gallery row">
				<!-- <div class="col"> -->
					<!-- <div class="acf_gallery"> -->
						<?php //Cool, we got some data so now let's loop over it
						foreach($images as $image):
							$id = $image['id']; // The attachment id of the media
							$title = $image['title']; //The title
							$caption= $image['caption']; //The caption
							$full_image_url= $image['full_image_url']; //Full size image url
							$full_image_url = acf_photo_gallery_resize_image($full_image_url, 262, 160); //Resized size to 262px width by 160px height image url
							$thumbnail_image_url= $image['thumbnail_image_url']; //Get the thumbnail size image url 150px by 150px
							$url= $image['url']; //Goto any link when clicked
							$target= $image['target']; //Open normal or new tab
							// $alt = get_field('photo_gallery_alt', $id); //Get the alt which is a extra field (See below how to add extra fields)
							// $class = get_field('photo_gallery_class', $id); //Get the class which is a extra field (See below how to add extra fields)
							// $alt = get_post_meta( $id, '_wp_attachment_image_alt', true );
							?>
							<div class="acf_gallery-item col-6 col-sm-4 col-md-3 col-lg-2">
								<figure>
									<?php 
									echo wp_get_attachment_image( $id, 'large' ); // (thumbnail, medium, large, full or custom size) 
									if( $caption ) echo '<figcaption>' . $caption . '</figcaption>';
									?>
								</figure>
							</div>
						<?php endforeach; ?>
					<!--</div>  acf_gallery -->
				<!-- </div> -->
			</div> <!-- acf_gallery row -->
		<?php endif; ?>

		<?php if( get_field('rules')['show_rules'] ): ?>
			<div class="row">
				<div class="col">
					<?php the_field('rules_rules_text'); ?>
					<?php // echo get_field('rules')['rules_text']; ?>
				</div>
			</div> <!-- row -->
		<?php endif; ?>
		
	</div><!-- .entry-content -->
</article><!-- #post-<?php the_ID(); ?> -->
