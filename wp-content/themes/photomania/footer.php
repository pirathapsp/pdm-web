<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Photomania
 */

	/**
	 * Hook - photomania_action_after_content.
	 *
	 * @hooked photomania_content_end - 10
	 */
	do_action( 'photomania_action_after_content' );
?>

	<?php
	/**
	 * Hook - photomania_action_before_footer.
	 *
	 * @hooked photomania_add_footer_bottom_widget_area - 5
	 * @hooked photomania_footer_start - 10
	 */
	do_action( 'photomania_action_before_footer' );
	?>
    <?php
	  /**
	   * Hook - photomania_action_footer.
	   *
	   * @hooked photomania_footer_copyright - 10
	   */
	  do_action( 'photomania_action_footer' );
	?>
	<?php
	/**
	 * Hook - photomania_action_after_footer.
	 *
	 * @hooked photomania_footer_end - 10
	 */
	do_action( 'photomania_action_after_footer' );
	?>

<?php
	/**
	 * Hook - photomania_action_after.
	 *
	 * @hooked photomania_page_end - 10
	 * @hooked photomania_footer_goto_top - 20
	 */
	do_action( 'photomania_action_after' );
?>

<?php wp_footer(); ?>
</body>
</html>
