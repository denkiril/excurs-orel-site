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
		$gallery = false;
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
				<?php if( $thumb_id = get_post_thumbnail_id() ): 
					// $full_image_url = get_the_post_thumbnail_url(); 
					$full_image_url = wp_get_attachment_image_url( $thumb_id, 'full' ); 
					$title = get_the_title($thumb_id); 
					$gallery = true; 
					echo markup_fancy_figure($thumb_id, 'gallery', $full_image_url, null, 'medium_large', false, $title, 'events-image');
				endif;

				the_field('event_description');
				?>

			</div>
		</div> <!-- row -->
		<hr /> 
		<?php 
		$location = $event_info['event_place_map'];
		$show_map = $event_info['show_map'] && !empty($location);
		$col_sfx = $show_map ? '-lg-6' : '';
		// get raw date
		$ev_date = get_field('event_info_event_date', false, false);
		$today = date('Ymd');
		// get_field('dev_show_offer'); -- Показывать оффер, даже если событие уже прошло
		$show_offer = get_field('dev_show_offer') ? true : ($ev_date >= $today);
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
					if( $show_offer ){
						$event_date_html = '<span class="attention">'.$event_date_html.'</span>';
					}
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
			if( $show_map ):
				do_action( 'event_map_scripts' ); ?>
				<div class="col<?=$col_sfx?>">
					<button id="OpenMap_btn" class="ref_btn">[ Показать карту ]</button>
					<div class="acf-map">
						<div class="marker" data-lat="<?php echo $location['lat']; ?>" data-lng="<?php echo $location['lng']; ?>"></div>
					</div>
				</div>
			<?php endif; ?>
		</div> <!-- event_info row -->

		<?php if( $event_info_is || $show_map ) echo '<hr />'; ?>

		<?php /* show_offer start */
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

					if( $offer['show_form'] ): ?>
						<div id="reg_form" style="display: none">
							<button id="close_btn">&#10060;</button>
							<?php 
							echo do_shortcode('[contact-form-7 id="285" title="RegForm-1" event-title="' . $event_title . '"]');
							do_action('add_wpcf7_scripts');
							?>
						</div>
					<?php endif; /* show_form end */ ?>

				<?php endif; /* show_registration end */ ?>
			</div>
		</div> 
		<?php endif; /* show_offer end */ ?>

		<?php
		$pre_gallery = do_shortcode('[gallery acf_field=pre-gallery size=medium_large mini=true]');
		if($pre_gallery){
			$gallery = true;
			echo $pre_gallery;
		}

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

		<!-- Yandex.RTB R-A-414612-1 -->
		<div id="yandex_rtb_R-A-414612-1" style="padding: 20px 0 20px 0;"></div>

		<?php 
		/* report_photos */
		$report = get_field('report');
		if ( $report['show'] ) : ?>
		<div class="post-gallery">
			<?php if ( $report['title'] ) : ?>
			<div class="row">
				<div class="col">
					<h2><?=$report['title']?></h2>
				</div>
			</div> <!-- row -->
			<?php endif; ?>

			<?php if ( $report['pre-text'] ) : ?>
			<div class="report-text row">
				<div class="col">
					<?=$report['pre-text']?>
				</div>
			</div> <!-- report-text row -->
			<?php endif; ?>

			<?php 
			$post_gallery = do_shortcode('[gallery acf_field=post-gallery ]');
			if ( $post_gallery ) {
				$gallery = true;
				echo $post_gallery;
			}

			if ( $report['post-text'] ) : ?>
			<div class="report-text row">
				<div class="col">
					<?=$report['post-text']?>
				</div>
			</div> <!-- report-text row -->
			<?php endif; ?>

		</div> <!-- post-gallery -->
		<?php endif;

		$smi = get_field('smi');
		if ( $smi ) : ?>
			<div class="row">
			<div class="col">
				<h2>О нас пишут</h2>
				<ul>
				<?php
				$lines = explode( PHP_EOL, trim($smi) );
				foreach ( $lines as $line ) {
					$sublines = explode( '=', $line, 2 );
					$text = esc_html( trim($sublines[0]) );
					$url = esc_html( trim($sublines[1]) );
					if ( $url ) {
						$ret = parse_url( $url );
						if( ! isset( $ret['scheme'] ) ) {
							$url = "http://{$url}";
						}
						$text = '<a href="'.$url.'" target="_blank" rel="noopener noreferrer">'.$text.'</a>';
					}
					echo '<li>'.$text.'</li>';
				}
				?>
				</ul>
			</div>
		</div> <!-- row -->
		<?php endif;

		/* video */
		// $video = get_field('video'); 
		if ($yt_links = trim(get_field('video_youtube'))) {
			$yt_links = explode(PHP_EOL, $yt_links);
		}
		if ($vk_links = trim(get_field('video_vk'))) {
			$vk_links = explode(PHP_EOL, $vk_links);
		}
		$links_count = 0;
		$links_count = is_array($yt_links) ? $links_count + count($yt_links) : $links_count;
		$links_count = is_array($vk_links) ? $links_count + count($vk_links) : $links_count;
		if ($links_count) :
			$col_sfx = $links_count > 1 ? '-lg-6' : '';
			?>
			<div class="video-gallery">
				<div class="row">
					<div class="col">
						<h2>Видео</h2>
						<button id="ShowVideo_btn" class="ref_btn">[ Показать видео ]</button>
					</div>
				</div> <!-- row -->

				<div class="row">
				<?php 
				// https://youtu.be/MOOqSGOXua0 
				// https://www.youtube.com/watch?v=MOOqSGOXua0&feature=youtu.be&ab_channel=AlekseyBorisov 
				// src="https://www.youtube.com/embed/MOOqSGOXua0?rel=0"
				if (is_array($yt_links)) :
					foreach ($yt_links as $yt_link) :
						$ytarray 	 = explode("/", $yt_link);
						$ytendstring = end($ytarray);
						$ytendarray  = explode("?v=", $ytendstring);
						$ytendstring = end($ytendarray);
						$ytendarray  = explode("&", $ytendstring);
						$ytcode 	 = $ytendarray[0]; 
						?>

						<div class="col<?=$col_sfx?> video-container">
							<iframe data-iframe_src="https://www.youtube.com/embed/<?=$ytcode?>" allow="accelerometer; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
						</div>

					<?php endforeach;
				endif;

				if (is_array($vk_links)) :
					foreach ($vk_links as $vk_link) :?>
				
					<div class="col<?=$col_sfx?> video-container">
						<iframe data-iframe_src="<?=$vk_link?>" allowfullscreen></iframe>
					</div>

					<?php endforeach;
				endif; ?>
				</div> <!-- row -->

			</div><!-- .video-gallery -->
		<?php endif; ?>
		
	</div><!-- .entry-content -->

	<?php
	/* Prev & Next Events */
	$prev_event = null;
	$next_event = null;
	$events_args = array(
		'post_type' 	=> 'events',
		'orderby' 		=> 'meta_value',
		'order'     	=> 'ASC',
		'meta_key' 		=> 'event_info_event_date',
		'numberposts' 	=> -1,
	);
	$events_posts = get_posts( $events_args );
	$event_id = get_queried_object()->ID;
	foreach ( $events_posts as $key => $post ) {
		if ( $event_id == $post->ID ) {
			$this_event_key = $key;
			break;
		}
	}
	if ( $this_event_key ) {
		$prev_event = ( isset( $events_posts[$this_event_key-1] ) ) ? $events_posts[$this_event_key-1] : null;
		$next_event = ( isset( $events_posts[$this_event_key+1] ) ) ? $events_posts[$this_event_key+1] : null;
	}
	
	if ( $prev_event || $next_event ) :
		if ( $show_offer || $report['show'] || $smi || $links_count ) {
			echo '<hr />';
		}
		?>
		<div class="flex-container">
		<?php if ($prev_event) : ?>
			<div class="prevnext-card">
				<p style="text-align: left">&lt;&nbsp;Предыдущее<span class="sm-hide"> событие</span></p>
				<div class="anno-card">
					<?php
						
						$thumb_id = get_post_thumbnail_id($prev_event);
						$permalink = get_the_permalink($prev_event);
						$title = esc_html( get_the_title($prev_event) ); 
						echo '<a href="'.$permalink.'" title="Ссылка на: '.$title.'">';
						echo get_attachment_picture( $thumb_id, 'medium', false, null, true, true );
						echo '</a>';
					?>
					<h2 class="annocard-title"><a href="<?=$permalink?>" title="Ссылка на: <?=$title?>"><?=$title?></a></h2>
				</div>
			</div>
		<?php endif; ?>

		<?php if ($next_event) : ?>
			<div class="prevnext-card">
				<p style="text-align: right">Следующее<span class="sm-hide"> событие</span>&nbsp;&gt;</p>
				<div class="anno-card">
					<?php
						$thumb_id = get_post_thumbnail_id($next_event);
						$permalink = get_the_permalink($next_event);
						$title = esc_html( get_the_title($next_event) ); 
						echo '<a href="'.$permalink.'" title="Ссылка на:'.$title.'">';
						echo get_attachment_picture( $thumb_id, 'medium', false, null, true, true );
						echo '</a>';
					?>
					<h2 class="annocard-title"><a href="<?=$permalink?>" title="Ссылка на: <?=$title?>"><?=$title?></a></h2>
				</div>
			</div>
		<?php endif; ?>
		</div>
	
	<?php endif; ?>
</article><!-- #post-<?php the_ID(); ?> -->

<?php if( $gallery ) do_action( 'add_gallery_scripts' ); ?>