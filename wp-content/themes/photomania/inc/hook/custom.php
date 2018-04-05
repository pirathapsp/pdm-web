<?php
/**
 * Custom theme functions.
 *
 * This file contains hook functions attached to theme hooks.
 *
 * @package Photomania
 */

if ( ! function_exists( 'photomania_skip_to_content' ) ) :
	/**
	 * Add Skip to content.
	 *
	 * @since 1.0.0
	 */
	function photomania_skip_to_content() {
	?><a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'photomania' ); ?></a><?php
	}
endif;

add_action( 'photomania_action_before', 'photomania_skip_to_content', 15 );


if ( ! function_exists( 'photomania_site_branding' ) ) :

	/**
	 * Site branding.
	 *
	 * @since 1.0.0
	 */
	function photomania_site_branding() {

		?>
	    <div class="site-branding">

			<?php photomania_the_custom_logo(); ?>

			<?php $show_title = photomania_get_option( 'show_title' ); ?>
			<?php $show_tagline = photomania_get_option( 'show_tagline' ); ?>
			<?php if ( true === $show_title || true === $show_tagline ) :  ?>
				<div id="site-identity">
					<?php if ( true === $show_title ) : ?>
						<?php if ( is_front_page() && is_home() ) : ?>
							<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
						<?php else : ?>
							<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
						<?php endif; ?>
					<?php endif; ?>
					<?php if ( true === $show_tagline ) :  ?>
						<p class="site-description"><?php bloginfo( 'description' ); ?></p>
					<?php endif; ?>
				</div><!-- #site-identity -->
			<?php endif; ?>
	    </div><!-- .site-branding -->
	    <div id="header-social">
			<?php the_widget( 'Photomania_Social_Widget' ); ?>
	    </div><!-- #header-social -->
	    <div id="main-nav">
	        <nav id="site-navigation" class="main-navigation" role="navigation">
	            <div class="wrap-menu-content">
					<?php
					wp_nav_menu(
						array(
						'theme_location' => 'primary',
						'menu_id'        => 'primary-menu',
						'fallback_cb'    => 'photomania_primary_navigation_fallback',
						)
					);
					?>
	            </div><!-- .menu-content -->
	        </nav><!-- #site-navigation -->
	    </div> <!-- #main-nav -->

	    <?php
	}

endif;

add_action( 'photomania_action_header', 'photomania_site_branding' );

if ( ! function_exists( 'photomania_mobile_navigation' ) ) :

	/**
	 * Mobile navigation.
	 *
	 * @since 1.0.0
	 */
	function photomania_mobile_navigation() {
		?>
		<a id="mobile-trigger" href="#mob-menu"><i class="fa fa-bars"></i></a>
		<div id="mob-menu">
			<?php
			wp_nav_menu( array(
				'theme_location' => 'primary',
				'container'      => '',
				'fallback_cb'    => 'photomania_primary_navigation_fallback',
				) );
			?>
		</div><!-- #mob-menu -->
		<?php

	}

endif;
add_action( 'photomania_action_before', 'photomania_mobile_navigation', 20 );

if ( ! function_exists( 'photomania_footer_copyright' ) ) :

	/**
	 * Footer copyright
	 *
	 * @since 1.0.0
	 */
	function photomania_footer_copyright() {

		// Check if footer is disabled.
		$footer_status = apply_filters( 'photomania_filter_footer_status', true );
		if ( true !== $footer_status ) {
			return;
		}

		// Footer Menu.
		$footer_menu_content = wp_nav_menu( array(
			'theme_location' => 'footer',
			'container'      => 'div',
			'container_id'   => 'footer-navigation',
			'depth'          => 1,
			'fallback_cb'    => false,
			'echo'           => false,
		) );

		// Copyright content.
		$copyright_text = photomania_get_option( 'copyright_text' );
		$copyright_text = apply_filters( 'photomania_filter_copyright_text', $copyright_text );
		if ( ! empty( $copyright_text ) ) {
			$copyright_text = wp_kses_data( $copyright_text );
		}

		// Powered by content.
		$powered_by_text = sprintf( __( 'Photomania by %s', 'photomania' ), '<a target="_blank" rel="designer" href="https://wenthemes.com/">' . __( 'WEN Themes', 'photomania' ) . '</a>' );

		$show_social_in_footer = photomania_get_option( 'show_social_in_footer' );
		?>

		<div class="colophon-inner">

		    <?php if ( true === $show_social_in_footer && has_nav_menu( 'social' ) ) : ?>
			    <div class="colophon-column">
			    	<div class="footer-social">
			    		<?php the_widget( 'Photomania_Social_Widget' ); ?>
			    	</div><!-- .footer-social -->
			    </div><!-- .colophon-column -->
		    <?php endif; ?>

		    <?php if ( ! empty( $copyright_text ) ) : ?>
			    <div class="colophon-column">
			    	<div class="copyright">
			    		<?php echo $copyright_text; ?>
			    	</div><!-- .copyright -->
			    </div><!-- .colophon-column -->
		    <?php endif; ?>

		    <?php if ( ! empty( $footer_menu_content ) ) : ?>
		    	<div class="colophon-column">
					<?php echo $footer_menu_content; ?>
		    	</div><!-- .colophon-column -->
		    <?php endif; ?>

		    <?php if ( ! empty( $powered_by_text ) ) : ?>
			    <div class="colophon-column">
			    	<div class="site-info">
			    		<?php echo $powered_by_text; ?>
			    	</div><!-- .site-info -->
			    </div><!-- .colophon-column -->
		    <?php endif; ?>

		</div><!-- .colophon-inner -->

	    <?php
	}

