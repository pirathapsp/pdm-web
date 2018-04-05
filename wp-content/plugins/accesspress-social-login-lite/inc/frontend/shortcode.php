<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
$options = get_option( APSL_SETTINGS );
if ( is_user_logged_in() ) {
    global $current_user;
    $user_info = "<span class='display-name'>{$current_user->data->display_name}</span>&nbsp;";
    $user_info .= get_avatar( $current_user->ID, 20 );

    if ( !empty( $_GET['redirect'] ) )
        $current_url = $_GET['redirect'];
    else
        $current_url = APSL_Lite_Login_Check_Class::curPageURL();

    if ( isset( $options['apsl_custom_logout_redirect_options'] ) && $options['apsl_custom_logout_redirect_options'] != '' ) {
        if ( $options['apsl_custom_logout_redirect_options'] == 'home' ) {
            $user_logout_url = wp_logout_url( home_url() );
        } else if ( $options['apsl_custom_logout_redirect_options'] == 'current_page' ) {
            $user_logout_url = wp_logout_url( $current_url );
        } else if ( $options['apsl_custom_logout_redirect_options'] == 'custom_page' ) {
            if ( $options['apsl_custom_logout_redirect_link'] != '' ) {
                $logout_page = $options['apsl_custom_logout_redirect_link'];
                $user_logout_url = wp_logout_url( $logout_page );
            } else {
                $user_logout_url = wp_logout_url( $current_url );
            }
        }
    } else {
        $user_logout_url = wp_logout_url( $current_url );
    }
    ?><div class="user-login"><?php _e('Welcome', 'accesspress-social-login-lite'); ?> <b><?php echo $user_info; ?></b>&nbsp;|&nbsp;<a href="<?php echo $user_logout_url; ?>" title="<?php _e('Logout', 'accesspress-social-login-lite'); ?>"><?php _e('Logout', 'accesspress-social-login-lite'); ?></a></div>
    <?php
} else {
    if( isset( $options['apsl_custom_login_redirect_options'] ) && $options['apsl_custom_login_redirect_options'] != '' ) {
    if( $options['apsl_custom_login_redirect_options'] == 'home' ) {
        $user_login_url = home_url();
    }
    else if( $options['apsl_custom_login_redirect_options'] == 'current_page' ) {
        if ( !empty( $_GET['redirect'] ) )
            $current_url = $_GET['redirect'];
        else
            $current_url = APSL_Lite_Login_Check_Class::curPageURL();
        
        $user_login_url = $current_url;
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
}else {
    $user_login_url = home_url();
}

// $redirect_to = isset( $_REQUEST['redirect_to'] ) ? $_REQUEST['redirect_to'] : '';

$encoded_url = urlencode( $user_login_url );
    $theme = $options['apsl_icon_theme'];
    ?>
    <div class='apsl-login-networks theme-<?php echo $theme; ?> clearfix'>
        <?php if ( isset( $attr['login_text'] ) && $attr['login_text'] != '' ) { ?>
            <span class='apsl-login-new-text'><?php echo $attr['login_text']; ?></span>
        <?php } ?>
        <?php
        if(isset($_SESSION['apsl_login_error_flag']) && $_SESSION['apsl_login_error_flag'] == '1'){ ?>
            <div class='apsl-error'><?php _e('You have Access Denied. Please authorize the app to login.', 'accesspress-social-login-lite' ); ?></div>
            <?php
            unset($_SESSION['apsl_login_error_flag']);
        } ?>
        <?php if ( isset( $_REQUEST['error'] ) || isset( $_REQUEST['denied'] ) ) { ?>
            <div class='apsl-error'><?php _e( 'You have Access Denied. Please authorize the app to login.', 'accesspress-social-login-lite' ); ?></div>
        <?php } ?>
        <div class='social-networks'>
            <?php
            foreach ( $options['network_ordering'] as $key => $value ):
                if ( $options["apsl_{$value}_settings"]["apsl_{$value}_enable"] === 'enable' ) { ?>
                    <a href="<?php echo wp_login_url() ?>?apsl_login_id=<?php echo $value; ?>_login<?php
                    if ( $encoded_url ) {
                        echo "&state=" . base64_encode( "redirect_to=$encoded_url" );
                    }
                    ?>" title='<?php _e( 'Login with', 'accesspress-social-login-lite' ); echo ' ' . $value; ?>'>
                        <div class="apsl-icon-block icon-<?php echo $value; ?>">
                            <i class="fa fa-<?php echo $value; ?>"></i>
                            <span class="apsl-login-text"><?php _e( 'Login', 'accesspress-social-login-lite' ); ?></span>
                            <span class="apsl-long-login-text"><?php _e( 'Login with', 'accesspress-social-login-lite' ); ?><?php echo ' ' . $value; ?></span>
                        </div>
                    </a>
                    <?php
                }
            endforeach;
            ?>
        </div>
    </div>
<?php
}