<?php
/**
 * Home Sections Options.
 *
 * @package Photomania
 */

$default = photomania_get_default_theme_options();

// Add Panel.
$wp_customize->add_panel( 'theme_home_sections_panel',
	array(
		'title'      => __( 'Homepage Sections', 'photomania' ),
		'priority'   => 100,
		'capability' => 'edit_theme_options',
		)
);

// Home Section Manager.
$wp_customize->add_section( 'section_home_sections_manager',
	array(
		'title'      => __( 'Manage Sections', 'photomania' ),
		'priority'   => 100,
		'capability' => 'edit_theme_options',
		'panel'      => 'theme_home_sections_panel',
		)
);

// Setting homepage_sections.
$wp_customize->add_setting( 'theme_options[homepage_sections]',
	array(
		'default'           => $default['homepage_sections'],
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'sanitize_text_field',
		)
);

$wp_customize->add_control(
	new Photomania_Section_Manager_Control(
		$wp_customize,
		'theme_options[homepage_sections]',
		array(
			'label'    => esc_html__( 'Toggle sections', 'photomania' ),
			'section'  => 'section_home_sections_manager',
			'settings' => 'theme_options[homepage_sections]',
			'priority' => 1,
			'args'     => array(
				'sections' => photomania_get_home_sections_options(),
				),
			)
	)
);

// Home Section Call To Action.
$wp_customize->add_section( 'section_home_call_to_action',
	array(
		'title'      => __( 'Call To Action', 'photomania' ),
		'priority'   => 100,
		'capability' => 'edit_theme_options',
		'panel'      => 'theme_home_sections_panel',
		)
);

// Setting cta_title.
$wp_customize->add_setting( 'theme_options[cta_title]',
	array(
		'default'           => $default['cta_title'],
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'sanitize_text_field',
		)
);
$wp_customize->add_control( 'theme_options[cta_title]',
	array(
		'label'    => __( 'Title', 'photomania' ),
		'section'  => 'section_home_call_to_action',
		'type'     => 'text',
		'priority' => 100,
		)
);

// Setting cta_description.
$wp_customize->add_setting( 'theme_options[cta_description]',
	array(
		'default'           => $default['cta_description'],
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'sanitize_text_field',
		)
);
$wp_customize->add_control( 'theme_options[cta_description]',
	array(
		'label'    => __( 'Description', 'photomania' ),
		'section'  => 'section_home_call_to_action',
		'type'     => 'text',
		'priority' => 100,
		)
);

// Setting cta_primary_button_text.
$wp_customize->add_setting( 'theme_options[cta_primary_button_text]',
	array(
		'default'           => $default['cta_primary_button_text'],
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'sanitize_text_field',
		)
);
$wp_customize->add_control( 'theme_options[cta_primary_button_text]',
	array(
		'label'    => __( 'Primary Button Text', 'photomania' ),
		'section'  => 'section_home_call_to_action',
		'type'     => 'text',
		'priority' => 100,
		)
);

// Setting cta_primary_button_url.
$wp_customize->add_setting( 'theme_options[cta_primary_button_url]',
	array(
		'default'           => $default['cta_primary_button_url'],
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'esc_url_raw',
		)
);
$wp_customize->add_control( 'theme_options[cta_primary_button_url]',
	array(
		'label'    => __( 'Primary Button URL', 'photomania' ),
		'section'  => 'section_home_call_to_action',
		'type'     => 'text',
		'priority' => 100,
		)
);

// Setting cta_secondary_button_text.
$wp_customize->add_setting( 'theme_options[cta_secondary_button_text]',
	array(
		'default'           => $default['cta_secondary_button_text'],
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'sanitize_text_field',
		)
);
$wp_customize->add_control( 'theme_options[cta_secondary_button_text]',
	array(
		'label'    => __( 'Secondary Button Text', 'photomania' ),
		'section'  => 'section_home_call_to_action',
		'type'     => 'text',
		'priority' => 100,
		)
);

