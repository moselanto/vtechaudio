<?php
/**
 * Per-page SEO + schema layer (v5.38.0).
 *
 * Complements inc/seo-local.php (sitewide entity) with page-level work:
 *  - Contact-data guards: a truncated phone/WhatsApp value (e.g. "+2547")
 *    can never reach the header, buttons or schema again; an Instagram field
 *    holding a TikTok URL is ignored; sameAs is de-duplicated and validated.
 *  - Keyword-led default titles + meta descriptions for services, industries,
 *    hire packages, projects and archives (only when the editor has NOT set a
 *    custom Rank Math title/description, so manual edits always win).
 *  - JSON-LD for pages that had none: industry pages (Service + audience),
 *    hire packages (Product + Offer in KES), Equipment Hire (OfferCatalog),
 *    Services archive (ItemList).
 *  - Thin pages (testimonials, single FAQs, duplicate equipment-hire-2) are
 *    set to noindex and removed from the sitemap.
 *
 * @package VTECH_AV
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

/* -------------------------------------------------------------------------
 * 1. Contact-data guards
 * ---------------------------------------------------------------------- */

add_filter( 'vtech_contact', function ( $value, $key ) {
	if ( in_array( $key, array( 'phone', 'whatsapp' ), true ) ) {
		$digits = preg_replace( '/\D+/', '', (string) $value );
		// A Kenyan number in international form has 12 digits (254 + 9).
		if ( strlen( $digits ) < 11 ) {
			$defaults = vtech_contact_defaults();
			return (string) $defaults[ $key ];
		}
	}
	return $value;
}, 5, 2 );

// Instagram field must point at Instagram.
add_filter( 'theme_mod_vtech_instagram', function ( $url ) {
	return ( $url && false === stripos( (string) $url, 'instagram.com' ) ) ? '' : $url;
} );

// Clean sameAs: unique, valid URLs only, WhatsApp link only when complete.
add_filter( 'vtech_business_entity', function ( $entity ) {
	if ( empty( $entity['sameAs'] ) || ! is_array( $entity['sameAs'] ) ) { return $entity; }
	$clean = array();
	foreach ( $entity['sameAs'] as $u ) {
		$u = trim( (string) $u );
		if ( '' === $u || ! wp_http_validate_url( $u ) ) { continue; }
		if ( false !== strpos( $u, 'wa.me/' ) && strlen( preg_replace( '/\D+/', '', $u ) ) < 11 ) { continue; }
		$clean[ untrailingslashit( strtolower( $u ) ) ] = $u;
	}
	$entity['sameAs'] = array_values( $clean );
	return $entity;
}, 20 );

/* -------------------------------------------------------------------------
 * Helpers
 * ---------------------------------------------------------------------- */

function vtech_clean_title( $post = null ) {
	return trim( html_entity_decode( wp_strip_all_tags( get_the_title( $post ) ), ENT_QUOTES, 'UTF-8' ) );
}

function vtech_has_custom_rm( $field ) {
	if ( ! is_singular() ) { return false; }
	return '' !== trim( (string) get_post_meta( get_queried_object_id(), 'rank_math_' . $field, true ) );
}

function vtech_strip_kenya( $t ) {
	return trim( preg_replace( '/\s+(in|across)\s+(Kenya|Nairobi)\s*$/i', '', $t ) );
}

/* -------------------------------------------------------------------------
 * 2. Default titles + descriptions per page type
 * ---------------------------------------------------------------------- */

function vtech_auto_seo_title() {
	if ( is_singular( 'service' ) ) {
		return vtech_strip_kenya( vtech_clean_title() ) . ' in Kenya | Supply & Installation | VTECH';
	}
	if ( is_singular( 'industry' ) ) {
		return vtech_strip_kenya( vtech_clean_title() ) . ' AV Solutions in Kenya | VTECH Audio';
	}
	if ( is_singular( 'hire_package' ) ) {
		return vtech_clean_title() . ' | Sound & PA Hire Nairobi | VTECH';
	}
	if ( is_singular( 'project' ) ) {
		return vtech_clean_title() . ' | AV Project in Kenya | VTECH';
	}
	if ( is_post_type_archive( 'service' ) ) {
		return 'Audio Visual Services in Kenya | Sound, LED, Conference | VTECH';
	}
	if ( is_post_type_archive( 'industry' ) ) {
		return 'AV Solutions by Industry in Kenya | Churches, Hotels, Schools | VTECH';
	}
	if ( is_post_type_archive( 'project' ) ) {
		return 'AV Installation Projects in Kenya | Portfolio | VTECH Audio';
	}
	if ( is_post_type_archive( 'hire_package' ) ) {
		return 'Sound & PA Hire Packages in Nairobi | Prices in KES | VTECH';
	}
	if ( is_page( 'equipment-hire' ) ) {
		return 'Sound, PA, LED Screen & Lighting Hire in Nairobi, Kenya | VTECH';
	}
	if ( is_page( 'about' ) ) {
		return 'About VTECH | Audio Visual Company in Nairobi, Kenya';
	}
	if ( is_page( 'contact' ) ) {
		return 'Contact VTECH Audio | Westlands, Nairobi | Call or WhatsApp';
	}
	if ( is_page( 'book-a-consultation' ) ) {
		return 'Book a Free AV Site Survey in Kenya | 24h Quote | VTECH';
	}
	if ( is_page( 'faq' ) ) {
		return 'AV, Sound System & Equipment Hire FAQs (Kenya) | VTECH';
	}
	if ( is_home() ) {
		return 'AV Guides & Prices in Kenya | Sound, PA, LED Blog | VTECH';
	}
	return '';
}

