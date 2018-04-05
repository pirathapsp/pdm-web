<?php
/*adding sections for category section in front page*/
$wp_customize->add_section( 'acmephoto-feature-page', array(
    'priority'       => 20,
    'capability'     => 'edit_theme_options',
    'theme_supports' => '',
    'title'          => __( 'Feature Page', 'acmephoto' ),
    'panel'          => 'acmephoto-feature-panel'
) );

/* feature page selection */
$wp_customize->add_setting( 'acmephoto_theme_options[acmephoto-feature-page]', array(
    'capability'		=> 'edit_theme_options',
    'default'			=> $defaults['acmephoto-feature-page'],
    'sanitize_callback' => 'acmephoto_sanitize_number'
) );


$wp_customize->add_control( 'acmephoto_theme_options[acmephoto-feature-page]', array(
    'label'		    => __( 'Select a Page', 'acmephoto' ),
    'description'	=> sprintf( __( 'Recommended to write short title, short content/excerpt and use featured image 1280*610 for the selected page below. If you want to show slider, the page you selected should have %1$s child pages %2$s', 'acmephoto' ), '<a href="https://www.acmethemes.com/blog/2016/04/how-to-create-child-pages-sub-pages/" target="_blank">','</a>' ),
    'section'       => 'acmephoto-feature-page',
    'settings'      => 'acmephoto_theme_options[acmephoto-feature-page]',
    'type'	  	    => 'dropdown-pages',
    'priority'      => 10
) );

/* feature section height*/
$wp_customize->add_setting( 'acmephoto_theme_options[acmephoto-feature-height]', array(
    'capability'		=> 'edit_theme_options',
    'default'			=> $defaults['acmephoto-feature-height'],
    'sanitize_callback' => 'acmephoto_sanitize_number'
) );

$wp_customize->add_control( 'acmephoto_theme_options[acmephoto-feature-height]', array(
    'type'        => 'range',
    'priority'    => 10,
    'section'       => 'acmephoto-feature-page',
    'label'		  => __( 'Feature Section Height', 'acmephoto' ),
    'description'		  => __( 'Control the height of feature section according to the height of device. The minimum height is 50% of current screens size.', 'acmephoto' ),
    'input_attrs' => array(
        'min'   => 50,
        'max'   => 100,
        'step'  => 1,
        'class' => 'acmephoto-feature-height',
        'style' => 'color: #0a0',
    ),
) );

/*Button Options*/
$wp_customize->add_setting( 'acmephoto_theme_options[acmephoto-feature-button-option]', array(
    'capability'		=> 'edit_theme_options',
    'default'			=> $defaults['acmephoto-feature-button-option'],
    'sanitize_callback' => 'acmephoto_sanitize_select'
) );
$choices = acmephoto_feature_button_options();
$wp_customize->add_control( 'acmephoto_theme_options[acmephoto-feature-button-option]', array(
    'choices'  	    => $choices,
    'label'		    => __( 'Feature Button Options', 'acmephoto' ),
    'section'       => 'acmephoto-feature-page',
    'settings'      => 'acmephoto_theme_options[acmephoto-feature-button-option]',
    'type'	  	    => 'select'
) );