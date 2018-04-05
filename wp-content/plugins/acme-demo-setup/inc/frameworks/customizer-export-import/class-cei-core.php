<?php
/**
 * The main export/import class from customizer-export-import.
 * Renaming CEI_Core to Acme_Demo_Setup_CEI_Core
 * @since 1.0.0
 */
final class Acme_Demo_Setup_CEI_Core {

	/**
	 * An array of core options that shouldn't be imported.
	 *
	 * @since 1.0.0
	 * @access private
	 * @var array $core_options
	 */
	static private $core_options = array(
		'blogname',
		'blogdescription',
		'show_on_front',
		'page_on_front',
		'page_for_posts',
	);
	
	/**
     * Acme Customized Version
	 * Imports uploaded mods and calls WordPress core customize_save actions so
	 * themes that hook into them can act before mods are saved to the database.
	 *
	 * @since 1.0
	 * @access public
	 * @param object $wp_customize An instance of WP_Customize_Manager.
	 */
	 function _import( $file_url ) {
        $template	 = get_template();

        WP_Filesystem();

        // Setup global vars.
        global $wp_filesystem;
        global $wp_customize;


        $raw = $wp_filesystem->get_contents( $file_url );
        if ( $error  = is_wp_error( $raw ) ) {
            return $error;
        }

		$data = @unserialize( $raw );

		// Data checks.
		if ( 'array' != gettype( $data ) ) {
			esc_html_e( 'Error importing settings! Please check that you uploaded a customizer export file.', 'acme-demo-setup' );
			return;
		}
		if ( ! isset( $data['template'] ) || ! isset( $data['mods'] ) ) {
            esc_html_e( 'Error importing settings! Please check that you uploaded a customizer export file.', 'acme-demo-setup' );
			return;
		}
		if ( $data['template'] != $template ) {
            esc_html_e( 'Error importing settings! The settings you uploaded are not for the current theme.', 'acme-demo-setup' );
			return;
		}
		
		// Import images.
        $data['mods'] = self::_import_images( $data['mods'] );

		// Import custom options.
		if ( isset( $data['options'] ) ) {
			
			foreach ( $data['options'] as $option_key => $option_value ) {
				
				$option = new Acme_Demo_Setup_CEI_Option( $wp_customize, $option_key, array(
					'default'		=> '',
					'type'			=> 'option',
					'capability'	=> 'edit_theme_options'
				) );
				
				$option->import( $option_value );
			}
		}

		// Call the customize_save action.
		do_action( 'customize_save', $wp_customize );

		// Loop through the mods.
		foreach ( $data['mods'] as $key => $val ) {

			// Call the customize_save_ dynamic action.
			do_action( 'customize_save_' . $key, $wp_customize );

			// Save the mod.
			set_theme_mod( $key, $val );
		}

	}
	
	/**
	 * Imports images for settings saved as mods.
	 *
	 * @since 0.1
	 * @access private
	 * @param array $mods An array of customizer mods.
	 * @return array The mods array with any new import data.
	 */
	static private function _import_images( $mods ) 
	{
		foreach ( $mods as $key => $val ) {
			
			if ( self::_is_image_url( $val ) ) {
				
				$data = self::_sideload_image( $val );
				
				if ( ! is_wp_error( $data ) ) {
					
					$mods[ $key ] = $data->url;
					
					// Handle header image controls.
					if ( isset( $mods[ $key . '_data' ] ) ) {
						$mods[ $key . '_data' ] = $data;
						update_post_meta( $data->attachment_id, '_wp_attachment_is_custom_header', get_stylesheet() );
					}
				}
			}
		}
		
		return $mods;
	}
	
	/**
	 * Taken from the core media_sideload_image function and
	 * modified to return an array of data instead of html.
	 *
	 * @since 0.1
	 * @access private
	 * @param string $file The image file path.
	 * @return stdClass An array of image data.
	 */
	static private function _sideload_image( $file ) 
	{
		$data = new stdClass();
		
		if ( ! function_exists( 'media_handle_sideload' ) ) {
			require_once(ABSPATH . 'wp-admin/includes/media.php');
			require_once(ABSPATH . 'wp-admin/includes/file.php');
			require_once(ABSPATH . 'wp-admin/includes/image.php');
		}
		if ( ! empty( $file ) ) {
			
			// Set variables for storage, fix file filename for query strings.
			preg_match( '/[^\?]+\.(jpe?g|jpe|gif|png)\b/i', $file, $matches );
			$file_array = array();
			$file_array['name'] = basename( $matches[0] );
	
			// Download file to temp location.
			$file_array['tmp_name'] = download_url( $file );
	
			// If error storing temporarily, return the error.
			if ( is_wp_error( $file_array['tmp_name'] ) ) {
				return $file_array['tmp_name'];
			}
	
			// Do the validation and storage stuff.
			$id = media_handle_sideload( $file_array, 0 );
	
			// If error storing permanently, unlink.
			if ( is_wp_error( $id ) ) {
				@unlink( $file_array['tmp_name'] );
				return $id;
			}
			
			// Build the object to return.
			$meta					= wp_get_attachment_metadata( $id );
			$data->attachment_id	= $id;
			$data->url				= wp_get_attachment_url( $id );
			$data->thumbnail_url	= wp_get_attachment_thumb_url( $id );
			$data->height			= $meta['height'];
			$data->width			= $meta['width'];
		}
	
		return $data;
	}
	
	/**
	 * Checks to see whether a string is an image url or not.
	 *
	 * @since 0.1
	 * @access private
	 * @param string $string The string to check.
	 * @return bool Whether the string is an image url or not.
	 */
	static private function _is_image_url( $string = '' ){
		if ( is_string( $string ) ) {
            if ( preg_match( '/\.(jpg|jpeg|png|gif)/i', $string ) ) {
				return true;
			}
		}
		return false;
	}
}
