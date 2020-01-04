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
	if ($obj_info['is_okn']) {
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
	if ($obj_info['registry_date']) {
		$label = '<span class="ei_label">Год постановки на охрану:</span> ';
		$obj_info_text .= $label . esc_html( $obj_info['registry_date'] ) . '<br />';
	}
	if ($obj_info['okn_id']) {
		$label = '<span class="ei_label">Номер в реестре ОКН:</span> ';
		$obj_info_text .= $label . esc_html( $obj_info['okn_id'] ) . '<br />';
	}
	if ($obj_info['founding_date']) {
		$label = '<span class="ei_label">Дата основания:</span> ';
		$obj_info_text .= $label . esc_html( $obj_info['founding_date'] ) . '<br />';
	}
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
		$obj_info_text .= $label . '<a href="'.$url.'" target="_blank" rel="noopener noreferrer">'.$site_url.'</a><br />';
	}
	if ($obj_info['more_info']) {
		$more_lines = explode(PHP_EOL, $obj_info['more_info']);
		foreach ($more_lines as $line) {
			$sublines = explode(':', $line, 2);
			$label = '<span class="ei_label">'.esc_html( trim($sublines[0]) ).':</span> ';
			$obj_info_text .= $label . esc_html( trim($sublines[1]) ) . '<br />';
		}
	}
	
	if ($obj_info_text) {
		$obj_info_text = '<p>' . $obj_info_text . '</p>';
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
							<button id="OpenMap_btn" class="ref_btn autoopen">[ Показать на карте ]</button>
							<div class="marker" data-lat="<?php echo $geolocation['lat']; ?>" data-lng="<?php echo $geolocation['lng']; ?>" data-post_id="<?php the_ID(); ?>"></div>
					<?php else : ?>
						<div class="mini-map mini-map-pregrow" data-sights="<?php echo esc_html(json_encode($sights)); ?>">
							<div class="map-cover"></div>
							<button id="OpenMap_btn" class="ref_btn">[ Показать на карте ]</button>
					<?php endif; ?>
						</div>
				</div>
			<?php endif; ?>
		</div> <!-- event_info row -->
	<?php endif; ?>

	<?php if ($obj_info_text) echo '<hr />'; ?>

	<?php
	$gba_content_text = $gba_content['text'];
	if ($gba_content_text) : ?>
		<div class="row info-block">
			<div class="col">
				<?php echo $gba_content_text ?>
			</div>
		</div> <!-- row -->
	<?php endif; ?>

	<?php 
	$gba_sources = get_field('gba_sources');
	$gba_sources_posts = get_field('gba_sources_posts');
	if ($gba_sources || $gba_sources_posts || $obj_info['is_okn']) :
	?>
		<div class="row info-block">
			<div class="col">
				<h2>Источники</h2>
				<ul>
				<?php
				if ($gba_sources) {
					$lines = explode(PHP_EOL, trim($gba_sources));
					foreach ($lines as $line) {
						$sublines = explode('=', $line, 2);
						$text = esc_html(trim($sublines[0]));
						$url = count($sublines) > 1 ? esc_html(trim($sublines[1])) : null;
						// print_r("url=".$url);
						if ($url) {
							$ret = parse_url($url);
							if (!isset($ret['scheme'])) {
								$url = "http://{$url}";
							}
							$text = '<a href="'.$url.'" target="_blank" rel="noopener noreferrer">'.$text.'</a>';
						}
						// echo '<li><a href="'.$href.'" target="_blank" rel="noopener noreferrer">'.$title.'</a></li>';
						echo '<li>'.$text.'</li>';
					}
				}
				if ($gba_sources_posts) {
					foreach ($gba_sources_posts as $post) {
						if ($permalink = get_the_permalink($post->ID)) {
							echo '<li><a href="'.$permalink.'">'.esc_html($post->post_title).'</a></li>';
						}
					}
				}
				if ($obj_info['is_okn']) {
					echo '<li><a href="'.$OKN_URL.'" target="_blank" rel="noopener noreferrer">'.$OKN_TXT.'</a></li>';
				}
				?>
				</ul>
			</div>
		</div> <!-- row -->
	<?php endif; ?>

	<?php 
	if ($posts = get_field('see_also')) : ?>
		<div class="row info-block">
			<div class="col">
				<h2>См. также</h2>
				<ul>
				<?php
				foreach ($posts as $post) {
					if ($permalink = get_the_permalink($post->ID)) {
						echo '<li><a href="'.$permalink.'">'.esc_html($post->post_title).'</a></li>';
					}
				}
				?>
				</ul>
			</div>
		</div> <!-- row -->
	<?php endif; ?>

</div><!-- .entry-content -->

</article><!-- #post-<?php the_ID(); ?> -->

<?php if ($gallery) do_action('add_gallery_scripts'); ?>
