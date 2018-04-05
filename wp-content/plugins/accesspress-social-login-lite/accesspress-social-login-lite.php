<?php defined( 'ABSPATH' ) or die( "No script kiddies please!" );
/*
  Plugin name: Social Login WordPress Plugin - AccessPress Social Login Lite
  Plugin URI: https://accesspressthemes.com/wordpress-plugins/accesspress-social-login-lite/
  Description: A plugin to add various social logins to a site.
  version: 3.3.3
  Author: AccessPress Themes
  Author URI: https://accesspressthemes.com/
  Text Domain: accesspress-social-login-lite
  Domain Path: /languages/
  License: GPLv2 or later
*/
//Declearation of the necessary constants for plugin
if( !defined( 'APSL_VERSION' ) ) {
    define( 'APSL_VERSION', '3.3.3' );
}

if( !defined( 'APSL_IMAGE_DIR' ) ) {
    define( 'APSL_IMAGE_DIR', plugin_dir_url( __FILE__ ) . 'images' );
}

if( !defined( 'APSL_JS_DIR' ) ) {
    define( 'APSL_JS_DIR', plugin_dir_url( __FILE__ ) . 'js' );
}

if( !defined( 'APSL_CSS_DIR' ) ) {
    define( 'APSL_CSS_DIR', plugin_dir_url( __FILE__ ) . 'css' );
}

if( !defined( 'APSL_LANG_DIR' ) ) {
    define( 'APSL_LANG_DIR', basename( dirname( __FILE__ ) ) . '/languages/' );
}

if( !defined( 'APSL_TEXT_DOMAIN' ) ) {
    define( 'APSL_TEXT_DOMAIN', 'accesspress-social-login-lite' );
}

if( !defined( 'APSL_SETTINGS' ) ) {
    define( 'APSL_SETTINGS', 'apsl-lite-settings' );
}

if( !defined( 'APSL_PLUGIN_DIR' ) ) {
    define( 'APSL_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}
/**
 * Register a widget
 *
 */
include_once( 'inc/backend/widget.php' );

if (version_compare(get_bloginfo('version'), '4.3.1', '>=')){
	// Redefine user notification function
	if ( !function_exists('wp_new_user_notification') ) {

	    function wp_new_user_notification( $user_id, $deprecated = null, $notify = 'both' ) {
	    if ( $deprecated !== null ) {
	        _deprecated_argument( __FUNCTION__, '4.3.1' );
	    }

	    global $wpdb, $wp_hasher;
	    $user = get_userdata( $user_id );
	    if ( empty ( $user ) )
	        return;

	    // The blogname option is escaped with esc_html on the way into the database in sanitize_option
	    // we want to reverse this for the plain text arena of emails.
	    $blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);

	    $message  = sprintf(__('New user registration on your site %s:'), $blogname) . "\r\n\r\n";
	    $message .= sprintf(__('Username: %s'), $user->user_login) . "\r\n\r\n";
	    $message .= sprintf(__('E-mail: %s'), $user->user_email) . "\r\n";

	    @wp_mail(get_option('admin_email'), sprintf(__('[%s] New User Registration'), $blogname), $message);

	    if ( 'admin' === $notify || empty( $notify ) ) {
	        return;
	    }

	    // Generate something random for a password reset key.
	    $key = wp_generate_password( 20, false );

	    /** This action is documented in wp-login.php */
	    do_action( 'retrieve_password_key', $user->user_login, $key );

	    // Now insert the key, hashed, into the DB.
	    if ( empty( $wp_hasher ) ) {
	        require_once ABSPATH . WPINC . '/class-phpass.php';
	        $wp_hasher = new PasswordHash( 8, true );
	    }
	    $hashed = time() . ':' . $wp_hasher->HashPassword( $key );
	    $wpdb->update( $wpdb->users, array( 'user_activation_key' => $hashed ), array( 'user_login' => $user->user_login ) );

	    $message = sprintf(__('Username: %s'), $user->user_login) . "\r\n\r\n";
	    $message .= __('To set your password, visit the following address:') . "\r\n\r\n";
	    $message .= '<' . network_site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($user->user_login), 'login') . ">\r\n\r\n";

	    $message .= wp_login_url() . "\r\n\r\n";
	        $message .= sprintf( __('If you have any problems, please contact us at %s.'), get_option('admin_email') ) . "\r\n\r\n";
	    $message .= __('Adios!') . "\r\n\r\n";

	    wp_mail($user->user_email, sprintf(__('[%s] Your username and password info'), $blogname), $message);
	    }
	}
}else{
	// for wordpress version less than 4.3.1
    // Redefine user notification function
	if(!function_exists( 'wp_new_user_notification' )){
	    function wp_new_user_notification( $user_id, $plaintext_pass = '' ) {
	        $user = new WP_User($user_id);

	        $user_login = stripslashes($user->user_login);
	        $user_email = stripslashes($user->user_email);

	        $message  = sprintf(__('New user registration on your site %s:'), get_option('blogname')) . "\r\n\r\n";
	        $message .= sprintf(__('Username: %s'), $user_login) . "\r\n\r\n";
	        $message .= sprintf(__('E-mail: %s'), $user_email) . "\r\n";
	        $message .= __('Thanks!');

	        $headers = 'From:'.get_option('blogname').' <'.get_option('admin_email').'>' . "\r\n";
	        @wp_mail(get_option('admin_email'), sprintf(__('[%s] New User Registration'), get_option('blogname')), $message, $headers);

	        if ( empty($plaintext_pass) )
	            return;

	        $message  = __('Hi there,') . "\r\n\r\n";
	        $message .= sprintf(__("Welcome to %s! Here's how to log in:"), get_option('blogname')) . "\r\n\r\n";
	        $message .= wp_login_url() . "\r\n";
	        $message .= sprintf(__('Username: %s'), $user_login) . "\r\n";
	        $message .= sprintf(__('Password: %s'), $plaintext_pass) . "\r\n\r\n";
	        $message .= sprintf(__('If you have any problems, please contact me at %s.'), get_option('admin_email')) . "\r\n\r\n";
	        $message .= __('Thanks!');

	        $headers = 'From:'.get_option('blogname').' <'.get_option('admin_email').'>' . "\r\n";

	        wp_mail($user_email, sprintf(__('[%s] Your username and password'), get_option('blogname')), $message, $headers);

	    }
	}
}


