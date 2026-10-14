<?php
/**
 * SEO + schema boost (v5.59.0).
 *
 *  1. Specific FAQs for the original service pages (sound systems, LED screens,
 *     conference, lighting, acoustics, consultation). They are shown on the
 *     page AND sent to Google as FAQPage data. Anything typed in the
 *     "Service page content" box still takes priority.
 *  2. Internal links: each service page lists the matching blog guides, and
 *     each article links back to the services it covers.
 *  3. Articles: author box ("VTECH engineering team"), and the Rank Math
 *     Article schema credits the business instead of the WordPress username.
 *  4. Homepage social image switched (once) to the branded share image.
 *
 * Content is deliberately factual: no invented prices, clients or figures.
 *
 * @package VTECH_AV
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

/* -------------------------------------------------------------------------
 * 1. Service FAQs
 * ---------------------------------------------------------------------- */

function vtech_core_service_faqs( $slug ) {
	$f = array(
		'sound-systems' => array(
			array( 'What does a professional sound system installation include?', 'A site survey, a speaker coverage plan for the room, supply of speakers, amplifiers, microphones and mixer, cabling, installation, tuning, and training for your team. Every installation includes 12 months of support.' ),
			array( 'Which speaker and microphone brands do you install?', 'We supply and install dB Technologies speakers and line arrays and Shure wired and wireless microphones, plus analogue and digital mixers matched to the number of inputs you need.' ),
			array( 'How do you make speech clear in a large hall or church?', 'We plan speaker positions so every seat is covered evenly, set the system up with proper tuning and delay where needed, and advise on acoustic treatment when echo is the real problem.' ),
			array( 'Can you upgrade our existing PA system instead of replacing it?', 'Yes. We test what you already have and reuse compatible speakers, amplifiers and cabling where it makes sense, then add only what is needed.' ),
			array( 'How long does a sound system installation take?', 'Most rooms are installed in one to five days once equipment is on site. We confirm the timeline in your written quote after the site survey.' ),
		),
		'led-screens' => array(
			array( 'Which LED screen pixel pitch do I need?', 'It depends on how close people sit or stand. Smaller pixel pitch suits close viewing such as boardrooms and churches; larger pitch suits outdoor and long-distance viewing. We recommend the right pitch after a site survey.' ),
			array( 'Indoor or outdoor LED screen: what is the difference?', 'Outdoor LED screens are brighter and weather-rated for sun and rain. Indoor screens have finer pixel pitch for sharper images at shorter viewing distances.' ),
			array( 'Do you install LED video walls in churches and auditoriums?', 'Yes. We supply, mount, wire and configure LED walls for churches, auditoriums, conference centres and retail spaces, including the video processor and content source.' ),
			array( 'Can I hire an LED screen for an event in Nairobi?', 'Yes. LED screens are available for event hire in Nairobi and across Kenya, delivered, built and operated by our technicians.' ),
			array( 'Do you provide support after an LED screen is installed?', 'Every installation includes a 12-month support window, with maintenance contracts available afterwards.' ),
		),
		'conference-systems' => array(
			array( 'What equipment does a boardroom AV system need?', 'Usually a display or LED wall, a conference camera, ceiling or table microphones, speakers, and simple one-touch control. We size each part to the room and how you hold meetings.' ),
			array( 'Do your meeting rooms work with Microsoft Teams and Zoom?', 'Yes. We design rooms around the platform you use so meetings start quickly and remote participants see and hear the room clearly.' ),
			array( 'Do you install delegate microphone systems?', 'Yes. We install delegate and discussion microphone systems for boardrooms, county assemblies and conference halls, with recording and streaming options.' ),
			array( 'Can you fix echo and poor audio in our existing boardroom?', 'Often yes. We check microphone placement, speaker setup and room acoustics, and recommend the smallest change that fixes the problem.' ),
			array( 'Do you support conference systems after installation?', 'Every installation includes a 12-month support window, with maintenance contracts available.' ),
		),
		'lighting' => array(
			array( 'What stage lighting does a church or auditorium need?', 'Typically front wash for faces, back and side lighting for depth, and a lighting console with saved scenes. We design around your stage size, ceiling height and how the space is used.' ),
			array( 'Do you install moving heads and LED stage lights?', 'Yes. We supply, rig and program LED fixtures and moving heads, and set up the control desk so your team can run services and events easily.' ),
			array( 'Can you light a stage for live streaming and video?', 'Yes. We plan lighting so faces look natural on camera, which is different from lighting for the room alone.' ),
			array( 'Do you offer stage lighting hire for events?', 'Yes. Stage lighting is available for event hire in Nairobi and across Kenya, with setup and an on-site technician.' ),
		),
		'acoustic-solutions' => array(
			array( 'What is the difference between acoustic treatment and soundproofing?', 'Acoustic treatment controls echo inside a room so speech and music are clear. Soundproofing stops sound travelling in or out of a room. Many projects need one, not both.' ),
			array( 'Why does our hall sound echoey even with a good PA system?', 'Hard walls, floors and high ceilings reflect sound, so it keeps bouncing around and blurs speech. Absorption panels placed in the right spots reduce that reverberation.' ),
			array( 'Which spaces do you treat?', 'Churches, auditoriums, boardrooms, studios, classrooms and offices across Kenya.' ),
			array( 'Do you measure the room before recommending panels?', 'Yes. We assess the room during a free site survey and recommend treatment based on its size, finishes and use.' ),
		),
		'consultation' => array(
			array( 'Is the site survey really free?', 'Yes. We visit your venue, understand how you use the space and take measurements at no cost and with no obligation.' ),
			array( 'What do I receive after the consultation?', 'A written technical design and a fixed, itemised quotation within 24 hours in Nairobi and 48 hours upcountry.' ),
			array( 'Can you design a system that another contractor will install?', 'Yes. We can provide the system design and equipment specification only, or design, supply and install everything ourselves.' ),
			array( 'Do you consult for projects outside Nairobi?', 'Yes. We work across all 47 counties in Kenya and on select projects in East Africa.' ),
		),
	);
	if ( empty( $f[ $slug ] ) ) { return array(); }
	$out = array();
	foreach ( $f[ $slug ] as $r ) { $out[] = array( 'question' => $r[0], 'answer' => $r[1] ); }
	return $out;
}

