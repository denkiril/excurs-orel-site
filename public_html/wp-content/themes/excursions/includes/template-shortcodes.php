<?php
/**
 * Shortcode functions for Excursions theme
 *
 * @package excursions
 */

/**
 * Print annocards shortcode.
 * [annocards post_type="post" cat_name="blog" tag_name="promo" section_title="Приходите" read_more="Подробнее..." date="future" exclude="312" size="medium"].
 * [annocards ids=1,2,3 section_title="См. также" read_more="Подробнее..."].
 *
 * @param array $atts Shortcode attributes.
 * @return string Output HTML.
 */
function annocards_func( $atts ) {
	// Белый список параметров и значения по умолчанию.
	$atts = shortcode_atts(
		array(
			'post_type'     => 'post',
			'cat_name'      => '',
			'tag_name'      => '',
			'section_title' => null,
			'read_more'     => null,
			'date'          => '',
			'exclude'       => '312',
			'size'          => 'medium_large',
			'ids'           => null,
		),
		$atts
	);

	$my_post_type  = $atts['post_type'];
	$cat_name      = $atts['cat_name'];
	$tag_name      = $atts['tag_name'];
	$section_title = $atts['section_title'];
	$read_more     = $atts['read_more'];
	$date          = $atts['date'];
	$exclude       = $atts['exclude'];
	$size          = $atts['size'];
	$ids           = $atts['ids'];

	$html          = '';
	$event_date    = '';
	$past_events   = false;
	$future_events = false;

	if ( $ids ) {
		$ids_arr = array();
		$ids     = explode( ',', $ids );
		foreach ( $ids as $my_id ) {
			if ( is_numeric( trim( $my_id ) ) ) {
				$ids_arr[] = intval( trim( $my_id ) );
			}
		}
		if ( is_array( $ids_arr ) ) {
			$args = array( 'post__in' => $ids_arr );
		}
	} else {
		$args = array(
			'post_type'     => $my_post_type,
			'category_name' => $cat_name,
			'tag'           => $tag_name,
			'exclude'       => $exclude,
		);

		if ( 'events' === $my_post_type ) {
			$args += array(
				'orderby' => 'event_info_event_date',
				'order'   => 'DESC',
			);

			if ( $date ) {
				$today = date( 'Ymd' );
				if ( 'past' === $date ) {
					$past_events = true;
				} else {
					$future_events = true;
				}
				$compare = $past_events ? '<' : '>=';
				$args += array(
					'meta_query' => array(
						array(
							'key'     => 'event_info_event_date',
							'compare' => $compare,
							'value'   => $today,
						),
					),
				);
			}
		}
	}

	$myposts = get_posts( $args );
	if ( $myposts ) {
		$html .= '<section><div class="row section-container"><div class="col">';
		if ( $section_title ) {
			$html .= '<h2>' . $section_title . '</h2>';
		}
		foreach ( $myposts as $mypost ) {
			$permalink = get_the_permalink( $mypost );
			$mytitle   = esc_html( get_the_title( $mypost ) );
			if ( 'events' === $mypost->post_type ) {
				$event_date = markup_event_date( $mypost->ID );
				if ( $future_events ) {
					$event_date = '<span class="attention">' . $event_date . '</span>';
				}
			}

			$post_thumbnail = get_attachment_picture( get_post_thumbnail_id( $mypost ), $size );

			$html .= '<div class="row anno-card"><div class="col-12 col-md-4"><a href="' . $permalink . '" title="Ссылка на: ' . $mytitle . '" tabindex="-1">';
			$html .= $post_thumbnail;
			$html .= '</a></div><div class="col-12 col-md-8"><h3 class="annocard-title"><a href="' . $permalink . '" title="Ссылка на: ';
			$html .= $mytitle . '">' . $mytitle . '</a></h3>';
			$html .= '<p>' . $event_date . get_the_excerpt( $mypost );
			if ( $read_more ) {
				$html .= ' <a href="' . $permalink . '" tabindex="-1">' . $read_more . '</a>';
			}
			$html .= '</p></div></div>';
		}
		if ( $past_events && ! is_archive() ) {
			$html .= '<p class="anno-ref"><a href="' . get_post_type_archive_link( 'events' ) . '" title="Ссылка на все события">Все события ></a></p>';
		}

		$html .= '</div></div></section>';

	} elseif ( $future_events ) {
		$html = '<p>На ближайшее время у нас ничего не запланировано.</p>
			<p>Вы можете проверить наши группы в соцсетях (ссылки внизу сайта) – возможно там есть анонсы, которые ещё не добрались до этого сайта.</p>';
	}

	return $html;
}
add_shortcode( 'annocards', 'annocards_func' );

