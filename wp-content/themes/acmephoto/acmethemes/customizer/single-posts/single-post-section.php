<?php
/*adding sections for Single post options*/
$wp_customize->add_section( 'acmephoto-single-post', array(
	'priority'       => 90,
	'capability'     => 'edit_theme_options',
	'theme_supports' => '',
	'title'          => __( 'Single Post Options', 'acmephoto' )
) );

/*single image size*/
$wp_customize->add_setting( 'acmephoto_theme_options[acmephoto-single-image-size]', array(
	'capability'		=> 'edit_theme_options',
	'default'			=> $defaults['acmephoto-single-image-size'],
	'sanitize_callback' => 'acmephoto_sanitize_select'
) );
$choices = acmephoto_get_image_sizes_options();
$wp_customize->add_control( 'acmephoto_theme_options[acmephoto-single-image-size]', array(
	'choices'  	=> $choices,
	'label'		=> __( 'Image Layout Options', 'acmephoto' ),
	'section'   => 'acmephoto-single-post',
	'settings'  => 'acmephoto_theme_options[acmephoto-single-image-size]',
	'type'	  	=> 'select',
	'priority'  => 20
) );

/*show related posts*/
$wp_customize->add_setting( 'acmephoto_theme_options[acmephoto-show-related]', array(
	'capability'		=> 'edit_theme_options',
	'default'			=> $defaults['acmephoto-show-related'],
	'sanitize_callback' => 'acmephoto_sanitize_checkbox'
) );
$wp_customize->add_control( 'acmephoto_theme_options[acmephoto-show-related]', array(
	'label'		=> __( 'Show Related Posts In Single Post', 'acmephoto' ),
	'section'   => 'acmephoto-single-post',
	'settings'  => 'acmephoto_theme_options[acmephoto-show-related]',
	'type'	  	=> 'checkbox',
	'priority'  => 30
) );

/*Related title*/
$wp_customize->add_setting( 'acmephoto_theme_options[acmephoto-related-title]', array(
	'capability'		=> 'edit_theme_options',
	'default'			=> $defaults['acmephoto-related-title'],
	'sanitize_callback' => 'sanitize_text_field'
) );
$wp_customize->add_control( 'acmephoto_theme_options[acmephoto-related-title]', array(
	'label'		=> __( 'Related Posts title', 'acmephoto' ),
	'section'   => 'acmephoto-single-post',
	'settings'  => 'acmephoto_theme_options[acmephoto-related-title]',
	'type'	  	=> 'text',
	'priority'  => 40
) );

/*related post by tag or category*/
$wp_customize->add_setting( 'acmephoto_theme_options[acmephoto-related-post-display-from]', array(
	'capability'		=> 'edit_theme_options',
	'default'			=> $defaults['acmephoto-related-post-display-from'],
	'sanitize_callback' => 'acmephoto_sanitize_select'
) );
$choices = acmephoto_related_post_display_from();
$wp_customize->add_control( 'acmephoto_theme_options[acmephoto-related-post-display-from]', array(
	'choices'  	=> $choices,
	'label'		=> __( 'Related Post Display From Options', 'acmephoto' ),
	'section'   => 'acmephoto-single-post',
	'settings'  => 'acmephoto_theme_options[acmephoto-related-post-display-from]',
	'type'	  	=> 'select',
	'priority'  => 50
) );