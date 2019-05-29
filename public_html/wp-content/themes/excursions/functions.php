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

$LINKS = array();
// $SCRIPTS = array();
$consolelog = '';
$SCRIPTS_VER = '20190529';
$STYLES_VER = '20190529';
$WEBP_ON = !(home_url() == 'http://excurs-orel');
if(!$WEBP_ON) console_log('WEBP_OFF');
$PLACEHOLDER_URL = get_template_directory_uri() . '/assets/img/placeholder_3x2.png';
// $PLACEHOLDER_URL = '/wp-content/themes/excursions/assets/img/placeholder_3x2.png';

// add_script( get_template_directory_uri().'/assets/include/cssrelpreload.js', false, 'nomodule' );
// add_script('script');
preload_link( get_template_directory_uri().'/assets/css/main_bottom.css', $STYLES_VER );

function excursions_scripts() {
	global $SCRIPTS_VER;
	global $STYLES_VER;
	$scripts_dirname = get_template_directory_uri() . '/assets/js/';
	wp_enqueue_style( 'excursions-style', get_stylesheet_uri(), array(), $STYLES_VER );
	// wp_enqueue_style( 'bootstrap-grid', get_template_directory_uri() . '/assets/include/bootstrap-grid.min.css', array(), null );

	wp_enqueue_script( 'excursions-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20151215', true );

	wp_enqueue_script( 'excursions-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20151215', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	wp_enqueue_script( 'script-js', $scripts_dirname.'script.js', array(), $SCRIPTS_VER, 'in_footer' );
	wp_enqueue_script( 'script-legacy', $scripts_dirname.'script-legacy.js', array(), $SCRIPTS_VER, 'in_footer' );

	if( is_singular('events') || is_singular('guidebook') ){
		wp_enqueue_style( 'events', get_template_directory_uri() . '/assets/css/events.css', array(), $STYLES_VER );
		wp_enqueue_script( 'events-js', $scripts_dirname.'events.js', array('script-js'), $SCRIPTS_VER, 'in_footer' );
		wp_enqueue_script( 'events-legacy', $scripts_dirname.'events-legacy.js', array('script-legacy'), $SCRIPTS_VER, 'in_footer' );
		// add_script('events');
	}

	wp_deregister_script( 'jquery' );
	// <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
	wp_register_script( 'jquery', '//code.jquery.com/jquery-3.3.1.min.js', array(), null, 'in_footer' );
	// wp_register_script( 'slick-js', get_template_directory_uri() . '/assets/include/slick.min.js', array('jquery'), null, 'in_footer' );
	wp_register_script( 'glide-js', get_template_directory_uri() . '/assets/include/glide.min.js', array(), null, 'in_footer' );
	wp_register_script( 'widgets-js', $scripts_dirname.'widgets.js', array('glide-js'), $SCRIPTS_VER, 'in_footer' );
	wp_register_script( 'widgets-legacy', $scripts_dirname.'widgets-legacy.js', array('glide-js'), $SCRIPTS_VER, 'in_footer' );

	$PF_URL = 'https://polyfill.io/v3/polyfill.min.js?features=fetch%2CElement.prototype.matches%2CObject.keys%2CNodeList.prototype.forEach%2CArray.prototype.forEach';
	// add_script( $PF_URL, false, 'nomodule' );
	wp_enqueue_script( 'polyfills-js', $PF_URL, array(), null, 'in_footer' );
	wp_enqueue_script( 'cssrelpreload-js', get_template_directory_uri() . '/assets/include/cssrelpreload.js', array(), null, 'in_footer' );
	// wp_register_script( 'promise-polyfill-js', '//cdn.jsdelivr.net/npm/promise-polyfill@8/dist/polyfill.min.js', array(), null, 'in_footer' );
	// wp_register_script( 'es6-polyfill-js', '//polyfill.io/v3/polyfill.min.js?features=es6', array(), null, 'in_footer' );
	// wp_enqueue_script( 'jquery', get_template_directory_uri() . '/assets/include/jquery-3.3.1.min.js', array(), false, 'in_footer' );
	// wp_enqueue_script( 'bootstrap-js', get_template_directory_uri() . '/assets/include/bootstrap.min.js', array('jquery'), null, 'in_footer' );
	// wp_enqueue_script( 'script-es5', get_template_directory_uri() . '/assets/js/script-es5.js', array(), $SCRIPTS_VER, 'in_footer' );
	// wp_register_script( 'googlemap-api?key=YOUR_API_KEY', '//maps.googleapis.com/maps/api/js', array(), false, 'in_footer' );
	// wp_register_script( 'ymap-api?apikey=6ebdbbc2-3779-4216-9d88-129e006559bd&lang=ru_RU', '//api-maps.yandex.ru/2.1/', array(), false, 'in_footer' );
	// wp_register_script( 'ymap-api', '//api-maps.yandex.ru/2.1/' );
	wp_register_script( 'acf_map-js', $scripts_dirname.'acf_map.js', array('script-js'), $SCRIPTS_VER, 'in_footer' );
	wp_register_script( 'acf_map-legacy', $scripts_dirname.'acf_map-legacy.js', array('script-legacy'), $SCRIPTS_VER, 'in_footer' );
	wp_register_script( 'events_map-js', $scripts_dirname.'events_map.js', array('script-js'), $SCRIPTS_VER, 'in_footer' );
	wp_register_script( 'events_map-legacy', $scripts_dirname.'events_map-legacy.js', array('script-legacy'), $SCRIPTS_VER, 'in_footer' );
	wp_register_script( 'guidebook_map-js', $scripts_dirname.'guidebook_map.js', array('script-js'), $SCRIPTS_VER, 'in_footer' );
	wp_register_script( 'guidebook_map-legacy', $scripts_dirname.'guidebook_map-legacy.js', array('script-legacy'), $SCRIPTS_VER, 'in_footer' );
	
	wp_register_script( 'yashare-js', '//yastatic.net/share2/share.js', array(), false, 'in_footer' );
	wp_register_script( 'fancybox-js', '//cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.6/dist/jquery.fancybox.min.js', array('jquery'), null, 'in_footer' );
	// wp_register_style( 'fancybox', '//cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.6/dist/jquery.fancybox.min.css' );
	// wp_register_style( 'slick-full', get_template_directory_uri() . '/assets/include/slick-full.css', array(), null );
	wp_register_style( 'glide-core', get_template_directory_uri() . '/assets/include/glide.core.min.css', array(), null );
	wp_register_style( 'glide-theme', get_template_directory_uri() . '/assets/include/glide.theme.min.css', array(), null );
	wp_register_style( 'events_map', get_template_directory_uri() . '/assets/css/events_map.css', array(), $STYLES_VER );
	wp_register_style( 'guidebook_map', get_template_directory_uri() . '/assets/css/guidebook_map.css', array(), $STYLES_VER );
}
add_action( 'wp_enqueue_scripts', 'excursions_scripts' );

function change_my_script( $tag, $handle, $src ) {
	switch ( $handle ) {
		case ( 'script-js' ):
		case ( 'events-js' ):
		case ( 'widgets-js' ):
		case ( 'acf_map-js' ):
		case ( 'events_map-js' ):
		case ( 'guidebook_map-js' ):
			$_tag = str_replace( "type='text/javascript'", "type='module'", $tag );
			break;

		case ( 'script-legacy' ):
		case ( 'events-legacy' ):
		case ( 'widgets-legacy' ):
		case ( 'acf_map-legacy' ):
		case ( 'events_map-legacy' ):
		case ( 'guidebook_map-legacy' ):
		case ( 'polyfills-js' ):
		$_tag = str_replace( "type='text/javascript'", "nomodule", $tag );
		break;
		
		case ( 'yashare-js' ):
		case ( 'cssrelpreload-js' ):
			$_tag = str_replace( "type='text/javascript'", "async defer", $tag );
			break;

		default:
			$_tag = $tag;
	}

	return $_tag;
}
add_filter( 'script_loader_tag', 'change_my_script', 10, 3 );


function preload_link( $href, $ver=false, $type='style' ){
	global $LINKS;
	// если $href ещё не было, добавляем в массив ссылок 
	// if( !in_array( $style_href, $style_hrefs ) ) array_push($style_hrefs, $style_href);

	foreach( $LINKS as $link ){
		if( $link['href'] == $href ) return;
	}
	$LINKS[] = array('href' => $href, 'ver' => $ver, 'type' => $type);
}

function preload_links(){ 
	// <link rel="preload" href="AO.js" as="script">
	// <link rel="preload" href="AO.css" as="style">
	global $LINKS;
	// echo '<script>console.log('.print_r($links).');</script>'.PHP_EOL;

	if( !empty($LINKS) ){
		foreach( $LINKS as $link ){
			$href = $link['href'];
			if( $link['ver'] ){
				$href .= '?ver='.$link['ver'];
			}

			if( $link['type'] == 'style' ){
				echo '<link rel="preload" as="style" href="'.$href.'" onload="this.rel=\'stylesheet\'" />'.PHP_EOL;
				echo '<noscript><link rel="stylesheet" href="'.$href.'"></noscript>'.PHP_EOL;
			}
			else if( $link['type'] == 'font' ){
				echo '<link rel="preload" as="font" type="font/woff" href="'.$href.'" crossorigin />'.PHP_EOL;
			}
		}

		// wp_enqueue_script( 'cssrelpreload-js' );
	}
}
add_action( 'wp_footer', 'preload_links' );

// function add_script( $src, $ver=true, $attr='module' ){
// 	global $SCRIPTS;

// 	foreach( $SCRIPTS as $script ){
// 		if( $script['src'] == $src ) return;
// 	}
// 	$SCRIPTS[] = array('src' => $src, 'ver' => $ver, 'attr' => $attr);
// }

// function add_top_scripts(){
// 	global $SCRIPTS;

// 	if( !empty($SCRIPTS) ){
// 		foreach( $SCRIPTS as $script ){
// 			if( $script['attr'] == 'nomodule' ){
// 				// echo '<script '.$script['attr'].' src="'.$script['src'].'"></script>'.PHP_EOL;
// 				echo '<script nomodule src="'.$script['src'].'"></script>'.PHP_EOL;
// 				// удалить $script из $SCRIPTS 
// 			}
// 		}
// 	}
// }
// add_action( 'wp_footer', 'add_top_scripts', 5 );

// function add_scripts(){
// 	global $SCRIPTS;
// 	global $SCRIPTS_VER;
// 	$scripts_dirname = get_template_directory_uri() . '/assets/js/';

// 	if( !empty($SCRIPTS) ){
// 		foreach( $SCRIPTS as $script ){
// 			// echo '<script async defer src="'.$href.'"></script>'.PHP_EOL;
// 			if( $script['attr'] == 'nomodule' ){
// 				continue;
// 			}
// 			elseif( $script['attr'] == 'async' ){
// 				echo '<script async defer src="'.$script['src'].'"></script>'.PHP_EOL;
// 			}
// 			else{
// 				$src_parts 	= pathinfo( $script['src'] );
// 				$dir_uri 	= ($src_parts['dirname'] && $src_parts['dirname'] != '.') ? $src_parts['dirname'] : $scripts_dirname;
// 				$filename 	= $src_parts['filename'];
// 				$ver 		= $script['ver'] ? '?ver='.$SCRIPTS_VER : '';
// 				$script_url = $dir_uri . $filename . '.js' . $ver;
				
// 				if( $script['attr'] == 'module' ){
// 					$script_legacy_url = $dir_uri . $filename . '-legacy.js' . $ver;
	
// 					echo '<script type="module" src="'.$script_url.'"></script>'.PHP_EOL;
// 					echo '<script nomodule src="'.$script_legacy_url.'"></script>'.PHP_EOL;
// 				}
// 				else{
// 					echo '<script defer src="'.$script_url.'"></script>'.PHP_EOL;
// 				}
// 			}
// 		}
// 	}
// }
// add_action( 'wp_footer', 'add_scripts', 20 );

// if( $gallery ) do_action( 'add_gallery_scripts' );
add_action( 'add_gallery_scripts', 'add_gallery_scripts_func', 10, 0);
function add_gallery_scripts_func() {
	global $STYLES_VER;
	wp_enqueue_script( 'fancybox-js' );
	// wp_enqueue_style( 'fancybox' );
	// wp_enqueue_style( 'gallery' );
	preload_link( '//cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.6/dist/jquery.fancybox.min.css' );
	preload_link( get_template_directory_uri().'/assets/css/gallery.css', $STYLES_VER );
}

// do_action( 'add_carousel_scripts' );
// add_action( 'add_carousel_scripts', 'add_carousel_scripts_func', 10, 0);
function add_carousel_scripts() {
	// global $SCRIPTS_VER;
	wp_enqueue_script( 'widgets-js' );
	wp_enqueue_script( 'widgets-legacy' );
	wp_enqueue_style( 'glide-core' );
	wp_enqueue_style( 'glide-theme' );
	// preload_link( get_template_directory_uri() . '/assets/include/glide.core.min.css' );
	// preload_link( get_template_directory_uri() . '/assets/include/glide.theme.min.css' );
	// add_script('widgets');
	// wp_enqueue_style( 'slick-full' );
	// preload_link( get_template_directory_uri().'/assets/include/slick-full.css' ); -- markup flash in Firefox 
	// <link rel="preload" as="font" type="font/woff" href="$theme_path/assets/include/fonts/slick.woff" crossorigin>
	// preload_link( get_template_directory_uri().'/assets/include/fonts/slick.woff', false, 'font' );
}

// if( '.events_map' ) do_action( 'events_map_scripts' );
add_action( 'events_map_scripts', 'events_map_scripts_func', 10, 0);
function events_map_scripts_func() {
	// global $SCRIPTS_VER;
	// preload_link( get_template_directory_uri().'/assets/css/events_map.css', $SCRIPTS_VER );
	// wp_enqueue_script( 'ymap-api?lang=ru_RU' );
	// wp_enqueue_script( 'ymap-api?lang=ru_RU', '//api-maps.yandex.ru/2.1/' );
	// add_script('//api-maps.yandex.ru/2.1/?lang=ru_RU');
	wp_enqueue_style( 'events_map' );
	wp_enqueue_script( 'events_map-js' );
	wp_localize_script( 'events_map-js', 'myajax', array( 'url' => admin_url('admin-ajax.php') ) );
	wp_enqueue_script( 'events_map-legacy' );
	wp_localize_script( 'events_map-legacy', 'myajax', array( 'url' => admin_url('admin-ajax.php') ) );
}

add_action( 'guidebook_map_scripts', 'guidebook_map_scripts_func', 10, 0);
function guidebook_map_scripts_func() {
	wp_enqueue_style( 'guidebook_map' );
	wp_enqueue_script( 'guidebook_map-js' );
	wp_localize_script( 'guidebook_map-js', 'myajax', array( 'url' => admin_url('admin-ajax.php') ) );
	wp_enqueue_script( 'guidebook_map-legacy' );
	wp_localize_script( 'guidebook_map-legacy', 'myajax', array( 'url' => admin_url('admin-ajax.php') ) );
}

// if( $show_map ) do_action( 'event_map_scripts' );
add_action( 'event_map_scripts', 'event_map_scripts_func', 10, 0);
function event_map_scripts_func() {
	// wp_enqueue_script( 'ymap-api?apikey=6ebdbbc2-3779-4216-9d88-129e006559bd&lang=ru_RU', '//api-maps.yandex.ru/2.1/', array(), false, 'in_footer' );
	wp_enqueue_script( 'acf_map-js' );
	wp_enqueue_script( 'acf_map-legacy' );
	// add_script('//api-maps.yandex.ru/2.1/?lang=ru_RU');
	// add_script( get_template_directory_uri().'/assets/js/acf_map.js' );
}

// do_action( 'add_social_scripts' );
// add_action( 'add_social_scripts', 'add_social_scripts_func', 10, 2);
function add_social_scripts() {
	wp_enqueue_script( 'widgets-js' );
	wp_enqueue_script( 'widgets-legacy' );
	// add_script('widgets');
	// if($vk) add_script( '//vk.com/js/api/openapi.js?160', 'async' );
	// $('<script async defer src="https://connect.facebook.net/ru_RU/sdk.js#xfbml=1&version=v3.2&appId=330469164341166&autoLogAppEvents=1"></script>').insertAfter('#fb-root');
	// if($fb) add_script( '//connect.facebook.net/ru_RU/sdk.js#xfbml=1&version=v3.2&appId=330469164341166&autoLogAppEvents=1', 'async' );
}

// do_action( 'add_social_scripts' );
// add_action( 'wp_footer', 'preload_social_scripts' );
// function preload_social_scripts( $vk=true, $fb=true ){ 
// 	// <link rel="preload" href="AO.js" as="script">
// 	$onload = 'if(screen.width > 768){ var script = document.createElement(\'script\'); script.src = this.href; document.body.appendChild(script); }';
// 	if( $vk ){
// 		$href = '//vk.com/js/api/openapi.js?160';
// 		echo '<link rel="preload" href="'.$href.'" as="script" onload="'.$onload.'" />'.PHP_EOL;
// 	}
// 	if( $fb ){
// 		$href = '//connect.facebook.net/ru_RU/sdk.js#xfbml=1&version=v3.2&appId=330469164341166&autoLogAppEvents=1';
// 		echo '<link rel="preload" href="'.$href.'" as="script" onload="'.$onload.'" />'.PHP_EOL;
// 	}

// 	wp_enqueue_script( 'cssrelpreload-js' );
// }

// do_action( 'add_share_scripts' );
add_action( 'add_share_scripts', 'add_share_scripts_func', 10, 0);
function add_share_scripts_func() {
	// add_script( '//yastatic.net/share2/share.js', false, 'async' );
	wp_enqueue_script('yashare-js');
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
	add_action( 'wp_footer', 'wpcf7_do_enqueue_scripts', 20 );
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

/* Custom post types: events, guidebook... */
add_action( 'init', 'register_post_types' );

function register_post_types(){
	// post_type => events
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

	// post_type => guidebook
	register_post_type('guidebook', array(
		'label'  => null,
		'labels' => array(
			'name'               => 'Путеводитель', // основное название для типа записи
			'singular_name'      => 'Статья ПВ', // название для одной записи этого типа
			'add_new'            => 'Добавить статью в ПВ', // для добавления новой записи
			'add_new_item'       => 'Добавление статьи в ПВ', // заголовка у вновь создаваемой записи в админ-панели.
			'edit_item'          => 'Редактировать статью ПВ', // для редактирования типа записи
			'new_item'           => 'Новая статья ПВ', // текст новой записи
			'view_item'          => 'Смотреть статью ПВ', // для просмотра записи этого типа.
			'search_items'       => 'Искать статьи в ПВ', // для поиска по этим типам записи
			'not_found'          => 'Не найдено', // если в результате поиска ничего не было найдено
			'not_found_in_trash' => 'Не найдено в корзине', // если не было найдено в корзине
			'parent_item_colon'  => '', // для родителей (у древовидных типов)
			'menu_name'          => 'Путеводитель', // название меню
		),
		'description'         => 'Путеводитель по Орлу от краеведов.',
		'public'              => true,
		'publicly_queryable'  => true, // зависит от public
		'exclude_from_search' => false, // зависит от public
		'show_ui'             => true, // зависит от public
		'show_in_menu'        => true, // показывать ли в меню адмнки
		'show_in_admin_bar'   => true, // по умолчанию значение show_in_menu
		'show_in_nav_menus'   => true, // зависит от public
		'show_in_rest'        => true, // добавить в REST API. C WP 4.7
		'rest_base'           => null, // $post_type. C WP 4.7
		'menu_position'       => 5,
		'menu_icon'           => 'dashicons-book-alt', 
		//'capability_type'   => 'post',
		//'capabilities'      => 'post', // массив дополнительных прав для этого типа записи
		//'map_meta_cap'      => null, // Ставим true чтобы включить дефолтный обработчик специальных прав
		'hierarchical'        => false,
		'supports'            => array('title','author','thumbnail'), // 'title','editor','author','thumbnail','excerpt','trackbacks','custom-fields','comments','revisions','page-attributes','post-formats'
		'taxonomies'          => array('category', 'sections'),
		'has_archive'         => false,
		'rewrite'             => true,
		'query_var'           => true,
	) );

	// taxonomies => sections 
	// Достопримечательности - sights, Люди - persons 
	register_taxonomy('sections', array('guidebook'), array(
		'label'                 => '', // определяется параметром $labels->name 
		'labels'                => array(
			'name'              => 'Разделы',
			'singular_name'     => 'Раздел',
			'search_items'      => 'Поиск разделов',
			'all_items'         => 'Все разделы',
			'view_item '        => 'Смотреть раздел',
			'parent_item'       => 'Родительский раздел',
			'parent_item_colon' => 'Родительский раздел:',
			'edit_item'         => 'Редактировать раздел',
			'update_item'       => 'Обновить раздел',
			'add_new_item'      => 'Добавить раздел',
			'new_item_name'     => 'Новое название раздела',
			'menu_name'         => 'Разделы',
		),
		'description'           => 'Разделы Путеводителя', // описание таксономии 
		'public'                => true,
		'publicly_queryable'    => null, // равен аргументу public
		'show_in_nav_menus'     => true, // равен аргументу public
		'show_ui'               => true, // равен аргументу public
		'show_in_menu'          => true, // равен аргументу show_ui
		'show_tagcloud'         => true, // равен аргументу show_ui
		'show_in_rest'          => null, // добавить в REST API
		'rest_base'             => null, // $taxonomy
		'hierarchical'          => true, // true - таксономия будет древовидная (как категории). false - будет не древовидная (как метки)
		//'update_count_callback' => '_update_post_term_count',
		'rewrite'               => array( 'slug' => 'guidebook_' ),
		//'query_var'             => $taxonomy, // название параметра запроса
		'capabilities'          => array(),
		'meta_box_cb'           => null, // callback функция. Отвечает за html код метабокса (с версии 3.8): post_categories_meta_box или post_tags_meta_box. Если указать false, то метабокс будет отключен вообще
		'show_admin_column'     => false, // Позволить или нет авто-создание колонки таксономии в таблице ассоциированного типа записи. (с версии 3.5)
		'_builtin'              => false,
		'show_in_quick_edit'    => null, // по умолчанию значение show_ui
	) );
}

// Меняем порядок вывода записей для архива типа записи 'events'
add_action('pre_get_posts', 'events_orderby_meta', 1 );
function events_orderby_meta( $query ) {
	// Выходим, если это админ-панель или не основной запрос
	if( is_admin() || ! $query->is_main_query() )
		return;

	if( $query->is_post_type_archive('events') ){
		// $query->set( 'posts_per_page', 5 ); // default=10
		$query->set( 'orderby', 	'meta_value' );
		$query->set( 'meta_key', 	'event_info_event_date' );
		$query->set( 'post__not_in', [312] ); // exclude="312"
	}
}

// Меняем порядок вывода записей для архивов таксономии 'sections'
add_action('pre_get_posts', 'sections_orderby_meta', 1 );
function sections_orderby_meta( $query ) {
	// Выходим, если это админ-панель или не основной запрос
	if( is_admin() || ! $query->is_main_query() )
		return;

	if( $query->is_tax('sections') ){
		$query->set( 'posts_per_page', 12 ); // default=10 
		// $query->set( 'orderby',  'meta_value_num' ); 
		$query->set( 'orderby',  array( 'meta_value_num' => 'DESC', 'title' => 'ASC' ) ); 
		$query->set( 'meta_key', 'gba_rating' ); // 1...10
		// $query->set( 'order', 	 'DESC' ); 		// от большего к меньшему, 10 -> 1 
	}
}

// Включаем поиск для записей типа 'events'
add_action('pre_get_posts', 'get_posts_search_filter');
function get_posts_search_filter( $query ){
	if ( ! is_admin() && $query->is_main_query() && $query->is_search ) {
		$query->set('post_type', array('post', 'events') );
	}
}


// [annocards post_type="post" cat_name="blog" tag_name="promo" section_title="Приходите" read_more="Подробнее..." date="future" exclude="312" size="medium"] 
add_shortcode( 'annocards', 'annocards_func' );
function annocards_func( $atts ){
	// белый список параметров и значения по умолчанию
	$atts = shortcode_atts( array(
		'post_type' 	=> 'post',
		'cat_name' 		=> '',
		'tag_name' 		=> '',
		'section_title' => null,
		'read_more' 	=> null,
		'date' 			=> '',
		'exclude' 		=> '312',
		'size' 			=> 'medium_large',
	), $atts );

	$post_type 		= $atts['post_type'];
	$cat_name 		= $atts['cat_name'];
	$tag_name 		= $atts['tag_name'];
	$section_title 	= $atts['section_title'];
	$read_more 		= $atts['read_more'];
	$date 			= $atts['date'];
	$exclude 		= $atts['exclude'];
	$size 			= $atts['size'];

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
		// $post_counter = 0;
		$echo .= '<section><div class="row section-container"><div class="col">';
		if( $section_title ) $echo .= '<h2>' . $section_title . '</h2>';
		foreach( $myposts as $post ):
			setup_postdata( $post );
			$permalink = get_the_permalink(); 
			$title = esc_html( get_the_title() );
			if( $post->post_type == 'events') {
				$event_date = markup_event_date( $post->id );
				if( $future_events ) 
					$event_date = '<span class="attention">'.$event_date.'</span>';
			}

			// $post_thumbnail = ($post_counter++ == 1) ? get_the_post_thumbnail(null, 'medium') : get_attachment_picture( get_post_thumbnail_id(), 'medium' );
			$post_thumbnail = get_attachment_picture( get_post_thumbnail_id(), $size );
			
			$echo .= '<div class="row anno-card"><div class="col-12 col-md-4"><a href="' . $permalink . '" title="Ссылка на: ' . $title . '" tabindex="-1">'; 
			$echo .= $post_thumbnail;
			$echo .= '</a></div><div class="col-12 col-md-8"><h3 class="annocard-title"><a href="' . $permalink . '" title="Ссылка на: '; 
			$echo .= $title . '">' . $title . '</a></h3>';
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

// [newscards section_id=announcement section_title=Актуальное section_link='' future_events=1 promo_posts=2 promo_events=3 promo_gba=4 read_more=Подробнее... exclude=312 size=medium] 
add_shortcode( 'newscards', 'newscards_func' );
function newscards_func( $atts ){
	// белый список параметров и значения по умолчанию
	$atts = shortcode_atts( array(
		'post_type'		=> array('post','events'),
		'section_id' 	=> null,
		'section_title' => null,
		'section_link' 	=> null,
		'cat_name' 		=> 'promo',
		// 'date' 		=> 'future',
		'future_events' => null,
		'promo_posts' 	=> null,
		'promo_events' 	=> null,
		'promo_gba' 	=> null,
		'read_more' 	=> '[Перейти&nbsp;>>]',
		'exclude' 		=> array(),
		'size' 			=> 'medium_large',
	), $atts );

	$numberposts 	= 9;
	$post_type 		= $atts['post_type'];
	$section_id 	= $atts['section_id'];
	$section_title 	= $atts['section_title'];
	$cat_name 		= $atts['cat_name'];
	$date 			= $atts['date'];
	$future_events 	= $atts['future_events'];
	$promo_posts 	= $atts['promo_posts'];
	$promo_events	= $atts['promo_events'];
	$promo_gba		= $atts['promo_gba'];
	$read_more 		= $atts['read_more'];
	$exclude 		= $atts['exclude'];
	$size 			= $atts['size'];
	$section_link 	= $atts['section_link'];

	// $args = array( 'numberposts' => $numberposts, 'post_type' => $post_type, 'category_name' => $cat_name );
	$today = date('Ymd');
	$myposts = array();

	if( $future_events ){
		$args = array( 'post_type' => 'events', 'exclude' => $exclude, 'meta_query' => array(
			array('key' => 'event_info_event_date', 'compare' => '>=', 'value' => $today)
			) );
		$myposts = array_merge($myposts, get_posts($args));
	}
	if( $promo_posts ){
		$args = array( 'post_type' => 'post', 'exclude' => $exclude, 'category_name' => $cat_name );
		$myposts = array_merge($myposts, get_posts($args));
	}
	if( $promo_events ){
		$args = array( 'post_type' => 'events', 'exclude' => $exclude, 'category_name' => $cat_name );
		$myposts = array_merge($myposts, get_posts($args));
	}
	if( $promo_gba ){
		$args = array( 'post_type' => 'guidebook', 'exclude' => $exclude, 'category_name' => $cat_name,
			'orderby' => array('meta_value_num' => 'DESC', 'title' => 'ASC'), 'meta_key' => 'gba_rating' );
		$myposts = array_merge($myposts, get_posts($args));
	}

	// $meta_query = array( 
	// 	array(
	// 		'key' 		=> 'event_info_event_date', 
	// 		'compare' 	=> '>=', 
	// 		'value' 	=> date('Ymd'),
	// 		) 
	// );
	// $args = array( 
	// 	'numberposts' 	=> $numberposts, 
	// 	'post_type' 	=> $post_type, 
	// 	'category_name' => $cat_name,
	// 	'meta_query'	=> $meta_query,
	// 	'exclude' 		=> $exclude,
	// 	'orderby' 		=> array( 'meta_value_num' => 'DESC', 'title' => 'ASC' ),
	// 	'meta_key' 		=> 'gba_rating',
	// 	// 'order'     	=> 'DESC',
	// );
	// $myposts = get_posts( $args );
	// print_r($args);
	// $myposts = array_merge($myposts1, $myposts2, $myposts3);
	// $attachments = new WP_Query;
	// $myposts = $attachments->query( $args );

	global $post;
	$echo = '';

	if( $myposts ): 
		if( $section_id ) $section_id = ' id="'.$section_id.'"';
		// $echo .= '<section'.$section_id.'><div class="row section-container"><div class="col">';
		$echo .= '<section'.$section_id.'><div class="section-container">';
		if($section_title){
			if($section_link) $section_title = '<a href="'.$section_link.'">'.$section_title.'</a>';
			$echo .= '<h2>'.$section_title.'</h2>';
		}

		$echo .= '<div class="row">';
		foreach( $myposts as $post ):
			setup_postdata( $post );
			$permalink 		= get_the_permalink(); 
			$title 			= esc_html( get_the_title() );
			$show_attention = get_field('event_info_event_date', false, false) >= $today;
			$newscard_title = esc_html( get_field('newscard-title') );
			if( empty($newscard_title) ){
				$event_date = ( $post->post_type == 'events') ? markup_event_date( $post->id ) : '';
				$newscard_title = $event_date.$title;
			}
			// $post_thumbnail = get_the_post_thumbnail(null, 'medium');
			$post_thumbnail = get_attachment_picture( get_post_thumbnail_id(), $size );

			$echo .= '<div class="newscard-container col-md-6 col-lg-4"><div class="newscard"><a href="'.$permalink.'" title="Ссылка на: '.$title.'">'; 
			$echo .= $post_thumbnail;
			$echo .= '</a>';
			if( $show_attention ) $echo .= '<p class="attention">Не пропустите!</p>';
			$echo .= '<h3 class="newscard-title">'.$newscard_title.'</h3>';
			// $echo .= get_the_excerpt();
			if( $read_more ) $echo .= ' <a href="'.$permalink.'" title="Ссылка на: '.$title.'" tabindex="-1">'.$read_more.'</a>';
			$echo .= '</div></div>';
		endforeach;
		wp_reset_postdata();

		$echo .= '</div><!-- .row -->';
		$echo .= '</div></section>';
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
		'vk_mode' 		=> '3',
		'vk_width' 		=> '300',
		'vk_height' 	=> '400',
		'vk_id' 		=> null,
		'no_cover' 		=> null,
		'fb' 			=> false,
	), $atts );

	$section_title 	= $atts['section_title'];
	$vk_mode 		= $atts['vk_mode'];
	$vk_width 		= $atts['vk_width'];
	$vk_height 		= $atts['vk_height'];
	$vk_id 			= $atts['vk_id'];
	$no_cover 		= $atts['no_cover'];
	$fb 			= $atts['fb'];

	$echo = '<section id="soc-section"><div class="row section-container"><div class="col">';
	if( $section_title ){
		$echo .= '<h2>'.$section_title.'</h2>';
	}
	$echo .= '<div class="row">';
	if( $vk_id ){
		$echo .= '<div class="col-md-6"><!-- VK Widget --><div class="socwidget" id="vk_groups" data-id="'.$vk_id.'"';
		if( $vk_mode ) 	$echo .= ' data-mode="'.$vk_mode.'"';
		if( $vk_width ) $echo .= ' data-width="'.$vk_width.'"';
		if( $vk_height) $echo .= ' data-height="'.$vk_height.'"';
		if( $no_cover ) $echo .= ' data-no_cover="'.$no_cover.'"';
		$echo .= '></div></div>';
	}
	if( $fb ){
		$echo .= '<div class="col-md-6"><div id="fb-root"></div>';
		$echo .= '<div class="socwidget fb-group" data-href="https://www.facebook.com/groups/excursorel" data-width="300" data-show-social-context="true" data-show-metadata="false"></div></div>';
	}
	$echo .= '</div>';
	// $echo .= '<noscript><p><a href="https://vk.com/excurs_orel" target="_blank">ВКонтакте</a></p><p><a href="https://www.facebook.com/groups/excursorel" target="_blank">Фейсбук</a></p></noscript>';
	$echo .= '</div></div></section>';

	add_social_scripts();

	return $echo;
}

// function carousel_func( $atts ){
// 	// белый список параметров и значения по умолчанию
// 	$atts = shortcode_atts( array(
// 		'class' => 'carousel',
// 		'hrefs' => false,
// 		'size' 	=> 'medium_large',
// 	), $atts );

// 	$class 		= $atts['class'];
// 	$class_item = $class.'-item';
// 	$hrefs 		= $atts['hrefs'];
// 	$size 		= $atts['size'];

// 	$echo = '';

// 	global $post;
// 	//Get the images ids from the post_metadata
// 	$images = acf_photo_gallery( 'carousel_gal', $post->ID );
// 	if( count($images) ):
// 		$images_counter = 0;
// 		$echo .= '<div class="'.$class.'">';
// 		foreach( $images as $image ):
// 			$id = $image['id']; // The attachment id of the media
// 			$title = $image['title']; //The title
// 			// $description = $image['caption']; //The caption (Description!)
// 			// $full_image_url= $image['full_image_url']; //Full size image url
// 			$post_id = wp_get_post_parent_id( $id ); // get_post($id)->post_parent 
// 			$post_link = get_permalink( $post_id ); 
// 			$attr = $hrefs ? null : array( 'title' => $title);

// 			$echo .= '<div class="'.$class_item.'">';
// 			if( $hrefs && $post_link )
// 				$echo .= '<a href="'.$post_link.'" title="'.get_the_title( $post_id ).'">';

// 			// Get picture item. 1st ($images_counter == 0) is not lazy 
// 			// if( $images_counter == 0 ) $echo .= wp_get_attachment_image( $id, 'medium_large', false, $attr );
// 			// else $echo .= get_lazy_attachment_image( $id, 'medium_large', false, $attr );
// 			$echo .= get_attachment_picture( $id, $size, false, $attr, $images_counter > 0 );
// 			$images_counter++;

// 			if( $hrefs && $post_link ) $echo .= '</a>';
// 			$echo .= '</div>';

// 		endforeach;
// 		$echo .= '</div> <!-- .'.$class.' -->';

// 		add_carousel_scripts();

// 	endif;

// 	return $echo;
// }

// [carousel class="carousel" hrefs=1 size="medium_large" container="col-md-10 col-lg-6 carousel-container"]
add_shortcode( 'carousel', 'carousel_func' );

function carousel_func( $atts ){
	// белый список параметров и значения по умолчанию
	$atts = shortcode_atts( array(
		'class' 	=> 'carousel',
		'hrefs' 	=> false,
		'size' 		=> 'medium_large',
		'container' => 'carousel-container',
	), $atts );

	$class 		= $atts['class'];
	$class_item = $class.'-item';
	$hrefs 		= $atts['hrefs'];
	$size 		= $atts['size'];
	$container 	= $atts['container'];

	$echo = '';

	global $post;
	//Get the images ids from the post_metadata
	$images = acf_photo_gallery( 'carousel_gal', $post->ID );
	if( count($images) ):
		$images_counter = 0;
		if( $container ){
			$echo .= '<div class="'.$container.'">';
		}
		$echo .= '<div class="glide '.$class.'">';
		$echo .= '<div class="glide__track" data-glide-el="track"><ul class="glide__slides">';
		foreach( $images as $image ){
			$id = $image['id']; // The attachment id of the media
			$title = $image['title']; //The title
			// $description = $image['caption']; //The caption (Description!)
			// $full_image_url= $image['full_image_url']; //Full size image url
			$post_id = wp_get_post_parent_id( $id ); // get_post($id)->post_parent 
			$post_link = get_permalink( $post_id ); 
			$attr = $hrefs ? null : array( 'title' => $title);

			$echo .= '<li class="glide__slide">';
			if( $hrefs && $post_link ){
				$echo .= '<a href="'.$post_link.'" title="'.get_the_title( $post_id ).'">';
			}
			// Get picture item. 1st ($images_counter == 0) is not lazy 
			// if( $images_counter == 0 ) $echo .= wp_get_attachment_image( $id, 'medium_large', false, $attr );
			// else $echo .= get_lazy_attachment_image( $id, 'medium_large', false, $attr );
			$echo .= get_attachment_picture( $id, $size, false, $attr, $images_counter > 0 );
			$images_counter++;

			if( $hrefs && $post_link ) $echo .= '</a>';
			$echo .= '</li>';

		}
		$echo .= '</ul></div>';
		// controls
		$echo .= '<div class="glide__arrows" data-glide-el="controls">
			<button class="glide__arrow glide__arrow--left" data-glide-dir="<">&lt;</button>
			<button class="glide__arrow glide__arrow--right" data-glide-dir=">">&gt;</button></div>';
		// bullets
		$echo .= '<div class="glide__bullets" data-glide-el="controls[nav]">';
		foreach( $images as $counter => $image ){
			$echo .= '<button class="glide__bullet" data-glide-dir="='.$counter.'"></button>';
		}
		$echo .= '</div>';

		$echo .= '</div> <!-- .glide .'.$class.' -->';
		if( $container ){
			$echo .= '</div> <!-- .'.$container.' -->';
		}

		add_carousel_scripts();

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
function get_attachment_picture( $attachment_id, $size='thumbnail', $icon=false, $attr='', $lazy=true, $placeholder=false ) {
	$html = '';
	global $PLACEHOLDER_URL;
	// $image 		= wp_get_attachment_image_src( $attachment_id, $size, $icon );
	// anti image_constrain_size_for_editor() to $content_width 
	$is_image = wp_attachment_is_image( $attachment_id );
	if( $is_image ){
		$img_url			= wp_get_attachment_url( $attachment_id );
		$image_meta 		= wp_get_attachment_metadata( $attachment_id );
		$width  			= $image_meta['width'];
		$height 			= $image_meta['height'];
		$img_url_basename 	= wp_basename( $img_url );
		// try for a new style intermediate size
		if( $intermediate = image_get_intermediate_size( $attachment_id, $size ) ){
			$img_url        = str_replace( $img_url_basename, $intermediate['file'], $img_url );
			$width          = $intermediate['width'];
			$height         = $intermediate['height'];
		}
		// list($src, $width, $height) = $image;
		$src				= $img_url;
		$hwstring           = image_hwstring( $width, $height );
		$size_class         = $size;
		if( is_array( $size_class ) ){
			$size_class = join( 'x', $size_class );
		}
		$attachment   = get_post( $attachment_id );
		$default_attr = array(
			'src' 		=> $src,
			'class' 	=> "attachment-$size_class size-$size_class",
			'alt' 		=> trim( strip_tags( get_post_meta( $attachment_id, '_wp_attachment_image_alt', true ) ) ),
		);

		if( $lazy ){
			$def_attr = wp_parse_args( $attr, $default_attr );

			$default_attr = array_merge( $default_attr, array(
				'src'		=> $PLACEHOLDER_URL, // for validation 
				'data-src' 	=> $src,
			));
		}

		$attr = wp_parse_args( $attr, $default_attr );

		// Generate <source>'s
		// Retrieve the uploads sub-directory from the full size image.
		$dirname = _wp_get_attachment_relative_path( $image_meta['file'] );
	
		if( $dirname ){
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
		$size_array = array( absint( $width ), absint( $height ) );
		$sizes 		= wp_calculate_image_sizes( $size_array, $src, $image_meta, $attachment_id );
		if($sizes) $sizes_html = $lazy ? 'data-sizes="'.$sizes.'"' : 'sizes="'.$sizes.'"';

		$html = '<picture>';

		global $WEBP_ON;
		if( $WEBP_ON ){
			$srcset = generate_image_srcset( $image_meta, $image_baseurl, true );
			if( $srcset ){
				$srcset_html = $lazy ? 'data-srcset="'.$srcset.'"' : 'srcset="'.$srcset.'"';
				$html .= '<source type="image/webp" '.$srcset_html.' '.$sizes_html.'>';
			}
		}

		$srcset = generate_image_srcset( $image_meta, $image_baseurl );
		if( $srcset ){
			$srcset_html = $lazy ? 'data-srcset="'.$srcset.'"' : 'srcset="'.$srcset.'"';
			$html .= '<source '.$srcset_html.' '.$sizes_html.'>';
		}

		// $attr = apply_filters( 'wp_get_attachment_image_attributes', $attr, $attachment, $size );
		$attr = array_map( 'esc_attr', $attr );
		$html .= rtrim( "<img $hwstring" );
		foreach ( $attr as $name => $value ) {
			$html .= " $name=" . '"' . $value . '"';
		}
		$html .= ' /></picture>';

		// <noscript> for JS-off clients 
		if( $lazy ){
			$def_attr = array_map( 'esc_attr', $def_attr );
			$html .= rtrim( "<noscript><img $hwstring" );
			foreach ( $def_attr as $name => $value ) {
				$html .= " $name=" . '"' . $value . '"';
			}
			$html .= ' /></noscript>';
		}
	}
	elseif($placeholder){
		$html = '<img src="'.$PLACEHOLDER_URL.'" alt="Картинки нет" />';
	}

	return $html;
}

function generate_image_srcset( $image_meta, $image_baseurl, $webp = false ){
	$srcset = '';
	$image_sizes = $image_meta['sizes'];

	if( !is_array( $image_sizes ) ){
		return $srcset;
	}

	$sfx = $webp ? '.webp' : '';
	$image_basename = wp_basename( $image_meta['file'] );

	$strings = array();
	foreach( $image_sizes as $image ){
		// Check if image meta isn't corrupted.
		if( ! is_array( $image ) ){
			continue;
		}

		if( isset($image['file']) ){
			$file_width = $image['width'];

			if( $file_width < 300 ) continue;

			$image_url 	= $image_baseurl . $image['file'];
			$string 	= $image_url.$sfx.' '.$file_width.'w';
			array_push($strings, $string);
		}  
	}

	$image_url 	= $image_baseurl . $image_basename; // full_image_url
	$file_width = (int) $image_meta['width']; // full_image_width
	$string 	= $image_url.$sfx.' '.$file_width.'w';
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
		'id' 	=> null,
		'size' 	=> 'thumbnail',
		'title' => true,
		'href' 	=> false,
		'lazy' 	=>true,
	), $atts );

	$class 	= $atts['class'];
	$id 	= $atts['id'];
	$size 	= $atts['size'];
	$title 	= $atts['title'];
	$href 	= $atts['href'];
	$lazy 	= $atts['lazy'];

	$echo = '';

	if( !$id ) $id = get_post_thumbnail_id();

	if( $id ){
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
	}

	return $echo;
}

function markup_post_permalink($post_id, $text, $permalink=true){
	$post_id = (int) $post_id;

	if($permalink && $post_id > 0){
		$post_link = get_permalink($post_id);
		if( $post_link ){
			$text = '<a href="'.$post_link.'" title="'.get_the_title( $post_id ).'">'.$text.'</a>';
		}
	}

	return $text;
}

/**
 * Parse wiki-style refs in image description field 
 */
function image_description_parse($str, $permalink=true){
	$text = $str;
	$matches = [];
	$count = preg_match_all('/\\[post=(\\d+)\\|([^\\]]*)\\]/u', $str, $matches);
	for($i = 0; $i < $count; $i++){
		$text = str_replace($matches[0][$i], markup_post_permalink($matches[1][$i], $matches[2][$i], $permalink), $text);
	}

	return $text;
}

function markup_fancy_figure($id, $fancybox, $full_image_url, $fancy_caption, $size='thumbnail', $lazy=false, $title=null, $img_class=null, $figcaption_html=null){
	$fancy_hint = '(Нажмите, чтобы увеличить)';
	$title_attr = $title;
	if($fancybox){
		$title_attr .= ' '.$fancy_hint;
	}
	if($fancy_caption){
		$fancy_caption = ' data-caption="'.$fancy_caption.'"';
	}
	$attr = [];
	if($title_attr){
		$attr['title'] = $title_attr;
	}
	if($img_class){
		$attr['class'] = $img_class;
	}

	$echo = '<figure><a data-fancybox="'.$fancybox.'" href="'.$full_image_url.'"'.$fancy_caption.'>';
	// $echo .= get_lazy_attachment_image( $id, 'medium_large', false, $attr );
	$echo .= get_attachment_picture( $id, $size, false, $attr, $lazy );
	$echo .= '</a>';

	if( $figcaption_html ){
		$echo .= '<figcaption>'.$figcaption_html.'</figcaption>';
	}
	$echo .= '</figure>';

	return $echo;
}

// [gallery acf_field=gallery_gal class=post-gallery item=gallery-item fancybox=gallery lazy=1 size=large nums=null(1,2,4) figcaption=image_description(parent_href) mini=false permalink=true] 
add_shortcode( 'gallery', 'gallery_func' );

function gallery_func( $atts ){
	// белый список параметров и значения по умолчанию
	$atts = shortcode_atts( array(
		'acf_field' 	=> 'gallery_gal',
		'class' 		=> null,
		'item' 			=> 'gallery-item',
		'fancybox' 		=> 'gallery',
		'lazy' 			=> true,
		'size' 			=> 'large',
		'nums' 			=> null,
		'figcaption' 	=> 'image_description',
		'mini' 			=> false,
		'permalink' 	=> true,
	), $atts );

	$acf_field 		= $atts['acf_field'];
	$class 			= $atts['class'];
	$item 			= $atts['item'];
	$fancybox 		= $atts['fancybox'];
	$lazy 			= $atts['lazy'];
	$size 			= $atts['size'];
	$nums 			= $atts['nums'];
	$figcaption 	= $atts['figcaption'];
	$mini 			= $atts['mini'];
	$permalink 		= $atts['permalink'];

	$echo = '';

	global $post;
	//Get the images ids from the post_metadata
	$images = acf_photo_gallery( $acf_field, $post->ID );
	if( count($images) ):
		if( !$class ){
			$class = $mini ? 'pre-gallery' : 'post-gallery';
		}
		$bootstrap = $mini ? ' col-12 col-sm-6 col-md-6 col-lg-4' : ' col-12';
		
		$images_counter = 0;
		if($nums){
			$nums_arr = explode(',', $nums);
			// print_r($nums_arr);
		}

		$echo .= '<div class="row '.$class.'">'; //  nums-'.$nums.'
		foreach( $images as $image ):
			// if( $number>0 && $number != ++$images_counter ) continue;
			if( $nums && !in_array( ++$images_counter, $nums_arr ) ) continue;

			$id 			= $image['id']; // The attachment id of the media
			$title 			= $image['title']; //The title
			$full_image_url = $image['full_image_url']; //Full size image url

			$figcaption_html = '';
			if( $figcaption == 'parent_href' ){
				$post_parent_id = wp_get_post_parent_id( $id );
				// Если картинка имеет родительский пост отличный от текущего, выводим на него ссылку в подписи 
				if( $post_parent_id != $post->ID && $post_parent_link = get_permalink( $post_parent_id ) ){
					$figcaption_html = '<a href="'.$post_parent_link.'">'.get_the_title( $post_parent_id ).'</a>';
				}
			}
			// Иначе берем подпись из БД и парсим ее
			if(!$figcaption_html && ($figcaption == 'image_description' || $figcaption == 'parent_href')){
				$figcaption_html = image_description_parse( $image['caption'], $permalink ); //The caption (Description!)
			}

			$echo .= '<div class="'.$item.$bootstrap.'">';
			// Get picture item in markup_figure() func. 1st two ($images_counter == 0 || 1) are not lazy 
			$echo .= markup_fancy_figure($id, $fancybox, $full_image_url, $title, $size, $images_counter > 2, $title, null, $figcaption_html);
			$echo .= '</div>';

		endforeach;
		$echo .= '</div> <!-- row '.$class.' -->';

		do_action( 'add_gallery_scripts' );

	endif;

	return $echo;
}

// Настраиваем страницам пагинации rel="canonical" на 1-ую стр. архива (для Yoast SEO) 
function return_canon() {
	$canon_page = is_page('guidebook') ? get_url_wo_pagenum() : get_pagenum_link();
	
	return $canon_page;
}
function canon_paged() {
	if (is_paged()) {
		add_filter( 'wpseo_canonical', 'return_canon' );
	}
}
add_filter('wpseo_head','canon_paged');

/**
 * Оптимизирует исходный файл и генерирует webp копии изображений сразу после загрузки изображения в медиабиблиотеку
 * - новые файлы сохраняет с именем name.ext.webp, например, thumb.jpg.webp 
 * Использует функции https://www.php.net/manual/ru/book.image.php 
 * 
 */
function gd_image_optimization_and_webp_generation($metadata) {
	$uploads = wp_upload_dir(); // получает папку для загрузки медиафайлов
	$quality = 80; // imagewebp() default is 80, imagejpeg() ~75 

    $file = $uploads['basedir'] . '/' . $metadata['file']; // получает исходный файл
    $ext = wp_check_filetype($file); // получает расширение файла
	
	if( !($ext['type'] == 'image/jpeg' || $ext['type'] == 'image/png') )
		return $metadata; // работаем только с jpeg и png 

    if ( $ext['type'] == 'image/jpeg' ) { // в зависимости от расширения обрабатаывает файлы разными функциями
		$image = imagecreatefromjpeg($file); // создает изображение из jpg
		imagejpeg($image, $uploads['basedir'] . '/' . $metadata['file'], $quality); // оптимизирует исходное изображение
        
    } elseif ( $ext['type'] == 'image/png' ){
        $image = imagecreatefrompng($file); // создает изображение из png
        imagepalettetotruecolor($image); // восстанавливает цвета
        imagealphablending($image, false); // выключает режим сопряжения цветов
		imagesavealpha($image, true); // сохраняет прозрачность
		imagepng($image, $uploads['basedir'] . '/' . $metadata['file']); // оптимизирует исходное изображение, 0...9, #define Z_DEFLATED 8 

    }
    imagewebp($image, $uploads['basedir'] . '/' . $metadata['file'] . '.webp', $quality); // сохраняет файл в webp

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
        
        imagewebp($image, $uploads['basedir'] . $uploads['subdir'] . '/' . $size['file'] . '.webp', $quality);

    }

    return $metadata;
}
add_filter('wp_generate_attachment_metadata', 'gd_image_optimization_and_webp_generation');

