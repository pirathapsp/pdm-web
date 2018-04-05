<?php
/*adding sections for feature social options */
$wp_customize->add_section( 'acmephoto-feature-social', array(
    'priority'       => 20,
    'capability'     => 'edit_theme_options',
    'theme_supports' => '',
    'title'          => __( 'Social Options', 'acmephoto' ),
    'panel'          => 'acmephoto-feature-panel'
) );

/*enable social*/
$wp_customize->add_setting( 'acmephoto_theme_options[acmephoto-feature-enable-social]', array(
    'capability'		=> 'edit_theme_options',
    'default'			=> $defaults['acmephoto-feature-enable-social'],
    'sanitize_callback' => 'acmephoto_sanitize_checkbox',
) );
$wp_customize->add_control( 'acmephoto_theme_options[acmephoto-feature-enable-social]', array(
    'label'		=> __( 'Enable social', 'acmephoto' ),
    'section'   => 'acmephoto-feature-social',
    'settings'  => 'acmephoto_theme_options[acmephoto-feature-enable-social]',
    'type'	  	=> 'checkbox',
    'priority'  => 30
) );