/**
 * Print newscards shortcode.
 * [newscards section_id=announcement section_title=Актуальное section_link='' future_events=1 actual_events=1 promo_posts=2 promo_events=3 promo_gba=4 read_more=Подробнее... exclude=312 size=medium]
 *
 * @param array $atts Shortcode attributes.
 * @return string Output HTML.
 */
function newscards_func( $atts ) {
	// Белый список параметров и значения по умолчанию.
	$atts = shortcode_atts(
		array(
			'post_type'     => array( 'post', 'events' ),
			'section_id'    => null,
			'section_title' => null,
			'section_link'  => null,
			'cat_name'      => 'promo',
			'future_events' => null,
			'actual_events' => null,
			'promo_posts'   => null,
			'promo_events'  => null,
			'promo_gba'     => null,
			'read_more'     => '[Перейти&nbsp;>>]',
			'exclude'       => array(),
			'size'          => 'medium_large',
			'events_num'    => 6,
		),
		$atts
	);

	$post_type     = $atts['post_type'];
	$section_id    = $atts['section_id'];
	$section_title = $atts['section_title'];
	$cat_name      = $atts['cat_name'];
	$future_events = $atts['future_events'];
	$actual_events = $atts['actual_events'];
	$promo_posts   = $atts['promo_posts'];
	$promo_events  = $atts['promo_events'];
	$promo_gba     = $atts['promo_gba'];
	$read_more     = $atts['read_more'];
	$exclude       = $atts['exclude'];
	$size          = $atts['size'];
	$section_link  = $atts['section_link'];
	$events_num    = $atts['events_num'];

	date_default_timezone_set( 'Europe/Moscow' );
	$today               = date( 'Ymd' );
	$current_time        = date( 'H:i' );
	$myposts             = array();
	$future_events_posts = array();

	$events_args = array(
		'post_type'   => 'events',
		'exclude'     => $exclude,
		'orderby'     => 'meta_value',
		'order'       => 'DESC',
		'meta_key'    => 'event_info_event_date',
		'numberposts' => -1,
	);

	if ( $future_events || $actual_events ) {
		$events_posts = get_posts( $events_args );
	}

	if ( $future_events && ! empty( $events_posts ) ) {
		foreach ( $events_posts as $counter => $events_post ) {
			$event_date = get_field( 'event_info_event_date', $events_post->ID, false );
			if ( $event_date > $today ) {
				$future_events_posts[] = $events_post;
				unset( $events_posts[ $counter ] );
			} elseif ( $event_date === $today ) {
				$event_time = get_field( 'event_info_event_time', $events_post->ID );
				if ( $event_time > $current_time ) {
					$future_events_posts[] = $events_post;
					unset( $events_posts[ $counter ] );
				}
			}
		}
		$myposts = array_merge( $myposts, $future_events_posts );
	}
	if ( $actual_events && ! empty( $events_posts ) ) {
		$date = new DateTime( $today );
		$date->modify( '-1 month' );
		$actual_date = $date->format( 'Ymd' );

		$ev_posts     = array();
		$posts_number = 0;
		foreach ( $events_posts as $counter => $events_post ) {
			$event_date = get_field( 'event_info_event_date', $events_post->ID, false );
			if ( $event_date > $actual_date ) {
				$ev_posts[] = $events_post;
				unset( $events_posts[ $counter ] );

				if ( ++$posts_number >= $events_num ) {
					break;
				}
			}
		}
		$myposts = array_merge( $myposts, $ev_posts );
	}
	if ( $promo_posts ) {
		$args    = array(
			'post_type'     => 'post',
			'exclude'       => $exclude,
			'category_name' => $cat_name,
		);
		$myposts = array_merge( $myposts, get_posts( $args ) );
	}
	if ( $promo_events ) {
		$args                  = $events_args;
		$args['category_name'] = $cat_name;
		$myposts               = array_merge( $myposts, get_posts( $args ) );
	}
	if ( $promo_gba ) {
		$args    = array(
			'post_type'     => 'guidebook',
			'exclude'       => $exclude,
			'category_name' => $cat_name,
			'meta_key'      => 'gba_rating',
			'orderby'       => array(
				'meta_value_num' => 'DESC',
				'title'          => 'ASC',
			),
		);
		$myposts = array_merge( $myposts, get_posts( $args ) );
	}

	// убираем из $myposts дубли.
	$posts_ids = array();
	foreach ( $myposts as $counter => $mypost ) {
		if ( array_search( $mypost->ID, $posts_ids, true ) === false ) {
			$posts_ids[] = $mypost->ID;
		} else {
			unset( $myposts[ $counter ] );
		}
	}

	$html = '';
	if ( $myposts ) {
		if ( $section_id ) {
			$section_id = 'id="' . $section_id . '"';
		}
		$html .= '<section ' . $section_id . '><div class="section-container">';
		if ( $section_title ) {
			if ( $section_link ) {
				$section_title = '<a href="' . $section_link . '">' . $section_title . '</a>';
			}
			$html .= '<h2>' . $section_title . '</h2>';
		}

		$html .= '<div class="row">';
		foreach ( $myposts as $mypost ) {
			$show_event_date = 'events' === $mypost->post_type;
			$show_attention  = in_array( $mypost, $future_events_posts, true );

			$html .= excurs_get_newscard_html( $mypost->ID, '', $show_event_date, $show_attention, $size, $read_more );
		}

		$html .= '</div><!-- .row -->';
		$html .= '</div></section>';
	}

	return $html;
}
add_shortcode( 'newscards', 'newscards_func' );