// подключаем AJAX обработчики, только когда в этом есть смысл
if( wp_doing_ajax() ){
	add_action('wp_ajax_get_events', 'get_events');
	add_action('wp_ajax_nopriv_get_events', 'get_events');
}
// function get_events_func() {
function get_events() {
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
		'numberposts' => -1, 
	);
	$myposts = get_posts( $args );

	if( $myposts ){
		// foreach( $myposts as $counter => $post ){
		foreach( $myposts as $post ){
			setup_postdata( $post );
			$post_id 	= get_the_ID();
			$permalink 	= get_the_permalink();
			$title 		= esc_html( get_field('event_info_event_date') . ' ' . get_the_title() );
			$location 	= get_field('event_info_event_place_map');

			// $events[$counter]['post_id'] 	= $post_id;
			// $events[$counter]['permalink'] 	= $permalink;
			// $events[$counter]['title'] 		= $title;
			// $events[$counter]['lat'] 		= $location['lat'];
			// $events[$counter]['lng'] 		= $location['lng'];
			
			$events[] = array(
				'post_id' 	=> $post_id, 
				'permalink' => $permalink,
				'title' 	=> $title,
				'lat' 		=> $location['lat'],
				'lng' 		=> $location['lng'],
			);
		}
		wp_reset_postdata();		
	}

	// $str = json_encode( print_r($events, true) );
	// echo "<script> console.log('$str'); </script>";
	// echo $events_num;
	// wp_send_json( $events );
	echo json_encode( $events );

	wp_die(); // выход нужен для того, чтобы в ответе не было ничего лишнего, только то что возвращает функция
}

