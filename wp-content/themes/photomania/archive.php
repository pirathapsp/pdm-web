<?php
/**
 * The template for displaying archive pages.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Photomania
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php if ( have_posts() ) : ?>

			<header class="page-header">
				<?php
					the_archive_title( '<h1 class="page-title">', '</h1>' );
					the_archive_description( '<div class="taxonomy-description">', '</div>' );
				?>
			</header><!-- .page-header -->

			<?php
			/**
			 * Hook - photomania_action_before_archive_loop.
			 *
			 * @hooked: photomania_add_before_archive_loop_markup - 10
			 */
			do_action( 'photomania_action_before_archive_loop' ); ?>

			<?php while ( have_posts() ) : the_post(); ?>

				<?php
					$archive_layout = photomania_get_option( 'archive_layout' );
					$part = ( 'masonry' === $archive_layout ) ? 'masonry' : '';
					get_template_part( 'template-parts/content', $part );
				?>

			<?php endwhile; ?>

			<?php
			/**
			 * Hook - photomania_action_after_archive_loop.
			 *
			 * @hooked: photomania_add_after_archive_loop_markup - 10
			 */
			do_action( 'photomania_action_after_archive_loop' ); ?>

			<?php
			/**
			 * Hook - photomania_action_posts_navigation.
			 *
			 * @hooked: photomania_custom_posts_navigation - 10
			 */
			do_action( 'photomania_action_posts_navigation' ); ?>

		<?php else : ?>

			<?php get_template_part( 'template-parts/content', 'none' ); ?>

		<?php endif; ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
	/**
	 * Hook - photomania_action_sidebar.
	 *
	 * @hooked: photomania_add_sidebar - 10
	 */
	do_action( 'photomania_action_sidebar' );
?>
<?php get_footer(); ?>
