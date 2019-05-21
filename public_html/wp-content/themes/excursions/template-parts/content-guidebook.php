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
	the_title( '<h1 class="entry-title">', '</h1>' );  
	?>
</header><!-- .entry-header -->

<div class="entry-content">
	<div class="row">
		<div class="col">
		<?php if( $thumb_id = get_post_thumbnail_id() ): 
			// $full_image_url = get_the_post_thumbnail_url(); 
			$full_image_url = wp_get_attachment_image_url( $thumb_id, 'full' ); 
			$title = get_the_title($thumb_id); 
			$gallery = true; ?>
			<a data-fancybox="gallery" href="<?=$full_image_url?>">
			<?php
				$attr = array( 'class' => "events-image", 'title' => $title );
				// the_post_thumbnail('medium_large', array( 'class' => "events-image", 'title' => $title )); 
				echo get_attachment_picture( $thumb_id, 'medium_large', false, $attr, false );
			?>
			</a>
		<?php endif; ?>

		<?php the_field('gba_intro'); ?>

		</div>
	</div> <!-- row -->

	<hr />
	<?php 
	$obj_info = get_field('obj_info');
	$geolocation = $obj_info['geolocation'];
	$col_sfx = $geolocation ? '-lg-6' : '';
	?>
	<div class="event-info row">
		<div class="col<?=$col_sfx?>">
			<?php
			$obj_info_is = false;
			$echo = '';
			if( $obj_info['is_okn'] )
			{
				// $event_date_html = markup_event_date();
				echo '<h2>Является объектом культурного наследия (ОКН)</h2>';
				$obj_info_is = true;
			}
			if( $obj_info['okn_type'] )
			{
				$label = '<span class="ei_label">Тип объекта:</span> ';
				switch( $obj_info['okn_type'] ){
					case 'a': $okn_type = 'Памятник археологии'; 							break;
					case 'g': $okn_type = 'Памятник архитектуры и градостроительства'; 		break;
					case 'i': $okn_type = 'Памятник истории и монументального искусства'; 	break;
				}
				$echo .= $label . $okn_type . '<br />';
			}
			if( $obj_info['protection_category'] )
			{
				$label = '<span class="ei_label">Категория охраны:</span> ';
				switch( $obj_info['protection_category'] ){
					case 'f': $protection_category = 'Федерального значения'; 	break;
					case 'r': $protection_category = 'Регионального значения'; 	break;
					case 'm': $protection_category = 'Местного значения'; 		break;
					case 'v': $protection_category = 'Выявленный объект'; 		break;
				}
				$echo .= $label . $protection_category . '<br />';
			}
			if( $obj_info['registry_name'] )
			{
				$label = '<span class="ei_label">Наименование ОКН:</span> ';
				$echo .= $label . esc_html( $obj_info['registry_name'] ) . '<br />';
			}
			if( $obj_info['okn_date'] )
			{
				$label = '<span class="ei_label">Датировка (по реестру ОКН):</span> ';
				$echo .= $label . esc_html( $obj_info['okn_date'] ) . '<br />';
			}
			if( $obj_info['location'] )
			{
				$label = '<span class="ei_label">Местонахождение:</span> ';
				$echo .= $label . esc_html( $obj_info['location'] ) . '<br />';
			}
			if( $obj_info['district'] )
			{
				$label = '<span class="ei_label">Район:</span> ';
				switch( $obj_info['district'] ){
					case '1': $district = 'Заводской'; 			break;
					case '2': $district = 'Железнодорожный'; 	break;
					case '3': $district = 'Советский'; 			break;
				}
				$echo .= $label . $district . '<br />';
			}
			if( $obj_info['registry_date'] )
			{
				$label = '<span class="ei_label">Год постановки на охрану:</span> ';
				$echo .= $label . esc_html( $obj_info['registry_date'] ) . '<br />';
			}
			if( $obj_info['more_info'] )
			{
				$more_lines = explode(PHP_EOL, $obj_info['more_info']);
				foreach( $more_lines as $line ){
					$sublines = explode(':', $line, 2);
					$label = '<span class="ei_label">'.esc_html( trim($sublines[0]) ).':</span> ';
					$echo .= $label . esc_html( trim($sublines[1]) ) . '<br />';
				}
			}
			
			if( $echo ) {
				$echo = '<p>' . $echo . '</p>';
				$obj_info_is = true;
			}
			
			echo $echo; 
			?>
		</div>
		<?php // $show_map id="map" 
		if( $geolocation ):
			do_action( 'event_map_scripts' ); ?>
			<div class="col<?=$col_sfx?>">
				<button id="OpenMap_btn" class="ref_btn">[ Показать карту ]</button>
				<div class="acf-map">
					<div class="marker" data-lat="<?php echo $geolocation['lat']; ?>" data-lng="<?php echo $geolocation['lng']; ?>"></div>
				</div>
			</div>
		<?php endif; ?>
	</div> <!-- event_info row -->

	<?php if( $obj_info_is || $geolocation ) echo '<hr />'; ?>

	<?php 
	$gba_content = get_field('gba_content');
	if( $gba_content ): ?>
		<div class="row info-block">
			<div class="col">
				<?php echo $gba_content; ?>
			</div>
		</div> <!-- row -->
	<?php endif; ?>
	
	<?php 
	$gba_sources = get_field('gba_sources');
	if( $gba_sources || $obj_info['is_okn'] ):
	?>
		<div class="row info-block">
			<div class="col">
				<h2>Источники</h2>
				<ul>
				<?php
				if( $gba_sources ){
					$lines = explode(PHP_EOL, trim($gba_sources));
					foreach( $lines as $line ){
						$sublines = explode('=', $line, 2);
						$text = esc_html( trim($sublines[0]) );
						$url = esc_html( trim($sublines[1]) );
						// print_r("url=".$url);
						if( $url ){
							$ret = parse_url($url);
							if( !isset($ret['scheme']) ){
								$url = "http://{$url}";
							}
							$text = '<a href="'.$url.'" target="_blank" rel="noopener noreferrer">'.$text.'</a>';
						}
						// echo '<li><a href="'.$href.'" target="_blank" rel="noopener noreferrer">'.$title.'</a></li>';
						echo '<li>'.$text.'</li>';
					}
				}
				if( $obj_info['is_okn'] ){
					echo '<li><a href="'.$OKN_URL.'" target="_blank" rel="noopener noreferrer">'.$OKN_TXT.'</a></li>';
				}
				?>
				</ul>
			</div>
		</div> <!-- row -->
	<?php endif; ?>

	<?php 
	if( $posts = get_field('see_also') ): ?>
		<div class="row info-block">
			<div class="col">
				<h2>См. также</h2>
				<ul>
				<?php
				foreach( $posts as $post ){
					if( $permalink = get_the_permalink( $post->ID ) ){
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

<?php if( $gallery ) do_action( 'add_gallery_scripts' ); ?>
