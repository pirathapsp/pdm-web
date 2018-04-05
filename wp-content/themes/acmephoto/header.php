<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Acme Themes
 * @subpackage AcmePhoto
 */

/**
 * acmephoto_action_before_head hook
 * @since AcmePhoto 1.0.0
 *
 * @hooked acmephoto_set_global -  0
 * @hooked acmephoto_doctype -  10
 */
do_action( 'acmephoto_action_before_head' );?>
	<head>

		<?php
		/**
		 * acmephoto_action_before_wp_head hook
		 * @since AcmePhoto 1.0.0
		 *
		 * @hooked acmephoto_before_wp_head -  10
		 */
		do_action( 'acmephoto_action_before_wp_head' );

		wp_head();
		?>
	</head>
<body <?php body_class();?>>

<?php
/**
 * acmephoto_action_before hook
 * @since AcmePhoto 1.0.0
 *
 * @hooked acmephoto_page_start - 10
 * @hooked acmephoto_page_start - 15
 */
do_action( 'acmephoto_action_before' );

/**
 * acmephoto_action_before_header hook
 * @since AcmePhoto 1.0.0
 *
 * @hooked acmephoto_skip_to_content - 10
 */
do_action( 'acmephoto_action_before_header' );

/**
 * acmephoto_action_header hook
 * @since AcmePhoto 1.0.0
 *
 * @hooked acmephoto_after_header - 10
 */
do_action( 'acmephoto_action_header' );

/**
 * acmephoto_action_after_header hook
 * @since AcmePhoto 1.0.0
 *
 * @hooked null
 */
do_action( 'acmephoto_action_after_header' );

/**
 * acmephoto_action_before_content hook
 * @since AcmePhoto 1.0.0
 *
 * @hooked acmephoto_before_content - 10
 */
do_action( 'acmephoto_action_before_content' );