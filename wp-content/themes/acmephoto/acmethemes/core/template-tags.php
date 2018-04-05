<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Acme Themes
 * @subpackage AcmePhoto
 */
if ( ! function_exists( 'acmephoto_posted_on' ) ) :
	/**
	 * Prints HTML with meta information for the current post-date/time and author.
	 */
	function acmephoto_posted_on( $show_date = 1, $show_author = 1 ) {

		if( 1 == $show_date ){
			$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
			if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
				$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
			}

			$time_string = sprintf( $time_string,
				esc_attr( get_the_date( 'c' ) ),
				esc_html( get_the_date() ),
				esc_attr( get_the_modified_date( 'c' ) ),
				esc_html( get_the_modified_date() )
			);

			$posted_on = sprintf(
				'%s',
				'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark"><i class="fa fa-calendar"></i>' . $time_string . '</a>'
			);
			echo '<span class="posted-on">' . $posted_on . '</span>'; // WPCS: XSS OK.

		}
		if( 1== $show_author ){

			$byline = sprintf(
				'%s',
				'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '"><i class="fa fa-user"></i>' . esc_html( get_the_author() ) . '</a></span>'
			);

			echo '<span class="byline"> ' . $byline . '</span>'; // WPCS: XSS OK.
		}

	}
endif;

if ( ! function_exists( 'acmephoto_entry_footer' ) ) :
/**
 * Prints HTML with meta information for the categories, tags and comments.
 */
function acmephoto_entry_footer( $show_cat = 1, $show_tag = 1 , $show_comment =  1 ,$edit_post =  1 ) {
	// Hide category and tag text for pages.
	if ( 'post' === get_post_type() ) {
		/* translators: used between list items, there is a space after the comma */
		if( 1 == $show_cat ){
			$categories_list = get_the_category_list( esc_html__( ', ', 'acmephoto' ) );
			if ( $categories_list && acmephoto_categorized_blog() ) {
				printf( '<span class="cat-links"><i class="fa fa-folder-o"></i>%1$s</span>', $categories_list ); // WPCS: XSS OK.
			}
		}

		/* translators: used between list items, there is a space after the comma */
		if( 1 == $show_tag ){
			$tags_list = get_the_tag_list( '', esc_html__( ', ', 'acmephoto' ) );
			if ( $tags_list ) {
				printf( '<span class="tags-links"><i class="fa fa-tags"></i>%1$s</span>', $tags_list ); // WPCS: XSS OK.
			}
		}
	}
	if( 1 == $show_comment ){
		if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
			echo '<span class="comments-link"><i class="fa fa-comment-o"></i>';
			comments_popup_link( esc_html__( 'Leave a comment', 'acmephoto' ), esc_html__( '1 Comment', 'acmephoto' ), esc_html__( '% Comments', 'acmephoto' ) );
			echo '</span>';
		}
	}
	if( $edit_post == 1 && get_edit_post_link()){
		edit_post_link(
			sprintf(
			/* translators: %s: Name of current post */
				esc_html__( 'Edit %s', 'acmephoto' ),
				the_title( '<span class="screen-reader-text">"', '"</span>', false )
			),
			'<span class="edit-link"><i class="fa fa-edit "></i>',
			'</span>'
		);	
	}
}
endif;

/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
if ( ! function_exists( 'acmephoto_categorized_blog' ) ) :
	
	function acmephoto_categorized_blog() {
		if ( false === ( $all_the_cool_cats = get_transient( 'acmephoto_categories' ) ) ) {
			// Create an array of all the categories that are attached to posts.
			$all_the_cool_cats = get_categories( array(
				'fields'     => 'ids',
				'hide_empty' => 1,

				// We only need to know if there is more than one category.
				'number'     => 2,
			) );

			// Count the number of categories that are attached to the posts.
			$all_the_cool_cats = count( $all_the_cool_cats );

			set_transient( 'acmephoto_categories', $all_the_cool_cats );
		}

		if ( $all_the_cool_cats > 1 ) {
			// This blog has more than 1 category so acmephoto_categorized_blog should return true.
			return true;
		} else {
			// This blog has only 1 category so acmephoto_categorized_blog should return false.
			return false;
		}
	}
endif;

/**
 * Flush out the transients used in acmephoto_categorized_blog.
 */
if ( ! function_exists( 'acmephoto_category_transient_flusher' ) ) :
	
	function acmephoto_category_transient_flusher() {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		// Like, beat it. Dig?
		delete_transient( 'acmephoto_categories' );
	}

endif;
add_action( 'edit_category', 'acmephoto_category_transient_flusher' );
add_action( 'save_post',     'acmephoto_category_transient_flusher' );