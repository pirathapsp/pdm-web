<?php
/**
 * Template part for displaying single posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Photomania
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
		<div class="entry-meta">
			<?php photomania_posted_on(); ?>
		</div><!-- .entry-meta -->
	</header><!-- .entry-header -->

    <?php
	  /**
	   * Hook - photomania_single_image.
	   *
	   * @hooked photomania_add_image_in_single_display -  10
	   */
	  do_action( 'photomania_single_image' );
	?>

	<div class="entry-content-wrapper">
		<div class="entry-content">
			<?php the_content(); ?>
			<?php
				wp_link_pages( array(
					'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'photomania' ),
					'after'  => '</div>',
				) );
			?>
		</div><!-- .entry-content -->
	</div><!-- .entry-content-wrapper -->

	<footer class="entry-footer">
		<?php photomania_entry_footer(); ?>
	</footer><!-- .entry-footer -->

	<?php if ( true === photomania_get_option( 'social_share_enable_single_post' ) ) : ?>
		<?php photomania_render_social_share(); ?>
	<?php endif; ?>

</article><!-- #post-## -->
