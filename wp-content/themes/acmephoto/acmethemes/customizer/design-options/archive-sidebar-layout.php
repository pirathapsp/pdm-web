<?php
/*adding sections for default layout options panel*/
$wp_customize->add_section( 'acmephoto-archive-sidebar-layout', array(
    'priority'       => 20,
    'capability'     => 'edit_theme_options',
    'theme_supports' => '',
    'title'          => __( 'Category/Archive Sidebar Layout', 'acmephoto' ),
    'panel'          => 'acmephoto-design-panel'
) );

/*Archive Sidebar Layout*/
$wp_customize->add_setting( 'acmephoto_theme_options[acmephoto-archive-sidebar-layout]', array(
    'capability'		=> 'edit_theme_options',
    'default'			=> $defaults['acmephoto-archive-sidebar-layout'],
    'sanitize_callback' => 'acmephoto_sanitize_select'
) );
$choices = acmephoto_sidebar_layout();
$wp_customize->add_control( 'acmephoto_theme_options[acmephoto-archive-sidebar-layout]', array(
    'choices'  	    => $choices,
    'label'		    => __( 'Category/Archive Sidebar Layout', 'acmephoto' ),
    'description'   => __( 'Sidebar Layout for listing pages like category, author etc', 'acmephoto' ),
    'section'       => 'acmephoto-archive-sidebar-layout',
    'settings'      => 'acmephoto_theme_options[acmephoto-archive-sidebar-layout]',
    'type'	  	    => 'select'
) );