endif;

add_action( 'photomania_action_footer', 'photomania_footer_copyright', 10 );


if ( ! function_exists( 'photomania_add_sidebar' ) ) :

	/**
	 * Add sidebar.
	 *
	 * @since 1.0.0
	 */
	function photomania_add_sidebar() {

		global $post;

		$global_layout = photomania_get_option( 'global_layout' );
		$global_layout = apply_filters( 'photomania_filter_theme_global_layout', $global_layout );

		// Check if single.
		if ( $post && is_singular() ) {
			$post_options = get_post_meta( $post->ID, 'photomania_theme_settings', true );
			if ( isset( $post_options['post_layout'] ) && ! empty( $post_options['post_layout'] ) ) {
				$global_layout = $post_options['post_layout'];
			}
		}

		// Include primary sidebar.
		if ( 'no-sidebar' !== $global_layout ) {
			get_sidebar();
		}
		// Include Secondary sidebar.
		switch ( $global_layout ) {
		  case 'three-columns':
		    get_sidebar( 'secondary' );
		    break;

		  default:
		    break;
		}

	}

endif;

add_action( 'photomania_action_sidebar', 'photomania_add_sidebar' );

if ( ! function_exists( 'photomania_custom_posts_navigation' ) ) :

	/**
	 * Posts navigation.
	 *
	 * @since 1.0.0
	 */
	function photomania_custom_posts_navigation() {

		the_posts_pagination();

	}
endif;

add_action( 'photomania_action_posts_navigation', 'photomania_custom_posts_navigation' );

if ( ! function_exists( 'photomania_add_image_in_single_display' ) ) :

	/**
	 * Add image in single post.
	 *
	 * @since 1.0.0
	 */
	function photomania_add_image_in_single_display() {

		global $post;

		if ( has_post_thumbnail() ) {

			$values = get_post_meta( $post->ID, 'photomania_theme_settings', true );
			$photomania_theme_settings_single_image = isset( $values['single_image'] ) ? esc_attr( $values['single_image'] ) : '';

			if ( ! $photomania_theme_settings_single_image ) {
				$photomania_theme_settings_single_image = photomania_get_option( 'single_image' );
			}

			if ( 'disable' !== $photomania_theme_settings_single_image ) {
				$args = array(
					'class' => 'aligncenter',
				);
				the_post_thumbnail( esc_attr( $photomania_theme_settings_single_image ), $args );
			}
		}

	}

endif;

add_action( 'photomania_single_image', 'photomania_add_image_in_single_display' );

if ( ! function_exists( 'photomania_add_breadcrumb' ) ) :

	/**
	 * Add breadcrumb.
	 *
	 * @since 1.0.0
	 */
	function photomania_add_breadcrumb() {

		// Bail if Breadcrumb disabled.
		$breadcrumb_type = photomania_get_option( 'breadcrumb_type' );
		if ( 'disabled' === $breadcrumb_type ) {
			return;
		}

		// Bail if Home Page.
		if ( is_front_page() || is_home() ) {
			return;
		}

		echo '<div id="breadcrumb"><div class="container">';
		switch ( $breadcrumb_type ) {
			case 'simple':
				photomania_simple_breadcrumb();
			break;

			default:
			break;
		}
		echo '</div><!-- .container --></div><!-- #breadcrumb -->';

	}

