<?php
/*adding feature options panel*/
$wp_customize->add_panel( 'acmephoto-feature-panel', array(
    'priority'       => 70,
    'capability'     => 'edit_theme_options',
    'theme_supports' => '',
    'title'          => __( 'Featured Section Options', 'acmephoto' ),
    'description'    => __( 'Customize your awesome site feature section ', 'acmephoto' )
) );


/*
* file for feature section enable
*/
require_once acmephoto_file_directory('acmethemes/customizer/feature-section/feature-enable.php');

/*
* file for feature slider category
*/
require_once acmephoto_file_directory('acmethemes/customizer/feature-section/feature-slider.php');

/*
* file for feature slider category
*/
require_once acmephoto_file_directory('acmethemes/customizer/feature-section/social-options.php');