<?php
/*adding sections for blog layout options*/
$wp_customize->add_section( 'acmephoto-design-blog-layout-option', array(
    'priority'       => 30,
    'capability'     => 'edit_theme_options',
    'theme_supports' => '',
    'title'          => __( 'Blog/Archive Layout Options', 'acmephoto' ),
    'panel'          => 'acmephoto-design-panel'
) );

/*blog image size*/
$wp_customize->add_setting( 'acmephoto_theme_options[acmephoto-blog-archive-image-size]', array(
	'capability'		=> 'edit_theme_options',
	'default'			=> $defaults['acmephoto-blog-archive-image-size'],
	'sanitize_callback' => 'acmephoto_sanitize_select'
) );
$choices = acmephoto_get_image_sizes_options();
$wp_customize->add_control( 'acmephoto_theme_options[acmephoto-blog-archive-image-size]', array(
	'choices'  	=> $choices,
	'label'		=> __( 'Image Size Options', 'acmephoto' ),
	'section'   => 'acmephoto-design-blog-layout-option',
	'settings'  => 'acmephoto_theme_options[acmephoto-blog-archive-image-size]',
	'type'	  	=> 'select',
) );

/*enable padding( gap ) */
$wp_customize->add_setting( 'acmephoto_theme_options[acmephoto-blog-enable-gap]', array(
    'capability'		=> 'edit_theme_options',
    'default'			=> $defaults['acmephoto-blog-enable-gap'],
    'sanitize_callback' => 'acmephoto_sanitize_checkbox',
) );
$wp_customize->add_control( 'acmephoto_theme_options[acmephoto-blog-enable-gap]', array(
    'label'		        => __( 'Enable Gap', 'acmephoto' ),
    'section'           => 'acmephoto-design-blog-layout-option',
    'settings'          => 'acmephoto_theme_options[acmephoto-blog-enable-gap]',
    'type'	  	        => 'checkbox',
    'priority'          => 20
) );

/*show title*/
$wp_customize->add_setting( 'acmephoto_theme_options[acmephoto-blog-show-title]', array(
    'capability'		=> 'edit_theme_options',
    'default'			=> $defaults['acmephoto-blog-show-title'],
    'sanitize_callback' => 'acmephoto_sanitize_checkbox',
) );
$wp_customize->add_control( 'acmephoto_theme_options[acmephoto-blog-show-title]', array(
    'label'		        => __( 'Show Title', 'acmephoto' ),
    'section'           => 'acmephoto-design-blog-layout-option',
    'settings'          => 'acmephoto_theme_options[acmephoto-blog-show-title]',
    'type'	  	        => 'checkbox',
    'priority'          => 30
) );

/*Show date*/
$wp_customize->add_setting( 'acmephoto_theme_options[acmephoto-blog-show-date]', array(
    'capability'		=> 'edit_theme_options',
    'default'			=> $defaults['acmephoto-blog-show-date'],
    'sanitize_callback' => 'acmephoto_sanitize_checkbox',
) );
$wp_customize->add_control( 'acmephoto_theme_options[acmephoto-blog-show-date]', array(
    'label'		        => __( 'Show Date', 'acmephoto' ),
    'section'           => 'acmephoto-design-blog-layout-option',
    'settings'          => 'acmephoto_theme_options[acmephoto-blog-show-date]',
    'type'	  	        => 'checkbox',
    'priority'          => 40
) );

/*Show author*/
$wp_customize->add_setting( 'acmephoto_theme_options[acmephoto-blog-show-author]', array(
    'capability'		=> 'edit_theme_options',
    'default'			=> $defaults['acmephoto-blog-show-author'],
    'sanitize_callback' => 'acmephoto_sanitize_checkbox'
) );
$wp_customize->add_control( 'acmephoto_theme_options[acmephoto-blog-show-author]', array(
    'label'		        => __( 'Show Author', 'acmephoto' ),
    'section'           => 'acmephoto-design-blog-layout-option',
    'settings'          => 'acmephoto_theme_options[acmephoto-blog-show-author]',
    'type'	  	        => 'checkbox',
    'priority'          => 50
) );

/*Show cats*/
$wp_customize->add_setting( 'acmephoto_theme_options[acmephoto-blog-show-cats]', array(
    'capability'		=> 'edit_theme_options',
    'default'			=> $defaults['acmephoto-blog-show-cats'],
    'sanitize_callback' => 'acmephoto_sanitize_checkbox',
) );
$wp_customize->add_control( 'acmephoto_theme_options[acmephoto-blog-show-cats]', array(
    'label'		        => __( 'Show Categories', 'acmephoto' ),
    'section'           => 'acmephoto-design-blog-layout-option',
    'settings'          => 'acmephoto_theme_options[acmephoto-blog-show-cats]',
    'type'	  	        => 'checkbox',
    'priority'          => 60
) );

/*Show comments*/
$wp_customize->add_setting( 'acmephoto_theme_options[acmephoto-blog-show-comments]', array(
    'capability'		=> 'edit_theme_options',
    'default'			=> $defaults['acmephoto-blog-show-comments'],
    'sanitize_callback' => 'acmephoto_sanitize_checkbox'
) );
$wp_customize->add_control( 'acmephoto_theme_options[acmephoto-blog-show-comments]', array(
    'label'		        => __( 'Show Comments', 'acmephoto' ),
    'section'           => 'acmephoto-design-blog-layout-option',
    'settings'          => 'acmephoto_theme_options[acmephoto-blog-show-comments]',
    'type'	  	        => 'checkbox',
    'priority'          => 70
) );