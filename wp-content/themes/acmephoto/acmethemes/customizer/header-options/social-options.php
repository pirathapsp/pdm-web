<?php
/*adding sections for header social options */
$wp_customize->add_section( 'acmephoto-header-social', array(
    'priority'       => 20,
    'capability'     => 'edit_theme_options',
    'theme_supports' => '',
    'title'          => __( 'Social Options', 'acmephoto' ),
    'panel'          => 'acmephoto-header-panel'
) );

/*facebook url*/
$wp_customize->add_setting( 'acmephoto_theme_options[acmephoto-facebook-url]', array(
    'capability'		=> 'edit_theme_options',
    'default'			=> $defaults['acmephoto-facebook-url'],
    'sanitize_callback' => 'esc_url_raw',
) );
$wp_customize->add_control( 'acmephoto_theme_options[acmephoto-facebook-url]', array(
    'label'		=> __( 'Facebook url', 'acmephoto' ),
    'section'   => 'acmephoto-header-social',
    'settings'  => 'acmephoto_theme_options[acmephoto-facebook-url]',
    'type'	  	=> 'url',
    'priority'  => 20
) );

/*twitter url*/
$wp_customize->add_setting( 'acmephoto_theme_options[acmephoto-twitter-url]', array(
    'capability'		=> 'edit_theme_options',
    'default'			=> $defaults['acmephoto-twitter-url'],
    'sanitize_callback' => 'esc_url_raw',
) );
$wp_customize->add_control( 'acmephoto_theme_options[acmephoto-twitter-url]', array(
    'label'		=> __( 'Twitter url', 'acmephoto' ),
    'section'   => 'acmephoto-header-social',
    'settings'  => 'acmephoto_theme_options[acmephoto-twitter-url]',
    'type'	  	=> 'url',
    'priority'  => 25
) );

/*instagram url*/
$wp_customize->add_setting( 'acmephoto_theme_options[acmephoto-instagram-url]', array(
    'capability'		=> 'edit_theme_options',
    'default'			=> $defaults['acmephoto-instagram-url'],
    'sanitize_callback' => 'esc_url_raw',
) );
$wp_customize->add_control( 'acmephoto_theme_options[acmephoto-instagram-url]', array(
    'label'		=> __( 'Instagram url', 'acmephoto' ),
    'section'   => 'acmephoto-header-social',
    'settings'  => 'acmephoto_theme_options[acmephoto-instagram-url]',
    'type'	  	=> 'url',
    'priority'  => 25
) );

/*enable social*/
$wp_customize->add_setting( 'acmephoto_theme_options[acmephoto-enable-social]', array(
    'capability'		=> 'edit_theme_options',
    'default'			=> $defaults['acmephoto-enable-social'],
    'sanitize_callback' => 'acmephoto_sanitize_checkbox',
) );
$wp_customize->add_control( 'acmephoto_theme_options[acmephoto-enable-social]', array(
    'label'		=> __( 'Enable social', 'acmephoto' ),
    'section'   => 'acmephoto-header-social',
    'settings'  => 'acmephoto_theme_options[acmephoto-enable-social]',
    'type'	  	=> 'checkbox',
    'priority'  => 30
) );