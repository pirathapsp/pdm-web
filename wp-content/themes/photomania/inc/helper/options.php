<?php
/**
 * Helper functions related to customizer and options.
 *
 * @package Photomania
 */

if ( ! function_exists( 'photomania_get_global_layout_options' ) ) :

	/**
	 * Returns global layout options.
	 *
	 * @since 1.0.0
	 *
	 * @return array Options array.
	 */
	function photomania_get_global_layout_options() {

		$choices = array(
			'left-sidebar'  => esc_html__( 'Primary Sidebar - Content', 'photomania' ),
			'right-sidebar' => esc_html__( 'Content - Primary Sidebar', 'photomania' ),
			'three-columns' => esc_html__( 'Three Columns', 'photomania' ),
			'no-sidebar'    => esc_html__( 'No Sidebar', 'photomania' ),
		);
		$output = apply_filters( 'photomania_filter_layout_options', $choices );
		return $output;

	}

endif;

if ( ! function_exists( 'photomania_get_breadcrumb_type_options' ) ) :

	/**
	 * Returns breadcrumb type options.
	 *
	 * @since 1.0.0
	 *
	 * @return array Options array.
	 */
	function photomania_get_breadcrumb_type_options() {

		$choices = array(
			'disabled' => esc_html__( 'Disabled', 'photomania' ),
			'simple'   => esc_html__( 'Simple', 'photomania' ),
		);
		return $choices;

	}

endif;


if ( ! function_exists( 'photomania_get_archive_layout_options' ) ) :

	/**
	 * Returns archive layout options.
	 *
	 * @since 1.0.0
	 *
	 * @return array Options array.
	 */
	function photomania_get_archive_layout_options() {

		$choices = array(
			'full'    => esc_html__( 'Full Post', 'photomania' ),
			'excerpt' => esc_html__( 'Post Excerpt', 'photomania' ),
			'masonry' => esc_html__( 'Masonry Grid', 'photomania' ),
		);
		$output = apply_filters( 'photomania_filter_archive_layout_options', $choices );
		if ( ! empty( $output ) ) {
			ksort( $output );
		}
		return $output;

	}

endif;

if ( ! function_exists( 'photomania_get_image_sizes_options' ) ) :

	/**
	 * Returns image sizes options.
	 *
	 * @since 1.0.0
	 *
	 * @param bool  $add_disable True for adding No Image option.
	 * @param array $allowed Allowed image size options.
	 * @return array Image size options.
	 */
	function photomania_get_image_sizes_options( $add_disable = true, $allowed = array(), $show_dimension = true ) {

		global $_wp_additional_image_sizes;
		$get_intermediate_image_sizes = get_intermediate_image_sizes();
		$choices = array();
		if ( true === $add_disable ) {
			$choices['disable'] = esc_html__( 'No Image', 'photomania' );
		}
		$choices['thumbnail'] = esc_html__( 'Thumbnail', 'photomania' );
		$choices['medium']    = esc_html__( 'Medium', 'photomania' );
		$choices['large']     = esc_html__( 'Large', 'photomania' );
		$choices['full']      = esc_html__( 'Full (original)', 'photomania' );

		if ( true === $show_dimension ) {
			foreach ( array( 'thumbnail', 'medium', 'large' ) as $key => $_size ) {
				$choices[ $_size ] = $choices[ $_size ] . ' (' . get_option( $_size . '_size_w' ) . 'x' . get_option( $_size . '_size_h' ) . ')';
			}
		}

		if ( ! empty( $_wp_additional_image_sizes ) && is_array( $_wp_additional_image_sizes ) ) {
			foreach ( $_wp_additional_image_sizes as $key => $size ) {
				$choices[ $key ] = $key;
				if ( true === $show_dimension ){
					$choices[ $key ] .= ' ('. $size['width'] . 'x' . $size['height'] . ')';
				}
			}
		}

		if ( ! empty( $allowed ) ) {
			foreach ( $choices as $key => $value ) {
				if ( ! in_array( $key, $allowed ) ) {
					unset( $choices[ $key ] );
				}
			}
		}

		return $choices;

	}

