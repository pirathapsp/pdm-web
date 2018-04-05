<?php
defined( 'ABSPATH' ) or die( "No script kiddies please!" );

$apsl_settings = array();
$apsl_settings['network_ordering'] = $_POST['network_ordering'];

//for facebook settings
foreach( $_POST['apsl_facebook_settings'] as $key => $value ) {
    $$key = sanitize_text_field( $value );
}

$apsl_facebook_enable = isset( $apsl_facebook_enable ) ? $apsl_facebook_enable : '';
$facebook_parameters = array(	'apsl_facebook_enable' => $apsl_facebook_enable,
								'apsl_facebook_app_id' => $apsl_facebook_app_id,
								'apsl_facebook_app_secret' => $apsl_facebook_app_secret,
								'apsl_profile_image_width' => $apsl_profile_image_width,
								'apsl_profile_image_height' => $apsl_profile_image_height
							);
$apsl_settings['apsl_facebook_settings'] = $facebook_parameters;

//for twitter settings
foreach( $_POST['apsl_twitter_settings'] as $key => $value ) {
    $$key = sanitize_text_field( $value );
}
$apsl_twitter_enable = isset( $apsl_twitter_enable ) ? $apsl_twitter_enable : '';

$twitter_parameters = array('apsl_twitter_enable' => $apsl_twitter_enable, 'apsl_twitter_api_key' => $apsl_twitter_api_key, 'apsl_twitter_api_secret' => $apsl_twitter_api_secret);
$apsl_settings['apsl_twitter_settings'] = $twitter_parameters;

//for google settings
foreach( $_POST['apsl_google_settings'] as $key => $value ) {
    $$key = sanitize_text_field( $value );
}

$apsl_google_enable = isset( $apsl_google_enable ) ? $apsl_google_enable : '';
$google_parameters = array('apsl_google_enable' => $apsl_google_enable, 'apsl_google_client_id' => $apsl_google_client_id, 'apsl_google_client_secret' => $apsl_google_client_secret);

$apsl_settings['apsl_google_settings'] = $google_parameters;
$apsl_settings['apsl_enable_disable_plugin'] = $_POST['apsl_enable_disable_plugin'];
$display_options = array();
if( isset( $_POST['apsl_display_options'] ) ) {
    foreach( $_POST['apsl_display_options'] as $key => $value ) {
        $display_options[] = $value;
    }
}

$apsl_settings['apsl_display_options'] = $display_options;
$apsl_settings['apsl_icon_theme'] = $_POST['apsl_icon_theme'];
$apsl_settings['apsl_title_text_field'] = sanitize_text_field( $_POST['apsl_title_text_field'] );
$apsl_settings['apsl_custom_logout_redirect_options'] = sanitize_text_field( $_POST['apsl_custom_logout_redirect_options'] );
$apsl_settings['apsl_custom_logout_redirect_link'] = sanitize_text_field( $_POST['apsl_custom_logout_redirect_link'] );
$apsl_settings['apsl_custom_login_redirect_options'] = sanitize_text_field( $_POST['apsl_custom_login_redirect_options'] );
$apsl_settings['apsl_custom_login_redirect_link'] = sanitize_text_field( $_POST['apsl_custom_login_redirect_link'] );
$apsl_settings['apsl_user_avatar_options'] = $_POST['apsl_user_avatar_options'];
$apsl_settings['apsl_send_email_notification_options'] = $_POST['apsl_send_email_notification_options'];

//for saving the settings
update_option( APSL_SETTINGS, $apsl_settings );
$_SESSION['apsl_message'] = __( 'Settings Saved Successfully.', 'accesspress-social-login-lite' );
wp_redirect( admin_url() . 'admin.php?page=' . 'accesspress-social-login-lite' );
exit;
