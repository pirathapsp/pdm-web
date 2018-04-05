<?php
/*Menu Section*/
$wp_customize->add_section( 'acmephoto-header-menu', array(
    'priority'       => 20,
    'capability'     => 'edit_theme_options',
    'theme_supports' => '',
    'title'          => __( 'Menu Options', 'acmephoto' ),
    'panel'          => 'acmephoto-header-panel'
) );

/*Menu Options*/
$wp_customize->add_setting( 'acmephoto_theme_options[acmephoto-menu-position-options]', array(
    'capability'		=> 'edit_theme_options',
    'default'			=> $defaults['acmephoto-menu-position-options'],
    'sanitize_callback' => 'acmephoto_sanitize_select'
) );
$choices = acmephoto_menu_position_options();
$wp_customize->add_control( 'acmephoto_theme_options[acmephoto-menu-position-options]', array(
    'choices'  	    => $choices,
    'label'		    => __( 'Menu Position', 'acmephoto' ),
    'section'       => 'acmephoto-header-menu',
    'settings'      => 'acmephoto_theme_options[acmephoto-menu-position-options]',
    'type'	  	    => 'select'
) );