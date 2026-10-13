<?php
/**
 * Granite Gateway theme functions.
 *
 * @package Granite_Gateway
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'GG_VERSION', '1.0.0' );

require_once get_template_directory() . '/inc/setup.php';

/**
 * Theme supports & menu locations.
 */
function gg_after_setup_theme() {
	add_theme_support( 'title-tag' ); // Lets SEO plugins control <title>.
	add_theme_support( 'post-thumbnails' );
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 36,
			'width'       => 36,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);
	add_theme_support( 'automatic-feed-links' );
	add_theme_support(
		'html5',
		array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' )
	);

	// Editable menus — header + the three footer link columns.
	register_nav_menus(
		array(
			'primary'          => __( 'Header Menu', 'granite-gateway' ),
			'footer_products'  => __( 'Footer — Featured Granite', 'granite-gateway' ),
			'footer_company'   => __( 'Footer — Company', 'granite-gateway' ),
			'footer_resources' => __( 'Footer — Resources', 'granite-gateway' ),
		)
	);
}
add_action( 'after_setup_theme', 'gg_after_setup_theme' );

/**
 * Front-end assets: fonts, compiled CSS, theme JS.
 */
function gg_enqueue_assets() {
	wp_enqueue_style(
		'gg-fonts',
		'https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap',
		array(),
		null
	);

	wp_enqueue_style(
		'gg-styles',
		get_template_directory_uri() . '/assets/css/styles.css',
		array(),
		GG_VERSION
	);

	wp_enqueue_script(
		'gg-theme',
		get_template_directory_uri() . '/assets/js/theme.js',
		array(),
		GG_VERSION,
		true
	);
}
add_action( 'wp_enqueue_scripts', 'gg_enqueue_assets' );

/**
 * Preconnect to Google Fonts.
 */
function gg_resource_hints( $urls, $relation_type ) {
	if ( 'preconnect' === $relation_type ) {
		$urls[] = array( 'href' => 'https://fonts.googleapis.com' );
		$urls[] = array(
			'href'        => 'https://fonts.gstatic.com',
			'crossorigin' => 'anonymous',
		);
	}
	return $urls;
}
add_filter( 'wp_resource_hints', 'gg_resource_hints', 10, 2 );

/**
 * Strip WordPress-generated body classes that fight the Tailwind design.
 * Keep only the few that the design / accessibility actually use.
 */
function gg_body_class( $classes ) {
	$keep = array( 'logged-in', 'admin-bar', 'rtl' );
	$kept = array_values( array_intersect( $classes, $keep ) );

	// Helpful, conflict-free context hooks.
	if ( is_front_page() ) {
		$kept[] = 'page-home';
	} elseif ( is_singular() ) {
		$post = get_queried_object();
		if ( $post instanceof WP_Post ) {
			$kept[] = 'page-' . $post->post_name;
		}
	}
	return $kept;
}
add_filter( 'body_class', 'gg_body_class' );

/**
 * Head cleanup.
 */
remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'wp_shortlink_wp_head' );
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
remove_action( 'admin_print_styles', 'print_emoji_styles' );

/**
 * Dequeue default block CSS so it cannot override the Tailwind build.
 */
function gg_remove_block_css() {
	wp_dequeue_style( 'wp-block-library' );
	wp_dequeue_style( 'wp-block-library-theme' );
	wp_dequeue_style( 'classic-theme-styles' );
	wp_dequeue_style( 'global-styles' );
}
add_action( 'wp_enqueue_scripts', 'gg_remove_block_css', 100 );

/**
 * Current page slug helper (front-page aware).
 */
function gg_current_slug() {
	if ( is_front_page() ) {
		return 'home';
	}
	$obj = get_queried_object();
	if ( $obj instanceof WP_Post ) {
		return $obj->post_name;
	}
	return '';
}

/**
 * Render a nav menu, or fall back to a sensible Pages list if the menu
 * location has not been assigned yet (so the header is never empty).
 */
function gg_nav_menu( $location, $args = array() ) {
	$defaults = array(
		'theme_location' => $location,
		'container'      => false,
		'echo'           => true,
		'fallback_cb'    => 'gg_nav_menu_fallback',
		'depth'          => 2,
	);
	wp_nav_menu( array_merge( $defaults, $args ) );
}

/**
 * Fallback markup when no menu is assigned: list top-level pages.
 */
function gg_nav_menu_fallback( $args ) {
	$items = wp_list_pages(
		array(
			'title_li' => '',
			'echo'     => false,
			'depth'    => 1,
			'sort_column' => 'menu_order, post_title',
		)
	);
	$out = '<ul class="' . esc_attr( $args['menu_class'] ?? '' ) . '">' . $items . '</ul>';
	if ( ! empty( $args['echo'] ) ) {
		echo $out; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		return;
	}
	return $out;
}

/**
 * Give wp_nav_menu <a> tags the design's utility classes.
 * Desktop and mobile menus are differentiated by their menu_class.
 */