/**
 * Shortcode for social widgets output.
 * [socwidgets section_title="Мы в соцсетях" vk_mode="3" vk_width="300" vk_height="400" vk_id="94410363" no_cover="1" fb=1]
 *
 * @param array $atts Shortcode attributes.
 * @return string Output HTML.
 */
function socwidgets_func( $atts ) {
	// Белый список параметров и значения по умолчанию.
	$atts = shortcode_atts(
		array(
			'section_title' => null,
			'vk_mode'       => '3',
			'vk_width'      => '300',
			'vk_height'     => '400',
			'vk_id'         => null,
			'no_cover'      => null,
			'fb'            => false,
		),
		$atts
	);

	$section_title = $atts['section_title'];
	$vk_mode       = $atts['vk_mode'];
	$vk_width      = $atts['vk_width'];
	$vk_height     = $atts['vk_height'];
	$vk_id         = $atts['vk_id'];
	$no_cover      = $atts['no_cover'];
	$fb            = $atts['fb'];

	$html = '<section id="soc-section"><div class="row section-container"><div class="col">';
	if ( $section_title ) {
		$html .= '<h2>' . $section_title . '</h2>';
	}
	$html .= '<div class="row">';
	if ( $vk_id ) {
		$html .= '<div class="col-md-6"><!-- VK Widget --><div class="socwidget" id="vk_groups" data-id="' . $vk_id . '"';
		if ( $vk_mode ) {
			$html .= ' data-mode="' . $vk_mode . '"';
		}
		if ( $vk_width ) {
			$html .= ' data-width="' . $vk_width . '"';
		}
		if ( $vk_height ) {
			$html .= ' data-height="' . $vk_height . '"';
		}
		if ( $no_cover ) {
			$html .= ' data-no_cover="' . $no_cover . '"';
		}
		$html .= '></div></div>';
	}
	if ( $fb ) {
		$html .= '<div class="col-md-6"><div id="fb-root"></div>';
		$html .= '<div class="socwidget fb-group" data-href="https://www.facebook.com/groups/excursorel" data-width="300" data-show-social-context="true" data-show-metadata="false"></div></div>';
	}
	$html .= '</div>';
	$html .= '</div></div></section>';

	add_social_scripts();

	return $html;
}
add_shortcode( 'socwidgets', 'socwidgets_func' );

/**
 * Shortcode for output carousel (slider) widget
 * [carousel class="carousel" hrefs=1 size="medium_large" container="col-md-10 col-lg-6 carousel-container"]
 *
 * @param array $atts Shortcode attributes.
 * @return string Output HTML.
 */
function carousel_func( $atts ) {
	// Белый список параметров и значения по умолчанию.
	$atts = shortcode_atts(
		array(
			'class'     => 'carousel',
			'hrefs'     => false,
			'size'      => 'medium_large',
			'container' => 'carousel-container',
		),
		$atts
	);

	$class      = $atts['class'];
	$class_item = $class . '-item';
	$hrefs      = $atts['hrefs'];
	$size       = $atts['size'];
	$container  = $atts['container'];

	$html = '';

	global $post;
	// Get the images ids from the post_metadata.
	$images = acf_photo_gallery( 'carousel_gal', $post->ID );
	if ( count( $images ) ) :
		$images_counter = 0;
		if ( $container ) {
			$html .= '<div class="' . $container . '">';
		}
		$html .= '<div class="glide ' . $class . '">';
		$html .= '<div class="glide__track" data-glide-el="track"><ul class="glide__slides">';
		foreach ( $images as $image ) {
			$img_id    = $image['id']; // The attachment id of the media.
			$img_title = $image['title']; // The title.
			$post_id   = wp_get_post_parent_id( $img_id );
			$post_link = get_permalink( $post_id );
			$attr      = $hrefs ? null : array( 'title' => $img_title );

			$html .= '<li class="glide__slide">';
			if ( $hrefs && $post_link ) {
				$html .= '<a href="' . $post_link . '" title="' . get_the_title( $post_id ) . '">';
			}

			$html .= get_attachment_picture( $img_id, $size, false, $attr, $images_counter > 0 );
			$images_counter++;

			if ( $hrefs && $post_link ) {
				$html .= '</a>';
			}
			$html .= '</li>';
		}
		$html .= '</ul></div>';
		// controls.
		$html .= '<div class="glide__arrows" data-glide-el="controls" style="display: none;">
			<button class="glide__arrow glide__arrow--left" data-glide-dir="<">&lt;</button>
			<button class="glide__arrow glide__arrow--right" data-glide-dir=">">&gt;</button></div>';
		// bullets.
		$html .= '<div class="glide__bullets" data-glide-el="controls[nav]" style="display: none;">';
		foreach ( $images as $counter => $image ) {
			$html .= '<button class="glide__bullet" data-glide-dir="=' . $counter . '"></button>';
		}
		$html .= '</div>';

		$html .= '</div> <!-- .glide .' . $class . ' -->';
		if ( $container ) {
			$html .= '</div> <!-- .' . $container . ' -->';
		}
		add_carousel_scripts();
	endif;

	return $html;
}
add_shortcode( 'carousel', 'carousel_func' );

