<?php
/**
 * Dynamic css
 *
 * @since AcmePhoto 1.0.0
 *
 * @param null
 * @return null
 *
 */
if ( ! function_exists( 'acmephoto_dynamic_css' ) ) :

    function acmephoto_dynamic_css() {

        global $acmephoto_customizer_all_values;
        /*Color options */
        $acmephoto_primary_color = esc_attr( $acmephoto_customizer_all_values['acmephoto-primary-color'] );
        $custom_css = '';
        /*background*/
        $custom_css .= "
            mark,
            .comment-form .form-submit input,
            #calendar_wrap #wp-calendar #today,
            #calendar_wrap #wp-calendar #today a,
            .wpcf7-form input.wpcf7-submit:hover,
            .breadcrumb,
            .masonry-start .read-more .read-more-btn,
            .show-more,
             .slicknav_btn,
             .widget-title::after,
             .banner-search .search-block #searchsubmit,
             .at-sticky{
                background: {$acmephoto_primary_color};
            }";
        /*color*/
        $custom_css .= "
            .slider-section .cat-links a,
            a:hover,
            .header-wrapper .menu li a:hover,
            .screen-reader-text:focus,
            .socials a:hover,
            .site-title a:hover,
            .widget_search input#s,
            .slider-feature-wrap a:hover,
            .featured-desc .above-entry-meta span:hover,
            .posted-on a:hover,
            .cat-links a:hover,
            .comments-link a:hover,
            .edit-link a:hover,
            .tags-links a:hover,
            .byline a:hover,
            .nav-links a:hover,
            #acmephoto-breadcrumbs a:hover,
            .wpcf7-form input.wpcf7-submit,
            .widget li a:hover,
            .main-navigation ul > li.current-menu-item > a,
            .main-navigation ul > li.current-menu-parent > a,
            .main-navigation ul > li.current_page_parent > a,
            .main-navigation ul > li.current_page_ancestor > a{
                color: {$acmephoto_primary_color};
            }";

        /*border*/
         $custom_css .= "
         .at-sticky::before {
         	border-top: 18px solid {$acmephoto_primary_color};
         }
            .page-header .page-title:after,
            .single .entry-header.border .entry-title:after{
                background: {$acmephoto_primary_color};
                content: '';
                height: 18px;
                position: absolute;
                top: 14px;
                width: 3px;
                left:0;
            }
            .page-header .page-title:before,
            .single .entry-header.border .entry-title:before{
                border-bottom: 7px solid {$acmephoto_primary_color};
            }
            .wpcf7-form input.wpcf7-submit:hover,
            .banner-search .search-block{
                border: 2px solid {$acmephoto_primary_color};
            }
            .breadcrumb::after {
                border-left: 5px solid {$acmephoto_primary_color};
            }
            .tagcloud a{
                border: 1px solid {$acmephoto_primary_color};
            }
         ";
        /*media width*/
        $custom_css .= "
            @media screen and (max-width:992px){
                .slicknav_nav li:hover > a,
                .slicknav_nav li.current-menu-ancestor a,
                .slicknav_nav li.current-menu-item  > a,
                .slicknav_nav li.current_page_item a,
                .slicknav_nav li.current_page_item .slicknav_item span,
                .slicknav_nav li .slicknav_item:hover a{
                    color: {$acmephoto_primary_color};
                }
            }";
        /*custom css*/
        $acmephoto_custom_css = wp_filter_nohtml_kses ( $acmephoto_customizer_all_values['acmephoto-custom-css'] );
        if ( ! empty( $acmephoto_custom_css ) ) {
            $custom_css .= $acmephoto_custom_css;
        }
        wp_add_inline_style( 'acmephoto-style', $custom_css );
    }
endif;
add_action( 'wp_enqueue_scripts', 'acmephoto_dynamic_css', 99 );