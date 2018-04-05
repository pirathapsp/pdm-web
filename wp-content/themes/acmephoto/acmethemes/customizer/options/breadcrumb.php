<?php
/*adding sections for breadcrumb */
$wp_customize->add_section( 'acmephoto-breadcrumb-options', array(
    'priority'       => 20,
    'capability'     => 'edit_theme_options',
    'theme_supports' => '',
    'title'          => __( 'Breadcrumb Options', 'acmephoto' ),
    'panel'          => 'acmephoto-options'
) );

/*show breadcrumb*/
$wp_customize->add_setting( 'acmephoto_theme_options[acmephoto-show-breadcrumb]', array(
    'capability'		=> 'edit_theme_options',
    'default'			=> $defaults['acmephoto-show-breadcrumb'],
    'sanitize_callback' => 'acmephoto_sanitize_checkbox'
) );

$wp_customize->add_control( 'acmephoto_theme_options[acmephoto-show-breadcrumb]', array(
    'label'		=> __( 'Enable Breadcrumb', 'acmephoto' ),
    'section'   => 'acmephoto-breadcrumb-options',
    'settings'  => 'acmephoto_theme_options[acmephoto-show-breadcrumb]',
    'type'	  	=> 'checkbox',
    'priority'  => 10
) );

/*You are here Text*/
$wp_customize->add_setting( 'acmephoto_theme_options[acmephoto-you-are-here-text]', array(
	'capability'		=> 'edit_theme_options',
	'default'			=> $defaults['acmephoto-you-are-here-text'],
	'sanitize_callback' => 'sanitize_text_field'
) );
$wp_customize->add_control( 'acmephoto_theme_options[acmephoto-you-are-here-text]', array(
	'label'		=> __( 'You are here Text', 'acmephoto' ),
	'section'   => 'acmephoto-breadcrumb-options',
	'settings'  => 'acmephoto_theme_options[acmephoto-you-are-here-text]',
	'type'	  	=> 'text'
) );