/**
 * Shortcode for output simple image HTML
 * [image num=1 class="" id=1 size=medium_large href=1 lazy=0 figcaption='']
 *
 * @param array $atts Shortcode attributes.
 * @return string Output HTML.
 */
function image_func( $atts ) {
	// Белый список параметров и значения по умолчанию.
	$atts = shortcode_atts(
		array(
			'class'      => 'image',
			'id'         => null,
			'size'       => 'thumbnail',
			'href'       => false,
			'lazy'       => true,
			'figcaption' => null,
			'num'        => null,
		),
		$atts
	);

	$class      = $atts['class'];
	$img_id     = $atts['id'];
	$size       = $atts['size'];
	$href       = $atts['href'];
	$lazy       = $atts['lazy'];
	$figcaption = $atts['figcaption'];
	$num        = $atts['num'];

	$acf_field = 'image_gal';
	$html      = '';

	if ( ! $img_id && ! $num ) {
		$img_id = get_post_thumbnail_id();
	}

	if ( ! $img_id && $num ) {
		global $post;
		// Get the images ids from the post_metadata.
		$images = acf_photo_gallery( $acf_field, $post->ID );
		if ( count( $images ) >= $num ) {
			$img_id = $images[ $num - 1 ]['id'];
		}
	}

	if ( $img_id ) {
		if ( $href ) {
			$post_id   = wp_get_post_parent_id( $img_id );
			$post_link = get_permalink( $post_id );
			if ( $post_link ) {
				$ahref_pre  = '<a href="' . $post_link . '" title="' . get_the_title( $post_id ) . '">';
				$ahref_post = '</a>';
			}
		} else {
			$ahref_pre  = '<a href="' . wp_get_attachment_image_url( $img_id, 'full' ) . '" title="' . get_the_title( $img_id ) . '" target="_blank">';
			$ahref_post = '</a>';
		}

		$image = get_attachment_picture( $img_id, $size, false, null, $lazy );

		if ( $image ) {
			$html .= '<div class="' . $class . '"><figure>' . $ahref_pre . $image . $ahref_post;
			if ( $figcaption ) {
				$html .= '<figcaption>' . $figcaption . '</figcaption>';
			}
			$html .= '</figure></div>';
		}
	}

	return $html;
}
add_shortcode( 'image', 'image_func' );

/**
 * Get array of numbers from string of numbers
 *
 * @param string $nums_str String of numbers, separated by comma (,) or minus (-).
 * @return array Array of int numbers.
 */
function excurs_get_nums_arr( $nums_str ) {
	$nums_arr = array();

	if ( strpos( $nums_str, '-' ) !== false ) {
		$nums_extremes = array_map( 'intval', explode( '-', $nums_str ) );
		if ( count( $nums_extremes ) > 1 ) {
			for ( $i = $nums_extremes[0]; $i <= $nums_extremes[1]; $i++ ) {
				$nums_arr[] = $i;
			}
		}
	} else {
		$nums_arr = array_map( 'intval', explode( ',', $nums_str ) );
	}

	return $nums_arr;
}

/**
 * Shortcode for output images on Guidebook page
 * [gb_images nums=1 title="Музеи Орла на карте" subtitle="(*Кликните на картинку, чтобы открыть в большом размере)"]
 *
 * @param array $atts Shortcode attributes.
 * @return string Output HTML.
 */
