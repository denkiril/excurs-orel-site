<?php
/**
 * Excursions functions and definitions
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

		// This theme uses wp_nav_menu() in locations below.
		register_nav_menus(
			array(
				'header_menu' => 'Меню в шапке',
				'footer_menu' => 'Меню в подвале',
			)
		);

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
			)
		);

		// Set up the WordPress core custom background feature.
		add_theme_support(
			'custom-background',
			apply_filters(
				'excursions_custom_background_args',
				array(
					'default-color' => 'ffffff',
					'default-image' => '',
				)
			)
		);

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support(
			'custom-logo',
			array(
				'height'      => 250,
				'width'       => 250,
				'flex-width'  => true,
				'flex-height' => true,
			)
		);
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
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'excursions' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'excursions' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'excursions_widgets_init' );

/**
 * Enqueue scripts and styles.
 */

$links_array = array();
$consolelog  = '';
define( 'SCRIPTS_VER', '20200414' );
define( 'STYLES_VER', '20200414' );
$webp_on = ! ( home_url() === 'http://excurs-orel' );
if ( ! $webp_on ) {
	console_log( 'WEBP_OFF' );
}

define( 'PLACEHOLDER_URL_3X2', get_template_directory_uri() . '/assets/img/placeholder_3x2.png' );
define( 'PLACEHOLDER_URL_4X4', get_template_directory_uri() . '/assets/img/placeholder_4x4.png' );
define( 'PLACEHOLDER_URL_2X3', get_template_directory_uri() . '/assets/img/placeholder_2x3.png' );
define( 'PLACEHOLDER_URL_MAP', get_template_directory_uri() . '/assets/img/map_loader.svg' );

preload_link( get_template_directory_uri() . '/assets/css/main_bottom.css', STYLES_VER );

/**
 * Scripts loading.
 *
 * @return void
 */