function vtech_auto_seo_description() {
	$ex = is_singular() ? trim( wp_strip_all_tags( get_the_excerpt( get_queried_object_id() ) ) ) : '';
	$cut = function ( $s ) { return mb_strlen( $s ) > 158 ? rtrim( mb_substr( $s, 0, 155 ), " ,.;:-" ) . '...' : $s; };

	if ( is_singular( 'service' ) ) {
		$t = vtech_strip_kenya( vtech_clean_title() );
		return $cut( $t . ' in Kenya by VTECH: design, supply, installation and support in Nairobi and all 47 counties. Free site survey, fixed quote in 24h.' );
	}
	if ( is_singular( 'industry' ) ) {
		$t = vtech_strip_kenya( vtech_clean_title() );
		return $cut( 'Sound, PA, LED screen, conference and lighting systems for ' . strtolower( $t ) . ' in Kenya. Designed, installed and supported by VTECH. Get a 24h quote.' );
	}
	if ( is_singular( 'hire_package' ) ) {
		return $cut( vtech_clean_title() . ' for events in Nairobi and Kenya: delivery, setup and on-site technician included. Book on WhatsApp. ' . $ex );
	}
	if ( is_singular( 'project' ) && $ex ) {
		return $cut( $ex . ' AV project in Kenya by VTECH.' );
	}
	if ( is_post_type_archive( 'service' ) ) {
		return 'Audio visual services in Kenya: PA and sound systems, LED screens, conference and boardroom AV, stage lighting, acoustics and AV consultation by VTECH.';
	}
	if ( is_post_type_archive( 'hire_package' ) || is_page( 'equipment-hire' ) ) {
		return 'Sound, PA, LED screen and lighting hire in Nairobi and across Kenya. Packages from KES, with delivery, setup and a technician. Book on WhatsApp.';
	}
	if ( is_page( 'contact' ) ) {
		return 'Contact VTECH Audio Visual Solutions, Mpaka Plaza, Westlands, Nairobi. Call or WhatsApp ' . vtech_contact( 'phone' ) . ' for AV quotes and equipment hire.';
	}
	if ( is_page( 'book-a-consultation' ) ) {
		return 'Book a free AV site survey anywhere in Kenya. VTECH designs your sound, LED, conference or lighting system and sends a fixed quote within 24 hours.';
	}
	return '';
}

add_filter( 'rank_math/frontend/title', function ( $title ) {
	if ( is_front_page() || vtech_has_custom_rm( 'title' ) ) { return $title; }
	$auto = vtech_auto_seo_title();
	return $auto ? $auto : $title;
}, 30 );

add_filter( 'rank_math/frontend/description', function ( $desc ) {
	if ( is_front_page() || vtech_has_custom_rm( 'description' ) ) { return $desc; }
	$auto = vtech_auto_seo_description();
	return $auto ? $auto : $desc;
}, 30 );

// Same values for social shares.
add_filter( 'rank_math/opengraph/facebook/og_title', function ( $t ) {
	if ( is_front_page() || vtech_has_custom_rm( 'facebook_title' ) ) { return $t; }
	$a = vtech_auto_seo_title(); return $a ? $a : $t;
}, 30 );
add_filter( 'rank_math/opengraph/facebook/og_description', function ( $d ) {
	if ( is_front_page() || vtech_has_custom_rm( 'facebook_description' ) ) { return $d; }
	$a = vtech_auto_seo_description(); return $a ? $a : $d;
}, 30 );

/* -------------------------------------------------------------------------
 * 3. Thin / duplicate pages: noindex + out of the sitemap
 * ---------------------------------------------------------------------- */

add_filter( 'rank_math/frontend/robots', function ( $robots ) {
	if ( is_singular( array( 'testimonial', 'faq' ) ) || is_page( 'equipment-hire-2' ) ) {
		$robots['index']  = 'noindex';
		$robots['follow'] = 'follow';
	}
	return $robots;
} );

add_filter( 'rank_math/sitemap/exclude_post_type', function ( $exclude, $type ) {
	return in_array( $type, array( 'testimonial', 'faq' ), true ) ? true : $exclude;
}, 10, 2 );

add_filter( 'rank_math/sitemap/entry', function ( $url, $type, $object ) {
	if ( is_object( $object ) && isset( $object->post_name ) && 'equipment-hire-2' === $object->post_name ) { return false; }
	return $url;
}, 10, 3 );

/* -------------------------------------------------------------------------
 * 4. JSON-LD for pages that had none
 * ---------------------------------------------------------------------- */