function gb_images_func( $atts ) {
	// Белый список параметров и значения по умолчанию.
	$atts = shortcode_atts(
		array(
			'nums'     => null,
			'class'    => 'large-image',
			'size'     => 'large',
			'lazy'     => true,
			'title'    => null,
			'subtitle' => null,
		),
		$atts
	);

	$nums     = $atts['nums'];
	$class    = $atts['class'];
	$size     = $atts['size'];
	$lazy     = $atts['lazy'];
	$title    = $atts['title'];
	$subtitle = $atts['subtitle'];

	$acf_field = 'image_gal';
	$html      = '';

	// Hide this block on pagen.
	$query_params = excurs_get_query_params();
	if ( isset( $query_params['pagenum'] ) && intval( $query_params['pagenum'] ) > 1 ) {
		return $html;
	}

	global $post;
	// Get the images ids from the post_metadata.
	$images = acf_photo_gallery( $acf_field, $post->ID );

	if ( count( $images ) ) :
		$images_count = count( $images );
		$nums_arr     = excurs_get_nums_arr( $nums );
		if ( count( $nums_arr ) ) {
			$images_count = count( $nums_arr );
		}

		switch ( $images_count ) {
			case 1:
				$bootstrap = 'col-12';
				break;
			case 3:
				$bootstrap = 'col-12 col-sm-6 col-md-4 col-lg-4';
				break;
			default:
				$bootstrap = 'col-12 col-sm-6';
		}

		if ( $title ) {
			$html .= '<h2>' . $title . '</h2>';
		}
		$html .= '<div class="row ' . $class . '">';
		foreach ( $images as $counter => $image ) {
			if ( $nums && ! in_array( $counter + 1, $nums_arr, true ) ) {
				continue;
			}

			$img_id          = $image['id']; // The attachment id of the media.
			$img_title       = $image['title']; // The title.
			$full_image_url  = $image['full_image_url']; // Full size image url.
			$figcaption_html = wiki_parse_text( $image['caption'] );

			if ( $img_id ) {
				$ahref_pre  = '<a href="' . $full_image_url . '" title="' . $img_title . '" target="_blank">';
				$ahref_post = '</a>';
				$picture    = get_attachment_picture( $img_id, $size, false, null, $lazy );

				if ( $picture ) {
					$html .= '<div class="' . $bootstrap . '"><figure>' . $ahref_pre . $picture . $ahref_post;
					if ( $figcaption_html ) {
						$html .= '<figcaption>' . $figcaption_html . '</figcaption>';
					}
					$html .= '</figure></div>';
				}
			}
		}
		$html .= '</div> <!-- row ' . $class . ' -->';

		if ( $subtitle ) {
			$html .= '<p style="font-size:0.9em;color:#3f3f3f;">' . $subtitle . '</p>';
		}
	endif;

	return $html;
}
add_shortcode( 'gb_images', 'gb_images_func' );

/**
 * Shortcode for output images in gallery mode (fancybox)
 * [gallery acf_field=gallery_gal class=post-gallery item=gallery-item fancybox=gallery lazy=1 size=large nums=null(1,2,4|1-5) figcaption=image_description(parent_href) mini=false permalink=true]
 * [gallery nums=(1,2,4|1-5) pics_in_row=2]
 *
 * @param array $atts Shortcode attributes.
 * @return string Output HTML.
 */
