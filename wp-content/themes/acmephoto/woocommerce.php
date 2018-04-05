<?php
/**
 * The template for woocommerce pages.
 *
 * @package Acme Themes
 * @subpackage AcmePhoto
 */
get_header(); ?>
<div id="primary" class="content-area">
    <main id="main" class="site-main">
        <?php if ( have_posts() ) :
            woocommerce_content();
        endif;
        ?>
    </main><!-- #main -->
</div><!-- #primary -->
<?php
get_sidebar( 'left' );
get_sidebar();
get_footer();