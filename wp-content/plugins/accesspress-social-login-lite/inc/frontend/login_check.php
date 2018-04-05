<?php
defined( 'ABSPATH' ) or die( "No script kiddies please!" );

if( !class_exists( 'APSL_Lite_Login_Check_Class' ) ) {

    class APSL_Lite_Login_Check_Class {
        //constructor
        function __construct() {

            if( isset( $_GET['apsl_login_id'] ) ) {
                if( isset( $_REQUEST['state'] ) ) {
                    parse_str( base64_decode( $_REQUEST['state'] ), $state_vars );

                    if( isset( $state_vars['redirect_to'] ) ) {
                        $_GET['redirect_to'] = $_REQUEST['redirect_to'] = $state_vars['redirect_to'];
                    }
                }

                $exploder = explode( '_', $_GET['apsl_login_id'] );
                switch( $exploder[0] ) {
                    case 'facebook':
                        if( version_compare( PHP_VERSION, '5.4.0', '<' ) ) {
                            echo _e( 'The Facebook SDK requires PHP version 5.4 or higher. Please notify about this error to site admin.', 'accesspress-social-login-lite' );
                            die();
                        }
                        $this->onFacebookLogin();
                    break;
                    case 'twitter':
                        if( !class_exists( 'TwitterOAuth' ) ) {
                            include( APSL_PLUGIN_DIR . 'twitter/OAuth.php' );
                            include( APSL_PLUGIN_DIR . 'twitter/twitteroauth.php' );
                        }
                        $this->onTwitterLogin();
                    break;
                    case 'google':
                    if( !class_exists( 'Google_Client' ) ) {
                        include( APSL_PLUGIN_DIR . 'google/Client.php' );
                    }
                    if(!class_exists('Google_Service_Plus')){
                        include( APSL_PLUGIN_DIR . 'google/Service/Plus.php' );
                    }
                        $this->onGoogleLogin();
                    break;
                }
            }
        }
        //for facebook login
        function onFacebookLogin() {
            $response = new stdClass();
            $result = $this->facebookLogin( $response );
            if( isset( $result->status ) && $result->status == 'SUCCESS' ) {
                global $wpdb;
                $unique_verifier = sha1($result->deutype.$result->deuid);
                $sql = "SELECT *  FROM  `{$wpdb->prefix}apsl_users_social_profile_details` WHERE  `provider_name` LIKE  '$result->deutype' AND  `identifier` LIKE  '$result->deuid' AND `unique_verifier` LIKE '$unique_verifier'";
                $row = $wpdb->get_row($sql);
                if( !$row ) {
                    //check if there is already a user with the email address provided from social login already
                    $user_details_by_email = $this->getUserByMail($result->email);
                    if( $user_details_by_email != false ){
                        //user already there so log him in
                        $id = $user_details_by_email->ID;
                        $sql = "SELECT *  FROM  `{$wpdb->prefix}apsl_users_social_profile_details` WHERE  `user_id` LIKE  '$id'; ";
                        $row = $wpdb->get_row($sql);
                        if(!$row){
                        self:: link_user($id, $result);
                        }
                        self:: loginUser( $id );
                        die();
                    }
                    $_SESSION['user_details']= $result;
                    
                    // use FB id as username if sanitized username is empty
                    $sanitized_user_name = sanitize_user( $result->username, true );
                    if ( empty( $sanitized_user_name ) ) {
                    $sanitized_user_name = $result->deuid;
                    }
                    $user_Id = self::creatUser( $sanitized_user_name, $result->email );
                    $user_row = self:: getUserByMail( $result->email );
                    $id = $user_row->ID;
                    $result = $result;
                    $role = 'subscriber';
                    self:: UpdateUserMeta( $id, $result, $role );
                    self:: loginUser( $id );
                    exit();
                }else{
                    if( ($row->provider_name == $result->deutype) && ($row->identifier == $result->deuid) ){
                        //echo "user found in our database";
                        self:: loginUser( $row->user_id );
                        exit();
                    }else{
                        // user not found in our database
                        // need to handle an exception
                    }
                }
            }else{
                if(isset($_REQUEST['error'])){
                    $_SESSION['apsl_login_error_flag'] = 1;
                    $redirect_url = isset($_REQUEST['redirect_to']) ? $_REQUEST['redirect_to'] : site_url();
                    $this->redirect($redirect_url);
                }
                die();
            }
        }
        
        function facebookLogin() {
            $request = $_REQUEST;
            $site = $this->siteUrl();
            $callBackUrl = $this->callBackUrl();
            $response = new stdClass();
            $return_user_details = new stdClass();
            $exploder = explode( '_', $_GET['apsl_login_id'] );
            $action = $exploder[1];
            $options = get_option( APSL_SETTINGS );
            if(isset($options['apsl_facebook_settings']['apsl_profile_image_width'])){
                $width  = $options['apsl_facebook_settings']['apsl_profile_image_width'];
            }else{
                $width  = 150;
            }

            if(isset($options['apsl_facebook_settings']['apsl_profile_image_height'])){
                $height = $options['apsl_facebook_settings']['apsl_profile_image_height'];
            }else{
                $height = 150;
            }

            $config = array('app_id' => $options['apsl_facebook_settings']['apsl_facebook_app_id'], 'app_secret' => $options['apsl_facebook_settings']['apsl_facebook_app_secret'], 'default_graph_version' => 'v2.4', 'persistent_data_handler' => 'session' );
            include( APSL_PLUGIN_DIR . 'facebook/autoload.php' );
            $fb = new Facebook\Facebook( $config );

            $callback = $callBackUrl . 'apsl_login_id' . '=facebook_check';

            if( $action == 'login' ) {
                // Well looks like we are a fresh dude, login to Facebook!
                $helper = $fb->getRedirectLoginHelper();
                $permissions = array('email', 'public_profile'); // optional
                $loginUrl = $helper->getLoginUrl( $callback, $permissions );

                $encoded_url = isset( $_GET['redirect_to'] ) ? $_GET['redirect_to'] : '';
                if( isset( $encoded_url ) && $encoded_url != '' ) {
                    setcookie("apsl_login_redirect_url", $encoded_url, time()+3600);
                    // $callback = $callBackUrl . 'apsl_login_id' . '=facebook_check&redirect_to=' . $encoded_url;
                }
                $this->redirect( $loginUrl );
            }
            else {
                if( isset( $_REQUEST['error'] ) ) {
                    $response->status = 'ERROR';
                    $response->error_code = 2;
                    $response->error_message = 'INVALID AUTHORIZATION';
                    return $response;
                    die();
                }
                if( isset( $_REQUEST['code'] ) ) {
                    $helper = $fb->getRedirectLoginHelper();
                    // Trick below will avoid "Cross-site request forgery validation failed. Required param "state" missing." from Facebook
                    $_SESSION['FBRLH_state'] = $_REQUEST['state'];
                    try {
                        $accessToken = $helper->getAccessToken($callback);
                    }
                    catch( Facebook\Exceptions\FacebookResponseException $e ) {
                        // When Graph returns an error
                        echo 'Graph returned an error: ' . $e->getMessage();
                        exit;
                    }
                    catch( Facebook\Exceptions\FacebookSDKException $e ) {
                        // When validation fails or other local issues
                        echo 'Facebook SDK returned an error: ' . $e->getMessage();
                        exit;
                    }
                    
                    if( isset( $accessToken ) ) {
                        // Logged in!
                        $_SESSION['facebook_access_token'] = (string)$accessToken;
                        $fb->setDefaultAccessToken( $accessToken );

                        try {
                            $response = $fb->get( '/me?fields=email,name, first_name, last_name, gender, link, about, birthday, education, hometown, is_verified, languages, location, website' );
                            $userNode = $response->getGraphUser();
                        }
                        catch( Facebook\Exceptions\FacebookResponseException $e ) {
                            // When Graph returns an error
                            echo 'Graph returned an error: ' . $e->getMessage();
                            exit;
                        }
                        catch( Facebook\Exceptions\FacebookSDKException $e ) {
                            // When validation fails or other local issues
                            echo 'Facebook SDK returned an error: ' . $e->getMessage();
                            exit;
                        }
                        // get the user profile details
                        $user_profile = $this->accessProtected( $userNode, 'items' );
                        if( $user_profile != null ) {
                            $return_user_details->status = 'SUCCESS';
                            $return_user_details->deuid = $user_profile['id'];
                            $return_user_details->deutype = 'facebook';
                            $return_user_details->first_name = $user_profile['first_name'];
                            $return_user_details->last_name = $user_profile['last_name'];
                            if(isset($user_profile['email']) || $user_profile['email'] != ''){
                                $user_email = $user_profile['email'];
                            }else{
                                $user_email = $user_profile['id'].'@facebook.com';
                            }
                            $return_user_details->email = $user_email;
                            $return_user_details->username = ($user_profile['first_name'] !='') ? strtolower( $user_profile['first_name'] ) : $user_email;
                            $return_user_details->gender = isset($user_profile['gender']) ? $user_profile['gender'] : 'N/A';
                            $return_user_details->url = $user_profile['link'];
                            $return_user_details->about = ''; //facebook doesn't return user about details.
                            $headers = get_headers( 'https://graph.facebook.com/' . $user_profile['id'] . '/picture?width='.$width.'&height='.$height, 1 );
                            // just a precaution, check whether the header isset...
                            if( isset( $headers['Location'] ) ) {
                                $return_user_details->deuimage = $headers['Location']; // string
                                
                            }
                            else {
                                $return_user_details->deuimage = false; // nothing there? .. weird, but okay!
                                
                            }
                            $return_user_details->error_message = '';
                        }
                        else {
                            $return_user_details->status = 'ERROR';
                            $return_user_details->error_code = 2;
                            $return_user_details->error_message = 'INVALID AUTHORIZATION';
                        }
                    }
                }
                else {
                    // Well looks like we are a fresh dude, login to Facebook!
                    $helper = $fb->getRedirectLoginHelper();
                    $permissions = array('email', 'public_profile'); // optional
                    $loginUrl = $helper->getLoginUrl( $callback, $permissions );
                    $this->redirect( $loginUrl );
                }
            }
            return $return_user_details;
        }
        //for twitter login
        function onTwitterLogin() {
            $result = $this->twitterLogin();
            if( isset( $result->status ) && $result->status == 'SUCCESS' ) {
                global $wpdb;
                $unique_verifier = sha1($result->deutype.$result->deuid);
                $sql = "SELECT *  FROM  `{$wpdb->prefix}apsl_users_social_profile_details` WHERE  `provider_name` LIKE  '$result->deutype' AND  `identifier` LIKE  '$result->deuid' AND `unique_verifier` LIKE '$unique_verifier'";
                $row = $wpdb->get_row($sql);
                if( !$row ) {
                    //check if there is already a user with the email address provided from social login already
                    $user_details_by_email = $this->getUserByMail($result->email);
                    if( $user_details_by_email != false ){
                        //user already there so log him in
                        $id = $user_details_by_email->ID;
                        $sql = "SELECT *  FROM  `{$wpdb->prefix}apsl_users_social_profile_details` WHERE  `user_id` LIKE  '$id'; ";
                        $row = $wpdb->get_row($sql);
                        // var_dump($row);
                        if(!$row){
                        self:: link_user($id, $result);
                        }
                        self:: loginUser( $id );
                        die();
                    }
                    $_SESSION['user_details']= $result;
                    self::creatUser( $result->username, $result->email );
                    $user_row = self:: getUserByMail( $result->email );
                    $id = $user_row->ID;
                    $result = $result;
                    $role = 'subscriber';
                    self:: UpdateUserMeta( $id, $result, $role );
                    self:: loginUser( $id );
                    exit();
                }else{
                    if( ($row->provider_name == $result->deutype) && ($row->identifier == $result->deuid) ){
                        //echo "user found in our database";
                        self:: loginUser( $row->user_id );
                        exit();
                    }else{
                        // user not found in our database
                        // need to handle an exception
                    }
                }
                    $_SESSION['apsl_login_error_flag'] = 1;
            }else{
                if(isset($_REQUEST['denied'])){
                    $redirect_url = isset($_REQUEST['redirect_to']) ? $_REQUEST['redirect_to'] : site_url();
                    $this->redirect($redirect_url);
                }
                die();
            }
        }
        
        function twitterLogin() {
            $request = $_REQUEST;
            $site = $this->siteUrl();
            $callBackUrl = $this->callBackUrl();
            $response = new stdClass();
            $exploder = explode( '_', $_GET['apsl_login_id'] );
            $action = $exploder[1];
            @session_start();
            $options = get_option( APSL_SETTINGS );
            if( $action == 'login' ) {
                // Get identity from user and redirect browser to OpenID Server
                if( !isset( $request['oauth_token'] ) || $request['oauth_token'] == '' ) {
                    $twitterObj = new TwitterOAuth( $options['apsl_twitter_settings']['apsl_twitter_api_key'], $options['apsl_twitter_settings']['apsl_twitter_api_secret'] );
                    $encoded_url = isset( $_GET['redirect_to'] ) ? $_GET['redirect_to'] : '';
                    if( isset( $encoded_url ) && $encoded_url != '' ) {
                        $callback = $callBackUrl . 'apsl_login_id' . '=twitter_check&redirect_to=' . $encoded_url;
                    }
                    else {
                        $callback = $callBackUrl . 'apsl_login_id' . '=twitter_check';
                    }
                    
                    $request_token = $twitterObj->getRequestToken( $callback );
                    $_SESSION['oauth_twitter'] = array();
                    /* Save temporary credentials to session. */
                    $_SESSION['oauth_twitter']['oauth_token'] = $token = $request_token['oauth_token'];
                    $_SESSION['oauth_twitter']['oauth_token_secret'] = $request_token['oauth_token_secret'];
                    /* If last connection failed don't display authorization link. */
                    switch( $twitterObj->http_code ) {
                        case 200:
                            try {
                                $url = $twitterObj->getAuthorizeUrl( $token );
                                $this->redirect( $url );
                            }
                            catch( Exception $e ) {
                                $response->status = 'ERROR';
                                $response->error_code = 2;
                                $response->error_message = 'Could not get AuthorizeUrl.';
                            }
                        break;
                        default:
                            $response->status = 'ERROR';
                            $response->error_code = 2;
                            $response->error_message = 'Could not connect to Twitter. Refresh the page or try again later.';
                        break;
                    }
                }
                else {
                    $response->status = 'ERROR';
                    $response->error_code = 2;
                    $response->error_message = 'INVALID AUTHORIZATION';
                }
            }
            else if( isset( $request['oauth_token'] ) && isset( $request['oauth_verifier'] ) ) {
                /* Create TwitteroAuth object with app key/secret and token key/secret from default phase */
                $twitterObj = new TwitterOAuth( $options['apsl_twitter_settings']['apsl_twitter_api_key'], $options['apsl_twitter_settings']['apsl_twitter_api_secret'], $_SESSION['oauth_twitter']['oauth_token'], $_SESSION['oauth_twitter']['oauth_token_secret'] );
                /* Remove no longer needed request tokens */
                unset( $_SESSION['oauth_twitter'] );
                try {
                    $access_token = $twitterObj->getAccessToken( $request['oauth_verifier'] );
                    /* If HTTP response is 200 continue otherwise send to connect page to retry */
                    if( 200 == $twitterObj->http_code ) {
                        $user_profile = $twitterObj->get( 'account/verify_credentials', array(
                                                                            'screen_name' => $access_token['screen_name'],
                                                                            'skip_status' => 'true',
                                                                            'include_entities' => 'true',
                                                                            'include_email' => 'true'
                                                                            )
                                                        );
                        /* Request access twitterObj from twitter */
                        $response->status = 'SUCCESS';
                        $response->deuid = $user_profile->id;
                        $response->deutype = 'twitter';
                        $response->name = explode( ' ', $user_profile->name, 2 );
                        $response->first_name = $response->name[0];
                        $response->last_name =( isset( $response->name[1] ) ) ? $response->name[1] : '';
                        $response->deuimage = $user_profile->profile_image_url_https;
                        $response->email = isset($user_profile->email) ? $user_profile->email : $user_profile->screen_name . '@twitter.com';
                        $response->username = ($user_profile->screen_name !='') ? strtolower($user_profile->screen_name) : $user_email;
                        $response->url = $user_profile->url;
                        $response->about = isset($user_profile->description) ? $user_profile->description : '';
                        $response->gender = isset($user_profile->gender) ? $user_profile->gender : 'N/A';
                        $response->location = $user_profile->location;
                        $response->error_message = '';
                    }
                    else {
                        $response->status = 'ERROR';
                        $response->error_code = 2;
                        $response->error_message = 'Could not connect to Twitter. Refresh the page or try again later.';
                    }
                }
                catch( Exception $e ) {
                    $response->status = 'ERROR';
                    $response->error_code = 2;
                    $response->error_message = 'Could not get AccessToken.';
                }
            }
            else { // User Canceled your Request
                $response->status = 'ERROR';
                $response->error_code = 1;
                $response->error_message = "USER CANCELED REQUEST";
            }
            return $response;
        }
        //for google login
        function onGoogleLogin() {
            $result = $this->GoogleLogin();
            if( isset( $result->status ) && $result->status == 'SUCCESS' ) {
                global $wpdb;
                $unique_verifier = sha1($result->deutype.$result->deuid);
                $sql = "SELECT *  FROM  `{$wpdb->prefix}apsl_users_social_profile_details` WHERE  `provider_name` LIKE  '$result->deutype' AND  `identifier` LIKE  '$result->deuid' AND `unique_verifier` LIKE '$unique_verifier'";
                $row = $wpdb->get_row($sql);
                if( !$row ) {
                    //check if there is already a user with the email address provided from social login already
                    $user_details_by_email = $this->getUserByMail($result->email);
                    if( $user_details_by_email != false ){
                        //user already there so log him in
                        $id = $user_details_by_email->ID;
                        $sql = "SELECT *  FROM  `{$wpdb->prefix}apsl_users_social_profile_details` WHERE  `user_id` LIKE  '$id'; ";
                        $row = $wpdb->get_row($sql);
                        if(!$row){
                        self:: link_user($id, $result);
                        }
                        self:: loginUser( $id );
                        die();
                    }
                    $_SESSION['user_details']= $result;
                    self::creatUser( $result->username, $result->email );
                    $user_row = self:: getUserByMail( $result->email );
                    $id = $user_row->ID;
                    $result = $result;
                    $role = 'subscriber';
                    self:: UpdateUserMeta( $id, $result, $role );
                    self:: loginUser( $id );
                    exit();
                }else{
                    if( ($row->provider_name == $result->deutype) && ($row->identifier == $result->deuid) ){
                        //echo "user found in our database";
                        self:: loginUser( $row->user_id );
                        exit();
                    }else{
                        // user not found in our database
                        // need to handle an exception
                    }
                }
            }else{
                if(isset($_REQUEST['error'])){
                    $_SESSION['apsl_login_error_flag'] = 1;
                    $redirect_url = isset($_REQUEST['redirect_to']) ? $_REQUEST['redirect_to'] : site_url();
                    $this->redirect($redirect_url);
                }
                die();
            }
        }
        
        function GoogleLogin() {
            $post = $_POST;
            $get = $_GET;
            $request = $_REQUEST;
            $site = $this->siteUrl();
            $callBackUrl = $this->callBackUrl();
            $options = get_option( APSL_SETTINGS );
            $response = new stdClass();
            $a = explode( '_', $_GET['apsl_login_id'] );
            $action = $a[1];
            $client_id = $options['apsl_google_settings']['apsl_google_client_id'];
            $client_secret = $options['apsl_google_settings']['apsl_google_client_secret'];
            
            $site_url = site_url() . '/wp-admin';
            $encoded_url = isset( $_GET['redirect_to'] ) ? $_GET['redirect_to'] : $site_url;
            $callback = $callBackUrl . 'apsl_login_id' . '=google_check';
            
            $redirect_uri = $callback;
            $client = new Google_Client;
            
            $client->setClientId( $client_id );
            $client->setClientSecret( $client_secret );
            $client->setRedirectUri( $redirect_uri );
            $client->addScope( "https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/plus.profile.emails.read" );
            if( isset( $encoded_url ) && $encoded_url != '' ) {
                $client->setState( base64_encode( "redirect_to=$encoded_url" ) );
            }
            
            $service = new Google_Service_Plus( $client );
            
            if( $action == 'login' ) { // Get identity from user and redirect browser to OpenID Server
            	unset($_SESSION['access_token']);
                if( !( isset( $_SESSION['access_token'] ) && $_SESSION['access_token'] ) ) {
                    $authUrl = $client->createAuthUrl();
                    $this->redirect( $authUrl );
                    die();
                }
                else {
                    $this->redirect( $redirect_uri . "&redirect_to=$encoded_url" );
                    die();
                }
            }
            elseif( isset( $_GET['code'] ) ) { // Perform HTTP Request to OpenID server to validate key
                $client->authenticate( $_GET['code'] );
                $_SESSION['access_token'] = $client->getAccessToken();
                $this->redirect( $redirect_uri . "&redirect_to=$encoded_url" );
                die();
            }
            elseif( isset( $_SESSION['access_token'] ) && $_SESSION['access_token'] ) {
                $client->setAccessToken( $_SESSION['access_token'] );
                
                try {
                    $user = $service->people->get( "me", array() );
                }
                catch( Exception $fault ) {
                    unset( $_SESSION['access_token'] );
                    $ref_object = $this->accessProtected( $fault, 'errors' );
                    echo $ref_object[0]['message'] . " Please notify about this error to the Site Admin.";
                    die();
                }
                
                if( !empty( $user ) ) {
                    if( !empty( $user->emails ) ) {
                        
                        $response->email = $user->emails[0]->value;
                        $response->username = ($user->name->givenName) ? strtolower($user->name->givenName) : $user_email;
                        $response->first_name = $user->name->givenName;
                        $response->last_name = $user->name->familyName;
                        $response->deuid = $user->emails[0]->value;
                        $response->deuimage = $user->image->url;
                        $response->gender = isset($user->gender) ? $user->gender : 'N/A';
                        $response->id = $user->id;
                        $response->about = $user->aboutMe;
                        $response->url = $user->url;
                        $response->deutype = 'google';
                        $response->status = 'SUCCESS';
                        $response->error_message = '';
                    }
                    else {
                        $response->status = 'ERROR';
                        $response->error_code = 2;
                        $response->error_message = "INVALID AUTHORIZATION";
                    }
                }
                else { // Signature Verification Failed
                    $response->status = 'ERROR';
                    $response->error_code = 2;
                    $response->error_message = "INVALID AUTHORIZATION";
                }
            }
            else { // User failed to login
                $response->status = 'ERROR';
                $response->error_code = 3;
                $response->error_message = "USER LOGIN FAIL";
            }
            return $response;
        }
        //other remaining methods
        function siteUrl() {
            return site_url();
        }

        function callBackUrl() {
            // $connection = !empty( $_SERVER['HTTPS'] ) ? 'https://' : 'http://';
            $url = wp_login_url();
            if( strpos( $url, '?' ) === false ) {
                $url.= '?';
            } 
            else {
                $url.= '&';
            }
            return $url;
        }
        //function to return json values from social media urls
        function get_json_values( $url ) {
            $response = wp_remote_get( $url );
            $json_response = wp_remote_retrieve_body( $response );
            return $json_response;
        }

        function redirect( $redirect ) {
            if( headers_sent() ) { // Use JavaScript to redirect if content has been previously sent (not recommended, but safe)
                echo '<script language="JavaScript" type="text/javascript">window.location=\'';
                echo $redirect;
                echo '\';</script>';
            }
            else { // Default Header Redirect
                header( 'Location: ' . $redirect );
            }
            exit;
        }

        static function get_username($user_name){
            $username = $user_name;
            $i = 1;
            while(username_exists( $username )){
                $username = $user_name.'_'.$i;
                $i++;
            }
            return $username;
        }

        function updateUser( $username, $email ) {
            $row = $this->getUserByUsername( $username );
            if( $row && $email != '' && $row->user_email != $email ) {
                $row = (array)$row;
                $row['user_email'] = $email;
                wp_update_user( $row );
            }
        }

        function getUserByMail( $email ) {
            global $wpdb;
            $row = $wpdb->get_row( "SELECT * FROM $wpdb->users WHERE user_email = '$email'" );
            if( $row ) {
                return $row;
            }
            return false;
        }

        function getUserByUsername( $username ) {
            global $wpdb;
            $row = $wpdb->get_row( "SELECT * FROM $wpdb->users WHERE user_login = '$username'" );
            if( $row ) {
                return $row;
            }
            return false;
        }

        function creatUser( $user_name, $user_email ) {
        	$username = self:: get_username($user_name);
            $random_password = wp_generate_password( 12, false );
            $user_id = wp_create_user( $username, $random_password, $user_email );
            do_action( 'APSL_createUser', $user_id ); //hookable function to perform additional work after creation of user.
            $options = get_option( APSL_SETTINGS );
            if( $options['apsl_send_email_notification_options'] == 'yes' ) {
                if (version_compare(get_bloginfo('version'), '4.3.1', '>=')){
                    wp_new_user_notification( $user_id, $deprecated = null, $notify = 'both' );
                }else{
                    wp_new_user_notification( $user_id, $random_password );
                }
            }
            return $user_id;
        }

        function set_cookies( $user_id = 0, $remember = true ) {
            if( !function_exists( 'wp_set_auth_cookie' ) ) {
                return false;
            }
            if( !$user_id ) {
                return false;
            }
            wp_clear_auth_cookie();
            wp_set_auth_cookie( $user_id, $remember );
            wp_set_current_user( $user_id );
            return true;
        }

        function loginUser( $user_id ) {
            $current_url_an = get_permalink();
            $reauth = empty( $_REQUEST['reauth'] ) ? false : true;
            if( $reauth )wp_clear_auth_cookie();
            
            if( isset( $_REQUEST['redirect_to'] ) ) {
                $redirect_to = $_REQUEST['redirect_to'];
                // Redirect to https if user wants ssl
                if( isset( $secure_cookie ) && false !== strpos( $redirect_to, 'wp-admin' ) )$redirect_to = preg_replace( '|^http://|', 'https://', $redirect_to );
            }
            else {
                $redirect_to = admin_url();
            }
            if( !isset( $secure_cookie ) && is_ssl() && !force_ssl_admin() &&( 0 !== strpos( $redirect_to, 'https' ) ) &&( 0 === strpos( $redirect_to, 'http' ) ) )$secure_cookie = false;
            // If cookies are disabled we can't log in even with a valid user+pass
            if( isset( $_POST['testcookie'] ) && empty( $_COOKIE[TEST_COOKIE] ) )$user = new WP_Error( 'test_cookie', __( "<strong>ERROR</strong>: Cookies are blocked or not supported by your browser. You must <a href='http://www.google.com/cookies.html'>enable cookies</a> to use WordPress." ) );
            else $user = wp_signon( '', isset( $secure_cookie ) );

            if( !$this->set_cookies( $user_id ) ) {
                return false;
            }
            $requested_redirect_to = isset( $_REQUEST['redirect_to'] ) ? $_REQUEST['redirect_to'] : site_url();
            $user_login_url = apply_filters( 'login_redirect', $redirect_to, $requested_redirect_to, $user );

            $options = get_option( APSL_SETTINGS );
            if( isset( $options['apsl_custom_login_redirect_options'] ) && $options['apsl_custom_login_redirect_options'] != '' ) {
                if( $options['apsl_custom_login_redirect_options'] == 'home' ) {
                    $user_login_url = home_url();
                }
                else if( $options['apsl_custom_login_redirect_options'] == 'current_page' ) {
                    if( isset( $_REQUEST['redirect_to'] ) ) {
                        $redirect_to = $_REQUEST['redirect_to'];
                        // Redirect to https if user wants ssl
                        if( isset( $secure_cookie ) && false !== strpos( $redirect_to, 'wp-admin' ) )$user_login_url = preg_replace( '|^http://|', 'https://', $redirect_to );
                    }
                    else {
                        $user_login_url = home_url();
                    }
                }
                else if( $options['apsl_custom_login_redirect_options'] == 'custom_page' ) {
                    if( $options['apsl_custom_login_redirect_link'] != '' ) {
                        $login_page = $options['apsl_custom_login_redirect_link'];
                        $user_login_url = $login_page;
                    }
                    else {
                        $user_login_url = home_url();
                    }
                }
            }
            else {
                $user_login_url = home_url();
            }
            $redirect_to = $user_login_url;
            $redirect_to = apply_filters( 'login_redirect', $redirect_to );
            $redirect_to = isset($_COOKIE["apsl_login_redirect_url"]) ? urldecode($_COOKIE["apsl_login_redirect_url"]) : $redirect_to;
            // echo "<script> window.close(); window.opener.location.href='$redirect_to'; </script>";
            wp_safe_redirect( $redirect_to );
            exit();
        }
        //returns the current page url
        public static function curPageURL() {
            $pageURL = 'http';
            if( isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] == 'on' ) {
                $pageURL.= "s";
            }
            $pageURL.= "://";
            if( $_SERVER["SERVER_PORT"] != "80" ) {
                $pageURL.= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
            } 
            else {
                $pageURL.= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
            }
            return $pageURL;
        }
        //function to access the protected object properties
        function accessProtected( $obj, $prop ) {
            $reflection = new ReflectionClass( $obj );
            $property = $reflection->getProperty( $prop );
            $property->setAccessible( true );
            return $property->getValue( $obj );
        }


        //insert the user data into plugin's custom database
        static function link_user($id, $result){
            global $wpdb;
            $unique_verifier = sha1($result->deutype.$result->deuid);
            $apsl_userdetails = "{$wpdb->prefix}apsl_users_social_profile_details";

            $first_name = sanitize_text_field($result->first_name);
            $last_name = sanitize_text_field($result->last_name);
            $profile_url = sanitize_text_field($result->url);
            $photo_url = sanitize_text_field( $result->deuimage);
            $display_name = sanitize_text_field( $result->first_name . ' ' . $result->last_name);
            $description = sanitize_text_field($result->about);

            $table_name     = $apsl_userdetails;
            $submit_array   = array(
                                   "user_id"        => $id,
                                   "provider_name"  => $result->deutype,
                                   "identifier"     => $result->deuid,
                                   "unique_verifier" => $unique_verifier,
                                   "email"          => $result->email,
                                   "first_name"     => $first_name,
                                   "last_name"      => $last_name,
                                   "profile_url"    =>$profile_url,
                                   "photo_url"      =>$photo_url,
                                   "display_name"   =>$display_name,
                                   "description"    =>$description,
                                   "gender"         =>$result->gender
                                );
            $user_profile_details = $result;
            $wpdb->insert($table_name, $submit_array );
            if(!$result){
                echo "Data insertion failed";
                // die(mysql_error());
            }
        }

        //update the user meta data
        static function UpdateUserMeta( $id, $result, $role ) {
            update_user_meta( $id, 'email', $result->email );
            update_user_meta( $id, 'first_name', $result->first_name );
            update_user_meta( $id, 'last_name', $result->last_name );
            update_user_meta( $id, 'billing_first_name', $result->first_name );
            update_user_meta( $id, 'billing_last_name', $result->last_name );
            update_user_meta( $id, 'deuid', $result->deuid );
            update_user_meta( $id, 'deutype', $result->deutype );
            update_user_meta( $id, 'deuimage', $result->deuimage );
            update_user_meta( $id, 'description', $result->about );
            update_user_meta( $id, 'sex', $result->gender );
            wp_update_user( array(
                'ID' => $id,
                'display_name' => $result->first_name . ' ' . $result->last_name,
                // 'role' => $role,
                'user_url' => $result->url
            ) );

            global $wpdb;
            $unique_verifier = sha1($result->deutype.$result->deuid);
            $apsl_userdetails = "{$wpdb->prefix}apsl_users_social_profile_details";

            $first_name = sanitize_text_field($result->first_name);
            $last_name = sanitize_text_field($result->last_name);
            $profile_url = sanitize_text_field($result->url);
            $photo_url = sanitize_text_field( $result->deuimage);
            $display_name = sanitize_text_field( $result->first_name . ' ' . $result->last_name);
            $description = sanitize_text_field($result->about);

            $table_name     = $apsl_userdetails;
            $submit_array   = array(
                                   "user_id"        => $id,
                                   "provider_name"  => $result->deutype,
                                   "identifier"     => $result->deuid,
                                   "unique_verifier" => $unique_verifier,
                                   "email"          => $result->email,
                                   "first_name"     => $first_name,
                                   "last_name"      => $last_name,
                                   "profile_url"    =>$profile_url,
                                   "photo_url"      =>$photo_url,
                                   "display_name"   =>$display_name,
                                   "description"    =>$description,
                                   "gender"         =>$result->gender
                                );
            $user_profile_details = $result;
            $wpdb->insert($table_name, $submit_array );

            // if(function_exists('bp_has_profile')){
            //     self:: apsl_buddypress_xprofile_mapping($id, $user_profile_details->deutype, $user_profile_details);
            // }
            if(!$result){
                echo "Data insertion failed";
                // die(mysql_error());
            }
        }

    } //termination of a class

} //end of if statement

$apsl_login_check = new APSL_Lite_Login_Check_Class();
