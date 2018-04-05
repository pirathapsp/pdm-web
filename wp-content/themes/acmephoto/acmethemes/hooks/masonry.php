<?php
/**
 * Add div for masonry
 *
 * @since AcmePhoto 1.0.0
 *
 * @param null
 * @return null
 *
 */
if ( !function_exists('acmephoto_masonry_start') ) :
    function acmephoto_masonry_start( ) {
        ?>
        <div class="masonry-start"><div id="masonry-loop">
        <?php
    }
endif;
add_action('acmephoto_action_masonry_start', 'acmephoto_masonry_start');

/**
 * End div for masonry
 *
 * @since AcmePhoto 1.0.0
 *
 * @param null
 * @return null
 *
 */
if ( !function_exists('acmephoto_masonry_end') ) :
    function acmephoto_masonry_end( ) {
        ?>
        </div><!--#masonry-loop-->
        </div><!--masonry-start-->
        <?php
    }
endif;
add_action('acmephoto_action_masonry_end', 'acmephoto_masonry_end');