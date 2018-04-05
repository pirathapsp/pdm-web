<?php
/**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
if ( ! function_exists( 'acmephoto_widget_init' ) ) :

    function acmephoto_widget_init(){
        register_sidebar(array(
            'name' => __('Main Sidebar Area', 'acmephoto'),
            'id'   => 'acmephoto-sidebar',
            'description' =>  __('Displays items on sidebar', 'acmephoto'),
            'before_widget' => '<aside id="%1$s" class="widget %2$s">',
            'after_widget' => '</aside>',
            'before_title' => '<h3 class="widget-title"><span>',
            'after_title' => '</span></h3>',
        ));
        register_sidebar(array(
            'name' => __('Footer Column One', 'acmephoto'),
            'id' => 'footer-top-col-one',
            'description' => __('Displays items on footer section.', 'acmephoto'),
            'before_widget' => '<aside id="%1$s" class="widget %2$s">',
            'after_widget' => '</aside>',
            'before_title' => '<h3 class="widget-title"><span>',
            'after_title' => '</span></h3>',
        ));
        register_sidebar(array(
            'name' => __('Footer Column Two', 'acmephoto'),
            'id' => 'footer-top-col-two',
            'description' => __('Displays items on footer section.', 'acmephoto'),
            'before_widget' => '<aside id="%1$s" class="widget %2$s">',
            'after_widget' => '</aside>',
            'before_title' => '<h3 class="widget-title"><span>',
            'after_title' => '</span></h3>',
        ));
        register_sidebar(array(
            'name' => __('Footer Column Three', 'acmephoto'),
            'id' => 'footer-top-col-three',
            'description' => __('Displays items on footer section.', 'acmephoto'),
            'before_widget' => '<aside id="%1$s" class="widget %2$s">',
            'after_widget' => '</aside>',
            'before_title' => '<h3 class="widget-title"><span>',
            'after_title' => '</span></h3>',
        ));
    }
endif;
add_action('widgets_init', 'acmephoto_widget_init');