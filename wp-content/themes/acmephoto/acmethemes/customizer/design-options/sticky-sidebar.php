<?php
/*adding sections for sidebar options */
$wp_customize->add_section( 'acmephoto-design-sidebar-sticky-option', array(
    'priority'       => 20,
    'capability'     => 'edit_theme_options',
    'theme_supports' => '',
    'title'          => __( 'Sticky Sidebar Option', 'acmephoto' ),
    'panel'          => 'acmephoto-design-panel'
) );

/*Sticky sidebar enable disable*/
$wp_customize->add_setting( 'acmephoto_theme_options[acmephoto-enable-sticky-sidebar]', array(
    'capability'		=> 'edit_theme_options',
    'default'			=> $defaults['acmephoto-enable-sticky-sidebar'],
    'sanitize_callback' => 'acmephoto_sanitize_checkbox'
) );
$wp_customize->add_control( 'acmephoto_theme_options[acmephoto-enable-sticky-sidebar]', array(
    'label'		=> __( 'Enable Sticky Sidebar Loader', 'acmephoto' ),
    'section'   => 'acmephoto-design-sidebar-sticky-option',
    'settings'  => 'acmephoto_theme_options[acmephoto-enable-sticky-sidebar]',
    'type'	  	=> 'checkbox',
    'priority'  => 30
) );