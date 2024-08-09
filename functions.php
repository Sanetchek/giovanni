<?php
/**
 * giovanni functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package giovanni
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function giovanni_setup() {
	/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on giovanni, use a find and replace
		* to change 'giovanni' to the name of your theme in all the template files.
		*/
	load_theme_textdomain( 'giovanni', get_template_directory() . '/languages' );

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

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'menu-1' => esc_html__( 'Primary', 'giovanni' ),
			'footer' => esc_html__( 'Footer', 'giovanni' ),
			'footer-2' => esc_html__( 'Footer-2', 'giovanni' ),
			'themes' => esc_html__( 'Themes', 'giovanni' ),
			'featured' => esc_html__( 'Featured', 'giovanni' ),
			'men' => esc_html__( 'Men', 'giovanni' ),
			'women' => esc_html__( 'Women', 'giovanni' ),
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
			'style',
			'script',
		)
	);

	// Set up the WordPress core custom background feature.
	add_theme_support(
		'custom-background',
		apply_filters(
			'giovanni_custom_background_args',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	// Add Image Sizes
	add_image_size( '1920-920', 1920, 920, true );
	add_image_size( '1920-865', 1920, 865, true );
	add_image_size( '1920-400', 1920, 400, true );
	add_image_size( '1440-690', 1440, 690, true );
	add_image_size( '1440-400', 1440, 400, true );
	add_image_size( '1400-865', 1400, 865, true );
	add_image_size( '1280-600', 1280, 600, true );
	add_image_size( '1280-267', 1280, 300, true );
	add_image_size( '1184-865', 1184, 865, true );
	add_image_size( '880-880', 880, 880, true );
	add_image_size('800-full', 800, 0, false);
	add_image_size('800-980', 800, 980, array('center', 'center'));
	add_image_size( '768-400', 768, 400, true );
	add_image_size( '745-516', 745, 516, true );
	add_image_size( '436-436', 436, 436, true );
	add_image_size( '424-424', 424, 424, true );
	add_image_size( '424-307', 424, 307, true );
	add_image_size( '372-372', 372, 372, true );
	add_image_size( '360-400', 360, 400, true );
	add_image_size( '350-350', 350, 350, true );

	/**
	 * Remove WordPress Meta Generator
	 */
	remove_action('wp_head', 'rsd_link');
	remove_action('wp_head', 'wp_generator');
	remove_action('wp_head', 'feed_links', 2);
	remove_action('wp_head', 'index_rel_link');
	remove_action('wp_head', 'wlwmanifest_link');
	remove_action('wp_head', 'feed_links_extra', 3);
	remove_action('wp_head', 'start_post_rel_link', 10);
	remove_action('wp_head', 'parent_post_rel_link', 10);
	remove_action('wp_head', 'wp_shortlink_wp_head', 10);
	remove_action('wp_head', 'adjacent_posts_rel_link', 10);
	remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10);
	remove_action('wp_head', 'print_emoji_detection_script', 7);
}
add_action( 'after_setup_theme', 'giovanni_setup' );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function giovanni_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'giovanni' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'giovanni' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);

	register_sidebar(array(
    'name' => esc_html__( 'Footer Widget 1', 'giovanni' ),
    'id' => 'footer-1',
    'description' => esc_html__( 'First area', 'giovanni' ),
    'before_widget' => '<div class="wsfooterwdget">',
    'after_widget' => '</div>',
    'before_title' => '<h2>',
    'after_title' => '</h2>',
    ));

    register_sidebar(array(
    'name' => esc_html__( 'Footer Widget 2', 'giovanni' ),
    'id' => 'footer-2',
    'description' => esc_html__( 'Second area', 'giovanni' ),
    'before_widget' => '<div class="wsfooterwdget">',
    'after_widget' => '</div>',
    'before_title' => '<h2>',
    'after_title' => '</h2>',
    ));

    register_sidebar(array(
    'name' => esc_html__( 'Footer Widget 3', 'giovanni' ),
    'id' => 'footer-3',
    'description' => esc_html__( 'Third area', 'giovanni' ),
    'before_widget' => '<div class="wsfooterwdget">',
    'after_widget' => '</div>',
    'before_title' => '<h2>',
    'after_title' => '</h2>',
    ));

  register_sidebar(array(
    'name' => esc_html__( 'Footer Widget 4', 'giovanni' ),
    'id' => 'footer-4',
    'description' => esc_html__( 'Fourth area', 'giovanni' ),
    'before_widget' => '<div class="wsfooterwdget">',
    'after_widget' => '</div>',
    'before_title' => '<h2>',
    'after_title' => '</h2>',
  ));
}
add_action( 'widgets_init', 'giovanni_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function giovanni_scripts() {
	// main styles
	wp_enqueue_style( 'giovanni-style', get_stylesheet_uri(), array(), _S_VERSION );
	wp_style_add_data( 'giovanni-style', 'rtl', 'replace' );

	// script - jquery
	wp_enqueue_script('jquery');

	// script - aos animation https://michalsnik.github.io/aos/
	wp_enqueue_script( 'aos', get_template_directory_uri() . '/assets/js/aos-next/aos.js', array(), _S_VERSION, true  );

	// script - slick slider https://kenwheeler.github.io/slick/
	wp_enqueue_script( 'slick', get_template_directory_uri() . '/assets/js/slick/slick.min.js', array(), _S_VERSION, true  );

	// script - theme
	wp_enqueue_script( 'giovanni-script', get_template_directory_uri() . '/assets/js/script.min.js', array('jquery'), _S_VERSION, true );

	// script - comment reply
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	global $wp_query;

	// wordpress localize
	wp_localize_script('giovanni-script', 'giovanni', [
		'ajax_url' => admin_url('admin-ajax.php'),
		'site_url' => get_home_url(),
		'current_page' => max( 1, get_query_var('paged') ),
		'max_page' => $wp_query->max_num_pages
	]);

	// dequeue
	wp_dequeue_style( 'wp-block-library' );
	wp_dequeue_style( 'wp-block-library-theme' );
	// wp_dequeue_style( 'wc-blocks-style' ); // Remove WooCommerce block CSS
}
add_action( 'wp_enqueue_scripts', 'giovanni_scripts' );

