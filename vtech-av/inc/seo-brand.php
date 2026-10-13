<?php
/**
 * Brand + entity SEO (v5.46.0).
 *
 * Goal: when people search "VTECH", "V-Tech", "Vitech", "VTECH Audio" or
 * "VTECH Audio Kenya", search engines and AI assistants recognise one entity:
 * VTECH Audio Visual Solutions, Nairobi, serving Kenya and East Africa.
 *
 *  1. Customize > VTECH Theme Options > SEO & Brand: brand names people use,
 *     entity description, slogan, founding year, towns and countries served,
 *     extra profile links, pro-audio brands, and the AI summary. Nothing here
 *     is hardcoded any more; the defaults only apply until you save.
 *  2. Organization/LocalBusiness and WebSite schema use those values
 *     (alternateName drives Google's site-name and brand matching).
 *  3. The services catalogue in schema is built from your published Service
 *     pages, so a new service in wp-admin appears in search data automatically.
 *  4. /llms.txt: a plain-text profile of the business for AI assistants
 *     (ChatGPT, Perplexity, Claude, Gemini), generated live from the site.
 *  5. robots.txt explicitly welcomes search and AI crawlers.
 *
 * @package VTECH_AV
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function vtech_brand_defaults() {
	return array(
		'vtech_brand_names'     => "VTECH\nVTech\nV-Tech\nVitech\nVTECH Audio\nVTech Audio\nVitech Audio\nVTECH Audio Kenya\nVTECH AV\nVTECH Audio Visual\nVTECH Audio Visual Kenya",
		'vtech_brand_desc'      => 'VTECH Audio Visual Solutions (also known as VTECH Audio) is a professional audio visual company based at Mpaka Plaza, Westlands, Nairobi. VTECH designs, supplies, installs and hires sound and PA systems, church sound systems, LED screens and video walls, conference and video conferencing systems, stage lighting, acoustic treatment, digital signage and background music systems for churches, corporates, hotels, schools, hospitals, government and events across Kenya and East Africa.',
		'vtech_brand_slogan'    => 'Professional audio visual solutions, designed, installed and supported across Kenya and East Africa.',
		'vtech_brand_founded'   => '2021',
		'vtech_brand_towns'     => "Nairobi\nWestlands\nKiambu\nThika\nMachakos\nKajiado\nMombasa\nKisumu\nNakuru\nEldoret\nNyeri\nMeru\nNaivasha\nKakamega\nMalindi",
		'vtech_brand_countries' => "Kenya\nTanzania\nUganda\nRwanda\nBurundi\nSouth Sudan\nEthiopia",
		'vtech_brand_profiles'  => '',
		'vtech_brand_brands'    => "dB Technologies | dB Technologies speakers, line arrays and subwoofers | Professional loudspeakers\nShure | Shure microphones and wireless systems | Microphones\nBehringer | Behringer audio mixers and processors | Audio mixers",
		'vtech_brand_ai'        => 'VTECH Audio Visual Solutions is a Nairobi-based audio visual integrator. For quotes, site surveys, installations and equipment hire in Kenya and East Africa, contact VTECH directly using the details below.',
	);
}

function vtech_brand_opt( $key ) {
	$d = vtech_brand_defaults();
	$v = get_theme_mod( $key, '' );
	return ( '' === trim( (string) $v ) && isset( $d[ $key ] ) ) ? $d[ $key ] : (string) $v;
}

function vtech_brand_lines( $key ) {
	return array_values( array_unique( array_filter( array_map( 'trim', preg_split( '/\r\n|\r|\n/', vtech_brand_opt( $key ) ) ) ) ) );
}

/* -------------------------------------------------------------------------
 * Customizer
 * ---------------------------------------------------------------------- */