// подключаем AJAX обработчики, только когда в этом есть смысл
if( wp_doing_ajax() ){
	add_action('wp_ajax_get_sights', 'get_sights');
	add_action('wp_ajax_nopriv_get_sights', 'get_sights');
}
function get_sights() {
	// echo json_encode($_SERVER['REQUEST_URI']);
	// wp_die();
	$sights 	= array();
	$myposts 	= get_guidebook_posts(null, -1); 

	if( $myposts ){
		global $post;
		// foreach( $myposts as $counter => $post ){
		foreach( $myposts as $post ){
			setup_postdata( $post );
			$post_id 	= get_the_ID();
			$permalink 	= get_the_permalink();
			$title 		= esc_html( get_the_title() );
			$location 	= get_field('obj_info_geolocation');
			// $thumb_url 	= wp_get_attachment_thumb_url( get_post_thumbnail_id() );
			$thumb_url 	= get_the_post_thumbnail_url( $post_id, 'thumbnail' );
			// if(!$thumb_url){
			// 	global $PLACEHOLDER_URL;
			// 	$thumb_url = $PLACEHOLDER_URL;
			// }
			
			$sights[] = array(
				'post_id' 	=> $post_id, 
				'permalink' => $permalink,
				'title' 	=> $title,
				'lat' 		=> $location['lat'],
				'lng' 		=> $location['lng'],
				'thumb_url' => $thumb_url,
			);
		}
		wp_reset_postdata();		
	}

	echo json_encode( $sights );

	wp_die(); // выход нужен для того, чтобы в ответе не было ничего лишнего, только то что возвращает функция
}

