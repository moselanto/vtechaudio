<?php
/**
 * Local SEO + entity schema layer (v5.37.0).
 *
 * Goal: make Google understand VTECH as a Nairobi-based audio visual company
 * serving all of Kenya, and connect the business to the searches that matter:
 * "audio visual companies in Kenya", "audio Kenya", "PA systems Kenya",
 * "dB Technologies Kenya", "Shure Kenya", "mixers Kenya", "sound hire Nairobi".
 *
 * What it does:
 *  1. Builds ONE canonical business entity (vtech_business_entity()) used by
 *     both the theme's own schema and Rank Math, so NAP, hours, geo, services
 *     and brands are identical everywhere (and match Google Business Profile).
 *  2. Enriches Rank Math's Organization node through the rank_math/json_ld
 *     filter: fixes the wrong legalName, upgrades it to LocalBusiness, adds
 *     telephone, geo, opening hours, areas served, service + brand catalogue.
 *  3. Outputs FAQPage schema on the homepage from the same FAQ list that is
 *     rendered visibly (vtech_home_faqs()), so markup always matches content.
 *  4. Sets a keyword-led homepage title and meta description (Rank Math or
 *     the theme's own meta layer).
 *
 * Everything is filterable so the business can adjust brands/services without
 * editing this file:  vtech_seo_brands, vtech_seo_services, vtech_seo_areas,
 * vtech_home_faqs, vtech_business_entity.
 *
 * @package VTECH_AV
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

/* -------------------------------------------------------------------------
 * Data sources
 * ---------------------------------------------------------------------- */

/** Brands VTECH supplies, installs and hires. Keep this to brands actually stocked. */
function vtech_seo_brands() {
	return apply_filters( 'vtech_seo_brands', array(
		array(
			'brand'    => 'dB Technologies',
			'name'     => 'dB Technologies speakers, line arrays and subwoofers',
			'category' => 'Professional loudspeakers',
			'desc'     => 'dB Technologies active speakers, line array systems and subwoofers supplied, installed and hired in Kenya.',
		),
		array(
			'brand'    => 'Shure',
			'name'     => 'Shure microphones and wireless systems',
			'category' => 'Microphones',
			'desc'     => 'Shure wired and wireless microphones, in-ear monitors and conference microphones supplied, installed and hired in Kenya.',
		),
		array(
			'brand'    => '',
			'name'     => 'Audio mixers and digital mixing consoles',
			'category' => 'Audio mixers',
			'desc'     => 'Analogue and digital audio mixers for churches, events, studios and venues across Kenya, with setup and training.',
		),
	) );
}

/** Core services (mirrors the Google Business Profile services list). */
function vtech_seo_services() {
	return apply_filters( 'vtech_seo_services', array(
		array( 'Audio visual installation', 'End-to-end AV design, supply and installation for offices, churches, hotels, schools and venues in Kenya.', '/services/' ),
		array( 'Sound system and PA system installation', 'PA systems, line arrays, speakers, microphones and mixers designed and installed across Kenya.', '/services/sound-systems/' ),
		array( 'LED screens and video walls', 'Indoor and outdoor LED screens and video walls supplied, installed and hired in Kenya.', '/services/led-screens/' ),
		array( 'Conference and boardroom systems', 'Video conferencing, delegate microphones and one-touch boardroom AV for corporates and government.', '/services/conference-systems/' ),
		array( 'Stage and architectural lighting', 'Event and permanent stage lighting design, rigging and control.', '/services/lighting/' ),
		array( 'Acoustic treatment and soundproofing', 'Room acoustics, acoustic panels and soundproofing for clear sound.', '/services/acoustic-solutions/' ),
		array( 'AV consultation and system design', 'Free site survey, AV system design and fixed written quotation within 24 hours.', '/services/consultation/' ),
		array( 'Sound and AV equipment hire', 'Sound, PA, LED screen, lighting and conference equipment hire with delivery, setup and technician.', '/equipment-hire/' ),
		array( 'Live streaming and event production', 'Multi-camera live streaming and technical production for events, churches and AGMs.', '/live-streaming-events-kenya-guide/' ),
		array( 'AV maintenance and support', 'Preventive maintenance, repairs and annual support contracts for installed AV systems.', '/contact/' ),
	) );
}

/** Areas served. Nairobi metro first (where most searches originate), then major towns. */
function vtech_seo_areas() {
	return apply_filters( 'vtech_seo_areas', array(
		'Nairobi', 'Westlands', 'Kiambu', 'Thika', 'Machakos', 'Kajiado', 'Mombasa', 'Kisumu',
		'Nakuru', 'Eldoret', 'Nyeri', 'Meru', 'Naivasha', 'Kakamega', 'Malindi',
	) );
}