function excursions_scripts() {
	$scripts_dirname = get_template_directory_uri() . '/assets/js/';
	wp_enqueue_style( 'excursions-style', get_stylesheet_uri(), array(), STYLES_VER );

	wp_enqueue_script( 'excursions-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20151215', true );

	wp_enqueue_script( 'excursions-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20151215', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	// $pf_url = 'https://polyfill.io/v3/polyfill.min.js?features=fetch%2CElement.prototype.matches%2CObject.keys%2CNodeList.prototype.forEach%2CArray.prototype.forEach';
	// wp_enqueue_script( 'polyfills-js', $pf_url, array(), '1', 'in_footer' );
	wp_enqueue_script( 'polyfills-js', get_template_directory_uri() . '/assets/include/polyfills-es5.js', array(), '20200410', 'in_footer' );

	wp_enqueue_script( 'script-js', $scripts_dirname . 'script.js', array(), SCRIPTS_VER, 'in_footer' );
	wp_enqueue_script( 'script-legacy', $scripts_dirname . 'script-legacy.js', array(), SCRIPTS_VER, 'in_footer' );
	$script_data = array(
		'url'   => admin_url( 'admin-ajax.php' ),
		'nonce' => wp_create_nonce( 'myajax-nonce' ),
	);
	wp_localize_script( 'script-js', 'myajax', $script_data );
	wp_localize_script( 'script-legacy', 'myajax', $script_data );

	if ( is_singular( 'events' ) || is_singular( 'guidebook' ) || is_page( 'map' ) ) {
		wp_enqueue_style( 'events', get_template_directory_uri() . '/assets/css/events.css', array(), STYLES_VER );
		wp_enqueue_script( 'events-js', $scripts_dirname . 'events.js', array( 'script-js' ), SCRIPTS_VER, 'in_footer' );
		wp_enqueue_script( 'events-legacy', $scripts_dirname . 'events-legacy.js', array( 'script-legacy' ), SCRIPTS_VER, 'in_footer' );
	}

	wp_deregister_script( 'jquery' );
	// wp_register_script( 'jquery', '//code.jquery.com/jquery-3.3.1.min.js', array(), '3.3.1', 'in_footer' ).
	wp_register_script( 'jquery', get_template_directory_uri() . '/assets/include/jquery-3.3.1.min.js', array(), '1', 'in_footer' );
	wp_register_script( 'glide-js', get_template_directory_uri() . '/assets/include/glide.min.js', array(), '1', 'in_footer' );
	wp_register_script( 'widgets-js', $scripts_dirname . 'widgets.js', array( 'glide-js' ), SCRIPTS_VER, 'in_footer' );
	wp_register_script( 'widgets-legacy', $scripts_dirname . 'widgets-legacy.js', array( 'glide-js' ), SCRIPTS_VER, 'in_footer' );

	wp_enqueue_script( 'cssrelpreload-js', get_template_directory_uri() . '/assets/include/cssrelpreload.js', array(), '1', 'in_footer' );
	wp_register_script( 'acf_map-js', $scripts_dirname . 'acf_map.js', array( 'script-js' ), SCRIPTS_VER, 'in_footer' );
	wp_register_script( 'acf_map-legacy', $scripts_dirname . 'acf_map-legacy.js', array( 'script-legacy' ), SCRIPTS_VER, 'in_footer' );
	wp_register_script( 'events_map-js', $scripts_dirname . 'events_map.js', array( 'script-js' ), SCRIPTS_VER, 'in_footer' );
	wp_register_script( 'events_map-legacy', $scripts_dirname . 'events_map-legacy.js', array( 'script-legacy' ), SCRIPTS_VER, 'in_footer' );
	wp_register_script( 'guidebook_map-js', $scripts_dirname . 'guidebook_map.js', array( 'script-js' ), SCRIPTS_VER, 'in_footer' );
	wp_register_script( 'guidebook_map-legacy', $scripts_dirname . 'guidebook_map-legacy.js', array( 'script-legacy' ), SCRIPTS_VER, 'in_footer' );

	wp_register_script( 'yashare-js', '//yastatic.net/share2/share.js', array(), '1', 'in_footer' );
	wp_register_script( 'fancybox-js', '//cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.6/dist/jquery.fancybox.min.js', array( 'jquery' ), '1', 'in_footer' );
	wp_register_style( 'glide-core', get_template_directory_uri() . '/assets/include/glide.core.min.css', array(), '1' );
	wp_register_style( 'glide-theme', get_template_directory_uri() . '/assets/include/glide.theme.min.css', array(), '1' );
	wp_register_style( 'events_map', get_template_directory_uri() . '/assets/css/events_map.css', array(), STYLES_VER );
	wp_register_style( 'guidebook_map', get_template_directory_uri() . '/assets/css/guidebook_map.css', array(), STYLES_VER );
}
add_action( 'wp_enqueue_scripts', 'excursions_scripts' );

/**
 * Add attributes to script tag.
 *
 * @param string $tag    The `<script>` tag for the enqueued script.
 * @param string $handle The script's registered handle.
 * @param string $src    The script's source URL.
 * @return string Changed `<script>` tag.
 */
function change_my_script( $tag, $handle, $src ) {
	switch ( $handle ) {
		case ( 'script-js' ):
		case ( 'events-js' ):
		case ( 'widgets-js' ):
		case ( 'acf_map-js' ):
		case ( 'events_map-js' ):
		case ( 'guidebook_map-js' ):
		case ( 'yashare-js' ):
			$_tag = str_replace( "type='text/javascript'", "type='module'", $tag );
			break;

		case ( 'script-legacy' ):
		case ( 'events-legacy' ):
		case ( 'widgets-legacy' ):
		case ( 'acf_map-legacy' ):
		case ( 'events_map-legacy' ):
		case ( 'guidebook_map-legacy' ):
		case ( 'polyfills-js' ):
			$_tag = str_replace( "type='text/javascript'", 'nomodule defer', $tag );
			break;

		case ( 'cssrelpreload-js' ):
			$_tag = str_replace( "type='text/javascript'", 'async defer', $tag );
			break;

		default:
			$_tag = $tag;
	}

	return $_tag;
}
add_filter( 'script_loader_tag', 'change_my_script', 10, 3 );

/**
 * Add link tag to links array.
 *
 * @param string  $href Link reference.
 * @param boolean $ver  Link version.
 * @param string  $type Link type.
 * @return void
 */
function preload_link( $href, $ver = false, $type = 'style' ) {
	global $links_array;
	// Если $href ещё не было, добавляем в массив ссылок.

	foreach ( $links_array as $link ) {
		if ( $link['href'] === $href ) {
			return;
		}
	}
	$links_array[] = array(
		'href' => $href,
		'ver'  => $ver,
		'type' => $type,
	);
}

/**
 * Load links from links array.
 *
 * @return void
 */
function preload_links() {
	// <link rel="preload" href="AO.js" as="script">.
	// <link rel="preload" href="AO.css" as="style">.
	global $links_array;

	if ( ! empty( $links_array ) ) {
		foreach ( $links_array as $link ) {
			$href = $link['href'];
			if ( $link['ver'] ) {
				$href .= '?ver=' . $link['ver'];
			}

			if ( 'style' === $link['type'] ) {
				echo '<link rel="preload" as="style" href="' . esc_html( $href ) . '" onload="this.rel=\'stylesheet\'" />' . PHP_EOL;
				echo '<noscript><link rel="stylesheet" href="' . esc_html( $href ) . '"></noscript>' . PHP_EOL;
			} elseif ( 'font' === $link['type'] ) {
				echo '<link rel="preload" as="font" type="font/woff" href="' . esc_html( $href ) . '" crossorigin />' . PHP_EOL;
			}
		}
	}
}
add_action( 'wp_footer', 'preload_links' );

/**
 * Add scripts for gallery widget.
 *
 * @return void
 */
function add_gallery_scripts_func() {
	wp_enqueue_script( 'fancybox-js' );
	preload_link( '//cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.6/dist/jquery.fancybox.min.css' );
	preload_link( get_template_directory_uri() . '/assets/css/gallery.css', STYLES_VER );
}
add_action( 'add_gallery_scripts', 'add_gallery_scripts_func', 10, 0 );

/**
 * Add scripts for carousel widget.
 *
 * @return void
 */
function add_carousel_scripts() {
	wp_enqueue_script( 'widgets-js' );
	wp_enqueue_script( 'widgets-legacy' );
	wp_enqueue_style( 'glide-core' );
	wp_enqueue_style( 'glide-theme' );
}

/**
 * Add scripts for events_map widget.
 *
 * @return void
 */
function events_map_scripts_func() {
	wp_enqueue_style( 'events_map' );
	wp_enqueue_script( 'events_map-js' );
	wp_enqueue_script( 'events_map-legacy' );
	// wp_localize_script( 'events_map-legacy', 'myajax', array( 'url' => admin_url( 'admin-ajax.php' ) ) );.
}
add_action( 'events_map_scripts', 'events_map_scripts_func', 10, 0 );

/**
 * Add scripts for guidebook_map widget.
 *
 * @return void
 */
function guidebook_map_scripts_func() {
	$data = array(
		'url'   => admin_url( 'admin-ajax.php' ),
		'nonce' => wp_create_nonce( 'myajax-gb-nonce' ),
	);
	wp_enqueue_style( 'guidebook_map' );
	wp_enqueue_script( 'guidebook_map-js' );
	wp_enqueue_script( 'guidebook_map-legacy' );
	// wp_localize_script( 'guidebook_map-legacy', 'myajax', $data );.
}
add_action( 'guidebook_map_scripts', 'guidebook_map_scripts_func', 10, 0 );

/**
 * Add scripts for event_map widget.
 *
 * @return void
 */
function event_map_scripts_func() {
	wp_enqueue_script( 'acf_map-js' );
	wp_enqueue_script( 'acf_map-legacy' );
	// wp_localize_script( 'acf_map-legacy', 'myajax', array( 'url' => admin_url( 'admin-ajax.php' ) ) );.
}
add_action( 'event_map_scripts', 'event_map_scripts_func', 10, 0 );

/**
 * Add scripts for social widgets.
 *
 * @return void
 */
function add_social_scripts() {
	wp_enqueue_script( 'widgets-js' );
	wp_enqueue_script( 'widgets-legacy' );
}

/**
 * Add scripts for share widget.
 *
 * @return void
 */
function add_share_scripts_func() {
	wp_enqueue_script( 'yashare-js' );
}
add_action( 'add_share_scripts', 'add_share_scripts_func', 10, 0 );

/* Устраните ресурсы, блокирующие отображение */

// contact-form-7
// \wp-content\plugins\contact-form-7\includes\controller.php :
// /wp-content/plugins/contact-form-7/includes/js/scripts.js
// add_action( 'wp_enqueue_scripts', 'wpcf7_do_enqueue_scripts', 10, 0 ).
remove_action( 'wp_enqueue_scripts', 'wpcf7_do_enqueue_scripts', 10, 0 );

/**
 * Add wpcf7 scripts if need ( $show_form ).
 *
 * @return void
 */
function add_wpcf7_scripts_func() {
	if ( wpcf7_load_js() ) {
		wpcf7_enqueue_scripts();
	}

	if ( wpcf7_load_css() ) {
		wpcf7_enqueue_styles();
	}
}
add_action( 'add_wpcf7_scripts', 'add_wpcf7_scripts_func', 10, 0 );

// block-library
// wp-includes\default-filters.php :
// add_action( 'wp_enqueue_scripts', 'wp_common_block_scripts_and_styles' );.
remove_action( 'wp_enqueue_scripts', 'wp_common_block_scripts_and_styles' );

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

/**
 * Register custom post types: events, guidebook...
 *
 * @return void
 */
function register_post_types() {
	// post_type => events.
	register_post_type(
		'events',
		array(
			'label'  => null,
			'labels' => array(
				'name'               => 'События', // основное название для типа записи.
				'singular_name'      => 'Событие', // название для одной записи этого типа.
				'add_new'            => 'Добавить событие', // для добавления новой записи.
				'add_new_item'       => 'Добавление события', // заголовка у вновь создаваемой записи в админ-панели.
				'edit_item'          => 'Редактировать событие', // для редактирования типа записи.
				'new_item'           => 'Новое событие', // текст новой записи.
				'view_item'          => 'Смотреть событие', // для просмотра записи этого типа.
				'search_items'       => 'Искать события', // для поиска по этим типам записи.
				'not_found'          => 'Не найдено', // если в результате поиска ничего не было найдено.
				'not_found_in_trash' => 'Не найдено в корзине', // если не было найдено в корзине.
				'parent_item_colon'  => '', // для родителей (у древовидных типов).
				'menu_name'          => 'События', // название меню.
			),
			'description'         => 'События – это наши экскурсии, лекции и прочие мероприятия с датой.',
			'public'              => true,
			'publicly_queryable'  => true, // зависит от public.
			'exclude_from_search' => false, // зависит от public.
			'show_ui'             => true, // зависит от public.
			'show_in_menu'        => true, // показывать ли в меню адмнки.
			'show_in_admin_bar'   => true, // по умолчанию значение show_in_menu.
			'show_in_nav_menus'   => true, // зависит от public.
			'show_in_rest'        => true, // добавить в REST API. C WP 4.7.
			'rest_base'           => null, // $post_type. C WP 4.7.
			'menu_position'       => 4,
			'menu_icon'           => 'dashicons-calendar-alt',
			'hierarchical'        => false,
			'supports'            => array( 'title', 'author', 'thumbnail', 'excerpt', 'comments' ),
			// 'title','editor','author','thumbnail','excerpt','trackbacks','custom-fields','comments','revisions','page-attributes','post-formats'.
			'taxonomies'          => array( 'category' ),
			'has_archive'         => true,
			'rewrite'             => true,
			'query_var'           => true,
		)
	);

	// post_type => guidebook.
	register_post_type(
		'guidebook',
		array(
			'label'  => null,
			'labels' => array(
				'name'               => 'Путеводитель', // основное название для типа записи.
				'singular_name'      => 'Статья ПВ', // название для одной записи этого типа.
				'add_new'            => 'Добавить статью в ПВ', // для добавления новой записи.
				'add_new_item'       => 'Добавление статьи в ПВ', // заголовка у вновь создаваемой записи в админ-панели.
				'edit_item'          => 'Редактировать статью ПВ', // для редактирования типа записи.
				'new_item'           => 'Новая статья ПВ', // текст новой записи.
				'view_item'          => 'Смотреть статью ПВ', // для просмотра записи этого типа.
				'search_items'       => 'Искать статьи в ПВ', // для поиска по этим типам записи.
				'not_found'          => 'Не найдено', // если в результате поиска ничего не было найдено.
				'not_found_in_trash' => 'Не найдено в корзине', // если не было найдено в корзине.
				'parent_item_colon'  => '', // для родителей (у древовидных типов).
				'menu_name'          => 'Путеводитель', // название меню.
			),
			'description'         => 'Путеводитель по Орлу от краеведов.',
			'public'              => true,
			'publicly_queryable'  => true, // зависит от public.
			'exclude_from_search' => false, // зависит от public.
			'show_ui'             => true, // зависит от public.
			'show_in_menu'        => true, // показывать ли в меню адмнки.
			'show_in_admin_bar'   => true, // по умолчанию значение show_in_menu.
			'show_in_nav_menus'   => true, // зависит от public.
			'show_in_rest'        => true, // добавить в REST API. C WP 4.7.
			'rest_base'           => null, // $post_type. C WP 4.7.
			'menu_position'       => 5,
			'menu_icon'           => 'dashicons-book-alt',
			'hierarchical'        => false,
			'supports'            => array( 'title', 'author', 'thumbnail' ),
			// 'title','editor','author','thumbnail','excerpt','trackbacks','custom-fields','comments','revisions','page-attributes','post-formats'.
			'taxonomies'          => array( 'category', 'sections' ),
			'has_archive'         => false,
			'rewrite'             => true,
			'query_var'           => true,
		)
	);

	// taxonomies => sections.
	register_taxonomy(
		'sections',
		array( 'guidebook' ),
		array(
			'label'              => '', // определяется параметром $labels->name.
			'labels'             => array(
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
			'description'        => 'Разделы Путеводителя', // описание таксономии.
			'public'             => true,
			'publicly_queryable' => null, // равен аргументу public.
			'show_in_nav_menus'  => true, // равен аргументу public.
			'show_ui'            => true, // равен аргументу public.
			'show_in_menu'       => true, // равен аргументу show_ui.
			'show_tagcloud'      => true, // равен аргументу show_ui.
			'show_in_rest'       => null, // добавить в REST API.
			'rest_base'          => null, // $taxonomy.
			'hierarchical'       => true, // true - таксономия будет древовидная (как категории). false - будет не древовидная (как метки).
			'rewrite'            => array( 'slug' => 'guidebook_' ),
			'capabilities'       => array(),
			'meta_box_cb'        => null, // callback функция. Отвечает за html код метабокса (с версии 3.8): post_categories_meta_box или post_tags_meta_box. Если указать false, то метабокс будет отключен вообще.
			'show_admin_column'  => true, // Позволить или нет авто-создание колонки таксономии в таблице ассоциированного типа записи. (с версии 3.5).
			'_builtin'           => false,
			'show_in_quick_edit' => null, // по умолчанию значение show_ui.
		)
	);
}
add_action( 'init', 'register_post_types' );

/**
 * Сбрасываем правила для произвольных типов записей
 *
 * @return void
 */
function excursions_flush_rewrite_rules() {
	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'excursions_flush_rewrite_rules' );

/**
 * Меняем порядок вывода записей для архива типа записи 'events'
 *
 * @param object $query WP_Query object.
 * @return void
 */
function events_orderby_meta( $query ) {
	// Выходим, если это админ-панель или не основной запрос.
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}

	if ( $query->is_post_type_archive( 'events' ) ) {
		// $query->set( 'posts_per_page', 5 ); // default=10.
		$query->set( 'orderby', 'meta_value' );
		$query->set( 'meta_key', 'event_info_event_date' );
		$query->set( 'post__not_in', array( 312 ) );
	}
}
add_action( 'pre_get_posts', 'events_orderby_meta', 1 );

/**
 * Меняем порядок вывода записей для архивов таксономии 'sections'
 *
 * @param object $query WP_Query object.
 * @return void
 */
function sections_orderby_meta( $query ) {
	// Выходим, если это админ-панель или не основной запрос.
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}

	if ( $query->is_tax( 'sections' ) ) {
		$query->set( 'posts_per_page', 24 ); // default=10.
		// $query->set( 'orderby',  'meta_value_num' );.
		$query->set(
			'orderby',
			array(
				'meta_value_num' => 'DESC',
				'title' => 'ASC',
			)
		);
		$query->set( 'meta_key', 'gba_rating' ); // 1...10.
		// $query->set( 'order', 	 'DESC' ); 		// от большего к меньшему, 10 -> 1.
	}
}
add_action( 'pre_get_posts', 'sections_orderby_meta', 1 );

