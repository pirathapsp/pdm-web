<?php
/*adding sections for Search Placeholder*/
$wp_customize->add_section( 'acmephoto-search', array(
    'priority'       => 20,
    'capability'     => 'edit_theme_options',
    'theme_supports' => '',
    'title'          => __( 'Search', 'acmephoto' ),
    'panel'          => 'acmephoto-options'
) );

/*Search Placeholder*/
$wp_customize->add_setting( 'acmephoto_theme_options[acmephoto-search-placholder]', array(
    'capability'		=> 'edit_theme_options',
    'default'			=> $defaults['acmephoto-search-placholder'],
    'sanitize_callback' => 'sanitize_text_field',
) );
$wp_customize->add_control( 'acmephoto_theme_options[acmephoto-search-placholder]', array(
    'label'		=> __( 'Search Placeholder', 'acmephoto' ),
    'section'   => 'acmephoto-search',
    'settings'  => 'acmephoto_theme_options[acmephoto-search-placholder]',
    'type'	  	=> 'text',
    'priority'  => 10
) );