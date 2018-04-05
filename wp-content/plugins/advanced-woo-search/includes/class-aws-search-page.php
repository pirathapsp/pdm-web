<?php
/**
 * Integrate with WP_Query
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

if ( ! class_exists( 'AWS_Search_Page' ) ) :

    /**
     * Class for plugin search
     */
    class AWS_Search_Page {

        /**
         * Is set only when we are within a multisite loop
         *
         * @var bool|WP_Query
         */
        private $query_stack = array();

        private $posts_by_query = array();

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
         * Placeholder
         */
        public function __construct() {}

        /**
         * Setup actions and filters for all things settings
         */
        public function setup() {

            // Make sure we return nothing for MySQL posts query
            add_filter( 'posts_request', array( $this, 'filter_posts_request' ), 10, 2 );

            // Query and filter to WP_Query
            add_filter( 'the_posts', array( $this, 'filter_the_posts' ), 10, 2 );

            // Add header
		    add_action( 'pre_get_posts', array( $this, 'action_pre_get_posts' ), 5 );

            // Nukes the FOUND_ROWS() database query
		    add_filter( 'found_posts_query', array( $this, 'filter_found_posts_query' ), 5, 2 );

        }

        /**
         * Check if we should override default search query
         *
         * @param string $query
         * @return bool
         */
        private function aws_searchpage_enabled( $query ) {
            $enabled = true;
            if ( ( isset( $query->query_vars['s'] ) && ! isset( $_GET['type_aws'] ) ) || ! isset( $query->query_vars['s'] ) || ! $query->query_vars['s'] ) {
                $enabled = false;
            }
            return apply_filters( 'aws_searchpage_enabled', $enabled, $query );
        }

        /**
        * Filter query string used for get_posts(). Query for posts and save for later.
        * Return a query that will return nothing.
        *
        * @param string $request
        * @param object $query
        * @return string
        */
        public function filter_posts_request( $request, $query ) {
            if ( ! $this->aws_searchpage_enabled( $query ) ) {
                return $request;
            }

            $new_posts = array();

            $search_query = $query->query_vars['s'];
            
            $posts_array = (array) aws_search( $search_query );
            $posts_per_page = apply_filters( 'aws_posts_per_page', $query->get( 'posts_per_page' ) );

            $query->found_posts = count( $posts_array['products'] );
            $query->max_num_pages = ceil( count( $posts_array['products'] ) / $posts_per_page );

            $paged  = $query->query_vars['paged'] ? $query->query_vars['paged'] : 1;
            $offset = ( $paged > 1 ) ? $paged * $posts_per_page - $posts_per_page : 0;


            if ( $query->query && isset( $query->query['orderby'] ) ) {
                $order_by = $query->query['orderby'];

                if ( isset( $posts_array['products'] ) && ! empty( $posts_array['products'] ) ) {

                    $posts_array['products'] = AWS()->order( $posts_array['products'], $order_by );

                }

            }

            $products = array_slice( $posts_array['products'], $offset, $posts_per_page );


            foreach ( $products as $post_array ) {
                $post = new stdClass();

                $post_array = (array) $post_array;
                $post_data = $post_array['post_data'];

                $post->ID = $post_data->ID;
                $post->site_id = get_current_blog_id();

                if ( ! empty( $post_data->site_id ) ) {
                    $post->site_id = $post_data->site_id;
                }

                $post_return_args = array(
                    'post_type',
                    'post_author',
                    'post_name',
                    'post_status',
                    'post_title',
                    'post_parent',
                    'post_content',
                    'post_excerpt',
                    'post_date',
                    'post_date_gmt',
                    'post_modified',
                    'post_modified_gmt',
                    'post_mime_type',
                    'comment_count',
                    'comment_status',
                    'ping_status',
                    'menu_order',
                    'permalink',
                    'terms',
                    'post_meta'
                );

                foreach ( $post_return_args as $key ) {
                    if ( isset( $post_data->$key ) ) {
                        $post->$key = $post_data->$key;
                    }
                }

                $post->awssearch = true; // Super useful for debugging

                if ( $post ) {
                    $new_posts[] = $post;
                }
            }


            $this->posts_by_query[spl_object_hash( $query )] = $new_posts;


            global $wpdb;

            return "SELECT * FROM $wpdb->posts WHERE 1=0";

        }

        /**
         * Filter the posts array to contain ES query results in EP_Post form. Pull previously queried posts.
         *
         * @param array $posts
         * @param object $query
         * @return array
         */
        public function filter_the_posts( $posts, $query ) {
            if ( ! $this->aws_searchpage_enabled( $query )  || ! isset( $this->posts_by_query[spl_object_hash( $query )] ) ) {
                return $posts;
            }

            $new_posts = $this->posts_by_query[spl_object_hash( $query )];

            return $new_posts;

        }

        /**
         * Disables cache_results, adds header.
         *
         * @param $query
         */
        public function action_pre_get_posts( $query ) {
            if ( ! $this->aws_searchpage_enabled( $query ) ) {
                return;
            }

            /**
             * `cache_results` defaults to false but can be enabled
             */
            $query->set( 'cache_results', false );
            if ( ! empty( $query->query['cache_results'] ) ) {
                $query->set( 'cache_results', true );
            }

            if ( ! headers_sent() ) {
                /**
                 * Manually setting a header as $wp_query isn't yet initialized
                 * when we call: add_filter('wp_headers', 'filter_wp_headers');
                 */
                header( 'X-AWS-Search: true' );
            }
        }

        /**
         * Remove the found_rows from the SQL Query
         *
         * @param string $sql
         * @param object $query
         * @return string
         */
        public function filter_found_posts_query( $sql, $query ) {
            if ( ! $this->aws_searchpage_enabled( $query ) ) {
                return $sql;
            }

            return '';
        }

    }


endif;

AWS_Search_Page::factory();