// Declaration of the class
if( !class_exists( 'APSL_Lite_Class' ) ) {
    
    class APSL_Lite_Class {
        
        var $apsl_settings;
        
        function __construct() {
            $this->apsl_settings = get_option( APSL_SETTINGS );
            add_action( 'init', array($this, 'session_init') ); //start the session if not started yet.
            register_activation_hook( __FILE__, array($this, 'plugin_activation') ); //load the default setting for the plugin while activating
            add_action( 'init', array($this, 'plugin_text_domain') ); //load the plugin text domain
            add_action( 'admin_menu', array($this, 'add_apsl_menu') ); //register the plugin menu in backend
            add_action( 'admin_enqueue_scripts', array($this, 'register_admin_assets') ); //registers all the assets required for wp-admin
            add_action( 'wp_enqueue_scripts', array($this, 'register_frontend_assets') ); // registers all the assets required for the frontend
            add_action( 'admin_post_apsl_save_options', array($this, 'save_settings') ); //save settings of a plugin
            
            $options = get_option( APSL_SETTINGS );
            if( $options['apsl_enable_disable_plugin'] == 'yes' ) {
                if( in_array( "login_form", $options['apsl_display_options'] ) ) {
                    add_action( 'login_form', array($this, 'add_social_login') ); // add the social logins to the login form
                    add_action( 'woocommerce_login_form', array($this, 'add_social_login_form_to_comment') );
                    
                }
                
                if( in_array( "register_form", $options['apsl_display_options'] ) ) {
                    add_action( 'register_form', array($this, 'add_social_login') ); //add the social logins to the registration form
                    add_action( 'after_signup_form', array($this, 'add_social_login') );
                }
                
                if( in_array( "comment_form", $options['apsl_display_options'] ) ) {
                    add_action( 'comment_form_top', array($this, 'add_social_login_form_to_comment') ); //add the social logins to the comment form
                    add_action( 'comment_form_must_log_in_after', array($this, 'add_social_login_form_to_comment') ); // add the social login buttons if “Users must be registered and logged in to comment” checked in the discussions settings.
                    
                }
            }
            
            add_shortcode( 'apsl-login-lite', array($this, 'apsl_shortcode') ); //adds a shortcode
            add_action( 'init', array($this, 'login_check') ); //check for the social logins
            add_action( 'widgets_init', array($this, 'register_apsl_widget') ); //register the widget of a plugin
            add_action( 'login_enqueue_scripts', array($this, 'apsl_login_form_enqueue_style'), 10 );
            add_action( 'login_enqueue_scripts', array($this, 'apsl_login_form__enqueue_script'), 1 );
            add_action( 'admin_post_apsl_restore_default_settings', array($this, 'apsl_restore_default_settings') ); //restores default settings.
            
            
            /**
             * Hook to display custom avatars
             */
            add_filter( 'get_avatar', array($this, 'apsl_social_login_custom_avatar'), 10, 5 );

            //add delete action when user is deleted from wordpress backend.
            add_action( 'delete_user', array ($this, 'apsl_delete_user') );
        }
        
        function apsl_social_login_custom_avatar( $avatar, $mixed, $size, $default, $alt = '' ) {
            $options = get_option( APSL_SETTINGS );
            //Check if we have an user identifier
            if( is_numeric( $mixed ) AND $mixed > 0 ) {
                $user_id = $mixed;
            }
            //Check if we have an user email
            elseif( is_string( $mixed ) AND( $user = get_user_by( 'email', $mixed ) ) ) {
                $user_id = $user->ID;
            }
            //Check if we have an user object
            elseif( is_object( $mixed ) AND property_exists( $mixed, 'user_id' ) AND is_numeric( $mixed->user_id ) ) {
                $user_id = $mixed->user_id;
            }
            //None found
            else {
                $user_id = null;
            }
            //User found?
            if( !empty( $user_id ) ) {
                //Override current avatar ?
                $override_avatar = true;
                //Read the avatar
                $user_meta_thumbnail = get_user_meta( $user_id, 'deuimage', true );
                //read user details
                $user_meta_name = get_user_meta( $user_id, 'first_name', true );
                
                if( $options['apsl_user_avatar_options'] == 'social' ) {
                    $user_picture =( !empty( $user_meta_thumbnail ) ? $user_meta_thumbnail : '' );
                    //Avatar found?
                    if( $user_picture !== false AND strlen( trim( $user_picture ) ) > 0 ) {
                        return '<img alt="' . $user_meta_name . '" src="' . $user_picture . '" class="avatar apsl-avatar-social-login avatar-' . $size . ' photo" height="' . $size . '" width="' . $size . '" />';
                    }
                }
            }
            return $avatar;
        }
        //starts the session with the call of init hook
        function session_init() {
            if( !session_id() && !headers_sent() ) {
                session_start();
            }
        }
        //load the default settings of the plugin
        function plugin_activation() {
            if( !get_option( APSL_SETTINGS ) ) {
                include( 'inc/backend/activation.php' );
            }
            self:: apsl_database_install();
        }

        //create the table to store the user details to the plugin
        function apsl_database_install() {
            global $wpdb;

            // create user details table
            $apsl_userdetails = "{$wpdb->prefix}apsl_users_social_profile_details";
            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

            $sql = "CREATE TABLE IF NOT EXISTS `$apsl_userdetails` (
                    id int(11) NOT NULL AUTO_INCREMENT,
                    user_id int(11) NOT NULL,
                    provider_name varchar(50) NOT NULL,
                    identifier varchar(255) NOT NULL,
                    unique_verifier varchar(255) NOT NULL,
                    email varchar(255) NOT NULL,
                    email_verified varchar(255) NOT NULL,
                    first_name varchar(150) NOT NULL,
                    last_name varchar(150) NOT NULL,
                    profile_url varchar(255) NOT NULL,
                    website_url varchar(255) NOT NULL,
                    photo_url varchar(255) NOT NULL,
                    display_name varchar(150) NOT NULL,
                    description varchar(255) NOT NULL,
                    gender varchar(10) NOT NULL,
                    language varchar(20) NOT NULL,
                    age varchar(10) NOT NULL,
                    birthday int(11) NOT NULL,
                    birthmonth int(11) NOT NULL,
                    birthyear int(11) NOT NULL,
                    phone varchar(75) NOT NULL,
                    address varchar(255) NOT NULL,
                    country varchar(75) NOT NULL,
                    region varchar(50) NOT NULL,
                    city varchar(50) NOT NULL,
                    zip varchar(25) NOT NULL,
                    UNIQUE KEY id (id),
                    KEY user_id (user_id),
                    KEY provider_name (provider_name)
                )";
                dbDelta( $sql );
        }

        //loads the text domain for translation
        function plugin_text_domain() {
            load_plugin_textdomain( 'accesspress-social-login-lite', false, APSL_LANG_DIR );
        }
        //register the plugin menu for backend.
        function add_apsl_menu() {
            add_menu_page( 'AccessPress Social Login Lite', 'AccessPress Social Login Lite', 'manage_options', 'accesspress-social-login-lite', array($this, 'main_page'), APSL_IMAGE_DIR . '/icon.png' );
        }
        //menu page
        function main_page() {
            include( 'inc/backend/main-page.php' );
        }
        //registration of the backend assets
        function register_admin_assets() {
            wp_enqueue_style( 'fontawsome-css', APSL_CSS_DIR .'/font-awesome/font-awesome.min.css', '', APSL_VERSION );
            if( isset( $_GET['page'] ) && $_GET['page'] == 'accesspress-social-login-lite' ) {
                //backend scripts
                wp_enqueue_script( 'jquery-ui-sortable' );
                wp_enqueue_script( 'apsl-admin-js', APSL_JS_DIR . '/backend.js', array('jquery', 'jquery-ui-sortable'), APSL_VERSION ); //registering plugin's admin js
                //register backend css
                wp_enqueue_style( 'apsl-backend-css', APSL_CSS_DIR . '/backend.css', '', APSL_VERSION );
            }
        }
        //registration of the plugins frontend assets
        function register_frontend_assets() {
            //register frontend scripts
            wp_enqueue_script( 'apsl-frontend-js', APSL_JS_DIR . '/frontend.js', array('jquery'), APSL_VERSION );
            
            //register frontend css
            // wp_enqueue_style( 'fontawsome-css', '//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css', '', APSL_VERSION );
            wp_enqueue_style( 'fontawsome-css', APSL_CSS_DIR .'/font-awesome/font-awesome.min.css', '', APSL_VERSION );

            wp_enqueue_style( 'apsl-frontend-css', APSL_CSS_DIR . '/frontend.css', '', APSL_VERSION );
        }
        //save the settings of a plugin
        function save_settings() {
            if( isset( $_POST['apsl_save_settings'] ) && $_POST['apsl_settings_action'] && wp_verify_nonce( $_POST['apsl_settings_action'], 'apsl_nonce_save_settings' ) ) {
                include( 'inc/backend/save-settings.php' );
            }
            else {
                die( 'No script kiddies please!' );
            }
        }
        //function to add the social login in the login and registration form.
        function add_social_login() {
            if( !is_user_logged_in() ) {
                include( 'inc/frontend/login_integration.php' );
            }
        }
        //function to add the social login in the comment form.
        function add_social_login_form_to_comment() {
            $options = get_option( APSL_SETTINGS );
            $login_text = $options['apsl_title_text_field'];
            if( !is_user_logged_in() ) {
                echo do_shortcode( "[apsl-login-lite login_text='{$login_text}']" );
            }
        }
        //function for adding shortcode of a plugin
        function apsl_shortcode( $attr ) {
            ob_start();
            include( 'inc/frontend/shortcode.php' );
            $html = ob_get_contents();
            ob_get_clean();
            return $html;
        }
        //checking of the login
        function login_check() {
            include( 'inc/frontend/login_check.php' );
        }
        //registration of the social login widget
        function register_apsl_widget() {
            register_widget( 'APSL_Lite_Widget' );
        }
        
        function apsl_login_form_enqueue_style() {
            wp_enqueue_style( 'fontawsome-css', '//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css', '', APSL_VERSION );
            wp_enqueue_style( 'apsl-backend-css', APSL_CSS_DIR . '/backend.css', '', APSL_VERSION );
            wp_enqueue_style( 'apsl-frontend-css', APSL_CSS_DIR . '/frontend.css', '', APSL_VERSION );
        }
        
        function apsl_login_form__enqueue_script() {
            wp_enqueue_script( 'apsl-admin-js', APSL_JS_DIR . '/backend.js', array('jquery', 'jquery-ui-sortable'), APSL_VERSION ); //registering plugin's admin js
            
        }
        
        function apsl_restore_default_settings() {
            $nonce = $_REQUEST['_wpnonce'];
            if( !empty( $_GET ) && wp_verify_nonce( $nonce, 'apsl-restore-default-settings-nonce' ) ) {
                //restore the default plugin activation settings from the activation page.
                include( 'inc/backend/activation.php' );
                $_SESSION['apsl_message'] = __( 'Settings restored Successfully.', 'accesspress-social-login-lite' );
                wp_redirect( admin_url() . 'admin.php?page=' . 'accesspress-social-login-lite' );
                exit;
            }
            else {
                die( 'No script kiddies please!' );
            }
        }

        function apsl_delete_user( $user_id ) {
            global $wpdb;
            $table_name = $apsl_userdetails = "{$wpdb->prefix}apsl_users_social_profile_details";
            $user_obj = get_userdata( $user_id );
            $result = $wpdb->delete( $table_name, array( 'user_id' => $user_id ) );
        }
    } //class termination

}
$apsl_object = new APSL_Lite_Class();