function gallery_func( $atts ) {
	// белый список параметров и значения по умолчанию.
	$atts = shortcode_atts(
		array(
			'acf_field'    => 'gallery_gal',
			'class'        => null,
			'item'         => 'gallery-item',
			'fancybox'     => 'gallery',
			'lazy'         => true,
			'size'         => null,
			'nums'         => null,
			'figcaption'   => 'image_description',
			'mini'         => false, // will be deprecated by pics_in_row.
			'permalink'    => true,
			'pics_in_row'  => 1,
			'return_array' => false,
		),
		$atts
	);

	$acf_field    = $atts['acf_field'];
	$class        = $atts['class'];
	$item         = $atts['item'];
	$fancybox     = $atts['fancybox'];
	$lazy         = $atts['lazy'];
	$size         = $atts['size'];
	$nums         = $atts['nums'];
	$figcaption   = $atts['figcaption'];
	$mini         = $atts['mini']; // will be deprecated by pics_in_row.
	$add_link     = $atts['permalink'];
	$pics_in_row  = intval( $atts['pics_in_row'] );
	$return_array = $atts['return_array'];

	$html   = '';
	$sights = array();

	global $post;
	// Get the images ids from the post_metadata.
	$images = acf_photo_gallery( $acf_field, $post->ID );
	if ( count( $images ) ) :
		if ( $mini ) {
			$pics_in_row = 3;
		}
		if ( ! $class ) {
			$class = 1 === $pics_in_row ? 'post-gallery' : 'pre-gallery';
		}
		if ( ! $size ) {
			$size = 1 === $pics_in_row ? 'large' : 'medium_large';
		}

		switch ( $pics_in_row ) {
			case 1:
				$bootstrap = 'col-12';
				break;
			case 3:
				$bootstrap = 'col-12 col-sm-6 col-md-4 col-lg-4';
				break;
			default:
				$bootstrap = 'col-12 col-sm-6';
		}

		$html    .= '<div class="row ' . $class . '">';
		$nums_arr = excurs_get_nums_arr( $nums );
		foreach ( $images as $counter => $image ) :
			if ( $nums && ! in_array( $counter + 1, $nums_arr, true ) ) {
				continue;
			}

			$img_id         = $image['id']; // The attachment id of the media.
			$img_title      = $image['title']; // The title.
			$full_image_url = $image['full_image_url']; // Full size image url.

			$figcaption_html = '';
			if ( 'parent_href' === $figcaption ) {
				$post_parent_id = wp_get_post_parent_id( $img_id );
				// Если картинка имеет родительский пост отличный от текущего, выводим на него ссылку в подписи.
				if ( $post_parent_id !== $post->ID ) {
					$post_parent_link = get_permalink( $post_parent_id );
					if ( $post_parent_link ) {
						$figcaption_html = '<a href="' . $post_parent_link . '">' . get_the_title( $post_parent_id ) . '</a>';
					}
				}
			}
			// Иначе берем подпись из БД и парсим ее.
			if ( ! $figcaption_html && ( 'image_description' === $figcaption || 'parent_href' === $figcaption ) ) {
				$figcaption_html = wiki_parse_text( $image['caption'], $add_link ); // The caption (Description!).
			}

			$item_anchor = $class . '-' . $img_id;

			$html .= '<div id="' . $item_anchor . '" class="' . $item . ' ' . $bootstrap . '"><a name="' . $item_anchor . '"></a>';
			$html .= markup_fancy_figure( $img_id, $fancybox, $full_image_url, $img_title, $size, $lazy, $img_title, null, $figcaption_html );
			$html .= '</div>';

			if ( $return_array ) {
				$location = null;
				$location = get_post_meta( $img_id, 'photo_location', true );
				if ( $location ) {
					$sights[] = array(
						'lat'       => $location['lat'],
						'lng'       => $location['lng'],
						'permalink' => '#' . $item_anchor,
						'title'     => $img_title,
						'thumb_url' => wp_get_attachment_image_url( $img_id, 'thumbnail' ),
					);
				}
			}
		endforeach;

		$html .= '</div> <!-- row ' . $class . ' -->';

		do_action( 'add_gallery_scripts' );
	endif;

	return $return_array ? array(
		'html'   => $html,
		'sights' => $sights,
	) : $html;
}
add_shortcode( 'gallery', 'gallery_func' );

/**
 * Shortcode for output Guidebook map (obj_map)
 * [guidebook_map add_classes="noautoopen another_some_class" slug=museums]
 *
 * @param array $atts Shortcode attributes.
 * @return string Outpit HTML.
 */