function gg_nav_link_attributes( $atts, $item, $args ) {
	$menu_class = isset( $args->menu_class ) ? $args->menu_class : '';
	$active     = array_intersect(
		array(
			'current-menu-item',
			'current-menu-parent',
			'current-menu-ancestor',
			'current_page_item',
			'current_page_parent',
			'current_page_ancestor',
		),
		(array) $item->classes
	);

	if ( strpos( $menu_class, 'gg-nav-desktop' ) !== false ) {
		$atts['class'] = 'px-3 py-2 rounded-md hover:bg-secondary transition-colors' . ( $active ? ' bg-secondary text-foreground' : '' );
	} elseif ( strpos( $menu_class, 'gg-nav-mobile' ) !== false ) {
		$atts['class'] = 'block px-3 py-3 rounded-md hover:bg-secondary transition-colors text-base' . ( $active ? ' bg-secondary text-foreground' : '' );
	} elseif ( strpos( $menu_class, 'gg-footer-menu' ) !== false ) {
		$atts['class'] = 'hover:text-gold transition';
	}
	return $atts;
}
add_filter( 'nav_menu_link_attributes', 'gg_nav_link_attributes', 10, 3 );

/**
 * Add an SVG chevron to desktop menu parent links.
 */
function gg_nav_parent_chevron( $item_output, $item, $depth, $args ) {
	$menu_class = isset( $args->menu_class ) ? $args->menu_class : '';
	$has_child  = in_array( 'menu-item-has-children', (array) $item->classes, true );

	if ( ! $has_child || false === strpos( $menu_class, 'gg-nav-desktop' ) ) {
		return $item_output;
	}

	$icon = '<svg class="gg-nav-chevron" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="m6 9 6 6 6-6"/></svg>';

	return str_replace( '</a>', $icon . '</a>', $item_output );
}
add_filter( 'walker_nav_menu_start_el', 'gg_nav_parent_chevron', 10, 4 );

/**
 * Disable wpautop on the imported CPT content so the captured markup stays intact.
 */
function gg_raw_cpt_content( $content ) {
	if ( is_singular( 'granite_product' ) || ( is_singular( 'post' ) && false !== strpos( $content, '<main' ) ) ) {
		remove_filter( 'the_content', 'wpautop' );
		remove_filter( 'the_content', 'shortcode_unautop' );
	}
	return $content;
}
add_filter( 'the_content', 'gg_raw_cpt_content', 0 );

/* -------------------------------------------------------------------------
 * Contact / quote request form handler.
 * ---------------------------------------------------------------------- */

function gg_handle_contact() {
	$redirect = wp_get_referer() ? wp_get_referer() : home_url( '/contact' );

	if ( ! isset( $_POST['gg_contact_nonce'] ) || ! wp_verify_nonce( wp_unslash( $_POST['gg_contact_nonce'] ), 'gg_contact' ) ) {
		wp_safe_redirect( add_query_arg( 'contact', 'error', $redirect ) );
		exit;
	}

	$fields = array(
		'fullName'    => 'Full Name',
		'company'     => 'Company',
		'country'     => 'Country',
		'email'       => 'Email',
		'whatsapp'    => 'WhatsApp',
		'product'     => 'Product',
		'form'        => 'Required Form',
		'finish'      => 'Finish',
		'thickness'   => 'Thickness (cm)',
		'quantity'    => 'Quantity',
		'port'        => 'Destination Port',
		'projectType' => 'Project Type',
		'message'     => 'Message',
	);

	$name  = isset( $_POST['fullName'] ) ? sanitize_text_field( wp_unslash( $_POST['fullName'] ) ) : '';
	$email = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';

	if ( '' === $name || ! is_email( $email ) ) {
		wp_safe_redirect( add_query_arg( 'contact', 'error', $redirect ) );
		exit;
	}

	$lines = array();
	foreach ( $fields as $key => $label ) {
		if ( ! isset( $_POST[ $key ] ) ) {
			continue;
		}
		$val = 'message' === $key
			? sanitize_textarea_field( wp_unslash( $_POST[ $key ] ) )
			: sanitize_text_field( wp_unslash( $_POST[ $key ] ) );
		if ( '' !== $val ) {
			$lines[] = $label . ': ' . $val;
		}
	}

	$to      = get_option( 'admin_email' );
	$subject = sprintf( '[Quote Request] %s — %s', $name, get_bloginfo( 'name' ) );
	$body    = implode( "\n", $lines );
	$headers = array( 'Content-Type: text/plain; charset=UTF-8' );
	if ( $email ) {
		$headers[] = 'Reply-To: ' . $name . ' <' . $email . '>';
	}

	$sent = wp_mail( $to, $subject, $body, $headers );

	wp_safe_redirect( add_query_arg( 'contact', $sent ? 'success' : 'error', $redirect ) );
	exit;
}
add_action( 'admin_post_nopriv_gg_contact', 'gg_handle_contact' );
add_action( 'admin_post_gg_contact', 'gg_handle_contact' );
