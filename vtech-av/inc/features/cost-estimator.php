<?php
/**
 * Project Cost Estimator.
 *
 * AJAX endpoint (action vtech_estimate, nonce vtech_nonce) used by the
 * front-end estimator widget.
 *
 * Status: live. Always routes visitors to a free site survey because VTECH
 * does not publish fixed prices. Extension point: return real ranges from
 * vtech_estimator_rate_card() once an approved rate card exists.
 *
 * @package VTECH_AV
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

/** Rate card intentionally empty — VTECH never publishes fabricated prices. */
function vtech_estimator_rate_card() {
	// No fabricated pricing. Empty until a real rate card is provided; the
	// endpoint returns a "book a survey" message instead of invented numbers.
	return array();
}

add_action( 'wp_ajax_vtech_estimate', 'vtech_handle_estimate' );
add_action( 'wp_ajax_nopriv_vtech_estimate', 'vtech_handle_estimate' );
function vtech_handle_estimate() {
	check_ajax_referer( 'vtech_nonce', 'nonce' );
	// No fabricated pricing is ever returned — always route to a real quote.
	wp_send_json_success( array(
		'currency' => 'KES',
		'note'     => 'For accurate pricing, book a free site survey — we quote within 24 hours.',
	) );
}
