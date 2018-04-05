<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'AWS_Search' ) ) :

    /**
     * Class for plugin search
     */
    class AWS_Search {

        /**
         * @var AWS_Search Array of all plugin data $data
         */
        private $data = array();

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

            $this->data['settings'] = get_option( 'aws_settings' );

            add_action( 'wp_ajax_aws_action', array( $this, 'action_callback' ) );
            add_action( 'wp_ajax_nopriv_aws_action', array( $this, 'action_callback' ) );

        }
        
        /*
         * AJAX call action callback
         */
        public function action_callback() {

            echo json_encode( $this->search() );

            die;

        }
        
        /*
         * Search
         */
        public function search( $keyword = ''  ) {

            global $wpdb;

            $cache = AWS()->get_settings( 'cache' );
            $special_chars = AWS_Helpers::get_special_chars();

            $s = $keyword ? esc_attr( $keyword ) : esc_attr( $_POST['keyword'] );
            $s = stripslashes( $s );
            $s = str_replace( array( "\r", "\n" ), '', $s );
            $s = str_replace( $special_chars, '', $s );

            $cache_option_name = '';
            
            if ( $cache === 'true' && ! $keyword  ) {
                $cache_option_name = AWS()->cache->get_cache_name( $s );
                $res = AWS()->cache->get_from_cache_table( $cache_option_name );
                if ( $res ) {
                    $cached_value = json_decode( $res );
                    if ( $cached_value && ! empty( $cached_value ) ) {
                        return $cached_value;
                    }
                }
            }

            $show_cats     = AWS()->get_settings( 'show_cats' );
            $show_tags     = AWS()->get_settings( 'show_tags' );
            $results_num   = $keyword ? apply_filters( 'aws_page_results', 100 ) : AWS()->get_settings( 'results_num' );
            $search_in     = AWS()->get_settings( 'search_in' );
            $outofstock    = AWS()->get_settings( 'outofstock' );

            $search_in_arr = explode( ',',  AWS()->get_settings( 'search_in' ) );

            // Search in title if all options is disabled
            if ( ! $search_in ) {
                $search_in_arr = array( 'title' );
            }

            $categories_array = array();
            $tags_array = array();


            $this->data['s'] = $s;
            $this->data['results_num']  = $results_num ? $results_num : 10;
            $this->data['search_terms'] = array();
            $this->data['search_terms'] = array_unique( explode( ' ', $s ) );
            $this->data['search_in']    = $search_in_arr;
            $this->data['outofstock']   = $outofstock;


            $posts_ids = $this->query_index_table();
            $products_array = $this->get_products( $posts_ids );


            if ( $show_cats === 'true' ) {
                $categories_array = $this->get_taxonomies( 'product_cat' );
            }

            if ( $show_tags === 'true' ) {
                $tags_array = $this->get_taxonomies( 'product_tag' );
            }

            $result_array = array(
                'cats'     => $categories_array,
                'tags'     => $tags_array,
                'products' => $products_array
            );


            if ( $cache === 'true' && ! $keyword  ) {
                AWS()->cache->insert_into_cache_table( $cache_option_name, $result_array );
            }


            return $result_array;

        }

        /*
         * Query in index table
         */
        private function query_index_table() {

            global $wpdb;

            $table_name = $wpdb->prefix . AWS_INDEX_TABLE_NAME;

            $search_in_arr    = $this->data['search_in'];
            $results_num      = $this->data['results_num'];
            $outofstock       = $this->data['outofstock'];


            $reindex_version = get_option( 'aws_reindex_version' );

            $query = array();

            $query['search'] = '';
            $query['source'] = '';
            $query['relevance'] = '';
            $query['stock'] = '';
            $query['visibility'] = '';
            $query['lang'] = '';

            $search_array = array();
            $source_array = array();
            $relevance_array = array();
            $new_relevance_array = array();


            foreach ( $this->data['search_terms'] as $search_term ) {

                $search_term_len = strlen( $search_term );

                $relevance_title        = 200 + 20 * $search_term_len;
                $relevance_content      = 35 + 4 * $search_term_len;
                $relevance_title_like   = 40 + 2 * $search_term_len;
                $relevance_content_like = 35 + 1 * $search_term_len;

                $search_term_like = preg_replace( '/(s|es|ies)$/i', '', $search_term );
                $like = '%' . $wpdb->esc_like( $search_term_like ) . '%';

                if ( $search_term_len > 1 ) {
                    $search_array[] = $wpdb->prepare( '( term LIKE %s )', $like );
                } else {
                    $search_array[] = $wpdb->prepare( '( term = "%s" )', $search_term );
                }

                foreach ( $search_in_arr as $search_in_term ) {

                    switch ( $search_in_term ) {

                        case 'title':
                            $relevance_array['title'][] = $wpdb->prepare( "( case when ( term_source = 'title' AND term = '%s' ) then {$relevance_title} * count else 0 end )", $search_term );
                            $relevance_array['title'][] = $wpdb->prepare( "( case when ( term_source = 'title' AND term LIKE %s ) then {$relevance_title_like} * count else 0 end )", $like );
                            break;

                        case 'content':
                            $relevance_array['content'][] = $wpdb->prepare( "( case when ( term_source = 'content' AND term = '%s' ) then {$relevance_content} * count else 0 end )", $search_term );
                            $relevance_array['content'][] = $wpdb->prepare( "( case when ( term_source = 'content' AND term LIKE %s ) then {$relevance_content_like} * count else 0 end )", $like );
                            break;

                        case 'excerpt':
                            $relevance_array['content'][] = $wpdb->prepare( "( case when ( term_source = 'excerpt' AND term = '%s' ) then {$relevance_content} * count else 0 end )", $search_term );
                            $relevance_array['content'][] = $wpdb->prepare( "( case when ( term_source = 'excerpt' AND term LIKE %s ) then {$relevance_content_like} * count else 0 end )", $like );
                            break;

                        case 'category':
                            $relevance_array['category'][] = $wpdb->prepare( "( case when ( term_source = 'category' AND term = '%s' ) then 35 else 0 end )", $search_term );
                            $relevance_array['category'][] = $wpdb->prepare( "( case when ( term_source = 'category' AND term LIKE %s ) then 5 else 0 end )", $like );
                            break;

                        case 'tag':
                            $relevance_array['tag'][] = $wpdb->prepare( "( case when ( term_source = 'tag' AND term = '%s' ) then 35 else 0 end )", $search_term );
                            $relevance_array['tag'][] = $wpdb->prepare( "( case when ( term_source = 'tag' AND term LIKE %s ) then 5 else 0 end )", $like );
                            break;

                        case 'sku':
                            $relevance_array['sku'][] = $wpdb->prepare( "( case when ( term_source = 'sku' AND term = '%s' ) then 300 else 0 end )", $search_term );
                            $relevance_array['sku'][] = $wpdb->prepare( "( case when ( term_source = 'sku' AND term LIKE %s ) then 50 else 0 end )", $like );
                            break;

                    }

                }

            }

            // Sort 'relevance' queries in the array by search priority
            foreach ( $search_in_arr as $search_in_item ) {
                if ( isset( $relevance_array[$search_in_item] ) ) {
                    $new_relevance_array[$search_in_item] = implode( ' + ', $relevance_array[$search_in_item] );
                }
            }

            foreach ( $search_in_arr as $search_in_term ) {
                $source_array[] = "term_source = '{$search_in_term}'";
            }

            $query['relevance'] .= sprintf( ' (SUM( %s )) ', implode( ' + ', $new_relevance_array ) );
            $query['search'] .= sprintf( ' AND ( %s )', implode( ' OR ', $search_array ) );
            $query['source'] .= sprintf( ' AND ( %s )', implode( ' OR ', $source_array ) );


            if ( $reindex_version && version_compare( $reindex_version, '1.16', '>=' ) ) {

                if ( $outofstock !== 'true' ) {
                    $query['stock'] .= " AND in_stock = 1";
                }

                $query['visibility'] .= " AND NOT visibility LIKE '%hidden%'";

            }


            if ( ( defined( 'ICL_SITEPRESS_VERSION' ) || function_exists( 'pll_current_language' ) ) && $reindex_version && version_compare( $reindex_version, '1.20', '>=' ) ) {

                $current_lang = false;

                if ( has_filter('wpml_current_language') ) {
                    $current_lang = apply_filters( 'wpml_current_language', NULL );
                } elseif ( function_exists( 'pll_current_language' ) ) {
                    $current_lang = pll_current_language();
                }

                if ( $current_lang ) {
                    $query['lang'] .= $wpdb->prepare( " AND ( lang LIKE %s OR lang = '' )", $current_lang );
                }

            } elseif( function_exists( 'qtranxf_getLanguage' ) ) {

                $current_lang = qtranxf_getLanguage();

                if ( $current_lang ) {
                    $query['lang'] .= $wpdb->prepare( " AND ( lang LIKE %s OR lang = '' )", $current_lang );
                }

            }


            $sql = "SELECT
                    distinct ID,
                    {$query['relevance']} as relevance
                FROM
                    {$table_name}
                WHERE
                    type = 'product'
                {$query['source']}
                {$query['search']}
                {$query['stock']}
                {$query['visibility']}
                {$query['lang']}
                GROUP BY ID
                ORDER BY
                    relevance DESC
				LIMIT 0, {$results_num}
		    ";

            $posts_ids = $this->get_posts_ids( $sql );

            return $posts_ids;

        }

        /*
         * Get array of included to search result posts ids
         */
        private function get_posts_ids( $sql ) {

            global $wpdb;

            $posts_ids = array();

            $search_results = $wpdb->get_results( $sql );


            if ( !empty( $search_results ) && !is_wp_error( $search_results ) && is_array( $search_results ) ) {
                foreach ( $search_results as $search_result ) {
                    $post_id = intval( $search_result->ID );
                    if ( ! in_array( $post_id, $posts_ids ) ) {
                        $posts_ids[] = $post_id;
                    }
                }
            }

            unset( $search_results );

            return $posts_ids;

        }

        /*
         * Get products info
         */
        private function get_products( $posts_ids ) {

            $products_array = array();

            if ( count( $posts_ids ) > 0 ) {

                $show_excerpt      = AWS()->get_settings( 'show_excerpt' );
                $excerpt_source    = AWS()->get_settings( 'desc_source' );
                $excerpt_length    = AWS()->get_settings( 'excerpt_length' );
                $mark_search_words = AWS()->get_settings( 'mark_words' );
                $show_price        = AWS()->get_settings( 'show_price' );
                $show_sale         = AWS()->get_settings( 'show_sale' );
                $show_image        = AWS()->get_settings( 'show_image' );
                $show_sku          = AWS()->get_settings( 'show_sku' );
                $show_stock_status = AWS()->get_settings( 'show_stock' );


                foreach ( $posts_ids as $post_id ) {

                    $product = wc_get_product( $post_id );

                    if( ! is_a( $product, 'WC_Product' ) ) {
                        continue;
                    }

                    $post_data = get_post( $post_id );

                    $title = $product->get_title();
                    $title = AWS_Helpers::html2txt( $title );

                    $excerpt      = '';
                    $price        = '';
                    $on_sale      = '';
                    $image        = '';
                    $sku          = '';
                    $stock_status = '';


                    if ( $show_excerpt === 'true' ) {
                        $excerpt = ( $excerpt_source === 'excerpt' && $post_data->post_excerpt ) ? $post_data->post_excerpt : $post_data->post_content;
                        $excerpt = AWS_Helpers::html2txt( $excerpt );
                        $excerpt = str_replace('"', "'", $excerpt);
                        $excerpt = strip_shortcodes( $excerpt );
                        $excerpt = AWS_Helpers::strip_shortcodes( $excerpt );
                    }

                    if ( $mark_search_words === 'true'  ) {

                        $marked_content = $this->mark_search_words( $title, $excerpt );

                        $title   = $marked_content['title'];
                        $excerpt = $marked_content['content'];

                    } else {
                        $excerpt = wp_trim_words( $excerpt, $excerpt_length, '...' );
                    }

                    if ( $show_price === 'true' ) {
                        $price = $product->get_price_html();
                    }

                    if ( $show_sale === 'true' ) {
                        $on_sale = $product->is_on_sale();
                    }

                    if ( $show_image === 'true' ) {
                        $image_id = $product->get_image_id();
                        $image_attributes = wp_get_attachment_image_src( $image_id );
                        $image = $image_attributes[0];
                    }

                    if ( $show_sku === 'true' ) {
                        $sku = $product->get_sku();
                    }
                    
                    if ( $show_stock_status === 'true' ) {
                        if ( $product->is_in_stock() ) {
                            $stock_status = array(
                                'status' => true,
                                'text'   => __( 'In stock', 'aws' )
                            );
                        } else {
                            $stock_status = array(
                                'status' => false,
                                'text'   => __( 'Out of stock', 'aws' )
                            );
                        }
                    }


                    $f_price   = $product->get_price();
                    $f_rating  = $product->get_average_rating();
                    $f_reviews = $product->get_review_count();

//                    $categories = $product->get_categories( ',' );
//                    $tags = $product->get_tags( ',' );

                    $new_result = array(
                        'title'        => $title,
                        'excerpt'      => $excerpt,
                        'link'         => get_permalink( $post_id ),
                        'image'        => $image,
                        'price'        => $price,
                        'on_sale'      => $on_sale,
                        'sku'          => $sku,
                        'stock_status' => $stock_status,
                        'f_price'      => $f_price,
                        'f_rating'     => $f_rating,
                        'f_reviews'    => $f_reviews,
                        'post_data'    => $post_data
                    );

                    $products_array[] = $new_result;
                }

            }

            return $products_array;

        }

        /*
         * Mark search words
         */
        private function mark_search_words( $title, $content ) {

            $pattern = array();
            $exact_pattern = array();
            $exact_words = array();
            $words = array();

            foreach( $this->data['search_terms'] as $search_in ) {

                $exact_words[] = '\b' . $search_in . '\b';
                $exact_pattern[] = '(\b' . $search_in . '\b)+';

                if ( strlen( $search_in ) > 1 ) {
                    $pattern[] = '(' . $search_in . ')+';
                    $words[] = $search_in;
                } else {
                    $pattern[] = '\b[' . $search_in . ']{1}\b';
                    $words[] = '\b' . $search_in . '\b';
                }

            }

            usort( $exact_words, array( $this, 'sort_by_length' ) );
            $exact_words = implode( '|', $exact_words );

            usort( $words, array( $this, 'sort_by_length' ) );
            $words = implode( '|', $words );

            usort( $exact_pattern, array( $this, 'sort_by_length' ) );
            $exact_pattern = implode( '|', $exact_pattern );
            $exact_pattern = sprintf( '/%s/i', $exact_pattern );

            usort( $pattern, array( $this, 'sort_by_length' ) );
            $pattern = implode( '|', $pattern );
            $pattern = sprintf( '/%s/i', $pattern );


            preg_match( '/([^.?!]*?)(' . $exact_words . '){1}(.*?[.!?])/i', $content, $matches );

            if ( ! isset( $matches[0] ) ) {
                preg_match( '/([^.?!]*?)(' . $words . '){1}(.*?[.!?])/i', $content, $matches );
            }

            if ( ! isset( $matches[0] ) ) {
                preg_match( '/([^.?!]*?)(.*?)(.*?[.!?])/i', $content, $matches );
            }

            if ( isset( $matches[0] ) ) {

                $content = $matches[0];

                // Trim to long content
                if (str_word_count(strip_tags($content)) > 34) {

                    if (str_word_count(strip_tags($matches[3])) > 34) {
                        $matches[3] = wp_trim_words($matches[3], 30, '...');
                    }

                    $content = '...' . $matches[2] . $matches[3];

                }

            } else {

                $content = '';

            }

            $title_has_exact = preg_match( '/(' . $exact_words . '){1}/i', $title );
            $content_has_exact = preg_match( '/(' . $exact_words . '){1}/i', $content );


            if ( $title_has_exact === 1 || $content_has_exact === 1 ) {
                $title = preg_replace($exact_pattern, '<strong>${0}</strong>', $title );
                $content = preg_replace($exact_pattern, '<strong>${0}</strong>', $content );
            } else {
                $title = preg_replace($pattern, '<strong>${0}</strong>', $title );
                $content = preg_replace( $pattern, '<strong>${0}</strong>', $content );
            }


            return array(
                'title'   => $title,
                'content' => $content
            );

        }

        /*
         * Sort array by its values length
         */
        private function sort_by_length( $a, $b ) {
            return strlen( $b ) - strlen( $a );
        }

        /*
         * Query product taxonomies
         */
        private function get_taxonomies( $taxonomy ) {

            global $wpdb;

            $result_array = array();
            $search_array = array();
            $excludes = '';
            $search_query = '';

            foreach ( $this->data['search_terms'] as $search_term ) {
                $like = '%' . $wpdb->esc_like($search_term) . '%';
                $search_array[] = $wpdb->prepare('( name LIKE %s )', $like);
            }

            $search_query .= sprintf( ' AND ( %s )', implode( ' OR ', $search_array ) );


            $sql = "
			SELECT
				distinct($wpdb->terms.name),
				$wpdb->terms.term_id,
				$wpdb->term_taxonomy.taxonomy,
				$wpdb->term_taxonomy.count
			FROM
				$wpdb->terms
				, $wpdb->term_taxonomy
			WHERE 1 = 1
				{$search_query}
				AND $wpdb->term_taxonomy.taxonomy = '{$taxonomy}'
				AND $wpdb->term_taxonomy.term_id = $wpdb->terms.term_id
			$excludes
			LIMIT 0, 10";

            $search_results = $wpdb->get_results( $sql );

            if ( ! empty( $search_results ) && !is_wp_error( $search_results ) ) {

                foreach ( $search_results as $result ) {

                    if ( ! $result->count > 0 ) {
                        continue;
                    }

                    if ( function_exists( 'wpml_object_id_filter' )  ) {
                        $term = wpml_object_id_filter( $result->term_id, $result->taxonomy );
                        if ( $term != $result->term_id ) {
                            continue;
                        }
                    } else {
                        $term = get_term( $result->term_id, $result->taxonomy );
                    }

                    if ( $term != null && !is_wp_error( $term ) ) {
                        $term_link = get_term_link( $term );
                    } else {
                        continue;
                    }

                    $new_result = array(
                        'name'     => $result->name,
                        'count'    => $result->count,
                        'link'     => $term_link
                    );

                    $result_array[] = $new_result;

                }

            }

            return $result_array;

        }

    }


endif;

AWS_Search::factory();

function aws_search( $keyword = '' ) {
    return AWS_Search::factory()->search( $keyword );
}