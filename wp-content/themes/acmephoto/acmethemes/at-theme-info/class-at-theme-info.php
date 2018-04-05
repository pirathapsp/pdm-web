<?php
/**
 * Acme Themes - Theme Info Admin Menu
 * @package Acme Themes
 * @subpackage Admin
 */
if ( ! class_exists( 'Acmephoto_Theme_Info' ) ) {
    class Acmephoto_Theme_Info{

        private $config;
        private $theme_name;
        private $theme_slug;
        private $theme_version;
        private $page_title;
        private $menu_title;
        private $tabs;

        /**
         * Constructor.
         */
        public function __construct( $config ) {
            $this->config = $config;
            $this->prepare_class();

            /* activation notice */
            add_action( 'load-themes.php', array( $this, 'activation_admin_notice' ) );

            /*admin menu*/
            add_action( 'admin_menu', array( $this, 'at_admin_menu' ) );

            /* enqueue script and style for about page */
            add_action( 'admin_enqueue_scripts', array( $this, 'style_and_scripts' ) );

            /* ajax callback for dismissable required actions */
            add_action( 'wp_ajax_at_theme_info_update_recommended_action', array( $this, 'update_recommended_action_callback' ) );

        }
        /**
         * Adds an admin notice upon successful activation.
         */
        public function activation_admin_notice() {
            global $pagenow;
            if ( is_admin() && ( 'themes.php' == $pagenow ) && isset( $_GET['activated'] ) ) {
                add_action( 'admin_notices', array( $this, 'at_theme_info_welcome_admin_notice' ), 99 );
            }
        }

        /**
         * Display an admin notice linking to the about page
         */
        public function at_theme_info_welcome_admin_notice() {
            echo '<div class="updated notice is-dismissible">';
            echo ( '<p>' . sprintf( __('Welcome! Thank you for choosing %1$s! To fully take advantage of the best our theme can offer please make sure you visit our %2$swelcome page%3$s.','acmephoto'), $this->theme_name, '<a href="' . esc_url( admin_url( 'themes.php?page=' . $this->theme_slug . '-info' ) ) . '">', '</a>' ) . '</p><p><a href="' . esc_url( admin_url( 'themes.php?page=' . $this->theme_slug . '-info' ) ) . '" class="button" style="text-decoration: none;">' . sprintf( __('Get started with %s','acmephoto'), $this->theme_name ) . '</a></p>' );
            echo '</div>';
        }

        /**
         * Prepare and setup class properties.
         */
        public function prepare_class() {
            $theme = wp_get_theme();
            if ( is_child_theme() ) {
                $this->theme_name = $theme->parent()->get( 'Name' );
            } else {
                $this->theme_name = $theme->get( 'Name' );
            }
            $this->theme_slug    = $theme->get_template();
            $this->theme_version = $theme->get( 'Version' );
            $this->page_title    = isset( $this->config['page_title'] ) ? $this->config['page_title'] : esc_html__('Info','acmephoto'). $this->theme_name;
            $this->menu_title    = isset( $this->config['menu_title'] ) ? $this->config['menu_title'] : esc_html__('Info','acmephoto') . $this->theme_name;
            $this->notification  = isset( $this->config['notification'] ) ? $this->config['notification'] : ( '<p>' . sprintf( __('Welcome! Thank you for choosing %1$s! To fully take advantage of the best our theme can offer please make sure you visit our %2$swelcome page%3$s.','acmephoto'), $this->theme_name, '<a href="' . esc_url( admin_url( 'themes.php?page=' . $this->theme_slug . '-info' ) ) . '">', '</a>' ) . '</p><p><a href="' . esc_url( admin_url( 'themes.php?page=' . $this->theme_slug . '-info' ) ) . '" class="button" style="text-decoration: none;">' . sprintf( __('Get started with %s','acmephoto'), $this->theme_name ) . '</a></p>' );
            $this->tabs          = isset( $this->config['tabs'] ) ? $this->config['tabs'] : array();

        }

        /**
         * Return the valid array of recommended actions.
         * @return array The valid array of recommended actions.
         */
        /**
         * Dismiss required actions
         */
        public function update_recommended_action_callback() {

            /*getting for provided array*/
            $recommended_actions = isset( $this->config['recommended_actions'] ) ? $this->config['recommended_actions'] : array();

            /*from js action*/
            $action_id = esc_attr( ( isset( $_GET['id'] ) ) ? $_GET['id'] : 0 );
            $todo = esc_attr( ( isset( $_GET['todo'] ) ) ? $_GET['todo'] : '' );

            /*getting saved actions*/
            $saved_actions = get_option( $this->theme_slug . '_recommended_actions' );

            echo esc_html( wp_unslash( $action_id ) ); /* this is needed and it's the id of the dismissable required action */

            if ( ! empty( $action_id ) ) {

                if( 'reset' == $todo ){
                    $saved_actions_new = array();
                    if ( ! empty( $recommended_actions ) ) {

                        foreach ( $recommended_actions as $recommended_action ) {
                            $saved_actions[ $recommended_action['id'] ] = true;
                        }
                        update_option( $this->theme_slug . '_recommended_actions', $saved_actions_new );
                    }
                }
                /* if the option exists, update the record for the specified id */
                elseif ( !empty( $saved_actions) and is_array( $saved_actions ) ) {

                    switch ( esc_html( $todo ) ) {
                        case 'add';
                            $saved_actions[ $action_id ] = true;
                            break;
                        case 'dismiss';
                            $saved_actions[ $action_id ] = false;
                            break;
                    }
                    update_option( $this->theme_slug . '_recommended_actions', $saved_actions );

                    /* create the new option,with false for the specified id */
                }
                else {
                    $saved_actions_new = array();
                    if ( ! empty( $recommended_actions ) ) {

                        foreach ( $recommended_actions as $recommended_action ) {
                            echo $recommended_action['id'];
                            echo " ".$todo;
                            if ( $recommended_action['id'] == $action_id ) {
                                switch ( esc_html( $todo ) ) {
                                    case 'add';
                                        $saved_actions_new[ $action_id ] = true;
                                        break;
                                    case 'dismiss';
                                        $saved_actions_new[ $action_id ] = false;
                                        break;
                                }
                            }
                        }
                    }
                    update_option( $this->theme_slug . '_recommended_actions', $saved_actions_new );
                }
            }
            exit;
        }

        private function get_recommended_actions() {
            $saved_actions = get_option( $this->theme_slug . '_recommended_actions' );
            if ( ! is_array( $saved_actions ) ) {
                $saved_actions = array();
            }
            $recommended_actions = isset( $this->config['recommended_actions'] ) ? $this->config['recommended_actions'] : array();
            $valid       = array();
            if( isset( $recommended_actions ) && is_array( $recommended_actions ) ){
                foreach ( $recommended_actions as $recommended_action ) {
                    if (
                        (
                            ! isset( $recommended_action['check'] ) ||
                            ( isset( $recommended_action['check'] ) && ( $recommended_action['check'] == false ) )
                        )
                        &&
                        ( ! isset( $saved_actions[ $recommended_action['id'] ] ) ||
                            ( isset( $saved_actions[ $recommended_action['id']] ) && ($saved_actions[ $recommended_action['id']] == true ) )
                        )
                    ) {
                        $valid[] = $recommended_action;
                    }
                }
            }
            return $valid;
        }

        private function count_recommended_actions() {
            $count = 0;
            $actions_count = $this->get_recommended_actions();
            if ( ! empty( $actions_count ) ) {
                $count = count( $actions_count );
            }
            return $count;
        }
        
        /**
         * Adding Theme Info Menu under Appearance.
         */
        function at_admin_menu() {

            if ( ! empty( $this->page_title ) && ! empty( $this->menu_title ) ) {
                $count = $this->count_recommended_actions();
                $menu_title = $count > 0 ? $this->menu_title . '<span class="badge-action-count">' . esc_html( $count ) . '</span>' : $this->menu_title;
                /* Example
                 * add_theme_page('My Plugin Theme', 'My Plugin', 'edit_theme_options', 'my-unique-identifier', 'my_plugin_function');
                 * */
                add_theme_page( $this->page_title, $menu_title, 'edit_theme_options', $this->theme_slug . '-info', array(
                    $this,
                    'at_theme_info_screen',
                ) );
            }
        }

        /**
         * Render the info content screen.
         */
        public function at_theme_info_screen() {

            if ( ! empty( $this->config['info_title'] ) ) {
                $welcome_title = $this->config['info_title'];
            }
            if ( ! empty( $this->config['info_content'] ) ) {
                $welcome_content = $this->config['info_content'];
            }
            if ( ! empty( $this->config['quick_links'] ) ) {
                $quick_links = $this->config['quick_links'];
            }

            if (
                ! empty( $welcome_title ) ||
                ! empty( $welcome_content ) ||
                ! empty( $quick_links ) ||
                ! empty( $this->tabs )
            ) {
                echo '<div class="wrap about-wrap info-wrap epsilon-wrap">';

                if ( ! empty( $welcome_title ) ) {
                    echo '<h1>';
                    echo esc_html( $welcome_title );
                    if ( ! empty( $this->theme_version ) ) {
                        echo esc_html( $this->theme_version ) . ' </sup>';
                    }
                    echo '</h1>';
                }
                if ( ! empty( $welcome_content ) ) {
                    echo '<div class="about-text">' . wp_kses_post( $welcome_content ) . '</div>';
                }

                echo '<a href="https://www.acmethemes.com/" target="_blank" class="wp-badge epsilon-info-logo"></a>';

                /*quick links*/
                if( !empty( $quick_links ) && is_array( $quick_links ) ){
                    echo '<p class="quick-links">';
                    foreach ( $quick_links as $quick_key => $quick_link ) {
                        $button = 'button-secondary';
                        if( 'pro_url' == $quick_key ){
                            $button = 'button-primary';
                        }
                        echo '<a href="'.esc_url( $quick_link['url'] ).'" class="button '.esc_attr( $button ).'" target="_blank">'.$quick_link['text'].'</a>';
                    }
                    echo "</p>";
                }
                /* Display tabs */
                if ( ! empty( $this->tabs ) ) {
                    $current_tab = isset( $_GET['tab'] ) ? wp_unslash( $_GET['tab'] ) : 'getting_started';

                    echo '<h2 class="nav-tab-wrapper wp-clearfix">';
                    $count = $this->count_recommended_actions();
                    foreach ( $this->tabs as $tab_key => $tab_name ) {

                        echo '<a href="' . esc_url( admin_url( 'themes.php?page=' . $this->theme_slug . '-info' ) ) . '&tab=' . $tab_key . '" class="nav-tab ' . ( $current_tab == $tab_key ? 'nav-tab-active' : '' ) . '" role="tab" data-toggle="tab">';
                        echo esc_html( $tab_name );
                        if ( $tab_key == 'recommended_actions' ) {
                            if ( $count > 0 ) {
                                echo '<span class="badge-action-count">' . esc_html( $count ) . '</span>';
                            }
                        }
                        echo '</a>';
                    }

                    echo '</h2>';

                    /* Display content for current tab, dynamic method according to key provided*/
                    if ( method_exists( $this, $current_tab ) ) {

                        echo "<div class='changelog point-releases'>";
                        $this->$current_tab();
                        echo "</div>";
                    }
                }
                echo '</div><!--/.wrap.about-wrap-->';
            }
        }

        /**
         * Getting started tab
         */
        public function getting_started() {
            if ( ! empty( $this->config['getting_started'] ) ) {
                $getting_started = $this->config['getting_started'];
                if ( ! empty( $getting_started ) ) {

                    /*defaults values for getting_started array */
                    $defaults = array(
                        'title'     => '',
                        'desc'       => '',
                        'recommended_actions'=> '',
                        'link_title'   => '',
                        'link_url'   => '',
                        'is_button' => false,
                        'is_new_tab' => false
                    );

                    echo '<div class="feature-section three-col">';

                    foreach ( $getting_started as $getting_started_item ) {

                        /*allowed 6 value in array */
                        $instance = wp_parse_args( (array) $getting_started_item, $defaults );
                        /*default values*/
                        $title = esc_html( $instance[ 'title' ] );
                        $desc = wp_kses_post( $instance[ 'desc' ] );
                        $link_title = esc_html( $instance[ 'link_title' ] );
                        $link_url = esc_url( $instance[ 'link_url' ] );
                        $is_button = $instance[ 'is_button' ];
                        $is_new_tab = $instance[ 'is_new_tab' ];


                        echo '<div class="col">';
                        if ( ! empty( $title ) ) {
                            echo '<h3>' . $title . '</h3>';
                        }
                        if ( ! empty( $desc ) ) {
                            echo '<p>' . $desc . '</p>';
                        }
                        if ( ! empty( $link_title ) && ! empty( $link_url ) ) {

                            echo '<p>';
                            $button_class = '';
                            if ( $is_button ) {
                                $button_class = 'button button-primary';
                            }

                            $count = $this->count_recommended_actions();

                            if ( $getting_started_item['recommended_actions'] && isset( $count ) ) {
                                if ( $count == 0 ) {
                                    echo '<span class="dashicons dashicons-yes"></span>';
                                } else {
                                    echo '<span class="dashicons dashicons-no-alt"></span>';
                                }
                            }

                            $button_new_tab = '_self';
                            if ( $is_new_tab ) {
                                $button_new_tab = '_blank';
                            }

                            echo '<a target="' . $button_new_tab . '" href="' . $getting_started_item['link_url'] . '"class="' . $button_class . '">' . $getting_started_item['link_title'] . '</a>';
                            echo '</p>';
                        }
                        echo '</div><!-- .col -->';
                    }
                    echo '</div><!-- .feature-section three-col -->';
                }
            }
        }

        /**
         * Recommended Actions tab
         */
        public function check_plugin_status( $slug ) {

            $path = WPMU_PLUGIN_DIR . '/' . $slug . '/' . $slug . '.php';
            if ( ! file_exists( $path ) ) {
                $path = WP_PLUGIN_DIR . '/' . $slug . '/' . $slug . '.php';
                if ( ! file_exists( $path ) ) {
                    $path = false;
                }
            }

            if ( file_exists( $path ) ) {
                include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

                $needs = is_plugin_active( $slug . '/' . $slug . '.php' ) ? 'deactivate' : 'activate';

                return array( 'status' => is_plugin_active( $slug . '/' . $slug . '.php' ), 'needs' => $needs );
            }

            return array( 'status' => false, 'needs' => 'install' );
        }

        public function create_action_link( $state, $slug ) {

            switch ( $state ) {
                case 'install':
                    return wp_nonce_url(
                        add_query_arg(
                            array(
                                'action' => 'install-plugin',
                                'plugin' => $slug
                            ),
                            network_admin_url( 'update.php' )
                        ),
                        'install-plugin_' . $slug
                    );
                    break;

                case 'deactivate':
                    return add_query_arg(
                            array(
                                'action'        => 'deactivate',
                                'plugin'        => rawurlencode( $slug . '/' . $slug . '.php' ),
                                'plugin_status' => 'all',
                                'paged'         => '1',
                                '_wpnonce'      => wp_create_nonce( 'deactivate-plugin_' . $slug . '/' . $slug . '.php' )
                                ),
                            network_admin_url( 'plugins.php' )
                    );
                    break;

                case 'activate':
                    return add_query_arg(
                            array(
                                'action'        => 'activate',
                                'plugin'        => rawurlencode( $slug . '/' . $slug . '.php' ),
                                'plugin_status' => 'all',
                                'paged'         => '1',
                                '_wpnonce'      => wp_create_nonce( 'activate-plugin_' . $slug . '/' . $slug . '.php' )
                            ),
                            network_admin_url( 'plugins.php' )
                    );
                    break;
            }
        }

        public function recommended_actions() {

            $recommended_actions = isset( $this->config['recommended_actions'] ) ? $this->config['recommended_actions'] : array();
            $hooray = true;
            if ( ! empty( $recommended_actions ) ) {

                echo '<div class="feature-section action-recommended demo-import-boxed" id="plugin-filter">';

                if ( ! empty( $recommended_actions ) && is_array( $recommended_actions ) ) {

                    /*get saved recommend actions*/
                    $saved_recommended_actions = get_option( $this->theme_slug . '_recommended_actions' );

                    /*defaults values for getting_started array */
                    $defaults = array(
                        'title'         => '',
                        'desc'          => '',
                        'check'         => false,
                        'plugin_slug'   => '',
                        'id'            => ''
                    );
                    foreach ( $recommended_actions as $action_key => $action_value ) {
                        $instance = wp_parse_args( (array) $action_value, $defaults );

                        /*allowed 5 value in array */
                        $title = $instance[ 'title' ];
                        $desc = $instance[ 'desc' ];
                        $check = $instance[ 'check' ];
                        $plugin_slug = $instance[ 'plugin_slug' ];
                        $id = $instance[ 'id' ];

                        $hidden = false;

                        /*magic check for recommended actions*/
                        if (
                            isset( $saved_recommended_actions[ $id ] ) &&
                            $saved_recommended_actions[ $id ] == false ) {
                            $hidden = true;
                        }
                        if ( $hidden ) {
                            //continue;
                        }
                        $done = '';
                        if ( $check ) {
                           $done = 'done';
                        }

                        echo "<div class='at-theme-info-action-recommended-box {$done}'>";

                        if ( ! $hidden ) {
                            echo '<span data-action="dismiss" class="dashicons dashicons-visibility at-theme-info-recommended-action-button" id="' . esc_attr( $action_value['id'] ) . '"></span>';
                        } else {
                            echo '<span data-action="add" class="dashicons dashicons-hidden at-theme-info-recommended-action-button" id="' . esc_attr( $action_value['id'] ) .'"></span>';
                        }

                        if ( ! empty( $title) ) {
                            echo '<h3>' . wp_kses_post( $title ) . '</h3>';
                        }

                        if ( ! empty( $desc ) ) {
                            echo '<p>' . wp_kses_post( $desc ) . '</p>';
                        }

                        if ( ! empty( $plugin_slug ) ) {

                            $active = $this->check_plugin_status( $action_value['plugin_slug'] );
                            $url    = $this->create_action_link( $active['needs'], $action_value['plugin_slug'] );
                            $label  = '';
                            $class  = '';
                            switch ( $active['needs'] ) {

                                case 'install':
                                    $class = 'install-now button';
                                    $label = esc_html__( 'Install', 'acmephoto' );
                                    break;

                                case 'activate':
                                    $class = 'activate-now button button-primary';
                                    $label = esc_html__( 'Activate', 'acmephoto' );

                                    break;

                                case 'deactivate':
                                    $class = 'deactivate-now button';
                                    $label = esc_html__( 'Deactivate', 'acmephoto' );

                                    break;
                            }

                            ?>
                            <p class="plugin-card-<?php echo esc_attr( $action_value['plugin_slug'] ) ?> action_button <?php echo ( $active['needs'] !== 'install' && $active['status'] ) ? 'active' : '' ?>">
                                <a data-slug="<?php echo esc_attr( $action_value['plugin_slug'] ) ?>"
                                   class="<?php echo esc_attr( $class ); ?>"
                                   href="<?php echo esc_url( $url ) ?>"> <?php echo esc_html( $label ) ?> </a>
                            </p>

                            <?php

                        }
                        echo '</div>';
                        $hooray = false;
                    }
                }
                if ( $hooray ){
                    echo '<span class="hooray">' . esc_html__( 'Hooray! There are no recommended actions for you right now.', 'acmephoto' ) . '</span>';
                    echo '<a data-action="reset" id="reset" class="reset-all" href="#">'.esc_html__('Show All Recommended Actions','acmephoto').'</a>';
                }
                echo '</div>';
            }
        }

        /**
         * Recommended plugins tab
         */
        /*
		 * Call plugin api
		 */
        public function call_plugin_api( $slug ) {
            include_once( ABSPATH . 'wp-admin/includes/plugin-install.php' );

            if ( false === ( $call_api = get_transient( 'at_theme_info_plugin_information_transient_' . $slug ) ) ) {
                $call_api = plugins_api( 'plugin_information', array(
                    'slug'   => $slug,
                    'fields' => array(
                        'downloaded'        => false,
                        'rating'            => false,
                        'description'       => false,
                        'short_description' => true,
                        'donate_link'       => false,
                        'tags'              => false,
                        'sections'          => true,
                        'homepage'          => true,
                        'added'             => false,
                        'last_updated'      => false,
                        'compatibility'     => false,
                        'tested'            => false,
                        'requires'          => false,
                        'downloadlink'      => false,
                        'icons'             => true
                    )
                ) );
                set_transient( 'at_theme_info_plugin_information_transient_' . $slug, $call_api, 30 * MINUTE_IN_SECONDS );
            }

            return $call_api;
        }
        public function get_plugin_icon( $arr ) {

            if ( ! empty( $arr['svg'] ) ) {
                $plugin_icon_url = $arr['svg'];
            } elseif ( ! empty( $arr['2x'] ) ) {
                $plugin_icon_url = $arr['2x'];
            } elseif ( ! empty( $arr['1x'] ) ) {
                $plugin_icon_url = $arr['1x'];
            } else {
                $plugin_icon_url = get_template_directory_uri() . '/acmethemes/at-theme-info/images/placeholder_plugin.png';
            }

            return $plugin_icon_url;
        }
        public function recommended_plugins() {
            $recommended_plugins = $this->config['recommended_plugins'];

            if ( ! empty( $recommended_plugins ) ) {
                if ( ! empty( $recommended_plugins ) && is_array( $recommended_plugins ) ) {

                    echo '<div class="feature-section recommended-plugins three-col demo-import-boxed" id="plugin-filter">';

                    foreach ( $recommended_plugins as $recommended_plugins_item ) {

                        if ( ! empty( $recommended_plugins_item['slug'] ) ) {
                            $info   = $this->call_plugin_api( $recommended_plugins_item['slug'] );
                            if ( ! empty( $info->icons ) ) {
                                $icon = $this->get_plugin_icon( $info->icons );
                            }

                            $active = $this->check_plugin_status( $recommended_plugins_item['slug'] );

                            if ( ! empty( $active['needs'] ) ) {
                                $url = $this->create_action_link( $active['needs'], $recommended_plugins_item['slug'] );
                            }

                            echo '<div class="col plugin_box">';
                            if ( ! empty( $icon ) ) {
                                echo '<img src="'.esc_url( $icon ).'" alt="plugin box image">';
                            }
                            if ( ! empty(  $info->version ) ) {
                                echo '<span class="version">'. ( ! empty( $this->config['recommended_plugins']['version_label'] ) ? esc_html( $this->config['recommended_plugins']['version_label'] ) : '' ) . esc_html( $info->version ).'</span>';
                            }
                            if ( ! empty( $info->author ) ) {
                                echo '<span class="separator"> | </span>' . wp_kses_post( $info->author );
                            }

                            if ( ! empty( $info->name ) && ! empty( $active ) ) {
                                echo '<div class="action_bar ' . ( ( $active['needs'] !== 'install' && $active['status'] ) ? 'active' : '' ) . '">';
                                echo '<span class="plugin_name">' . ( ( $active['needs'] !== 'install' && $active['status'] ) ? 'Active: ' : '' ) . esc_html( $info->name ) . '</span>';
                                echo '</div>';

                                $label = '';

                                switch ( $active['needs'] ) {

                                    case 'install':
                                        $class = 'install-now button';
                                        $label = esc_html__( 'Install', 'acmephoto' );
                                        break;

                                    case 'activate':
                                        $class = 'activate-now button button-primary';
                                        $label = esc_html__( 'Activate', 'acmephoto' );

                                        break;

                                    case 'deactivate':
                                        $class = 'deactivate-now button';
                                        $label = esc_html__( 'Deactivate', 'acmephoto' );

                                        break;
                                }

                                echo '<span class="plugin-card-' . esc_attr( $recommended_plugins_item['slug'] ) . ' action_button ' . ( ( $active['needs'] !== 'install' && $active['status'] ) ? 'active' : '' ) . '">';
                                echo '<a data-slug="' . esc_attr( $recommended_plugins_item['slug'] ) . '" class="' . esc_attr( $class ) . '" href="' . esc_url( $url ) . '">' . esc_html( $label ) . '</a>';
                                echo '</span>';
                            }
                            echo '</div><!-- .col.plugin_box -->';
                        }
                    }
                    echo '</div><!-- .recommended-plugins -->';
                }
            }
        }

        /**
         * Child themes
         */
        public function child_themes() {
            echo '<div id="child-themes" class="at-theme-info-tab-pane">';
            $child_themes = isset( $this->config['child_themes'] ) ? $this->config['child_themes'] : array();
            if ( ! empty( $child_themes ) ) {

                /*defaults values for child theme array */
                $defaults = array(
                    'title'        => '',
                    'screenshot'   => '',
                    'download_link'=> '',
                    'preview_link' => ''
                );
                if ( ! empty( $child_themes ) && is_array( $child_themes ) ) {
                    echo '<div class="at-about-row">';
                    $i = 0;
                    foreach ( $child_themes as $child_theme ){
                        $instance = wp_parse_args( (array) $child_theme, $defaults );

                        /*allowed 5 value in array */
                        $title = $instance[ 'title' ];
                        $screenshot = $instance[ 'screenshot'];
                        $download_link = $instance[ 'download_link'];
                        $preview_link = $instance[ 'preview_link'];

                        if( !empty( $screenshot) ){
                            echo '<div class="at-about-child-theme">';
                            echo '<div class="at-theme-info-child-theme-image">';

                            echo '<img src="' . esc_url( $screenshot ) . '" alt="' . ( ! empty( $title ) ? esc_attr( $title ) : '' ) . '" />';

                            if ( ! empty( $title ) ) {
                                echo '<div class="at-theme-info-child-theme-details">';
                                echo '<div class="theme-details">';
                                echo '<span class="theme-name">' . esc_html( $title  ). '</span>';
                                if ( ! empty( $download_link ) ) {
                                    echo '<a href="' . esc_url( $download_link ) . '" class="button button-primary install right">' . esc_html__( 'Download','acmephoto' ) . '</a>';
                                }
                                if ( ! empty( $preview_link ) ) {
                                    echo '<a class="button button-secondary preview right" target="_blank" href="' . $preview_link . '">' . esc_html__( 'Live Preview','acmephoto' ). '</a>';
                                }
                                echo '</div>';
                                echo '</div>';
                            }

                            echo "</div>";
                            echo "</div>";
                            $i++;
                        }
                        if( 0 == $i % 3 ){
                            echo "</div><div class='at-about-row'>";/*.at-about-row end-start*/
                        }
                    }

                    echo '</div>';/*.at-about-row end*/
                }// End if().
            }// End if().
            echo '</div>';
        }

        /**
         * Support tab
         */
        public function support() {
            echo '<div class="feature-section three-col">';

            if ( ! empty( $this->config['support_content'] ) ) {

                $supports = $this->config['support_content'];

                if ( ! empty( $supports ) ) {

                    /*defaults values for child theme array */
                    $defaults = array(
                        'title' => '',
                        'icon' => '',
                        'desc' => '',
                        'button_label' => '',
                        'button_link' => '',
                        'is_button' => true,
                        'is_new_tab' => true
                    );

                    foreach ( $supports as $support ) {
                        $instance = wp_parse_args( (array) $support, $defaults );

                        /*allowed 7 value in array */
                        $title = $instance[ 'title' ];
                        $icon = $instance[ 'icon'];
                        $desc = $instance[ 'desc'];
                        $button_label = $instance[ 'button_label'];
                        $button_link = $instance[ 'button_link'];
                        $is_button = $instance[ 'is_button'];
                        $is_new_tab = $instance[ 'is_new_tab'];
                        
                        echo '<div class="col">';

                        if ( ! empty( $title ) ) {
                            echo '<h3>';
                            if ( ! empty( $icon ) ) {
                                echo '<i class="' . $icon . '"></i>';
                            }
                            echo $title;
                            echo '</h3>';
                        }

                        if ( ! empty( $desc ) ) {
                            echo '<p><i>' . $desc . '</i></p>';
                        }

                        if ( ! empty( $button_link ) && ! empty( $button_label ) ) {

                            echo '<p>';
                            $button_class = '';
                            if ( $is_button ) {
                                $button_class = 'button button-primary';
                            }

                            $button_new_tab = '_self';
                            if ( isset( $is_new_tab ) ) {
                                if ( $is_new_tab ) {
                                    $button_new_tab = '_blank';
                                }
                            }
                            echo '<a target="' . $button_new_tab . '" href="' . $button_link . '" class="' . $button_class . '">' . $button_label . '</a>';
                            echo '</p>';
                        }
                        echo '</div>';
                    }
                }
            }
            echo '</div>';
        }

        /**
         * Changelog tab
         */
        private function parse_changelog() {
            WP_Filesystem();
            global $wp_filesystem;
            $changelog = $wp_filesystem->get_contents( get_template_directory() . '/changelog.txt' );
            if ( is_wp_error( $changelog ) ) {
                $changelog = '';
            }
            return $changelog;
        }

        public function changelog() {
            $changelog = $this->parse_changelog();
            if ( ! empty( $changelog ) ) {
                echo '<div class="featured-section changelog">';
                echo "<pre class='changelog'>";
                echo $changelog;
                echo "</pre>";
                echo '</div><!-- .featured-section.changelog -->';
            }
        }

        /**
         * Free vs PRO tab
         */
        public function free_pro() {
            $free_pro = isset( $this->config['free_pro'] ) ? $this->config['free_pro'] : array();
            if ( ! empty( $free_pro ) ) {
                /*defaults values for child theme array */
                $defaults = array(
                    'title'=> '',
                    'desc' => '',
                    'free' => '',
                    'pro'  => '',
                );

                if ( ! empty( $free_pro ) && is_array( $free_pro ) ) {
                    echo '<div class="feature-section">';
                    echo '<div id="free_pro" class="at-theme-info-tab-pane at-theme-info-fre-pro">';
                    echo '<table class="free-pro-table">';
                    echo '<thead>';
                    echo '<tr>';
                    echo '<th></th>';
                    echo '<th>' . esc_html__( 'AcmePhoto','acmephoto' ) . '</th>';
                    echo '<th>' . esc_html__( 'AcmePhotoPro','acmephoto' ) . '</th>';
                    echo '</tr>';
                    echo '</thead>';
                    echo '<tbody>';
                    foreach ( $free_pro as $feature ) {

                        $instance = wp_parse_args( (array) $feature, $defaults );

                        /*allowed 7 value in array */
                        $title = $instance[ 'title' ];
                        $desc = $instance[ 'desc'];
                        $free = $instance[ 'free'];
                        $pro = $instance[ 'pro'];

                        echo '<tr>';
                        if ( ! empty( $title ) || ! empty( $desc ) ) {
                            echo '<td>';
                            if ( ! empty( $title ) ) {
                                echo '<h3>' . wp_kses_post( $title ) . '</h3>';
                            }
                            if ( ! empty( $desc ) ) {
                                echo '<p>' . wp_kses_post( $desc ) . '</p>';
                            }
                            echo '</td>';
                        }

                        if ( ! empty( $free )) {
                            if( 'yes' === $free ){
                                echo '<td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>';
                            }
                            elseif ( 'no' === $free ){
                                echo '<td class="only-pro"><span class="dashicons-before dashicons-no-alt"></span></td>';
                            }
                            else{
                                echo '<td class="only-lite">'.esc_html($free ).'</td>';
                            }

                        }
                        if ( ! empty( $pro )) {
                            if( 'yes' === $pro ){
                                echo '<td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>';
                            }
                            elseif ( 'no' === $pro ){
                                echo '<td class="only-pro"><span class="dashicons-before dashicons-no-alt"></span></td>';
                            }
                            else{
                                echo '<td class="only-lite">'.esc_html($pro ).'</td>';
                            }
                        }
                        echo '</tr>';
                    }

                    echo '<tr class="at-theme-info-text-center">';
                    echo '<td></td>';
                    echo '<td colspan="2"><a href="https://www.acmethemes.com/themes/acmephotopro/" target="_blank" class="button button-primary button-hero"> AcmePhotoPro</a></td>';
                    echo '</tr>';

                    echo '</tbody>';
                    echo '</table>';
                    echo '</div>';
                    echo '</div>';

                }
            }
        }

        /**
         * Support tab
         */
        public function faq() {
            echo '<div class="feature-section three-col faq">';

            if ( ! empty( $this->config['faq'] ) ) {

                $supports = $this->config['faq'];

                if ( ! empty( $supports ) ) {

                    /*defaults values for child theme array */
                    $defaults = array(
                        'title' => '',
                        'icon' => '',
                        'desc' => '',
                        'button_label' => '',
                        'button_link' => '',
                        'is_button' => true,
                        'is_new_tab' => true
                    );

                    foreach ( $supports as $support ) {
                        $instance = wp_parse_args( (array) $support, $defaults );

                        /*allowed 7 value in array */
                        $title = $instance[ 'title' ];
                        $icon = $instance[ 'icon'];
                        $desc = $instance[ 'desc'];
                        $button_label = $instance[ 'button_label'];
                        $button_link = $instance[ 'button_link'];
                        $is_button = $instance[ 'is_button'];
                        $is_new_tab = $instance[ 'is_new_tab'];

                        echo '<div class="col-full">';

                        if ( ! empty( $title ) ) {
                            echo '<h3 class="faq-title">';
                            if ( ! empty( $icon ) ) {
                                echo '<i class="' . $icon . '"></i>';
                            }
                            echo $title;
                            echo '</h3>';
                        }
                        echo "<div class='faq-content'>";

                        if ( ! empty( $desc ) ) {
                            echo '<p><i>' . $desc . '</i></p>';
                        }
                        if ( ! empty( $button_link ) && ! empty( $button_label ) ) {

                            echo '<p>';
                            $button_class = '';
                            if ( $is_button ) {
                                $button_class = 'button button-primary';
                            }

                            $button_new_tab = '_self';
                            if ( isset( $is_new_tab ) ) {
                                if ( $is_new_tab ) {
                                    $button_new_tab = '_blank';
                                }
                            }
                            echo '<a target="' . $button_new_tab . '" href="' . $button_link . '" class="' . $button_class . '">' . $button_label . '</a>';
                            echo '</p>';
                        }
                        echo "</div>";

                        echo '</div>';
                    }
                }
            }
            echo '</div>';
        }

        /**
         * Load css and scripts for the about page
         */
        public function style_and_scripts( $hook_suffix ) {

            // this is needed on all admin pages, not just the about page, for the badge action count in the wordpress main sidebar
            wp_enqueue_style( 'at-theme-info-css', get_template_directory_uri() . '/acmethemes/at-theme-info/css/at-theme-info.css' );

            if ( 'appearance_page_' . $this->theme_slug . '-info' == $hook_suffix ) {

                wp_enqueue_script( 'at-theme-info-js', get_template_directory_uri() . '/acmethemes/at-theme-info/js/at-theme-info.js', array( 'jquery' ) );

                wp_enqueue_style( 'plugin-install' );
                wp_enqueue_script( 'plugin-install' );
                wp_enqueue_script( 'updates' );

                $count = $this->count_recommended_actions();
                wp_localize_script( 'at-theme-info-js', 'at_theme_info_object', array(
                    'nr_actions_recommended'      => $count,
                    'ajaxurl'                  => admin_url( 'admin-ajax.php' ),
                    'template_directory'       => get_template_directory_uri()
                ) );

            }

        }
    }
}