/**
 * Add attributes to SCRIPT link
 *
 * @param [type] $tag
 * @param [type] $handle
 * @param [type] $src
 * @return void
 */
function add_attribs_to_scripts( $tag, $handle, $src ) {

	// The handles of the enqueued scripts we want to defer
	$async_scripts = array(
		'jquery-migrate',
		'sharethis',
	);

	$defer_scripts = array(
		'contact-form-7',
		'jquery-form',
		'wpdm-bootstrap',
		'frontjs',
		'jquery-choosen',
		'fancybox',
		'jquery-colorbox',
		'search'
	);

	$jquery = array(
		'jquery'
	);

	if ( in_array( $handle, $defer_scripts ) ) {
		return '<script src="' . $src . '" defer="defer" type="text/javascript"></script>' . "\n";
	}
	if ( in_array( $handle, $async_scripts ) ) {
		return '<script src="' . $src . '" async="async" type="text/javascript"></script>' . "\n";
	}
	if ( in_array( $handle, $jquery ) ) {
		return '<script src="' . $src . '" integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous" type="text/javascript"></script>' . "\n";
	}

	return $tag;
}
add_filter( 'script_loader_tag', 'add_attribs_to_scripts', 10, 3 );

/**
 * Actions.
 */
require get_template_directory() . '/inc/__actions.php';

/**
 * Filters.
 */
require get_template_directory() . '/inc/__filters.php';

/**
 * Ajax.
 */
require get_template_directory() . '/inc/__ajax.php';

/**
 * ACF functionality.
 */
require get_template_directory() . '/inc/__acf.php';

/**
 * Likes functionality.
 */
require get_template_directory() . '/inc/__likes.php';

/**
 * Custom functionality.
 */
require get_template_directory() . '/inc/__custom-functions.php';

/**
 * Customizer.
 */
require get_template_directory() . '/inc/__customizer.php';

/**
 * Woocommerce.
 */
require get_template_directory() . '/inc/__woocommerce.php';