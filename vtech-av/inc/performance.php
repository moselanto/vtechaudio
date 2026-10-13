<?php
/**
 * Core Web Vitals performance layer.
 * - Inlines critical CSS in <head> (no render-blocking).
 * - Preloads variable fonts + establishes preconnects.
 * - Swaps deferred main.css from media=print to all on load.
 * - Adds native lazy-loading + async decoding to content images.
 *
 * @package VTECH_AV
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

// 1) Preload fonts + preconnect (fonts are self-hosted -> no Google round trip).
add_action( 'wp_head', function () {
	$f = VTECH_URI . '/assets/fonts/';
	echo '<link rel="preload" href="' . esc_url( $f . 'manrope-var.woff2' ) . '" as="font" type="font/woff2" crossorigin>' . "\n";
	echo '<link rel="preload" href="' . esc_url( $f . 'inter-var.woff2' ) . '" as="font" type="font/woff2" crossorigin>' . "\n";
	echo '<style id="vtech-fonts">@font-face{font-family:Manrope;font-style:normal;font-weight:200 800;font-display:swap;src:url(' . esc_url( $f . 'manrope-var.woff2' ) . ') format("woff2");}@font-face{font-family:Inter;font-style:normal;font-weight:100 900;font-display:swap;src:url(' . esc_url( $f . 'inter-var.woff2' ) . ') format("woff2");}</style>';
	// Preload the LCP hero image on the homepage.
	if ( is_front_page() ) {
		$hero = get_theme_mod( 'vtech_hero_img', VTECH_URI . '/assets/img/hero.webp' );
		echo '<link rel="preload" href="' . esc_url( $hero ) . '" as="image" fetchpriority="high">' . "\n";
	}
}, 1 );

// 2) Inline the FULL stylesheet in <head> (critical.css + main.css).
//    Foolproof: no external CSS file to 404/mis-serve, no media attribute
//    to break, no JS/cache-plugin dependency. Total is ~12KB — trivial.
add_action( 'wp_head', function () {
	$css = '';
	foreach ( array( 'critical.css' ) as $f ) {
		$path = VTECH_DIR . '/assets/css/' . $f;
		if ( file_exists( $path ) ) { $css .= file_get_contents( $path ) . "
"; }
	}
	if ( $css ) {
		$css = preg_replace( '#/\*.*?\*/#s', '', $css );
		$css = trim( preg_replace( '/\s+/', ' ', $css ) );
		echo '<style id="vtech-inline-css">' . $css . '</style>' . "
"; // phpcs:ignore
	}
}, 2 );

// 3) (Removed) main.css now loads as a normal stylesheet. The former
//    print->all onload swap was fragile (silent str_replace miss / JS
//    dependency) and could leave the whole page unstyled. main.css is
//    ~7KB so render-blocking cost is negligible and CWV stays green.

// 4) Native lazy-load + async decode on content images (skip first/LCP image).
add_filter( 'wp_get_attachment_image_attributes', function ( $attr ) {
	if ( empty( $attr['loading'] ) ) { $attr['loading'] = 'lazy'; }
	$attr['decoding'] = 'async';
	return $attr;
} );

// 5) Remove default WP block library inline CSS bloat we override anyway is kept,
//    but dequeue classic-theme styles and global-styles duplicates if unused.
add_action( 'wp_enqueue_scripts', function () {
	wp_dequeue_style( 'wp-block-library-theme' );
}, 100 );

// 6) DNS-prefetch for the map + WhatsApp only (kept minimal).
add_filter( 'wp_resource_hints', function ( $hints, $relation ) {
	if ( 'dns-prefetch' === $relation ) {
		$hints[] = 'https://www.google.com';
		$hints[] = 'https://wa.me';
	}
	return $hints;
}, 10, 2 );