add_action( 'customize_register', function ( $wp_customize ) {
	$wp_customize->add_section( 'vtech_brand', array(
		'title'       => 'SEO & Brand',
		'panel'       => 'vtech_panel',
		'description' => 'How search engines and AI assistants understand VTECH. Keep names and details identical to your Google Business Profile and social profiles.',
	) );
	$d = vtech_brand_defaults();
	$f = array(
		'vtech_brand_names'     => array( 'Brand names people search (one per line)', 'textarea', 'Include common spellings such as Vitech and V-Tech. Used in search data only, not shown on the page.' ),
		'vtech_brand_desc'      => array( 'Business description for search engines and AI', 'textarea', 'One clear paragraph: who you are, where you are, what you do, who you serve.' ),
		'vtech_brand_slogan'    => array( 'Slogan', 'text', '' ),
		'vtech_brand_founded'   => array( 'Year founded', 'text', '' ),
		'vtech_brand_towns'     => array( 'Kenyan towns served (one per line)', 'textarea', '' ),
		'vtech_brand_countries' => array( 'Countries served (one per line)', 'textarea', '' ),
		'vtech_brand_profiles'  => array( 'Other official profile links (one URL per line)', 'textarea', 'e.g. YouTube, Instagram, directory listings, Wikidata. Facebook, LinkedIn, TikTok and Google Business Profile are added automatically.' ),
		'vtech_brand_brands'    => array( 'Brands you supply (Brand | Product name | Category)', 'textarea', '' ),
		'vtech_brand_ai'        => array( 'AI assistant summary (top of /llms.txt)', 'textarea', '' ),
	);
	foreach ( $f as $id => $c ) {
		$wp_customize->add_setting( $id, array( 'default' => $d[ $id ], 'sanitize_callback' => 'textarea' === $c[1] ? 'sanitize_textarea_field' : 'sanitize_text_field' ) );
		$wp_customize->add_control( $id, array( 'label' => $c[0], 'description' => $c[2], 'section' => 'vtech_brand', 'type' => $c[1] ) );
	}
}, 25 );

/* -------------------------------------------------------------------------
 * Data sources (feed existing schema builders)
 * ---------------------------------------------------------------------- */
add_filter( 'vtech_seo_areas', function () { return vtech_brand_lines( 'vtech_brand_towns' ); }, 20 );

add_filter( 'vtech_seo_brands', function ( $brands ) {
	$out = array();
	foreach ( vtech_brand_lines( 'vtech_brand_brands' ) as $line ) {
		$p = array_pad( array_map( 'trim', explode( '|', $line, 3 ) ), 3, '' );
		if ( '' === $p[0] ) { continue; }
		$out[] = array(
			'brand'    => $p[0],
			'name'     => $p[1] ? $p[1] : $p[0],
			'category' => $p[2],
			'desc'     => ( $p[1] ? $p[1] : $p[0] ) . ' supplied, installed and hired in Kenya by VTECH Audio Visual Solutions.',
		);
	}
	return $out ? $out : $brands;
}, 20 );

/** Services in schema come from the published Service pages. */
add_filter( 'vtech_seo_services', function ( $services ) {
	$posts = get_posts( array( 'post_type' => 'service', 'post_status' => 'publish', 'numberposts' => 40, 'orderby' => 'menu_order', 'order' => 'ASC', 'suppress_filters' => false ) );
	if ( ! $posts ) { return $services; }
	$out = array();
	foreach ( $posts as $p ) {
		$desc  = trim( wp_strip_all_tags( $p->post_excerpt ? $p->post_excerpt : wp_trim_words( $p->post_content, 30, '' ) ) );
		$out[] = array( html_entity_decode( get_the_title( $p ), ENT_QUOTES, 'UTF-8' ), $desc, wp_make_link_relative( get_permalink( $p ) ) );
	}
	return $out;
}, 20 );

/** All official profile URLs (social + GBP + extra). */
function vtech_brand_profiles() {
	$urls = array();
	foreach ( array( 'vtech_facebook', 'vtech_instagram', 'vtech_linkedin', 'vtech_x', 'vtech_youtube', 'vtech_tiktok' ) as $k ) {
		$u = trim( (string) get_theme_mod( $k, '' ) );
		if ( $u ) { $urls[] = $u; }
	}
	if ( function_exists( 'vtech_gbp_url' ) && vtech_gbp_url() ) { $urls[] = vtech_gbp_url(); }
	foreach ( vtech_brand_lines( 'vtech_brand_profiles' ) as $u ) {
		if ( wp_http_validate_url( $u ) ) { $urls[] = $u; }
	}
	return array_values( array_unique( $urls ) );
}