function guidebook_map_func( $atts ) {
	// белый список параметров и значения по умолчанию.
	$atts = shortcode_atts(
		array(
			'class'       => 'obj_map',
			'add_classes' => '',
			'slug'        => '',
			'form'        => false,
		),
		$atts
	);

	$class       = $atts['class'];
	$add_classes = $atts['add_classes'];
	$data_slug   = $atts['slug'] ? sprintf( ' data-slug="%s"', $atts['slug'] ) : '';
	$form        = $atts['form'];

	$query_params = excurs_get_query_params();
	if ( isset( $query_params['pagenum'] ) && strpos( $add_classes, 'noautoopen' ) === false ) {
		$add_classes .= ' noautoopen';
	}

	$form_html = '';
	if ( $form ) {
		$cat_f_checked         = isset( $query_params['cat_f'] ) ? 'checked' : '';
		$numberposts_1_checked = isset( $query_params['numberposts_1'] ) ? 'checked' : '';
		$numberposts_2_checked = isset( $query_params['numberposts_2'] ) ? 'checked' : '';
		$pagenum1_checked      = ( empty( $query_params['pagenum'] ) || 1 === intval( $query_params['pagenum'] ) ) ? 'checked' : '';
		$pagenum2_checked      = 2 === intval( $query_params['pagenum'] ) ? 'checked' : '';

		$form_html .= '<div class="om_block omb_topFilter"><form>';
		$form_html .= '<label><input type="checkbox" name="cat_f" ' . $cat_f_checked . '>Ф</label>';
		$form_html .= '<label><input type="checkbox" name="numberposts_1" ' . $numberposts_1_checked . '>1</label>';
		$form_html .= '<label><input type="checkbox" name="numberposts_2" ' . $numberposts_2_checked . '>2</label>';
		$form_html .= '<label><input type="radio" name="pagenum" value="1" ' . $pagenum1_checked . '>1</label>';
		$form_html .= '<label><input type="radio" name="pagenum" value="2" ' . $pagenum2_checked . '>2</label>';
		$form_html .= '<button type="submit">Применить</button></form></div>';
	}

	$html  = '';
	$html .= '<noscript><style>.' . $class . '{display:none;}</style><p>Если включите JavaScript, здесь отобразится карта.</p></noscript>';
	$html .= '<div class="' . $class . ' ' . $add_classes . '" data-state="init"' . $data_slug . '>';
	$html .= '<div class="om_block omb_panel"><button class="OpenMap_btn hidden">';
	$html .= '<span class="state_init">[ Показать на карте ]</span>';
	$html .= '<span class="state_open">[ Свернуть карту ]</span>';
	$html .= '<span class="state_close">[ Открыть карту ]</span></button></div>';
	$html .= '<div class="om_content">' . $form_html . '<div class="om_flex">';
	$html .= '<div class="om_block omb_map"><img class="map_loader" src="' . PLACEHOLDER_URL_MAP . '" /></div>';
	$html .= '<div class="om_aside"><div class="om_block omb_filter">';
	$html .= '<input type="search" placeholder="Поиск по названию" id="filterByTitle" class="hidden">';
	$html .= '<div class="ctrl_flex hidden">';
	$html .= '<label title="Переключение вида">';
	$html .= '<input class="hidden_input" type="radio" name="omb_list_view" value="list" checked>';
	$html .= '<span class="chb_svg"></span></label>';
	$html .= '<label title="Переключение вида">';
	$html .= '<input class="hidden_input" type="radio" name="omb_list_view" value="imgs">';
	$html .= '<span class="chb_svg"></span></label></div></div>';
	$html .= '<div class="om_block omb_list"></div>';
	$html .= '<div class="om_block omb_info"><img id="infoImg" src="" /><a id="infoRef" href=""></a></div>';
	$html .= '</div></div></div></div>';

	do_action( 'guidebook_map_scripts' );

	return $html;
}
add_shortcode( 'guidebook_map', 'guidebook_map_func' );

/**
 * Shortcode for output guidbook sections (on Guidebook page)
 * [guidebook_sections content="" show='sights museums documents']
 *
 * @param array $atts Shortcode attributes.
 * @return string Outpit HTML.
 */
function guidebook_sections_func( $atts ) {
	// белый список параметров и значения по умолчанию.
	$atts = shortcode_atts(
		array(
			'content' => null,
			'show'    => null,
		),
		$atts
	);

	$gb_content = $atts['content'];
	$show_terms = explode( ' ', $atts['show'] );

	$tax_name       = 'sections';
	$sections_terms = get_terms( $tax_name );
	$terms          = array();
	foreach ( $show_terms as $term ) {
		foreach ( $sections_terms as $s_term ) {
			if ( $term === $s_term->slug ) {
				$terms[] = $s_term;
				break;
			}
		}
	}

	$html = '';
	if ( $terms && ! is_wp_error( $terms ) ) :
		$query_params = excurs_get_query_params();
		$term_counter = 0;
		foreach ( $terms as $term ) :
			$numberposts = 12; // 4 for dev.
			if ( 0 === $term_counter ) {
				if ( isset( $query_params['numberposts_1'] ) ) {
					$numberposts = 1;
				}
				if ( isset( $query_params['numberposts_2'] ) ) {
					$numberposts = 2;
				}
			}
			$myposts = get_guidebook_posts( $term->slug, $numberposts );

			if ( $myposts ) {
				$term_link = get_term_link( (int) $term->term_id );
				$term_name = esc_html( $term->name );
				if ( 1 === $term_counter ) {
					$html .= '<hr />';
				}
				if ( 0 === $term_counter ) {
					$annocard_title = '<h2 class="annocard-title">' . $term_name . '</h2>';
				} else {
					$annocard_title = '<h2 class="annocard-title"><a href="' . $term_link . '">' . $term_name . '</a></h2>';
				}
				$html .= '<div class="section-container">' . $annocard_title . '<div class="row">';

				foreach ( $myposts as $mypost ) {
					$permalink  = get_the_permalink( $mypost );
					$thumb_id   = get_post_thumbnail_id( $mypost );
					$title_attr = esc_attr( excurs_get_the_title_for_attr( $mypost ) );
					$a_format   = '<a href="' . $permalink . '" title="' . $title_attr . '" %1$s>%2$s</a>';

					$html .= '<div class="anno-card col-6 col-sm-6 col-md-4 col-lg-3">';
					$html .= sprintf( $a_format, 'tabindex="-1"', get_attachment_picture( $thumb_id, 'medium', false, null, true, true ) );
					$html .= '<h3 class="annocard-caption">' . sprintf( $a_format, '', esc_html( get_the_title( $mypost ) ) ) . '</h3></div>';
				}

				$html .= '</div>';

				// После первой секции (Достопримечательности) выводим её пагинацию.
				if ( 0 === $term_counter ) {
					$all_posts = get_guidebook_posts( null, -1 );
					$total     = ceil( count( $all_posts ) / $numberposts );
					$current   = empty( $query_params['pagenum'] ) ? 1 : intval( $query_params['pagenum'] );
					$url       = get_url_wo_pagenum();
					$query     = wp_parse_url( $url, PHP_URL_QUERY );
					$sfx       = $query ? '&' : '?';
					$args      = array(
						'base'               => $url . '%_%',
						'format'             => $sfx . 'pagenum=%#%',
						'total'              => $total,
						'current'            => $current,
						'mid_size'           => 1,
						'prev_text'          => '<<',
						'next_text'          => '>>',
						'screen_reader_text' => __( 'Posts navigation' ),
					);

					$links = paginate_links( $args );
					if ( $links ) {
						$html .= _navigation_markup( $links, 'pagination', $args['screen_reader_text'] );
					}
				}
				$html .= '</div> <!-- .section-container -->';

				// Если это не главная страница Путеводителя (первая стр. пагинации), Описание и другие разделы не выводим.
				if ( $current > 1 ) {
					break;
				}

				// После первой секции (Достопримечательности) выводим Описание путеводителя.
				if ( 0 === $term_counter && $gb_content ) {
					$html .= '<div class="main-section">' . $gb_content . '</div>';
				}
			}

			$term_counter++;

		endforeach;

		if ( $term_counter > 1 ) {
			$html .= '<hr />';
		}

	endif;

	return $html;
}
add_shortcode( 'guidebook_sections', 'guidebook_sections_func' );

