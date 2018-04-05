<?php
/**
 * Setting global variables for all theme options db saved values
 *
 * @since AcmePhoto 1.0.0
 *
 * @param null
 * @return null
 *
 */
if ( ! function_exists( 'acmephoto_set_global' ) ) :

    function acmephoto_set_global() {
        /*Getting saved values start*/
        $acmephoto_saved_theme_options = acmephoto_get_theme_options();
        $GLOBALS['acmephoto_customizer_all_values'] = $acmephoto_saved_theme_options;
        /*Getting saved values end*/
    }
endif;
add_action( 'acmephoto_action_before_head', 'acmephoto_set_global', 0 );

/**
 * Logo options
 *
 * @since AcmePhoto 1.0.0
 *
 * @param null
 * @return null
 *
 */
if ( ! function_exists( 'acmephoto_front_logo_options' ) ) :

    function acmephoto_front_logo_options() {
        global $acmephoto_customizer_all_values;
        if ( 'disable' != $acmephoto_customizer_all_values['acmephoto-header-id-display-opt'] ):
            if ( 'logo-only' == $acmephoto_customizer_all_values['acmephoto-header-id-display-opt'] ):
                if ( function_exists( 'the_custom_logo' ) ) :
                    the_custom_logo();
                else :
                    if( !empty( $acmephoto_customizer_all_values['acmephoto-header-logo'] ) ):
                        ?>
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
                            <img src="<?php echo esc_url( $acmephoto_customizer_all_values['acmephoto-header-logo'] ); ?>">
                        </a>
                        <?php
                    endif;/*acmephoto-header-logo*/
                endif;
            else:/*else is title-only or title-and-tagline*/
                if ( is_front_page() && is_home() ) : ?>
                    <h1 class="site-title">
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
                    </h1>
                <?php else : ?>
                    <p class="site-title">
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
                    </p>
                <?php endif;
                if ( 'title-and-tagline' == $acmephoto_customizer_all_values['acmephoto-header-id-display-opt'] ):
                    $description = get_bloginfo( 'description', 'display' );
                    if ( $description || is_customize_preview() ) : ?>
                        <p class="site-description"><?php echo esc_html( $description ); ?></p>
                    <?php endif;
                endif;
            endif;
       endif;?><!--acmephoto-header-id-display-opt-->
        <?php
    }
endif;

/**
 * Logo options
 *
 * @since AcmePhoto 1.0.1
 *
 * @param null
 * @return null
 *
 */
if ( ! function_exists( 'acmephoto_front_navigation_options' ) ) :

    function acmephoto_front_navigation_options() {
        global $acmephoto_customizer_all_values;
        ?>
        <div class="wrapper">
            <div class="sticky-site-identity">
                <?php
                acmephoto_front_logo_options();
                ?><!--acmephoto-header-id-display-opt-->
            </div>
            <?php
            if( 1 == $acmephoto_customizer_all_values['acmephoto-enable-social'] ){
                do_action('acmephoto_action_social_links');
            }
            ?>
            <button type="button" class="navbar-toggle"><i class="fa fa-bars"></i></button>
            <div class="main-navigation clearfix" id="main-navigation">
                <?php
                wp_nav_menu(
                    array(
                        'theme_location' => 'primary',
                        'menu_id' => 'primary-menu',
                        'menu_class' => 'nav navbar-nav navbar-right animated',
                    )
                );
                ?>
            </div>
            <!--/.nav-collapse -->
        </div>
        <?php

    }
endif;

/**
 * Doctype Declaration
 *
 * @since AcmePhoto 1.0.0
 *
 * @param null
 * @return null
 *
 */
if ( ! function_exists( 'acmephoto_doctype' ) ) :
    function acmephoto_doctype() {
        ?>
        <!DOCTYPE html><html <?php language_attributes(); ?>>
    <?php
    }
endif;
add_action( 'acmephoto_action_before_head', 'acmephoto_doctype', 10 );

/**
 * Code inside head tag but before wp_head funtion
 *
 * @since AcmePhoto 1.0.0
 *
 * @param null
 * @return null
 *
 */
if ( ! function_exists( 'acmephoto_before_wp_head' ) ) :

    function acmephoto_before_wp_head() {
        ?>
        <meta charset="<?php bloginfo( 'charset' ); ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="profile" href="<?php echo esc_url('http://gmpg.org/xfn/11')?>">
        <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
    <?php
    }
endif;
add_action( 'acmephoto_action_before_wp_head', 'acmephoto_before_wp_head', 10 );

/**
 * Add body class
 *
 * @since AcmePhoto 1.0.0
 *
 * @param null
 * @return null
 *
 */
