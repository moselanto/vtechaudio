<?php
/**
 * Client Portal route.
 *
 * A real client portal (authenticated quote tracking + project status) is an
 * application feature beyond a theme. This file:
 *  - Registers a `/client-portal/` page endpoint.
 *  - Gates it behind login and provides a template hook.
 *  - Defines the intended data model in comments.
 *
 * Status: login-gated route only. Without templates/client-portal.php the
 * route sends signed-in visitors to the consultation page. A full portal
 * (client role, quote tracking, secure downloads) belongs in a companion plugin.
 *
 * @package VTECH_AV
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

add_action( 'init', function () {
	add_rewrite_rule( '^client-portal/?$', 'index.php?vtech_portal=1', 'top' );
} );
add_filter( 'query_vars', function ( $vars ) { $vars[] = 'vtech_portal'; return $vars; } );

add_action( 'template_redirect', function () {
	if ( ! get_query_var( 'vtech_portal' ) ) { return; }
	if ( ! is_user_logged_in() ) {
		wp_safe_redirect( wp_login_url( home_url( '/client-portal/' ) ) );
		exit;
	}
	// Load a dedicated template if present.
	$tpl = locate_template( 'templates/client-portal.php' );
	if ( $tpl ) { include $tpl; exit; }
	// No portal template yet — send the visitor somewhere useful, never a raw error.
	wp_safe_redirect( home_url( '/book-a-consultation/' ) );
	exit;
} );