/**
 * Включаем поиск для записей типа 'events'.
 *
 * @param object $query WP_Query object.
 * @return void
 */
function get_posts_search_filter( $query ) {
	if ( ! is_admin() && $query->is_main_query() && $query->is_search ) {
		$query->set( 'post_type', array( 'post', 'events' ) );
	}
}
add_action( 'pre_get_posts', 'get_posts_search_filter' );

/**
 * Markup date with <time> tag
 *
 * @param number $post_id Post id.
 * @return string html
 */
function markup_event_date( $post_id = null ) {
	$html = '';

	$event_date = get_field( 'event_info_event_date', $post_id );
	if ( $event_date ) {
		$raw_date = get_field( 'event_info_event_date', $post_id, false );
		$date_obj = new DateTime( $raw_date );
		$datetime = $date_obj->format( 'Y-m-d' );
		$html     = '<time datetime="' . $datetime . '">' . $event_date . '</time> ';
	}

	return $html;
}

/**
 * Add custom shortcode attribute to Contact Form 7
 *
 * @param array $out Output atts.
 * @param array $pairs I dont know.
 * @param array $atts Custom input atts.
 * @return array Custom output atts.
 */
function custom_shortcode_atts_wpcf7( $out, $pairs, $atts ) {
	$my_attr = 'event-title';

	if ( isset( $atts[ $my_attr ] ) ) {
		$out[ $my_attr ] = $atts[ $my_attr ];
	}

	return $out;
}
add_filter( 'shortcode_atts_wpcf7', 'custom_shortcode_atts_wpcf7', 10, 3 );