endif;


if ( ! function_exists( 'photomania_get_image_alignment_options' ) ) :

	/**
	 * Returns image options.
	 *
	 * @since 1.0.0
	 *
	 * @return array Options array.
	 */
	function photomania_get_image_alignment_options() {

		$choices = array(
			'none'   => _x( 'None', 'Alignment', 'photomania' ),
			'left'   => _x( 'Left', 'Alignment', 'photomania' ),
			'center' => _x( 'Center', 'Alignment', 'photomania' ),
			'right'  => _x( 'Right', 'Alignment', 'photomania' ),
		);
		return $choices;

	}

endif;

if ( ! function_exists( 'photomania_get_featured_slider_transition_effects' ) ) :

	/**
	 * Returns the featured slider transition effects.
	 *
	 * @since 1.0.0
	 *
	 * @return array Options array.
	 */
	function photomania_get_featured_slider_transition_effects() {

		$choices = array(
			'fade'       => _x( 'fade', 'Transition Effect', 'photomania' ),
			'fadeout'    => _x( 'fadeout', 'Transition Effect', 'photomania' ),
			'none'       => _x( 'none', 'Transition Effect', 'photomania' ),
			'scrollHorz' => _x( 'scrollHorz', 'Transition Effect', 'photomania' ),
		);
		$output = apply_filters( 'photomania_filter_featured_slider_transition_effects', $choices );
		if ( ! empty( $output ) ) {
			ksort( $output );
		}
		return $output;

	}

endif;

if ( ! function_exists( 'photomania_get_featured_slider_content_options' ) ) :

	/**
	 * Returns the featured slider content options.
	 *
	 * @since 1.0.0
	 *
	 * @return array Options array.
	 */
	function photomania_get_featured_slider_content_options() {

		$choices = array(
			'home-page' => esc_html__( 'Static Front Page Only', 'photomania' ),
			'disabled'  => esc_html__( 'Disabled', 'photomania' ),
		);
		$output = apply_filters( 'photomania_filter_featured_slider_content_options', $choices );
		if ( ! empty( $output ) ) {
			ksort( $output );
		}
		return $output;

	}

endif;

if ( ! function_exists( 'photomania_get_featured_slider_type' ) ) :

	/**
	 * Returns the featured slider type.
	 *
	 * @since 1.0.0
	 *
	 * @return array Options array.
	 */
	function photomania_get_featured_slider_type() {

		$choices = array(
			'featured-page' => __( 'Featured Pages', 'photomania' ),
		);
		$output = apply_filters( 'photomania_filter_featured_slider_type', $choices );
		if ( ! empty( $output ) ) {
			ksort( $output );
		}
		return $output;

	}

endif;

if ( ! function_exists( 'photomania_get_numbers_dropdown_options' ) ) :

	/**
	 * Returns numbers dropdown options.
	 *
	 * @since 1.0.0
	 *
	 * @param int $min Min.
	 * @param int $max Max.
	 *
	 * @return array Options array.
	 */
	function photomania_get_numbers_dropdown_options( $min = 1, $max = 4 ) {

		$output = array();

		if ( $min <= $max ) {
			for ( $i = $min; $i <= $max; $i++ ) {
				$output[ $i ] = $i;
			}
		}

		return $output;

	}

endif;

if ( ! function_exists( 'photomania_get_home_sections_options' ) ) :

	/**
	 * Returns home sections options.
	 *
	 * @since 1.0.0
	 *
	 * @return array Options array.
	 */
	function photomania_get_home_sections_options() {

		$choices = array(
			'portfolio' => array(
				'label'    => __( 'Portfolio', 'photomania' ),
				'template' => 'template-parts/home/portfolio',
				),
			'call-to-action' => array(
				'label'    => __( 'Call To Action', 'photomania' ),
				'template' => 'template-parts/home/call-to-action',
				),
			);
		$output = apply_filters( 'photomania_filter_home_sections_options', $choices );
		return $output;

	}

endif;

