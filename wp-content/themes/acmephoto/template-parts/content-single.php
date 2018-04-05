<?php
/**
 * Template part for displaying single posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Acme Themes
 * @subpackage AcmePhoto
 */
$no_image ='';
if( !has_post_thumbnail() ){
	$no_image = 'acme-no-image';
}
global $acmephoto_customizer_all_values;
$acmephoto_single_image_size = $acmephoto_customizer_all_values['acmephoto-single-image-size']
?>
<article id="post-<?php the_ID(); ?>" <?php post_class($no_image); ?>>
	<!--post thumbnal options-->
	<?php
	if( has_post_thumbnail() ){
		?>
		<div class="single-feat clearfix">
			<div class="at-hover-fixed">
				<figure class="single-thumb single-thumb-full">
					<?php
					the_post_thumbnail( $acmephoto_single_image_size );
					?>
				</figure>
				<div class="singe-content-wrapper">
					<div class="at-overlay">
						<div class="acme-col-2">
							<div class="entry-data">
								<?php if ( 'post' === get_post_type() ) : ?>
									<div class="entry-meta">
										<?php acmephoto_posted_on( 1, 1 ); ?>
									</div><!-- .entry-meta -->
								<?php endif; ?>
							</div>
						</div>
						<div class="acme-col-2 float-right">
							<footer class="entry-footer">
								<?php acmephoto_entry_footer(1, 0, 1, 0); ?>
							</footer><!-- .entry-footer -->
						</div>
					</div>
				</div>
			</div><!--.at-hover-fixed-->
		</div><!-- .single-feat-->
	<?php
	}
	?>
	<header class="entry-header border">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
	</header><!-- .entry-header -->
	<?php
	if( !has_post_thumbnail() ){
		?>
		<div class="no-thumbnail">
			<div class="entry-data">
				<?php if ( 'post' === get_post_type() ) : ?>
					<div class="entry-meta">
						<?php acmephoto_posted_on( 1, 1 ); ?>
					</div><!-- .entry-meta -->
				<?php endif; ?>
			</div>
		</div>
		<?php
	}
	?>
	<div class="entry-content">
		<?php the_content(); ?>
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'acmephoto' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->
	<?php
	if( !has_post_thumbnail() ){
		?>
		<div class="no-thumbnail">
			<footer class="entry-footer">
				<?php acmephoto_entry_footer(1, 0, 1, 0); ?>
			</footer><!-- .entry-footer -->
		</div>
		<?php
	}
	?>
</article><!-- #post-## -->