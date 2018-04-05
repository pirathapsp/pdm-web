<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Photomania
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>

		<?php if ( 'post' === get_post_type() ) : ?>
		<div class="entry-meta">
			<?php photomania_posted_on(); ?>
		</div><!-- .entry-meta -->
		<?php endif; ?>
	</header><!-- .entry-header -->

	<?php $archive_layout = photomania_get_option( 'archive_layout' ); ?>
	<?php if ( has_post_thumbnail() ) : ?>
		<?php
		$archive_image           = photomania_get_option( 'archive_image' );
		$archive_image_alignment = photomania_get_option( 'archive_image_alignment' );
		?>
		<?php if ( 'disable' !== $archive_image ) : ?>
			<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( esc_attr( $archive_image ), array( 'class' => 'align'. esc_attr( $archive_image_alignment ) ) ); ?></a>
		<?php endif; ?>
	<?php endif; ?>
	<div class="entry-content-wrapper">
		<div class="entry-content">

			<?php if ( 'full' === $archive_layout ) : ?>
				<?php
				the_content( sprintf(
					/* translators: %s: Name of current post. */
					wp_kses( __( 'Continue reading %s <span class="meta-nav">&rarr;</span>', 'photomania' ), array( 'span' => array( 'class' => array() ) ) ),
					the_title( '<span class="screen-reader-text">"', '"</span>', false )
				) );
				?>
				<?php
					wp_link_pages( array(
						'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'photomania' ),
						'after'  => '</div>',
					) );
				?>
		    <?php else : ?>
				<?php the_excerpt(); ?>
		    <?php endif; ?>

		    <footer class="entry-footer">
		    	<?php photomania_entry_footer(); ?>
		    </footer><!-- .entry-footer -->

		    <?php if ( true === photomania_get_option( 'social_share_enable_archive' ) ) : ?>
		    	<?php photomania_render_social_share(); ?>
		    <?php endif; ?>

		</div><!-- .entry-content -->

	</div><!-- .entry-content-wrapper -->


</article><!-- #post-## -->
