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

		add_image_size( 'anno-thumb', 600, 400, true );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
			'menu-1' => esc_html__( 'Primary', 'excursions' ),
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
    wp_enqueue_style( 'slick', get_template_directory_uri() . '/assets/include/slick.css' );
    wp_enqueue_style( 'slick-theme', get_template_directory_uri() . '/assets/include/slick-theme.css' );
    // wp_enqueue_style( 'main', href="https://fonts.googleapis.com/css?family=Ubuntu:300,400&amp;subset=cyrillic" );
	wp_enqueue_style( 'main', get_template_directory_uri() . '/assets/css/main.css' );

	wp_deregister_script( 'jquery' );
	// wp_register_script( 'jquery', get_template_directory_uri() . '/assets/include/jquery-3.3.1.min.js' );
	wp_enqueue_script( 'jquery', get_template_directory_uri() . '/assets/include/jquery-3.3.1.min.js', array(), false, 'in_footer' );
	wp_enqueue_script( 'bootstrap', get_template_directory_uri() . '/assets/include/bootstrap.bundle.min.js', array('jquery'),false,'in_footer' );
	wp_enqueue_script( 'slick', get_template_directory_uri() . '/assets/include/slick.min.js', array('jquery'),false,'in_footer' );
	wp_enqueue_script( 'script', get_template_directory_uri() . '/assets/js/script.js', array('jquery'),false,'in_footer' );
}
add_action( 'wp_enqueue_scripts', 'excursions_scripts' );


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

// использование: [annocards post_type = "post" cat_name="event" section_title="Приходите"] 

function annocards_func( $atts ){
	// белый список параметров и значения по умолчанию
	$atts = shortcode_atts( array(
		'post_type' => 'post',
		'cat_name' => '',
		'section_title' => '',
		'read_more' => 'Подробнее...'
	), $atts );

	$post_type = $atts['post_type'];
	$cat_name = $atts['cat_name'];
	$section_title = $atts['section_title'];
	$read_more = $atts['read_more'];
	$echo = '';

	global $post;
	$args = array( 'post_type' => $post_type, 'category_name' => $cat_name );
	$myposts = get_posts( $args );
	if ( $myposts ): 
		$echo .= '<section role="' . $cat_name . '"><div class="row section-container"><div class="col">';
		$echo .= '<h2>' . $section_title . '</h2>';
		foreach( $myposts as $post ):
			setup_postdata( $post );
			$permalink = get_the_permalink(); 
			$title = esc_html( get_the_title() );
			$echo .= '<div class="row anno-card"><div class="col-12 col-md-4"><a href="' . $permalink . '" tabindex="-1">'; 
			$echo .= get_the_post_thumbnail(null, 'medium');
			$echo .= '</a></div><div class="col-12 col-md-8"><h3><a href="' . $permalink . '" title="Ссылка на: '; 
			$echo .= $title . '">' . $title . '</a></h3><p>' . get_the_excerpt() . '  ';
			$echo .= '<a href="' . $permalink . '" tabindex="-1">' . $read_more . '</a></p></div></div>';
			wp_reset_postdata();
		endforeach;
		$echo .= '</div></div></section>';
	endif;

	return $echo;
}