// "Sender email address does not belong to the site domain." avoid
// add_filter( 'wpcf7_validate_configuration', '__return_false' );

/**
 * Disable the emoji's
 */
function disable_emojis() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
}
add_action( 'init', 'disable_emojis' );

/**
 * Clear the archive title
 * Удаляет слова "Рубрика: ", "Метка: " и т.д. из заголовка архива.
 *
 * @param string $title Default title.
 * @return string Cleared title.
 */
function clear_archive_title( $title ) {
	return preg_replace( '~^[^:]+: ~', '', $title );
}
add_filter( 'get_the_archive_title', 'clear_archive_title' );

/**
 * Function get_attachment_picture() is mod of standart wp_get_attachment_image()
 *
 * @param int          $attachment_id Image attachment ID.
 * @param string|int[] $size          Optional. Image size. Accepts any valid image size name, or an array of width
 *                                    and height values in pixels (in that order). Default 'thumbnail'.
 * @param bool         $icon          Optional. Whether the image should fall back to a mime type icon. Default false.
 * @param string|array $attr {
 *     Optional. Attributes for the image markup.
 *
 *     @type string $src    Image attachment URL.
 *     @type string $class  CSS class name or space-separated list of classes.
 *                          Default `attachment-$size_class size-$size_class`,
 *                          where `$size_class` is the image size being requested.
 *     @type string $alt    Image description for the alt attribute.
 *     @type string $srcset The 'srcset' attribute value.
 *     @type string $sizes  The 'sizes' attribute value.
 * }
 * @param bool         $lazy          Optional. Whether the image should lazyloading. Default true.
 * @param bool         $placeholder   Optional. Whether the image should have placeholder image. Default false.
 * @return string HTML img element or empty string on failure.
 */