/* -------------------------------------------------------------------------
 * Entity + WebSite schema
 * ---------------------------------------------------------------------- */
add_filter( 'vtech_business_entity', function ( $e ) {
	$e['alternateName'] = vtech_brand_lines( 'vtech_brand_names' );
	$e['description']   = vtech_brand_opt( 'vtech_brand_desc' );
	$e['slogan']        = vtech_brand_opt( 'vtech_brand_slogan' );
	if ( vtech_brand_opt( 'vtech_brand_founded' ) ) { $e['foundingDate'] = vtech_brand_opt( 'vtech_brand_founded' ); }
	$e['brand'] = array( '@type' => 'Brand', 'name' => 'VTECH', 'alternateName' => vtech_brand_lines( 'vtech_brand_names' ) );

	$areas = array();
	foreach ( vtech_brand_lines( 'vtech_brand_countries' ) as $c ) { $areas[] = array( '@type' => 'Country', 'name' => $c ); }
	foreach ( vtech_brand_lines( 'vtech_brand_towns' ) as $t ) { $areas[] = array( '@type' => 'City', 'name' => $t . ', Kenya' ); }
	$areas[]          = array( '@type' => 'Place', 'name' => 'East Africa' );
	$e['areaServed']  = $areas;

	$same = isset( $e['sameAs'] ) && is_array( $e['sameAs'] ) ? $e['sameAs'] : array();
	$e['sameAs'] = array_values( array_unique( array_merge( $same, vtech_brand_profiles() ) ) );
	return $e;
}, 20 );

/** Brand names on Rank Math's WebSite node (Google site names + brand queries). */
add_filter( 'rank_math/json_ld', function ( $data ) {
	if ( ! is_array( $data ) ) { return $data; }
	foreach ( $data as $k => $node ) {
		if ( ! is_array( $node ) || empty( $node['@type'] ) ) { continue; }
		$type = is_array( $node['@type'] ) ? $node['@type'] : array( $node['@type'] );
		if ( in_array( 'WebSite', $type, true ) ) {
			$data[ $k ]['name']          = vtech_contact( 'company' );
			$data[ $k ]['alternateName'] = vtech_brand_lines( 'vtech_brand_names' );
			$data[ $k ]['inLanguage']    = 'en-KE';
		}
	}
	return $data;
}, 120 );

