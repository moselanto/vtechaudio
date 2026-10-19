<?php
/**
 * Equipment Hire Catalog + quote request 
 *
 * The `equipment` CPT (see post-types.php) + ACF fields (daily_rate,
 * availability, specs) power a real, browsable catalog with filters today.
 * What is stubbed: real-time availability (calendar-based booking) and the
 * cart/quote-basket persistence.
 *
 * Status: live. Availability and bookings are handled by inventory.php and
 * accept-booking.php; this file keeps the session quote basket and the
 * availability badge used by templates.
 *
 * @package VTECH_AV
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

/** Add-to-quote AJAX (session-based basket). */
add_action( 'wp_ajax_vtech_add_quote', 'vtech_add_quote' );
add_action( 'wp_ajax_nopriv_vtech_add_quote', 'vtech_add_quote' );
function vtech_add_quote() {
	check_ajax_referer( 'vtech_nonce', 'nonce' );
	// v5.61.0: session cookie is HttpOnly, SameSite=Lax and Secure on HTTPS,
	// and only real published equipment items can be added.
	if ( ! session_id() && ! headers_sent() ) {
		session_set_cookie_params( array( 'lifetime' => 0, 'path' => '/', 'secure' => is_ssl(), 'httponly' => true, 'samesite' => 'Lax' ) );
		@session_start(); // phpcs:ignore
	}
	$id = absint( $_POST['id'] ?? 0 );
	if ( ! $id || 'equipment' !== get_post_type( $id ) || 'publish' !== get_post_status( $id ) ) { wp_send_json_error(); }
	if ( ! isset( $_SESSION ) ) { wp_send_json_error(); }
	$_SESSION['vtech_quote'] = array_slice( (array) ( $_SESSION['vtech_quote'] ?? array() ), 0, 49 );
	$_SESSION['vtech_quote'] = array_unique( array_merge( $_SESSION['vtech_quote'] ?? array(), array( $id ) ) );
	wp_send_json_success( array( 'count' => count( $_SESSION['vtech_quote'] ) ) );
}

/** Availability badge helper for templates. */
function vtech_availability_badge( $post_id ) {
	$status = function_exists( 'get_field' ) ? get_field( 'availability', $post_id ) : 'available';
	$labels = array( 'available' => 'Available', 'limited' => 'Limited stock', 'booked' => 'Fully booked' );
	$label  = $labels[ $status ] ?? 'Available';
	return '<span class="badge badge--' . esc_attr( $status ) . '">' . esc_html( $label ) . '</span>';
}
