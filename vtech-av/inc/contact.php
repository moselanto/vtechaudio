<?php
/**
 * Company contact details — SINGLE SOURCE OF TRUTH.
 *
 * Everything on the site (header, footer, floating buttons, contact page,
 * schema, emails, quotes/invoices AND the text inside ordinary WordPress
 * pages such as Privacy Policy and Terms) reads its phone / WhatsApp /
 * email / address from here.
 *
 * Edit the values ONCE in:  Appearance > Customize > VTECH Theme Options >
 * Company Details.  When you save, the new values are pushed into existing
 * page content too, so nothing is left showing an old number.
 *
 * Templates should never hardcode a phone number, email or address again:
 *   vtech_contact( 'phone' )      - display value, e.g. +254 182 942 946
 *   vtech_contact_tel()           - href value for tel: links
 *   vtech_contact_wa()            - WhatsApp digits, e.g. 254182942946
 *   vtech_contact_wa_url( $text ) - full https://wa.me/... click-to-chat URL
 *   vtech_contact( 'email' )      - info@vtechaudio.co.ke
 *   vtech_contact( 'address' )    - full postal address
 *
 * In page/post content use the shortcodes:
 *   [vtech_phone] [vtech_phone_link] [vtech_email] [vtech_email_link]
 *   [vtech_whatsapp] [vtech_whatsapp_link] [vtech_address] [vtech_hours]
 *   [vtech_company] [vtech_map]
 *
 * @package VTECH_AV
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Canonical fallbacks. These are ONLY used before anything is saved in the
 * Customizer. They are also treated as "legacy" strings, so any page still
 * carrying one of them is corrected automatically once the Customizer value
 * differs.
 *
 * @return array
 */
function vtech_contact_defaults() {
	return array(
		'company'   => 'VTECH Audio Visual Solutions',
		'phone'     => '+254 182 942 946',
		'whatsapp'  => '254182942946',
		'email'     => 'info@vtechaudio.co.ke',
		'address'   => 'Mpaka Plaza, Mpaka Road, Westlands',
		'locality'  => 'Nairobi',
		'region'    => 'Nairobi County',
		'postal'    => '00800',
		'country'   => 'KE',
		'hours'     => 'Mon-Fri 8:00 AM - 6:00 PM, Sat 8:00 AM - 6:30 PM',
		'hours_schema' => 'Mo-Fr 08:00-18:00, Sa 08:00-18:30',
		'map_embed' => '',
		'geo_lat'   => '-1.2648485',
		'geo_lng'   => '36.8051541',
	);
}

/**
 * The keys that are pushed into existing page content when they change.
 *
 * @return array
 */
function vtech_contact_synced_keys() {
	return array( 'phone', 'whatsapp', 'email', 'address', 'hours', 'company' );
}

/**
 * Read one contact value.
 *
 * @param string $key      Key from vtech_contact_defaults(), e.g. 'phone'.
 * @param string $fallback Optional override for the built-in default.
 * @return string
 */
function vtech_contact( $key, $fallback = null ) {
	$defaults = vtech_contact_defaults();
	$default  = ( null !== $fallback ) ? $fallback : ( isset( $defaults[ $key ] ) ? $defaults[ $key ] : '' );
	$value    = get_theme_mod( 'vtech_' . $key, $default );
	if ( '' === $value || null === $value ) { $value = $default; }
	/**
	 * Filter a single company contact value.
	 *
	 * @param string $value Resolved value.
	 * @param string $key   Contact key.
	 */
	return (string) apply_filters( 'vtech_contact', $value, $key );
}

/** Phone number stripped to a dialable href value, e.g. +254182942946. */
function vtech_contact_tel() {
	$raw = vtech_contact( 'phone' );
	$tel = preg_replace( '/[^\d+]/', '', $raw );
	return $tel;
}

/** WhatsApp number as digits only, e.g. 254182942946. */
function vtech_contact_wa() {
	$wa = preg_replace( '/\D+/', '', (string) vtech_contact( 'whatsapp' ) );
	if ( ! $wa ) {
		// Fall back to the phone number so the button is never dead.
		$wa = preg_replace( '/\D+/', '', (string) vtech_contact( 'phone' ) );
	}
	return $wa;
}

/**
 * Full WhatsApp click-to-chat URL.
 *
 * @param string $text Optional pre-filled message.
 * @return string
 */
