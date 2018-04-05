<?php
if( !class_exists( 'Acme_Demo_Setup_After_Import') ):

    class Acme_Demo_Setup_After_Import {

        /*update nav  menu*/
        function update_nav_menu( $acme_demo_setup_nav_data ){
            $new_theme_navs = array();
            $nav_menus = wp_get_nav_menus();
            if ( ! empty( $nav_menus ) ) {
                foreach ( $nav_menus as $nav_menu ) {
                    if ( is_object( $nav_menu ) ) {
                        foreach ( $acme_demo_setup_nav_data as $location => $location_name ) {
                            if ( $nav_menu->name == $location_name ) {
                                $new_theme_navs[ $location ] = $nav_menu->term_id;
                            }
                        }
                    }
                }
            }
            set_theme_mod( 'nav_menu_locations', $new_theme_navs );
        }

        /*update WordPRess options for theme*/
        function update_wp_options( $wp_options ) {

            if ( ! empty( $wp_options ) ) {

                foreach ( $wp_options as $option_key => $option_value ) {
                    if ( ! in_array( $option_key, array( 'blogname', 'blogdescription', 'show_on_front', 'page_on_front', 'page_for_posts' ) ) ) {
                        continue;
                    }

                    // Format the value based on option key.
                    switch ( $option_key ) {
                        case 'show_on_front':
                            if ( in_array( $option_value, array( 'posts', 'page' ) ) ) {
                                update_option( 'show_on_front', $option_value );
                            }
                            break;

                        case 'page_on_front':
                        case 'page_for_posts':
                            $page = get_page_by_title( $option_value );
                            if ( is_object( $page ) && $page->ID ) {
                                update_option( $option_key, $page->ID );
                                update_option( 'show_on_front', 'page' );
                            }
                            break;

                        default:
                            update_option( $option_key, sanitize_text_field( $option_value ) );
                            break;
                    }
                }
            }

            return true;
        }

        function after_demo_import(){

            /*update theme options*/
            $acme_demo_setup_wp_options_data = apply_filters('acme_demo_setup_wp_options_data',array() );

            if( !empty( $acme_demo_setup_wp_options_data ) ){
                $this->update_wp_options( $acme_demo_setup_wp_options_data );
            }

            /*update theme menu*/
            $acme_demo_setup_nav_data = apply_filters('acme_demo_setup_nav_data',array() );
            if( !empty( $acme_demo_setup_nav_data ) ){
                $this->update_nav_menu( $acme_demo_setup_nav_data );
            }
        }

    }
endif;
$Acme_Demo_Setup_After_Import = new Acme_Demo_Setup_After_Import();
add_action( 'acme_demo_setup_after_import', array( $Acme_Demo_Setup_After_Import, 'after_demo_import' ) );