/** Homepage FAQs — rendered visibly in front-page.php AND as FAQPage schema. */
function vtech_home_faqs() {
	return apply_filters( 'vtech_home_faqs', array(
		array( 'How fast can VTECH quote my AV project?', 'We provide a fixed written quote within 24 hours of a free site survey in Nairobi, and within 48 hours upcountry.' ),
		array( 'Do you cover locations outside Nairobi?', 'Yes. We install and support AV systems across all 47 counties in Kenya, including Mombasa, Kisumu, Nakuru and Eldoret, and select projects across East Africa.' ),
		array( 'Do you supply dB Technologies speakers and Shure microphones in Kenya?', 'Yes. We supply, install and hire dB Technologies speakers and line arrays and Shure wired and wireless microphones, matched to your venue and set up by our technicians.' ),
		array( 'Can you help me choose the right audio mixer?', 'Yes. We recommend analogue or digital mixers based on your number of microphones, instruments and outputs, then install, configure and train your team on site.' ),
		array( 'Do you offer equipment hire as well as installation?', 'Yes. Sound, PA, lighting, LED screen and conferencing equipment is available for event hire in Nairobi and across Kenya, with delivery, setup and an on-site technician.' ),
		array( 'Do you provide maintenance after installation?', 'Every installation includes a 12-month support window, with annual maintenance contracts available.' ),
	) );
}

/* -------------------------------------------------------------------------
 * Canonical business entity
 * ---------------------------------------------------------------------- */

