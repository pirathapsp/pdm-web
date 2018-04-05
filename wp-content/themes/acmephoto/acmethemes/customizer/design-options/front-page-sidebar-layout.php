<?php
/*adding sections for default layout options panel*/
$wp_customize->add_section( 'acmephoto-front-page-sidebar-layout', array(
    'priority'       => 20,
    'capability'     => 'edit_theme_options',
    'theme_supports' => '',
    'title'          => __( 'Front/Home Sidebar Layout', 'acmephoto' ),
    'panel'          => 'acmephoto-design-panel'
) );

/*Sidebar Layout*/
$wp_customize->add_setting( 'acmephoto_theme_options[acmephoto-front-page-sidebar-layout]', array(
    'capability'		=> 'edit_theme_options',
    'default'			=> $defaults['acmephoto-front-page-sidebar-layout'],
    'sanitize_callback' => 'acmephoto_sanitize_select'
) );
$choices = acmephoto_sidebar_layout();
$wp_customize->add_control( 'acmephoto_theme_options[acmephoto-front-page-sidebar-layout]', array(
    'choices'  	=> $choices,
    'label'		=> __( 'Front/Home Sidebar Layout', 'acmephoto' ),
    'section'   => 'acmephoto-front-page-sidebar-layout',
    'settings'  => 'acmephoto_theme_options[acmephoto-front-page-sidebar-layout]',
    'type'	  	=> 'select'
) );