function get_attachment_picture( $attachment_id, $size = 'thumbnail', $icon = false, $attr = '', $lazy = true, $placeholder = false ) {
	$html = '';
	// $image = wp_get_attachment_image_src( $attachment_id, $size, $icon );
	// anti image_constrain_size_for_editor() to $content_width.
	$is_image = wp_attachment_is_image( $attachment_id );
	if ( $is_image ) {
		$img_url          = wp_get_attachment_url( $attachment_id );
		$image_meta       = wp_get_attachment_metadata( $attachment_id );
		$width            = $image_meta['width'];
		$height           = $image_meta['height'];
		$img_url_basename = wp_basename( $img_url );
		// try for a new style intermediate size.
		$intermediate = image_get_intermediate_size( $attachment_id, $size );
		if ( $intermediate ) {
			$img_url = str_replace( $img_url_basename, $intermediate['file'], $img_url );
			$width   = $intermediate['width'];
			$height  = $intermediate['height'];
		}
		// list( $src, $width, $height ) = $image.
		$src        = $img_url;
		$hwstring   = image_hwstring( $width, $height );
		$size_class = $size;
		if ( is_array( $size_class ) ) {
			$size_class = join( 'x', $size_class );
		}
		$attachment   = get_post( $attachment_id );
		$default_attr = array(
			'src'   => $src,
			'class' => "attachment-$size_class size-$size_class",
			'alt'   => trim( strip_tags( get_post_meta( $attachment_id, '_wp_attachment_image_alt', true ) ) ),
		);

		if ( $lazy ) {
			$def_attr = wp_parse_args( $attr, $default_attr );
			$wh_coef  = $width / $height;
			if ( $wh_coef >= 1.49 ) {
				$placeholder_url = PLACEHOLDER_URL_3X2;
			} elseif ( $wh_coef >= 0.99 ) {
				$placeholder_url = PLACEHOLDER_URL_4X4;
			} else {
				$placeholder_url = PLACEHOLDER_URL_2X3;
			}

			$default_attr = array_merge(
				$default_attr,
				array(
					'src'      => $placeholder_url,
					'data-src' => $src,
				)
			);
		}

		$attr = wp_parse_args( $attr, $default_attr );

		// Generate <source>'s
		// Retrieve the uploads sub-directory from the full size image.
		$dirname = _wp_get_attachment_relative_path( $image_meta['file'] );

		if ( $dirname ) {
			$dirname = trailingslashit( $dirname );
		}

		$upload_dir    = wp_get_upload_dir();
		$image_baseurl = trailingslashit( $upload_dir['baseurl'] ) . $dirname;

		/*
		 * If currently on HTTPS, prefer HTTPS URLs when we know they're supported by the domain
		 * (which is to say, when they share the domain name of the current request).
		 */
		if ( is_ssl() && 'https' !== substr( $image_baseurl, 0, 5 ) && isset( $_SERVER['HTTP_HOST'] ) && wp_parse_url( $image_baseurl, PHP_URL_HOST ) === $_SERVER['HTTP_HOST'] ) {
			$image_baseurl = set_url_scheme( $image_baseurl, 'https' );
		}

		// $html .= $source.$full_image_url.'.webp" media="(min-width: 1200px)" type="image/webp">';
		// $image_sizes = $image_meta['sizes'];
		// $image_sizes = array_reverse( $image_sizes );.
		$size_array = array( absint( $width ), absint( $height ) );
		$sizes      = wp_calculate_image_sizes( $size_array, $src, $image_meta, $attachment_id );
		if ( $sizes ) {
			$sizes_html = $lazy ? 'data-sizes="' . $sizes . '"' : 'sizes="' . $sizes . '"';
		}

		$html = '<span class="picture"><picture>';

		global $webp_on;
		if ( $webp_on ) {
			$srcset = generate_image_srcset( $image_meta, $image_baseurl, true );
			if ( $srcset ) {
				$srcset_html = $lazy ? 'data-srcset="' . $srcset . '"' : 'srcset="' . $srcset . '"';
				$html       .= '<source type="image/webp" ' . $srcset_html . ' ' . $sizes_html . '>';
			}
		}

		$srcset = generate_image_srcset( $image_meta, $image_baseurl );
		if ( $srcset ) {
			$srcset_html = $lazy ? 'data-srcset="' . $srcset . '"' : 'srcset="' . $srcset . '"';
			$html       .= '<source ' . $srcset_html . ' ' . $sizes_html . '>';
		}

		// $attr = apply_filters( 'get_attachment_picture_attributes', $attr, $attachment, $size );?
		$attr  = array_map( 'esc_attr', $attr );
		$html .= rtrim( "<img $hwstring" );
		foreach ( $attr as $name => $value ) {
			$html .= " $name=" . '"' . $value . '"';
		}
		$html .= ' /></picture>';

		if ( $lazy ) {
			$def_attr = array_map( 'esc_attr', $def_attr );
			$html    .= rtrim( "<noscript><img $hwstring" );
			foreach ( $def_attr as $name => $value ) {
				$html .= " $name=" . '"' . $value . '"';
			}
			$html .= ' /></noscript>';
		}
		$html .= '</span>';
	} elseif ( $placeholder ) {
		$html = '<img src="' . PLACEHOLDER_URL_3X2 . '" alt="Картинки нет" />';
	}

	return $html;
}

/**
 * This function is the same as echo get_attachment_picture()
 *
 * @param int          $attachment_id Image attachment ID.
 * @param string|int[] $size          Optional. Image size. Accepts any valid image size name, or an array of width
 *                                    and height values in pixels (in that order). Default 'thumbnail'.
 * @param bool         $icon          Optional. Whether the image should fall back to a mime type icon. Default false.
 * @param string|array $attr {
 *     Optional. Attributes for the image markup.
 *
 *     @type string $src    Image attachment URL.
 *     @type string $class  CSS class name or space-separated list of classes.
 *                          Default `attachment-$size_class size-$size_class`,
 *                          where `$size_class` is the image size being requested.
 *     @type string $alt    Image description for the alt attribute.
 *     @type string $srcset The 'srcset' attribute value.
 *     @type string $sizes  The 'sizes' attribute value.
 * }
 * @param bool         $lazy          Optional. Whether the image should lazyloading. Default true.
 * @param bool         $placeholder   Optional. Whether the image should have placeholder image. Default false.
 * @return void
 */
function the_attachment_picture( $attachment_id, $size = 'thumbnail', $icon = false, $attr = '', $lazy = true, $placeholder = false ) {
	echo get_attachment_picture( $attachment_id, $size, $icon, $attr, $lazy, $placeholder );
}

/**
 * Generate image srcset attribute
 *
 * @param array   $image_meta    Image meta array.
 * @param string  $image_baseurl Image baseurl.
 * @param boolean $webp          Optional. Should add webp src. Default false.
 * @return string                Output srcset attribute.
 */
function generate_image_srcset( $image_meta, $image_baseurl, $webp = false ) {
	$srcset      = '';
	$image_sizes = $image_meta['sizes'];
	if ( ! is_array( $image_sizes ) ) {
		return $srcset;
	}

	$sfx            = $webp ? '.webp' : '';
	$image_basename = wp_basename( $image_meta['file'] );
	$strings        = array();
	foreach ( $image_sizes as $image ) {
		// Check if image meta isn't corrupted.
		if ( ! is_array( $image ) ) {
			continue;
		}

		if ( isset( $image['file'] ) ) {
			$file_width = $image['width'];
			if ( $file_width < 300 ) {
				continue;
			}

			$image_url = $image_baseurl . $image['file'];
			$string    = $image_url . $sfx . ' ' . $file_width . 'w';
			array_push( $strings, $string );
		}
	}

	$image_url  = $image_baseurl . $image_basename; // full_image_url.
	$file_width = (int) $image_meta['width']; // full_image_width.
	$string     = $image_url . $sfx . ' ' . $file_width . 'w';
	array_push( $strings, $string );

	$srcset = implode( ', ', $strings );

	return $srcset;
}

/**
 * Get title attribute escaped of &shy;
 *
 * @param array|int $post Optional. Post object or ID. Default null.
 * @return string Title of post escaped of &shy;.
 */
function excurs_get_the_title_for_attr( $post = null ) {
	return str_replace( '&shy;', '', get_the_title( $post ) );
}
/**
 * Print title attribute escaped of &shy;
 *
 * @return void
 */
function excurs_the_title_attr() {
	printf( 'title="%s"', esc_attr( excurs_get_the_title_for_attr() ) );
}

/**
 * Markup href to <a> tag
 *
 * @param string  $url      The href attribute.
 * @param string  $text     The content of <a> tag.
 * @param boolean $add_link Optional. Do work? Default true.
 *
 * @return string Output HTML.
 */
function markup_url_permalink( $url, $text, $add_link = true ) {
	if ( $add_link && $url ) {
		$ret = wp_parse_url( $url );
		if ( ! isset( $ret['scheme'] ) ) {
			$url = "http://{$url}";
		}
		$text = '<a href="' . $url . '" target="_blank" rel="noopener noreferrer">' . $text . '</a>';
	}
	return $text;
}

