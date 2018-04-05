<?php
/**
 * Header logo/text display options alternative
 *
 * @since AcmePhoto 1.0.0
 *
 * @param null
 * @return array $acmephoto_header_id_display_opt
 *
 */
if ( !function_exists('acmephoto_header_id_display_opt') ) :
    function acmephoto_header_id_display_opt() {
        $acmephoto_header_id_display_opt =  array(
            'logo-only'         => __( 'Logo Only ( First Select Logo Above )', 'acmephoto' ),
            'title-only'        => __( 'Site Title Only', 'acmephoto' ),
            'title-and-tagline' =>  __( 'Site Title and Tagline', 'acmephoto' ),
            'disable'           => __( 'Disable', 'acmephoto' )
        );
        return apply_filters( 'acmephoto_header_id_display_opt', $acmephoto_header_id_display_opt );
    }
endif;

/**
 * Global layout options
 *
 * @since AcmePhoto 1.0.0
 *
 * @param null
 * @return array $acmephoto_default_layout
 *
 */
if ( !function_exists('acmephoto_default_layout') ) :
    function acmephoto_default_layout() {
        $acmephoto_default_layout =  array(
            'fullwidth' => __( 'Fullwidth', 'acmephoto' ),
            'boxed'     => __( 'Boxed', 'acmephoto' )
        );
        return apply_filters( 'acmephoto_default_layout', $acmephoto_default_layout );
    }
endif;

/**
 * Sidebar layout options
 *
 * @since AcmePhoto 1.0.0
 *
 * @param null
 * @return array $acmephoto_sidebar_layout
 *
 */
if ( !function_exists('acmephoto_sidebar_layout') ) :
    function acmephoto_sidebar_layout() {
        $acmephoto_sidebar_layout =  array(
            'right-sidebar' => __( 'Right Sidebar', 'acmephoto' ),
            'left-sidebar'  => __( 'Left Sidebar' , 'acmephoto' ),
            'no-sidebar'    => __( 'No Sidebar', 'acmephoto' )
        );
        return apply_filters( 'acmephoto_sidebar_layout', $acmephoto_sidebar_layout );
    }
endif;

/**
 * Pagination Options
 *
 * @since AcmePhoto 1.0.0
 *
 * @param null
 * @return array $acmephoto_pagination_options
 *
 */
if ( !function_exists('acmephoto_pagination_options') ) :
    function acmephoto_pagination_options() {
        $acmephoto_pagination_options =  array(
            'default'  => __( 'Default', 'acmephoto' ),
            'numeric'  => __( 'Ajax Loading', 'acmephoto' )
        );
        return apply_filters( 'acmephoto_pagination_options', $acmephoto_pagination_options );
    }
endif;

/**
 * Button Options
 *
 * @since AcmePhoto 1.0.0
 *
 * @param null
 * @return array acmephoto_feature_button_options
 *
 */
if ( !function_exists('acmephoto_feature_button_options') ) :
    function acmephoto_feature_button_options() {
        $acmephoto_feature_button_options =  array(
            'search'  => __( 'Show Search', 'acmephoto' ),
            'read-more'  => __( 'Show Read More', 'acmephoto' ),
            'hide'  => __( 'Hide', 'acmephoto' )
        );
        return apply_filters( 'acmephoto_feature_button_options', $acmephoto_feature_button_options );
    }
endif;

/**
 * Button Options
 *
 * @since AcmePhoto 1.0.0
 *
 * @param null
 * @return array acmephoto_menu_position_options
 *
 */
if ( !function_exists('acmephoto_menu_position_options') ) :
    function acmephoto_menu_position_options() {
        $acmephoto_menu_position_options =  array(
            'top-normal'  => __( 'Top Normal', 'acmephoto' ),
            'top-fixed'  => __( 'Top Fixed', 'acmephoto' ),
            'below-feature'  => __( 'Below Feature', 'acmephoto' )
        );
        return apply_filters( 'acmephoto_menu_position_options', $acmephoto_menu_position_options );
    }
endif;

/**
 * 
 * Reset post
 *
 * @since AcmePhoto 1.0.0
 *
 * @param null
 * @return array
 *
 */
if ( !function_exists('acmephoto_reset_options') ) :
    function acmephoto_reset_options() {
        $acmephoto_reset_options =  array(
            '0'                    => __( 'Do Not Reset', 'acmephoto' ),
            'reset-color-options'  => __( 'Reset Colors Options', 'acmephoto' ),
            'reset-all'            => __( 'Reset All', 'acmephoto' )
        );
        return apply_filters( 'acmephoto_reset_options', $acmephoto_reset_options );
    }
endif;

/**
 * Related Post Display From Options
 *
 * @since Acmephoto 1.2.0
 *
 * @param null
 * @return array
 *
 */
