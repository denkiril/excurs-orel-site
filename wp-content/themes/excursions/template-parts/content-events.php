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
		$event_title = get_the_title();
		$event_info = get_field('event_info');
		$date_in_title = $event_info['event_date_in_htitle'];
		$event_date = $event_info['event_date'];
		if( $date_in_title && $event_date ) $event_title .= ' / ' . $event_date;
		?>
		<h1 class="entry-title"><?php echo esc_html( $event_title ); ?></h1>
	</header><!-- .entry-header -->

	<div class="entry-content">
		<div class="row">
			<div class="col">
				<?php 
					if( $thumb_id = get_post_thumbnail_id() ): 
					// $full_image_url = get_the_post_thumbnail_url(); 
					$full_image_url = wp_get_attachment_image_url( $thumb_id, 'full' ); 
					$title = get_the_title($thumb_id); ?>
					<a data-fancybox="gallery" href="<?=$full_image_url?>">
					<?php the_post_thumbnail('medium_large', array( 'class' => "events-image", 'title' => $title )); ?>
					</a>
				<?php endif;

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
				{
					$label = $event_info['event_date_label'] ? '<span class="ei_label">' . esc_html( $event_info['event_date_label'] ) . '</span> ' : '';
					$event_date_html = markup_event_date();
					$echo .= $label . $event_date_html . '<br />';
				}
				if( $event_info['show_event_time'] && $event_info['event_time'] )
				{
					$label = $event_info['event_time_label'] ? '<span class="ei_label">' . esc_html( $event_info['event_time_label'] ) . '</span> ' : '';
					$echo .= $label . esc_html( $event_info['event_time'] ) . '<br />';
				}
					// $echo .= esc_html( $event_info['event_time_label'] ) . ' ' . esc_html( $event_info['event_time'] ) . '<br />';
				if( $event_info['show_event_place'] && $event_info['event_place_text'] )
				{
					$label = $event_info['event_place_label'] ? '<span class="ei_label">' . esc_html( $event_info['event_place_label'] ) . '</span> ' : '';
					$echo .= $label . esc_html( $event_info['event_place_text'] ) . '<br />';
				}
					// $echo .= esc_html( $event_info['event_place_label'] ) . ' ' . esc_html( $event_info['event_place_text'] ) . '<br />';
				$show_guide = $event_info['show_guide'] && ($event_info['guide_text'] || $event_info['guide_names']);
				if( $echo && $show_guide )
					$echo .= '<br />';
				if( $show_guide )
				{
					$guide_text = $event_info['guide_text'];
					if( !$guide_text ){
						$names = $event_info['guide_names'];
						$guide_text = implode(', ', $names);
					}
					$label = $event_info['guide_label'] ? '<span class="ei_label">' . esc_html( $event_info['guide_label'] ) . '</span> ' : '';
					$echo .= $label . esc_html( $guide_text ) . '<br />';
				}

				if( $echo ) {
					$echo = '<p>' . $echo . '</p>';
					$event_info_is = true;
				}
				echo $echo; ?>
			</div>
			<?php // $show_map id="map" 
			if( $show_map ):  ?>
				<div class="col<?=$col_sfx?>">
					<button id="OpenMap_btn" class="ref_btn">[ Показать карту ]</button>
					<div class="acf-map">
						<div class="marker" data-lat="<?php echo $location['lat']; ?>" data-lng="<?php echo $location['lng']; ?>"></div>
					</div>
				</div>
				<?php // do_action( 'add_map_scripts' ); ?>
			<?php endif; ?>
		</div> <!-- event_info row -->

		<?php if( $event_info_is || $show_map ) echo '<hr />'; ?>

		<?php /* show_offer start */
		// get raw date
		$ev_date = get_field('event_info_event_date', false, false);
		$today = date('Ymd');
		// get_field('dev_show_offer'); -- Показывать оффер, даже если событие уже прошло
		$show_offer = get_field('dev_show_offer') ? true : ($ev_date >= $today);
		if( $show_offer ): ?>
		<div class="row">
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
					if( $offer['alt_registration_text'] )
						echo '<li>' . esc_html( $offer['alt_registration_text'] ) . ' </li>';
					if( $offer['show_form'] ): ?>
						<li><?=esc_html( $offer['form_registration_text'] )?> <button id="reg_btn" class="ref_btn">[ Открыть форму записи ]</button></li>
					<?php endif;
					echo '</ul>';

					if( $offer['show_form'] ): 
						do_action( 'add_wpcf7_scripts' ); ?>
						<div id="reg_form" style="display: none">
							<button id="close_btn">&#10060;</button>
							<?= do_shortcode('[contact-form-7 id="285" title="RegForm-1" event-title="' . $event_title . '"]'); ?>
						</div>
					<?php endif; /* show_form end */ ?>

				<?php endif; /* show_registration end */ ?>
			</div>
		</div> 
		<?php endif; /* show_offer end */ ?>

		<?php
		// the_content();

		//Get the images ids from the post_metadata
		$images = acf_photo_gallery('pre-gallery', $post->ID);
		//Check if return array has anything in it
		if( count($images) ): ?>
			<div class="pre-gallery row">
				<?php //Cool, we got some data so now let's loop over it
				foreach($images as $image):
					$id = $image['id']; // The attachment id of the media
					$title = $image['title']; //The title
					$description = $image['caption']; //The caption (Description!)
					$full_image_url= $image['full_image_url']; //Full size image url
					// $full_image_url = acf_photo_gallery_resize_image($full_image_url, 262, 160); //Resized size to 262px width by 160px height image url
					// $thumbnail_image_url= $image['thumbnail_image_url']; //Get the thumbnail size image url 150px by 150px
					// $url= $image['url']; //Goto any link when clicked
					// $target= $image['target']; //Open normal or new tab
					// $alt = get_field('photo_gallery_alt', $id); //Get the alt which is a extra field (See below how to add extra fields)
					// $class = get_field('photo_gallery_class', $id); //Get the class which is a extra field (See below how to add extra fields)
					// $alt = get_post_meta( $id, '_wp_attachment_image_alt', true );
					?>
					<div class="acf_gallery-item col-12 col-sm-6 col-md-6 col-lg-4">
						<figure>
							<a data-fancybox="gallery" href="<?=$full_image_url?>" data-caption="<?=$title?>">
							<?= wp_get_attachment_image( $id, 'medium_large', false,  // (thumbnail, medium, large, full or custom size) 
								array( 'title' => $title) ); ?>
							</a>
							<?php if( $description ) echo '<figcaption>' . $description . '</figcaption>'; ?>
						</figure>
					</div>
				<?php endforeach; ?>
			</div> <!-- pre-gallery row -->
		<?php endif; ?>

		<?php 
		$show_rules = get_field('rules_show_rules');
		$alt_text = get_field('rules_alt_text');
		if( $show_offer && ($show_rules || $alt_text) ): ?>
			<div class="row">
				<div class="col">
					<?php if( $show_rules ): ?>
					<p>Участвуя в наших мероприятиях вы соглашаетесь с <a href="/events-rules/" target="_blank">Правилами проведения мероприятий</a> организации «Экскурсии по Орлу».</p>
					<?php endif; ?>
					<?php if( $alt_text ) echo $alt_text; ?>
				</div>
			</div> <!-- row -->
		<?php endif; ?>

		<?php 
		/* report_photos */
		$report = get_field('report');
		if( $report['show'] ): ?>
		<div class="post-gallery">
			<?php if( $report['title'] ): ?>
			<div class="row">
				<div class="col">
					<h2><?=$report['title']?></h2>
				</div>
			</div> <!-- row -->
			<?php endif; ?>

			<?php if( $report['pre-text'] ): ?>
			<div class="report-text row">
				<div class="col">
					<?=$report['pre-text']?>
				</div>
			</div> <!-- report-text row -->
			<?php endif; ?>

			<?php 
			$images = acf_photo_gallery('post-gallery', $post->ID);
			if( count($images) ): ?>
			<div class="row">
				<?php 
				foreach($images as $image):
					$id = $image['id']; // The attachment id of the media
					$title = $image['title']; //The title
					$description = $image['caption']; //The caption (Description!)
					$full_image_url= $image['full_image_url']; //Full size image url
					// $full_image_url = acf_photo_gallery_resize_image($full_image_url, 262, 160); //Resized size to 262px width by 160px height image url
					// $thumbnail_image_url= $image['thumbnail_image_url']; //Get the thumbnail size image url 150px by 150px
					// $url= $image['url']; //Goto any link when clicked
					// $target= $image['target']; //Open normal or new tab
					// $alt = get_field('photo_gallery_alt', $id); //Get the alt which is a extra field (See below how to add extra fields)
					// $class = get_field('photo_gallery_class', $id); //Get the class which is a extra field (See below how to add extra fields)
					// $alt = get_post_meta( $id, '_wp_attachment_image_alt', true );
					?>
					<div class="acf_gallery-item col-sm-12 offset-md-1 col-md-10">
						<figure>
							<a data-fancybox="gallery" href="<?=$full_image_url?>" data-caption="<?=$title?>">
							<?= wp_get_attachment_image( $id, 'medium_large', false,  // (thumbnail, medium, large, full or custom size) 
								array( 'title' => $title ) ); ?>
							</a>
							<?php if( $description ) echo '<figcaption>' . $description . '</figcaption>'; ?>
						</figure>
					</div>
				<?php endforeach; ?>
			</div> <!-- row -->
			<?php endif; ?>

			<?php if( $report['post-text'] ): ?>
			<div class="report-text row">
				<div class="col">
					<?=$report['post-text']?>
				</div>
			</div> <!-- report-text row -->
			<?php endif; ?>

		</div> <!-- post-gallery -->
		<?php endif;

		/* video */
		// $video = get_field('video'); 
		$yt_link = get_field('video_youtube');
		$vk_link = get_field('video_vk');
		if( $yt_link || $yt_link ): ?>
		<div class="video-gallery">
			<div class="row">
				<div class="col">
					<h2>Видео</h2>
				</div>
			</div> <!-- row -->

			<?php 
			// https://youtu.be/MOOqSGOXua0 
			// https://www.youtube.com/watch?v=MOOqSGOXua0&feature=youtu.be&ab_channel=AlekseyBorisov 
			// src="https://www.youtube.com/embed/MOOqSGOXua0?rel=0"
			if( $yt_link ): 
				$ytarray 	 = explode("/", $yt_link);
				$ytendstring = end($ytarray);
				$ytendarray  = explode("?v=", $ytendstring);
				$ytendstring = end($ytendarray);
				$ytendarray  = explode("&", $ytendstring);
				$ytcode 	 = $ytendarray[0]; 
				?> 
				<div class="row">
					<div class="col video-container">
						<iframe src="https://www.youtube.com/embed/<?=$ytcode?>" frameborder="0" allow="accelerometer; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
					</div>
				</div> <!-- row -->
			<?php endif; ?>

			<?php 
			if( $vk_link ): ?>
				<div class="row">
					<div class="col video-container">
						<iframe src="<?=$vk_link?>" frameborder="0" allowfullscreen></iframe>
					</div>
				</div> <!-- row -->
			<?php endif; ?>

		<?php endif; ?>
		
	</div><!-- .entry-content -->
</article><!-- #post-<?php the_ID(); ?> -->
