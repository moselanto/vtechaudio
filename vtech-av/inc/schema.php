<?php
/**
 * Structured data (JSON-LD). Output in wp_head.
 * LocalBusiness + Organization site-wide; Service / Project / FAQ / Breadcrumb
 * / Review contextually. Uses ACF + theme options where available.
 *
 * @package VTECH_AV
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function vtech_nap() {
	// Pull live social profiles from theme options so schema matches the site.
	$same = array();
	$vtc_socials = array(
		'vtech_facebook'  => 'https://web.facebook.com/vtechaudioke',
		'vtech_instagram' => '',
		'vtech_linkedin'  => 'https://www.linkedin.com/company/vtech-audio/',
		'vtech_x'         => '',
		'vtech_youtube'   => '',
		'vtech_tiktok'    => 'https://www.tiktok.com/@vtech.audio',
	);
	foreach ( $vtc_socials as $vtc_sk => $vtc_default ) {
		$vtc_su = get_theme_mod( $vtc_sk, $vtc_default );
		if ( $vtc_su ) { $same[] = $vtc_su; }
	}
	// v5.60.0: WhatsApp chat links are not official profiles, so they are no longer listed in sameAs.
	return array(
		'name'    => vtech_contact( 'company' ),
		'email'   => vtech_contact( 'email' ),
		'phone'   => vtech_contact( 'phone' ),
		'street'  => vtech_contact( 'address' ),
		'locality'=> vtech_contact( 'locality' ),
		'postal'  => vtech_contact( 'postal' ),
		'region'  => vtech_contact( 'region' ),
		'country' => vtech_contact( 'country' ),
		'geo'     => array( 'lat' => (float) vtech_contact( 'geo_lat' ), 'lng' => (float) vtech_contact( 'geo_lng' ) ),
		'url'     => home_url( '/' ),
		'hours'   => vtech_contact( 'hours_schema' ),
		'sameAs'  => $same,
	);
}

function vtech_json_ld( $data ) {
	echo "\n<script type=\"application/ld+json\">" . wp_json_encode( $data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . "</script>\n";
}

add_action( 'wp_head', function () {
	// HYBRID SCHEMA OWNERSHIP (v5.29.0): Rank Math (when active) owns the sitewide
	// entity graph — Organization / LocalBusiness / WebSite (BreadcrumbList lives
	// in inc/breadcrumbs.php). We defer ONLY that to the plugin, to avoid a second,
	// conflicting LocalBusiness node. The richer per-page schema further down
	// (Service, FAQPage, project CreativeWork) is ALWAYS emitted, because Rank Math
	// does not build it from the theme's ACF data.
	$seo_active = ( function_exists( 'vtech_seo_plugin_active' ) && vtech_seo_plugin_active() );
	$nap = vtech_nap();
	$logo = get_theme_mod( 'vtech_logo_url', VTECH_URI . '/assets/img/logo.png' );

	if ( ! $seo_active ) {

	// Organization + LocalBusiness (site-wide) — canonical entity from inc/seo-local.php.
	if ( function_exists( 'vtech_business_entity' ) ) {
		vtech_json_ld( array_merge( array( '@context' => 'https://schema.org' ), vtech_business_entity() ) );
	}

	// WebSite + Sitelinks search box.
	vtech_json_ld( array(
		'@context' => 'https://schema.org',
		'@type'    => 'WebSite',
		'url'      => $nap['url'],
		'name'     => $nap['name'],
		'potentialAction' => array(
			'@type' => 'SearchAction',
			'target' => array( '@type' => 'EntryPoint', 'urlTemplate' => $nap['url'] . '?s={search_term_string}' ),
			'query-input' => 'required name=search_term_string',
		),
	) );

	} // end sitewide entity graph (deferred to the SEO plugin when active).

	// --- PER-PAGE SCHEMA: always emitted; complements Rank Math using our ACF data. ---

	// Project -> CreativeWork.
	if ( is_singular( 'project' ) ) {
		$img_url = has_post_thumbnail() ? get_the_post_thumbnail_url( get_the_ID(), 'vtech-og' ) : $logo;
		$proj = array(
			'@context' => 'https://schema.org',
			'@type'    => 'CreativeWork',
			'name'     => vtech_clean_title(),
			'headline' => vtech_clean_title(),
			'description' => wp_strip_all_tags( get_the_excerpt() ),
			'image'    => $img_url,
			'url'      => get_permalink(),
			'dateCreated' => get_the_date( 'c' ),
			'creator'  => array( '@type' => 'Organization', 'name' => $nap['name'], '@id' => $nap['url'] . '#organization' ),
			'about'    => 'Audio visual installation project by VTECH Audio Visual Solutions in Kenya',
			'locationCreated' => array( '@type' => 'Place', 'address' => array( '@type' => 'PostalAddress', 'addressLocality' => $nap['locality'], 'addressCountry' => 'KE' ) ),
		);
		// Link the project to its sector, technologies and location (v5.59.0).
		$about = array();
		foreach ( array( 'industry' => 'Thing', 'technology' => 'Thing' ) as $tax => $type ) {
			$terms = get_the_terms( get_the_ID(), $tax );
			if ( $terms && ! is_wp_error( $terms ) ) {
				foreach ( $terms as $tm ) { $about[] = array( '@type' => $type, 'name' => $tm->name ); }
			}
		}
		if ( $about ) { $proj['about'] = $about; $proj['keywords'] = implode( ', ', wp_list_pluck( $about, 'name' ) ); }
		$locs = get_the_terms( get_the_ID(), 'project_location' );
		if ( $locs && ! is_wp_error( $locs ) ) {
			$proj['locationCreated'] = array( '@type' => 'Place', 'name' => $locs[0]->name . ', Kenya', 'address' => array( '@type' => 'PostalAddress', 'addressLocality' => $locs[0]->name, 'addressCountry' => 'KE' ) );
		}
		$proj['publisher'] = array( '@id' => $nap['url'] . '#organization' );
		$archive = get_post_type_archive_link( 'project' );
		if ( $archive ) { $proj['isPartOf'] = array( '@type' => 'CollectionPage', 'url' => $archive, 'name' => 'AV installation projects in Kenya' ); }
		$proj['mainEntityOfPage'] = get_permalink();
		vtech_json_ld( $proj );
	}

	if ( is_singular( 'service' ) ) {
		$id = get_the_ID();
		$price = function_exists( 'get_field' ) ? get_field( 'price_from', $id ) : '';
		// v5.60.0: Service pages carry no Rank Math entity graph, so the provider is
		// described in full here (an @id alone cannot resolve on this page).
		$svc = array(
			'@context'    => 'https://schema.org',
			'@type'       => 'Service',
			'@id'         => get_permalink() . '#service',
			'name'        => vtech_clean_title(),
			'serviceType' => vtech_clean_title(),
			'description' => wp_strip_all_tags( get_the_excerpt() ),
			'provider'    => array(
				'@type'     => 'LocalBusiness',
				'@id'       => $nap['url'] . '#organization',
				'name'      => $nap['name'],
				'url'       => $nap['url'],
				'telephone' => $nap['phone'],
				'email'     => $nap['email'],
				'image'     => $logo,
				'address'   => array( '@type' => 'PostalAddress', 'streetAddress' => 'Ground Floor, Mpaka Plaza, Mpaka Road, Westlands', 'addressLocality' => 'Nairobi', 'addressRegion' => 'Nairobi County', 'addressCountry' => 'KE' ),
			),
			'areaServed'  => array(
				array( '@type' => 'City', 'name' => 'Nairobi' ),
				array( '@type' => 'Country', 'name' => 'Kenya' ),
			),
			'availableChannel' => array(
				'@type'           => 'ServiceChannel',
				'serviceUrl'      => home_url( '/book-a-consultation/' ),
				'servicePhone'    => array( '@type' => 'ContactPoint', 'telephone' => $nap['phone'], 'contactType' => 'sales', 'availableLanguage' => array( 'English', 'Swahili' ) ),
			),
			'url'              => get_permalink(),
			'mainEntityOfPage' => get_permalink(),
		);
		if ( has_post_thumbnail( $id ) ) { $svc['image'] = get_the_post_thumbnail_url( $id, 'vtech-og' ); }
		if ( $price ) {
			$svc['offers'] = array( '@type' => 'Offer', 'priceCurrency' => 'KES', 'price' => (string) $price, 'availability' => 'https://schema.org/InStock' );
		}
		vtech_json_ld( $svc );

		// FAQ schema from ACF repeater.
		if ( function_exists( 'get_field' ) ) {
			$faqs = get_field( 'faqs', $id );
			if ( $faqs ) {
				$items = array();
				foreach ( $faqs as $f ) {
					$items[] = array( '@type' => 'Question', 'name' => $f['question'], 'acceptedAnswer' => array( '@type' => 'Answer', 'text' => wp_strip_all_tags( $f['answer'] ) ) );
				}
				vtech_json_ld( array( '@context' => 'https://schema.org', '@type' => 'FAQPage', 'mainEntity' => $items ) );
			}
		}
	}

}, 20 );
