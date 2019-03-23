<?php
/**
 * excursions functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package excursions
 */

if ( ! function_exists( 'excursions_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function excursions_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on excursions, use a find and replace
		 * to change 'excursions' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'excursions', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in locations:
		register_nav_menus( array(
			'header_menu' => 'Меню в шапке',
			'footer_menu' => 'Меню в подвале'
		) );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		// Set up the WordPress core custom background feature.
		add_theme_support( 'custom-background', apply_filters( 'excursions_custom_background_args', array(
			'default-color' => 'ffffff',
			'default-image' => '',
		) ) );

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support( 'custom-logo', array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		) );
	}
endif;
add_action( 'after_setup_theme', 'excursions_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function excursions_content_width() {
	// This variable is intended to be overruled from themes.
	// Open WPCS issue: {@link https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/1043}.
	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
	$GLOBALS['content_width'] = apply_filters( 'excursions_content_width', 640 );
}
add_action( 'after_setup_theme', 'excursions_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function excursions_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'excursions' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'excursions' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'excursions_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function excursions_scripts() {
	wp_enqueue_style( 'excursions-style', get_stylesheet_uri() );

	wp_enqueue_script( 'excursions-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20151215', true );

	wp_enqueue_script( 'excursions-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20151215', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	wp_enqueue_style( 'bootstrap-grid', get_template_directory_uri() . '/assets/include/bootstrap-grid.min.css' );

	if( is_singular('events') ){
		wp_enqueue_style( 'events', get_template_directory_uri() . '/assets/css/events.css' );
	}

	wp_deregister_script( 'jquery' );
	// <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
	wp_enqueue_script( 'jquery', '//code.jquery.com/jquery-3.3.1.min.js', array(), false, 'in_footer' );
	// wp_enqueue_script( 'jquery', get_template_directory_uri() . '/assets/include/jquery-3.3.1.min.js', array(), false, 'in_footer' );
	wp_enqueue_script( 'bootstrap-js', get_template_directory_uri() . '/assets/include/bootstrap.min.js', array('jquery'), false, 'in_footer' );
	wp_register_script( 'slick-js', get_template_directory_uri() . '/assets/include/slick.min.js', array('jquery'), false, 'in_footer' );
	wp_enqueue_script( 'script', get_template_directory_uri() . '/assets/js/script.js', array('jquery'), false, 'in_footer' );
	// wp_register_script( 'googlemap-api?key=YOUR_API_KEY', '//maps.googleapis.com/maps/api/js', array(), false, 'in_footer' );
	// wp_register_script( 'ymap-api?apikey=6ebdbbc2-3779-4216-9d88-129e006559bd&lang=ru_RU', '//api-maps.yandex.ru/2.1/', array(), false, 'in_footer' );
	// wp_register_script( 'ymap-api', '//api-maps.yandex.ru/2.1/' );
	wp_register_script( 'acf-map-js', get_template_directory_uri() . '/assets/js/acf-map-yandex.js', array('jquery'), false, 'in_footer' );
	wp_register_script( 'events_map-js', get_template_directory_uri() . '/assets/js/events_map.js', array('jquery'), false, 'in_footer' );
	wp_enqueue_script( 'yashare-js', '//yastatic.net/share2/share.js', array('jquery'), false, 'in_footer' );
	wp_register_script( 'fancybox-js', '//cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.6/dist/jquery.fancybox.min.js', array('jquery'), false, 'in_footer' );
	// wp_register_style( 'fancybox', '//cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.6/dist/jquery.fancybox.min.css' );
	// wp_register_style( 'gallery', get_template_directory_uri() . '/assets/css/gallery.css' );
	wp_register_script( 'cssrelpreload-js', get_template_directory_uri() . '/assets/include/cssrelpreload.js', array(), false, 'in_footer' );
}
add_action( 'wp_enqueue_scripts', 'excursions_scripts' );

// function styles_to_footer() {
	// wp_enqueue_style( 'main-bottom', get_template_directory_uri() . '/assets/css/main-bottom.css' );
	// wp_enqueue_style( 'slick', get_template_directory_uri() . '/assets/include/slick.css' );
    // wp_enqueue_style( 'slick-theme', get_template_directory_uri() . '/assets/include/slick-theme.css' );
    // wp_enqueue_style( 'main-font?family=Ubuntu:300,400&amp;subset=cyrillic', '//fonts.googleapis.com/css' );
// }
// add_action( 'wp_footer', 'styles_to_footer' );

$style_hrefs = array();

preload_style( get_template_directory_uri().'/assets/css/main-bottom.css' );
preload_style( '//fonts.googleapis.com/css?family=Ubuntu:300,400&amp;subset=cyrillic' );

function preload_style( $style_href ){
	global $style_hrefs;
	// если $style_href ещё не было, добавляем в массив ссылок 
	if( !in_array( $style_href, $style_hrefs ) )
		array_push($style_hrefs, $style_href);
}

function preload_styles(){ 
	// <link rel="preload" href="AO.js" as="script">
	// <link rel="preload" href="AO.css" as="style">

	global $style_hrefs;

	if( !empty($style_hrefs) ):
		foreach( $style_hrefs as $href ):
			echo '<link rel="preload" href="'.$href.'" as="style" onload="this.rel=\'stylesheet\'" />'.PHP_EOL;
			echo '<noscript><link rel="stylesheet" href="'.$href.'"></noscript>'.PHP_EOL;
		endforeach;

		wp_enqueue_script( 'cssrelpreload-js' );
	endif;
}
add_action( 'wp_footer', 'preload_styles' );

// add_action( 'add_map_scripts', 'add_map_scripts_func', 10, 0);
// function add_map_scripts_func() {
// 	wp_enqueue_script( 'ymap-api?apikey=6ebdbbc2-3779-4216-9d88-129e006559bd&lang=ru_RU', '//api-maps.yandex.ru/2.1/', array(), false, 'in_footer' );
// 	wp_enqueue_script( 'acf-map-js' );
// }

// if( $gallery ) do_action( 'add_gallery_scripts' );
add_action( 'add_gallery_scripts', 'add_gallery_scripts_func', 10, 0);
function add_gallery_scripts_func() {
	wp_enqueue_script( 'fancybox-js' );
	// wp_enqueue_style( 'fancybox' );
	// wp_enqueue_style( 'gallery' );
	preload_style( '//cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.6/dist/jquery.fancybox.min.css' );
	preload_style( get_template_directory_uri().'/assets/css/gallery.css' );
}

// if( $carousel ) do_action( 'add_carousel_scripts' );
add_action( 'add_carousel_scripts', 'add_carousel_scripts_func', 10, 0);
function add_carousel_scripts_func() {
	wp_enqueue_script( 'slick-js' );
	preload_style( get_template_directory_uri().'/assets/include/slick.css' );
}

// if( '.events_map' ) do_action( 'events_map_scripts' );
add_action( 'events_map_scripts', 'events_map_scripts_func', 10, 0);
function events_map_scripts_func() {
	preload_style( get_template_directory_uri().'/assets/css/events_map.css' );
	// wp_enqueue_script( 'ymap-api?lang=ru_RU' );
	// wp_enqueue_script( 'ymap-api?lang=ru_RU', '//api-maps.yandex.ru/2.1/' );
	wp_enqueue_script( 'events_map-js' );
	wp_localize_script( 'events_map-js', 'myajax', 
		array(
			'url' => admin_url('admin-ajax.php')
		)
	);  
}

// function test_styleadd() {
// 	wp_enqueue_style( 'excursions-style', get_stylesheet_uri() );
// }
// add_action( 'wp_enqueue_scripts', 'test_styleadd' );
// -------------------------------------------------------------------

/* Устраните ресурсы, блокирующие отображение */

// contact-form-7
// \wp-content\plugins\contact-form-7\includes\controller.php :
// add_action( 'wp_enqueue_scripts', 'wpcf7_do_enqueue_scripts', 10, 0 );
remove_action( 'wp_enqueue_scripts', 'wpcf7_do_enqueue_scripts', 10, 0 );

// if( $show_form ) do_action( 'add_wpcf7_scripts' );
add_action( 'add_wpcf7_scripts', 'add_wpcf7_scripts_func', 10, 0);
function add_wpcf7_scripts_func() {
	add_action( 'wp_footer', 'wpcf7_do_enqueue_scripts' );
}

// block-library
// wp-includes\default-filters.php :
// add_action( 'wp_enqueue_scripts', 'wp_common_block_scripts_and_styles' );
remove_action( 'wp_enqueue_scripts', 'wp_common_block_scripts_and_styles' );
// add_action( 'wp_footer', 'wp_common_block_scripts_and_styles' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

// Отключаем автоформатирование
// remove_filter( 'the_content', 'wpautop' );
// remove_filter( 'the_excerpt', 'wpautop' );
// remove_filter( 'comment_text', 'wpautop' );

add_action( 'anno-cards', 'anno_func', 10, 3);

function anno_func( $cat_name, $section_title, $read_more='Подробнее...' ) {
	global $post;
	$args = array( 'post_type' => 'post', 'category_name' => $cat_name );
	$myposts = get_posts( $args );
	if ( $myposts ): 
		echo '<section role="' . $cat_name . '"><div class="row section-container"><div class="col">';
		echo '<h2>' . $section_title . '</h2>';
		foreach( $myposts as $post ):
			setup_postdata( $post );
			$permalink = get_the_permalink(); 
			$title = esc_html( get_the_title() );
			$echo = '<div class="row anno-card"><div class="col-12 col-md-4"><a href="' . $permalink . '" tabindex="-1">'; 
			$echo .= get_the_post_thumbnail(null, 'medium');
			$echo .= '</a></div><div class="col-12 col-md-8"><h3><a href="' . $permalink . '" title="Ссылка на: '; 
			$echo .= $title . '">' . $title . '</a></h3><p>' . get_the_excerpt() . '  ';
			$echo .= '<a href="' . $permalink . '" tabindex="-1">' . $read_more . '</a></p></div></div>';
			echo $echo; 
		endforeach;
		wp_reset_postdata();
		echo '</div></div></section>';
	endif;
}

// [annocards post_type="post" cat_name="blog" tag_name="promo" section_title="Приходите" read_more="Подробнее..." date="future" exclude="312"] 
add_shortcode( 'annocards', 'annocards_func' );
function annocards_func( $atts ){
	// белый список параметров и значения по умолчанию
	$atts = shortcode_atts( array(
		'post_type' => 'post',
		'cat_name' => '',
		'tag_name' => '',
		'section_title' => null,
		'read_more' => null,
		'date' => '',
		'exclude' => array()
	), $atts );

	$post_type = $atts['post_type'];
	$cat_name = $atts['cat_name'];
	$tag_name = $atts['tag_name'];
	$section_title = $atts['section_title'];
	$read_more = $atts['read_more'];
	$date = $atts['date'];
	$exclude = $atts['exclude'];

	$echo = '';

	global $post;
	$args = array( 'post_type' => $post_type, 'category_name' => $cat_name, 'tag' => $tag_name, 'exclude' => $exclude );

	if( $post_type == 'events'):
		$args += array( 
			'orderby'    => 'event_info_event_date', 
			'order'      => 'DESC',
		);

		if( $date ):
			$today = date('Ymd');
			if($date == 'past') $past_events = true;
			else $future_events = true;
			$compare = $past_events ? '<' : '>=';
			$args += array( 
				'meta_query' => array( array('key' => 'event_info_event_date', 'compare' => $compare, 'value' => $today) )
			);
		endif;
	endif;

	$myposts = get_posts( $args );
	if( $myposts ): 
		$echo .= '<section><div class="row section-container"><div class="col">';
		if( $section_title ) $echo .= '<h2>' . $section_title . '</h2>';
		foreach( $myposts as $post ):
			setup_postdata( $post );
			$permalink = get_the_permalink(); 
			$title = esc_html( get_the_title() );
			if( $post->post_type == 'events') $event_date = markup_event_date( $post->id );

			$echo .= '<div class="row anno-card"><div class="col-12 col-md-4"><a href="' . $permalink . '" title="Ссылка на: ' . $title . '" tabindex="-1">'; 
			// $echo .= get_the_post_thumbnail(null, 'medium');
			$thumb_id = get_post_thumbnail_id();
			$echo .= get_attachment_picture( $thumb_id, 'medium' );
			$echo .= '</a></div><div class="col-12 col-md-8"><h3 class="annocard-title"><a href="' . $permalink . '" title="Ссылка на: '; 
			$echo .= $title . '">' . $title . '</a></h3>';
			// if( $event_date ) $echo .= '<time>'.$event_date.'</time> ';
			$echo .= '<p>'.$event_date.get_the_excerpt();
			if( $read_more ) $echo .= ' <a href="' . $permalink . '" tabindex="-1">' . $read_more . '</a>';
			$echo .= '</p></div></div>';
		endforeach;
		wp_reset_postdata();
		if( $past_events && !is_archive() )
			$echo .= '<p class="anno-ref"><a href="' . get_post_type_archive_link('events') . '" title="Ссылка на все события">Все события ></a></p>';

		$echo .= '</div></div></section>';

	elseif( $future_events ):
		$echo = '<p>На ближайшее время у нас ничего не запланировано.</p>
			<p>Вы можете проверить наши группы в соцсетях (ссылки внизу сайта) – возможно там есть анонсы, которые ещё не добрались до этого сайта.</p>';

	endif;

	return $echo;
}

// [newscards section_id="announcement" section_title="Актуальное" future_events="1" promo_posts="2" promo_events="3" read_more="Подробнее..." exclude="312"] 
add_shortcode( 'newscards', 'newscards_func' );
function newscards_func( $atts ){
	// белый список параметров и значения по умолчанию
	$atts = shortcode_atts( array(
		'post_type' => array('post','events'),
		'section_id' => '',
		'section_title' => null,
		'cat_name' => 'promo',
		// 'date' => 'future',
		'future_events' => '0',
		'promo_posts' => '0',
		'promo_events' => '0',
		'read_more' => null,
		'exclude' => array()
	), $atts );

	$numberposts = 9;
	$post_type = $atts['post_type'];
	$section_id = $atts['section_id'];
	$section_title = $atts['section_title'];
	$cat_name = $atts['cat_name'];
	$date = $atts['date'];
	$future_events = $atts['future_events'];
	$promo_posts = $atts['promo_posts'];
	$promo_events = $atts['promo_events'];
	$read_more = $atts['read_more'];
	$exclude = $atts['exclude'];

	// $args = array( 'numberposts' => $numberposts, 'post_type' => $post_type, 'category_name' => $cat_name );
	$myposts = array();
	if( $future_events ){
		$today = date('Ymd');
		$compare = '>=';
		$args_fe = array( 'post_type' => 'events', 'exclude' => $exclude, 'meta_query' => array( array('key' => 'event_info_event_date', 'compare' => $compare, 'value' => $today) ) );
		$myposts = array_merge($myposts, get_posts( $args_fe ));
	}
	if( $promo_posts ){
		$args_pp = array( 'post_type' => 'post', 'exclude' => $exclude, 'category_name' => $cat_name );
		$myposts = array_merge($myposts, get_posts( $args_pp ));
	}
	if( $promo_events ){
		$args_pe = array( 'post_type' => 'events', 'exclude' => $exclude, 'category_name' => $cat_name );
		$myposts = array_merge($myposts, get_posts( $args_pe ));
	}

	// print_r($args);
	// $myposts = array_merge($myposts1, $myposts2, $myposts3);

	// $my_posts = new WP_Query;
	// $myposts = $my_posts->query( $args );

	global $post;
	$echo = '';

	if ( $myposts ): 
		if( $section_id ) $section_id = ' id="'.$section_id.'"';
		$echo .= '<section'.$section_id.'><div class="row section-container"><div class="col">';
		if( $section_title ) $echo .= '<h2>'.$section_title.'</h2>';
		$echo .= '<div class="row">';
		foreach( $myposts as $post ):
			setup_postdata( $post );
			$permalink = get_the_permalink(); 
			$title = esc_html( get_the_title() );
			if( $post->post_type == 'events') $event_date = markup_event_date( $post->id );

			$echo .= '<div class="newscard-container col-md-6 col-lg-4"><div class="newscard"><a href="'.$permalink.'" title="Ссылка на: '.$title.'">'; 
			// $echo .= get_the_post_thumbnail(null, 'medium');
			$thumb_id = get_post_thumbnail_id();
			$echo .= get_attachment_picture( $thumb_id, 'medium' );
			$echo .= '</a><h3 class="newscard-title">'.$event_date.$title.'</h3>';
			// $echo .= get_the_excerpt();
			if( $read_more ) $echo .= ' <a href="'.$permalink.'" title="Ссылка на: '.$title.'" tabindex="-1">'.$read_more.'</a>';
			$echo .= '</div></div>';
		endforeach;
		wp_reset_postdata();

		$echo .= '</div><!-- row -->';
		$echo .= '</div></div></section>';
	endif;

	return $echo;
}

function markup_event_date( $post_id = null ){
	$event_date = '';
	if( $event_date = get_field('event_info_event_date', $post_id) ){
		$raw_date = get_field('event_info_event_date', $post_id, false);
		$date_obj = new DateTime($raw_date);
		$datetime = $date_obj->format('Y-m-d');
		$event_date = '<time datetime="'.$datetime.'">'.$event_date.'</time> ';
	}

	return $event_date;
}

add_action( 'init', 'register_post_types' );

function register_post_types(){
	register_post_type('events', array(
		'label'  => null,
		'labels' => array(
			'name'               => 'События', // основное название для типа записи
			'singular_name'      => 'Событие', // название для одной записи этого типа
			'add_new'            => 'Добавить событие', // для добавления новой записи
			'add_new_item'       => 'Добавление события', // заголовка у вновь создаваемой записи в админ-панели.
			'edit_item'          => 'Редактировать событие', // для редактирования типа записи
			'new_item'           => 'Новое событие', // текст новой записи
			'view_item'          => 'Смотреть событие', // для просмотра записи этого типа.
			'search_items'       => 'Искать события', // для поиска по этим типам записи
			'not_found'          => 'Не найдено', // если в результате поиска ничего не было найдено
			'not_found_in_trash' => 'Не найдено в корзине', // если не было найдено в корзине
			'parent_item_colon'  => '', // для родителей (у древовидных типов)
			'menu_name'          => 'События', // название меню
		),
		'description'         => 'События – это наши экскурсии, лекции и прочие мероприятия с датой.',
		'public'              => true,
		'publicly_queryable'  => true, // зависит от public
		'exclude_from_search' => false, // зависит от public
		'show_ui'             => true, // зависит от public
		'show_in_menu'        => true, // показывать ли в меню адмнки
		'show_in_admin_bar'   => true, // по умолчанию значение show_in_menu
		'show_in_nav_menus'   => true, // зависит от public
		'show_in_rest'        => true, // добавить в REST API. C WP 4.7
		'rest_base'           => null, // $post_type. C WP 4.7
		'menu_position'       => 4,
		'menu_icon'           => 'dashicons-calendar-alt', 
		//'capability_type'   => 'post',
		//'capabilities'      => 'post', // массив дополнительных прав для этого типа записи
		//'map_meta_cap'      => null, // Ставим true чтобы включить дефолтный обработчик специальных прав
		'hierarchical'        => false,
		'supports'            => array('title','author','thumbnail','excerpt'), // 'title','editor','author','thumbnail','excerpt','trackbacks','custom-fields','comments','revisions','page-attributes','post-formats'
		'taxonomies'          => array('category'),
		'has_archive'         => true,
		'rewrite'             => true,
		'query_var'           => true,
	) );
}

//отключение всех архивов start
// function wph_disable_all_archives($false) {
//     if (is_archive()) {
//         global $wp_query;
//         $wp_query->set_404();
//         status_header(404);
//         nocache_headers();
//         return true;
//     }
//     return $false;
// }
//удаление ссылки на архив автора
// function wph_remove_author_link($content) {return home_url();}

// add_action('pre_handle_404', 'wph_disable_all_archives');
// add_filter('author_link', 'wph_remove_author_link');
//отключение всех архивов end



add_filter( 'shortcode_atts_wpcf7', 'custom_shortcode_atts_wpcf7', 10, 3 );
function custom_shortcode_atts_wpcf7( $out, $pairs, $atts ) {
	if( isset($atts['event-title']) )
		$out['event-title'] = $atts['event-title'];

	return $out;
}

// "Sender email address does not belong to the site domain." avoid
// add_filter( 'wpcf7_validate_configuration', '__return_false' );

remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');

## Удаляет "Рубрика: ", "Метка: " и т.д. из заголовка архива
add_filter( 'get_the_archive_title', function( $title ){
	return preg_replace('~^[^:]+: ~', '', $title );
});

// [socwidgets section_title="Мы в соцсетях" vk_mode="3" vk_width="300" vk_height="400" vk_id="94410363" no_cover="1" fb=1] 
add_shortcode( 'socwidgets', 'socwidgets_func' );

function socwidgets_func( $atts ){
	// белый список параметров и значения по умолчанию
	$atts = shortcode_atts( array(
		'section_title' => null,
		'vk_mode' => '3',
		'vk_width' => '300',
		'vk_height' => '400',
		'vk_id' => null,
		'no_cover' => null,
		'fb' => false,
	), $atts );

	$section_title = $atts['section_title'];
	$vk_mode = $atts['vk_mode'];
	$vk_width = $atts['vk_width'];
	$vk_height = $atts['vk_height'];
	$vk_id = $atts['vk_id'];
	$no_cover = $atts['no_cover'];
	$fb = $atts['fb'];

	$echo = '<section id="soc-section"><div class="row section-container"><div class="col">';
	if( $section_title ) $echo .= '<h2>'.$section_title.'</h2>';
	$echo .= '<div class="row">';
	if( $vk_id ):
		$echo .= '<div class="col-md-6"><!-- VK Widget --><div class="socwidget" id="vk_groups" data-id="'.$vk_id.'"';
		if( $vk_mode ) $echo .= ' data-mode="'.$vk_mode.'"';
		if( $vk_width ) $echo .= ' data-width="'.$vk_width.'"';
		if( $vk_height ) $echo .= ' data-height="'.$vk_height.'"';
		if( $no_cover ) $echo .= ' data-no_cover="'.$no_cover.'"';
		$echo .= '></div></div>';
	endif;
	if( $fb ):
		$echo .= '<div class="col-md-6"><div id="fb-root"></div>';
		$echo .= '<div class="socwidget fb-group" data-href="https://www.facebook.com/groups/excursorel" data-width="300" data-show-social-context="true" data-show-metadata="false"></div></div>';
	endif;
	$echo .= '</div>';
	$echo .= '<noscript><p><a href="https://vk.com/excurs_orel" target="_blank">ВКонтакте</a></p><p><a href="https://www.facebook.com/groups/excursorel" target="_blank">Фейсбук</a></p></noscript>';
	$echo .= '</div></div></section>';

	return $echo;
}

// [carousel class="carousel" hrefs=1]
add_shortcode( 'carousel', 'carousel_func' );

function carousel_func( $atts ){
	// белый список параметров и значения по умолчанию
	$atts = shortcode_atts( array(
		'class' => 'carousel',
		'hrefs' => false,
	), $atts );

	$class = $atts['class'];
	$class_item = $class.'-item';
	$hrefs = $atts['hrefs'];

	$echo = '';

	global $post;
	//Get the images ids from the post_metadata
	$images = acf_photo_gallery( 'carousel_gal', $post->ID );
	if( count($images) ):
		$images_counter = 0;
		$echo .= '<div class="'.$class.'">';
		foreach( $images as $image ):
			$id = $image['id']; // The attachment id of the media
			$title = $image['title']; //The title
			// $description = $image['caption']; //The caption (Description!)
			// $full_image_url= $image['full_image_url']; //Full size image url
			$post_id = wp_get_post_parent_id( $id ); // get_post($id)->post_parent 
			$post_link = get_permalink( $post_id ); 
			$attr = $hrefs ? null : array( 'title' => $title);

			$echo .= '<div class="'.$class_item.'">';
			if( $hrefs && $post_link )
				$echo .= '<a href="'.$post_link.'" title="'.get_the_title( $post_id ).'">';

			// Get picture item. 1st ($images_counter == 0) is not lazy 
			// if( $images_counter == 0 ) $echo .= wp_get_attachment_image( $id, 'medium_large', false, $attr );
			// else $echo .= get_lazy_attachment_image( $id, 'medium_large', false, $attr );
			$echo .= get_attachment_picture( $id, $size, false, $attr, $images_counter > 0 );
			$images_counter++;

			if( $hrefs && $post_link ) $echo .= '</a>';
			$echo .= '</div>';

		endforeach;
		$echo .= '</div> <!-- .'.$class.' -->';

		do_action( 'add_carousel_scripts' );

	endif;

	return $echo;
}

/*
	get_lazy_attachment_image() is mod of standart wp_get_attachment_image()
*/
function get_lazy_attachment_image( $attachment_id, $size = 'thumbnail', $icon = false, $attr = '' ) {
	$html  = '';
	$image = wp_get_attachment_image_src( $attachment_id, $size, $icon );
	if ( $image ) {
		list($src, $width, $height) = $image;
		$hwstring                   = image_hwstring( $width, $height );
		$size_class                 = $size;
		if ( is_array( $size_class ) ) {
			$size_class = join( 'x', $size_class );
		}
		$attachment   = get_post( $attachment_id );
		$default_attr = array(
			'src'		=> '/wp-content/themes/excursions/assets/include/ajax-loader.gif', // for validation 
			'data-src' 	=> $src, // was: 'src' => $src, 
			'class' 	=> "attachment-$size_class size-$size_class",
			'alt' 		=> trim( strip_tags( get_post_meta( $attachment_id, '_wp_attachment_image_alt', true ) ) ),
		);

		$attr = wp_parse_args( $attr, $default_attr );

		// Generate 'srcset' and 'sizes' if not already present.
		if ( empty( $attr['srcset'] ) ) {
			$image_meta = wp_get_attachment_metadata( $attachment_id );

			if ( is_array( $image_meta ) ) {
				$size_array = array( absint( $width ), absint( $height ) );
				$srcset     = wp_calculate_image_srcset( $size_array, $src, $image_meta, $attachment_id );
				$sizes      = wp_calculate_image_sizes( $size_array, $src, $image_meta, $attachment_id );

				if ( $srcset && ( $sizes || ! empty( $attr['sizes'] ) ) ) {
					// $attr['srcset'] = ''; 			// for validation 
					$attr['data-srcset'] = $srcset; // was: $attr['srcset'] = $srcset;

					if ( empty( $attr['sizes'] ) ) {
						$attr['data-sizes'] = $sizes;	// was: $attr['sizes'] = $sizes;
					}
				}
			}
		}

		/**
		 * Filters the list of attachment image attributes.
		 *
		 * @since 2.8.0
		 *
		 * @param array        $attr       Attributes for the image markup.
		 * @param WP_Post      $attachment Image attachment post.
		 * @param string|array $size       Requested size. Image size or array of width and height values
		 *                                 (in that order). Default 'thumbnail'.
		 */
		$attr = apply_filters( 'wp_get_attachment_image_attributes', $attr, $attachment, $size );
		$attr = array_map( 'esc_attr', $attr );
		$html = rtrim( "<img $hwstring" );
		foreach ( $attr as $name => $value ) {
			$html .= " $name=" . '"' . $value . '"';
		}
		$html .= ' />';
	}

	return $html;
}

/*
	get_attachment_picture() is mod of standart wp_get_attachment_image() 
*/
function get_attachment_picture( $attachment_id, $size = 'thumbnail', $icon = false, $attr = '', $lazy = true ) {
	$html  = '';
	$image = wp_get_attachment_image_src( $attachment_id, $size, $icon );
	if ( $image ) {
		$html = '<picture>';
		list($src, $width, $height) = $image;
		$hwstring                   = image_hwstring( $width, $height );
		$size_class                 = $size;
		if ( is_array( $size_class ) ) {
			$size_class = join( 'x', $size_class );
		}
		$attachment   = get_post( $attachment_id );
		$default_attr = array(
			'src' 		=> $src,
			'class' 	=> "attachment-$size_class size-$size_class",
			'alt' 		=> trim( strip_tags( get_post_meta( $attachment_id, '_wp_attachment_image_alt', true ) ) ),
		);

		if( $lazy ){
			$default_attr = array_merge( $default_attr, array(
				'src'		=> '/wp-content/themes/excursions/assets/include/placeholder_3x2.png', // for validation 
				'data-src' 	=> $src,
			));
		}

		$attr = wp_parse_args( $attr, $default_attr );

		// Generate <source>'s
		$image_meta = wp_get_attachment_metadata( $attachment_id );

		// Retrieve the uploads sub-directory from the full size image.
		$dirname = _wp_get_attachment_relative_path( $image_meta['file'] );
	
		if( $dirname ) {
			$dirname = trailingslashit( $dirname );
		}
	
		$upload_dir    = wp_get_upload_dir();
		$image_baseurl = trailingslashit( $upload_dir['baseurl'] ) . $dirname;
	
		/*
		 * If currently on HTTPS, prefer HTTPS URLs when we know they're supported by the domain
		 * (which is to say, when they share the domain name of the current request).
		 */
		if ( is_ssl() && 'https' !== substr( $image_baseurl, 0, 5 ) && parse_url( $image_baseurl, PHP_URL_HOST ) === $_SERVER['HTTP_HOST'] ) {
			$image_baseurl = set_url_scheme( $image_baseurl, 'https' );
		}

		// $html .= $source.$full_image_url.'.webp" media="(min-width: 1200px)" type="image/webp">';
		// $image_sizes = $image_meta['sizes'];
		// $image_sizes = array_reverse( $image_sizes );

		$source = $lazy ? '<source data-srcset="' : '<source srcset="';

		$srcset = generate_image_srcset( $image_meta, $image_baseurl, true );
		if( $srcset ) $html .= $source.$srcset.'" type="image/webp">';

		$srcset = generate_image_srcset( $image_meta, $image_baseurl );
		if( $srcset ) $html .= $source.$srcset.'">';

		// $attr = apply_filters( 'wp_get_attachment_image_attributes', $attr, $attachment, $size );
		$attr = array_map( 'esc_attr', $attr );
		$html .= rtrim( "<img $hwstring" );
		foreach ( $attr as $name => $value ) {
			$html .= " $name=" . '"' . $value . '"';
		}
		$html .= ' /></picture>';
	}

	return $html;
}

function generate_image_srcset( $image_meta, $image_baseurl, $webp = false ){
	$srcset = '';
	$image_sizes = $image_meta['sizes'];

	if ( !is_array( $image_sizes ) ) {
		return $srcset;
	}

	$sfx = $webp ? '.webp' : '';
	$image_basename = wp_basename( $image_meta['file'] );

	$strings = array();
	foreach( $image_sizes as $image ){
		// Check if image meta isn't corrupted.
		if ( ! is_array( $image ) ) {
			continue;
		}

		if( isset($image['file']) ){

			$file_width = $image['width'];

			if( $file_width < 300 ) continue;

			$image_url = $image_baseurl . $image['file'];
			$string = $image_url.$sfx.' '.$file_width.'w';
			array_push($strings, $string);
			// $srcset .= 
			// $media = 'media="(min-width: '.$file_width.'px)"';
			// $html .= $source.$image_url.'" '.$media.'>';
		}  
	}

	$image_url = $image_baseurl . $image_basename; // full_image_url
	$file_width  = (int) $image_meta['width']; // full_image_width
	$string = $image_url.$sfx.' '.$file_width.'w';
	array_push($strings, $string);

	$srcset = implode(', ', $strings);

	return $srcset;
}

// [image class="" id=1 size="medium_large" title=false href=1 lazy=0] 
add_shortcode( 'image', 'image_func' );

function image_func( $atts ){
	// белый список параметров и значения по умолчанию
	$atts = shortcode_atts( array(
		'class' => 'image',
		'id' => null,
		'size' => 'thumbnail',
		'title' => true,
		'href' => false,
		'lazy' =>true,
	), $atts );

	$class = $atts['class'];
	$id = $atts['id'];
	$size = $atts['size'];
	$title = $atts['title'];
	$href = $atts['href'];
	$lazy = $atts['lazy'];

	$echo = '';

	if( !$id ) $id = get_post_thumbnail_id();

	if( $id ):

		if( $href ){
			$post_id = wp_get_post_parent_id( $id );
			if( $post_link = get_permalink( $post_id ) ){
				$ahref_pre = '<a href="'.$post_link.'" title="'.get_the_title( $post_id ).'">';
				$ahref_post = '</a>';
			}
		}
		elseif( $title ){
			$title = get_the_title( $id );
			$attr = array( 'title' => $title);
		}

		// $image = wp_get_attachment_image( $id, $size, false, $attr );
		$image = get_attachment_picture( $id, $size, false, $attr, $lazy );

		if( $image ){
			$echo .= '<div class="'.$class.'"><figure>'.$ahref_pre.$image.$ahref_post.'</figure></div>';
		}

	endif;

	return $echo;
}

// [gallery class="post-gallery" item="gallery-item" fancybox="gallery" lazy=0] 
add_shortcode( 'gallery', 'gallery_func' );

function gallery_func( $atts ){
	// белый список параметров и значения по умолчанию
	$atts = shortcode_atts( array(
		'class' => 'post-gallery',
		'item' => 'gallery-item',
		'fancybox' => 'gallery',
		'lazy' => true,
	), $atts );

	$class = $atts['class'];
	$item = $atts['item'];
	$fancybox = $atts['gallery'];
	$lazy = $atts['lazy'];

	$echo = '';

	global $post;
	//Get the images ids from the post_metadata
	$images = acf_photo_gallery( 'gallery_gal', $post->ID );
	if( count($images) ):
		$images_counter = 0;
		$echo .= '<div class="row '.$class.'">';
		foreach( $images as $image ):
			$id = $image['id']; // The attachment id of the media
			$title = $image['title']; //The title
			$description = $image['caption']; //The caption (Description!)
			$full_image_url = $image['full_image_url']; //Full size image url

			if( $title ) $attr = array( 'title' => $title);

			$echo .= '<div class="'.$item.' col-12">';
			$echo .= '<figure><a data-fancybox="'.$fancybox.'" href="'.$full_image_url.'" data-caption="'.$title.'">';
			// Get picture item. 1st two ($images_counter == 0 || 1) are not lazy 
			// $echo .= get_lazy_attachment_image( $id, 'medium_large', false, $attr );
			$echo .= get_attachment_picture( $id, 'medium_large', false, $attr, $images_counter > 1 );
			$images_counter++;
			$echo .= '</a>';
			if( $description ) $echo .= '<figcaption>'.$description.'</figcaption>';
			$echo .= '</figure></div>';

		endforeach;
		$echo .= '</div> <!-- row '.$class.' -->';

		do_action( 'add_gallery_scripts' );

	endif;

	return $echo;
}

// Меняем порядок вывода записей для архива типа записи 'events'
add_action('pre_get_posts', 'events_orderby_meta', 1 );
function events_orderby_meta( $query ) {
	// Выходим, если это админ-панель или не основной запрос
	if( is_admin() || ! $query->is_main_query() )
		return;

	if( $query->is_post_type_archive('events') ){
		// $query->set( 'posts_per_page', 5 ); // default=10
		$query->set( 'orderby', 'meta_value' );
		$query->set( 'meta_key', 'event_info_event_date' );
		$query->set( 'post__not_in', [312] ); // exclude="312"
	}
}

// Включаем поиск для записей типа 'events'
add_action('pre_get_posts', 'get_posts_search_filter');
function get_posts_search_filter( $query ){
	if ( ! is_admin() && $query->is_main_query() && $query->is_search ) {
		$query->set('post_type', array('post', 'events') );
	}
}

// Настраиваем страницам пагинации rel="canonical" на 1-ую стр. архива (для Yoast SEO) 
function return_canon () {
	$canon_page = get_pagenum_link(1);
	return $canon_page;
}
function canon_paged() {
	if (is_paged()) {
		add_filter( 'wpseo_canonical', 'return_canon' );
	}
}
add_filter('wpseo_head','canon_paged');

/**
 * Генерирует webp копии изображений сразу после загрузки изображения в медиабиблиотеку
 * - новые файлы сохраняет с именем name.ext.webp, например, thumb.jpg.webp
 * 
 * -- на локальном сервере wepb получаются больше jpg. Надо будет дорабатывать. 
 */
function gt_webp_generation($metadata) {
    $uploads = wp_upload_dir(); // получает папку для загрузки медиафайлов

    $file = $uploads['basedir'] . '/' . $metadata['file']; // получает исходный файл
    $ext = wp_check_filetype($file); // получает расширение файла
    
    if ( $ext['type'] == 'image/jpeg' ) { // в зависимости от расширения обрабатаывает файлы разными функциями
        $image = imagecreatefromjpeg($file); // создает изображение из jpg
        
    } elseif ( $ext['type'] == 'image/png' ){
        $image = imagecreatefrompng($file); // создает изображение из png
        imagepalettetotruecolor($image); // восстанавливает цвета
        imagealphablending($image, false); // выключает режим сопряжения цветов
        imagesavealpha($image, true); // сохраняет прозрачность

    }
    imagewebp($image, $uploads['basedir'] . '/' . $metadata['file'] . '.webp', 90); // сохраняет файл в webp

    foreach ($metadata['sizes'] as $size) { // перебирает все размеры файла и также сохраняет в webp
        $file = $uploads['url'] . '/' . $size['file'];
        $ext = $size['mime-type'];

        if ( $ext == 'image/jpeg' ) {
            $image = imagecreatefromjpeg($file); 
            
        } elseif ( $ext == 'image/png' ){
            $image = imagecreatefrompng($file);
            imagepalettetotruecolor($image);
            imagealphablending($image, false);
            imagesavealpha($image, true);
        }
        
        imagewebp($image, $uploads['basedir'] . $uploads['subdir'] . '/' . $size['file'] . '.webp', 90);

    }

    return $metadata;
}
// add_filter('wp_generate_attachment_metadata', 'gt_webp_generation');

// подключаем AJAX обработчики, только когда в этом есть смысл
if( wp_doing_ajax() ){
	add_action('wp_ajax_get_events', 'get_events_func');
	add_action('wp_ajax_nopriv_get_events', 'get_events_func');
}
function get_events_func() {
	// $whatever = intval( $_POST['whatever'] );
	// $whatever += 10;
	// echo $whatever;	
	global $post;
	$events = array();

	$args = array( 
		'post_type' => 'events', 
		// 'orderby'   => 'event_info_event_date',  
		'orderby' 	=> 'meta_value',
		'meta_key' 	=> 'event_info_event_date',
		'order'     => 'DESC',
		'exclude' 	=> '312',
		'numberposts' => 999, 
	);
	$myposts = get_posts( $args );

	if( $myposts ){
		$ev_counter = 0;
		
		foreach( $myposts as $post ){
			setup_postdata( $post );
			$post_id = get_the_ID();
			$permalink = get_the_permalink();
			$title = esc_html( get_field('event_info_event_date') . ' ' . get_the_title() );
			$location = get_field('event_info_event_place_map');

			$events[$ev_counter]['post_id'] = $post_id;
			$events[$ev_counter]['permalink'] = $permalink;
			$events[$ev_counter]['title'] = $title;
			$events[$ev_counter]['lat'] = $location['lat'];
			$events[$ev_counter]['lng'] = $location['lng'];

			$ev_counter++;

		}
		wp_reset_postdata();		
	}

	// $str = json_encode( print_r($events, true) );
	// echo "<script> console.log('$str'); </script>";
	// echo $events_num;
	echo json_encode( $events );

	wp_die(); // выход нужен для того, чтобы в ответе не было ничего лишнего, только то что возвращает функция
}
