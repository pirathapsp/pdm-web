<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Photomania
 */

?><?php
	/**
	 * Hook - photomania_action_doctype.
	 *
	 * @hooked photomania_doctype -  10
	 */
	do_action( 'photomania_action_doctype' );
?>
<head>
	<?php
	/**
	 * Hook - photomania_action_head.
	 *
	 * @hooked photomania_head -  10
	 */
	do_action( 'photomania_action_head' );
	?>

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

	<?php
	/**
	 * Hook - photomania_action_before.
	 *
	 * @hooked photomania_page_start - 10
	 * @hooked photomania_skip_to_content - 15
	 */
	do_action( 'photomania_action_before' );
	?>

    <?php
	  /**
	   * Hook - photomania_action_before_header.
	   *
	   * @hooked photomania_header_start - 10
	   */
	  do_action( 'photomania_action_before_header' );
	?>
		<?php
		/**
		 * Hook - photomania_action_header.
		 *
		 * @hooked photomania_site_branding - 10
		 */
		do_action( 'photomania_action_header' );
		?>
    <?php
	  /**
	   * Hook - photomania_action_after_header.
	   *
	   * @hooked photomania_header_end - 10
	   */
	  do_action( 'photomania_action_after_header' );
	?>

	<?php
	/**
	 * Hook - photomania_action_before_content.
	 *
	 * @hooked photomania_add_breadcrumb - 7
	 * @hooked photomania_content_start - 10
	 */
	do_action( 'photomania_action_before_content' );
	?>
    <?php
	  /**
	   * Hook - photomania_action_content.
	   */
	  do_action( 'photomania_action_content' );
	?>