function vtech_contact_wa_url( $text = '' ) {
	$wa = vtech_contact_wa();
	if ( ! $wa ) { return ''; }
	$url = 'https://wa.me/' . $wa;
	if ( $text ) { $url .= '?text=' . rawurlencode( $text ); }
	return $url;
}

/** Google Maps embed URL — explicit value if set, otherwise built from the address. */
function vtech_contact_map_embed() {
	$map = vtech_contact( 'map_embed' );
	if ( $map ) { return $map; }
	$q = trim( vtech_contact( 'company' ) . ', ' . vtech_contact( 'address' ) );
	return 'https://www.google.com/maps?q=' . rawurlencode( $q ) . '&output=embed';
}

/** Ready-made markup helpers. */
function vtech_contact_phone_link( $class = '' ) {
	return sprintf(
		'<a class="%s" href="tel:%s">%s</a>',
		esc_attr( $class ),
		esc_attr( vtech_contact_tel() ),
		esc_html( vtech_contact( 'phone' ) )
	);
}

function vtech_contact_email_link( $class = '' ) {
	$email = vtech_contact( 'email' );
	return sprintf(
		'<a class="%s" href="mailto:%s">%s</a>',
		esc_attr( $class ),
		esc_attr( $email ),
		esc_html( $email )
	);
}

function vtech_contact_wa_link( $label = '', $class = 'btn btn--wa', $text = '' ) {
	$url = vtech_contact_wa_url( $text );
	if ( ! $url ) { return ''; }
	if ( ! $label ) { $label = __( 'Chat on WhatsApp', 'vtech-av' ); }
	return sprintf(
		'<a class="%s" href="%s" target="_blank" rel="noopener">%s</a>',
		esc_attr( $class ),
		esc_url( $url ),
		esc_html( $label )
	);
}

/* -------------------------------------------------------------------------
 * Shortcodes — so page/post content can stay in sync too.
 * ---------------------------------------------------------------------- */

add_action( 'init', function () {
	$map = array(
		'vtech_phone'         => function () { return esc_html( vtech_contact( 'phone' ) ); },
		'vtech_phone_link'    => function ( $atts ) { $a = shortcode_atts( array( 'class' => '' ), $atts ); return vtech_contact_phone_link( $a['class'] ); },
		'vtech_whatsapp'      => function () { return esc_html( vtech_contact_wa() ); },
		'vtech_whatsapp_link' => function ( $atts ) {
			$a = shortcode_atts( array( 'class' => 'btn btn--wa', 'label' => '', 'text' => '' ), $atts );
			return vtech_contact_wa_link( $a['label'], $a['class'], $a['text'] );
		},
		'vtech_email'         => function () { return esc_html( vtech_contact( 'email' ) ); },
		'vtech_email_link'    => function ( $atts ) { $a = shortcode_atts( array( 'class' => '' ), $atts ); return vtech_contact_email_link( $a['class'] ); },
		'vtech_address'       => function () { return esc_html( vtech_contact( 'address' ) ); },
		'vtech_hours'         => function () { return esc_html( vtech_contact( 'hours' ) ); },
		'vtech_company'       => function () { return esc_html( vtech_contact( 'company' ) ); },
		'vtech_map'           => function () {
			$src = vtech_contact_map_embed();
			if ( ! $src ) { return ''; }
			return '<iframe title="' . esc_attr__( 'Office location map', 'vtech-av' ) . '" src="' . esc_url( $src ) . '" loading="lazy" referrerpolicy="no-referrer-when-downgrade" style="width:100%;height:320px;border:0"></iframe>';
		},
	);
	foreach ( $map as $tag => $cb ) { add_shortcode( $tag, $cb ); }
} );

// Let shortcodes work inside widgets and excerpts as well as content.
add_filter( 'widget_text', 'do_shortcode' );

/* -------------------------------------------------------------------------
 * Keeping existing page content in sync.
 *
 * Theme templates always read live values, but text typed into a page (the
 * Privacy Policy, Terms, an About paragraph) is stored in the database and
 * cannot follow the Customizer on its own. Two mechanisms fix that:
 *
 *   1. On Customizer save, old values are search-replaced across post
 *      content, excerpts and non-serialised post meta.
 *   2. A display-time filter rewrites any leftover legacy value, so even
 *      content that was missed (or restored from a backup) renders correctly.
 * ---------------------------------------------------------------------- */

