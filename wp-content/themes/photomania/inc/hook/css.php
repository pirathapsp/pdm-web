<?php
/**
 * CSS related hooks.
 *
 * This file contains hook functions which are related to CSS.
 *
 * @package Photomania
 */

if ( ! function_exists( 'photomania_trigger_custom_css_action' ) ) :

	/**
	 * Do action theme custom CSS.
	 *
	 * @since 1.0.0
	 */
	function photomania_trigger_custom_css_action() {

		/**
		 * Hook - photomania_action_theme_custom_css.
		 */
		do_action( 'photomania_action_theme_custom_css' );

	}

endif;

add_action( 'wp_head', 'photomania_trigger_custom_css_action', 99 );
