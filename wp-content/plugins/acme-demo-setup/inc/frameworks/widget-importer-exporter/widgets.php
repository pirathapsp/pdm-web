<?php
/**
 * Widget Functions
 * @since 1.0.0
 */

// No direct access
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Available widgets
 *
 * Gather site's widgets into array with ID base, name, etc.
 * Used by export and import functions.
 *
 * @since 0.4
 * @global array $wp_registered_widget_updates
 * @return array Widget information
 *
 * Remaning wie_available_widgets to acme_demo_setup_wie_available_widgets
 */
function acme_demo_setup_wie_available_widgets() {
	global $wp_registered_widget_controls;
	$widget_controls = $wp_registered_widget_controls;
	$available_widgets = array();
	foreach ( $widget_controls as $widget ) {
		if ( ! empty( $widget['id_base'] ) && ! isset( $available_widgets[$widget['id_base']] ) ) { // no dupes
			$available_widgets[$widget['id_base']]['id_base'] = $widget['id_base'];
			$available_widgets[$widget['id_base']]['name'] = $widget['name'];
		}
	}
	return apply_filters( 'acme_demo_setup_wie_available_widgets', $available_widgets );
}