endif;

add_action( 'photomania_action_before_content', 'photomania_add_breadcrumb', 7 );


if ( ! function_exists( 'photomania_footer_goto_top' ) ) :

	/**
	 * Go to top.
	 *
	 * @since 1.0.0
	 */
	function photomania_footer_goto_top() {

		$go_to_top = photomania_get_option( 'go_to_top' );
		if ( true !== $go_to_top ) {
			return;
		}
		echo '<a href="#page" class="scrollup" id="btn-scrollup"><i class="fa fa-angle-up"></i></a>';

	}

endif;

add_action( 'photomania_action_after', 'photomania_footer_goto_top', 20 );

if ( ! function_exists( 'photomania_add_front_page_home_sections' ) ) :

	/**
	 * Add Front Page widget sections.
	 *
	 * @since 1.0.0
	 */
	function photomania_add_front_page_home_sections() {

		$section_status = apply_filters( 'photomania_filter_front_page_home_sections_status', false );

		if ( true !== $section_status ) {
			return;
		}

		$active_sections = photomania_get_active_homepage_sections();

		if ( ! empty( $active_sections ) ) {
			echo '<div id="front-page-home-sections" class="widget-area">';
			foreach ( $active_sections as $section ) {
				get_template_part( $section['template'] );
			}
			echo '</div><!-- #front-page-home-sections -->';
		}

	}
endif;

add_action( 'photomania_action_before_content', 'photomania_add_front_page_home_sections', 6 );



if( ! function_exists( 'photomania_check_front_homepage_section_status' ) ) :

	/**
	 * Check status of front homepage section.
	 *
	 * @since 1.0.0
	 */
	function photomania_check_front_homepage_section_status( $input ) {

		$current_id = photomania_get_index_page_id();

		if ( is_front_page() && get_queried_object_id() === $current_id && $current_id > 0 ) {
			$input = true;
		}

		return $input;

	}
endif;

add_filter( 'photomania_filter_front_page_home_sections_status', 'photomania_check_front_homepage_section_status' );

if ( ! function_exists( 'photomania_check_home_page_content' ) ) :

	/**
	 * Check home page content status.
	 *
	 * @since 1.0.0
	 *
	 * @param bool $status Home page content status.
	 * @return bool Modified home page content status.
	 */
	function photomania_check_home_page_content( $status ) {

		if ( is_front_page() ) {
			$home_content_status = photomania_get_option( 'home_content_status' );
			if ( false === $home_content_status ) {
				$status = false;
			}
		}
		return $status;

	}

endif;

add_action( 'photomania_filter_home_page_content', 'photomania_check_home_page_content' );

if ( ! function_exists( 'photomania_fetch_portfolio_posts' ) ) :

	/**
	 * Returns portfolio posts.
	 *
	 * @since 1.0.0
	 */
	function photomania_fetch_portfolio_posts() {

		$output = array();
		$output['status'] = 0;
		$term_id = absint( $_POST['term_id'] );

		$portfolio_number = photomania_get_option( 'portfolio_number' );

		if ( $term_id > 0 ) {
			$output['term_id'] = $term_id;
			$output['status'] = 1;

			// Args.
			$qargs = array(
				'posts_per_page' => absint( $portfolio_number ),
				'no_found_rows'  => true,
				'meta_query'     => array(
					array(
						'key' => '_thumbnail_id',
						),
					),
				);
			$tax = array(
				array(
					'taxonomy' => 'category',
					'field'    => 'term_id',
					'terms'    => $term_id,
					),
				);
			$qargs['tax_query'] = $tax;

			// Fetch posts.
			$the_query = new WP_Query( $qargs );
			$posts = array();

			if ( $the_query->have_posts() ) {
				while ( $the_query->have_posts() ) {
					$the_query->the_post();
					$item = array();
					$item['ID']    = get_the_ID();
					$item['title'] = get_the_title();
					$item['url']   = get_permalink();

					// Images.
					$image_details = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'photomania-portfolio' );
					$item['images']['thumb']['url'] = $image_details[0];
					$item['images']['thumb']['width'] = $image_details[1];
					$item['images']['thumb']['height'] = $image_details[2];
					$large_image_details = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'full' );
					$item['images']['large']['url'] = $large_image_details[0];
					$item['images']['large']['width'] = $large_image_details[1];
					$item['images']['large']['height'] = $large_image_details[2];

					// Social.
					$item['social']['facebook']   = 'https://www.facebook.com/sharer/sharer.php?u=' . $item['url'] . '&title=' . urlencode( $item['title'] );
					$item['social']['pinterest']  = 'https://pinterest.com/pin/create/button/?url=' . $item['url'] . '&media=' . $large_image_details[0] . '&description=' . urlencode( $item['title'] );
					$item['social']['twitter']    = 'http://twitter.com/intent/tweet?status=' . urlencode( $item['title'] ) . '+' . $item['url'];
					$item['social']['googleplus'] = 'https://plus.google.com/share?url=' . $item['url'];

					$posts[] = $item;
				}
				// Reset.
				wp_reset_postdata();
			}
			if ( ! empty( $posts ) ) {
				$output['posts'] = $posts;
			}

		}

		return wp_send_json( $output );

	}
