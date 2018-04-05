<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Acme Themes
 * @subpackage AcmePhoto
 */

/**
 * acmephoto_action_after_content hook
 * @since AcmePhoto 1.0.0
 *
 * @hooked acmephoto_after_content - 10
 */
do_action( 'acmephoto_action_after_content' );

/**
 * acmephoto_action_before_footer hook
 * @since AcmePhoto 1.0.0
 *
 * @hooked null
 */
do_action( 'acmephoto_action_before_footer' );

/**
 * acmephoto_action_footer hook
 * @since AcmePhoto 1.0.0
 *
 * @hooked acmephoto_footer - 10
 */
do_action( 'acmephoto_action_footer' );

/**
 * acmephoto_action_after_footer hook
 * @since AcmePhoto 1.0.0
 *
 * @hooked null
 */
do_action( 'acmephoto_action_after_footer' );

/**
 * acmephoto_action_after hook
 * @since AcmePhoto 1.0.0
 *
 * @hooked acmephoto_page_end - 10
 */
do_action( 'acmephoto_action_after' );
wp_footer(); ?>
</body>
</html>