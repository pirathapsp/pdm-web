<?php
/**
 * AccessPress Store Theme Customizer
 *
 * @package AccessPress Store
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function accesspress_store_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	/*------------------------------------------------------------------------------------*/
	/**
	 * Upgrade to Uncode Pro
	*/
	// Register custom section types.
	$wp_customize->register_section_type( 'AccessPress_Store_Customize_Section_Pro' );

	// Register sections.
	$wp_customize->add_section(
	    new AccessPress_Store_Customize_Section_Pro(
	        $wp_customize,
	        'accesspress-store-pro',
	        array(
	            'title1'    => esc_html__( 'Free Vs Pro', 'accesspress-store' ),
	            'pro_text1' => esc_html__( 'Compare','accesspress-store' ),
	            'pro_url1'  => admin_url( 'themes.php?page=accesspressstore-welcome&section=free_vs_pro'),
	            'priority' => 1,
	        )
	    )
	);
	$wp_customize->add_setting(
		'revolve_pro_upbuton',
		array(
			'section' => 'accesspress-store-pro',
			'sanitize_callback' => 'esc_attr',
		)
	);

	$wp_customize->add_control(
		'revolve_pro_upbuton',
		array(
			'section' => 'accesspress-store-pro'
		)
	);

}
add_action( 'customize_register', 'accesspress_store_customize_register' );


/**
 * Load Sanitizer Functions
*/
require get_template_directory() . '/inc/customizer/accesspress-sanitizer.php';

/**
 * Load Custom Control Customizer Class
*/
require get_template_directory() . '/inc/customizer/custom-control-class.php';


/**
 * Load General Setting
*/
require get_template_directory() . '/inc/customizer/general-settings/general-setting.php';

/**
 * Load Slider Setting
*/
require get_template_directory() . '/inc/customizer/slider-settings/slider-setting.php';

/**
 * Load Woocommerce Setting
*/
require get_template_directory() . '/inc/customizer/woocommerce-settings/woocommerce-setting.php';

/**
 * Load Page/Post Setting
*/
require get_template_directory() . '/inc/customizer/layout-settings/pagepost-setting.php';

/**
 * Load Page/Post Setting
*/
require get_template_directory() . '/inc/customizer/blog-settings/blog-setting.php';

/**
 * Load Page/Post Setting
*/
require get_template_directory() . '/inc/customizer/paymentlogo-settings/paymentlogo-setting.php';


/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
*/
function accesspress_store_customize_preview_js() {    
	wp_enqueue_script( 'accesspress_store_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20130508', true );
}
add_action( 'customize_preview_init', 'accesspress_store_customize_preview_js' );