<?php
/**
 * Default theme options.
 *
 * @package Photomania
 */

if ( ! function_exists( 'photomania_get_default_theme_options' ) ) :

	/**
	 * Get default theme options.
	 *
	 * @since 1.0.0
	 *
	 * @return array Default theme options.
	 */
	function photomania_get_default_theme_options() {

		$defaults = array();

		// Header.
		$defaults['show_title']   = true;
		$defaults['show_tagline'] = false;

		// Layout.
		$defaults['global_layout']           = 'no-sidebar';
		$defaults['archive_layout']          = 'masonry';
		$defaults['archive_image']           = 'large';
		$defaults['archive_image_alignment'] = 'center';
		$defaults['single_image']            = 'large';

		// Home Page.
		$defaults['home_content_status'] = true;

		// Footer.
		$defaults['copyright_text']        = esc_html__( 'Copyright &copy; All rights reserved.', 'photomania' );
		$defaults['show_social_in_footer'] = false;
		$defaults['go_to_top']             = true;

		// Blog.
		$defaults['excerpt_length']     = 40;
		$defaults['read_more_text']     = esc_html__( 'Read more', 'photomania' );
		$defaults['exclude_categories'] = '';

		// Breadcrumb.
		$defaults['breadcrumb_type'] = 'simple';

		// Social share.
		$defaults['social_share_enable_archive']     = true;
		$defaults['social_share_enable_single_post'] = true;

		// Homepage Sections.
		$defaults['homepage_sections'] = 'portfolio';

		// Homepage Portfolio.
		$defaults['portfolio_title']         = esc_html__( 'Recent Works', 'photomania' );
		$defaults['portfolio_category']      = array();
		$defaults['portfolio_number']        = 10;
		$defaults['portfolio_enable_popup']  = true;
		$defaults['portfolio_enable_social'] = true;
		$defaults['portfolio_enable_browse'] = false;

		// Homepage Call To Action.
		$defaults['cta_title']                 = esc_html__( 'We are creative', 'photomania' );
		$defaults['cta_description']           = esc_html__( 'This is about you. Always be yourself, express yourself, have faith in yourself, do not go out and look for a successful personality and duplicate it.', 'photomania' );
		$defaults['cta_primary_button_text']   = esc_html__( 'Learn more', 'photomania' );
		$defaults['cta_primary_button_url']    = '#';
		$defaults['cta_secondary_button_text'] = '';
		$defaults['cta_secondary_button_url']  = '';

		// Slider Options.
		$defaults['featured_slider_status']              = 'disabled';
		$defaults['featured_slider_transition_effect']   = 'fadeout';
		$defaults['featured_slider_transition_delay']    = 3;
		$defaults['featured_slider_transition_duration'] = 1;
		$defaults['featured_slider_enable_caption']      = true;
		$defaults['featured_slider_enable_arrow']        = true;
		$defaults['featured_slider_enable_pager']        = false;
		$defaults['featured_slider_enable_autoplay']     = true;
		$defaults['featured_slider_enable_searchform']   = true;
		$defaults['featured_slider_type']                = 'featured-page';
		$defaults['featured_slider_number']              = 3;
		$defaults['featured_slider_read_more_text']      = esc_html__( 'Read More', 'photomania' );

		// Pass through filter.
		$defaults = apply_filters( 'photomania_filter_default_theme_options', $defaults );
		return $defaults;
	}

endif;
