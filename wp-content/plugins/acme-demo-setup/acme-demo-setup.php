<?php
/*
Plugin Name: Acme Demo Setup
Plugin URI:
Description: One click demo import
Version: 1.0.7
Author: Acme Themes
Author URI: https://www.acmethemes.com/
License: GPLv2 or later
*/

/*Make sure we don't expose any info if called directly*/
if ( !function_exists( 'add_action' ) ) {
    echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
    exit;
}
/*Define Constants for this plugin*/
define( 'ACME_DEMO_SETUP_VERSION', '1.0.7' );
define( 'ACME_DEMO_SETUP_PATH', plugin_dir_path( __FILE__ ) );
define( 'ACME_DEMO_SETUP_URL', plugin_dir_url( __FILE__ ) );

/*Now lets init the functionality of this plugin*/
require_once( ACME_DEMO_SETUP_PATH . '/inc/init.php' );

/**
 * Load plugin textdomain.
 * see here https://ulrich.pogson.ch/load-theme-plugin-translations
 */
if ( ! function_exists( 'acme_demo_setup_load_textdomain' ) ) :
    function acme_demo_setup_load_textdomain() {

        $domain = 'acme-demo-setup';
        $locale = apply_filters( 'plugin_locale', get_locale(), $domain );

        // wp-content/languages/plugin-name/plugin-name-de_DE.mo
        load_textdomain( 'acme-demo-setup', trailingslashit( WP_LANG_DIR ) . $domain . '/' . $domain . '-' . $locale . '.mo' );

        // wp-content/plugins/plugin-name/languages/plugin-name-de_DE.mo
        load_plugin_textdomain( 'acme-demo-setup', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
    }
endif;
add_action( 'plugins_loaded', 'acme_demo_setup_load_textdomain' );