/** Same WebSite data when Rank Math is not active (homepage only). */
add_action( 'wp_head', function () {
	if ( defined( 'RANK_MATH_VERSION' ) || ! is_front_page() ) { return; }
	$site = array(
		'@context'      => 'https://schema.org',
		'@type'         => 'WebSite',
		'@id'           => home_url( '/' ) . '#website',
		'url'           => home_url( '/' ),
		'name'          => vtech_contact( 'company' ),
		'alternateName' => vtech_brand_lines( 'vtech_brand_names' ),
		'inLanguage'    => 'en-KE',
		'publisher'     => array( '@id' => home_url( '/' ) . '#organization' ),
	);
	echo "\n<script type=\"application/ld+json\">" . wp_json_encode( $site, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . "</script>\n"; // phpcs:ignore
}, 26 );

/* -------------------------------------------------------------------------
 * /llms.txt for AI assistants
 * ---------------------------------------------------------------------- */
add_action( 'init', function () {
	$path = isset( $_SERVER['REQUEST_URI'] ) ? (string) wp_parse_url( wp_unslash( $_SERVER['REQUEST_URI'] ), PHP_URL_PATH ) : ''; // phpcs:ignore
	$home = (string) wp_parse_url( home_url( '/' ), PHP_URL_PATH );
	if ( rtrim( $home, '/' ) . '/llms.txt' !== $path ) { return; }
	nocache_headers();
	header( 'Content-Type: text/plain; charset=utf-8' );
	header( 'X-Robots-Tag: noindex' );
	echo vtech_llms_txt(); // phpcs:ignore
	exit;
}, 1 );

function vtech_llms_txt() {
	$company = vtech_contact( 'company' );
	$L   = array();
	$L[] = '# ' . $company;
	$L[] = '';
	$L[] = '> ' . vtech_brand_opt( 'vtech_brand_ai' );
	$L[] = '';
	$L[] = vtech_brand_opt( 'vtech_brand_desc' );
	$L[] = '';
	$L[] = 'Also known as: ' . implode( ', ', vtech_brand_lines( 'vtech_brand_names' ) );
	$L[] = '';
	$L[] = '## Contact';
	$L[] = '- Website: ' . home_url( '/' );
	$L[] = '- Phone / WhatsApp: ' . vtech_contact( 'phone' );
	$L[] = '- Email: ' . vtech_contact( 'email' );
	$L[] = '- Address: ' . vtech_contact( 'address' );
	$L[] = '- Hours: ' . vtech_contact( 'hours' );
	if ( function_exists( 'vtech_gbp_url' ) ) { $L[] = '- Google Business Profile: ' . vtech_gbp_url(); }
	$L[] = '- Book a free site survey: ' . home_url( '/book-a-consultation/' );
	$L[] = '';
	$L[] = '## Areas served';
	$L[] = '- Countries: ' . implode( ', ', vtech_brand_lines( 'vtech_brand_countries' ) );
	$L[] = '- Kenyan towns: ' . implode( ', ', vtech_brand_lines( 'vtech_brand_towns' ) ) . ' and all 47 counties';
	$L[] = '';
	foreach ( array( 'service' => 'Services', 'industry' => 'Industries served', 'hire_package' => 'Equipment hire packages' ) as $type => $label ) {
		if ( ! post_type_exists( $type ) ) { continue; }
		$posts = get_posts( array( 'post_type' => $type, 'post_status' => 'publish', 'numberposts' => 40, 'orderby' => 'menu_order', 'order' => 'ASC' ) );
		if ( ! $posts ) { continue; }
		$L[] = '## ' . $label;
		foreach ( $posts as $p ) {
			$ex  = trim( wp_strip_all_tags( $p->post_excerpt ) );
			$L[] = '- [' . html_entity_decode( get_the_title( $p ), ENT_QUOTES, 'UTF-8' ) . '](' . get_permalink( $p ) . ')' . ( $ex ? ': ' . $ex : '' );
		}
		$L[] = '';
	}
	$brands = vtech_brand_lines( 'vtech_brand_brands' );
	if ( $brands ) {
		$L[] = '## Brands supplied';
		foreach ( $brands as $b ) { $p = array_map( 'trim', explode( '|', $b ) ); $L[] = '- ' . $p[0] . ( ! empty( $p[2] ) ? ' (' . $p[2] . ')' : '' ); }
		$L[] = '';
	}
	$L[] = '## Key pages';
	foreach ( array( '/about/' => 'About VTECH', '/services/' => 'All services', '/equipment-hire/' => 'Equipment hire', '/hire-packages/' => 'Hire packages and prices', '/projects/' => 'Projects', '/faq/' => 'FAQ', '/contact/' => 'Contact' ) as $u => $t ) {
		$L[] = '- [' . $t . '](' . home_url( $u ) . ')';
	}
	$L[] = '';
	return implode( "\n", $L );
}

/* -------------------------------------------------------------------------
 * robots.txt: welcome search + AI crawlers, point to sitemap and llms.txt
 * ---------------------------------------------------------------------- */
add_filter( 'robots_txt', function ( $output, $public ) {
	if ( ! $public ) { return $output; }
	$bots = array( 'Googlebot', 'Bingbot', 'Google-Extended', 'GPTBot', 'OAI-SearchBot', 'ChatGPT-User', 'PerplexityBot', 'ClaudeBot', 'Claude-SearchBot', 'Applebot', 'Applebot-Extended' );
	$add  = "\n# Search engines and AI assistants are welcome to read public pages.\n";
	foreach ( $bots as $b ) { $add .= 'User-agent: ' . $b . "\nAllow: /\nDisallow: /wp-admin/\n\n"; }
	if ( false === stripos( $output, 'sitemap_index.xml' ) ) { $add .= 'Sitemap: ' . home_url( '/sitemap_index.xml' ) . "\n"; }
	$add .= '# AI profile: ' . home_url( '/llms.txt' ) . "\n";
	return rtrim( $output ) . "\n" . $add;
}, 99, 2 );