/** FAQs shown when a service has no FAQs in ACF or the Service page content box. */
function vtech_service_fallback_faqs( $post_id ) {
	$faqs = vtech_core_service_faqs( get_post_field( 'post_name', $post_id ) );
	if ( $faqs ) { return $faqs; }
	$title = wp_strip_all_tags( get_the_title( $post_id ) );
	return array(
		array( 'question' => sprintf( 'How much does %s cost in Kenya?', $title ), 'answer' => 'Pricing depends on your space, coverage and equipment tier. After a free site survey we provide a fixed written quote within 24 hours.' ),
		array( 'question' => 'Do you cover locations outside Nairobi?', 'answer' => 'Yes. We install and support across all 47 counties in Kenya and select projects across East Africa.' ),
		array( 'question' => 'Do you provide maintenance and support?', 'answer' => 'Every installation includes a 12-month support window, with annual maintenance contracts available.' ),
		array( 'question' => 'Can you work with our existing equipment?', 'answer' => 'Yes. We assess and integrate compatible existing systems wherever possible to protect your investment.' ),
	);
}

/* FAQPage schema for services whose FAQs come from the fallback (the other
 * cases are already covered by inc/schema.php and inc/seo-services.php). */
add_action( 'wp_head', function () {
	if ( ! is_singular( 'service' ) ) { return; }
	$id = get_queried_object_id();
	if ( function_exists( 'get_field' ) && get_field( 'faqs', $id ) ) { return; }
	$box = function_exists( 'vtech_service_content' ) ? vtech_service_content( $id ) : null;
	if ( $box && ! empty( $box['faqs'] ) ) { return; }
	$q = array();
	foreach ( vtech_service_fallback_faqs( $id ) as $f ) {
		$q[] = array( '@type' => 'Question', 'name' => $f['question'], 'acceptedAnswer' => array( '@type' => 'Answer', 'text' => $f['answer'] ) );
	}
	if ( ! $q ) { return; }
	echo "\n<script type=\"application/ld+json\">" . wp_json_encode( array( '@context' => 'https://schema.org', '@type' => 'FAQPage', '@id' => get_permalink( $id ) . '#faq', 'mainEntity' => $q ), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . "</script>\n"; // phpcs:ignore
}, 26 );

/* -------------------------------------------------------------------------
 * 2. Internal links between services and guides
 * ---------------------------------------------------------------------- */

function vtech_service_guide_map() {
	return apply_filters( 'vtech_service_guide_map', array(
		'sound-systems'              => array( 'shure-microphones-kenya-guide', 'db-technologies-speakers-kenya', 'church-sound-system-kenya-guide' ),
		'church-sound-systems'       => array( 'church-sound-system-kenya-guide', 'shure-microphones-kenya-guide', 'db-technologies-speakers-kenya' ),
		'led-screens'                => array( 'led-screens-kenya-guide', 'event-sound-led-lighting-hire-nairobi' ),
		'digital-signage'            => array( 'led-screens-kenya-guide' ),
		'conference-systems'         => array( 'boardroom-conference-room-solutions-kenya', 'shure-microphones-kenya-guide' ),
		'video-conferencing-systems' => array( 'boardroom-conference-room-solutions-kenya' ),
		'lighting'                   => array( 'event-sound-led-lighting-hire-nairobi', 'church-sound-system-kenya-guide' ),
		'acoustic-solutions'         => array( 'church-sound-system-kenya-guide', 'boardroom-conference-room-solutions-kenya' ),
		'consultation'               => array( 'boardroom-conference-room-solutions-kenya', 'led-screens-kenya-guide', 'church-sound-system-kenya-guide' ),
		'background-music-systems'   => array( 'db-technologies-speakers-kenya' ),
		'av-maintenance-support'     => array( 'church-sound-system-kenya-guide', 'boardroom-conference-room-solutions-kenya' ),
	) );
}

function vtech_published_by_slug( $slug, $type ) {
	$p = get_page_by_path( $slug, OBJECT, $type );
	return ( $p && 'publish' === $p->post_status ) ? $p : null;
}

/** "Guides for this service" block on service pages. */
function vtech_render_service_guides( $service_id ) {
	$map  = vtech_service_guide_map();
	$slug = get_post_field( 'post_name', $service_id );
	if ( empty( $map[ $slug ] ) ) { return; }
	$posts = array_filter( array_map( function ( $s ) { return vtech_published_by_slug( $s, 'post' ); }, $map[ $slug ] ) );
	if ( ! $posts ) { return; }
	?>
	<section class="section svc-guides" aria-labelledby="svc-guides-title"><div class="container">
		<div class="section-head">
			<p class="eyebrow"><?php esc_html_e( 'Guides & insights', 'vtech-av' ); ?></p>
			<h2 id="svc-guides-title" class="section__title"><?php esc_html_e( 'Read before you buy', 'vtech-av' ); ?></h2>
		</div>
		<div class="blog-grid">
		<?php foreach ( $posts as $p ) : $cat = get_the_category( $p->ID ); ?>
			<article class="blog-card">
				<a class="blog-card__media" href="<?php echo esc_url( get_permalink( $p ) ); ?>" tabindex="-1" aria-hidden="true"><?php echo get_the_post_thumbnail( $p, 'vtech-card', array( 'loading' => 'lazy', 'alt' => get_the_title( $p ) ) ); ?></a>
				<div class="blog-card__body">
					<p class="blog-card__meta"><?php if ( $cat ) : ?><span class="blog-card__cat"><?php echo esc_html( $cat[0]->name ); ?></span><?php endif; ?><time datetime="<?php echo esc_attr( get_the_date( 'c', $p ) ); ?>"><?php echo esc_html( get_the_date( 'j M Y', $p ) ); ?></time></p>
					<h3 class="blog-card__title"><a href="<?php echo esc_url( get_permalink( $p ) ); ?>"><?php echo esc_html( get_the_title( $p ) ); ?></a></h3>
					<span class="svx-link" aria-hidden="true"><?php esc_html_e( 'Read guide', 'vtech-av' ); ?> &rarr;</span>
				</div>
			</article>
		<?php endforeach; ?>
		</div>
	</div></section>
	<?php
}

/** Services an article covers (reverse of the map above). */
function vtech_post_services( $post_id ) {
	$slug = get_post_field( 'post_name', $post_id );
	$out  = array();
	foreach ( vtech_service_guide_map() as $svc => $guides ) {
		if ( in_array( $slug, $guides, true ) ) {
			$p = vtech_published_by_slug( $svc, 'service' );
			if ( $p ) { $out[ $p->ID ] = $p; }
		}
	}
	return array_slice( $out, 0, 4, true );
}

/** Author box + related services under each article. */
function vtech_render_post_footer( $post_id ) {
	$company  = function_exists( 'vtech_contact' ) ? vtech_contact( 'company' ) : get_bloginfo( 'name' );
	$services = vtech_post_services( $post_id );
	?>
	<aside class="post-author" aria-label="<?php esc_attr_e( 'About the author', 'vtech-av' ); ?>">
		<img class="post-author__logo" src="<?php echo esc_url( function_exists( 'vtech_login_logo_url' ) ? vtech_login_logo_url() : VTECH_URI . '/assets/img/logo.png' ); ?>" alt="" width="56" height="56" loading="lazy">
		<div>
			<p class="post-author__name"><?php printf( esc_html__( 'Written by the %s engineering team', 'vtech-av' ), esc_html( $company ) ); ?></p>
			<p class="post-author__bio"><?php esc_html_e( 'Our engineers design, install and support sound, LED, lighting and conference systems for churches, corporates, hotels, schools and events across Kenya. This guide is based on that hands-on work and was reviewed by the team before publishing.', 'vtech-av' ); ?></p>
			<p class="post-author__meta"><?php printf( esc_html__( 'Last reviewed %s', 'vtech-av' ), esc_html( get_the_modified_date( 'j M Y', $post_id ) ) ); ?> &middot; <a href="<?php echo esc_url( home_url( '/about/' ) ); ?>"><?php esc_html_e( 'About VTECH', 'vtech-av' ); ?></a></p>
		</div>
	</aside>
	<?php if ( $services ) : ?>
	<nav class="post-services" aria-label="<?php esc_attr_e( 'Related services', 'vtech-av' ); ?>">
		<p class="post-services__title"><?php esc_html_e( 'Services related to this guide', 'vtech-av' ); ?></p>
		<ul>
		<?php foreach ( $services as $s ) : ?>
			<li><a href="<?php echo esc_url( get_permalink( $s ) ); ?>"><?php echo esc_html( get_the_title( $s ) ); ?> &rarr;</a></li>
		<?php endforeach; ?>
		</ul>
	</nav>
	<?php endif;
}

/* -------------------------------------------------------------------------
 * 3. Article schema: credit the business, link related services
 * ---------------------------------------------------------------------- */

add_filter( 'rank_math/json_ld', function ( $data ) {
	if ( ! is_singular( 'post' ) || ! is_array( $data ) ) { return $data; }
	$org_id = home_url( '/' ) . '#organization';
	foreach ( $data as $node ) {
		if ( ! is_array( $node ) || empty( $node['@type'] ) ) { continue; }
		$t = (array) $node['@type'];
		if ( array_intersect( $t, array( 'Organization', 'LocalBusiness', 'ProfessionalService' ) ) && ! empty( $node['@id'] ) ) { $org_id = $node['@id']; break; }
	}
	$org_ref   = array( '@id' => $org_id );
	$person_id = '';
	$mentions  = array();
	foreach ( vtech_post_services( get_queried_object_id() ) as $s ) {
		$mentions[] = array( '@type' => 'Service', 'name' => wp_strip_all_tags( get_the_title( $s ) ), 'url' => get_permalink( $s ) );
	}
	foreach ( $data as $k => $node ) {
		if ( ! is_array( $node ) || empty( $node['@type'] ) ) { continue; }
		$t = (array) $node['@type'];
		if ( array_intersect( $t, array( 'Article', 'BlogPosting', 'NewsArticle' ) ) ) {
			if ( isset( $node['author']['@id'] ) ) { $person_id = $node['author']['@id']; }
			$data[ $k ]['author']    = array( '@type' => 'Organization', '@id' => $org_id, 'name' => function_exists( 'vtech_contact' ) ? vtech_contact( 'company' ) : get_bloginfo( 'name' ), 'url' => home_url( '/' ) );
			$data[ $k ]['publisher'] = $org_ref;
			if ( $mentions ) { $data[ $k ]['about'] = $mentions; }
		}
		if ( in_array( 'WebPage', $t, true ) ) {
			$data[ $k ]['reviewedBy']   = $org_ref;
			$data[ $k ]['lastReviewed'] = get_the_modified_date( 'c' );
		}
	}
	// Drop the now-unused WordPress user "Person" node.
	if ( $person_id ) {
		foreach ( $data as $k => $node ) {
			if ( is_array( $node ) && isset( $node['@id'] ) && $node['@id'] === $person_id && in_array( 'Person', (array) ( $node['@type'] ?? array() ), true ) ) { unset( $data[ $k ] ); }
		}
	}
	return $data;
}, 30 );

/* -------------------------------------------------------------------------
 * 4. Homepage social image -> branded share image (one time)
 * ---------------------------------------------------------------------- */

add_action( 'admin_init', function () {
	if ( get_option( 'vtech_og_home_set' ) || ! current_user_can( 'manage_options' ) ) { return; }
	$att = (int) get_option( 'vtech_og_default_set' );
	if ( ! $att || ! wp_get_attachment_url( $att ) ) { return; }
	$url = wp_get_attachment_url( $att );
	$front = (int) get_option( 'page_on_front' );
	if ( $front ) {
		update_post_meta( $front, 'rank_math_facebook_image', $url );
		update_post_meta( $front, 'rank_math_facebook_image_id', $att );
		update_post_meta( $front, 'rank_math_twitter_use_facebook', 'on' );
	}
	$opts = get_option( 'rank-math-options-titles', array() );
	if ( is_array( $opts ) ) {
		$opts['homepage_facebook_image']    = $url;
		$opts['homepage_facebook_image_id'] = $att;
		update_option( 'rank-math-options-titles', $opts );
	}
	update_option( 'vtech_og_home_set', 1, false );
} );
