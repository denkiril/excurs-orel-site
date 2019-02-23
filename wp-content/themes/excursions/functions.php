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
		// set_post_thumbnail_size( 150, 150 );

		// add_image_size( 'anno-thumb', 600, 400, true );

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
	wp_enqueue_style( 'events-css', get_template_directory_uri() . '/assets/css/events.css' );
	// wp_enqueue_style( 'main-top-css', get_template_directory_uri() . '/assets/css/main-top.css' );

	wp_deregister_script( 'jquery' );
	// wp_register_script( 'jquery', get_template_directory_uri() . '/assets/include/jquery-3.3.1.min.js' );
	wp_enqueue_script( 'jquery', get_template_directory_uri() . '/assets/include/jquery-3.3.1.min.js', array(), false, 'in_footer' );
	wp_enqueue_script( 'bootstrap-js', get_template_directory_uri() . '/assets/include/bootstrap.min.js', array('jquery'), false, 'in_footer' );
	wp_enqueue_script( 'slick-js', get_template_directory_uri() . '/assets/include/slick.min.js', array('jquery'), false, 'in_footer' );
	wp_enqueue_script( 'script', get_template_directory_uri() . '/assets/js/script.js', array('jquery'), false, 'in_footer' );
	// wp_register_script( 'googlemap-api?key=YOUR_API_KEY', '//maps.googleapis.com/maps/api/js', array(), false, 'in_footer' );
	// wp_register_script( 'ymap-api?apikey=6ebdbbc2-3779-4216-9d88-129e006559bd&lang=ru_RU', '//api-maps.yandex.ru/2.1/', array(), false, 'in_footer' );
	wp_register_script( 'acf-map-js', get_template_directory_uri() . '/assets/js/acf-map-yandex.js', array('jquery'), false, 'in_footer' );
	wp_enqueue_script( 'fancybox-js', '//cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.6/dist/jquery.fancybox.min.js', array('jquery'), false, 'in_footer' );
}
add_action( 'wp_enqueue_scripts', 'excursions_scripts' );

function styles_to_footer() {
	wp_enqueue_style( 'main-bottom-css', get_template_directory_uri() . '/assets/css/main-bottom.css' );
	wp_enqueue_style( 'slick-css', get_template_directory_uri() . '/assets/include/slick.css' );
    wp_enqueue_style( 'slick-theme', get_template_directory_uri() . '/assets/include/slick-theme.css' );
    wp_enqueue_style( 'main-font?family=Ubuntu:300,400&amp;subset=cyrillic', '//fonts.googleapis.com/css' );
	wp_enqueue_style( 'fancybox-css', '//cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.6/dist/jquery.fancybox.min.css' );
}
add_action( 'wp_footer', 'styles_to_footer' );

add_action( 'add_map_scripts', 'add_map_scripts_func', 10, 0);
function add_map_scripts_func() {
	wp_enqueue_script( 'ymap-api?apikey=6ebdbbc2-3779-4216-9d88-129e006559bd&lang=ru_RU', '//api-maps.yandex.ru/2.1/', array(), false, 'in_footer' );
	wp_enqueue_script('acf-map-js');
}

// function test_styleadd() {
// 	wp_enqueue_style( 'excursions-style', get_stylesheet_uri() );
// }
// add_action( 'wp_enqueue_scripts', 'test_styleadd' );
// -------------------------------------------------------------------
// remove_action( 'wp_enqueue_scripts', 'test_styleadd' );
// add_action( 'wp_footer', 'test_styleadd' );


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
			wp_reset_postdata();
		endforeach;
		echo '</div></div></section>';
	endif;
}

add_shortcode( 'annocards', 'annocards_func' );

// использование: [annocards post_type="post" cat_name="blog" tag_name="promo" section_title="Приходите" read_more="Подробнее..." date="future"] 

function annocards_func( $atts ){
	// белый список параметров и значения по умолчанию
	$atts = shortcode_atts( array(
		'post_type' => 'post',
		'cat_name' => '',
		'tag_name' => '',
		'section_title' => null,
		'read_more' => null,
		'date' => ''
	), $atts );

	$post_type = $atts['post_type'];
	$cat_name = $atts['cat_name'];
	$tag_name = $atts['tag_name'];
	$section_title = $atts['section_title'];
	$read_more = $atts['read_more'];
	$date = $atts['date'];
	$echo = '';

	global $post;
	$args = array( 'post_type' => $post_type, 'category_name' => $cat_name, 'tag' => $tag_name );

	if( $post_type == 'events'):
		$today = date('Ymd');
		if($date == 'past') $past_events = true;
		$compare = $past_events ? '<' : '>=';
		$args += array( 'meta_query' => array( array('key' => 'event_info_event_date', 'compare' => $compare, 'value' => $today) ) );
	endif;

	$myposts = get_posts( $args );
	if ( $myposts ): 
		$echo .= '<section><div class="row section-container"><div class="col">';
		if( $section_title ) $echo .= '<h2>' . $section_title . '</h2>';
		foreach( $myposts as $post ):
			setup_postdata( $post );
			if( $post_type == 'events')
				$event_date = get_field('event_info_event_date');
				// $event_date = get_field('event_info_event_date', false, false);
			$permalink = get_the_permalink(); 
			$title = esc_html( get_the_title() );

			$echo .= '<div class="row anno-card"><div class="col-12 col-md-4"><a href="' . $permalink . '" title="Ссылка на: ' . $title . '" tabindex="-1">'; 
			$echo .= get_the_post_thumbnail(null, 'medium');
			$echo .= '</a></div><div class="col-12 col-md-8"><h3><a href="' . $permalink . '" title="Ссылка на: '; 
			$echo .= $title . '">' . $title . '</a></h3><p>';
			if( $event_date ) $echo .= $event_date . ' ';
			$echo .= get_the_excerpt();
			if( $read_more ) $echo .= ' <a href="' . $permalink . '" tabindex="-1">' . $read_more . '</a>';
			$echo .= '</p></div></div>';
			wp_reset_postdata();
		endforeach;
		if( $past_events && !is_archive() )
			$echo .= '<p class="anno-ref"><a href="' . get_post_type_archive_link('events') . '" title="Ссылка на все события">Все события ></a></p>';

		$echo .= '</div></div></section>';
	endif;

	return $echo;
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
		'exclude_from_search' => true, // зависит от public
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
		'taxonomies'          => array(),
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

add_shortcode( 'socwidgets', 'socwidgets_func' );
// использование: [socwidgets section_title="Мы в соцсетях"] 

function socwidgets_func( $atts ){
	// белый список параметров и значения по умолчанию
	$atts = shortcode_atts( array(
		'section_title' => null
	), $atts );

	$section_title = $atts['section_title'];

	$echo = '<section id="soc-section" style="display:none"><div class="row section-container"><div class="col">';
	if( $section_title ) $echo .= '<h2>'.$section_title.'</h2>';
	$echo .= '<div class="row"><div class="col-md-6">';
	$echo .= '<!-- VK Widget --><div id="vk_groups" style="margin: auto;"></div>';
	$echo .= '</div></div>';
	$echo .= '<noscript><p><a href="https://vk.com/excurs_orel" target="_blank">ВКонтакте</a></p><p><a href="https://www.facebook.com/groups/excursorel" target="_blank">Фейсбук</a></p></noscript>';
	$echo .= '</div></div></section>';

	return $echo;
}