<?php
/**
 * Display Social Links
 *
 * @since AcmePhoto 1.0.0
 *
 * @param null
 * @return void
 *
 */
if ( !function_exists('acmephoto_social_links') ) :

    function acmephoto_social_links( $show_title = 0 ) {
        global $acmephoto_customizer_all_values;
        ?>
        <div class="socials">
            <?php
            if ( !empty( $acmephoto_customizer_all_values['acmephoto-facebook-url'] ) ) { ?>
                <a href="<?php echo esc_url( $acmephoto_customizer_all_values['acmephoto-facebook-url'] ); ?>" class="facebook" data-title="Facebook" target="_blank">
                    <span class="font-icon-social-facebook">
                        <i class="fa fa-facebook"></i>
                        <?php
                        if( 1 == $show_title ){
                          ?>
                            <span><?php _e( 'Facebook','acmephoto' ); ?></span>
                            <?php
                        }
                        ?>
                    </span>
                </a>
            <?php }
            if ( !empty( $acmephoto_customizer_all_values['acmephoto-twitter-url'] ) ) { ?>
                <a href="<?php echo esc_url( $acmephoto_customizer_all_values['acmephoto-twitter-url'] ); ?>" class="twitter" data-title="Twitter" target="_blank">
                    <span class="font-icon-social-twitter">
                        <i class="fa fa-twitter"></i>
                        <?php
                        if( 1 == $show_title ){
                            ?>
                            <span><?php _e( 'Twitter','acmephoto' ); ?></span>
                            <?php
                        }
                        ?>
                    </span>
                </a>
            <?php }
            if ( !empty( $acmephoto_customizer_all_values['acmephoto-instagram-url'] ) ) { ?>
                <a href="<?php echo esc_url( $acmephoto_customizer_all_values['acmephoto-instagram-url'] ); ?>" class="instagram" data-title="instagram" target="_blank">
                    <span class="font-icon-social-instagram">
                        <i class="fa fa-instagram"></i>
                        <?php
                        if( 1 == $show_title ){
                            ?>
                            <span><?php _e( 'Instagram','acmephoto' ); ?></span>
                            <?php
                        }
                        ?>
                    </span>
                </a>
            <?php } ?>
        </div>
        <?php
    }
endif;
add_filter( 'acmephoto_action_social_links', 'acmephoto_social_links', 10 ,1 );