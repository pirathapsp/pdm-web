<?php
/*
 * adding sections for default layout options
*/
$wp_customize->add_section( 'acmephoto-design-default-layout-option', array(
    'priority'       => 10,
    'capability'     => 'edit_theme_options',
    'theme_supports' => '',
    'title'          => __( 'Default Layout', 'acmephoto' ),
    'panel'          => 'acmephoto-design-panel'
) );

/*global default layout*/
$wp_customize->add_setting( 'acmephoto_theme_options[acmephoto-default-layout]', array(
    'capability'		=> 'edit_theme_options',
    'default'			=> $defaults['acmephoto-default-layout'],
    'sanitize_callback' => 'acmephoto_sanitize_select'
) );

$choices = acmephoto_default_layout();
$wp_customize->add_control( 'acmephoto_theme_options[acmephoto-default-layout]', array(
    'choices'  	    => $choices,
    'label'		    => __( 'Default Layout', 'acmephoto' ),
    'description'   => __( 'Boxed or Full Layout', 'acmephoto' ),
    'section'       => 'acmephoto-design-default-layout-option',
    'settings'      => 'acmephoto_theme_options[acmephoto-default-layout]',
    'type'	  	    => 'select'
) );