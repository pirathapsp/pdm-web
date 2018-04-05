<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Photomania
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'masonry-entry' ); ?>>
	<div class="masonry-item-wrapper">
		<div class="masonry-thumb">
			<?php if ( has_post_thumbnail() ) : ?>
				<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'photomania-portfolio' ); ?></a>
			<?php else: ?>
				<a href="<?php the_permalink(); ?>"><img src="<?php echo get_template_directory_uri(); ?>/images/masonry-no-image.png" alt="" /></a>
			<?php endif; ?>
		</div><!-- .masonry-thumb -->
		<div class="post-content">
			<?php $large_image_details = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'large' ); ?>
			<?php if ( ! empty( $large_image_details ) ) : ?>
				<a class="popup-link" href="<?php echo esc_url( $large_image_details[0] ); ?>"><i class="fa fa-search-plus" aria-hidden="true"></i></a>
			<?php endif; ?>
			<?php the_title( sprintf( '<h2 class="masonry-item-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
		</div><!-- .post-content -->

		<?php if ( true === photomania_get_option( 'social_share_enable_archive' ) ) : ?>
			<?php photomania_render_social_share(); ?>
		<?php endif; ?>

	</div><!-- .masonry-item-wrapper -->

</article><!-- #post-## -->