$config = array(
    // Page title.
    'page_title'               => esc_html__( 'AcmePhoto Info', 'acmephoto' ),

    // Menu name under Appearance.
    'menu_title'               => esc_html__( 'AcmePhoto Info', 'acmephoto' ),

    // Main welcome title
    'info_title'         => sprintf( esc_html__( 'Welcome to %s - ', 'acmephoto' ), 'AcmePhoto' ),

    // Main welcome content
    'info_content'       => sprintf( esc_html__( '%s is now installed and ready to use. We hope the following information will help you. If you want to ask any query or just want to say hello, you can always contact us. We hope you enjoy it! ', 'acmephoto' ), '<b>AcmePhoto</b>' ),

    /**
     * Quick links
     */
    'quick_links'                    => array(
        'theme_url'  => array(
                'text' => __('Theme Details','acmephoto'),
                'url' => 'https://www.acmethemes.com/themes/acmephoto/'
        ),
        'demo_url'  => array(
            'text' => __('View Demo','acmephoto'),
            'url' => 'http://www.demo.acmethemes.com/acmephoto/'
        ),
        'pro_url'  => array(
            'text' => __('View Pro Version','acmephoto'),
            'url' => 'https://www.acmethemes.com/themes/acmephotopro/'
        ),
        'rate_url'  => array(
            'text' => __('Rate This Theme','acmephoto'),
            'url' => 'https://wordpress.org/support/theme/acmephoto/reviews/?filter=5'
        ),
    ),

    'tabs'                    => array(
        'getting_started'  => __( 'Getting Started', 'acmephoto' ),
        'recommended_actions' => __( 'Recommended Actions', 'acmephoto' ),
        'recommended_plugins' => __( 'Useful Plugins','acmephoto' ),
        'support'       => __( 'Support', 'acmephoto' ),
        'changelog'        => __( 'Changelog', 'acmephoto' ),
        'faq'        => __( 'FAQ', 'acmephoto' ),
        'free_pro'         => __( 'Free VS PRO', 'acmephoto' ),
    ),

    /*Getting started tab*/
    'getting_started' => array(
        'first' => array(
            'title' => esc_html__( 'Step 1 : Read full documentation','acmephoto' ),
            'desc' => esc_html__( 'Please check our full documentation for detailed information on how to Setup and Use AcmePhoto.','acmephoto' ),
            'link_title' => esc_html__( 'Documentation','acmephoto' ),
            'link_url' => 'http://www.doc.acmethemes.com/acmephoto/',
            'is_button' => false,
            'recommended_actions' => false,
            'is_new_tab' => true
        ),
        'second' => array(
            'title' => esc_html__( 'Step 2 : Go to Customizer','acmephoto' ),
            'desc' => esc_html__( 'All Setting, Theme Options, Widgets and Menus are available via Customize screen. Have a quick look or start customization!','acmephoto' ),
            'link_title' => esc_html__( 'Go to Customizer','acmephoto' ),
            'link_url' => esc_url( admin_url( 'customize.php' ) ),
            'is_button' => true,
            'recommended_actions' => false,
            'is_new_tab' => true
        ),
        'third' => array(
	        'title' => esc_html__( 'Step 3 : Setting Featured Section ','acmephoto' ),
	        'desc' => esc_html__( 'Add Feature Image or Unlimited Slides on Feature Section. Please do the Step 3 before this.','acmephoto' ),
	        'link_title' => esc_html__( 'Featured Section','acmephoto' ),
	        'link_url' => esc_url( admin_url( 'customize.php' ) ).'?autofocus[panel]=acmephoto-feature-panel',
	        'is_button' => true,
	        'recommended_actions' => false,
	        'is_new_tab' => true
        )
    ),

    // recommended actions array.
    'recommended_actions'        => array(
        'demo-content' => array(
            'title' => esc_html__( 'Install Demo Setup','acmephoto' ),
            'desc' => sprintf( esc_html__( 'For the demo content installation, install the Acme Demo Setup plugin, download demo content from %1$s here %2$s. Then go to "Acme Demo Setup" and import demo. ', 'acmephoto' ), '<a target="_blank" href="http://www.doc.acmethemes.com/acmephoto/#DummyData" >','</a>' ),
            'id' => 'acme-demo-setup',
            'check' =>  ( ( function_exists('acme_demo_setup_load_textdomain') )? true : false ),
            'plugin_slug'   => 'acme-demo-setup',
        )
    ),

    // Plugins array.
    'recommended_plugins'        => array(
        'acme-demo-setup' => array(
            'slug' => 'acme-demo-setup'
        )
    ),

	/*FAQ*/
    'faq'      => array(
	    'first' => array (
		    'title' => esc_html__( 'Where to get a dedicated support ? ','acmephoto' ),
		    'icon' => 'dashicons dashicons-sos',
		    'desc' => esc_html__( 'Follow the theme dedicated support forum.','acmephoto' ),
		    'button_label' => esc_html__( 'Click here to get the support','acmephoto' ),
		    'button_link' => esc_url( 'https://www.acmethemes.com/supports' ),
		    'is_button' => true,
		    'is_new_tab' => true
	    ),
	    'second' => array (
		    'title' => esc_html__( 'Recent Updates of your Site ? ','acmephoto' ),
		    'icon' => 'dashicons dashicons-sos',
		    'desc' => esc_html__( 'Where i can get the recent updates of theme related updates ? ','acmephoto' ),
		    'button_label' => esc_html__( 'Visit our site blog page','acmephoto' ),
		    'button_link' => esc_url( 'https://www.acmethemes.com/blog/' ),
		    'is_button' => true,
		    'is_new_tab' => true
	    ),
	    'third' => array (
		    'title' => esc_html__( 'How to hide/change footer text Proudly powered by WordPress | Theme: Weblog by AcmeThemes. ? ','acmephoto' ),
		    'icon' => 'dashicons dashicons-sos',
		    'button_label' => esc_html__( 'Visit this blog post to know how to change or remove powered by text.','acmephoto' ),
		    'button_link' => esc_url( 'https://www.acmethemes.com/blog/2017/01/remove-powered-by-text-on-footer/' ),
		    'is_button' => true,
		    'is_new_tab' => true
	    ),
    ),
	// Support content tab.
    'support_content'      => array(
	    'first' => array (
		    'title' => esc_html__( 'Contact Support','acmephoto' ),
		    'icon' => 'dashicons dashicons-sos',
		    'desc' => esc_html__( 'Got theme support question or found bug? Best place to ask your query is our dedicated Support forum.','acmephoto' ),
		    'button_label' => esc_html__( 'Contact Support','acmephoto' ),
		    'button_link' => esc_url( 'https://www.acmethemes.com/supports/forum/acmephoto/' ),
		    'is_button' => true,
		    'is_new_tab' => true
	    ),
	    'second' => array(
		    'title' => esc_html__( 'Documentation','acmephoto' ),
		    'icon' => 'dashicons dashicons-book-alt',
		    'desc' => esc_html__( 'Please check our full documentation for detailed information on how to Setup and Use Weblog.','acmephoto' ),
		    'button_label' => esc_html__( 'Read full documentation','acmephoto' ),
		    'button_link' => 'http://www.doc.acmethemes.com/acmephoto/',
		    'is_button' => false,
		    'is_new_tab' => true
	    ),
	    'third' => array(
		    'title' => esc_html__( 'Need more features?','acmephoto' ),
		    'icon' => 'dashicons dashicons-screenoptions',
		    'desc' => esc_html__( 'Upgrade to PRO version for more exciting features and Priority Support.','acmephoto' ),
		    'button_label' => esc_html__( 'View Pro Version','acmephoto' ),
		    'button_link' => 'https://www.acmethemes.com/themes/acmephotopro/',
		    'is_button' => true,
		    'is_new_tab' => true
	    ),
	    'fourth' => array(
		    'title' => esc_html__( 'Got sales related question?','acmephoto' ),
		    'icon' => 'dashicons dashicons-cart',
		    'desc' => esc_html__( "Have any query before purchase, you are more than welcome to ask.",'acmephoto' ),
		    'button_label' => esc_html__( 'Pre-sale Question?','acmephoto' ),
		    'button_link' => 'https://www.acmethemes.com/pre-sale-question/',
		    'is_button' => true,
		    'is_new_tab' => true
	    ),
	    'fifth' => array(
		    'title' => esc_html__( 'Customization Request','acmephoto' ),
		    'icon' => 'dashicons dashicons-hammer',
		    'desc' => esc_html__( 'Needed any customization for the theme, you can request from here.','acmephoto' ),
		    'button_label' => esc_html__( 'Customization Request','acmephoto' ),
		    'button_link' => 'https://www.acmethemes.com/customization-request/',
		    'is_button' => false,
		    'is_new_tab' => true
	    )
    ),

	// Free vs pro array.
    'free_pro' => array(
	    array(
		    'title'=> __( 'Custom Widgets', 'acmephoto' ),
		    'desc' => __( 'All added custom widgets are ready for page builder plugin', 'acmephoto' ),
		    'free' => __('1','acmephoto'),
		    'pro'  => __('8+ Available','acmephoto'),
	    ),
	    array(
		    'title'       => __( 'Widget Options', 'acmephoto' ),
		    'desc' => __( 'Every widgets have multiple options to customize section', 'acmephoto' ),
		    'free'  => __('Not Available','acmephoto'),
		    'pro'   => __('Multiple Options','acmephoto'),
	    ),
	    array(
		    'title'       => __( 'Google Fonts', 'acmephoto' ),
		    'desc' => __( 'Google fonts options for changing the overall site fonts ', 'acmephoto' ),
		    'free'  => 'yes',
		    'pro'   => __('100 +','acmephoto'),
	    ),
	    array(
		    'title'       => __( 'Color Options', 'acmephoto' ),
		    'desc' => __( 'Color options for changing overall site colors, Contat us', 'acmephoto' ),
		    'free'  => __('2','acmephoto'),
		    'pro'   => __('Unlimited','acmephoto'),
	    ),
	    array(
		    'title'       => __( 'Header Options', 'acmephoto' ),
		    'desc' => __( 'Customze the header section as your need.', 'acmephoto' ),
		    'free'  => __('Limited','acmephoto'),
		    'pro'   => __('Multiple Options','acmephoto'),
	    ),
	    array(
		    'title'       => __( 'Menu Options', 'acmephoto' ),
		    'desc' => __( 'Lots of menu options are available to manage the menu section. Appearance > Menus', 'acmephoto' ),
		    'free'  => __('Limited','acmephoto'),
		    'pro'   => __('Multiple Options','acmephoto'),
	    ),
	    array(
		    'title'       => __( 'Sticky Menu Options', 'acmephoto' ),
		    'desc' => __( 'Enable Sticky menu on your site.', 'acmephoto' ),
		    'free'  => 'no',
		    'pro'   => 'yes'
	    ),
	    array(
		    'title'       => __( 'Featured Selection', 'acmephoto' ),
		    'desc' => __( 'This is slider section. Check every options there','acmephoto' ),
		    'free'  => __('Parent/Child Page','acmephoto'),
		    'pro'   => __('Page, Post, Category, And Custom','acmephoto'),
	    ),
	    array(
		    'title'       => __( 'Featured Section Options', 'acmephoto' ),
		    'desc' => __( 'Options to manage the sliders', 'acmephoto' ),
		    'free'  => __('Limited','acmephoto'),
		    'pro'   => __('Multiple Options','acmephoto'),
	    ),
	    array(
		    'title'       => __( 'Sticky Sidebar', 'acmephoto' ),
		    'desc' => __( 'Enable/Disable sticky sidebars', 'acmephoto' ),
		    'free'  => 'yes',
		    'pro'   => 'yes',
	    ),
	    array(
		    'title'       => __( 'Blog/Archive Options', 'acmephoto' ),
		    'desc' => __( 'Various Options are available for the blog and archive page management. Check on Appearance > Customize > Layout/Design Options.', 'acmephoto' ),
		    'free'  => __('Limited','acmephoto'),
		    'pro'   => __('Manage Every Entity','acmephoto'),
	    ),
	    array(
		    'title'       => __( 'Footer Options', 'acmephoto' ),
		    'desc' => __( 'Manage the every entity related to footer section.', 'acmephoto' ),
		    'free'  => __('Limited','acmephoto'),
		    'pro'   => __('Multiple Options','acmephoto'),
	    ),
	    array(
		    'title'       => __( 'Social Options', 'acmephoto' ),
		    'desc' => __( 'Options to manage the social icons like facebook, instagram, linkedin etc.', 'acmephoto' ),
		    'free'  => __('Limited','acmephoto'),
		    'pro'   => __('Multiple Options','acmephoto'),
	    ),
	    array(
		    'title'       => __( 'Theme Credit Link', 'acmephoto' ),
		    'desc' => __( 'Options to disable to Powered by text on footer.', 'acmephoto' ),
		    'free'  => __('No Options To Disable','acmephoto'),
		    'pro'   => __('Enable/Disable','acmephoto'),
	    ),
	    array(
		    'title'       => __( 'Own Credit Link', 'acmephoto' ),
		    'desc' => __( 'Write your own credit text.', 'acmephoto' ),
		    'free'  => 'no',
		    'pro'   => __('Your Own Credit Text','acmephoto'),
	    ),
	    array(
		    'title'       => __( 'Social Icons', 'acmephoto' ),
		    'desc' => __( 'Enter the URL for social icons. ', 'acmephoto' ),
		    'free'  => __('4 +','acmephoto'),
		    'pro'   => __('Available Mostly Used','acmephoto'),
	    ),
	    array(
		    'title'       => __( 'Intro Loader Options', 'acmephoto' ),
		    'desc' => __( 'It will load before the site loaded completely.', 'acmephoto' ),
		    'free'  => 'no',
		    'pro'   => __('Multiple Options','acmephoto'),
	    ),
	    array(
		    'title'       => __( 'Related Post Options', 'acmephoto' ),
		    'desc' => __( 'Related post will appear on single post of each blog article', 'acmephoto' ),
		    'free'   => 'no',
		    'pro'   => __('Multiple Options','acmephoto'),
	    ),
	    array(
		    'title'       => __( 'Author Info Options', 'acmephoto' ),
		    'desc' => __( 'This will appear on single page and archive page of that author.', 'acmephoto' ),
		    'free'   => 'no',
		    'pro'   => 'yes'
	    ),
	    array(
		    'title'       => __( 'Feature Image Options', 'acmephoto' ),
		    'desc' => __( 'Featured image options for the single page and post.', 'acmephoto' ),
		    'free'  => 'yes',
		    'pro'   => __('Manage As Your Need','acmephoto'),
	    ),
	    array(
		    'title'       => __( 'Home Page Layout', 'acmephoto' ),
		    'desc' => __( 'Change your home page layout with available options', 'acmephoto' ),
		    'free'  => __('Masonry','acmephoto'),
		    'pro'   => __('Multiple Layout Available','acmephoto'),
	    ),
	    array(
		    'title'       => __( 'Navigation Options', 'acmephoto' ),
		    'desc' => __( 'Post Navigation means, previous post and next post. Check on single post.', 'acmephoto' ),
		    'free'   => __('Default','acmephoto'),
		    'pro'   => __('Advanced','acmephoto'),
	    ),
	    array(
		    'title'       => __( 'Post Comment Options', 'acmephoto' ),
		    'desc' => __( 'Manage the comment section on single post.', 'acmephoto' ),
		    'free'  => 'no',
		    'pro'   => __('Options Available','acmephoto'),
	    ),
	    array(
		    'title'       => __( 'Show/Hide Comment', 'acmephoto' ),
		    'desc' => __( 'Options to show hide comments on page and post.', 'acmephoto' ),
		    'free'  => 'no',
		    'pro'   => 'yes',
	    ),
	    array(
		    'title'       => __( 'Breadcrumb', 'acmephoto' ),
		    'desc' => __( 'Advanced options for manage the Breadcrumb.', 'acmephoto' ),
		    'free'  => 'yes',
		    'pro'   => __('Advanced','acmephoto'),
	    ),
	    array(
		    'title'       => __( 'Video Post On Featured', 'acmephoto' ),
		    'desc' => __( 'Create your video post easily from Youtube and Vimeo video.', 'acmephoto' ),
		    'free'  => 'no',
		    'pro'   => __('Add Own Video On Featured','acmephoto'),
	    ),
	    array(
		    'title'=> __( 'Single Post Options', 'acmephoto' ),
		    'desc' => __( 'Manage every entity of single post.', 'acmephoto' ),
		    'free' => __('Limited','acmephoto'),
		    'pro'  => __('Multiple Options','acmephoto'),
	    ),
	    array(
		    'title'=> __( 'Featured Image Size', 'acmephoto' ),
		    'desc' => __( 'Select the size of the featured image on the single post.', 'acmephoto' ),
		    'free' => 'yes',
		    'pro'  => 'yes',
	    ),
	    array(
		    'title'=> __( 'Image Size Options', 'acmephoto' ),
		    'desc' => __( 'Make the image size as your need on blog/archive page.', 'acmephoto' ),
		    'free' => 'yes',
		    'pro'  => 'yes',
	    ),
	    array(
		    'title'=> __( 'Advertisement Options', 'acmephoto' ),
		    'desc' => __( 'Can we advertise with this theme?', 'acmephoto' ),
		    'free' => 'no',
		    'pro'  => 'yes',
	    ),
	    array(
		    'title'=> __( 'Advertisement Widget', 'acmephoto' ),
		    'desc' => __( 'Is custom widget available for Advertisement?', 'acmephoto' ),
		    'free' => 'No',
		    'pro'  => 'yes',
	    ),
	    array(
		    'title'=> __( 'Sidebars', 'acmephoto' ),
		    'desc' => __( 'Global and Individual sidebars are available. You can easily orveride it from single page/post.', 'acmephoto' ),
		    'free' => 'yes',
		    'pro'  => __('Global And Individuals', 'acmephoto'),
	    ),
	    array(
		    'title'=> __( 'Infinite Scroll Features', 'acmephoto' ),
		    'desc' => __( 'Is theme have default infinite scroll ?', 'acmephoto' ),
		    'free' => 'no',
		    'pro'  => 'yes',
	    ),
	    array(
		    'title'=> __( 'Infinite Scroll', 'acmephoto' ),
		    'desc' => __( 'Use Jetpack Infinite Scroll on Blog Page', 'acmephoto' ),
		    'free' => 'yes',
		    'pro'  => 'yes',
	    ),
	    array(
		    'title'=> __( 'WooCommerce', 'acmephoto' ),
		    'desc' => __( 'Create WooCommerce shop easily with the help of WooCommerce plugin.', 'acmephoto' ),
		    'free' => 'yes',
		    'pro'  => 'yes',
	    ),
	    array(
		    'title'       => __( 'Translation', 'acmephoto' ),
		    'desc' => __( 'Both theme are translation ready.', 'acmephoto' ),
		    'free'  => 'yes',
		    'pro'   => 'yes',
	    ),
	    array(
		    'title'       => __( 'SEO', 'acmephoto' ),
		    'desc' => __( 'Developed with high skilled SEO tools.', 'acmephoto' ),
		    'free'  => 'yes',
		    'pro'   => 'yes',
	    ),
	    array(
		    'title'       => __( 'Widget Area On Footer', 'acmephoto' ),
		    'desc' => __( 'Change the number of footers on footer area as you need.', 'acmephoto' ),
		    'free'  => __('3 ', 'acmephoto'),
		    'pro'   => __('Choose On Need', 'acmephoto'),
	    ),
	    array(
		    'title'       => __( 'Support Forum', 'acmephoto' ),
		    'desc' => __( 'Highly dedicated support team are assigned for your help. Try this today.', 'acmephoto' ),
		    'free'  => __('Second', 'acmephoto'),
		    'pro'   => __('Dedicated With High Priority', 'acmephoto'),
	    )
    )
);
return new Acmephoto_Theme_Info( $config );