function get_guidebook_posts( $term_slug='', $numberposts=0 ) {
	$posts 			= array();
	$tax_name 		= 'sections';
	$term_slug_0 	= get_terms( array('taxonomy' => $tax_name) )[0]->slug; // 'sights'

	if(!$term_slug){
		$term_slug 	= $term_slug_0; // 'sights'
	}
	$check_filter 	= $term_slug == $term_slug_0;

	if($term_slug){
		$paged = 1;
		$meta_query = array();

		if($check_filter){
			// $url 			= $_SERVER['REQUEST_URI'];
			// $query_str = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
			// $query = array();
			// parse_str($query_str, $query);
			// unset($query['pagenum']);

			if(!$numberposts){
				if(isset($_GET['numberposts_1'])){
					$numberposts = 1;
				}
				if(isset($_GET['numberposts_2'])){
					$numberposts = 2;
				}				
			}
			if(isset($_GET['pagenum'])){
				$paged = $_GET['pagenum'];
			}
			if(isset($_GET['cat_f'])){
				$meta_query[] = array('key' => 'obj_info_protection_category', 'value' => 'f');
			}
		}

		// if($numberposts == -1){
		// 	$paged = 0;
		// }
		
		$args = array( 
			'post_type' 	=> 'guidebook', 
			$tax_name 		=> $term_slug,
			'orderby' 		=> array( 'meta_value_num' => 'DESC', 'title' => 'ASC' ),
			'meta_key' 		=> 'gba_rating',
			// 'order'     	=> 'DESC',
			// 'numberposts' 	=> $numberposts, 
			'numberposts' 	=> -1, 
			// 'paged'			=> $paged,
			'meta_query'	=> $meta_query,
		);

		$posts = get_posts( $args );
		// посты с category_name = promo поднимаем вверх 
		$promo_posts = [];
		foreach( $posts as $counter => $post ){
			if(in_category('promo', $post->ID)){
				$promo_posts[] = $post;
				unset( $posts[$counter] );
				// array_splice($posts, $counter, 1);
			}
		}

		$posts = array_merge( $promo_posts, $posts );

		if($numberposts > 0){
			$posts = array_slice($posts, $numberposts*($paged-1), $numberposts);
		}

	}
		
	return $posts;
}