/**
 * Shortcode for output guidebook route sights
 * [gbr_sights class="" nums=(1,2,4|1-5)]
 *
 * @param array $atts Shortcode attributes.
 * @return string Output HTML.
 */
function gbr_sights_func( $atts ) {
	// Белый список параметров и значения по умолчанию.
	$atts = shortcode_atts(
		array(
			'nums'        => null,
			'num_markers' => false,
		),
		$atts
	);

	$nums             = $atts['nums'];
	$show_num_markers = $atts['num_markers'];

	$html       = '';
	$gallery    = false;
	$nums_arr   = excurs_get_nums_arr( $nums );
	$gbr_sights = carbon_get_the_post_meta( 'gbr_sights' );
	foreach ( $gbr_sights as $key => $obj ) {
		$num = $key + 1;
		if ( $nums && ! in_array( $num, $nums_arr, true ) ) {
			continue;
		}

		$title      = esc_html( trim( $obj['title'] ) );
		$desription = trim( $obj['desription'] );
		$img_id     = $obj['image'];

		$picture = '';
		if ( $img_id ) {
			$full_img_url = wp_get_attachment_image_url( $img_id, 'full' );
			$img_title    = get_the_title( $img_id );
			$picture      = markup_fancy_figure( $img_id, 'gallery', $full_img_url, $img_title, 'medium_large', 'lazy', $img_title );
			$gallery      = true;
		}

		$gba_postid = count( $obj['gba'] ) ? $obj['gba'][0]['id'] : null;
		$gba_link   = $gba_postid ? get_permalink( $gba_postid ) : null;

		if ( $gba_link ) {
			$gba_title  = get_the_title( $gba_postid );
			$title_attr = $gba_title ? 'title="' . $gba_title . '"' : '';
			$title      = $title ? '<a href="' . $gba_link . '" ' . $title_attr . '>' . $title . '</a>' : '';
		}

		$item_anchor = 'item-' . $num;
		$title       = $title ? '<h3 class="annocard-title">' . $title . '</h3>' : '';
		$num_marker  = $show_num_markers ? '<div class="num-marker">' . $num . '</div>' : '';

		$html .= '<div id="' . $item_anchor . '" class="row anno-card"><a name="' . $item_anchor . '"></a>';
		$html .= '<div class="col-12 col-md-4">' . $picture . '</div>';
		$html .= '<div class="col-12 col-md-8">' . $num_marker . $title . $desription . '</div>';
		$html .= '</div>';
	}

	if ( $gallery ) {
		do_action( 'add_gallery_scripts' );
	}

	return $html;
}
add_shortcode( 'gbr_sights', 'gbr_sights_func' );