if ( !function_exists('acmephoto_related_post_display_from') ) :
	function acmephoto_related_post_display_from() {
		$acmephoto_related_post_display_from =  array(
			'cat'  => __( 'Related Posts From Categories', 'acmephoto' ),
			'tag'  => __( 'Related Posts From Tags', 'acmephoto' )
		);
		return apply_filters( 'acmephoto_related_post_display_from', $acmephoto_related_post_display_from );
	}
endif;

if ( !function_exists('acmephoto_get_image_sizes_options') ) :
	function acmephoto_get_image_sizes_options( $add_disable = false ) {
		global $_wp_additional_image_sizes;
		$choices = array();
		if ( true == $add_disable ) {
			$choices['disable'] = __( 'No Image', 'acmephoto' );
		}
		foreach ( array( 'thumbnail', 'medium', 'large' ) as $key => $_size ) {
			$choices[ $_size ] = $_size . ' ('. get_option( $_size . '_size_w' ) . 'x' . get_option( $_size . '_size_h' ) . ')';
		}
		$choices['full'] = __( 'full (original)', 'acmephoto' );
		if ( ! empty( $_wp_additional_image_sizes ) && is_array( $_wp_additional_image_sizes ) ) {

			foreach ($_wp_additional_image_sizes as $key => $size ) {
				$choices[ $key ] = $key . ' ('. $size['width'] . 'x' . $size['height'] . ')';
			}
		}
		return apply_filters( 'acmephoto_get_image_sizes_options', $choices );
	}
endif;

/**
 *  Default Theme Options
 *
 * @since AcmePhoto 1.0.0
 *
 * @param null
 * @return array $acmephoto_default_theme_options
 *
 */
if ( !function_exists('acmephoto_get_default_theme_options') ) :
    function acmephoto_get_default_theme_options() {

        $default_theme_options = array(
            /*feature section options*/
            'acmephoto-enable-feature'       => 1,
            'acmephoto-feature-enable-social'       => '',
            'acmephoto-feature-page'         => 0,
            'acmephoto-feature-height'       => 60,
            'acmephoto-feature-button-option'=> 'search',

            /*header options*/
            'acmephoto-header-logo'          => '',
            'acmephoto-header-id-display-opt'=> 'title-and-tagline',
            'acmephoto-menu-position-options'=> 'top-normal',
            'acmephoto-facebook-url'         => '',
            'acmephoto-twitter-url'          => '',
            'acmephoto-instagram-url'          => '',
            'acmephoto-enable-social'        => '',

            /*footer options*/
            'acmephoto-enable-footer-social'     => '',
            'acmephoto-footer-copyright'     => __( '&copy; All Right Reserved 2018', 'acmephoto' ),

            /*layout/design options*/
            'acmephoto-default-layout'       => 'fullwidth',

            'acmephoto-sidebar-layout'       => 'right-sidebar',
            'acmephoto-front-page-sidebar-layout'  => 'no-sidebar',
            'acmephoto-archive-sidebar-layout'  => 'no-sidebar',
            'acmephoto-single-sidebar-layout'=> 'right-sidebar',
            'acmephoto-enable-sticky-sidebar'  => '',
            'acmephoto-pagination-option'    => 'default',
            
            /*blog archive*/
            'acmephoto-blog-enable-gap'  => 1,
            'acmephoto-blog-show-title'  => 1,
            'acmephoto-blog-show-cats'  => 1,
            'acmephoto-blog-show-comments'  => '',
            'acmephoto-blog-show-date'  => '',
            'acmephoto-blog-show-author'  => 1,
            
            /*color*/
            'acmephoto-primary-color'        => '#F88C00',
            
            /*custom css*/
            'acmephoto-custom-css'           => '',

	        /*single post options*/
            'acmephoto-blog-archive-image-size' => 'large',

            'acmephoto-show-related'  => 1,
            'acmephoto-related-title'  => __( 'Related posts', 'acmephoto' ),
            'acmephoto-related-post-display-from'  => 'cat',
            'acmephoto-single-image-size'  => 'full',

            /*theme options*/
            'acmephoto-search-placholder'    => __( 'Search', 'acmephoto' ),
            'acmephoto-show-breadcrumb'      => '',

            /*Reset*/
            'acmephoto-reset-options'        => '0',
            'acmephoto-you-are-here-text'  => __( 'You are here', 'acmephoto' )
        );
        return apply_filters( 'acmephoto_default_theme_options', $default_theme_options );
    }
endif;

/**
 *  Get theme options
 *
 * @since AcmePhoto 1.0.0
 *
 * @param null
 * @return array acmephoto_theme_options
 *
 */
if ( !function_exists('acmephoto_get_theme_options') ) :

    function acmephoto_get_theme_options() {
        $acmephoto_default_theme_options = acmephoto_get_default_theme_options();
        $acmephoto_get_theme_options = get_theme_mod( 'acmephoto_theme_options');
        if( is_array( $acmephoto_get_theme_options ) ){
            return array_merge( $acmephoto_default_theme_options ,$acmephoto_get_theme_options );
        }
        else{
            return $acmephoto_default_theme_options;
        }
    }
endif;