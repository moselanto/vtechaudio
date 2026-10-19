<?php
/**
 * Production security controls (v5.61.0).
 *
 * Adds the controls that were missing after the 19 Oct 2026 security review.
 * Everything here is additive: no existing feature, form, URL or workflow is
 * removed. See SECURITY-AUDIT.md (repository root) for the evidence behind
 * each control.
 *
 *  1. Rate limiting      vtech_rate_limited() helper used by every public
 *                        form, AJAX and REST entry point.
 *  2. Login protection   5 failed sign-ins per IP + username in 15 minutes
 *                        locks that pair for 15 minutes.
 *  3. Session expiry     sign-ins expire after 12 hours (7 days when
 *                        "Remember me" is ticked) instead of 2 / 14 days.
 *  4. Password policy    minimum 12 characters on profile save and reset
 *                        (WordPress itself hashes every password).
 *  5. CORS               the theme's own REST routes (vtech/v1) only answer
 *                        browsers on this site's origin.
 *  6. Private documents  quote / invoice / acceptance pages are never
 *                        cached or indexed.
 *  7. Extra headers      Cross-Origin-Opener-Policy and
 *                        X-Permitted-Cross-Domain-Policies.
 *
 * @package VTECH_AV
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

/* -------------------------------------------------------------------------
 * 1. Rate limiting
 * ---------------------------------------------------------------------- */

/**
 * Visitor IP used only as a rate-limit key (stored hashed, never in clear).
 * REMOTE_ADDR is used on purpose: forwarded headers can be forged.
 */
function vtech_client_ip() {
	$ip = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';
	return filter_var( $ip, FILTER_VALIDATE_IP ) ? $ip : '0.0.0.0';
}

/**
 * Count one hit for $bucket and report whether the visitor is over the limit.
 *
 * @param string $bucket Short name of the protected action, e.g. 'form'.
 * @param int    $max    Allowed hits inside the window.
 * @param int    $window Window length in seconds.
 * @return bool True when the request must be refused.
 */
function vtech_rate_limited( $bucket, $max, $window ) {
	if ( current_user_can( 'edit_posts' ) ) { return false; } // Staff are never throttled.
	$max    = (int) apply_filters( 'vtech_rate_limit_max', $max, $bucket );
	$key    = 'vtech_rl_' . sanitize_key( $bucket ) . '_' . md5( vtech_client_ip() . wp_salt( 'nonce' ) );
	$hits   = (int) get_transient( $key );
	if ( $hits >= $max ) { return true; }
	set_transient( $key, $hits + 1, $window );
	return false;
}

/** Standard refusal for JSON endpoints. */
function vtech_rate_limit_json() {
	wp_send_json_error( array( 'message' => 'Too many requests. Please wait a few minutes, or call or WhatsApp us directly.' ), 429 );
}