/**
 * Markup href to <a> tag
 *
 * @param string  $postid    Post ID.
 * @param string  $text      The content of <a> tag.
 * @param boolean $permalink Optional. The permalink. Default null.
 * @param string  $title     Title attribute.
 *
 * @return string Output HTML.
 */
function markup_post_permalink( $postid, $text, $permalink = null, $title = null ) {
	$post_id   = (int) $postid;
	$permalink = $permalink ? $permalink : ( $post_id > 0 ? get_permalink( $post_id ) : null );
	$title     = $title ? $title : esc_html( get_the_title( $post_id ) );
	$title     = $title ? ' title="' . $title . '"' : '';
	if ( $permalink ) {
		$text = '<a href="' . $permalink . '"' . $title . '">' . $text . '</a>';
	}

	return $text;
}

/**
 * Get guidebook post ID by name (slug)
 *
 * @param string $post_name Guidebook post slug.
 * @return int|null Post ID or null if post not found.
 */
function get_post_id( $post_name ) {
	$post_id = null;
	if ( preg_match( '/post[id]*=(\\d+)/u', $post_name, $matches ) ) {
		$post_id = (int) $matches[1];
	} else {
		$post_obj = get_page_by_path( $post_name, OBJECT, 'guidebook' );
		if ( $post_obj ) {
			$post_id = (int) $post_obj->ID;
		}
	}

	return $post_id;
}

/**
 * Parse wiki-style refs in text and markup them
 *
 * @param string  $text     Text for parsing.
 * @param boolean $add_link Optional. Should markup links in urls? Default true.
 * @return array Text and array of sights.
 */
function wiki_parse( $text, $add_link = true ) {
	$sights    = array();
	$dbl_count = preg_match_all( '/\\[{2}([^\\|]*)\\|([^\\]]*)\\]{2}/u', $text, $matches );
	for ( $i = 0; $i < $dbl_count; $i++ ) {
		$post_id = get_post_id( $matches[1][ $i ] );
		if ( $post_id ) {
			$title     = esc_html( get_the_title( $post_id ) );
			$permalink = get_permalink( $post_id );
			$link_text = markup_post_permalink( $post_id, $matches[2][ $i ], $permalink, $title );
			$text      = str_replace( $matches[0][ $i ], $link_text, $text );

			$location = get_field( 'obj_info', $post_id )['geolocation'];
			if ( $location ) {
				$thumb_url = get_the_post_thumbnail_url( $post_id, 'thumbnail' );
				$sights[]  = array(
					'lat'       => $location['lat'],
					'lng'       => $location['lng'],
					'post_id'   => $post_id,
					'permalink' => $permalink,
					'title'     => $title,
					'thumb_url' => $thumb_url,
				);
			}
		}
	}

	$url_count = preg_match_all( '/\\[url=([^\\|]*)\\|([^\\]]*)\\]/u', $text, $matches );
	for ( $i = 0; $i < $url_count; $i++ ) {
		$text = str_replace( $matches[0][ $i ], markup_url_permalink( $matches[1][ $i ], $matches[2][ $i ], $add_link ), $text );
	}
	$post_count = preg_match_all( '/\\[post[id]*=(\\d+)\\|([^\\]]*)\\]/u', $text, $matches );
	for ( $i = 0; $i < $post_count; $i++ ) {
		$text = str_replace( $matches[0][ $i ], markup_post_permalink( $matches[1][ $i ], $matches[2][ $i ] ), $text );
	}

	return array(
		'text'   => $text,
		'sights' => $sights,
	);
}

/**
 * Alias for get 'text' key of wiki_parse function
 *
 * @param string  $text     Text for parsing.
 * @param boolean $add_link Optional. Should markup links in urls? Default true.
 * @return string Output HTML.
 */
function wiki_parse_text( $text, $add_link = true ) {
	return wiki_parse( $text, $add_link )['text'];
}

/**
 * Markup image with <figure> tags anf fancybox attributes
 *
 * @param int          $att_id          Image attachment ID.
 * @param string       $fancybox        Fancybox name.
 * @param string       $full_image_url  The URL of full image source.
 * @param string       $fancy_caption   Fancybox caption of the image.
 * @param string|int[] $size            Optional. Image size. Accepts any valid image size name, or an array of width
 *                                      and height values in pixels (in that order). Default 'thumbnail'.
 * @param boolean      $lazy            Optional. Whether the image should lazyloading. Default true.
 * @param string       $title           Optional. Title attribute. Default null.
 * @param string       $img_class       Optional. Class of the image. Default null.
 * @param string       $figcaption_html Optional. Content of <figcaption>. Default null.
 *
 * @return string Output HTML.
 */
function markup_fancy_figure( $att_id, $fancybox, $full_image_url, $fancy_caption, $size = 'thumbnail', $lazy = false, $title = null, $img_class = null, $figcaption_html = null ) {
	$fancy_hint = '(Нажмите, чтобы увеличить)';
	$title_attr = $title;

	if ( $fancybox ) {
		$title_attr .= ' ' . $fancy_hint;
	}
	if ( $fancy_caption ) {
		$fancy_caption = ' data-caption="' . $fancy_caption . '"';
	}
	$attr = array();
	if ( $title_attr ) {
		$attr['title'] = $title_attr;
	}
	if ( $img_class ) {
		$attr['class'] = $img_class;
	}

	$html  = '<figure><a data-fancybox="' . $fancybox . '" href="' . $full_image_url . '"' . $fancy_caption . '>';
	$html .= get_attachment_picture( $att_id, $size, false, $attr, $lazy );
	// $html .= awpwp_get_attachment_picture( $att_id, $size, false, $attr, $lazy );
	$html .= '</a>';

	if ( $figcaption_html ) {
		$html .= '<figcaption>' . $figcaption_html . '</figcaption>';
	}
	$html .= '</figure>';

	return $html;
}

/**
 * Настраиваем страницам пагинации rel="canonical" на 1-ую стр. архива (для Yoast SEO)
 *
 * @return string The URL of right canonical page
 */
function return_canon() {
	$canon_page = is_page( 'guidebook' ) ? get_url_wo_pagenum() : get_pagenum_link();

	return $canon_page;
}

/**
 * Настраиваем страницам пагинации rel="canonical" на 1-ую стр. архива (для Yoast SEO)
 *
 * @return void
 */
function canon_paged() {
	if ( is_paged() ) {
		add_filter( 'wpseo_canonical', 'return_canon' );
	}
}
add_filter( 'wpseo_head', 'canon_paged' );

/**
 * Оптимизирует исходный файл и генерирует webp копии изображений
 * сразу после загрузки изображения в медиабиблиотеку –
 * новые файлы сохраняет с именем name.ext.webp, например, thumb.jpg.webp.
 * Использует функции https://www.php.net/manual/ru/book.image.php.
 *
 * @param array $metadata Attachment metadata.
 * @return array Same metadata.
 */
