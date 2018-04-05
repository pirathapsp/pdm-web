<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
$apsl_settings = array();
$social_networks = array(0 => 'facebook', 1 => 'twitter', 2 => 'google');

$apsl_settings['network_ordering'] = $social_networks;
//facebook settings
$facebook_parameters = array('apsl_facebook_enable' => '0', 'apsl_facebook_app_id' => '', 'apsl_facebook_app_secret' => '', 'apsl_profile_image_width' => '50', 'apsl_profile_image_height' => '50');
$apsl_settings['apsl_facebook_settings'] = $facebook_parameters;
//twitter settings
$twitter_parameters = array('apsl_twitter_enable' => '0', 'apsl_twitter_api_key' => '', 'apsl_twitter_api_secret' => '');
$apsl_settings['apsl_twitter_settings'] = $twitter_parameters;
//google settings
$google_parameters = array('apsl_google_enable' => '0', 'apsl_google_client_id' => '', 'apsl_google_client_secret' => '');

$apsl_settings['apsl_google_settings'] = $google_parameters;

$apsl_settings['apsl_enable_disable_plugin'] = 'yes';

$display_options = array('login_form', 'register_form', 'comment_form');
$apsl_settings['apsl_display_options'] = $display_options;

$apsl_settings['apsl_icon_theme'] = '1';

$apsl_settings['apsl_title_text_field'] = 'Social connect:';
$apsl_settings['apsl_custom_logout_redirect_options'] = 'home';
$apsl_settings['apsl_custom_logout_redirect_link'] = '';

$apsl_settings['apsl_custom_login_redirect_options'] = 'home';
$apsl_settings['apsl_custom_login_redirect_link'] = '';

$apsl_settings['apsl_user_avatar_options'] = 'default';

$apsl_settings['apsl_send_email_notification_options'] = 'yes';

update_option( APSL_SETTINGS, $apsl_settings );