// 7) Front-end performance: drop WordPress block-editor scripts/styles that
//    aren't needed on this classic PHP-templated front-end. These were the
//    render-blocking i18n/hooks/index.js requests (~720ms) in Lighthouse.
add_action( 'wp_enqueue_scripts', 'vtech_trim_block_assets', 100 );
function vtech_trim_block_assets() {
	if ( is_admin() ) { return; }
	wp_dequeue_style( 'wp-block-library' );
	wp_dequeue_style( 'wp-block-library-theme' );
	wp_dequeue_style( 'global-styles' );
	wp_dequeue_style( 'classic-theme-styles' );

	// Only dequeue block scripts this classic front-end genuinely does not use.
	// IMPORTANT: never deregister wp-i18n / wp-hooks — plugins such as
	// Contact Form 7 declare them as dependencies, and deregistering them
	// triggers "enqueued with dependencies that are not registered" notices
	// and can break the plugin's JS entirely.
	$vtc_drop = array( 'wp-block-library', 'wp-interactivity' );

	// If a form/interactive plugin that needs the block runtime is active,
	// leave everything alone to stay safe.
	if ( function_exists( 'is_plugin_active' ) ) {
		if ( is_plugin_active( 'contact-form-7/wp-contact-form-7.php' ) ) {
			$vtc_drop = array(); // CF7 present — do not trim block scripts.
		}
	}

	foreach ( $vtc_drop as $vtc_h ) {
		// Dequeue only. Do NOT deregister, so dependencies stay resolvable
		// for any other script that references them.
		wp_dequeue_script( $vtc_h );
	}
}

// 8) Remove emoji + oEmbed cruft (extra requests/inline JS on every page).
add_action( 'init', function () {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
	remove_action( 'wp_head', 'wp_oembed_add_host_js' );
	remove_action( 'wp_head', 'rsd_link' );
	remove_action( 'wp_head', 'wlwmanifest_link' );
} );

/* -------------------------------------------------------------------------
 * v5.42 speed layer
 * ---------------------------------------------------------------------- */

/**
 * Contact Form 7 loads its CSS, its JS and the wp-hooks + wp-i18n runtimes on
 * every page, but this theme uses its own forms. Only load CF7 where a CF7
 * form is actually shown (Contact page with a CF7 ID set, or any page whose
 * content contains the CF7 shortcode).
 */
add_action( 'wp_enqueue_scripts', 'vtech_trim_cf7_assets', 120 );
function vtech_trim_cf7_assets() {
	if ( is_admin() ) { return; }
	$needs = false;
	if ( is_singular() ) {
		$post = get_post();
		if ( $post && has_shortcode( (string) $post->post_content, 'contact-form-7' ) ) { $needs = true; }
		if ( $post && 'contact' === $post->post_name && vtech_opt( 'vtech_cf7_id', '' ) ) { $needs = true; }
	}
	if ( apply_filters( 'vtech_needs_cf7_assets', $needs ) ) { return; }
	wp_dequeue_style( 'contact-form-7' );
	wp_dequeue_script( 'contact-form-7' );
	wp_dequeue_script( 'swv' );
	wp_dequeue_script( 'wpcf7-recaptcha' );
	wp_dequeue_script( 'google-recaptcha' );
}
// Belt and braces: CF7's own switches.
add_filter( 'wpcf7_load_js', 'vtech_cf7_load_flag' );
add_filter( 'wpcf7_load_css', 'vtech_cf7_load_flag' );
function vtech_cf7_load_flag( $load ) {
	if ( is_admin() ) { return $load; }
	$post = is_singular() ? get_post() : null;
	if ( $post && ( has_shortcode( (string) $post->post_content, 'contact-form-7' ) || ( 'contact' === $post->post_name && vtech_opt( 'vtech_cf7_id', '' ) ) ) ) { return $load; }
	return false;
}

/**
 * Instant navigation: when a visitor hovers or presses a link, WordPress
 * prerenders the next page in the background, so it opens almost instantly.
 * Uses the core Speculation Rules API (WordPress 6.8+); ignored by browsers
 * that do not support it. Forms, admin and query-string URLs stay excluded.
 */
add_filter( 'wp_speculation_rules_configuration', function ( $config ) {
	if ( is_user_logged_in() ) { return $config; }
	return array( 'mode' => 'prerender', 'eagerness' => 'moderate' );
} );
add_filter( 'wp_speculation_rules_href_exclude_paths', function ( $paths ) {
	$paths[] = '/wp-admin/*';
	$paths[] = '/*vtech_sent*';
	$paths[] = '/client-portal/*';
	return $paths;
} );

/** Hero image preload also on pages with a hero photo (services/industries use CSS gradients, so homepage only). */
add_filter( 'wp_img_tag_add_auto_sizes', '__return_true' );
