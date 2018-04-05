<?php
/**
 * A class that extends WP_Customize_Setting so we can access
 * the protected updated method when importing options.
 *
 * @since 1.0.0
 *
 * Renaming CEI_Option to Acme_Demo_Setup_CEI_Option
 */
// Load WordPress Customize Setting Class.
if ( ! class_exists( 'WP_Customize_Setting' ) ) {
    require_once( ABSPATH . WPINC . '/class-wp-customize-setting.php' );
}

final class Acme_Demo_Setup_CEI_Option extends WP_Customize_Setting {

	/**
	 * Import an option value for this setting.
	 *
	 * @since 1.0.0
	 * @param mixed $value The option value.
	 * @return void
	 */
	public function import( $value )
	{
		$this->update( $value );
	}
}