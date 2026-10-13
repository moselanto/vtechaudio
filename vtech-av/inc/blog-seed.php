<?php
/**
 * Blog articles seed (v5.56.0).
 *
 * Publishes the SEO articles stored in /content/blog/*.json as WordPress posts
 * with their original (backdated) publish dates, featured images from
 * /assets/img/blog/, category, tags, Rank Math title / description / focus
 * keywords and FAQs. Runs when an administrator opens wp-admin and keeps
 * retrying until every article exists. Existing posts with the same slug are
 * never overwritten.
 *
 * Also renders each post's FAQs under the article and outputs FAQPage schema.
 *
 * @package vtech-av
 */

defined( 'ABSPATH' ) || exit;

/** All article definitions from the theme, keyed by slug. */
function vtech_blog_seed_items() {
	$out = array();
	foreach ( (array) glob( get_template_directory() . '/content/blog/*.json' ) as $file ) {
		$data = json_decode( (string) file_get_contents( $file ), true ); // phpcs:ignore
		if ( is_array( $data ) && ! empty( $data['slug'] ) && ! empty( $data['html'] ) ) {
			$out[ $data['slug'] ] = $data;
		}
	}
	return $out;
}

/** Get or create a category by name; returns term ID or 0. */
function vtech_blog_seed_category( $name ) {
	$name = trim( (string) $name );
	if ( '' === $name ) { return 0; }
	$t = term_exists( $name, 'category' );
	if ( ! $t ) { $t = wp_insert_term( $name, 'category' ); }
	if ( is_wp_error( $t ) ) { return 0; }
	return (int) ( is_array( $t ) ? $t['term_id'] : $t );
}

/** Copy a theme image into the Media Library and attach it as the featured image. */
function vtech_blog_seed_image( $post_id, $rel, $alt, $title ) {
	$src = get_template_directory() . '/assets/img/' . ltrim( (string) $rel, '/' );
	if ( ! $rel || ! file_exists( $src ) ) { return 0; }
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';
	$tmp = wp_tempnam( basename( $src ) );
	if ( ! $tmp || ! @copy( $src, $tmp ) ) { return 0; } // phpcs:ignore
	$att = media_handle_sideload( array( 'name' => basename( $src ), 'tmp_name' => $tmp ), $post_id, $title );
	if ( is_wp_error( $att ) ) { @unlink( $tmp ); return 0; } // phpcs:ignore
	update_post_meta( $att, '_wp_attachment_image_alt', sanitize_text_field( $alt ) );
	set_post_thumbnail( $post_id, $att );
	return (int) $att;
}

/** Create any missing articles. Returns the number created. */
function vtech_blog_seed_run() {
	$created = 0;
	$missing = 0;
	foreach ( vtech_blog_seed_items() as $slug => $a ) {
		if ( get_page_by_path( $slug, OBJECT, 'post' ) ) { continue; }
		$date = ! empty( $a['date'] ) ? $a['date'] : current_time( 'mysql' );
		$cat  = vtech_blog_seed_category( $a['category'] ?? '' );
		$id   = wp_insert_post( array(
			'post_type'     => 'post',
			'post_status'   => 'publish',
			'post_title'    => wp_strip_all_tags( $a['title'] ),
			'post_name'     => $slug,
			'post_content'  => wp_kses_post( $a['html'] ),
			'post_excerpt'  => sanitize_text_field( $a['excerpt'] ?? '' ),
			'post_date'     => $date,
			'post_date_gmt' => get_gmt_from_date( $date ),
			'post_category' => $cat ? array( $cat ) : array(),
			'tags_input'    => array_map( 'sanitize_text_field', (array) ( $a['tags'] ?? array() ) ),
			'comment_status'=> 'closed',
		), true );
		if ( is_wp_error( $id ) || ! $id ) { $missing++; continue; }

		if ( ! empty( $a['seo_title'] ) )       { update_post_meta( $id, 'rank_math_title', sanitize_text_field( $a['seo_title'] ) ); }
		if ( ! empty( $a['seo_description'] ) ) { update_post_meta( $id, 'rank_math_description', sanitize_text_field( $a['seo_description'] ) ); }
		if ( ! empty( $a['focus_keywords'] ) )  { update_post_meta( $id, 'rank_math_focus_keyword', sanitize_text_field( $a['focus_keywords'] ) ); }
		if ( ! empty( $a['faqs'] ) && is_array( $a['faqs'] ) ) {
			$faqs = array();
			foreach ( $a['faqs'] as $f ) {
				if ( ! empty( $f['q'] ) && ! empty( $f['a'] ) ) { $faqs[] = array( 'q' => sanitize_text_field( $f['q'] ), 'a' => sanitize_textarea_field( $f['a'] ) ); }
			}
			update_post_meta( $id, 'vtech_post_faqs', $faqs );
		}
		vtech_blog_seed_image( $id, $a['image'] ?? '', $a['image_alt'] ?? $a['title'], $a['title'] );
		$created++;
	}
	if ( 0 === $missing ) { update_option( 'vtech_blog_seed_done', '1', false ); }
	return $created;
}