/** Current values for the synced keys. */
function vtech_contact_snapshot_values() {
	$out = array();
	foreach ( vtech_contact_synced_keys() as $key ) { $out[ $key ] = vtech_contact( $key ); }
	return $out;
}

/**
 * All searchable string variants of a value, so "+254 182 942 946",
 * "+254182942946" and "254182942946" are all caught.
 *
 * @param string $key   Contact key.
 * @param string $value Value.
 * @return array variant => type
 */
function vtech_contact_variants( $key, $value ) {
	$value = (string) $value;
	if ( '' === trim( $value ) ) { return array(); }
	$variants = array( $value => 'raw' );
	if ( in_array( $key, array( 'phone', 'whatsapp' ), true ) ) {
		$compact = preg_replace( '/[^\d+]/', '', $value );
		$digits  = preg_replace( '/\D+/', '', $value );
		if ( $compact && $compact !== $value ) { $variants[ $compact ] = 'compact'; }
		if ( $digits && ! isset( $variants[ $digits ] ) ) { $variants[ $digits ] = 'digits'; }
	}
	return $variants;
}

/**
 * Turn a current value into the matching variant form.
 *
 * @param string $value Current value.
 * @param string $type  raw|compact|digits.
 * @return string
 */
function vtech_contact_variant_value( $value, $type ) {
	switch ( $type ) {
		case 'compact':
			return preg_replace( '/[^\d+]/', '', (string) $value );
		case 'digits':
			return preg_replace( '/\D+/', '', (string) $value );
	}
	return (string) $value;
}

/** Legacy strings that should be rewritten to the current value. */
function vtech_contact_legacy_map() {
	$legacy = get_option( 'vtech_contact_legacy', array() );
	if ( ! is_array( $legacy ) ) { $legacy = array(); }

	// The theme's original hardcoded values are always treated as legacy, so
	// pages written before centralisation self-correct.
	$defaults = vtech_contact_defaults();
	foreach ( vtech_contact_synced_keys() as $key ) {
		if ( empty( $defaults[ $key ] ) ) { continue; }
		foreach ( vtech_contact_variants( $key, $defaults[ $key ] ) as $old => $type ) {
			if ( ! isset( $legacy[ $old ] ) ) { $legacy[ $old ] = array( 'key' => $key, 'type' => $type ); }
		}
	}

	// Numbers the business no longer uses are always rewritten too.
	foreach ( vtech_contact_retired_values() as $key => $values ) {
		foreach ( $values as $value ) {
			foreach ( vtech_contact_variants( $key, $value ) as $old => $type ) {
				if ( ! isset( $legacy[ $old ] ) ) { $legacy[ $old ] = array( 'key' => $key, 'type' => $type ); }
			}
		}
	}
	return $legacy;
}

/**
 * Contact values the business has retired. Any page, excerpt or meta field
 * still carrying one of these is rewritten to the current value.
 *
 * @return array key => list of old values
 */
function vtech_contact_retired_values() {
	return array(
		'phone'    => array( '+254 728 135 246', '+254 728135246', '+254 728 135246', '0728 135 246', '0728135246' ),
		'whatsapp' => array( '254728135246' ),
		'address'  => array(
			'Ground Floor, Mpaka Plaza, Mpaka Road, Westlands, Nairobi, P.O. Box 66734-00800',
			'Ground Floor, Mpaka Plaza, Mpaka Road, Westlands, Nairobi · P.O. Box 66734-00800',
			'Ground Floor, Mpaka Plaza, Mpaka Road, Westlands, Nairobi',
			'Ground Floor, Mpaka Plaza, Mpaka Road, Westlands',
			'Mpaka Plaza, Mpaka Road, Westlands, Nairobi, Kenya',
			'Mpaka Plaza, Mpaka Road, Westlands, Nairobi',
			'Mpaka Plaza, Mpaka Road, Nairobi, Kenya',
			'Mpaka Plaza, Mpaka Road, Nairobi',
			'Mpaka Plaza in Westlands, Nairobi',
			'Mpaka Plaza, Westlands, Nairobi',
			'Mpaka Plaza, Westlands',
		),
	);
}

/**
 * One-time migration (Oct 2026): the official phone/WhatsApp number changed
 * from +254 728 135 246 to +254 182 942 946. Updates the saved Customizer
 * values (which override the defaults above), rewrites the old number across
 * existing content and refreshes the sync snapshot. Runs once per site.
 */
