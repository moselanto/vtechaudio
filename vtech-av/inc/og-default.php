<?php
/**
 * Default social share image (v5.57.1).
 *
 * Once, when an administrator opens wp-admin, copies the theme's branded
 * 1200x630 share image (assets/img/og-default.jpg) into the Media Library and
 * sets it as Rank Math > Titles & Meta > Global Meta > OpenGraph Thumbnail.
 * Pages and posts with their own image keep using it. To change the default
 * later, pick another image in Rank Math; this never runs again.
 *
 * @package VTECH_AV
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

add_action( 'admin_init', function () {
	if ( get_option( 'vtech_og_default_set' ) || ! current_user_can( 'manage_options' ) ) { return; }
	$src = VTECH_DIR . '/assets/img/og-default.jpg';
	if ( ! file_exists( $src ) ) { return; }

	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';

	$tmp = wp_tempnam( 'vtech-og-default.jpg' );
	if ( ! $tmp || ! copy( $src, $tmp ) ) { return; }
	$id = media_handle_sideload(
		array( 'name' => 'vtech-audio-visual-share.jpg', 'tmp_name' => $tmp ),
		0,
		'VTECH Audio Visual Solutions - Kenya audio visual company'
	);
	if ( is_wp_error( $id ) ) { @unlink( $tmp ); return; }
	update_post_meta( $id, '_wp_attachment_image_alt', 'VTECH Audio Visual Solutions - sound, LED screens and conference AV in Kenya' );

	$url  = wp_get_attachment_url( $id );
	$opts = get_option( 'rank-math-options-titles', array() );
	if ( ! is_array( $opts ) ) { $opts = array(); }
	$opts['open_graph_image']    = $url;
	$opts['open_graph_image_id'] = $id;
	update_option( 'rank-math-options-titles', $opts );
	update_option( 'vtech_og_default_set', $id, false );
} );
