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
			'themes' => esc_html__( 'Collections', 'giovanni' ),
			'featured' => esc_html__( 'Featured', 'giovanni' ),
			'men' => esc_html__( 'Men', 'giovanni' ),
			'women' => esc_html__( 'Women', 'giovanni' ),
			'charms' => esc_html__( 'Charms', 'giovanni' ),
			'charms-2' => esc_html__( 'Charms 2', 'giovanni' ),
			'gifts' => esc_html__( 'Gifts', 'giovanni' ),
			'about' => esc_html__( 'About', 'giovanni' ),
			'occasions' => esc_html__( 'Occasions', 'giovanni' ),
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
	add_image_size( '1920-560', 1920, 560, true );
	add_image_size( '1920-400', 1920, 400, true );
	add_image_size( '1780-full', 1920, 0, false );
	add_image_size( '1440-690', 1440, 690, true );
	add_image_size( '1440-500', 1440, 500, true );
	add_image_size( '1440-400', 1440, 400, true );
	add_image_size( '1400-865', 1400, 865, true );
	add_image_size( '1280-600', 1280, 600, true );
	add_image_size( '1280-267', 1280, 300, true );
	add_image_size( '1280-full', 1280, 0, false );
	add_image_size( '1184-865', 1184, 865, true );
	add_image_size( '812-812', 812, 812, true );
	add_image_size( '800-full', 800, 0, false);
	add_image_size( '800-980', 800, 980, array('center', 'center'));
	add_image_size( '768-865', 768, 865, true );
	add_image_size( '768-400', 768, 400, true );
	add_image_size( '745-516', 745, 516, true );
	add_image_size( '436-436', 436, 436, true );
	add_image_size( '424-424', 424, 424, true );
	add_image_size( '424-307', 424, 307, true );
	add_image_size( '382-382', 382, 382, true );
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

	// Disable automatic paragraph tags in Contact Form 7
	add_filter('wpcf7_autop_or_not', '__return_false');
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
    'before_title' => '<div class="accordion-row active"><h2>',
    'after_title' => '</h2><button class="js-accordion-trigger accordion-trigger" aria-controls="footer1" aria-expanded="false"><span class="toggle-icon plus"></span>	<span class="toggle-icon minus" style="display: none;"></span></button></div>',
    ));

    register_sidebar(array(
    'name' => esc_html__( 'Footer Widget 2', 'giovanni' ),
    'id' => 'footer-2',
    'description' => esc_html__( 'Second area', 'giovanni' ),
    'before_widget' => '<div class="wsfooterwdget">',
    'after_widget' => '</div>',
    'before_title' => '<div class="accordion-row"><h2>',
    'after_title' => '</h2><button class="js-accordion-trigger accordion-trigger" aria-controls="footer2" aria-expanded="true"><span class="toggle-icon plus"></span>	<span class="toggle-icon minus" style="display: none;"></span></button></div>',
    ));

    register_sidebar(array(
    'name' => esc_html__( 'Footer Widget 3', 'giovanni' ),
    'id' => 'footer-3',
    'description' => esc_html__( 'Third area', 'giovanni' ),
    'before_widget' => '<div class="wsfooterwdget">',
    'after_widget' => '</div>',
    'before_title' => '<div class="accordion-row"><h2>',
    'after_title' => '</h2><button class="js-accordion-trigger accordion-trigger" aria-controls="footer3" aria-expanded="true"><span class="toggle-icon plus"></span>	<span class="toggle-icon minus" style="display: none;"></span></button></div>',
    ));

  register_sidebar(array(
    'name' => esc_html__( 'Footer Widget 4', 'giovanni' ),
    'id' => 'footer-4',
    'description' => esc_html__( 'Fourth area', 'giovanni' ),
    'before_widget' => '<div class="wsfooterwdget">',
    'after_widget' => '</div>',
    'before_title' => '<div class="accordion-row"><h2>',
    'after_title' => '</h2><button class="js-accordion-trigger accordion-trigger" aria-controls="footer4" aria-expanded="true"><span class="toggle-icon plus"></span>	<span class="toggle-icon minus" style="display: none;"></span></button></div>',
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

	// script - slick slider https://kenwheeler.github.io/slick/
	wp_enqueue_script( 'slick', get_template_directory_uri() . '/assets/js/slick/slick.min.js', array(), _S_VERSION, true  );

	// script - theme
	wp_enqueue_script( 'giovanni-script', get_template_directory_uri() . '/assets/js/scripts.min.js', array('jquery'), _S_VERSION, true );

	// script - comment reply
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	global $wp_query;

	$category_id = '';
	if (is_product_category()) {
		$category = get_queried_object();
		$category_id = $category->term_id;
	}

	// Localize script with additional parameters
	wp_localize_script('giovanni-script', 'giovanni', [
		'ajax_url' => admin_url('admin-ajax.php'),
		'site_url' => get_home_url(),
		'current_page' => max(1, get_query_var('paged')),
		'max_page' => $wp_query->max_num_pages,
		'search_nonce' => wp_create_nonce('giovanni_search_nonce'),
		'product_filter_nonce' => wp_create_nonce('giovanni_product_filter_nonce'),
		'product_info_nonce' => wp_create_nonce('giovanni_product_info_nonce'),
		'registration_nonce'   => wp_create_nonce('register_nonce'),
		'gift_nonce' => wp_create_nonce('add_gift_card_action'),
		'current_category_id' => $category_id, // Include current category ID if on a product category page
	]);

	if (defined('DOING_AJAX') && DOING_AJAX) {
		nocache_headers();
	}

	// dequeue
	wp_dequeue_style( 'wp-block-library' );
	wp_dequeue_style( 'wp-block-library-theme' );
	wp_enqueue_style(
		'giovanni-slick-css',
		get_template_directory_uri() . '/assets/js/slick/slick.css',
		array(),
		'1.0.0'
	);
	// wp_dequeue_style( 'wc-blocks-style' ); // Remove WooCommerce block CSS

	if (class_exists('WooCommerce')) {
		wp_enqueue_script('wc-cart-fragments');
	}
}
add_action( 'wp_enqueue_scripts', 'giovanni_scripts' );


add_action('wp_head', function () {
	echo '<link rel="preconnect" href="https://fonts.googleapis.com">';
	echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>';
	echo '<link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Jost:wght@500&family=Libre+Caslon+Text&family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" onload="this.onload=null;this.rel=\'stylesheet\'">';
	echo '<noscript><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Jost:wght@500&family=Libre+Caslon+Text&family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap"></noscript>';
}, 1);

add_filter('wp_get_attachment_image_attributes', function($attr, $attachment = null, $size = null){
	if (is_admin()) return $attr;
	$class = isset($attr['class']) ? $attr['class'] : '';
	$is_hero = (strpos($class, 'is-hero') !== false);
	if ($is_hero) {
		$attr['loading'] = 'eager';
		$attr['fetchpriority'] = 'high';
	} else {
		$attr['loading'] = 'lazy';
		$attr['decoding'] = 'async';
	}
	if (!empty($class)) {
		if (strpos($class, 'collections-bg') !== false && strpos($class, 'page-desk') !== false) {
			$attr['sizes'] = '(max-width: 1920px) 100vw, 1920px';
		}
		if (strpos($class, 'main-post-image') !== false && strpos($class, 'main-post-image-desktop') !== false) {
			$attr['sizes'] = '(max-width: 1280px) 100vw, 1280px';
		}
	}
	if (empty($attr['sizes'])) {
		$attr['sizes'] = '100vw';
	}
	return $attr;
}, 10, 3);


add_filter('wp_calculate_image_srcset', function($sources, $size_array, $image_src, $image_meta, $attachment_id){
	if (is_admin() || !is_array($sources)) return $sources;
	$max_w = 1920;
	foreach ($sources as $w => $data) {
		if ($w > $max_w) unset($sources[$w]);
	}
	return $sources;
}, 10, 5);



add_filter('the_content', function($html){
	if (is_admin() || stripos($html, '<img') === false) return $html;
	$html = preg_replace('/<img\b((?:(?!\bdecoding=).)*?)>/i', '<img decoding="async"$1>', $html);
	$html = preg_replace('/<img\b((?:(?!\bloading=).)*?)>/i', '<img loading="lazy"$1>', $html);
	return $html;
}, 12);

add_action('wp_footer', function () {
	?>
	<script>
	(function(){
	  if(!('IntersectionObserver' in window)) return;
	  var els = [].slice.call(document.querySelectorAll('[data-bg]'));
	  if(!els.length) return;
	  var io = new IntersectionObserver(function(entries){
	    entries.forEach(function(e){
	      if(e.isIntersecting){
	        var el = e.target;
	        var url = el.getAttribute('data-bg');
	        if(url){ el.style.backgroundImage = 'url('+url+')'; el.removeAttribute('data-bg'); }
	        io.unobserve(el);
	      }
	    });
	  }, { rootMargin: '300px 0px' });
	  els.forEach(function(el){ io.observe(el); });
	})();
	</script>
	<?php
}, 99);

add_filter('script_loader_tag', function($tag, $handle, $src){
	if (is_admin()) return $tag;
	$tag = preg_replace('/\s(defer|async)(=("defer"|"async"))?/i','', $tag);
	$never_touch = array(
		'wp-i18n','wp-hooks','wp-element','wp-components','wp-api-fetch','wp-util','wp-a11y',
		'jquery','jquery-migrate','underscore',
		'wp-url','wp-dom-ready','wp-api-request','wp-api','wp-polyfill',
		'mediaelement-core','mediaelement-migrate','wp-mediaelement',
		'wp-blob','react','react-dom','react-jsx-runtime',
		'wp-private-apis','wp-escape-html',
		'media-views','media-models','media-editor','media-audiovideo',
		'wp-pointer','thickbox','wp-backbone','backbone','shortcode',
		'wc-cart-fragments',
		'contact-form-7','wpcf7-recaptcha','google-recaptcha','recaptcha','grecaptcha','g-recaptcha'
	);
	if (in_array($handle, $never_touch, true)) return $tag;
	$safe_defer = array('slick','giovanni-script');
	if (in_array($handle, $safe_defer, true) && !empty($src)) {
		if (!preg_match('/\sdefer(\s|=|>)/i', $tag)) {
			$tag = preg_replace('/<script\b/i', '<script defer', $tag, 1);
		}
	}
	return $tag;
}, 1000, 3);

/**
 * Add attributes to SCRIPT link
 *
 * @param [type] $tag
 * @param [type] $handle
 * @param [type] $src
 * @return void
 */
function add_attribs_to_scripts( $tag, $handle, $src ) {

	$async_scripts = array(
		'jquery-migrate',
		'sharethis',
	);

	$defer_scripts = array(
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

add_filter('posts_join', function ($join, \WP_Query $q) {
    if (is_admin()) return $join;

    $post_type = (array) $q->get('post_type');
    $is_products = in_array('product', $post_type, true) || $q->get('wc_query') === 'product_query';

    if ($q->is_search() && $is_products) {
        global $wpdb;
        $join .= "
            LEFT JOIN {$wpdb->postmeta} AS sku_pm
                ON sku_pm.post_id = {$wpdb->posts}.ID
               AND sku_pm.meta_key = '_sku'
            LEFT JOIN {$wpdb->posts} AS var_p
                ON var_p.post_parent = {$wpdb->posts}.ID
               AND var_p.post_type = 'product_variation'
            LEFT JOIN {$wpdb->postmeta} AS var_pm
                ON var_pm.post_id = var_p.ID
               AND var_pm.meta_key = '_sku'
        ";
    }
    return $join;
}, 10, 2);

add_filter('posts_where', function ($where, \WP_Query $q) {
    if (is_admin()) return $where;

    $post_type = (array) $q->get('post_type');
    $is_products = in_array('product', $post_type, true) || $q->get('wc_query') === 'product_query';

    if ($q->is_search() && $is_products) {
        global $wpdb;
        $s_raw = $q->get('s');
        if ($s_raw !== '') {
            $like = '%' . $wpdb->esc_like(wc_clean(wp_unslash($s_raw))) . '%';
            $where .= $wpdb->prepare(
                " OR (sku_pm.meta_value LIKE %s) OR (var_pm.meta_value LIKE %s)",
                $like, $like
            );
        }
    }
    return $where;
}, 10, 2);

add_filter('posts_distinct', function ($distinct, \WP_Query $q) {
    if (is_admin()) return $distinct;

    $post_type = (array) $q->get('post_type');
    $is_products = in_array('product', $post_type, true) || $q->get('wc_query') === 'product_query';

    if ($q->is_search() && $is_products) {
        return 'DISTINCT';
    }
    return $distinct;
}, 10, 2);

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
 * Registration.
 */
require get_template_directory() . '/inc/__registration.php';

/**
 * User Profile.
 */
require get_template_directory() . '/inc/__user-profile.php';

/**
 * Gift Card.
 */
require get_template_directory() . '/inc/__gift-card.php';

/**
 * Woocommerce.
 */
require get_template_directory() . '/inc/__woocommerce.php';



// Register custom email
add_filter( 'woocommerce_email_classes', function( $emails ) {

    require_once get_stylesheet_directory() . '/class-wc-email-custom-invoice.php';

    $emails['WC_Email_Custom_Invoice'] = new WC_Email_Custom_Invoice();
    return $emails;
});

// Add action button to order actions
add_filter( 'woocommerce_order_actions', function( $actions ) {
    $actions['send_custom_invoice'] = __('Send Payment Invoice', 'giovanni');
    return $actions;
});

// Handle action trigger
add_action( 'woocommerce_order_action_send_custom_invoice', function( $order_id ) {

    $mailer = WC()->mailer();
    $emails = $mailer->get_emails();

    if ( isset( $emails['WC_Email_Custom_Invoice'] ) ) {
        $emails['WC_Email_Custom_Invoice']->trigger( $order_id );
    }
});

