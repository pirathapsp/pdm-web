<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'AWS_Order' ) ) :

    /**
     * Class for products sorting
     */
    class AWS_Order {

        /**
         * @var AWS_Order Array of products
         */
        private $products = array();

        /**
         * @var AWS_Order Order by value
         */
        private $order_by = null;

        /**
         * Constructor
         */
        public function __construct( $products, $order_by ) {

            $this->products = $products;
            $this->order_by = $order_by;

            $this->order();

        }

        /*
         * Sort products
         */
        private function order() {

            switch( $this->order_by ) {

                case 'price':

                    if ( isset( $this->products[0]['f_price'] ) ) {
                        usort( $this->products, array( $this, 'compare_price_asc' ) );
                    }

                    break;

                case 'price-desc':

                    if ( isset( $this->products[0]['f_price'] ) ) {
                        usort( $this->products, array( $this, 'compare_price_desc' ) );
                    }

                    break;

                case 'date':

                    if ( isset( $this->products[0]['post_data'] ) ) {
                        usort( $this->products, array( $this, 'compare_date' ) );
                    }

                    break;

                case 'rating':

                    if ( isset( $this->products[0]['f_rating'] ) ) {
                        usort( $this->products, array( $this, 'compare_rating' ) );
                    }

                    break;

                case 'popularity':

                    if ( isset( $this->products[0]['f_reviews'] ) ) {
                        usort( $this->products, array( $this, 'compare_reviews' ) );
                    }

                    break;

            }

        }

        /*
         * Compare price values asc
         */
        private function compare_price_asc( $a, $b ) {
            $a = intval( $a['f_price'] * 100 );
            $b = intval( $b['f_price'] * 100 );
            if ($a == $b) {
                return 0;
            }
            return ($a < $b) ? -1 : 1;
        }

        /*
         * Compare price values desc
         */
        private function compare_price_desc( $a, $b ) {
            $a = intval( $a['f_price'] * 100 );
            $b = intval( $b['f_price'] * 100 );
            if ($a == $b) {
                return 0;
            }
            return ($a < $b) ? 1 : -1;
        }

        /*
         * Compare date
         */
        private function compare_date( $a, $b ) {
            $a = strtotime( $a['post_data']->post_date );
            $b = strtotime( $b['post_data']->post_date );
            if ($a == $b) {
                return 0;
            }
            return ($a < $b) ? 1 : -1;
        }

        /*
         * Compare rating
         */
        private function compare_rating( $a, $b ) {
            $a = intval( $a['f_rating'] * 100 );
            $b = intval( $b['f_rating'] * 100 );
            if ($a == $b) {
                return 0;
            }
            return ($a < $b) ? 1 : -1;
        }

        /*
         * Compare rating
         */
        private function compare_reviews( $a, $b ) {
            $a = intval( $a['f_reviews'] * 100 );
            $b = intval( $b['f_reviews'] * 100 );
            if ($a == $b) {
                return 0;
            }
            return ($a < $b) ? 1 : -1;
        }

        /*
         * Return array of sorted products
         */
        public function result() {

            return $this->products;

        }

    }

endif;