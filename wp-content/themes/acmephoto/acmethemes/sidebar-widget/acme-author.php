<?php
/**
 * Custom author
 *
 * @package Acme Themes
 * @subpackage AcmePhoto
 */
if ( ! class_exists( 'Acmephoto_author_widget' ) ) :
    /**
     * Class for adding author widget
     * A new way to add author
     * @package Acme Themes
     * @subpackage AcmePhoto
     * @since 1.0.0
     */
    class Acmephoto_author_widget extends WP_Widget {
        /*defaults values for fields*/
        private $defaults = array(
            'acmephoto_author_title' => '',
            'acmephoto_author_image' => '',
            'acmephoto_author_link'  => '',
            'acmephoto_author_new_window' => 1,
            'acmephoto_author_short_disc'  => '',
        );
        
        function __construct() {
            parent::__construct(
            /*Base ID of your widget*/
                'acmephoto_author',
                /*Widget name will appear in UI*/
                __('AT Author', 'acmephoto'),
                /*Widget description*/
                array( 'description' => __( 'Add author with different options.', 'acmephoto' ), )
            );
        }

        /*Widget Backend*/
        public function form( $instance ) {
            /*merging arrays*/
            $instance = wp_parse_args( (array) $instance, $this->defaults);
            $acmephoto_author_title  = esc_attr( $instance['acmephoto_author_title'] );
            $acmephoto_author_image  = esc_url( $instance['acmephoto_author_image'] );
            $acmephoto_author_link   = esc_url( $instance['acmephoto_author_link'] );
            $acmephoto_author_new_window = esc_attr( $instance['acmephoto_author_new_window'] );
            $acmephoto_author_short_disc = esc_attr( $instance['acmephoto_author_short_disc'] );
            ?>
            <p class="description">
                <?php
                _e('Note: Use square image. Recommended image size : 250 x 250', 'acmephoto' );
                ?>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id( 'acmephoto_author_title' ); ?>"><?php _e( 'Title:', 'acmephoto' ); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id( 'acmephoto_author_title' ); ?>" name="<?php echo $this->get_field_name( 'acmephoto_author_title' ); ?>" type="text" value="<?php echo esc_attr( $acmephoto_author_title ); ?>" />
            </p>
            <h4><?php _e( 'Author Image', 'acmephoto' ); ?></h4>
            <p>
                <label for="<?php echo $this->get_field_id('acmephoto_author_image'); ?>">
                    <?php _e( 'Select Author Image', 'acmephoto' ); ?>
                </label>
                <?php
                $acmephoto_display_none = '';
                if ( empty( $acmephoto_author_image ) ){
                    $acmephoto_display_none = ' style="display:none;" ';
                }
                ?>
                <span class="img-preview-wrap" <?php echo esc_attr( $acmephoto_display_none ); ?>>
                    <img class="widefat" src="<?php echo esc_url( $acmephoto_author_image ); ?>" alt="<?php _e( 'Image preview', 'acmephoto' ); ?>"  />
                </span><!-- .ad-preview-wrap -->
                <input type="text" class="widefat" name="<?php echo $this->get_field_name('acmephoto_author_image'); ?>" id="<?php echo $this->get_field_id('acmephoto_author_image'); ?>" value="<?php echo esc_url( $acmephoto_author_image ); ?>" />
                <input type="button" value="<?php _e( 'Upload Image', 'acmephoto' ); ?>" class="button media-image-upload" data-title="<?php _e( 'Select Author Image','acmephoto'); ?>" data-button="<?php _e( 'Select Author Image','acmephoto'); ?>"/>
                <input type="button" value="<?php _e( 'Remove Image', 'acmephoto' ); ?>" class="button media-image-remove" />
            </p>
            <p>
                <label for="<?php echo $this->get_field_id( 'acmephoto_author_short_disc' ); ?>"><?php _e( 'Author Short Disc:', 'acmephoto' ); ?></label>
                <textarea class="widefat" id="<?php echo $this->get_field_id( 'acmephoto_author_short_disc' ); ?>" name="<?php echo $this->get_field_name( 'acmephoto_author_short_disc' ); ?>"><?php echo esc_attr( $acmephoto_author_short_disc ); ?></textarea>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id( 'acmephoto_author_link' ); ?>"><?php _e( 'Link URL:', 'acmephoto' ); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id( 'acmephoto_author_link' ); ?>" name="<?php echo $this->get_field_name( 'acmephoto_author_link' ); ?>" type="text" value="<?php echo esc_attr( $acmephoto_author_link ); ?>" />
            </p>
            <p>
                <input id="<?php echo $this->get_field_id( 'acmephoto_author_new_window' ); ?>" name="<?php echo $this->get_field_name( 'acmephoto_author_new_window' ); ?>" type="checkbox" <?php checked( 1 == $acmephoto_author_new_window ? $instance['acmephoto_author_new_window'] : 0); ?> />
                <label for="<?php echo $this->get_field_id( 'acmephoto_author_new_window' ); ?>"><?php _e( 'Open in new window', 'acmephoto' ); ?></label>
            </p>
            <hr />
            <?php
        }

        /**
         * Function to Updating widget replacing old instances with new
         *
         * @access public
         * @since 1.0
         *
         * @param array $new_instance new arrays value
         * @param array $old_instance old arrays value
         * @return array
         *
         */
        function update( $new_instance, $old_instance ) {
            $instance = $old_instance;
            $instance['acmephoto_author_title'] = ( isset( $new_instance['acmephoto_author_title'] ) ) ?  sanitize_text_field( $new_instance['acmephoto_author_title'] ): '';
            $instance['acmephoto_author_image'] = ( isset( $new_instance['acmephoto_author_image'] ) ) ?  esc_url( $new_instance['acmephoto_author_image'] ): '';
            $instance['acmephoto_author_link'] = ( isset( $new_instance['acmephoto_author_link'] ) ) ?  esc_url( $new_instance['acmephoto_author_link'] ): '';
            $instance['acmephoto_author_short_disc'] = ( isset( $new_instance['acmephoto_author_short_disc'] ) ) ?  wp_kses_post( $new_instance['acmephoto_author_short_disc'] ): '';
            $instance['acmephoto_author_new_window'] = isset($new_instance['acmephoto_author_new_window'])? 1 : 0;

            return $instance;
        }
        /**
         * Function to Creating widget front-end. This is where the action happens
         *
         * @access public
         * @since 1.0
         *
         * @param array $args widget setting
         * @param array $instance saved values
         * @return void
         *
         */
        function widget( $args, $instance ) {
            $instance = wp_parse_args( (array) $instance, $this->defaults);
            $acmephoto_author_title      = apply_filters( 'widget_title', $instance['acmephoto_author_title'], $instance, $this->id_base );
            $acmephoto_author_image      = esc_url( $instance['acmephoto_author_image'] );
            $acmephoto_author_link       = esc_url( $instance['acmephoto_author_link'] );
            $acmephoto_author_new_window = esc_attr( $instance['acmephoto_author_new_window'] );
            $acmephoto_author_short_disc = wp_kses_post( $instance['acmephoto_author_short_disc'] );

            echo $args['before_widget'];

            if ( !empty($acmephoto_author_title) ) {
                echo $args['before_title'] . $acmephoto_author_title . $args['after_title'];
            }
            $ad_content_image = '';
            if ( ! empty( $acmephoto_author_image ) ) {
                /*creating add*/
                $img_html = '<img src="' . $acmephoto_author_image . '"/>';
                $link_open = '';
                $link_close = '';
                if ( ! empty( $acmephoto_author_link ) ) {
                    $target_text = ( 1 == $acmephoto_author_new_window ) ? ' target="_blank" ' : '';
                    $link_open = '<a href="' .  $acmephoto_author_link . '" ' . $target_text . '>';
                    $link_close = '</a>';
                }
                $ad_content_image = $link_open . $img_html .  $link_close.$acmephoto_author_short_disc;
            }
            if ( !empty( $ad_content_image ) ) {
                echo '<div class="acmephoto-author-widget">';
                echo $ad_content_image;
                echo '</div>';
            }
            echo $args['after_widget'];
        }
    }
endif;

if ( ! function_exists( 'acmephoto_author_widget' ) ) :
    /**
     * Function to Register and load the widget
     *
     * @since 1.0
     *
     * @param null
     * @return void
     *
     */
    function acmephoto_author_widget() {
        register_widget( 'Acmephoto_author_widget' );
    }
endif;
add_action( 'widgets_init', 'acmephoto_author_widget' );