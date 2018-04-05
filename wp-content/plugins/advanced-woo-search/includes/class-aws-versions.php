<?php
/**
 * Versions capability
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

if ( ! class_exists( 'AWS_Versions' ) ) :

    /**
     * Class for plugin search
     */
    class AWS_Versions {

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

            $current_version = get_option( 'aws_plugin_ver' );
            $reindex_version = get_option( 'aws_reindex_version' );

            if ( ! ( $reindex_version ) ) {
                add_action( 'admin_notices', array( $this, 'admin_notice_no_index' ) );
            }

            if ( $reindex_version && version_compare( $reindex_version, '1.23', '<' ) ) {
                add_action( 'admin_notices', array( $this, 'admin_notice_reindex' ) );
            }

            if ( $current_version ) {

                if ( version_compare( $current_version, '1.16', '<' ) ) {

                    $settings = get_option( 'aws_settings' );

                    if ( $settings ) {
                        if ( ! isset( $settings['outofstock'] ) ) {
                            $settings['outofstock'] = 'false';
                            update_option( 'aws_settings', $settings );
                        }
                    }

                }

                if ( version_compare( $current_version, '1.17', '<' ) ) {

                    $settings = get_option( 'aws_settings' );

                    if ( $settings ) {
                        if ( ! isset( $settings['use_analytics'] ) ) {
                            $settings['use_analytics'] = 'false';
                            update_option( 'aws_settings', $settings );
                        }
                    }

                }

                if ( version_compare( $current_version, '1.21', '<' ) ) {

                    $settings = get_option( 'aws_settings' );

                    if ( $settings ) {
                        if ( ! isset( $settings['show_page'] ) ) {
                            $settings['show_page'] = 'false';
                            update_option( 'aws_settings', $settings );
                        }
                    }

                }

                if ( version_compare( $current_version, '1.23', '<' ) ) {

                    $settings = get_option( 'aws_settings' );

                    if ( $settings ) {
                        if ( ! isset( $settings['stopwords'] ) ) {
                            $settings['stopwords'] = 'a, about, above, across, after, afterwards, again, against, all, almost, alone, along, already, also, although, always, am, among, amongst, amoungst, amount, an, and, another, any, anyhow, anyone, anything, anyway, anywhere, are, around, as, at, back, be, became, because, become, becomes, becoming, been, before, beforehand, behind, being, below, beside, besides, between, beyond, bill, both, bottom, but, by, call, can, cannot, cant, co, con, could, couldnt, cry, de, describe, detail, do, done, down, due, during, each, eg, eight, either, eleven, else, elsewhere, empty, enough, etc, even, ever, every, everyone, everything, everywhere, except, few, fifteen, fify, fill, find, fire, first, five, for, former, formerly, forty, found, four, from, front, full, further, get, give, go, had, has, hasnt, have, he, hence, her, here, hereafter, hereby, herein, hereupon, hers, herself, him, himself, his, how, however, hundred, ie, if, in, inc, indeed, interest, into, is, it, its, itself, keep, last, latter, latterly, least, less, ltd, made, many, may, me, meanwhile, might, mill, mine, more, moreover, most, mostly, move, much, must, my, myself, name, namely, neither, never, nevertheless, next, nine, no, nobody, none, noone, nor, not, nothing, now, nowhere, of, off, often, on, once, one, only, onto, or, other, others, otherwise, our, ours, ourselves, out, over, own, part, per, perhaps, please, put, rather, re, same, see, seem, seemed, seeming, seems, serious, several, she, should, show, side, since, sincere, six, sixty, so, some, somehow, someone, something, sometime, sometimes, somewhere, still, such, system, take, ten, than, that, the, their, them, themselves, then, thence, there, thereafter, thereby, therefore, therein, thereupon, these, they, thickv, thin, third, this, those, though, three, through, throughout, thru, thus, to, together, too, top, toward, towards, twelve, twenty, two, un, under, until, up, upon, us, very, via, was, we, well, were, what, whatever, when, whence, whenever, where, whereafter, whereas, whereby, wherein, whereupon, wherever, whether, which, while, whither, who, whoever, whole, whom, whose, why, will, with, within, without, would, yet, you, your, yours, yourself, yourselves';
                            update_option( 'aws_settings', $settings );
                        }
                    }

                }
                
                if ( version_compare( $current_version, '1.27', '<' ) ) {

                    $settings = get_option( 'aws_settings' );

                    if ( $settings ) {
                        if ( ! isset( $settings['show_stock'] ) ) {
                            $settings['show_stock'] = 'false';
                            update_option( 'aws_settings', $settings );
                        }
                    }

                }

            }

            update_option( 'aws_plugin_ver', AWS_VERSION );

        }

        /**
         * Admin notice for table first reindex
         */
        public function admin_notice_no_index() { ?>
            <div class="updated notice is-dismissible">
                <p><?php printf( esc_html__( 'Advanced Woo Search: Please go to plugin setting page and start the indexing of your products. %s', 'aws' ), '<a class="button button-secondary" href="'.esc_url( admin_url('admin.php?page=aws-options') ).'">'.esc_html__( 'Reindex Table', 'aws' ).'</a>'  ); ?></p>
            </div>
        <?php }

        /**
         * Admin notice for table reindex
         */
        public function admin_notice_reindex() { ?>
            <div class="updated notice is-dismissible">
                <p><?php printf( esc_html__( 'Advanced Woo Search: Please reindex table for proper work of new plugin features. %s', 'aws' ), '<a class="button button-secondary" href="'.esc_url( admin_url('admin.php?page=aws-options') ).'">'.esc_html__( 'Reindex Table', 'aws' ).'</a>'  ); ?></p>
            </div>
        <?php }

    }


endif;

add_action( 'admin_init', 'AWS_Versions::factory' );