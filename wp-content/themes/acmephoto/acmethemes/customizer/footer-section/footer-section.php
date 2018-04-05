<?php
/*adding sections for footer options*/
$wp_customize->add_section( 'acmephoto-footer-option', array(
    'priority'       => 80,
    'capability'     => 'edit_theme_options',
    'theme_supports' => '',
    'title'          => __( 'Footer Option', 'acmephoto' )
) );

/*enable social*/
$wp_customize->add_setting( 'acmephoto_theme_options[acmephoto-enable-footer-social]', array(
    'capability'		=> 'edit_theme_options',
    'default'			=> $defaults['acmephoto-enable-footer-social'],
    'sanitize_callback' => 'acmephoto_sanitize_checkbox',
) );
$wp_customize->add_control( 'acmephoto_theme_options[acmephoto-enable-footer-social]', array(
    'label'		=> __( 'Enable social', 'acmephoto' ),
    'section'   => 'acmephoto-footer-option',
    'settings'  => 'acmephoto_theme_options[acmephoto-enable-footer-social]',
    'type'	  	=> 'checkbox',
    'priority'  => 10
) );

/*copyright*/
$wp_customize->add_setting( 'acmephoto_theme_options[acmephoto-footer-copyright]', array(
    'capability'		=> 'edit_theme_options',
    'default'			=> $defaults['acmephoto-footer-copyright'],
    'sanitize_callback' => 'wp_kses_post'
) );
$wp_customize->add_control( 'acmephoto_theme_options[acmephoto-footer-copyright]', array(
    'label'		=> __( 'Copyright Text', 'acmephoto' ),
    'section'   => 'acmephoto-footer-option',
    'settings'  => 'acmephoto_theme_options[acmephoto-footer-copyright]',
    'type'	  	=> 'text',
    'priority'  => 20
) );