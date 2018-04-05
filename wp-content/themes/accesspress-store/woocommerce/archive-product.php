<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @author      WooThemes
 * @package     WooCommerce/Templates
 * @version     3.3.0
 */
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

get_header( 'shop' );


$breadcrumb = intval( get_theme_mod( 'breadcrumb_options', '1' ) );
$archive_bread = esc_url( get_theme_mod( 'breadcrumb_archive_image' ) );
if ( $archive_bread ) {
    $bread_archive = $archive_bread;
} else {
    $bread_archive = esc_url( get_template_directory_uri() . '/images/about-us-bg.jpg' );
}

if ( $breadcrumb == '1' ) :
    ?>
    <header id="title_bread_wrap" class="entry-header" style="background:url('<?php echo $bread_archive; ?>') no-repeat center; background-size: cover;">
        <div class="ak-container">     
            <?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
                <h1 class="entry-title ak-container"><?php woocommerce_page_title(); ?></h1>
            <?php
            endif;
            /**
             * Hook: woocommerce_before_main_content.
             *
             * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
             * @hooked woocommerce_breadcrumb - 20
             * @hooked WC_Structured_Data::generate_website_data() - 30
             */
            do_action( 'woocommerce_before_main_content' );

            /**
             * Hook: woocommerce_archive_description.
             *
             * @hooked woocommerce_taxonomy_archive_description - 10
             * @hooked woocommerce_product_archive_description - 10
             */
            do_action( 'woocommerce_archive_description' );
            ?>
        </div>
    </header>
<?php endif; ?>

<div class="inner">
    <div class="ak-container left-sidebar"> 
        <div id="primary" class="content-area clearfix">
            <div class="content-inner">
                <?php
                if ( have_posts() ) {

                    /**
                     * Hook: woocommerce_before_shop_loop.
                     *
                     * @hooked wc_print_notices - 10
                     * @hooked woocommerce_result_count - 20
                     * @hooked woocommerce_catalog_ordering - 30
                     */
                    do_action( 'woocommerce_before_shop_loop' );
                    ?>
                    <div class="clearfix"></div>
                    <div class="wc-products">
                        <?php
                        woocommerce_product_loop_start();

                        if ( wc_get_loop_prop( 'total' ) ) {
                            while ( have_posts() ) {
                                the_post();

                                /**
                                 * Hook: woocommerce_shop_loop.
                                 *
                                 * @hooked WC_Structured_Data::generate_product_data() - 10
                                 */
                                do_action( 'woocommerce_shop_loop' );

                                wc_get_template_part( 'content', 'product' );
                            }
                        }

                        woocommerce_product_loop_end();
                        ?>
                    </div>
                    <?php
                    /**
                     * Hook: woocommerce_after_shop_loop.
                     *
                     * @hooked woocommerce_pagination - 10
                     */
                    do_action( 'woocommerce_after_shop_loop' );
                } else {
                    /**
                     * Hook: woocommerce_no_products_found.
                     *
                     * @hooked wc_no_products_found - 10
                     */
                    do_action( 'woocommerce_no_products_found' );
                }

                /**
                 * Hook: woocommerce_after_main_content.
                 *
                 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
                 */
                do_action( 'woocommerce_after_main_content' );
                ?>
            </div>
        </div>
        <div id="secondary" class="widget-area secondary-left sidebar">
            <?php
            /**
             * Hook: woocommerce_sidebar.
             *
             * @hooked woocommerce_get_sidebar - 10
             */
            do_action( 'woocommerce_sidebar' );
            ?>
        </div>
    </div>
</div>
<?php
get_footer( 'shop' );