if ( ! function_exists( 'acmephoto_body_class' ) ) :

    function acmephoto_body_class( $acmephotobody_classes ) {
        global $acmephoto_customizer_all_values;
        $acmephoto_enable_feature = $acmephoto_customizer_all_values['acmephoto-enable-feature'];
        $acmephoto_menu_position_options = $acmephoto_customizer_all_values['acmephoto-menu-position-options'];

        if ( 'boxed' == $acmephoto_customizer_all_values['acmephoto-default-layout'] ) {
            $acmephotobody_classes[] = 'boxed-layout';
        }
	    if( 1 == $acmephoto_customizer_all_values['acmephoto-enable-sticky-sidebar'] ){
		    $acmephotobody_classes[] = 'at-sticky-sidebar';
	    }
        if( 
            ( !is_front_page() && ('top-fixed' == $acmephoto_menu_position_options || 'below-feature' == $acmephoto_menu_position_options) ) || 
            ( is_front_page() && is_home() && 'top-fixed' == $acmephoto_menu_position_options && 1 != $acmephoto_enable_feature)){
            $acmephotobody_classes[] = 'not-front-page';
        }
        $acmephotobody_classes[] = acmephoto_sidebar_selection();

        return $acmephotobody_classes;
    }
endif;
add_action( 'body_class', 'acmephoto_body_class', 10, 1);

/**
 * Page start
 *
 * @since AcmePhoto 1.0.0
 *
 * @param null
 * @return null
 *
 */
if ( ! function_exists( 'acmephoto_page_start' ) ) :

    function acmephoto_page_start() {
        ?>
        <div id="page" class="hfeed site">
    <?php
    }
endif;
add_action( 'acmephoto_action_before', 'acmephoto_page_start', 15 );

/**
 * Skip to content
 *
 * @since AcmePhoto 1.0.0
 *
 * @param null
 * @return null
 *
 */
if ( ! function_exists( 'acmephoto_skip_to_content' ) ) :

    function acmephoto_skip_to_content() {
        ?>
        <a class="skip-link screen-reader-text" href="#content" title="link"><?php esc_html_e( 'Skip to content', 'acmephoto' ); ?></a>
    <?php
    }

endif;
add_action( 'acmephoto_action_before_header', 'acmephoto_skip_to_content', 10 );

/**
 * Main header
 *
 * @since AcmePhoto 0.0.1
 *
 * @param null
 * @return null
 *
 */
if ( ! function_exists( 'acmephoto_header' ) ) :
    function acmephoto_header() {
        global $acmephoto_customizer_all_values;
        $acmephoto_enable_feature = $acmephoto_customizer_all_values['acmephoto-enable-feature'];
        $acmephoto_menu_position_options = $acmephoto_customizer_all_values['acmephoto-menu-position-options'];
        if( 'top-normal' == $acmephoto_menu_position_options ) {
            ?>
            <div class="navbar at-navbar normal-navigation clearfix" id="navbar" role="navigation">
                <?php
                acmephoto_front_navigation_options();
                ?>
            </div>
            <?php
        }
        elseif( 'top-fixed' == $acmephoto_menu_position_options ) {
            /*do nothing*/
        }
        else{
            if ( is_front_page() && 1 == $acmephoto_enable_feature ){
                ?>
                <div class="top-header" id="top-header">
                    <div class="wrapper">
                        <div class="navbar-header clearfix">
                            <?php
                            acmephoto_front_logo_options();
                            ?><!--acmephoto-header-id-display-opt-->
                        </div>
                    </div>
                </div>
                <?php
            }
            else{
                /*do nothing*/
            }
        }
    }
endif;
add_action( 'acmephoto_action_header', 'acmephoto_header', 10 );

/**
 * Before main content
 *
 * @since AcmePhoto 1.0.0
 *
 * @param null
 * @return void
 *
 */
if ( ! function_exists( 'acmephoto_before_content' ) ) :

    function acmephoto_before_content() {

        global $acmephoto_customizer_all_values;
        $acmephoto_enable_feature = $acmephoto_customizer_all_values['acmephoto-enable-feature'];
        $acmephoto_menu_position_options = $acmephoto_customizer_all_values['acmephoto-menu-position-options'];
        if ( is_front_page() && 1 == $acmephoto_enable_feature ) {
            echo '<div class="slider-feature-wrap clearfix">';
            /**
             * Slide
             * acmephoto_action_feature_slider
             * @since AcmePhoto 1.1.0
             *
             * @hooked acmephoto_feature_slider -  0
             */
            do_action('acmephoto_action_feature_slider');
            echo "</div>";
            $acmephoto_content_id = "home-content";
        } else {
            $acmephoto_content_id = "content";
        }
        $inner_nav = '';
        if( 'top-normal' != $acmephoto_menu_position_options ){
            if( !is_front_page() ||
                ( is_front_page() && 1 != $acmephoto_enable_feature )  ||
                ( is_front_page() && 1 == $acmephoto_enable_feature && 'top-fixed' == $acmephoto_menu_position_options )){
                $inner_nav .= ' navbar-small';
            }
            else{
                $inner_nav .= ' at-navbar-controller';
            }
            ?>
            <div class="navbar at-navbar clearfix <?php echo esc_attr( $inner_nav );?>" id="navbar" role="navigation">
                <?php
                acmephoto_front_navigation_options();
                ?>
            </div>
            <?php
        }
        ?>
        <div class="wrapper content-wrapper clearfix">
    <div id="<?php echo esc_attr( $acmephoto_content_id ); ?>" class="site-content clearfix">
    <?php
        if( 1 == $acmephoto_customizer_all_values['acmephoto-show-breadcrumb'] && !is_front_page() ){
            acmephoto_breadcrumbs();
        }
    }
endif;
add_action( 'acmephoto_action_after_header', 'acmephoto_before_content', 10 );