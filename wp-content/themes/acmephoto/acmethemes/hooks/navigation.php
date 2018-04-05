<?php
/**
 * Post Navigation
 *
 * @since AcmePhoto 1.0.0
 *
 * @param null
 * @return void
 *
 */
if ( !function_exists('acmephoto_posts_navigation') ) :
    function acmephoto_posts_navigation() {
        global $acmephoto_customizer_all_values;

        if( 'default' == $acmephoto_customizer_all_values['acmephoto-pagination-option'] ){
            the_posts_navigation();
        }
        else{
            $page_number = get_query_var('paged');
            if( $page_number == 0 ){
                $output_page = 2;
            }
            else{
                $output_page = $page_number + 1;
            }
            echo "<div class='clearfix'></div> <div class='show-more' data-number='$output_page'><i class='fa fa-refresh'></i>".__('Show More','acmephoto')."</div><div id='acmephoto-temp-post'></div>";
        }
    }
endif;
add_action( 'acmephoto_action_navigation', 'acmephoto_posts_navigation', 10 );