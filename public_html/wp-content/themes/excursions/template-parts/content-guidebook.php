<?php
/**
 * Template part for displaying guidebook posts 
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package excursions
 */

?>

<?php

// $OKN_URL = 'https://orel-region.ru/index.php?head=6&part=73&unit=401&op=8&in=168';
$OKN_URL = 'https://opendata.mkrf.ru/opendata/7705851331-egrkn';
// $OKN_TXT = 'Реестр ОКН, расположенных на территории Орловской области';
$OKN_TXT = 'Сведения из Единого госреестра ОКН (сайт Минкультуры России)';

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

<header class="entry-header">
	<?php 
	$gallery = false;
	the_title('<h1 class="entry-title">', '</h1>');
	?>
</header><!-- .entry-header -->

<div class="entry-content">
	<div class="row">
		<div class="col">
		<?php if ($thumb_id = get_post_thumbnail_id()) :
			// $full_image_url = get_the_post_thumbnail_url(); 
			$full_image_url = wp_get_attachment_image_url($thumb_id, 'full');
			$title = get_the_title($thumb_id); 
			$gallery = true; 
			echo markup_fancy_figure($thumb_id, 'gallery', $full_image_url, null, 'medium_large', false, $title, 'events-image');
			
				// <a data-fancybox="gallery" href="<=$full_image_url>">
				// $attr = array( 'class' => "events-image", 'title' => $title );
				// the_post_thumbnail('medium_large', array( 'class' => "events-image", 'title' => $title )); 
				// echo get_attachment_picture( $thumb_id, 'medium_large', false, $attr, false );
				// </a>
		endif; 
		
		the_field('gba_intro');
		?>

		</div>
	</div> <!-- row -->

	<hr />
	<?php 
	$obj_info = get_field('obj_info');

	$obj_info_text = '';

	$is_okn = $obj_info['is_okn'];
	if ($is_okn) {
		// $event_date_html = markup_event_date();
		$obj_info_text .= '<h2>Является объектом культурного наследия (ОКН)</h2>';
	}

	if ($obj_info['okn_type']) {
		$label = '<span class="ei_label">Тип объекта:</span> ';
		switch ($obj_info['okn_type']) {
			case 'a': $okn_type = 'Памятник археологии'; 						break;
			case 'g': $okn_type = 'Памятник архитектуры и градостроительства'; 	break;
			case 'h': $okn_type = 'Памятник истории'; 							break;
			case 'i': $okn_type = 'Памятник искусства'; 						break;
		}
		$obj_info_text .= $label . $okn_type . '<br />';
	}

	if ($obj_info['protection_category']) {
		$label = '<span class="ei_label">Категория охраны:</span> ';
		switch ($obj_info['protection_category']) {
			case 'f': $protection_category = 'Федерального значения'; 	break;
			case 'r': $protection_category = 'Регионального значения'; 	break;
			case 'm': $protection_category = 'Местного значения'; 		break;
			case 'v': $protection_category = 'Выявленный объект'; 		break;
		}
		$obj_info_text .= $label . $protection_category . '<br />';
	}

	if ($obj_info['registry_name']) {
		$label = '<span class="ei_label">Наименование ОКН:</span> ';
		$obj_info_text .= $label . esc_html($obj_info['registry_name']) . '<br />';
	}

	if ($obj_info['okn_date']) {
		$label = '<span class="ei_label">Датировка (по реестру ОКН):</span> ';
		$obj_info_text .= $label . esc_html($obj_info['okn_date']) . '<br />';
	}

	if ($obj_info['location']) {
		$label = '<span class="ei_label">Местонахождение:</span> ';
		$obj_info_text .= $label . esc_html($obj_info['location']) . '<br />';
	}

	if ($obj_info['district']) {
		$label = '<span class="ei_label">Район:</span> ';
		switch ($obj_info['district']) {
			case '1': $district = 'Заводской'; 			break;
			case '2': $district = 'Железнодорожный'; 	break;
			case '3': $district = 'Советский'; 			break;
		}
		$obj_info_text .= $label . $district . '<br />';
	}

	$doc_link = null;
	$doc_title = null;
	$registry_date = esc_html($obj_info['registry_date']);
	if ($registry_date) {
		$dates = explode(',', $registry_date);
		$registry_dates = [];
		foreach ($dates as $date) {
			$date = trim($date);
			switch ($date) {
				case '1960': $doc_path = 'postanovlenie-sovmina-rsfsr-ot-30-avg-1960-1327'; break;
				case '1974': $doc_path = 'postanovlenie-sovmina-rsfsr-4-dek-1974-n624'; break;
				default: $doc_path = null;
			}
			if ($doc_path) {
				$post_obj = get_page_by_path($doc_path, OBJECT, 'guidebook');
				if ($post_obj) {
					$post_id = (int) $post_obj->ID;
					$doc_title = esc_html(get_the_title($post_id));
					$doc_link = get_permalink($post_id);
	
					$title = $doc_title ? ' title="'.$doc_title.'"' : '';
					if ($doc_link) {
						$date = '<a href="'.$doc_link.'"'.$title.'">'.$date.'</a>';
					}
				}
			}
			$registry_dates[] = $date;
		}
		$label = '<span class="ei_label">Год постановки на охрану:</span> ';
		$obj_info_text .= $label . implode(', ', $registry_dates) . '<br />';
	}

	if ($obj_info['okn_id']) {
		$label = '<span class="ei_label">Номер в реестре ОКН:</span> ';
		$obj_info_text .= $label . esc_html($obj_info['okn_id']) . '<br />';
	}

	if ($obj_info['founding_date']) {
		$label = '<span class="ei_label">Дата основания:</span> ';
		$obj_info_text .= $label . esc_html($obj_info['founding_date']) . '<br />';
	}

	if ($obj_info['site']) {
		$site_url = esc_html(trim($obj_info['site']));
		if ($site_url) {
			$label = '<span class="ei_label">Официальный сайт:</span> ';
			$url = $site_url;
			$ret = parse_url($url);
			if (!isset($ret['scheme'])) {
				$url = "http://{$url}";
			} else {
				// $site_url = preg_replace("(^https?://)", "", $site_url );
				$site_url = $ret['host'] . $ret['path'];
			}
			$obj_info_text .= $label.'<a href="'.$url.'" target="_blank" rel="noopener noreferrer">'.$site_url.'</a><br />';
		}
	}

	if ($obj_info['more_info']) {
		$more_lines = explode(PHP_EOL, $obj_info['more_info']);
		foreach ($more_lines as $line) {
			$sublines = explode(':', $line, 2);
			$label = '<span class="ei_label">'.esc_html(trim($sublines[0])).':</span> ';
			$value = count($sublines) > 1 ? esc_html(trim($sublines[1])) : '';
			$obj_info_text .= $label . $value.'<br />';
		}
	}
	
	if ($obj_info_text) {
		$obj_info_text = '<p>'.$obj_info_text.'</p>';
	}
	
	$geolocation = $obj_info['geolocation'];
	$gba_content = wiki_parse(get_field('gba_content'));
	$sights = $gba_content['sights'];
	// if ($sights) print_r2($sights);
	$col_sfx = ($obj_info_text && ($geolocation || $sights)) ? '-lg-6' : '';

	if ($obj_info_text || $geolocation || $sights) : ?>
		<div class="event-info row">
			<?php if ($obj_info_text) : ?>
			<div class="col<?php echo $col_sfx?>">
				<?php echo $obj_info_text; ?>
			</div>
			<?php endif;
			if ($geolocation || $sights) :
				do_action('event_map_scripts'); ?>
				<div class="col<?php echo $col_sfx ?>">
					<?php if ($geolocation) : ?>
						<div class="mini-map" data-sights="sights">
							<noscript>Если включите JavaScript, здесь отобразится карта.</noscript>
							<button id="OpenMap_btn" class="ref_btn autoopen">[ Показать на карте ]</button>
							<div class="marker" data-lat="<?php echo $geolocation['lat']; ?>" data-lng="<?php echo $geolocation['lng']; ?>" data-post_id="<?php the_ID(); ?>"></div>
					<?php else : ?>
						<div class="mini-map mini-map-pregrow" data-sights="<?php echo esc_html(json_encode($sights)); ?>">
							<noscript>Если включите JavaScript, здесь отобразится карта.</noscript>
							<div class="map-cover"></div>
							<button id="OpenMap_btn" class="ref_btn">[ Показать на карте ]</button>
					<?php endif; ?>
						</div>
				</div>
			<?php endif; ?>
		</div> <!-- .event-info -->
	<?php endif; ?>

	<?php if ($obj_info_text) echo '<hr />'; ?>

	<?php
	$gba_content_text = $gba_content['text'];
	if ($gba_content_text) : ?>
		<div class="row info-block">
			<div class="col">
				<?php echo $gba_content_text ?>
			</div>
		</div> <!-- .info-block -->
	<?php endif; ?>

	<?php 
	$gba_sources = get_field('gba_sources');
	$gba_sources_posts = get_field('gba-sources-posts');
	if ($gba_sources || $gba_sources_posts || ($doc_link && $doc_title) || $is_okn) :
	?>
		<div class="row info-block">
			<div class="col">
				<h2>Источники</h2>
				<ul>
				<?php
				$echo = '';
				$egrkn_printed = false;
				if ($gba_sources) {
					$lines = explode(PHP_EOL, trim($gba_sources));
					foreach ($lines as $line) {
						$sublines = explode('=', $line, 2);
						$text = esc_html(trim($sublines[0]));
						$parse_url = false;
						switch ($text) {
							case '[egrkn]': $text = $OKN_TXT; $url = $OKN_URL; $egrkn_printed = true; break;
							default: $parse_url = true;
						}
						if ($parse_url) {
							$url = count($sublines) > 1 ? esc_html(trim($sublines[1])) : null;
							if ($url) {
								$ret = parse_url($url);
								if (!isset($ret['scheme'])) {
									$url = "http://{$url}";
								}
							}
						}
						if ($url) {
							$text = '<a href="'.$url.'" target="_blank" rel="noopener noreferrer">'.$text.'</a>';
						}
						$echo .= '<li>'.$text.'</li>';
					}
				}
				if ($gba_sources_posts) {
					global $post;
					foreach ($gba_sources_posts as $post) {
						setup_postdata($post);
						// $post_id = $post->ID;
						$permalink = get_permalink();
						if ($permalink) {
							$title = esc_html(get_the_title());
							$echo .= '<li><a href="'.$permalink.'">'.$title.'</a></li>';
						}
					}
					wp_reset_postdata();
				}
				if ($doc_link && $doc_title) {
					$echo .= '<li><a href="'.$doc_link.'" title="'.$doc_title.'">'.$doc_title.'</a></li>';
				} elseif ($is_okn && !$egrkn_printed) {
					$echo .= '<li><a href="'.$OKN_URL.'" target="_blank" rel="noopener noreferrer">'.$OKN_TXT.'</a></li>';
				}
				echo $echo;
				?>
				</ul>
			</div>
		</div> <!-- .info-block -->
	<?php endif; ?>

	<?php
	$posts = get_field('see_also');
	if ($posts) : ?>
		<div class="info-block">
			<h2>См. также</h2>
			<div class="row">
			<?php
			$echo = '';
			global $post;
			foreach ($posts as $post) {
				setup_postdata($post);
				// $post_id = $post->ID;
				$permalink = get_permalink();
				if ($permalink) {
					$title = esc_html(get_the_title());
					$thumb_id = get_post_thumbnail_id();
					$echo .= '<div class="anno-card col-6 col-sm-6 col-md-4 col-lg-3">';
					$echo .= '<a href="'.$permalink.'" title="Ссылка на '.$title.'" tabindex="-1">';
					$echo .= get_attachment_picture( $thumb_id, 'medium', false, null, true, true ); // medium_large 
					$echo .= '</a><h3 class="annocard-caption"><a href="'.$permalink.'" title="'.$title.'">'.$title.'</a></h3></div>';
				}
			}
			wp_reset_postdata();
			echo $echo;
			?>
			</div>
		</div> <!-- .info-block -->
	<?php endif; ?>

</div><!-- .entry-content -->

</article><!-- #post-<?php the_ID(); ?> -->

<?php if ($gallery) do_action('add_gallery_scripts'); ?>
