<?php
/**
 * Template part for displaying home portfolio section.
 *
 * @package Photomania
 */

?>
<?php
	$portfolio_title         = photomania_get_option( 'portfolio_title' );
	$portfolio_number        = photomania_get_option( 'portfolio_number' );
	$portfolio_category      = photomania_get_option( 'portfolio_category' );
	$portfolio_enable_browse = photomania_get_option( 'portfolio_enable_browse' );
	if ( 1 === count( $portfolio_category ) && empty( $portfolio_category[0] ) ) {
		$portfolio_category = array();
	}
?>

<div id="photomania-portfolio" class="home-section-portfolio">
	<div class="container">
		<h2 class="section-title"><?php echo esc_html( $portfolio_title ); ?></h2>
		<?php
			$cat_args = array(
				'hide_empty'   => true,
				'hierarchical' => false,
				'number'       => 5,
			);
			if ( ! empty( $portfolio_category ) ) {
				$cat_args['include'] = $portfolio_category;
				$cat_args['number']  = count( $portfolio_category );
			}
			$categories = get_categories( $cat_args );
		?>
		<?php if ( ! empty( $categories ) ) : ?>
			<div class="portfolio-filter">
				<ul>
				<?php foreach ( $categories as $cat ) : ?>
					<li><a href="#" data-term_url="<?php echo esc_url( get_term_link( $cat ) ); ?>" data-term_id="<?php echo esc_attr( $cat->term_id ); ?>"><?php echo esc_html( $cat->name ); ?></a></li>
				<?php endforeach; ?>
				</ul>
			</div><!-- .portfolio-filter -->
		<?php endif; ?>

		<div class="portfolio-main-wrapper">
			<div class="portfolio-container portfolio-column-3">
				<div id="portfolio-loading">
					<img src="<?php echo get_template_directory_uri(); ?>/images/loading.gif" alt="" />
				</div><!-- #portfolio-loading -->
				<div id="portfolio-output" ><!-- #portfolio-output -->
				</div> <!-- #portfolio-output -->
			</div><!-- .portfolio-container -->
		</div> <!-- .portfolio-main-wrapper -->
		<?php if ( true === $portfolio_enable_browse ) : ?>
			<a href="#" id="portfolio-browse-more" class="button"><?php _e( 'Browse More', 'photomania' ); ?></a>
		<?php endif; ?>
	</div> <!-- .container -->
</div><!-- .home-section-portfolio -->