function check_pagenum_in_uri(){
	if(is_page('guidebook') && isset($_GET['pagenum'])){
		$myposts = get_guidebook_posts();
		// print_r($myposts);
	
		if( !$myposts || $_GET['pagenum'] == 1){

			$url = get_url_wo_pagenum();
			// print_r($url);
			// header( $protocol.' 301 Moved Permanently' );
			// header( 'Location: '.strtolower($_SERVER['REQUEST_URI']) );
			header( 'Location: '.$url, true, 301 );
			exit();
		}
	}
}
add_action( 'template_redirect', 'check_pagenum_in_uri' );

function get_url_wo_pagenum(){
	$string 		= $_SERVER['REQUEST_URI'];
	$parts 			= parse_url($string);
	$queryParams 	= array();
	parse_str($parts['query'], $queryParams);
	unset($queryParams['pagenum']);
	$queryString 	= http_build_query($queryParams);
	if($queryString){
		$queryString = '?' . $queryString;
	}
	// $url 		= $_SERVER['HTTP_HOST'] . $parts['path'] . '?' . $queryString;
	$url 			= home_url() . $parts['path'] . $queryString;

	return $url;
}


/**
 * "ACF Photo Gallery Field" Plugin extension - add post_parent to attachment.
 * Fires off when the WordPress update button is clicked 
 */