function vtech_ld( $data ) {
	echo "\n<script type=\"application/ld+json\">" . wp_json_encode( array_merge( array( '@context' => 'https://schema.org' ), $data ), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . "</script>\n";
}

add_action( 'wp_head', function () {
	$org = array( '@id' => home_url( '/' ) . '#organization' );
	$ke  = array( '@type' => 'Country', 'name' => 'Kenya' );

	// Industry landing pages.
	if ( is_singular( 'industry' ) ) {
		$t = vtech_strip_kenya( vtech_clean_title() );
		$node = array(
			'@type'       => 'Service',
			'@id'         => get_permalink() . '#service',
			'name'        => 'Audio visual solutions for ' . $t . ' in Kenya',
			'serviceType' => 'Audio visual installation',
			'description' => vtech_auto_seo_description(),
			'url'         => get_permalink(),
			'provider'    => $org,
			'areaServed'  => $ke,
			'audience'    => array( '@type' => 'Audience', 'audienceType' => $t ),
		);
		if ( has_post_thumbnail() ) { $node['image'] = get_the_post_thumbnail_url( null, 'vtech-og' ); }
		vtech_ld( $node );
		if ( function_exists( 'get_field' ) ) {
			$faqs = get_field( 'faqs' );
			if ( $faqs && is_array( $faqs ) ) {
				$items = array();
				foreach ( $faqs as $f ) {
					if ( empty( $f['question'] ) || empty( $f['answer'] ) ) { continue; }
					$items[] = array( '@type' => 'Question', 'name' => $f['question'], 'acceptedAnswer' => array( '@type' => 'Answer', 'text' => wp_strip_all_tags( $f['answer'] ) ) );
				}
				if ( $items ) { vtech_ld( array( '@type' => 'FAQPage', 'mainEntity' => $items ) ); }
			}
		}
	}

	// Hire packages: Product + Offer.
	if ( is_singular( 'hire_package' ) ) {
		$id    = get_the_ID();
		$price = get_post_meta( $id, 'vtech_pkg_price', true );
		if ( '' === $price ) { $price = get_post_meta( $id, 'price', true ); }
		if ( '' === $price && function_exists( 'get_field' ) ) { $price = get_field( 'price', $id ); }
		$node = array(
			'@type'       => 'Product',
			'@id'         => get_permalink() . '#product',
			'name'        => vtech_clean_title(),
			'description' => vtech_auto_seo_description(),
			'category'    => 'Sound and AV equipment hire',
			'brand'       => array( '@type' => 'Brand', 'name' => 'VTECH Audio Visual Solutions' ),
			'url'         => get_permalink(),
		);
		if ( has_post_thumbnail() ) { $node['image'] = get_the_post_thumbnail_url( null, 'vtech-og' ); }
		if ( $price && is_numeric( preg_replace( '/[^\d.]/', '', (string) $price ) ) ) {
			$node['offers'] = array(
				'@type'         => 'Offer',
				'price'         => preg_replace( '/[^\d.]/', '', (string) $price ),
				'priceCurrency' => 'KES',
				'availability'  => 'https://schema.org/InStock',
				'url'           => get_permalink(),
				'seller'        => $org,
				'areaServed'    => $ke,
			);
		}
		vtech_ld( $node );
	}

	// Equipment Hire page + hire archive: service with a catalogue of packages.
	if ( is_page( 'equipment-hire' ) || is_post_type_archive( 'hire_package' ) ) {
		$pk = get_posts( array( 'post_type' => 'hire_package', 'numberposts' => 20, 'post_status' => 'publish' ) );
		$list = array();
		foreach ( $pk as $p ) {
			$list[] = array( '@type' => 'Offer', 'itemOffered' => array( '@type' => 'Service', 'name' => vtech_clean_title( $p ), 'url' => get_permalink( $p ) ) );
		}
		$node = array(
			'@type'       => 'Service',
			'@id'         => home_url( '/equipment-hire/' ) . '#service',
			'name'        => 'Sound, PA, LED screen and lighting hire in Nairobi',
			'serviceType' => 'Audio visual equipment rental',
			'description' => vtech_auto_seo_description(),
			'url'         => home_url( '/equipment-hire/' ),
			'provider'    => $org,
			'areaServed'  => $ke,
		);
		if ( $list ) { $node['hasOfferCatalog'] = array( '@type' => 'OfferCatalog', 'name' => 'Hire packages', 'itemListElement' => $list ); }
		vtech_ld( $node );
	}

	// Services archive: ItemList of services.
	if ( is_post_type_archive( 'service' ) ) {
		$sv = get_posts( array( 'post_type' => 'service', 'numberposts' => 30, 'orderby' => 'menu_order', 'order' => 'ASC' ) );
		$items = array();
		foreach ( $sv as $i => $s ) {
			$items[] = array( '@type' => 'ListItem', 'position' => $i + 1, 'name' => vtech_clean_title( $s ), 'url' => get_permalink( $s ) );
		}
		if ( $items ) { vtech_ld( array( '@type' => 'ItemList', 'name' => 'Audio visual services in Kenya', 'itemListElement' => $items ) ); }
	}
}, 22 );