function vtech_contact_migrate_phone_2026_10() {
	if ( '2026-10-phone' === get_option( 'vtech_contact_migration' ) ) { return; }

	$defaults = vtech_contact_defaults();
	$retired  = vtech_contact_retired_values();
	$pairs    = array();

	foreach ( array( 'phone', 'whatsapp' ) as $key ) {
		$olds  = $retired[ $key ];
		$saved = (string) get_theme_mod( 'vtech_' . $key, '' );
		if ( '' !== trim( $saved ) && $saved !== $defaults[ $key ] ) { $olds[] = $saved; }

		set_theme_mod( 'vtech_' . $key, $defaults[ $key ] );

		foreach ( $olds as $old_value ) {
			foreach ( vtech_contact_variants( $key, $old_value ) as $old => $type ) {
				$new = vtech_contact_variant_value( $defaults[ $key ], $type );
				if ( $old !== $new ) { $pairs[ $old ] = $new; }
			}
		}
	}

	if ( $pairs ) { vtech_contact_propagate( $pairs ); }
	update_option( 'vtech_contact_snapshot', vtech_contact_snapshot_values(), false );
	update_option( 'vtech_contact_migration', '2026-10-phone', true );
}
add_action( 'init', 'vtech_contact_migrate_phone_2026_10', 5 );

/**
 * The business no longer publishes a P.O. Box (removed Nov 2026, owner request).
 * Returns the address with any "P.O. Box ..." part stripped, tidying the
 * leftover commas and spaces.
 *
 * @param string $value Address text.
 * @return string
 */
function vtech_contact_strip_po_box( $value ) {
	$value = (string) $value;
	// No P.O. Box (owner request, Nov 2026).
	$value = preg_replace( '/[,\x{00B7}]?\s*P\.?\s*O\.?\s*Box\s*[\d\s\-]+/iu', '', $value );
	// Owner wants the address shown as "Mpaka Plaza, Mpaka Road, Westlands":
	// drop a leading "Ground Floor," and a trailing ", Nairobi".
	$value = preg_replace( '/^\s*Ground Floor\s*,\s*/i', '', $value );
	$value = preg_replace( '/\s*,\s*Nairobi\s*$/i', '', trim( $value, " \t\n\r\0\x0B," ) );
	$value = preg_replace( '/\s*,\s*,/', ',', $value );
	return trim( $value, " \t\n\r\0\x0B," );
}

/** Never output a P.O. Box in the address, whatever is saved in the Customizer. */
add_filter( 'vtech_contact', function ( $value, $key ) {
	return ( 'address' === $key ) ? vtech_contact_strip_po_box( $value ) : $value;
}, 99, 2 );
add_filter( 'theme_mod_vtech_address', 'vtech_contact_strip_po_box', 99 );

/**
 * One-time migration (Nov 2026): remove "P.O. Box 66734-00800" everywhere.
 * Cleans the saved Customizer address and strips the box number from existing
 * post content, excerpts and non-serialised post meta. Runs once per site.
 */
function vtech_contact_migrate_po_box_2026_11() {
	if ( get_option( 'vtech_contact_po_box_removed' ) ) { return; }
	global $wpdb;

	$saved = (string) get_theme_mod( 'vtech_address', '' );
	if ( '' !== trim( $saved ) ) {
		$clean = vtech_contact_strip_po_box( $saved );
		if ( $clean !== $saved && '' !== $clean ) { set_theme_mod( 'vtech_address', $clean ); }
	}

	$needles = array( ', P.O. Box 66734-00800', ' P.O. Box 66734-00800', 'P.O. Box 66734-00800', ', P.O Box 66734-00800', ', PO Box 66734-00800', 'P.O Box 66734-00800', 'PO Box 66734-00800' );
	$touched = 0;
	foreach ( $needles as $old ) {
		$like = '%' . $wpdb->esc_like( $old ) . '%';
		$touched += (int) $wpdb->query( $wpdb->prepare(
			"UPDATE {$wpdb->posts} SET post_content = REPLACE(post_content, %s, '')
			 WHERE post_type NOT IN ('revision') AND post_content LIKE %s",
			$old, $like
		) );
		$touched += (int) $wpdb->query( $wpdb->prepare(
			"UPDATE {$wpdb->posts} SET post_excerpt = REPLACE(post_excerpt, %s, '')
			 WHERE post_type NOT IN ('revision') AND post_excerpt LIKE %s",
			$old, $like
		) );
		$touched += (int) $wpdb->query( $wpdb->prepare(
			"UPDATE {$wpdb->postmeta} SET meta_value = REPLACE(meta_value, %s, '')
			 WHERE meta_value LIKE %s
			   AND meta_value NOT LIKE 'a:%%'
			   AND meta_value NOT LIKE 'O:%%'
			   AND meta_value NOT LIKE 's:%%'",
			$old, $like
		) );
	}

	if ( $touched ) {
		wp_cache_flush();
		if ( function_exists( 'wp_cache_clear_cache' ) ) { wp_cache_clear_cache(); }
	}
	update_option( 'vtech_contact_snapshot', vtech_contact_snapshot_values(), false );
	update_option( 'vtech_contact_po_box_removed', '2026-11', true );
}
add_action( 'init', 'vtech_contact_migrate_po_box_2026_11', 6 );

