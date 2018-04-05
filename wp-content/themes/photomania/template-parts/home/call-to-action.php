<?php
/**
 * Template part for displaying home call to action section.
 *
 * @package Photomania
 */

?>
<?php
	$cta_title                 = photomania_get_option( 'cta_title' );
	$cta_description           = photomania_get_option( 'cta_description' );
	$cta_primary_button_text   = photomania_get_option( 'cta_primary_button_text' );
	$cta_primary_button_url    = photomania_get_option( 'cta_primary_button_url' );
	$cta_secondary_button_text = photomania_get_option( 'cta_secondary_button_text' );
	$cta_secondary_button_url  = photomania_get_option( 'cta_secondary_button_url' );
?>
<div id="photomania-call-to-action" class="home-section-call-to-action">
	<div class="container">
		<h2 class="section-title"><?php echo esc_html( $cta_title ); ?></h2>
		<div class="cta-content">
			<div class="cta-content-text">
				<?php echo wp_kses_post( wpautop( $cta_description ) ); ?>
			</div><!-- .cta-content-text -->
		</div><!-- .cta-content -->
			<div class="cta-buttons">
				<?php if ( ! empty( $cta_primary_button_text ) && ! empty( $cta_primary_button_url ) ) : ?>
					<a href="<?php echo esc_url( $cta_primary_button_url ); ?>" class="button cta-btn"><?php echo esc_html( $cta_primary_button_text ); ?></a>
				<?php endif; ?>
				<?php if ( ! empty( $cta_secondary_button_text ) && ! empty( $cta_secondary_button_url ) ) : ?>
					<a href="<?php echo esc_url( $cta_secondary_button_url ); ?>" class="button cta-btn"><?php echo esc_html( $cta_secondary_button_text ); ?></a>
				<?php endif; ?>
			</div><!-- .cta-buttons -->
	</div> <!-- .container -->
</div><!-- .home-section-call-to-action -->
