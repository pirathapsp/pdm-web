<?php
/*
 * adding sections for custom css options
*/
$wp_customize->add_section( 'acmephoto-design-custom-css-option', array(
    'priority'       => 60,
    'capability'     => 'edit_theme_options',
    'theme_supports' => '',
    'title'          => __( 'Custom CSS', 'acmephoto' ),
    'panel'          => 'acmephoto-design-panel'
) );

/*custom-css*/
$wp_customize->add_setting( 'acmephoto_theme_options[acmephoto-custom-css]', array(
    'capability'		   => 'edit_theme_options',
    'default'			   => $defaults['acmephoto-custom-css'],
    'sanitize_callback'    => 'wp_filter_nohtml_kses',
    'sanitize_js_callback' => 'wp_filter_nohtml_kses'
) );
$wp_customize->add_control( 'acmephoto_theme_options[acmephoto-custom-css]', array(
    'label'		=> __( 'Custom CSS', 'acmephoto' ),
    'section'   => 'acmephoto-design-custom-css-option',
    'settings'  => 'acmephoto_theme_options[acmephoto-custom-css]',
    'type'	  	=> 'textarea',
    'priority'  => 2
) );