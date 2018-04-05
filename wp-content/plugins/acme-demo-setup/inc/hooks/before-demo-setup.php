<?php
if( !class_exists( 'Acme_Demo_Setup_Before_Import') ):

    class Acme_Demo_Setup_Before_Import {
        /**
         * Reset existing active widgets.
         */
        function reset_widgets() {
            $sidebars_widgets = wp_get_sidebars_widgets();

            // Reset active widgets.
            foreach ( $sidebars_widgets as $key => $widgets ) {
                $sidebars_widgets[ $key ] = array();
            }

            wp_set_sidebars_widgets( $sidebars_widgets );
        }

        /**
         * Delete existing navigation menus.
         */
        function delete_nav_menus() {
            $nav_menus = wp_get_nav_menus();

            // Delete navigation menus.
            if ( ! empty( $nav_menus ) ) {
                foreach ( $nav_menus as $nav_menu ) {
                    wp_delete_nav_menu( $nav_menu->slug );
                }
            }
        }

        function before_demo_import() {
            $this->reset_widgets();
            $this->delete_nav_menus();
            /**
             * Remove theme modifications option.
             */
            remove_theme_mods();
        }
    }
endif;
$Acme_Demo_Setup_Before_Import = new Acme_Demo_Setup_Before_Import();
add_action( 'acme_demo_setup_before_import', array( $Acme_Demo_Setup_Before_Import, 'before_demo_import' ) );