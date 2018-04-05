<?php
global $wp_version;
// Return if wp version less than 4.5
if ( version_compare( $wp_version, '4.5', '<' ) ) {
    /*header logo*/
    $wp_customize->add_setting( 'acmephoto_theme_options[acmephoto-header-logo]', array(
        'capability'		=> 'edit_theme_options',
        'default'			=> $defaults['acmephoto-header-logo'],
        'sanitize_callback' => 'acmephoto_sanitize_image',
    ) );
    $wp_customize->add_control(
        new WP_Customize_Image_Control(
            $wp_customize,
            'acmephoto_theme_options[acmephoto-header-logo]',
            array(
                'label'		=> __( 'Logo', 'acmephoto' ),
                'section'   => 'title_tagline',
                'settings'  => 'acmephoto_theme_options[acmephoto-header-logo]',
                'type'	  	=> 'image',
                'priority'  => 10
            )
        )
    );
}

/*header logo/text display options*/
$wp_customize->add_setting( 'acmephoto_theme_options[acmephoto-header-id-display-opt]', array(
    'capability'		=> 'edit_theme_options',
    'default'			=> $defaults['acmephoto-header-id-display-opt'],
    'sanitize_callback' => 'acmephoto_sanitize_select'
) );
$choices = acmephoto_header_id_display_opt();
$wp_customize->add_control( 'acmephoto_theme_options[acmephoto-header-id-display-opt]', array(
    'choices'  	=> $choices,
    'label'		=> __( 'Logo/Site Title-Tagline Display Options', 'acmephoto' ),
    'section'   => 'title_tagline',
    'settings'  => 'acmephoto_theme_options[acmephoto-header-id-display-opt]',
    'type'	  	=> 'radio',
    'priority'  => 20
) );