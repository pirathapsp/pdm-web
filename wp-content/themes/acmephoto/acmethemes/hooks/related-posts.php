<?php
/**
 * Display related posts from same category
 *
 * @since AcmePhoto 1.0.0
 *
 * @param int $post_id
 * @return void
 *
 */
if ( !function_exists('acmephoto_related_post_below') ) :

    function acmephoto_related_post_below( $post_id ) {

        global $acmephoto_customizer_all_values;
	    if( 0 == $acmephoto_customizer_all_values['acmephoto-show-related'] ){
		    return;
	    }
	    $acmephoto_cat_post_args = array(
		    'post__not_in' => array($post_id),
		    'post_type' => 'post',
		    'posts_per_page'      => 3,
		    'post_status'         => 'publish',
		    'ignore_sticky_posts' => true
	    );
	    $acmephoto_related_post_display_from = $acmephoto_customizer_all_values['acmephoto-related-post-display-from'];

	    if( 'tag' == $acmephoto_related_post_display_from ){

		    $tags = get_post_meta( $post_id, 'related-posts', true );
		    if ( !$tags ) {
			    $tags = wp_get_post_tags( $post_id, array('fields'=>'ids' ) );
			    $acmephoto_cat_post_args['tag__in'] = $tags;
		    }
		    else {
			    $acmephoto_cat_post_args['tag_slug__in'] = explode(',', $tags);
		    }

	    }
	    else{

		    $cats = get_post_meta( $post_id, 'related-posts', true );
		    if ( !$cats ) {
			    $cats = wp_get_post_categories( $post_id, array('fields'=>'ids' ) );
			    $acmephoto_cat_post_args['category__in'] = $cats;
		    }
		    else {
			    $acmephoto_cat_post_args['cat'] = $cats;
		    }

	    }
	    $acmephoto_featured_query = new WP_Query($acmephoto_cat_post_args);
	    if( $acmephoto_featured_query->have_posts() ){
		    ?>
            <div class="related-post-wrapper">
			    <?php
			    $acmephoto_related_title = $acmephoto_customizer_all_values['acmephoto-related-title'];
			    if( !empty( $acmephoto_related_title ) ){
				    ?>
                    <h2 class="widget-title">
                        <span><?php echo esc_html( $acmephoto_related_title ); ?></span>
                    </h2>
				    <?php
			    }
			    ?>
                <div class="featured-entries-col masonry-start featured-related-posts">
				    <?php
				    while ( $acmephoto_featured_query->have_posts() ) : $acmephoto_featured_query->the_post();
					    get_template_part( 'template-parts/content', 'related' );
				    endwhile;
				    wp_reset_postdata();
				    ?>
                </div>
                <div class="clearfix"></div>
            </div>
		    <?php
        }
    }
endif;
add_action( 'acmephoto_related_posts', 'acmephoto_related_post_below', 10, 1 );