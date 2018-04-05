<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'AWS_Cache' ) ) :

    /**
     * Class for plugin search
     */
    class AWS_Cache {

        /**
         * @var AWS_Cache Cache table name
         */
        private $cache_table_name;

        /**
         * Return a singleton instance of the current class
         *
         * @return object
         */
        public static function factory() {
            static $instance = false;

            if ( ! $instance ) {
                $instance = new self();
                $instance->setup();
            }

            return $instance;
        }

        /**
         * Constructor
         */
        public function __construct() {}

        /**
         * Setup actions and filters for all things settings
         */
        public function setup() {

            global $wpdb;

            $this->cache_table_name = $wpdb->prefix . AWS_CACHE_TABLE_NAME;

            add_action( 'aws_cache_clear', array( $this, 'clear_cache' ) );
            add_action( 'wp_ajax_aws-clear-cache', array( $this, 'clear_cache' ) );

        }

        /**
         * Get caching option name
         */
        public function get_cache_name( $s ) {

            $cache_option_name = 'aws_search_term_' . $s;

            if ( has_filter('wpml_current_language') ) {
                $current_lang = apply_filters('wpml_current_language', NULL);
                if ( $current_lang ) {
                    $cache_option_name = $cache_option_name . '_' . $current_lang;
                }
            }

            return $cache_option_name;

        }

        /*
         * Check if cache table exist
         */
        private function is_cache_table_not_exist() {

            global $wpdb;

            return ( $wpdb->get_var( "SHOW TABLES LIKE '{$this->cache_table_name}'" ) != $this->cache_table_name );

        }

        /*
         * Create cache table
         */
        private function create_cache_table() {

            global $wpdb;

            $charset_collate = $wpdb->get_charset_collate();

            $sql = "CREATE TABLE {$this->cache_table_name} (
                      name VARCHAR(50) NOT NULL,
                      value LONGTEXT NOT NULL
                ) $charset_collate;";

            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            dbDelta( $sql );

        }

        /*
         * Insert data into cache table
         */
        public function insert_into_cache_table( $cache_option_name, $result_array ) {

            global $wpdb;

            $values = $wpdb->prepare(
                "(%s, %s)",
                $cache_option_name, json_encode( $result_array )
            );

            $query  = "INSERT IGNORE INTO {$this->cache_table_name}
				       (`name`, `value`)
                       VALUES $values
            ";

            $wpdb->query( $query );

            if ( $wpdb->last_error ) {
                if ( $this->is_cache_table_not_exist() ) {
                    $this->create_cache_table();
                }
            }

        }

        /*
         * Get data from cache table
         */
        public function get_from_cache_table( $cache_option_name ) {

            global $wpdb;

            $result = '';
            $where = $wpdb->prepare( " name LIKE %s", $cache_option_name );

            $sql = "SELECT *
                FROM
                    {$this->cache_table_name}
                WHERE
                    {$where}
		    ";

            $cache_content = $wpdb->get_results( $sql, ARRAY_A );

            if ( ! $wpdb->last_error ) {
                if (!empty($cache_content) && !is_wp_error($cache_content) && is_array($cache_content)) {
                    $result = $cache_content[0]['value'];
                }
            } else {
                if ( $this->is_cache_table_not_exist() ) {
                    $this->create_cache_table();
                }
            }

            return $result;

        }

        /*
         * Clear cached terms
         */
        public function clear_cache() {

            global $wpdb;

            if ( ! $this->is_cache_table_not_exist() ) {
                
                $terms = "aws_search_term_%";
                $where = $wpdb->prepare( " name LIKE %s", $terms );

                $sql = "DELETE FROM {$this->cache_table_name}
                    WHERE {$where}
                        ";

                $wpdb->query( $sql );
            
            }
            
        }

    }


endif;