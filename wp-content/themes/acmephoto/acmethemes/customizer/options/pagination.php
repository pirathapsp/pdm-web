<?php
/*adding sections for default layout options panel*/
$wp_customize->add_section( 'acmephoto-option-pagination-option', array(
    'priority'       => 20,
    'capability'     => 'edit_theme_options',
    'theme_supports' => '',
    'title'          => __( 'Pagination Options', 'acmephoto' ),
    'panel'          => 'acmephoto-options'
) );

/*Pagination Options*/
$wp_customize->add_setting( 'acmephoto_theme_options[acmephoto-pagination-option]', array(
    'capability'		=> 'edit_theme_options',
    'default'			=> $defaults['acmephoto-pagination-option'],
    'sanitize_callback' => 'acmephoto_sanitize_select'
) );
$choices = acmephoto_pagination_options();
$wp_customize->add_control( 'acmephoto_theme_options[acmephoto-pagination-option]', array(
    'choices'  	    => $choices,
    'label'		    => __( 'Pagination Options', 'acmephoto' ),
    'description'   => __( 'Blog and Archive Pages Pagination', 'acmephoto' ),
    'section'       => 'acmephoto-option-pagination-option',
    'settings'      => 'acmephoto_theme_options[acmephoto-pagination-option]',
    'type'	  	    => 'select'
) );