endif;

add_action( 'wp_ajax_photomania_portfolio_fetch_posts', 'photomania_fetch_portfolio_posts' );
add_action( 'wp_ajax_nopriv_photomania_portfolio_fetch_posts', 'photomania_fetch_portfolio_posts' );

if ( ! function_exists( 'photomania_print_portfolio_post_template' ) ) :

	/**
	 * Render portfolio template.
	 *
	 * @since 1.0.0
	 */

	function photomania_print_portfolio_post_template() {
		$portfolio_enable_popup  = photomania_get_option( 'portfolio_enable_popup' );
		$portfolio_enable_social = photomania_get_option( 'portfolio_enable_social' );
		?>
	    <script type="text/html" id="tmpl-portfolio-post">

			    <div class="portfolio-item">

			    	<div class="portfolio-item-wrapper">
			    		<div class="portfolio-thumb">
				    		<a href="{{ data.url }}"><img src="{{ data.images.thumb.url }}" width="{{ data.images.thumb.width }}" height="{{ data.images.thumb.height }}" alt="" /></a>
			    		</div>
			    		<div class="post-content">
				    		<?php if ( true === $portfolio_enable_popup ) : ?>
					    		<a class="popup-link" href="{{ data.images.large.url }}"><i class="fa fa-search-plus" aria-hidden="true"></i></a>
				    		<?php endif; ?>
			    			<h2 class="portfolio-title"><a href="{{ data.url }}">{{ data.title }}</a></h2>
			    		</div><!-- .post-content -->
    		    		<?php if ( true === $portfolio_enable_social ) : ?>
    			    		<div class="photomania-social-share">
    			    			<ul>
	    			    			<li><a href="{{ data.social.facebook }}" target="_blank"><i class="fa fa-facebook"></i></a></li>
	    			    			<li><a href="{{ data.social.pinterest }}" target="_blank"><i class="fa fa-pinterest"></i></a></li>
	    			    			<li><a href="{{ data.social.twitter }}" target="_blank"><i class="fa fa-twitter"></i></a></li>
	    			    			<li><a href="{{ data.social.googleplus }}" target="_blank"><i class="fa fa-google-plus"></i></a></li>
    			    			</ul>
    			    		</div><!-- .photomania-social-share -->
    		    		<?php endif; ?>

			    	</div><!-- .portfolio-item-wrapper -->

			    </div><!-- .portfolio-item -->

	    </script>
		<?php
	}
endif;

add_action( 'wp_footer', 'photomania_print_portfolio_post_template', 25 );

if ( ! function_exists( 'photomania_add_before_archive_loop_markup' ) ) :

	/**
	 * Add before archive loop markup.
	 *
	 * @since 1.0.0
	 */

	function photomania_add_before_archive_loop_markup() {
		$archive_layout = photomania_get_option( 'archive_layout' );
		if ( 'masonry' === $archive_layout ) {
			echo '<div id="masonry-loop">';
		}
	}
endif;

add_action( 'photomania_action_before_archive_loop', 'photomania_add_before_archive_loop_markup' );

if ( ! function_exists( 'photomania_add_after_archive_loop_markup' ) ) :

	/**
	 * Add after archive loop markup.
	 *
	 * @since 1.0.0
	 */

	function photomania_add_after_archive_loop_markup() {
		$archive_layout = photomania_get_option( 'archive_layout' );
		if ( 'masonry' === $archive_layout ) {
			echo '</div><!-- #masonry-loop -->';
		}
	}
endif;

add_action( 'photomania_action_after_archive_loop', 'photomania_add_after_archive_loop_markup' );