/**
 * One-time migration (20 Nov 2026): the address is now exactly
 * "Mpaka Plaza, Mpaka Road, Westlands". Forces the saved Customizer value and
 * rewrites every older form of the address in posts, excerpts and
 * non-serialised post meta.
 */
function vtech_contact_migrate_address_2026_11b() {
	if ( '2026-11b' === get_option( 'vtech_contact_address_migration' ) ) { return; }
	$new = vtech_contact_defaults()['address'];
	set_theme_mod( 'vtech_address', $new );

	$olds  = vtech_contact_retired_values()['address'];
	$pairs = array();
	foreach ( $olds as $old ) { $pairs[ $old ] = $new; }
	// Longest strings first so a shorter one never splits a longer match.
	uksort( $pairs, function ( $x, $y ) { return strlen( $y ) - strlen( $x ); } );
	vtech_contact_propagate( $pairs );
	vtech_contact_migrate_po_box_strip_only();

	update_option( 'vtech_contact_snapshot', vtech_contact_snapshot_values(), false );
	update_option( 'vtech_contact_address_migration', '2026-11b', true );
	if ( function_exists( 'wp_cache_flush' ) ) { wp_cache_flush(); }
	do_action( 'vtech_purge_page_cache' );
	if ( class_exists( '\seraph_accel\API' ) && method_exists( '\seraph_accel\API', 'OperateCache' ) ) {
		try { \seraph_accel\API::OperateCache( \seraph_accel\API::CACHE_OP_DEL ); } catch ( \Throwable $e ) {} // phpcs:ignore
	}
}
add_action( 'init', 'vtech_contact_migrate_address_2026_11b', 7 );

/**
 * One-time migration (20 Nov 2026, live-page audit): Privacy Policy and Terms
 * still said "Mpaka Plaza, Mpaka Road, Nairobi, Kenya" and the Privacy Policy
 * still had the retired number "+254 728135246"; several guides said
 * "Mpaka Plaza, Westlands" or "Mpaka Plaza in Westlands, Nairobi". Rewrites all
 * of them to the current address and phone across posts, excerpts and meta.
 */
function vtech_contact_migrate_address_2026_11c() {
	if ( '2026-11c' === get_option( 'vtech_contact_address_migration_c' ) ) { return; }
	$retired = vtech_contact_retired_values();
	$pairs   = array();
	foreach ( $retired['address'] as $old ) { $pairs[ $old ] = vtech_contact( 'address' ); }
	foreach ( $retired['phone'] as $old ) {
		foreach ( vtech_contact_variants( 'phone', $old ) as $variant => $type ) {
			$pairs[ $variant ] = vtech_contact_variant_value( vtech_contact( 'phone' ), $type );
		}
	}
	// Longest first so a shorter string never splits a longer match.
	uksort( $pairs, function ( $x, $y ) { return strlen( $y ) - strlen( $x ); } );
	vtech_contact_propagate( $pairs );
	update_option( 'vtech_contact_snapshot', vtech_contact_snapshot_values(), false );
	update_option( 'vtech_contact_address_migration_c', '2026-11c', true );
	if ( function_exists( 'wp_cache_flush' ) ) { wp_cache_flush(); }
	if ( class_exists( '\seraph_accel\API' ) && method_exists( '\seraph_accel\API', 'OperateCache' ) ) {
		try { \seraph_accel\API::OperateCache( \seraph_accel\API::CACHE_OP_DEL ); } catch ( \Throwable $e ) {} // phpcs:ignore
	}
}
add_action( 'init', 'vtech_contact_migrate_address_2026_11c', 8 );

