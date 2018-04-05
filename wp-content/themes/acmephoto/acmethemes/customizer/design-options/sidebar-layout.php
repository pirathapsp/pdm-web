<?php
/*
 * adding sections for sidebar options
 * */
$wp_customize->add_section( 'acmephoto-design-sidebar-layout-option', array(
    'priority'       => 20,
    'capability'     => 'edit_theme_options',
    'theme_supports' => '',
    'title'          => __( 'Single/Default Sidebar Layout', 'acmephoto' ),
    'panel'          => 'acmephoto-design-panel'
) );

/*Sidebar Layout*/
$wp_customize->add_setting( 'acmephoto_theme_options[acmephoto-single-sidebar-layout]', array(
    'capability'		=> 'edit_theme_options',
    'default'			=> $defaults['acmephoto-single-sidebar-layout'],
    'sanitize_callback' => 'acmephoto_sanitize_select'
) );
$choices = acmephoto_sidebar_layout();
$wp_customize->add_control( 'acmephoto_theme_options[acmephoto-single-sidebar-layout]', array(
    'choices'  	=> $choices,
    'label'		=> __( 'Single Post/Page Sidebar Layout', 'acmephoto' ),
    'section'   => 'acmephoto-design-sidebar-layout-option',
    'settings'  => 'acmephoto_theme_options[acmephoto-single-sidebar-layout]',
    'type'	  	=> 'select'
) );