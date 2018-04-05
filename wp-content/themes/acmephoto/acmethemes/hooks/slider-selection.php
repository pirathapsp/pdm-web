<?php
/**
 * Display featured slider
 *
 * @since AcmePhoto 1.0.0
 *
 * @param null
 * @return void
 *
 */
if ( !function_exists('acmephoto_default_slider') ) :
    function acmephoto_default_slider(){

        $bg_image_style = 'height:60vh;';
        $bg_image_style .= 'background-image:url(' . esc_url( get_template_directory_uri()."/assets/img/acme-photo-feature.jpg" ) . ');background-repeat:no-repeat;background-size:cover;background-attachment:scroll;background-position: center;';


        ?>
        <div class="item" style="<?php echo $bg_image_style; ?>">
            <div class="slider-desc">
                <div class="slider-details">
                    <div class="slide-title">
                        <?php _e('Life Is Beautiful','acmephoto'); ?>
                    </div>
                    <?php
                    echo '<div class="slide-caption">'.__("Don't miss anything",'acmephoto').'</div>';
                    ?>
                </div>
            </div>
        </div>
        <?php
    }
endif;

/**
 * Featured Slider display
 *
 * @since AcmePhoto 1.0.0
 *
 * @param null
 * @return void
 */

if ( ! function_exists( 'acmephoto_display_feature_slider' ) ) :

    function acmephoto_display_feature_slider(){
        global $acmephoto_customizer_all_values;
        $acmephoto_feature_page = $acmephoto_customizer_all_values['acmephoto-feature-page'];
        $acmephoto_feature_height = $acmephoto_customizer_all_values['acmephoto-feature-height'];
        $acmephoto_feature_button_option = $acmephoto_customizer_all_values['acmephoto-feature-button-option'];
        if ( 0 != $acmephoto_feature_page ) {
            $acmephoto_child_page_args = array(
                'post_parent'         => $acmephoto_feature_page,
                'posts_per_page'      => 3,
                'post_type'           => 'page',
                'no_found_rows'       => true,
                'post_status'         => 'publish'
            );
            $slider_query = new WP_Query( $acmephoto_child_page_args );
            if ( !$slider_query->have_posts() ){
                $acmephoto_child_page_args = array(
                    'page_id'         => $acmephoto_feature_page,
                    'posts_per_page'      => 1,
                    'post_type'           => 'page',
                    'no_found_rows'       => true,
                    'post_status'         => 'publish'
                );
                $slider_query = new WP_Query( $acmephoto_child_page_args );
            }
            $bg_image_style = 'height:'.$acmephoto_feature_height."vh;";
            if ($slider_query->have_posts()):
                while ($slider_query->have_posts()): $slider_query->the_post();
                    if (has_post_thumbnail()) {
                        $image_url = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
                    } else {
                        $image_url[0] = get_template_directory_uri() . '/assets/img/no-image-avalable.png';
                    }
                    $bg_image_style .= 'background-image:url(' . esc_url( $image_url[0] ) . ');background-repeat:no-repeat;background-size:cover;background-attachment:scroll;background-position: center;';
                    ?>
                    <div class="item" style="<?php echo $bg_image_style; ?>">
                        <div class="slider-desc">
                            <div class="slider-details">
                                <div class="slide-title">
                                    <?php the_title(); ?>
                                </div>
                                <div class="slide-caption">
                                    <?php echo esc_html( get_the_excerpt() ); ?>
                                </div>
                                <?php
                                if ( 'read-more' == $acmephoto_feature_button_option ){
                                    ?>
                                    <a href="<?php the_permalink()?>" class="read-more">
                                        <?php _e( 'Read More', 'acmephoto' );?>
                                    </a>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <?php
                endwhile;
                wp_reset_postdata();
            endif;
        }
        else{
            acmephoto_default_slider();
        }
    }
endif;

/**
 * Display featured slider
 *
 * @since AcmePhoto 1.0.0
 *
 * @param null
 * @return void
 *
 */
if ( !function_exists('acmephoto_feature_slider') ) :
    function acmephoto_feature_slider() {
        global $acmephoto_customizer_all_values;
        $acmephoto_feature_button_option = $acmephoto_customizer_all_values['acmephoto-feature-button-option'];
        $acmephoto_feature_enable_social = $acmephoto_customizer_all_values['acmephoto-feature-enable-social'];

        /*slider start*/
        ?>
        <div class="slider-section">
            <?php
            if( 'search' == $acmephoto_feature_button_option ){
                ?>
                <div class="banner-search">
                    <?php get_search_form()?>
                </div>
                <?php
            }
            if( 1 == $acmephoto_feature_enable_social ) {
                ?>
                <div class="banner-social">
                    <?php
                    do_action('acmephoto_action_social_links');
                    ?>
                </div>
                <?php
            }
            ?>
            <div
                class="feature-slider cycle-slideshow"
                data-cycle-fx = 'tileBlind'
                data-cycle-log="false"
                data-cycle-speed = 1000
                data-cycle-pause-on-hover = true
                data-cycle-timeout = 2000
                data-cycle-slides = '.item'
            >
                <!-- prev/next links -->
                <div class="owl-prev cycle-prev"><i class="fa fa-angle-left"></i></div>
                <div class="owl-next cycle-next"><i class="fa fa-angle-right"></i></div>
                <?php acmephoto_display_feature_slider(); ?>
            </div>
        </div><!-- .slider-section -->
        <?php
    }
endif;
add_action( 'acmephoto_action_feature_slider', 'acmephoto_feature_slider', 0 );