// AJAX lead forms: 6 submissions per 10 minutes per visitor.
add_action( 'init', function () {
	if ( ! ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) { return; }
	$action = isset( $_REQUEST['action'] ) ? sanitize_key( wp_unslash( $_REQUEST['action'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification
	$limits = array(
		'vtech_consultation'       => array( 'lead', 6, 10 * MINUTE_IN_SECONDS ),
		'vtech_hire_request'       => array( 'lead', 6, 10 * MINUTE_IN_SECONDS ),
		'vtech_quote'              => array( 'lead', 6, 10 * MINUTE_IN_SECONDS ),
		'vtech_estimate'           => array( 'tool', 30, 10 * MINUTE_IN_SECONDS ),
		'vtech_add_quote'          => array( 'tool', 30, 10 * MINUTE_IN_SECONDS ),
		'vtech_check_availability' => array( 'tool', 30, 10 * MINUTE_IN_SECONDS ),
	);
	if ( isset( $limits[ $action ] ) && vtech_rate_limited( $limits[ $action ][0], $limits[ $action ][1], $limits[ $action ][2] ) ) {
		vtech_rate_limit_json();
	}
}, 1 );

/* -------------------------------------------------------------------------
 * 2. Login protection (brute force)
 * ---------------------------------------------------------------------- */

function vtech_login_key( $username ) {
	return 'vtech_login_' . md5( strtolower( (string) $username ) . '|' . vtech_client_ip() . wp_salt( 'auth' ) );
}

add_filter( 'authenticate', function ( $user, $username ) {
	if ( '' === (string) $username ) { return $user; }
	if ( (int) get_transient( vtech_login_key( $username ) ) >= 5 ) {
		return new WP_Error( 'vtech_locked', __( 'Too many failed sign-in attempts. Please wait 15 minutes and try again.', 'vtech-av' ) );
	}
	return $user;
}, 30, 2 );

add_action( 'wp_login_failed', function ( $username ) {
	$key = vtech_login_key( $username );
	set_transient( $key, (int) get_transient( $key ) + 1, 15 * MINUTE_IN_SECONDS );
} );

add_action( 'wp_login', function ( $username ) {
	delete_transient( vtech_login_key( $username ) );
} );

/* -------------------------------------------------------------------------
 * 3. Session expiry
 * ---------------------------------------------------------------------- */

add_filter( 'auth_cookie_expiration', function ( $length, $user_id, $remember ) {
	return $remember ? 7 * DAY_IN_SECONDS : 12 * HOUR_IN_SECONDS;
}, 10, 3 );

/* -------------------------------------------------------------------------
 * 4. Password policy (hashing itself is done by WordPress core)
 * ---------------------------------------------------------------------- */

function vtech_password_too_short( $pass ) {
	return '' !== (string) $pass && strlen( (string) $pass ) < 12;
}

add_action( 'user_profile_update_errors', function ( $errors ) {
	$pass = isset( $_POST['pass1'] ) ? (string) wp_unslash( $_POST['pass1'] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification
	if ( vtech_password_too_short( $pass ) ) {
		$errors->add( 'vtech_pass_length', __( 'Passwords must be at least 12 characters long.', 'vtech-av' ) );
	}
} );

add_action( 'validate_password_reset', function ( $errors ) {
	$pass = isset( $_POST['pass1'] ) ? (string) wp_unslash( $_POST['pass1'] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification
	if ( vtech_password_too_short( $pass ) ) {
		$errors->add( 'vtech_pass_length', __( 'Passwords must be at least 12 characters long.', 'vtech-av' ) );
	}
} );

/* -------------------------------------------------------------------------
 * 5. CORS: theme REST routes answer this site only
 * ---------------------------------------------------------------------- */

add_filter( 'rest_pre_serve_request', function ( $served, $result, $request ) {
	if ( 0 !== strpos( (string) $request->get_route(), '/vtech/v1/' ) || headers_sent() ) { return $served; }
	$origin = get_http_origin();
	$home   = untrailingslashit( home_url() );
	header_remove( 'Access-Control-Allow-Origin' );
	header_remove( 'Access-Control-Allow-Credentials' );
	if ( $origin && untrailingslashit( $origin ) === $home ) {
		header( 'Access-Control-Allow-Origin: ' . esc_url_raw( $home ) );
		header( 'Vary: Origin', false );
	}
	return $served;
}, 20, 3 );

/* -------------------------------------------------------------------------
 * 6. Private documents: never cached, never indexed
 * ---------------------------------------------------------------------- */

add_action( 'template_redirect', function () {
	if ( empty( $_GET['vtech_doc'] ) && empty( $_GET['vtech_accept'] ) ) { return; } // phpcs:ignore WordPress.Security.NonceVerification
	if ( headers_sent() ) { return; }
	nocache_headers();
	header( 'X-Robots-Tag: noindex, nofollow, noarchive' );
	header( 'Referrer-Policy: no-referrer' );
}, 0 );

/* -------------------------------------------------------------------------
 * 7. Extra response headers (added through WordPress, never raw output)
 * ---------------------------------------------------------------------- */

add_filter( 'wp_headers', function ( $headers ) {
	$headers['Cross-Origin-Opener-Policy']        = 'same-origin-allow-popups';
	$headers['X-Permitted-Cross-Domain-Policies'] = 'none';
	return $headers;
}, 20 );
