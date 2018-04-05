<?php
/*
 * customizing default colors section and adding new controls-setting too
*/
$wp_customize->add_section( 'colors', array(
    'priority'       => 40,
    'capability'     => 'edit_theme_options',
    'theme_supports' => '',
    'title'          => __( 'Colors', 'acmephoto' ),
    'panel'          => 'acmephoto-design-panel'
) );

/*Primary color*/
$wp_customize->add_setting( 'acmephoto_theme_options[acmephoto-primary-color]', array(
    'capability'		=> 'edit_theme_options',
    'default'			=> $defaults['acmephoto-primary-color'],
    'sanitize_callback' => 'sanitize_hex_color'
) );
$wp_customize->add_control( 'acmephoto_theme_options[acmephoto-primary-color]', array(
    'label'		=> __( 'Primary Color', 'acmephoto' ),
    'section'   => 'colors',
    'settings'  => 'acmephoto_theme_options[acmephoto-primary-color]',
    'type'	  	=> 'color'
) );