/** Removes any stray "P.O. Box 66734-00800" left in content (no full-address match). */
function vtech_contact_migrate_po_box_strip_only() {
	global $wpdb;
	foreach ( array( ', P.O. Box 66734-00800', ' · P.O. Box 66734-00800', ' P.O. Box 66734-00800', 'P.O. Box 66734-00800' ) as $old ) {
		$like = '%' . $wpdb->esc_like( $old ) . '%';
		$wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->posts} SET post_content = REPLACE(post_content, %s, '') WHERE post_type NOT IN ('revision') AND post_content LIKE %s", $old, $like ) );
		$wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->posts} SET post_excerpt = REPLACE(post_excerpt, %s, '') WHERE post_type NOT IN ('revision') AND post_excerpt LIKE %s", $old, $like ) );
		$wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->postmeta} SET meta_value = REPLACE(meta_value, %s, '') WHERE meta_value LIKE %s AND meta_value NOT LIKE 'a:%%' AND meta_value NOT LIKE 'O:%%' AND meta_value NOT LIKE 's:%%'", $old, $like ) );
	}
}

/**
 * Replace a set of old strings with their current values across the database.
 *
 * @param array $pairs old string => new string.
 * @return int Rows touched.
 */
function vtech_contact_propagate( array $pairs ) {
	global $wpdb;
	$touched = 0;

	foreach ( $pairs as $old => $new ) {
		$old = (string) $old;
		$new = (string) $new;
		if ( '' === trim( $old ) || $old === $new || '' === trim( $new ) ) { continue; }
		// Guard against absurdly short needles that could corrupt content.
		if ( strlen( $old ) < 6 ) { continue; }

		$like = '%' . $wpdb->esc_like( $old ) . '%';

		$touched += (int) $wpdb->query( $wpdb->prepare(
			"UPDATE {$wpdb->posts} SET post_content = REPLACE(post_content, %s, %s)
			 WHERE post_type NOT IN ('revision')
			   AND post_status NOT IN ('trash','auto-draft')
			   AND post_content LIKE %s",
			$old, $new, $like
		) );

		$touched += (int) $wpdb->query( $wpdb->prepare(
			"UPDATE {$wpdb->posts} SET post_excerpt = REPLACE(post_excerpt, %s, %s)
			 WHERE post_type NOT IN ('revision')
			   AND post_status NOT IN ('trash','auto-draft')
			   AND post_excerpt LIKE %s",
			$old, $new, $like
		) );

		// Post meta, but never serialised values (replacing inside a
		// serialised string would break its length prefixes).
		$touched += (int) $wpdb->query( $wpdb->prepare(
			"UPDATE {$wpdb->postmeta} SET meta_value = REPLACE(meta_value, %s, %s)
			 WHERE meta_value LIKE %s
			   AND meta_value NOT LIKE 'a:%%'
			   AND meta_value NOT LIKE 'O:%%'
			   AND meta_value NOT LIKE 's:%%'",
			$old, $new, $like
		) );
	}

	if ( $touched ) {
		wp_cache_flush();
		if ( function_exists( 'wp_cache_clear_cache' ) ) { wp_cache_clear_cache(); }
		do_action( 'vtech_contact_propagated', $touched, $pairs );
	}

	return $touched;
}

/**
 * Compare the saved snapshot with the live values and push any change into
 * existing content. Runs after every Customizer save.
 */
