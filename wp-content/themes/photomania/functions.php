<?php
/**
 * Theme functions and definitions.
 *
 * @link https://codex.wordpress.org/Functions_File_Explained
 *
 * @package Photomania
 */

if ( ! function_exists( 'photomania_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function photomania_setup() {

		// Make theme available for translation.
		load_theme_textdomain( 'photomania' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		// Let WordPress manage the document title.
		add_theme_support( 'title-tag' );

		// Enable support for Post Thumbnails on posts and pages.
		add_theme_support( 'post-thumbnails' );

		// Register nav menu locations.
		register_nav_menus( array(
			'primary'  => esc_html__( 'Primary Menu', 'photomania' ),
			'footer'   => esc_html__( 'Footer Menu', 'photomania' ),
			'social'   => esc_html__( 'Social Menu', 'photomania' ),
			'notfound' => esc_html__( '404 Menu', 'photomania' ),
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
		add_theme_support( 'custom-background', apply_filters( 'photomania_custom_background_args', array(
			'default-color' => 'f6f6f6',
			'default-image' => '',
		) ) );

		// Enable support for selective refresh of widgets in Customizer.
		add_theme_support( 'customize-selective-refresh-widgets' );

		// Enable support for custom logo.
		add_theme_support( 'custom-logo' );

		// Enable support for footer widgets.
		add_theme_support( 'footer-widgets', 4 );

		// Load Supports.
		require get_template_directory() . '/inc/support.php';

		global $photomania_default_options;
		$photomania_default_options = photomania_get_default_theme_options();

	}
endif;

add_action( 'after_setup_theme', 'photomania_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function photomania_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'photomania_content_width', 640 );
}
add_action( 'after_setup_theme', 'photomania_content_width', 0 );

/**
 * Register widget area.
 */
function photomania_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Primary Sidebar', 'photomania' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here to appear in your Primary Sidebar.', 'photomania' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'Secondary Sidebar', 'photomania' ),
		'id'            => 'sidebar-2',
		'description'   => esc_html__( 'Add widgets here to appear in your Secondary Sidebar.', 'photomania' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

}
add_action( 'widgets_init', 'photomania_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function photomania_scripts() {

	$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

	wp_enqueue_style( 'font-awesome', get_template_directory_uri() . '/third-party/font-awesome/css/font-awesome' . $min . '.css', '', '4.7.0' );

	$fonts_url = photomania_fonts_url();
	if ( ! empty( $fonts_url ) ) {
		wp_enqueue_style( 'photomania-google-fonts', $fonts_url, array(), null );
	}

	wp_enqueue_style( 'jquery-sidr', get_template_directory_uri() .'/third-party/sidr/css/jquery.sidr.dark' . $min . '.css', '', '2.2.1' );

	wp_enqueue_style( 'jquery-magnific-popup', get_template_directory_uri() .'/third-party/magnific-popup/css/magnific-popup' . $min . '.css', '', '1.1.0' );

	wp_enqueue_style( 'photomania-style', get_stylesheet_uri(), array(), '1.0.9' );

	wp_enqueue_script( 'photomania-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix' . $min . '.js', array(), '20130115', true );

	wp_enqueue_script( 'jquery-cycle2', get_template_directory_uri() . '/third-party/cycle2/js/jquery.cycle2' . $min . '.js', array( 'jquery' ), '2.1.6', true );

	wp_enqueue_script( 'jquery-sidr', get_template_directory_uri() . '/third-party/sidr/js/jquery.sidr' . $min . '.js', array( 'jquery' ), '2.2.1', true );

	wp_enqueue_script( 'jquery-magnific-popup', get_template_directory_uri() . '/third-party/magnific-popup/js/jquery.magnific-popup' . $min . '.js', array( 'jquery' ), '1.1.0', true );

	wp_enqueue_script( 'photomania-custom', get_template_directory_uri() . '/js/custom' . $min . '.js', array( 'jquery', 'wp-util', 'masonry' ), '1.0.0', true );
	$custom_args = array(
		'go_to_top_status' => ( true === photomania_get_option( 'go_to_top' ) ) ? 1 : 0,
		);
	$custom_args['ajaxurl'] = admin_url( 'admin-ajax.php' );
	wp_localize_script( 'photomania-custom', 'Photomania_Custom_Options', $custom_args );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'photomania_scripts' );

/**
 * Enqueue admin scripts and styles.
 */
function photomania_admin_scripts( $hook ) {

	$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

	if ( in_array( $hook, array( 'post.php', 'post-new.php' ) ) ) {
		wp_enqueue_style( 'photomania-metabox', get_template_directory_uri() . '/css/metabox' . $min . '.css', '', '1.0.0' );
		wp_enqueue_script( 'photomania-custom-admin', get_template_directory_uri() . '/js/admin' . $min . '.js', array( 'jquery', 'jquery-ui-core', 'jquery-ui-tabs' ), '1.0.0', true );
	}

	if ( 'widgets.php' === $hook ) {
		wp_enqueue_style( 'wp-color-picker' );
	    wp_enqueue_script( 'wp-color-picker' );
	    wp_enqueue_media();
		wp_enqueue_style( 'photomania-custom-widgets-style', get_template_directory_uri() . '/css/widgets' . $min . '.css', array(), '1.0.0' );
		wp_enqueue_script( 'photomania-custom-widgets', get_template_directory_uri() . '/js/widgets' . $min . '.js', array( 'jquery' ), '1.0.0', true );
	}

}
add_action( 'admin_enqueue_scripts', 'photomania_admin_scripts' );

/**
 * Load init.
 */
require_once get_template_directory() . '/inc/init.php';