// Setting cta_secondary_button_url.
$wp_customize->add_setting( 'theme_options[cta_secondary_button_url]',
	array(
		'default'           => $default['cta_secondary_button_url'],
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'esc_url_raw',
		)
);
$wp_customize->add_control( 'theme_options[cta_secondary_button_url]',
	array(
		'label'    => __( 'Secondary Button URL', 'photomania' ),
		'section'  => 'section_home_call_to_action',
		'type'     => 'text',
		'priority' => 100,
		)
);

// Home Section Portfolio.
$wp_customize->add_section( 'section_home_portfolio',
	array(
		'title'      => __( 'Portfolio', 'photomania' ),
		'priority'   => 100,
		'capability' => 'edit_theme_options',
		'panel'      => 'theme_home_sections_panel',
		)
);

// Setting portfolio_title.
$wp_customize->add_setting( 'theme_options[portfolio_title]',
	array(
		'default'           => $default['portfolio_title'],
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'sanitize_text_field',
		)
);
$wp_customize->add_control( 'theme_options[portfolio_title]',
	array(
		'label'    => __( 'Title', 'photomania' ),
		'section'  => 'section_home_portfolio',
		'type'     => 'text',
		'priority' => 100,
		)
);

// Setting portfolio_number.
$wp_customize->add_setting( 'theme_options[portfolio_number]',
	array(
		'default'           => $default['portfolio_number'],
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'photomania_sanitize_number_range',
		)
);
$wp_customize->add_control( 'theme_options[portfolio_number]',
	array(
		'label'       => __( 'No of Posts', 'photomania' ),
		'section'     => 'section_home_portfolio',
		'type'        => 'number',
		'priority'    => 100,
		'input_attrs' => array( 'min' => 1, 'max' => 20, 'step' => 1, 'style' => 'width: 55px;' ),
		)
);

// Setting portfolio_category.
$wp_customize->add_setting( 'theme_options[portfolio_category]',
	array(
		'default'           => $default['portfolio_category'],
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'photomania_sanitize_multiple_dropdown_taxonomies',
	)
);
$wp_customize->add_control(
	new Photomania_Dropdown_Taxonomies_Control( $wp_customize, 'theme_options[portfolio_category]',
		array(
			'label'       => __( 'Select Category', 'photomania' ),
			'description' => __( 'Hold down the Ctrl (windows) / Command (Mac) button to select multiple options.', 'photomania' ),
			'section'     => 'section_home_portfolio',
			'settings'    => 'theme_options[portfolio_category]',
			'priority'    => 100,
			'multiple'    => true,
		)
	)
);

// Setting portfolio_enable_popup.
$wp_customize->add_setting( 'theme_options[portfolio_enable_popup]',
	array(
		'default'           => $default['portfolio_enable_popup'],
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'photomania_sanitize_checkbox',
	)
);
$wp_customize->add_control( 'theme_options[portfolio_enable_popup]',
	array(
		'label'    => __( 'Enable Popup', 'photomania' ),
		'section'  => 'section_home_portfolio',
		'type'     => 'checkbox',
		'priority' => 100,
	)
);

// Setting portfolio_enable_social.
$wp_customize->add_setting( 'theme_options[portfolio_enable_social]',
	array(
		'default'           => $default['portfolio_enable_social'],
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'photomania_sanitize_checkbox',
	)
);
$wp_customize->add_control( 'theme_options[portfolio_enable_social]',
	array(
		'label'    => __( 'Enable Social Share', 'photomania' ),
		'section'  => 'section_home_portfolio',
		'type'     => 'checkbox',
		'priority' => 100,
	)
);

// Setting portfolio_enable_browse.
$wp_customize->add_setting( 'theme_options[portfolio_enable_browse]',
	array(
		'default'           => $default['portfolio_enable_browse'],
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'photomania_sanitize_checkbox',
	)
);
$wp_customize->add_control( 'theme_options[portfolio_enable_browse]',
	array(
		'label'    => __( 'Enable Browse Button', 'photomania' ),
		'section'  => 'section_home_portfolio',
		'type'     => 'checkbox',
		'priority' => 100,
	)
);