function vtech_contact_sync_after_save() {
	$current  = vtech_contact_snapshot_values();
	$snapshot = get_option( 'vtech_contact_snapshot', array() );
	if ( ! is_array( $snapshot ) ) { $snapshot = array(); }

	$pairs  = array();
	$legacy = get_option( 'vtech_contact_legacy', array() );
	if ( ! is_array( $legacy ) ) { $legacy = array(); }

	foreach ( $current as $key => $new ) {
		$old = isset( $snapshot[ $key ] ) ? (string) $snapshot[ $key ] : '';
		if ( '' === $old || $old === $new ) { continue; }
		foreach ( vtech_contact_variants( $key, $old ) as $old_variant => $type ) {
			$pairs[ $old_variant ] = vtech_contact_variant_value( $new, $type );
			$legacy[ $old_variant ] = array( 'key' => $key, 'type' => $type );
		}
	}

	// Also correct anything still carrying the theme's original defaults.
	$defaults = vtech_contact_defaults();
	foreach ( vtech_contact_synced_keys() as $key ) {
		if ( empty( $defaults[ $key ] ) || $defaults[ $key ] === $current[ $key ] ) { continue; }
		foreach ( vtech_contact_variants( $key, $defaults[ $key ] ) as $old_variant => $type ) {
			if ( ! isset( $pairs[ $old_variant ] ) ) {
				$pairs[ $old_variant ] = vtech_contact_variant_value( $current[ $key ], $type );
			}
		}
	}

	update_option( 'vtech_contact_snapshot', $current, false );
	update_option( 'vtech_contact_legacy', $legacy, true );

	if ( $pairs ) {
		$rows = vtech_contact_propagate( $pairs );
		set_transient( 'vtech_contact_sync_notice', (int) $rows, 120 );
	}
}
add_action( 'customize_save_after', 'vtech_contact_sync_after_save', 20 );

/** Seed the snapshot on first load so the first save has something to compare. */
add_action( 'after_setup_theme', function () {
	if ( false === get_option( 'vtech_contact_snapshot', false ) ) {
		update_option( 'vtech_contact_snapshot', vtech_contact_snapshot_values(), false );
	}
}, 20 );

/**
 * Display-time safety net: rewrite any legacy value still sitting in content.
 *
 * @param string $html Content.
 * @return string
 */
function vtech_contact_filter_content( $html ) {
	if ( ! is_string( $html ) || '' === $html ) { return $html; }
	$legacy = vtech_contact_legacy_map();
	if ( empty( $legacy ) ) { return $html; }
	uksort( $legacy, function ( $x, $y ) { return strlen( (string) $y ) - strlen( (string) $x ); } );

	foreach ( $legacy as $old => $meta ) {
		$key  = is_array( $meta ) ? ( $meta['key'] ?? '' ) : (string) $meta;
		$type = is_array( $meta ) ? ( $meta['type'] ?? 'raw' ) : 'raw';
		if ( ! $key ) { continue; }
		$new = vtech_contact_variant_value( vtech_contact( $key ), $type );
		if ( '' === $new || $new === (string) $old || strlen( (string) $old ) < 6 ) { continue; }
		$html = str_replace( (string) $old, $new, $html );
	}
	return $html;
}
add_filter( 'the_content', 'vtech_contact_filter_content', 20 );
add_filter( 'the_excerpt', 'vtech_contact_filter_content', 20 );
add_filter( 'widget_text', 'vtech_contact_filter_content', 20 );
add_filter( 'render_block', 'vtech_contact_filter_content', 20 );

/** Admin confirmation after a sync. */
add_action( 'admin_notices', function () {
	$rows = get_transient( 'vtech_contact_sync_notice' );
	if ( false === $rows ) { return; }
	delete_transient( 'vtech_contact_sync_notice' );
	echo '<div class="notice notice-success is-dismissible"><p><strong>VTECH:</strong> ' .
		esc_html( sprintf(
			/* translators: %d: number of database rows updated. */
			__( 'Company details updated everywhere (%d content rows rewritten).', 'vtech-av' ),
			(int) $rows
		) ) . '</p></div>';
} );


/* -------------------------------------------------------------------------
 * FAQ questions: "outside Nairobi" -> "across Kenya" (v5.71.0, Nov 2026).
 * Applied at display time (homepage, services, ACF, post content) so older
 * values saved in the database also show the new wording, visibly and in
 * FAQPage schema. A one-time migration below also stores the new wording.
 * ---------------------------------------------------------------------- */
function vtech_faq_question_map() {
	return array(
		'Do you work with churches outside Nairobi?' => 'Do you work with churches across Kenya?',
		'Yes, we can support events outside Nairobi.' => 'Yes, we can support events across Kenya.',
		'Yes, across Kenya. Delivery outside Nairobi is quoted based on the location.' => 'Yes. Delivery across Kenya is quoted based on the location.',
		'Can you work outside Nairobi?' => 'Can you work across Kenya?',
		'Do you provide sound and screens for events outside Nairobi?' => 'Do you provide sound and screens for events across Kenya?',
		'Do you install video conferencing outside Nairobi?' => 'Do you install video conferencing across Kenya?',
		'Do you consult for projects outside Nairobi?' => 'Do you consult for projects across Kenya?',
		'Do you cover locations outside Nairobi?' => 'Do you cover locations across Kenya?',
		'Do you deliver outside Nairobi?' => 'Do you deliver across Kenya?',
		'Do you work outside Nairobi?' => 'Do you work across Kenya?',
	);
}