function acf_photo_gallery_addPostParent( $post_id ){
	
	if ( ! wp_is_post_revision( $post_id ) ){
		// unhook this function so it doesn't loop infinitely
		// remove_action( 'save_post', 'acf_photo_gallery_save' );
		remove_action( 'save_post', 'acf_photo_gallery_addPostParent' );

		$field = isset($_POST['acf-photo-gallery-groups'])? $_POST['acf-photo-gallery-groups']: null;
		if( !empty($field) ){
			foreach($field as $k => $v ){
				$field_id = isset($_POST['acf-photo-gallery-groups'][$k])? $_POST['acf-photo-gallery-groups'][$k]: null;
				if(!empty($field_id)) {
					$ids = !empty($field) && isset($_POST[$field_id])? $_POST[$field_id]: null;
					if(!empty($ids)) {
						$post_type = get_post($post_id)->post_type;
						// $ids = implode(',', $ids);
						// update_post_meta($post_id, $field_id, $ids);
						foreach($ids as $attachment_id) {
							$attachment_parent = get_post($attachment_id)->post_parent;
							// Если у вложения нет post_parent, устанавливаем его равным id текущего поста 
							// Без проверки $post_type работает некорректно 
							if( ($post_type == 'events' || $post_type == 'post' || $post_type == 'guidebook') &&  $attachment_parent == 0 ){
								// Обновляем данные в БД
								wp_update_post( array( 'ID' => $attachment_id, 'post_parent' => $post_id ) );
							}
						}
					}
				}
			}
		}

		// re-hook this function
		// add_action( 'save_post', 'acf_photo_gallery_save' );
		add_action( 'save_post', 'acf_photo_gallery_addPostParent' );
	}
}
add_action( 'save_post', 'acf_photo_gallery_addPostParent' );


function console_log( $str ){
	global $consolelog;

	// $consolelog .= esc_html($str) . '\n';
	$consolelog .= $str . '\n';
}

function echo_console_log(){
	global $consolelog;

	if( $consolelog ){
		// echo '<script>console.log("echo_console_log");</script>'.PHP_EOL;
		echo '<script>console.log("'.$consolelog.'");</script>'.PHP_EOL;
	}
}
add_action( 'wp_footer', 'echo_console_log', 20 );
add_action( 'admin_footer', 'echo_console_log', 15 );

function print_r2($val){
	echo '<pre>';
	print_r($val);
	echo  '</pre>';
}