function gd_image_optimization_and_webp_generation( $metadata ) {
	$uploads = wp_upload_dir(); // получает папку для загрузки медиафайлов.
	$quality = 80; // imagewebp() default is 80, imagejpeg() ~75.

	$file     = $uploads['basedir'] . '/' . $metadata['file']; // получает исходный файл.
	$filetype = wp_check_filetype( $file )['type']; // mime тип файла.
	$filesize = filesize( $file ); // размер файла в байтах.

	if ( ! ( ( 'image/jpeg' === $filetype || 'image/png' === $filetype ) && $filesize ) ) {
		return $metadata; // работаем только с jpeg и png.
	}

	// оптимизируем (уменьшаем качество и вес) исходное изображение.
	// в зависимости от mime типа обрабатаывает файлы разными функциями.
	if ( 'image/jpeg' === $filetype ) {
		$image = imagecreatefromjpeg( $file ); // создает изображение из jpg.
		if ( $image ) {
			ob_start();
			imagejpeg( $image, null, $quality ); // сохраняем оптим. изображение в оперативную память.
			$new_file_size = ob_get_length();
			ob_end_clean();
			if ( $new_file_size < $filesize ) {
				imagejpeg( $image, $file, $quality ); // сохраняем оптим. изображение на место исходного.
			}
		}
	} elseif ( 'image/png' === $filetype ) {
		$image = imagecreatefrompng( $file ); // создает изображение из png.
		if ( $image ) {
			imagepalettetotruecolor( $image ); // восстанавливает цвета.
			imagealphablending( $image, false ); // выключает режим сопряжения цветов.
			imagesavealpha( $image, true ); // сохраняет прозрачность.

			ob_start();
			imagepng( $image ); // оптимизируем, 0...9, #define Z_DEFLATED 8 - сохр. в оперативную память.
			$new_file_size = ob_get_length();
			ob_end_clean();
			if ( $new_file_size < $filesize ) {
				imagepng( $image, $file ); // сохраняем оптим. изображение на место исходного.
			}
		}
	}

	// делаем webp-версию исх. изображения.
	if ( $image ) {
		imagewebp( $image, $file . '.webp', $quality );
	}

	// перебирает все размеры файла и также сохраняет в webp.
	foreach ( $metadata['sizes'] as $size_name => $size ) {
		$file = $uploads['basedir'] . $uploads['subdir'] . '/' . $size['file'];

		$sizes_filesize = filesize( $file );
		// если вес к.-л. (меньшего) размера больше исходного файла, удаляем этот размер.
		if ( $sizes_filesize && $sizes_filesize > $filesize ) {
			unlink( $file );
			unset( $metadata['sizes'][ $size_name ] );
			continue;
		}

		$sizes_filetype = $size['mime-type'];
		if ( 'image/jpeg' === $sizes_filetype ) {
			$image = imagecreatefromjpeg( $file );
		} elseif ( 'image/png' === $sizes_filetype ) {
			$image = imagecreatefrompng( $file );
			imagepalettetotruecolor( $image );
			imagealphablending( $image, false );
			imagesavealpha( $image, true );
		}

		if ( $image ) {
			imagewebp( $image, $file . '.webp', $quality );
		}
	}

	return $metadata;
}
add_filter( 'wp_generate_attachment_metadata', 'gd_image_optimization_and_webp_generation' );

/**
 * Get query params from $_SERVER['REQUEST_URI']
 *
 * @return array Query params.
 */
function excurs_get_query_params() {
	$query_params = array();

	if ( isset( $_SERVER['REQUEST_URI'] ) ) {
		$uri   = esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) );
		$parts = wp_parse_url( $uri );
		if ( isset( $parts['query'] ) ) {
			parse_str( $parts['query'], $query_params );
		}
	}

	return $query_params;
}

/**
 * Get guidebook posts: all (default) or by term slug
 *
 * @param string  $term_slug   Optional. Guidebook's sections term slug. Default '' - equal all terms.
 * @param integer $numberposts Optional. Number of posts. Default 0 - equal all posts.
 * @return array Posts array.
 */
function get_guidebook_posts( $term_slug = '', $numberposts = 0 ) {
	$posts       = array();
	$tax_name    = 'sections';
	$term_slug_0 = 'sights';

	if ( ! $term_slug ) {
		$term_slug = $term_slug_0;
	}
	$check_filter = $term_slug === $term_slug_0;

	if ( $term_slug ) {
		$paged      = 1;
		$meta_query = array();

		if ( $check_filter ) {
			$query_params = excurs_get_query_params();
			if ( ! $numberposts ) {
				if ( isset( $query_params['numberposts_1'] ) ) {
					$numberposts = 1;
				}
				if ( isset( $query_params['numberposts_2'] ) ) {
					$numberposts = 2;
				}
			}
			if ( isset( $query_params['pagenum'] ) ) {
				$paged = intval( $query_params['pagenum'] );
			}
			if ( isset( $query_params['cat_f'] ) ) {
				$meta_query[] = array(
					'key'   => 'obj_info_protection_category',
					'value' => 'f',
				);
			}
		}

		$args = array(
			'post_type'   => 'guidebook',
			$tax_name     => $term_slug,
			'orderby'     => array(
				'meta_value_num' => 'DESC',
				'title'          => 'ASC',
			),
			'meta_key'    => 'gba_rating',
			'numberposts' => -1,
			'meta_query'  => $meta_query,
		);

		$posts = get_posts( $args );
		// посты с category_name = promo поднимаем вверх.
		$promo_posts = array();
		foreach ( $posts as $counter => $post ) {
			if ( in_category( 'promo', $post->ID ) ) {
				$promo_posts[] = $post;
				unset( $posts[ $counter ] );
			}
		}

		$posts = array_merge( $promo_posts, $posts );

		if ( $numberposts > 0 ) {
			$posts = array_slice( $posts, $numberposts * ( $paged - 1 ), $numberposts );
		}
	}

	return $posts;
}

/**
 * Remove unregistered query params from address bar in browser
 *
 * @return void
 */
function check_query_params() {
	$query_params = excurs_get_query_params();

	if ( count( $query_params ) ) {
		$allowed_keys = array( 'pagenum', 'cat_f', 'numberposts_1', 'numberposts_2' );
		parse_str( $parts['query'], $query_params );
		$start_query_params = $query_params;

		foreach ( $query_params as $key => $value ) {
			if ( ! in_array( $key, $allowed_keys, true ) ) {
				unset( $query_params[ $key ] );
			}
		}

		if ( is_page( 'guidebook' ) && isset( $query_params['pagenum'] ) ) {
			$myposts = get_guidebook_posts();
			if ( ! $myposts || 1 === intval( $query_params['pagenum'] ) ) {
				unset( $query_params['pagenum'] );
			}
		}

		if ( $start_query_params !== $query_params ) {
			$query_string = http_build_query( $query_params );
			if ( $query_string ) {
				$query_string = '?' . $query_string;
			}
			$url = home_url() . $parts['path'] . $query_string;
			header( 'Location: ' . $url, true, 301 );
			exit();
		}
	}
}
add_action( 'template_redirect', 'check_query_params' );