function vtech_business_entity() {
	$url  = home_url( '/' );
	$nap  = function_exists( 'vtech_nap' ) ? vtech_nap() : array();
	$logo = get_theme_mod( 'vtech_logo_url', VTECH_URI . '/assets/img/logo.png' );
	$img  = get_theme_mod( 'vtech_og_default', VTECH_URI . '/assets/img/og-default.jpg' );

	$areas = array( array( '@type' => 'Country', 'name' => 'Kenya' ) );
	foreach ( vtech_seo_areas() as $a ) {
		$areas[] = array( '@type' => 'City', 'name' => $a . ', Kenya' );
	}
	$areas[] = array( '@type' => 'Place', 'name' => 'East Africa' );

	$svc_offers = array();
	foreach ( vtech_seo_services() as $s ) {
		$svc_offers[] = array(
			'@type'       => 'Offer',
			'itemOffered' => array(
				'@type'       => 'Service',
				'name'        => $s[0],
				'description' => $s[1],
				'url'         => home_url( $s[2] ),
				'areaServed'  => array( '@type' => 'Country', 'name' => 'Kenya' ),
				'provider'    => array( '@id' => $url . '#organization' ),
			),
		);
	}

	$prod_offers = array();
	foreach ( vtech_seo_brands() as $b ) {
		$p = array(
			'@type'       => 'Product',
			'name'        => $b['name'],
			'category'    => $b['category'],
			'description' => $b['desc'],
		);
		if ( ! empty( $b['brand'] ) ) {
			$p['brand'] = array( '@type' => 'Brand', 'name' => $b['brand'] );
		}
		$prod_offers[] = array( '@type' => 'Offer', 'itemOffered' => $p, 'areaServed' => 'KE' );
	}

	$entity = array(
		'@type'       => array( 'Organization', 'LocalBusiness', 'ProfessionalService' ),
		'@id'         => $url . '#organization',
		'name'        => $nap['name'] ?? 'VTECH Audio Visual Solutions',
		'legalName'   => $nap['name'] ?? 'VTECH Audio Visual Solutions',
		'alternateName' => array( 'VTECH', 'VTECH Audio', 'VTECH Audio Kenya', 'VTECH AV' ),
		'description' => 'VTECH Audio Visual Solutions is an audio visual company in Nairobi, Kenya. We design, supply, install and hire professional sound and PA systems, dB Technologies speakers, Shure microphones, audio mixers, LED screens, conference systems, stage lighting and acoustic treatment for churches, corporates, hotels, schools, government and events across Kenya.',
		'url'         => $url,
		'email'       => $nap['email'] ?? '',
		'telephone'   => $nap['phone'] ?? '',
		'logo'        => array( '@type' => 'ImageObject', 'url' => $logo ),
		'image'       => $img,
		'priceRange'  => 'KES 10,000 - KES 5,000,000',
		'currenciesAccepted' => 'KES',
		'paymentAccepted'    => 'M-PESA, Bank Transfer, Cash',
		'foundingDate'=> '2021',
		'address'     => array(
			'@type'           => 'PostalAddress',
			'streetAddress'   => 'Ground Floor, Mpaka Plaza, Mpaka Road, Westlands',
			'addressLocality' => $nap['locality'] ?? 'Nairobi',
			'addressRegion'   => $nap['region'] ?? 'Nairobi County',
			'postalCode'      => $nap['postal'] ?? '00800',
			'addressCountry'  => 'KE',
		),
		'geo'         => array(
			'@type'     => 'GeoCoordinates',
			'latitude'  => $nap['geo']['lat'] ?? -1.2669,
			'longitude' => $nap['geo']['lng'] ?? 36.8047,
		),
		'hasMap'      => 'https://www.google.com/maps?q=' . rawurlencode( 'VTECH Audio Visual Solutions, Mpaka Plaza, Mpaka Road, Westlands, Nairobi' ),
		'openingHoursSpecification' => array(
			array(
				'@type'     => 'OpeningHoursSpecification',
				'dayOfWeek' => array( 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday' ),
				'opens'     => '09:00',
				'closes'    => '18:00',
			),
		),
		'areaServed'  => $areas,
		'knowsAbout'  => array(
			'Audio visual installation in Kenya', 'PA systems', 'Professional sound systems', 'Line array speakers',
			'dB Technologies', 'Shure microphones', 'Wireless microphones', 'Audio mixers', 'Digital mixing consoles',
			'Church sound systems', 'LED screens and video walls', 'Conference and boardroom AV', 'Video conferencing',
			'Stage lighting', 'Acoustic treatment and soundproofing', 'Digital signage', 'Sound equipment hire', 'Live streaming',
		),
		'hasOfferCatalog' => array(
			'@type' => 'OfferCatalog',
			'name'  => 'Audio visual services and equipment in Kenya',
			'itemListElement' => array(
				array( '@type' => 'OfferCatalog', 'name' => 'Audio visual services', 'itemListElement' => $svc_offers ),
				array( '@type' => 'OfferCatalog', 'name' => 'Pro audio brands and equipment', 'itemListElement' => $prod_offers ),
			),
		),
		'contactPoint' => array(
			array(
				'@type'             => 'ContactPoint',
				'telephone'         => $nap['phone'] ?? '',
				'email'             => $nap['email'] ?? '',
				'contactType'       => 'sales',
				'areaServed'        => 'KE',
				'availableLanguage' => array( 'English', 'Swahili' ),
			),
		),
		'sameAs'      => $nap['sameAs'] ?? array(),
		'slogan'      => 'Kenya\'s premium audio visual company: designed, installed and supported.',
	);

	return apply_filters( 'vtech_business_entity', $entity );
}

/* -------------------------------------------------------------------------
 * Rank Math integration — enrich its graph instead of duplicating it.
 * ---------------------------------------------------------------------- */

add_filter( 'rank_math/json_ld', function ( $data, $jsonld = null ) {
	if ( ! is_array( $data ) ) { return $data; }
	$entity = vtech_business_entity();
	$org_id = home_url( '/' ) . '#organization';
	$found  = false;

	foreach ( $data as $key => $node ) {
		if ( ! is_array( $node ) || empty( $node['@id'] ) ) { continue; }
		$id = (string) $node['@id'];

		// Organization / LocalBusiness node: our canonical entity wins on every field.
		if ( '#organization' === substr( $id, -13 ) ) {
			$entity['@id'] = $id;
			$data[ $key ]  = array_merge( $node, $entity );
			$found         = true;
		}

		// Place node: add full address + geo so it matches Google Business Profile.
		if ( '#place' === substr( $id, -6 ) ) {
			$data[ $key ]['address'] = $entity['address'];
			$data[ $key ]['geo']     = $entity['geo'];
		}
	}

	if ( ! $found && is_front_page() ) {
		$data['vtechBusiness'] = $entity;
	}
	return $data;
}, 99, 2 );

/* -------------------------------------------------------------------------
 * Homepage FAQPage schema (always — Rank Math does not build it from code).
 * ---------------------------------------------------------------------- */

add_action( 'wp_head', function () {
	if ( ! is_front_page() ) { return; }
	$items = array();
	foreach ( vtech_home_faqs() as $f ) {
		$items[] = array(
			'@type'          => 'Question',
			'name'           => $f[0],
			'acceptedAnswer' => array( '@type' => 'Answer', 'text' => $f[1] ),
		);
	}
	if ( ! $items ) { return; }
	echo "\n<script type=\"application/ld+json\">" . wp_json_encode(
		array( '@context' => 'https://schema.org', '@type' => 'FAQPage', '@id' => home_url( '/' ) . '#faq', 'mainEntity' => $items ),
		JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
	) . "</script>\n";
}, 25 );

/* -------------------------------------------------------------------------
 * Homepage title + meta description (keyword-led, within SERP limits).
 * ---------------------------------------------------------------------- */

function vtech_home_seo_title() {
	return apply_filters( 'vtech_home_seo_title', 'Audio Visual Company in Kenya | Sound, PA & LED | VTECH' );
}
function vtech_home_seo_description() {
	return apply_filters( 'vtech_home_seo_description', 'Nairobi audio visual company: PA & sound systems, dB Technologies speakers, Shure mics, mixers, LED screens, conference AV & hire across Kenya. Quote in 24h.' );
}

add_filter( 'rank_math/frontend/title', function ( $title ) {
	return is_front_page() ? vtech_home_seo_title() : $title;
}, 20 );
add_filter( 'rank_math/frontend/description', function ( $desc ) {
	return is_front_page() ? vtech_home_seo_description() : $desc;
}, 20 );
add_filter( 'rank_math/opengraph/facebook/og_title', function ( $t ) { return is_front_page() ? vtech_home_seo_title() : $t; }, 20 );
add_filter( 'rank_math/opengraph/facebook/og_description', function ( $d ) { return is_front_page() ? vtech_home_seo_description() : $d; }, 20 );
add_filter( 'rank_math/opengraph/facebook/og_locale', function () { return 'en_KE'; } );
