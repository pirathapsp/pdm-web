<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}


if ( ! class_exists( 'AWS_Helpers' ) ) :

    /**
     * Class for plugin help methods
     */
    class AWS_Helpers {

        /*
         * Removes scripts, styles, html tags
         */
        static public function html2txt( $str ) {
            $search = array(
                '@<script[^>]*?>.*?</script>@si',
                '@<[\/\!]*?[^<>]*?>@si',
                '@<style[^>]*?>.*?</style>@siU',
                '@<![\s\S]*?--[ \t\n\r]*>@'
            );
            $str = preg_replace( $search, '', $str );

            $str = esc_attr( $str );
            $str = stripslashes( $str );
            $str = str_replace( array( "\r", "\n" ), ' ', $str );

            $str = str_replace( array(
                "Â·",
                "â€¦",
                "â‚¬",
                "&shy;"
            ), "", $str );

            return $str;
        }

        /*
         * Get amount of indexed products
         */
        static public function get_indexed_products_count() {

            global $wpdb;

            $table_name = $wpdb->prefix . AWS_INDEX_TABLE_NAME;

            $indexed_products = 0;

            if ( $wpdb->get_var( "SHOW TABLES LIKE '{$table_name}'" ) === $table_name ) {

                $sql = "SELECT FOUND_ROWS() as rows FROM {$table_name} GROUP BY ID;";

                $indexed_products = $wpdb->query( $sql );

            }

            return $indexed_products;

        }
        
        /*
         * Get special characters that must be striped
         */
        static public function get_special_chars() {
            
            $chars = array(
                '-',
                '_',
                '|',
                '+',
                '`',
                '~',
                '!',
                '@',
                '#',
                '$',
                '%',
                '^',
                '&',
                '*',
                '(',
                ')',
                '\\',
                '?',
                ';',
                ':',
                "'",
                '"',
                ".",
                ",",
                "<",
                ">",
                "{",
                "}",
                "/",
                "[",
                "]",
            );
            
            return apply_filters( 'aws_special_chars', $chars );
            
        }

        /*
         * Strip shortcodes
         */
        static public function strip_shortcodes( $str ) {
            $str = preg_replace( '#\[[^\]]+\]#', '', $str );
            return $str;
        }

    }

endif;