/**
 * Remove 'pagenum' query param from URL
 *
 * @return string URL without 'pagenum' query param. Empty string if error.
 */
function get_url_wo_pagenum() {
	if ( empty( $_SERVER['REQUEST_URI'] ) ) {
		return '';
	}

	$uri   = esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) );
	$parts = wp_parse_url( $uri );

	$query_params = array();
	$query_string = '';
	if ( isset( $parts['query'] ) ) {
		parse_str( $parts['query'], $query_params );
		unset( $query_params['pagenum'] );

		$query_string = http_build_query( $query_params );
		if ( $query_string ) {
			$query_string = '?' . $query_string;
		}
	}

	return home_url() . $parts['path'] . $query_string;
}

/**
 * "ACF Photo Gallery Field" Plugin extension - add post_parent to attachment
 * Fires off when the WordPress update button is clicked.
 *
 * @param int $post_id Post ID.
 * @return void
 */
function acf_photo_gallery_add_post_parent( $post_id ) {
	if ( ! wp_is_post_revision( $post_id ) ) {
		// unhook this function so it doesn't loop infinitely.
		remove_action( 'save_post', 'acf_photo_gallery_add_post_parent' );

		$field = isset( $_POST['acf-photo-gallery-groups'] ) ? $_POST['acf-photo-gallery-groups'] : null;
		if ( ! empty( $field ) ) {
			foreach ( $field as $k => $v ) {
				$field_id = isset( $_POST['acf-photo-gallery-groups'][ $k ] ) ? $_POST['acf-photo-gallery-groups'][ $k ] : null;
				if ( ! empty( $field_id ) ) {
					$ids = ! empty( $field ) && isset( $_POST[ $field_id ] ) ? $_POST[ $field_id ] : null;
					if ( ! empty( $ids ) ) {
						$post_type = get_post( $post_id )->post_type;
						foreach ( $ids as $attachment_id ) {
							$attachment_parent = get_post( $attachment_id )->post_parent;
							// Если у вложения нет post_parent, устанавливаем его равным id текущего поста.
							// Без проверки $post_type работает некорректно.
							if ( ( 'events' === $post_type || 'post' === $post_type || 'guidebook' === $post_type ) && 0 === $attachment_parent ) {
								// Обновляем данные в БД.
								wp_update_post(
									array(
										'ID'          => $attachment_id,
										'post_parent' => $post_id,
									)
								);
							}
						}
					}
				}
			}
		}

		// re-hook this function.
		add_action( 'save_post', 'acf_photo_gallery_add_post_parent' );
	}
}
add_action( 'save_post', 'acf_photo_gallery_add_post_parent' );

/**
 * Add Google Maps API key to ACF Plugin
 *
 * @param array $api Google Maps API array.
 * @return array Filtered Google Maps API array.
 */
function my_acf_google_map_api( $api ) {
	$api['key'] = GOOGLE_MAPS_API;
	return $api;
}
add_filter( 'acf/fields/google_map/api', 'my_acf_google_map_api' );

/**
 * Add string to $consolelog array
 *
 * @param string $str String need to output to console.
 * @return void
 */
function console_log( $str ) {
	global $consolelog;

	$consolelog .= $str . '\n';
}

/**
 * Output <script> tag with console.log()
 *
 * @return void
 */
function echo_console_log() {
	global $consolelog;

	if ( $consolelog ) {
		echo '<script>console.log("' . esc_html( $consolelog ) . '");</script>' . PHP_EOL;
	}
}
add_action( 'wp_footer', 'echo_console_log', 20 );
add_action( 'admin_footer', 'echo_console_log', 15 );

/**
 * Standard print_r function surraunded <pre> tags
 *
 * @param array $val Array to print.
 * @return void
 */
function print_r2( $val ) {
	echo '<pre>';
	print_r( $val );
	echo '</pre>';
}

/**
 * Remove editor field on 'map' page.
 */
function remove_fields_on_map_page() {
	if ( isset( $_GET['post'] ) && intval( $_GET['post'] ) === get_page_by_path( 'map' )->ID ) {
		remove_post_type_support( 'page', 'editor' );
	}
}
add_action( 'admin_init', 'remove_fields_on_map_page' );

/**
 * Generate newscards html
 *
 * @param integer $post_id         Post ID.
 * @param string  $alt_title       Optional. Alt title for the card. Default none.
 * @param boolean $show_event_date Optional. Shouls show event date. Default false.
 * @param boolean $show_attention  Optional. Shouls show attention. Default false.
 * @param string  $size            Optional. Size of card picture. Default 'medium_large'.
 * @param string  $read_more       Optional. Read more text. Default '[Перейти&nbsp;>>]'.
 * @return string Outer HTML.
 */
function excurs_get_newscard_html( $post_id, $alt_title = '', $show_event_date = false, $show_attention = false, $size = 'medium_large', $read_more = '[Перейти&nbsp;>>]' ) {
	$html           = '';
	$permalink      = get_the_permalink( $post_id );
	$title_attr     = excurs_get_the_title_for_attr( $post_id );
	$a_format       = '<a href="' . $permalink . '" title="' . $title_attr . '" %1$s>%2$s</a>';
	$event_date     = $show_event_date ? markup_event_date( $post_id ) : '';
	$newscard_title = $alt_title ? $alt_title : get_field( 'newscard-title', $post_id );
	if ( empty( $newscard_title ) ) {
		$newscard_title = get_the_title( $post_id );
	}

	$html .= '<div class="newscard-container col-md-6 col-lg-4"><div class="newscard">';
	$html .= sprintf( $a_format, '', get_attachment_picture( get_post_thumbnail_id( $post_id ), $size ) );
	if ( $show_attention ) {
		$html .= '<p class="attention">Не пропустите!</p>';
	}
	$html .= '<h3 class="newscard-title">' . $event_date . esc_html( $newscard_title ) . '</h3>';
	if ( $read_more ) {
		$html .= ' ' . sprintf( $a_format, 'tabindex="-1"', esc_html( $read_more ) );
	}
	$html .= '</div></div>';

	return $html;
}

require_once get_template_directory() . '/includes/template-carbon-fields.php';
require_once get_template_directory() . '/includes/template-shortcodes.php';
require_once get_template_directory() . '/includes/template-ajax-actions.php';
