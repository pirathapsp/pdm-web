<?php
/*adding sections for enabling feature section in front page*/
$wp_customize->add_section( 'acmephoto-enable-feature', array(
    'priority'       => 10,
    'capability'     => 'edit_theme_options',
    'theme_supports' => '',
    'title'          => __( 'Enable Feature Section', 'acmephoto' ),
    'panel'          => 'acmephoto-feature-panel'
) );

/*enable feature section*/
$wp_customize->add_setting( 'acmephoto_theme_options[acmephoto-enable-feature]', array(
    'capability'		=> 'edit_theme_options',
    'default'			=> $defaults['acmephoto-enable-feature'],
    'sanitize_callback' => 'acmephoto_sanitize_checkbox'
) );

$wp_customize->add_control( 'acmephoto_theme_options[acmephoto-enable-feature]', array(
    'label'		    => __( 'Enable Feature Section', 'acmephoto' ),
    'description'	=> __( 'Feature section will display on front/home page', 'acmephoto' ),
    'section'       => 'acmephoto-enable-feature',
    'settings'      => 'acmephoto_theme_options[acmephoto-enable-feature]',
    'type'	  	    => 'checkbox',
    'priority'      => 10
) );