function vtech_faq_question_fix( $text ) {
	if ( ! is_string( $text ) || '' === $text || false === strpos( $text, 'outside Nairobi' ) ) { return $text; }
	$m = vtech_faq_question_map();
	return str_replace( array_keys( $m ), array_values( $m ), $text );
}

/** Fix the question key in a list of FAQ rows (array( q, a ) or array( 'question' => ... )). */
function vtech_faq_rows_fix( $rows ) {
	if ( ! is_array( $rows ) ) { return $rows; }
	foreach ( $rows as $i => $r ) {
		if ( ! is_array( $r ) ) { continue; }
		foreach ( array( 0, 'question', 'q' ) as $k ) {
			if ( isset( $r[ $k ] ) && is_string( $r[ $k ] ) ) { $rows[ $i ][ $k ] = vtech_faq_question_fix( $r[ $k ] ); }
		}
	}
	return $rows;
}

add_filter( 'vtech_home_faqs', 'vtech_faq_rows_fix', 99 );
add_filter( 'acf/format_value/name=faqs', 'vtech_faq_rows_fix', 99 );
add_filter( 'the_content', 'vtech_faq_question_fix', 21 );
add_filter( 'render_block', 'vtech_faq_question_fix', 21 );
add_filter( 'theme_mod_vtech_hs_faq_items', 'vtech_faq_question_fix', 99 );

/** One-time migration: store the new question wording in saved settings and content. */
add_action( 'init', function () {
	if ( '2026-11g' === get_option( 'vtech_faq_question_migration' ) ) { return; }
	global $wpdb;
	$v = get_theme_mod( 'vtech_hs_faq_items', '' );
	if ( is_string( $v ) && '' !== $v ) {
		$n = vtech_faq_question_fix( $v );
		if ( $n !== $v ) { set_theme_mod( 'vtech_hs_faq_items', $n ); }
	}
	// Plain-text meta (pipe-separated rows), safe to rewrite via the API.
	$ids = $wpdb->get_col( "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = 'vtech_svc_faqs' AND meta_value LIKE '%outside Nairobi%'" );
	foreach ( (array) $ids as $id ) {
		$m = get_post_meta( (int) $id, 'vtech_svc_faqs', true );
		if ( is_string( $m ) ) { update_post_meta( (int) $id, 'vtech_svc_faqs', vtech_faq_question_fix( $m ) ); }
	}
	// ACF repeater sub-fields (faqs_N_question) are plain strings.
	$rows = $wpdb->get_results( "SELECT meta_id, post_id, meta_key, meta_value FROM {$wpdb->postmeta} WHERE ( meta_key LIKE 'faqs\\_%\\_question' OR meta_key LIKE 'faqs\\_%\\_answer' ) AND meta_value LIKE '%outside Nairobi%'" );
	foreach ( (array) $rows as $r ) {
		update_post_meta( (int) $r->post_id, $r->meta_key, vtech_faq_question_fix( $r->meta_value ) );
	}
	// Post content (homepage pattern HTML etc.). Plain text, not serialised.
	$posts = $wpdb->get_results( "SELECT ID, post_content FROM {$wpdb->posts} WHERE post_content LIKE '%outside Nairobi%' AND post_type NOT IN ('revision')" );
	foreach ( (array) $posts as $p ) {
		$n = vtech_faq_question_fix( $p->post_content );
		if ( $n !== $p->post_content ) {
			$wpdb->update( $wpdb->posts, array( 'post_content' => $n ), array( 'ID' => (int) $p->ID ) );
			clean_post_cache( (int) $p->ID );
		}
	}
	update_option( 'vtech_faq_question_migration', '2026-11g', true );
	if ( function_exists( 'wp_cache_flush' ) ) { wp_cache_flush(); }
	if ( class_exists( '\\seraph_accel\\API' ) && method_exists( '\\seraph_accel\\API', 'OperateCache' ) ) {
		try { \seraph_accel\API::OperateCache( \seraph_accel\API::CACHE_OP_DEL ); } catch ( \Throwable $e ) {} // phpcs:ignore
	}
}, 9 );
