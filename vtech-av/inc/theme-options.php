<?php
/**
 * Theme Settings Panel via the Customizer (native, no plugin).
 * Company NAP, social, hero, CTA, tracking, and toggle for float buttons.
 *
 * @package VTECH_AV
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

add_action( 'customize_register', function ( $wp_customize ) {

	$wp_customize->add_panel( 'vtech_panel', array( 'title' => 'VTECH Theme Options', 'priority' => 10 ) );

	/* --- Company / NAP ---
	 * SINGLE SOURCE OF TRUTH. Every template, email, quote, schema block and
	 * floating button reads these values through vtech_contact() in
	 * inc/contact.php. Saving here also rewrites the old values inside
	 * existing page content (Privacy Policy, Terms, About, etc.), so a change
	 * made here really does apply everywhere.
	 */
	$wp_customize->add_section( 'vtech_company', array(
		'title'       => 'Company Details',
		'panel'       => 'vtech_panel',
		'description' => 'Change your phone, WhatsApp, email or address here only. On save, these values are updated across the whole site — header, footer, floating buttons, contact page, forms, quotes, search-engine data — and inside existing page text such as the Privacy Policy and Terms. In page content you can also use the shortcodes [vtech_phone], [vtech_email], [vtech_whatsapp_link], [vtech_address], [vtech_hours] and [vtech_company].',
	) );

	$vtc_defaults = function_exists( 'vtech_contact_defaults' ) ? vtech_contact_defaults() : array();
	$vtc_d = function ( $key, $fallback = '' ) use ( $vtc_defaults ) {
		return isset( $vtc_defaults[ $key ] ) ? $vtc_defaults[ $key ] : $fallback;
	};

	$fields = array(
		'vtech_company'  => array( 'Company name', $vtc_d( 'company' ), 'Used in the footer, emails, quotes and search-engine data.' ),
		'vtech_phone'    => array( 'Phone', $vtc_d( 'phone' ), 'Display format, e.g. +254 728 135 246. Call links are generated automatically.' ),
		'vtech_whatsapp' => array( 'WhatsApp number (digits only)', $vtc_d( 'whatsapp' ), 'Country code + number, no spaces or +, e.g. 254728135246. Leave blank to fall back to the phone number.' ),
		'vtech_email'    => array( 'Email', $vtc_d( 'email' ), 'Also the inbox that contact, consultation and hire forms are sent to.' ),
		'vtech_address'  => array( 'Address', $vtc_d( 'address' ), '' ),
		'vtech_locality' => array( 'City / town', $vtc_d( 'locality' ), 'Used in search-engine data.' ),
		'vtech_region'   => array( 'County / region', $vtc_d( 'region' ), 'Used in search-engine data.' ),
		'vtech_postal'   => array( 'Postal code', $vtc_d( 'postal' ), 'Used in search-engine data.' ),
		'vtech_hours'    => array( 'Business Hours', $vtc_d( 'hours' ), 'Shown in the top bar and footer.' ),
		'vtech_hours_schema' => array( 'Business hours (search-engine format)', $vtc_d( 'hours_schema' ), 'Schema.org format, e.g. Mo-Fr 09:00-18:00.' ),
		'vtech_map_embed'=> array( 'Google Map embed URL', $vtc_d( 'map_embed' ), 'Leave blank to build the map automatically from the address above.' ),
		'vtech_geo_lat'  => array( 'Map latitude (for SEO, e.g. -1.2669)', $vtc_d( 'geo_lat' ), '' ),
		'vtech_geo_lng'  => array( 'Map longitude (for SEO, e.g. 36.8047)', $vtc_d( 'geo_lng' ), '' ),
	);
	foreach ( $fields as $id => $f ) {
		$wp_customize->add_setting( $id, array( 'default' => $f[1], 'sanitize_callback' => 'sanitize_text_field' ) );
		$wp_customize->add_control( $id, array(
			'label'       => $f[0],
			'description' => isset( $f[2] ) ? $f[2] : '',
			'section'     => 'vtech_company',
			'type'        => 'text',
		) );
	}

	/* --- Social Media --- */
	$wp_customize->add_section( 'vtech_social', array( 'title' => 'Social Media Links', 'panel' => 'vtech_panel' ) );
	$social = array(
		'vtech_facebook'  => array( 'Facebook URL', 'https://web.facebook.com/vtechaudioke' ),
		'vtech_instagram' => array( 'Instagram URL', '' ),
		'vtech_linkedin'  => array( 'LinkedIn URL', 'https://www.linkedin.com/company/vtech-audio/' ),
		'vtech_x'         => array( 'X (Twitter) URL', '' ),
		'vtech_youtube'   => array( 'YouTube URL', '' ),
		'vtech_tiktok'    => array( 'TikTok URL', 'https://www.tiktok.com/@vtech.audio' ),
	);
	foreach ( $social as $sid => $sf ) {
		$wp_customize->add_setting( $sid, array( 'default' => $sf[1], 'sanitize_callback' => 'esc_url_raw' ) );
		$wp_customize->add_control( $sid, array( 'label' => $sf[0], 'description' => 'Leave blank to hide this icon.', 'section' => 'vtech_social', 'type' => 'url' ) );
	}

	/* --- Hero --- */
	$wp_customize->add_section( 'vtech_hero', array( 'title' => 'Homepage Hero', 'panel' => 'vtech_panel' ) );
	$wp_customize->add_setting( 'vtech_hero_title', array( 'default' => 'Kenya\'s Premium Audio Visual Company', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'vtech_hero_title', array( 'label' => 'Hero Title', 'section' => 'vtech_hero', 'type' => 'text' ) );
	$wp_customize->add_setting( 'vtech_hero_sub', array( 'default' => 'Sound, LED screens, stage lighting, conference & PA systems, acoustics and digital signage — designed, installed and supported across Kenya and East Africa.', 'sanitize_callback' => 'sanitize_textarea_field' ) );
	$wp_customize->add_control( 'vtech_hero_sub', array( 'label' => 'Hero Subtitle', 'section' => 'vtech_hero', 'type' => 'textarea' ) );
	$wp_customize->add_setting( 'vtech_hero_img', array( 'default' => VTECH_URI . '/assets/img/hero.webp', 'sanitize_callback' => 'esc_url_raw' ) );
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'vtech_hero_img', array( 'label' => 'Hero Image (LCP)', 'section' => 'vtech_hero' ) ) );

	/* --- Conversion toggles --- */
	$wp_customize->add_section( 'vtech_conv', array( 'title' => 'Conversion Elements', 'panel' => 'vtech_panel' ) );
	foreach ( array(
		'vtech_show_whatsapp' => 'Show floating WhatsApp button',
		'vtech_show_call'     => 'Show floating call button',
		'vtech_show_sticky_cta' => 'Show sticky "Get a Quote" bar',
		'vtech_show_exit_intent' => 'Enable exit-intent lead popup',
	) as $id => $label ) {
		$wp_customize->add_setting( $id, array( 'default' => true, 'sanitize_callback' => 'wp_validate_boolean' ) );
		$wp_customize->add_control( $id, array( 'label' => $label, 'section' => 'vtech_conv', 'type' => 'checkbox' ) );
	}

	/* --- Homepage Trust Stats --- */
	$wp_customize->add_section( 'vtech_stats', array( 'title' => 'Homepage Trust Stats', 'panel' => 'vtech_panel' ) );
	$vtc_stat_defaults = array(
		'vtech_stat1_num' => array( 'Stat 1 number', '42+' ),
		'vtech_stat1_lbl' => array( 'Stat 1 label', 'Installations delivered' ),
		'vtech_stat2_num' => array( 'Stat 2 number', '47' ),
		'vtech_stat2_lbl' => array( 'Stat 2 label', 'Counties served' ),
		'vtech_stat3_num' => array( 'Stat 3 number', '24h' ),
		'vtech_stat3_lbl' => array( 'Stat 3 label', 'Quote turnaround' ),
		'vtech_stat4_num' => array( 'Stat 4 number', '12mo' ),
		'vtech_stat4_lbl' => array( 'Stat 4 label', 'Support & warranty' ),
	);
	foreach ( $vtc_stat_defaults as $vtc_sid => $vtc_sf ) {
		$wp_customize->add_setting( $vtc_sid, array( 'default' => $vtc_sf[1], 'sanitize_callback' => 'sanitize_text_field' ) );
		$wp_customize->add_control( $vtc_sid, array( 'label' => $vtc_sf[0], 'description' => 'Leave blank to hide this stat.', 'section' => 'vtech_stats', 'type' => 'text' ) );
	}

	/* --- Footer --- */
	$wp_customize->add_section( 'vtech_footer', array( 'title' => 'Footer', 'panel' => 'vtech_panel' ) );
	$wp_customize->add_setting( 'vtech_footer_blurb', array( 'default' => "Kenya's premium audio-visual integrator. Sound, LED, lighting, conference & PA systems, acoustics and digital signage designed, installed and supported across Kenya and East Africa.", 'sanitize_callback' => 'sanitize_textarea_field' ) );
	$wp_customize->add_control( 'vtech_footer_blurb', array( 'label' => 'Footer description', 'section' => 'vtech_footer', 'type' => 'textarea' ) );
	$wp_customize->add_setting( 'vtech_footer_copyright', array( 'default' => 'VTECH Audio Visual Solutions. All rights reserved.', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'vtech_footer_copyright', array( 'label' => 'Copyright text (year is added automatically)', 'section' => 'vtech_footer', 'type' => 'text' ) );

	/* --- Homepage content counts --- */
	$wp_customize->add_section( 'vtech_home_counts', array( 'title' => 'Homepage Content Counts', 'panel' => 'vtech_panel' ) );
	$wp_customize->add_setting( 'vtech_home_projects', array( 'default' => 6, 'sanitize_callback' => 'absint' ) );
	$wp_customize->add_control( 'vtech_home_projects', array( 'label' => 'Recent Projects to show on homepage', 'description' => 'How many of the most recent projects to display in the "Recent Projects in Kenya" section.', 'section' => 'vtech_home_counts', 'type' => 'number', 'input_attrs' => array( 'min' => 1, 'max' => 24, 'step' => 1 ) ) );

	/* --- Tracking --- */
	$wp_customize->add_section( 'vtech_track', array( 'title' => 'Analytics & Tracking', 'panel' => 'vtech_panel' ) );
	$wp_customize->add_setting( 'vtech_gtag', array( 'default' => '', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'vtech_gtag', array( 'label' => 'Google Analytics / GTM ID', 'section' => 'vtech_track', 'type' => 'text' ) );
} );

/** Helper accessors used across templates. */
if ( ! function_exists( 'vtech_opt' ) ) {
	function vtech_opt( $key, $default = '' ) { return get_theme_mod( $key, $default ); }
}
