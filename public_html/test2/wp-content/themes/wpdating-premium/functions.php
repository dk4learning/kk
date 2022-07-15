<?php
/**
 * WPDating Premium functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package WPDating_Premium
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}

define( 'CS_ACTIVE_FRAMEWORK',   true  ); // default true
define( 'CS_ACTIVE_METABOX',     false ); // default true
define( 'CS_ACTIVE_TAXONOMY',    false ); // default true
define( 'CS_ACTIVE_SHORTCODE',   false ); // default true
define( 'CS_ACTIVE_CUSTOMIZE',   false ); // default true

flush_rewrite_rules( false );


if ( ! function_exists( 'wpdating_premium_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */

	function wpdating_premium_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on WPDating Premium, use a find and replace
		 * to change 'wpdating-premium' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'wpdating-premium', get_template_directory() . '/languages' );

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

		/*  Image thumbnail sizes
			/* ------------------------------------ */
			    

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus(
			array(
				'menu-1' => esc_html__( 'Primary', 'wpdating-premium' ),
			)
		);
		add_action( 'init', 'register_footer_menus' );
			function register_footer_menus() {  
			    register_nav_menus(
			        array(
			            'footer-nav' => __( 'Footer Menu' )
			        )
			    );
			}

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

		add_image_size( 'thumb-small', 200, 200, true ); // Hard crop to exact dimensions (crops sides or top and bottom)
	    add_image_size( 'thumb-medium', 450, 500 ); // Crop to 520px width, unlimited height
	    add_image_size( 'thumb-medium-square', 450, 450, true ); // Crop 450px square image
	    add_image_size( 'thumb-large', 720, 340 ); // Soft proprtional crop to max 720px width, max 340px height
	    add_image_size( 'user-stories-big', 600, 853, true ); // Crop 450px square image
	    add_image_size( 'user-stories-small', 530, 245, true ); // Crop 450px square image

		// Set up the WordPress core custom background feature.
		add_theme_support(
			'custom-background',
			apply_filters(
				'wpdating_premium_custom_background_args',
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

		// This will check elementor option in setting on every reload 
		// Admin Dashboard -> Elementor -> Setting -> General
		update_option( 'elementor_disable_color_schemes', 'yes' ); // Disable Default Colors
		update_option( 'elementor_disable_typography_schemes', 'yes' ); // Disable Default Fonts

	}
endif;
add_action( 'after_setup_theme', 'wpdating_premium_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function wpdating_premium_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'wpdating_premium_content_width', 640 );
}
add_action( 'after_setup_theme', 'wpdating_premium_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function wpdating_premium_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'wpdating-premium' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'wpdating-premium' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'wpdating_premium_widgets_init' );


    /**
    * Adds customizable styles to your <head>
    */
	// function wpdating_premium_customize_css()
	// {
	// 	get_template_part('inc/theme-options');

	// }
	// add_action( 'wp_head', 'wpdating_premium_customize_css');
	function wpdating_premium_customize_css()
	{
		ob_start();
		get_template_part('inc/theme-options');
		$css = ob_get_clean();
	    $css = wpdating_premium_css_strip_whitespace(apply_filters('wpdating_premium_dynamic_css', $css));
	    wp_add_inline_style('responsive', $css);
	}
	add_action('wp_enqueue_scripts', 'wpdating_premium_customize_css', 999);

/**
 * Enqueue scripts and styles.
 */
function wpdating_premium_scripts() {
	wp_enqueue_style( 'wpdating-premium-style', get_stylesheet_uri(), array(), _S_VERSION );
	wp_style_add_data( 'wpdating-premium-style', 'rtl', 'replace' );
	wp_enqueue_style('slick', get_template_directory_uri() .'/css/slick.css' );
	wp_enqueue_style('slick-theme', get_template_directory_uri() .'/css/slick-theme.css' );
	wp_enqueue_style('responsive', get_template_directory_uri() .'/css/responsive.css' );
	wp_dequeue_style('wpdating-gallery-bootstrap');


	wp_enqueue_script( 'wpdating-premium-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true );
	wp_enqueue_script( 'slick js', get_template_directory_uri() . '/js/slick.js', array(), _S_VERSION, true );
	wp_enqueue_script( 'custom js', get_template_directory_uri() . '/js/custom.js', array(), _S_VERSION, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'wpdating_premium_scripts' );



require_once get_template_directory() .'/inc/cs-framework/cs-framework.php';

//Enqueue Color Picker
add_action( 'admin_enqueue_scripts', 'mw_enqueue_color_picker' );
function mw_enqueue_color_picker( $hook_suffix ) {
    // first check that $hook_suffix is appropriate for your admin page
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script( 'wpdating-premium-navigation', get_template_directory_uri() . '/js/color-picker.js', array('wp-color-picker'), _S_VERSION, true );
}

/**
 * Implement the Custom Header feature.
 */
//require get_template_directory() . '/inc/custom-header.php';

//require_once get_template_directory() .'/inc/cs-framework/cs-framework.php';


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

//require get_template_directory() . '/inc/theme-options.php';


/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

// shortcodes section start

require_once get_template_directory() . '/inc/shortcodes/blog-post-shortcodes.php';
require_once get_template_directory() . '/inc/shortcodes/user-story-shortcodes.php';
require_once get_template_directory() . '/inc/shortcodes/testimonials-shortcodes.php';
// shortcodes section end

require_once get_template_directory() . '/inc/social-links-functions.php';
require_once get_template_directory() . '/widgets/footer-widgets.php';


// add back to top button in wordpress footer
$back_to_top = cs_get_option( 'back-to-top' );
	if( $back_to_top ){
		function wcs_add_back_to_top() {
		    echo '<a id="toTop" href="#"><i class="fa fa-chevron-up" aria-hidden="true"></i></a>';
		}
		add_action( 'wp_footer', 'wcs_add_back_to_top' );
	}


/**
 * Strip whitespace in dynamic style
 */
if (!function_exists('wpdating_premium_css_strip_whitespace')) {
	function wpdating_premium_css_strip_whitespace($css)
	{
		$replace = array(
			"#/\*.*?\*/#s" => "",  // Strip C style comments.
			"#\s\s+#"      => " ", // Strip excess whitespace.
		);
		$search = array_keys($replace);
		$css = preg_replace($search, $replace, $css);

		$replace = array(
			": "  => ":",
			"; "  => ";",
			" {"  => "{",
			" }"  => "}",
			", "  => ",",
			"{ "  => "{",
			";}"  => "}", // Strip optional semicolons.
			",\n" => ",", // Don't wrap multiple selectors.
			"\n}" => "}", // Don't wrap closing braces.
			//"} "  => "}\n", // Put each rule on it's own line.
		);
		$search = array_keys($replace);
		$css = str_replace($search, $replace, $css);

		return trim($css);
	}
}