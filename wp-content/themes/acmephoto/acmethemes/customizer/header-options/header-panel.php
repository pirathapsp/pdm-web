<?php
/*adding header options panel*/
$wp_customize->add_panel( 'acmephoto-header-panel', array(
    'priority'       => 30,
    'capability'     => 'edit_theme_options',
    'theme_supports' => '',
    'title'          => __( 'Header Options', 'acmephoto' ),
    'description'    => __( 'Customize your awesome site header ', 'acmephoto' )
) );

/*
* file for header logo options
*/
require_once acmephoto_file_directory('acmethemes/customizer/header-options/header-logo.php');

/*
* file for header social options
*/
require_once acmephoto_file_directory('acmethemes/customizer/header-options/social-options.php');

/*
* file for menu options
*/
require_once acmephoto_file_directory('acmethemes/customizer/header-options/menu-option.php');