// Run for an administrator in wp-admin until every article exists.
add_action( 'admin_init', function () {
	if ( wp_doing_ajax() || ! current_user_can( 'manage_options' ) ) { return; }
	$items = vtech_blog_seed_items();
	if ( ! $items ) { return; }
	$need = false;
	foreach ( array_keys( $items ) as $slug ) { if ( ! get_page_by_path( $slug, OBJECT, 'post' ) ) { $need = true; break; } }
	if ( ! $need ) { return; }
	$n = vtech_blog_seed_run();
	if ( $n ) { set_transient( 'vtech_blog_seed_notice', $n, 300 ); }
}, 30 );

add_action( 'admin_notices', function () {
	$n = get_transient( 'vtech_blog_seed_notice' );
	if ( false === $n ) { return; }
	delete_transient( 'vtech_blog_seed_notice' );
	printf( '<div class="notice notice-success is-dismissible"><p>%s</p></div>', esc_html( sprintf( 'VTECH blog: %d articles published with their original dates, featured images and SEO settings.', (int) $n ) ) );
} );

/* -------------------------------------------------------------------------
 * Post FAQs: visible section under the article + FAQPage schema.
 * ---------------------------------------------------------------------- */

function vtech_post_faqs( $post_id = 0 ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	$f       = get_post_meta( $post_id, 'vtech_post_faqs', true );
	return is_array( $f ) ? $f : array();
}

add_filter( 'the_content', function ( $content ) {
	if ( ! is_singular( 'post' ) || ! in_the_loop() || ! is_main_query() ) { return $content; }
	$faqs = vtech_post_faqs();
	if ( ! $faqs ) { return $content; }
	$html = '<section class="post-faq" id="faq"><h2>' . esc_html__( 'Frequently asked questions', 'vtech-av' ) . '</h2>';
	foreach ( $faqs as $i => $f ) {
		$html .= '<details class="svx-q"' . ( 0 === $i ? ' open' : '' ) . '><summary>' . esc_html( $f['q'] ) . '</summary><p>' . esc_html( $f['a'] ) . '</p></details>';
	}
	return $content . $html . '</section>';
}, 20 );

add_action( 'wp_head', function () {
	if ( ! is_singular( 'post' ) ) { return; }
	$faqs = vtech_post_faqs( get_queried_object_id() );
	if ( ! $faqs ) { return; }
	$items = array();
	foreach ( $faqs as $f ) {
		$items[] = array( '@type' => 'Question', 'name' => $f['q'], 'acceptedAnswer' => array( '@type' => 'Answer', 'text' => $f['a'] ) );
	}
	echo "\n<script type=\"application/ld+json\">" . wp_json_encode( array( '@context' => 'https://schema.org', '@type' => 'FAQPage', '@id' => get_permalink( get_queried_object_id() ) . '#faq', 'mainEntity' => $items ), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . "</script>\n"; // phpcs:ignore
}, 26 );
