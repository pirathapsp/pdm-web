<?php
/**
 * Content and content wrapper end
 *
 * @since AcmePhoto 1.0.0
 *
 * @param null
 * @return null
 *
 */
if ( ! function_exists( 'acmephoto_after_content' ) ) :

    function acmephoto_after_content() {
      ?>
        </div><!-- #content -->
        </div><!-- content-wrapper-->
    <?php
    }
endif;
add_action( 'acmephoto_action_after_content', 'acmephoto_after_content', 10 );

/**
 * Footer content
 *
 * @since AcmePhoto 1.0.0
 *
 * @param null
 * @return null
 *
 */
if ( ! function_exists( 'acmephoto_footer' ) ) :

    function acmephoto_footer() {

        global $acmephoto_customizer_all_values;
        ?>
        <div class="clearfix"></div>
        <footer id="colophon" class="site-footer" role="contentinfo">
            <div class=" footer-wrapper">
            <?php
            if(
                is_active_sidebar( 'footer-top-col-one' ) ||
                is_active_sidebar( 'footer-top-col-two' ) ||
                is_active_sidebar( 'footer-top-col-three' ) 
            ){
                ?>
                <div class="top-bottom clearfix">
                    <?php
                    $footer_top_col = 'acme-col-3';
                    ?>
                    <div id="footer-top" class="wrapper">
                        <div class="footer-columns">
                            <?php if( is_active_sidebar( 'footer-top-col-one' ) ) : ?>
                                <div class="footer-sidebar <?php echo esc_attr( $footer_top_col );?>">
                                    <?php dynamic_sidebar( 'footer-top-col-one' ); ?>
                                </div>
                            <?php endif;
                            if( is_active_sidebar( 'footer-top-col-two' ) ) : ?>
                                <div class="footer-sidebar <?php echo esc_attr( $footer_top_col );?>">
                                    <?php dynamic_sidebar( 'footer-top-col-two' ); ?>
                                </div>
                            <?php endif;
                            if( is_active_sidebar( 'footer-top-col-three' ) ) : ?>
                                <div class="footer-sidebar <?php echo esc_attr( $footer_top_col );?>">
                                    <?php dynamic_sidebar( 'footer-top-col-three' ); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div><!-- #foter-top -->
                </div><!-- top-bottom-->
                <div class="clearfix"></div>
                <?php
            }
            ?>
                <div class="footer-copyright border text-center">
                    <div class="wrapper">
                        <?php
                        if(  1 == $acmephoto_customizer_all_values['acmephoto-enable-footer-social'] ){
                            do_action( 'acmephoto_action_social_links', 1 );
                        }
                        ?>
                        <div class="clearfix"></div>
                        <?php
                        if( isset( $acmephoto_customizer_all_values['acmephoto-footer-copyright'] ) ): ?>
                            <p><?php echo wp_kses_post( $acmephoto_customizer_all_values['acmephoto-footer-copyright'] ); ?></p>
                        <?php endif; ?>
                        <div class="site-info">
                            <a href="<?php echo esc_url( __( 'https://wordpress.org/', 'acmephoto' ) ); ?>"><?php printf( esc_html__( 'Proudly powered by %s', 'acmephoto' ), 'WordPress' ); ?></a>
                            <span class="sep"> | </span>
                            <?php printf( esc_html__( 'Theme: %1$s by %2$s', 'acmephoto' ), 'AcmePhoto', '<a href="https://www.acmethemes.com/" rel="designer">Acme Themes</a>' ); ?>
                        </div><!-- .site-info -->
                    </div>
                </div>
                <div class="clearfix"></div>
            </div><!-- footer-wrapper-->
        </footer><!-- #colophon -->
    </div><!--page end-->
    <?php
    }
endif;
add_action( 'acmephoto_action_footer', 'acmephoto_footer', 10 );