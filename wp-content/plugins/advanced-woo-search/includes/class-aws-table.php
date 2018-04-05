<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'AWS_Table' ) ) :

    /**
     * Class for plugin index table
     */
    class AWS_Table {

        /**
         * @var AWS_Table Index table name
         */
        private $table_name;

        /**
         * Constructor
         */
        public function __construct() {

            global $wpdb;

            $this->table_name = $wpdb->prefix . AWS_INDEX_TABLE_NAME;

            add_action( 'wp_insert_post', array( $this, 'product_changed' ), 10, 3 );

            add_action( 'create_term', array( &$this, 'term_changed' ), 10, 3 );
            add_action( 'delete_term', array( &$this, 'term_changed' ), 10, 3 );
            add_action( 'edit_term', array( &$this, 'term_changed' ), 10, 3 );
           
            if ( defined('WOOCOMMERCE_VERSION') ) {              
                if ( version_compare( WOOCOMMERCE_VERSION, '3.0', ">=" ) ) {
                    add_action( 'woocommerce_variable_product_sync_data', array( $this, 'variable_product_changed' ) );
                } else {
                    add_action( 'woocommerce_variable_product_sync', array( $this, 'variable_product_changed' ), 10, 2 );  
                }
            }

            add_action( 'updated_postmeta', array( $this, 'updated_custom_tabs' ), 10, 4 );

            add_action( 'wp_ajax_aws-reindex', array( $this, 'reindex_table' ) );

            add_action( 'aws_reindex_table', array( $this, 'reindex_table_job' ) );

        }

        /*
         * Reindex plugin table
         */
        public function reindex_table( $data = false ) {

            global $wpdb;

            $index_meta = $data ? $data : $_POST['data'];
            $status = false;

            // No current index going on. Let's start over
            if ( 'start' === $index_meta ) {
                $status = 'start';
                $index_meta = array(
                    'offset' => 0,
                    'start' => true,
                );

                $wpdb->query("DROP TABLE IF EXISTS {$this->table_name}");

                $this->create_table();

                $index_meta['found_posts'] = $this->get_number_of_products();

            } else if ( ! empty( $index_meta['site_stack'] ) && $index_meta['offset'] >= $index_meta['found_posts'] ) {
                $status = 'start';

                $index_meta['start'] = true;
                $index_meta['offset'] = 0;
                $index_meta['current_site'] = array_shift( $index_meta['site_stack'] );
            } else {
                $index_meta['start'] = false;
            }

            $index_meta = apply_filters( 'aws_index_meta', $index_meta );
            $posts_per_page = apply_filters( 'aws_index_posts_per_page', 10 );


            $args = array(
                'posts_per_page'      => $posts_per_page,
                'fields'              => 'ids',
                'post_type'           => 'product',
                'post_status'         => 'publish',
                'offset'              => $index_meta['offset'],
                'ignore_sticky_posts' => true,
                'suppress_filters'    => true,
                'no_found_rows'       => 1,
                'orderby'             => 'ID',
                'order'               => 'DESC',
            );


            $posts = get_posts( $args );

            if ( $status !== 'start' ) {

                if ( $posts && count( $posts ) > 0 ) {

                    $queued_posts = array();

                    foreach( $posts as $post_id ) {
                        $queued_posts[] = absint( $post_id );
                    }

                    $this->fill_table( $queued_posts );

                    $index_meta['offset'] = absint( $index_meta['offset'] + $posts_per_page );

                    if ( $index_meta['offset'] >= $index_meta['found_posts'] ) {
                        $index_meta['offset'] = $index_meta['found_posts'];
                    }

                } else {
                    // We are done (with this site)

                    $index_meta['offset'] = (int) count( $posts );

                    do_action('aws_cache_clear');

                    update_option( 'aws_reindex_version', AWS_VERSION );

                }

            }

            if ( $data ) {
                return $index_meta;
            } else {
                wp_send_json_success( $index_meta );
            }

        }

        /*
         * Cron job function
         */
        public function reindex_table_job() {

            $meta = 'start';

            do {
                $meta = $this->reindex_table( $meta );
                $offset = (int) isset( $meta['offset'] ) ? $meta['offset'] : 0;
                $start = (int) isset( $meta['start'] ) ? $meta['start'] : 0;
            } while ( !( $offset === 0 && ! $start ) );

        }

        /*
         * Get total number of products
         */
        private function get_number_of_products() {

            $args = array(
                'posts_per_page'      => -1,
                'fields'              => 'ids',
                'post_type'           => 'product',
                'post_status'         => 'publish',
                'ignore_sticky_posts' => true,
                'suppress_filters'    => true,
                'no_found_rows'       => 1,
                'orderby'             => 'ID',
                'order'               => 'DESC',
            );


            $posts = get_posts( $args );

            if ( $posts && count( $posts ) > 0 ) {
                $count = count( $posts );
            } else {
                $count = 0;
            }

            return $count;

        }

        /*
         * Check if index table exist
         */
        private function is_table_not_exist() {

            global $wpdb;

            return ( $wpdb->get_var( "SHOW TABLES LIKE '{$this->table_name}'" ) != $this->table_name );

        }

        /*
         * Create index table
         */
        private function create_table() {

            global $wpdb;

            $charset_collate = $wpdb->get_charset_collate();

            $sql = "CREATE TABLE {$this->table_name} (
                      id BIGINT(20) UNSIGNED NOT NULL DEFAULT 0,
                      term VARCHAR(50) NOT NULL DEFAULT 0,
                      term_source VARCHAR(20) NOT NULL DEFAULT 0,
                      type VARCHAR(50) NOT NULL DEFAULT 0,
                      count BIGINT(20) UNSIGNED NOT NULL DEFAULT 0,
                      in_stock INT(11) NOT NULL DEFAULT 0,
                      visibility VARCHAR(20) NOT NULL DEFAULT 0,
                      lang VARCHAR(20) NOT NULL DEFAULT 0
                ) $charset_collate;";

            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            dbDelta( $sql );

        }

        /*
         * Insert data into the index table
         */
        private function fill_table( $posts ) {

            foreach ( $posts as $found_post_id ) {

                $data = array();

                $data['terms'] = array();
                $data['id'] = $found_post_id;

                if ( $this->is_excluded( $data['id'] ) ) {
                    continue;
                }

                $product = wc_get_product( $data['id'] );

                if( ! is_a( $product, 'WC_Product' ) ) {
                    continue;
                }


                $lang = '';

                if ( defined( 'ICL_SITEPRESS_VERSION' ) && has_filter( 'wpml_post_language_details' ) ) {
                    $lang = apply_filters( 'wpml_post_language_details', NULL, $data['id'] );
                    $lang = $lang['language_code'];
                } elseif ( function_exists( 'pll_default_language' ) && function_exists( 'pll_get_post_language' ) ) {
                    $lang = pll_get_post_language( $data['id'] ) ? pll_get_post_language( $data['id'] ) : pll_default_language();
                } elseif ( function_exists( 'qtranxf_getLanguageDefault' ) ) {
                    $lang = qtranxf_getLanguageDefault();
                }


                $data['in_stock'] = method_exists( $product, 'get_stock_status' ) ? ( ( $product->get_stock_status() === 'outofstock' ) ? 0 : 1 ) : ( method_exists( $product, 'is_in_stock' ) ? $product->is_in_stock() : 1 );
                $data['visibility'] = method_exists( $product, 'get_catalog_visibility' ) ? $product->get_catalog_visibility() : ( method_exists( $product, 'get_visibility' ) ? $product->get_visibility() : 'visible' );
                $data['lang'] = $lang ? $lang : '';

                $sku = $product->get_sku();

                $title = apply_filters( 'the_title', get_the_title( $data['id'] ) );
                $content = apply_filters( 'the_content', get_post_field( 'post_content', $data['id'] ) );
                $excerpt = get_post_field( 'post_excerpt', $data['id'] );


                $cat_names = $this->get_terms_names_list( $data['id'], 'product_cat' );
                $tag_names = $this->get_terms_names_list( $data['id'], 'product_tag' );


                // Get all child products if exists
                if ( $product->is_type( 'variable' ) && class_exists( 'WC_Product_Variation' ) ) {

                    if ( sizeof( $product->get_children() ) > 0 ) {

                        foreach ( $product->get_children() as $child_id ) {

                            $variation_product = new WC_Product_Variation( $child_id );

                            $variation_sku = $variation_product->get_sku();

                            $variation_desc = '';
                            if ( method_exists( $variation_product, 'get_description' ) ) {
                                $variation_desc = $variation_product->get_description();
                            }

                            if ( $variation_sku ) {
                                $sku = $sku . ' ' . $variation_sku;
                            }

                            if ( $variation_desc ) {
                                $content = $content . ' ' . $variation_desc;
                            }

                        }

                    }

                }


                // Get content from Custom Product Tabs
                if ( $custom_tabs = get_post_meta( $data['id'], 'yikes_woo_products_tabs' ) ) {
                    if ( $custom_tabs && ! empty( $custom_tabs ) ) {
                        foreach( $custom_tabs as $custom_tab_array ) {
                            if ( $custom_tab_array && ! empty( $custom_tab_array ) ) {
                                foreach( $custom_tab_array as $custom_tab ) {
                                    if ( isset( $custom_tab['content'] ) && $custom_tab['content'] ) {
                                        $content = $content . ' ' . $custom_tab['content'];
                                    }
                                }
                            }
                        }
                    }
                }


                // WP 4.2 emoji strip
                if ( function_exists( 'wp_encode_emoji' ) ) {
                    $content = wp_encode_emoji( $content );
                }

                $content = AWS_Helpers::strip_shortcodes( $content );
                $excerpt = AWS_Helpers::strip_shortcodes( $excerpt );

                /**
                 * Filters product title before it will be indexed.
                 *
                 * @since 1.37
                 *
                 * @param string $title Product title.
                 * @param int $data['id'] Product id.
                 * @param object $product Current product object.
                 */
                $title = apply_filters( 'aws_indexed_title', $title, $data['id'], $product );

                /**
                 * Filters product content before it will be indexed.
                 *
                 * @since 1.37
                 *
                 * @param string $content Product content.
                 * @param int $data['id'] Product id.
                 * @param object $product Current product object.
                 */
                $content = apply_filters( 'aws_indexed_content', $content, $data['id'], $product );

                /**
                 * Filters product excerpt before it will be indexed.
                 *
                 * @since 1.37
                 *
                 * @param string $excerpt Product excerpt.
                 * @param int $data['id'] Product id.
                 * @param object $product Current product object.
                 */
                $excerpt = apply_filters( 'aws_indexed_excerpt', $excerpt, $data['id'], $product );


                $data['terms']['title']    = $this->extract_terms( $title );
                $data['terms']['content']  = $this->extract_terms( $content );
                $data['terms']['excerpt']  = $this->extract_terms( $excerpt );
                $data['terms']['sku']      = $this->extract_terms( $sku );
                $data['terms']['category'] = $this->extract_terms( $cat_names );
                $data['terms']['tag']      = $this->extract_terms( $tag_names );


                // Get translations if exists ( WPML )
                if ( defined( 'ICL_SITEPRESS_VERSION' ) && has_filter('wpml_element_has_translations') && has_filter('wpml_get_element_translations') ) {

                    $is_translated = apply_filters( 'wpml_element_has_translations', NULL, $data['id'], 'post_product' );

                    if ( $is_translated ) {

                        $translations = apply_filters( 'wpml_get_element_translations', NULL, $data['id'], 'post_product');

                        foreach( $translations as $language => $lang_obj ) {
                            if ( ! $lang_obj->original && $lang_obj->post_status === 'publish' ) {
                                $translated_post =  get_post( $lang_obj->element_id );
                                if ( $translated_post && !empty( $translated_post ) ) {

                                    $translated_post_data = array();
                                    $translated_post_data['id'] = $translated_post->ID;
                                    $translated_post_data['in_stock'] = $data['in_stock'];
                                    $translated_post_data['visibility'] = $data['visibility'];
                                    $translated_post_data['lang'] = $lang_obj->language_code;
                                    $translated_post_data['terms'] = array();

                                    $translated_title = apply_filters( 'the_title', get_the_title( $translated_post->ID ) );
                                    $translated_content = apply_filters( 'the_content', get_post_field( 'post_content', $translated_post->ID ) );
                                    $translated_excerpt = get_post_field( 'post_excerpt', $translated_post->ID );

                                    $translated_content = AWS_Helpers::strip_shortcodes( $translated_content );
                                    $translated_excerpt = AWS_Helpers::strip_shortcodes( $translated_excerpt );

                                    $translated_post_data['terms']['title'] = $this->extract_terms( $translated_title );
                                    $translated_post_data['terms']['content'] = $this->extract_terms( $translated_content );
                                    $translated_post_data['terms']['excerpt'] = $this->extract_terms( $translated_excerpt );
                                    $translated_post_data['terms']['sku'] = $this->extract_terms( $sku );


                                    //Insert translated product data into table
                                    $this->insert_into_table( $translated_post_data );

                                }
                            }
                        }

                    }

                }
                elseif ( function_exists( 'qtranxf_use' ) ) {

                    $enabled_languages = get_option( 'qtranslate_enabled_languages' );

                    if ( $enabled_languages ) {

                        foreach( $enabled_languages as $current_lang ) {

                            if ( $current_lang == $lang ) {
                                $default_lang_title = qtranxf_use( $current_lang, $product->get_name(), true, true );
                                $data['terms']['title'] = $this->extract_terms( $default_lang_title );
                                continue;
                            }

                            if ( function_exists( 'qtranxf_isAvailableIn' ) && qtranxf_isAvailableIn( $data['id'], $current_lang ) ) {

                                if ( method_exists( $product, 'get_description' ) && method_exists( $product, 'get_name' ) && method_exists( $product, 'get_short_description' ) ) {

                                    $translated_post_data = array();
                                    $translated_post_data['id'] = $data['id'];
                                    $translated_post_data['in_stock'] = $data['in_stock'];
                                    $translated_post_data['visibility'] = $data['visibility'];
                                    $translated_post_data['lang'] = $current_lang;
                                    $translated_post_data['terms'] = array();

                                    $translated_title = qtranxf_use( $current_lang, $product->get_name(), true, true );
                                    $translated_content = qtranxf_use( $current_lang, $product->get_description(), true, true );
                                    $translated_excerpt = qtranxf_use( $current_lang, $product->get_short_description(), true, true );

                                    $translated_content = AWS_Helpers::strip_shortcodes( $translated_content );
                                    $translated_excerpt = AWS_Helpers::strip_shortcodes( $translated_excerpt );

                                    $translated_post_data['terms']['title'] = $this->extract_terms( $translated_title );
                                    $translated_post_data['terms']['content'] = $this->extract_terms( $translated_content );
                                    $translated_post_data['terms']['excerpt'] = $this->extract_terms( $translated_excerpt );
                                    $translated_post_data['terms']['sku'] = $this->extract_terms( $sku );


                                    //Insert translated product data into table
                                    $this->insert_into_table( $translated_post_data );

                                }

                            }

                        }

                    }

                }


                //Insert data into table
                $this->insert_into_table( $data );


            }

        }

        /*
         * Scrap all product data and insert to table
         */
        private function insert_into_table( $data ) {

            global $wpdb;

            $values = array();

            foreach( $data['terms'] as $source => $all_terms ) {

                foreach ( $all_terms as $term => $count ) {

                    if ( ! $term ) {
                        continue;
                    }

                    $value = $wpdb->prepare(
                        "(%d, %s, %s, %s, %d, %d, %s, %s)",
                        $data['id'], $term, $source, 'product', $count, $data['in_stock'], $data['visibility'], $data['lang']
                    );

                    $values[] = $value;

                }

            }


            if ( count( $values ) > 0 ) {

                $values = implode( ', ', $values );

                $query  = "INSERT IGNORE INTO {$this->table_name}
				              (`id`, `term`, `term_source`, `type`, `count`, `in_stock`, `visibility`, `lang`)
				              VALUES $values
                    ";

                $wpdb->query( $query );

            }

        }

        /*
         * Fires when products terms are changed
         */
        public function term_changed( $term_id, $tt_id, $taxonomy ) {

            if ( $taxonomy === 'product_cat' || $taxonomy === 'product_tag' ) {
                do_action( 'aws_cache_clear' );
            }

        }

        /*
         * Update index table
         */
        public function product_changed( $post_id, $post, $update ) {

            $slug = 'product';

            if ( $slug != $post->post_type ) {
                return;
            }

            $this->update_table( $post_id );

        }

        /*
         * Fires when products variations are changed
         */
        public function variable_product_changed( $product, $children = null ) {

            $product_id = '';

            if ( is_object( $product ) ) {
                $product_id = $product->get_id();
            } else {
                $product_id = $product;
            }

            $this->update_table( $product_id );

        }

        /*
         * Custom Tabs was updated
         */
        public function updated_custom_tabs( $meta_id, $object_id, $meta_key, $meta_value ) {

            if ( $meta_key === 'yikes_woo_products_tabs' ) {

                $this->update_table( $object_id );

            }

        }

        /*
         * Update index table
         */
        private function update_table( $product_id ) {

            global $wpdb;

            if ( $this->is_table_not_exist() ) {
                $this->create_table();
            }

            $wpdb->delete( $this->table_name, array( 'id' => $product_id ) );

            $posts = get_posts( array(
                'posts_per_page'   => -1,
                'fields'           => 'ids',
                'post_type'        => 'product',
                'post_status'      => 'publish',
                'suppress_filters' => false,
                'no_found_rows'    => 1,
                'include'          => $product_id
            ) );

            $this->fill_table( $posts );

            do_action('aws_cache_clear');

        }

        /*
         * Check is product excluded with Search Exclude plugin ( https://wordpress.org/plugins/search-exclude/ )
         */
        private function is_excluded( $id ) {

            $excluded = get_option('sep_exclude');

            if ( $excluded && is_array( $excluded ) && ! empty( $excluded ) ) {
                if ( false !== array_search( $id, $excluded ) ) {
                    return true;
                }
            }

            return false;

        }

        /*
         * Extract terms from content
         */
        private function extract_terms( $str ) {

            $stopwords = AWS()->get_settings( 'stopwords' );

            $str = AWS_Helpers::html2txt( $str );

            // Avoid single A-Z.
            //$str = preg_replace( '/\b\w{1}\b/i', " ", $str );
            
            $special_cars = AWS_Helpers::get_special_chars();
            $str = str_replace( $special_cars, "", $str );

            $str = str_replace( array(
                "Ă‹â€ˇ",
                "Ă‚Â°",
                "Ă‹â€ş",
                "Ă‹ĹĄ",
                "Ă‚Â¸",
                "Ă‚Â§",
                "=",
                "Ă‚Â¨",
                "â€™",
                "â€",
                "â€ť",
                "â€ś",
                "â€ž",
                "Â´",
                "â€”",
                "â€“",
                "Ă—",
                '&#8217;',
                "&nbsp;",
                chr( 194 ) . chr( 160 )
            ), " ", $str );

            $str = str_replace( 'Ăź', 'ss', $str );

            //$str = preg_replace( '/[[:punct:]]+/u', ' ', $str );
            $str = preg_replace( '/[[:space:]]+/', ' ', $str );

            // Most objects except unicode characters
            $str = preg_replace( '/[\x00-\x08\x0B\x0C\x0E-\x1F\x80-\x9F]/u', '', $str );

            // Line feeds, carriage returns, tabs
            $str = preg_replace( '/[\x00-\x1F\x80-\x9F]/u', '', $str );

            if ( function_exists( 'mb_strtolower' ) ) {
                $str = mb_strtolower( $str );
            } else {
                $str = strtolower( $str );
            }

            $str = preg_replace( '/^[a-z]$/i', "", $str );

            $str = trim( preg_replace( '/\s+/', ' ', $str ) );

            $str_array = array_count_values( explode( ' ', $str ) );


            if ( $stopwords && $str_array && ! empty( $str_array ) ) {
                $stopwords_array = explode( ',', $stopwords );
                if ( $stopwords_array && ! empty( $stopwords_array ) ) {
                    $stopwords_array = array_map( 'trim', $stopwords_array );

                    foreach ( $str_array as $str_word => $str_count ) {
                        if ( in_array( $str_word, $stopwords_array ) ) {
                            unset( $str_array[$str_word] );
                        }
                    }

                }
            }


            return $str_array;

        }

        /*
         * Get string with current product terms ids
         *
         * @return string List of terms ids
         */
        private function get_terms_list( $id, $taxonomy ) {

            $terms = get_the_terms( $id, $taxonomy );

            if ( is_wp_error( $terms ) ) {
                return '';
            }

            if ( empty( $terms ) ) {
                return '';
            }

            $cats_array_temp = array();

            foreach ( $terms as $term ) {
                $cats_array_temp[] = $term->term_id;
            }

            return implode( ', ', $cats_array_temp );

        }

        /*
         * Get string with current product terms names
         *
         * @return string List of terms names
         */
        private function get_terms_names_list( $id, $taxonomy ) {

            $terms = get_the_terms( $id, $taxonomy );

            if ( is_wp_error( $terms ) ) {
                return '';
            }

            if ( empty( $terms ) ) {
                return '';
            }

            $cats_array_temp = array();

            foreach ( $terms as $term ) {
                $cats_array_temp[] = $term->name;
            }

            return implode( ', ', $cats_array_temp );

        }

    }

endif;


new AWS_Table();