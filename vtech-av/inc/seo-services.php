<?php
/**
 * Service catalogue + GBP alignment (v5.43.0).
 *
 * 1. Adds dedicated service pages for services listed on the Google Business
 *    Profile that had no page of their own: church sound systems, video
 *    conferencing, AV maintenance and support, digital signage and background
 *    music systems. Each is a normal "service" post (created once, editable in
 *    wp-admin afterwards) with its own SEO title, meta description, benefits,
 *    FAQs (rendered visibly and as FAQPage schema) and related services.
 * 2. Gives the existing principal service pages intent-specific titles and
 *    descriptions (only when no custom Rank Math title/description is set).
 * 3. Aligns opening hours and coordinates with the Google Business Profile and
 *    links the website entity to the profile (sameAs + hasMap).
 *
 * Content is deliberately factual: no invented projects, clients or figures.
 *
 * @package VTECH_AV
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

/** Google Business Profile (Maps) URL for VTECH Audio Visual Solutions. */
function vtech_gbp_url() {
	return (string) apply_filters( 'vtech_gbp_url', 'https://maps.google.com/?cid=17980650114841087012' );
}

/* -------------------------------------------------------------------------
 * 1. Catalogue
 * ---------------------------------------------------------------------- */

function vtech_service_catalog() {
	$c = array(
		'church-sound-systems' => array(
			'title'   => 'Church Sound System Installation',
			'seo'     => 'Church Sound System Installation in Kenya | VTECH Audio Visual',
			'desc'    => 'Church sound systems in Kenya: speech-clear PA, worship band audio, wireless mics, mixers and live-stream feeds, designed and installed by VTECH. Free site survey.',
			'tagline' => 'Clear preaching, full worship sound and a clean live-stream feed, designed around your sanctuary and your team.',
			'order'   => 31,
			'intro'   => '<h2>Church sound systems designed for worship</h2>
<p>A church sound system has two jobs that pull in different directions: every word of the sermon must be clear in every seat, and the worship band must sound full without overwhelming the room. VTECH designs church PA systems around both, starting with a free site survey of your sanctuary, its size, its finishes and how your services actually run.</p>
<h3>What we design and install</h3>
<ul>
<li>Main PA and speaker coverage planned so the back rows hear as clearly as the front</li>
<li>Pulpit, lectern and wireless microphones for preachers, worship leaders and choirs</li>
<li>Analogue or digital mixers sized to your band, choir and number of inputs</li>
<li>Stage monitors or in-ear monitoring for musicians</li>
<li>Overflow, foyer and children\'s church audio</li>
<li>A dedicated audio feed for live streaming and recording</li>
</ul>
<h3>Speech clarity first</h3>
<p>Many sanctuaries have hard walls, high ceilings and long reverberation, which blur speech. We plan speaker placement and, where needed, recommend acoustic treatment so the room helps rather than fights the system. See our <a href="/services/acoustic-solutions/">acoustic treatment and soundproofing</a> service.</p>
<h3>Built for volunteer teams</h3>
<p>Most church sound desks are run by volunteers. We set up clear scenes and labelling, train your team on site and stay available for support, so Sunday services run smoothly week after week.</p>',
			'benefits' => array(
				array( 'title' => 'Speech you can follow', 'text' => 'Speaker coverage planned for clear preaching in every seat, not just loud sound at the front.' ),
				array( 'title' => 'Worship that sounds full', 'text' => 'Mixers, monitors and microphones matched to your band, choir and style of worship.' ),
				array( 'title' => 'Ready for live streaming', 'text' => 'A separate, balanced audio feed so your online congregation hears the service properly.' ),
				array( 'title' => 'Volunteer friendly', 'text' => 'Simple scenes, clear labelling and on-site training for your sound team.' ),
				array( 'title' => 'Room acoustics considered', 'text' => 'We assess echo and reverberation and advise on treatment where it will make a real difference.' ),
				array( 'title' => 'Support after installation', 'text' => 'Every installation includes a 12-month support window, with maintenance contracts available.' ),
			),
			'faqs' => array(
				array( 'question' => 'How much does a church sound system cost in Kenya?', 'answer' => 'It depends on the size of the sanctuary, the number of people, your band setup and whether you stream services. After a free site survey we give a fixed, itemised quotation within 24 hours in Nairobi and 48 hours upcountry.' ),
				array( 'question' => 'Can you improve our existing church sound system?', 'answer' => 'Yes. We can assess your current speakers, mixer and microphones, keep what works and recommend targeted upgrades rather than a full replacement.' ),
				array( 'question' => 'Why is our church echoey even with a good sound system?', 'answer' => 'Hard surfaces and high ceilings cause long reverberation that blurs speech. Speaker placement helps, and acoustic treatment of the room often makes the biggest difference.' ),
				array( 'question' => 'Do you set up audio for live streaming services?', 'answer' => 'Yes. We provide a dedicated audio mix for streaming and recording, separate from what the congregation hears in the room.' ),
				array( 'question' => 'Do you train our sound team?', 'answer' => 'Yes. We train your volunteers on site at handover and remain available for support afterwards.' ),
			),
			'related' => array( 'sound-systems', 'acoustic-solutions', 'lighting' ),
		),

		'video-conferencing-systems' => array(
			'title'   => 'Video Conferencing Systems',
			'seo'     => 'Video Conferencing Systems Kenya | Hybrid Meeting Rooms | VTECH',
			'desc'    => 'Video conferencing installation in Kenya: room cameras, ceiling and table microphones, displays and one-touch control for Zoom, Microsoft Teams and Google Meet rooms.',
			'tagline' => 'Hybrid meeting rooms where remote participants see and hear everyone clearly, and every meeting starts on time.',
			'order'   => 32,
			'intro'   => '<h2>Video conferencing rooms that just work</h2>
<p>Hybrid meetings succeed or fail on audio and reliability. VTECH designs and installs video conferencing rooms for offices, boardrooms, training rooms and government meeting spaces across Kenya, so remote participants can see the room, hear every speaker and join in without delays.</p>
<h3>What a VTECH video conferencing room includes</h3>
<ul>
<li>Room cameras chosen for the size and shape of the space</li>
<li>Ceiling, table or beamforming microphones with echo cancellation</li>
<li>Speakers and displays sized for the room and viewing distance</li>
<li>Wireless presentation so guests can share a screen without cables</li>
<li>Room control so a meeting can start from one button or panel</li>
</ul>
<h3>Works with the platforms you use</h3>
<p>We build rooms around the platform your organisation already uses, such as Zoom, Microsoft Teams or Google Meet, and confirm compatibility during the design stage.</p>
<h3>From a single huddle room to a full boardroom</h3>
<p>For larger boardrooms and council chambers, video conferencing is usually part of a complete room design. See our <a href="/services/conference-systems/">conference and boardroom systems</a> service.</p>',
			'benefits' => array(
				array( 'title' => 'Everyone is heard', 'text' => 'Microphone coverage and echo cancellation planned so remote participants hear every seat.' ),
				array( 'title' => 'One-touch start', 'text' => 'Simple room control so meetings start on time without calling IT.' ),
				array( 'title' => 'Platform compatible', 'text' => 'Rooms built around the platform you use, such as Zoom, Microsoft Teams or Google Meet.' ),
				array( 'title' => 'Wireless sharing', 'text' => 'Guests share content from a laptop or phone without hunting for cables.' ),
				array( 'title' => 'Scales across offices', 'text' => 'A consistent design you can roll out to every meeting room and branch.' ),
				array( 'title' => 'Supported after handover', 'text' => '12-month support window included, with maintenance contracts available.' ),
			),
			'faqs' => array(
				array( 'question' => 'Which video conferencing platforms do you support?', 'answer' => 'We design rooms around the platform your organisation uses, such as Zoom, Microsoft Teams or Google Meet, and confirm compatibility at the design stage.' ),
				array( 'question' => 'How many microphones does a meeting room need?', 'answer' => 'Enough to cover every seat evenly. The number depends on room size, table layout and ceiling height, which we measure during the site survey.' ),
				array( 'question' => 'Can you upgrade our existing meeting rooms?', 'answer' => 'Yes. We can reuse suitable displays and cabling and add the cameras, microphones and control needed for reliable hybrid meetings.' ),
				array( 'question' => 'Do you install video conferencing outside Nairobi?', 'answer' => 'Yes. We install and support systems across Kenya.' ),
			),
			'related' => array( 'conference-systems', 'led-screens', 'av-maintenance-support' ),
		),

		'av-maintenance-support' => array(
			'title'   => 'AV Maintenance, Repairs & Technical Support',
			'seo'     => 'AV Maintenance & Repair Kenya | Sound System Support | VTECH',
			'desc'    => 'AV maintenance and repairs in Kenya: sound system troubleshooting, PA and conference system servicing, commissioning and maintenance contracts from VTECH technicians.',
			'tagline' => 'Keep your sound, conference and display systems working: troubleshooting, servicing, commissioning and maintenance contracts.',
			'order'   => 33,
			'intro'   => '<h2>Technical support for the systems you rely on</h2>
<p>Audio-visual systems need attention to stay reliable. Cables wear, settings get changed, firmware falls behind and small faults become failures at the worst moment. VTECH provides maintenance, troubleshooting and technical support for sound, PA, conference and display systems across Kenya, including systems we did not install.</p>
<h3>What we do</h3>
<ul>
<li>Fault finding and repair of sound, PA and conference systems</li>
<li>Preventive maintenance visits and system health checks</li>
<li>Re-tuning and re-commissioning after room or equipment changes</li>
<li>Firmware and software updates on supported equipment</li>
<li>Training refreshers for your in-house team</li>
<li>Annual maintenance contracts with scheduled visits</li>
</ul>
<h3>Commissioning of new systems</h3>
<p>If equipment has been supplied but not properly set up, we can commission it: checking wiring, setting gain structure and processing, labelling and documenting the system so it performs as intended.</p>
<h3>Tell us what is happening</h3>
<p>Describe the fault and the equipment involved when you contact us. Our team will advise on the next step and, where needed, arrange a site visit.</p>',
			'benefits' => array(
				array( 'title' => 'Fewer failures', 'text' => 'Scheduled maintenance finds worn cables and faults before they interrupt an event or meeting.' ),
				array( 'title' => 'Any installer', 'text' => 'We support systems we installed and, after an assessment, systems installed by others.' ),
				array( 'title' => 'Properly commissioned', 'text' => 'Gain structure, processing and settings checked and documented.' ),
				array( 'title' => 'Trained users', 'text' => 'Refresher training so your team uses the system with confidence.' ),
				array( 'title' => 'Predictable costs', 'text' => 'Annual maintenance contracts with scheduled visits.' ),
				array( 'title' => 'Across Kenya', 'text' => 'Support visits in Nairobi and upcountry.' ),
			),
			'faqs' => array(
				array( 'question' => 'Do you repair systems installed by another company?', 'answer' => 'Yes. We first assess the system, then advise on repairs, adjustments or upgrades.' ),
				array( 'question' => 'What does an AV maintenance contract include?', 'answer' => 'Scheduled maintenance visits, system checks and priority support. The exact scope is agreed for each site and written into the contract.' ),
				array( 'question' => 'Can you fix feedback and poor sound in our hall?', 'answer' => 'Often, yes. Feedback and poor clarity usually come from speaker placement, gain structure or room acoustics, which we diagnose on site.' ),
				array( 'question' => 'How do I request support?', 'answer' => 'Call or WhatsApp us, or send a request through the website with a short description of the fault and the equipment involved.' ),
			),
			'related' => array( 'sound-systems', 'conference-systems', 'consultation' ),
		),

		'digital-signage' => array(
			'title'   => 'Digital Signage & Commercial Displays',
			'seo'     => 'Digital Signage Kenya | Commercial Displays & LED Screens | VTECH',
			'desc'    => 'Digital signage in Kenya: commercial displays, indoor and outdoor LED screens and content playback for retail, corporate, hospitality and public spaces. Free site survey.',
			'tagline' => 'Commercial displays and LED screens that put the right message in front of the right people.',
			'order'   => 34,
			'intro'   => '<h2>Digital signage for business and public spaces</h2>
<p>Digital signage replaces printed posters and notice boards with screens you can update at any time: menus, promotions, wayfinding, announcements and live information. VTECH supplies and installs commercial displays and LED screens for retail, offices, hotels, schools, hospitals and public spaces across Kenya.</p>
<h3>Choosing the right screen</h3>
<ul>
<li><strong>Commercial displays</strong> are built for long daily operating hours, unlike home TVs.</li>
<li><strong>Indoor LED screens</strong> suit large sizes and very bright spaces such as lobbies and showrooms.</li>
<li><strong>Outdoor LED screens</strong> need higher brightness and weather protection for direct sunlight and rain.</li>
</ul>
<h3>Pixel pitch and viewing distance</h3>
<p>For LED screens, pixel pitch (the distance between pixels) decides how close viewers can stand before the image looks grainy. A smaller pitch is sharper up close but costs more. We recommend a pitch based on your real viewing distance rather than the smallest number available.</p>
<h3>Mounting, power and playback</h3>
<p>We plan mounting, power and signal routing, and set up content playback so your team can update what is shown. For large video walls and event screens, see our <a href="/services/led-screens/">LED screens and video walls</a> service.</p>',
			'benefits' => array(
				array( 'title' => 'Right screen for the space', 'text' => 'Commercial display or LED chosen for brightness, size and operating hours.' ),
				array( 'title' => 'Sharp at viewing distance', 'text' => 'Pixel pitch matched to where people actually stand.' ),
				array( 'title' => 'Indoor or outdoor', 'text' => 'Weather and sunlight requirements planned for outdoor screens.' ),
				array( 'title' => 'Easy updates', 'text' => 'Playback set up so your team can change content.' ),
				array( 'title' => 'Clean installation', 'text' => 'Mounting, power and cabling planned and installed neatly.' ),
				array( 'title' => 'Supported', 'text' => '12-month support window included.' ),
			),
			'faqs' => array(
				array( 'question' => 'What is the difference between a commercial display and a normal TV?', 'answer' => 'Commercial displays are built to run for long hours every day and usually have higher brightness and longer support. Home TVs are not designed for continuous signage use.' ),
				array( 'question' => 'What pixel pitch do I need for an LED screen?', 'answer' => 'It depends on the closest normal viewing distance. We recommend a pitch after checking where viewers will stand, so you do not pay for detail no one will see.' ),
				array( 'question' => 'Can you install outdoor digital signage?', 'answer' => 'Yes. Outdoor screens need higher brightness and weather protection, which we specify during the design stage.' ),
				array( 'question' => 'Can we update the content ourselves?', 'answer' => 'Yes. We set up playback so your team can change what is shown.' ),
			),
			'related' => array( 'led-screens', 'conference-systems', 'consultation' ),
		),

		'background-music-systems' => array(
			'title'   => 'Background Music & Distributed Audio Systems',
			'seo'     => 'Background Music Systems Kenya | Distributed Audio & Paging | VTECH',
			'desc'    => 'Background music and distributed audio systems in Kenya for hotels, restaurants, retail, offices and hospitals: zoned music, paging and announcements, installed by VTECH.',
			'tagline' => 'Zoned background music, paging and announcements for hotels, restaurants, shops, offices and campuses.',
			'order'   => 35,
			'intro'   => '<h2>The right sound in every area</h2>
<p>Background music and paging systems shape how a space feels and keep people informed. VTECH designs distributed audio systems for hotels, restaurants, retail, offices, hospitals and schools across Kenya, with even coverage and separate control for each area.</p>
<h3>What we install</h3>
<ul>
<li>Ceiling, wall and outdoor speakers for even, comfortable coverage</li>
<li>Zoned systems so each area can have its own music source and volume</li>
<li>Paging microphones and announcements to one zone or all zones</li>
<li>Simple wall controls or app control for staff</li>
<li>Integration with an existing PA or emergency announcement system where required</li>
</ul>
<h3>Comfortable, not loud</h3>
<p>Good background music is heard evenly at a comfortable level, with no loud spots near a speaker and no quiet corners. We plan speaker spacing from the layout and ceiling height of each area.</p>
<p>For larger halls and event spaces, see our <a href="/services/sound-systems/">professional sound systems</a> service.</p>',
			'benefits' => array(
				array( 'title' => 'Even coverage', 'text' => 'Speaker spacing planned for comfortable sound in every corner.' ),
				array( 'title' => 'Zone by zone', 'text' => 'Different music and volume for lobby, restaurant, terrace and back of house.' ),
				array( 'title' => 'Paging included', 'text' => 'Announcements to one area or the whole site.' ),
				array( 'title' => 'Staff friendly', 'text' => 'Simple controls that do not need a technician.' ),
				array( 'title' => 'Indoor and outdoor', 'text' => 'Weather-rated speakers for gardens, pools and terraces.' ),
				array( 'title' => 'Supported', 'text' => '12-month support window included.' ),
			),
			'faqs' => array(
				array( 'question' => 'Can different areas play different music?', 'answer' => 'Yes. A zoned system lets each area have its own source and volume.' ),
				array( 'question' => 'Can the system also make announcements?', 'answer' => 'Yes. Paging microphones can announce to a single zone or all zones.' ),
				array( 'question' => 'Do you install outdoor speakers for gardens and pool areas?', 'answer' => 'Yes. We use weather-rated speakers for outdoor areas.' ),
				array( 'question' => 'Can you add zones to our existing system?', 'answer' => 'Often, yes. We assess your current amplifiers and speakers and advise on extending them.' ),
			),
			'related' => array( 'sound-systems', 'av-maintenance-support', 'acoustic-solutions' ),
		),
	);
	return apply_filters( 'vtech_service_catalog', $c );
}

/** Intent-specific titles/descriptions for the existing principal pages (slug => [title, description]). */
function vtech_principal_service_seo() {
	return apply_filters( 'vtech_principal_service_seo', array(
		'sound-systems'      => array( 'Sound System Installation Kenya | PA & Professional Audio | VTECH', 'Professional sound and PA system installation in Kenya: line arrays, speakers, microphones and mixers for churches, halls, hotels and events. Free site survey.' ),
		'led-screens'        => array( 'LED Screen Installation Kenya | LED Video Walls | VTECH', 'Indoor and outdoor LED screens and video walls in Kenya: supply, installation, control and support for events, retail, churches and corporate spaces.' ),
		'conference-systems' => array( 'Conference Room AV Systems Kenya | Boardroom AV Nairobi | VTECH', 'Boardroom and conference room AV in Nairobi and across Kenya: displays, delegate and ceiling microphones, video conferencing and one-touch control.' ),
		'lighting'           => array( 'Stage Lighting Installation Kenya | Architectural Lighting | VTECH', 'Stage and architectural lighting in Kenya: design, rigging, control and installation for churches, auditoriums, venues and events.' ),
		'acoustic-solutions' => array( 'Acoustic Treatment & Soundproofing Kenya | Nairobi | VTECH', 'Acoustic treatment and soundproofing in Kenya: acoustic panels, echo reduction and sound isolation for churches, auditoriums, studios and offices.' ),
		'consultation'       => array( 'AV Consultation & System Design Kenya | Free Site Survey | VTECH', 'Independent AV consultation and system design in Kenya: site survey, needs analysis, technical design and a fixed quotation within 24 hours.' ),
	) );
}

function vtech_service_catalog_item( $slug ) {
	$c = vtech_service_catalog();
	return isset( $c[ $slug ] ) ? $c[ $slug ] : null;
}

/* -------------------------------------------------------------------------
 * 2. Create the new service pages once (editable afterwards in wp-admin)
 * ---------------------------------------------------------------------- */

add_action( 'init', 'vtech_seed_catalog_services', 40 );
function vtech_seed_catalog_services() {
	if ( '5.43' === get_option( 'vtech_catalog_seeded' ) ) { return; }
	if ( ! post_type_exists( 'service' ) ) { return; }
	foreach ( vtech_service_catalog() as $slug => $s ) {
		if ( get_page_by_path( $slug, OBJECT, 'service' ) ) { continue; }
		wp_insert_post( array(
			'post_type'    => 'service',
			'post_status'  => 'publish',
			'post_name'    => $slug,
			'post_title'   => $s['title'],
			'post_excerpt' => $s['tagline'],
			'post_content' => $s['intro'],
			'menu_order'   => (int) $s['order'],
		) );
	}
	update_option( 'vtech_catalog_seeded', '5.43', true );
}

/* -------------------------------------------------------------------------
 * 3. Titles + descriptions (manual Rank Math edits always win)
 * ---------------------------------------------------------------------- */

function vtech_catalog_seo_for_current() {
	if ( ! is_singular( 'service' ) ) { return null; }
	$slug = get_post_field( 'post_name', get_queried_object_id() );
	$item = vtech_service_catalog_item( $slug );
	if ( $item ) { return array( $item['seo'], $item['desc'] ); }
	$p = vtech_principal_service_seo();
	return isset( $p[ $slug ] ) ? $p[ $slug ] : null;
}

function vtech_catalog_has_custom( $field ) {
	return function_exists( 'vtech_has_custom_rm' ) ? vtech_has_custom_rm( $field ) : false;
}

add_filter( 'rank_math/frontend/title', function ( $title ) {
	$s = vtech_catalog_seo_for_current();
	return ( $s && ! vtech_catalog_has_custom( 'title' ) ) ? $s[0] : $title;
}, 30 );
add_filter( 'rank_math/frontend/description', function ( $desc ) {
	$s = vtech_catalog_seo_for_current();
	return ( $s && ! vtech_catalog_has_custom( 'description' ) ) ? $s[1] : $desc;
}, 30 );
add_filter( 'rank_math/opengraph/facebook/og_title', function ( $t ) {
	$s = vtech_catalog_seo_for_current();
	return ( $s && ! vtech_catalog_has_custom( 'facebook_title' ) ) ? $s[0] : $t;
}, 30 );
add_filter( 'rank_math/opengraph/facebook/og_description', function ( $d ) {
	$s = vtech_catalog_seo_for_current();
	return ( $s && ! vtech_catalog_has_custom( 'facebook_description' ) ) ? $s[1] : $d;
}, 30 );
// Without Rank Math: the document title still uses the catalogue title.
add_filter( 'pre_get_document_title', function ( $t ) {
	if ( defined( 'RANK_MATH_VERSION' ) ) { return $t; }
	$s = vtech_catalog_seo_for_current();
	return $s ? $s[0] : $t;
}, 30 );

/* -------------------------------------------------------------------------
 * 4. FAQPage schema for catalogue pages (ACF FAQs take priority elsewhere)
 * ---------------------------------------------------------------------- */

add_action( 'wp_head', function () {
	if ( ! is_singular( 'service' ) ) { return; }
	$id = get_queried_object_id();
	if ( function_exists( 'get_field' ) && get_field( 'faqs', $id ) ) { return; } // schema.php already outputs ACF FAQs.
	$item = function_exists( 'vtech_service_content' ) ? vtech_service_content( $id ) : vtech_service_catalog_item( get_post_field( 'post_name', $id ) );
	if ( ! $item || empty( $item['faqs'] ) ) { return; }
	$q = array();
	foreach ( $item['faqs'] as $f ) {
		$q[] = array( '@type' => 'Question', 'name' => $f['question'], 'acceptedAnswer' => array( '@type' => 'Answer', 'text' => $f['answer'] ) );
	}
	echo "\n<script type=\"application/ld+json\">" . wp_json_encode( array( '@context' => 'https://schema.org', '@type' => 'FAQPage', '@id' => get_permalink( $id ) . '#faq', 'mainEntity' => $q ), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . "</script>\n"; // phpcs:ignore
}, 25 );

/* -------------------------------------------------------------------------
 * 5. Business entity: link the Google Business Profile, GBP hours and pin
 * ---------------------------------------------------------------------- */

add_filter( 'vtech_business_entity', function ( $e ) {
	$gbp = vtech_gbp_url();
	if ( $gbp ) {
		$e['hasMap'] = $gbp;
		$same        = isset( $e['sameAs'] ) && is_array( $e['sameAs'] ) ? $e['sameAs'] : array();
		$same[]      = $gbp;
		$e['sameAs'] = array_values( array_unique( $same ) );
	}
	$e['openingHoursSpecification'] = vtech_opening_hours_spec();
	$e['geo'] = array(
		'@type'     => 'GeoCoordinates',
		'latitude'  => (float) vtech_contact( 'geo_lat' ),
		'longitude' => (float) vtech_contact( 'geo_lng' ),
	);
	// Add the new catalogue services to the offer catalogue.
	if ( isset( $e['hasOfferCatalog']['itemListElement'][0]['itemListElement'] ) && is_array( $e['hasOfferCatalog']['itemListElement'][0]['itemListElement'] ) ) {
		$have = array();
		foreach ( $e['hasOfferCatalog']['itemListElement'][0]['itemListElement'] as $o ) {
			if ( isset( $o['itemOffered']['url'] ) ) { $have[ untrailingslashit( $o['itemOffered']['url'] ) ] = true; }
		}
		foreach ( vtech_service_catalog() as $slug => $s ) {
			$url = home_url( '/services/' . $slug . '/' );
			if ( isset( $have[ untrailingslashit( $url ) ] ) ) { continue; }
			$e['hasOfferCatalog']['itemListElement'][0]['itemListElement'][] = array(
				'@type'       => 'Offer',
				'itemOffered' => array(
					'@type'       => 'Service',
					'name'        => $s['title'],
					'description' => $s['desc'],
					'url'         => $url,
					'areaServed'  => array( '@type' => 'Country', 'name' => 'Kenya' ),
					'provider'    => array( '@id' => home_url( '/' ) . '#organization' ),
				),
			);
		}
	}
	return $e;
}, 15 );

/**
 * Opening hours as schema, parsed from the "hours_schema" contact value
 * (e.g. "Mo-Fr 08:00-18:00, Sa 08:00-18:30") so the Customizer stays the
 * single source of truth.
 */
function vtech_opening_hours_spec() {
	$map  = array( 'Mo' => 'Monday', 'Tu' => 'Tuesday', 'We' => 'Wednesday', 'Th' => 'Thursday', 'Fr' => 'Friday', 'Sa' => 'Saturday', 'Su' => 'Sunday' );
	$keys = array_keys( $map );
	$out  = array();
	foreach ( preg_split( '/\s*[,;]\s*/', (string) vtech_contact( 'hours_schema' ) ) as $part ) {
		if ( ! preg_match( '/^(Mo|Tu|We|Th|Fr|Sa|Su)(?:-(Mo|Tu|We|Th|Fr|Sa|Su))?\s+(\d{2}:\d{2})-(\d{2}:\d{2})$/', trim( $part ), $m ) ) { continue; }
		$a    = array_search( $m[1], $keys, true );
		$b    = '' !== $m[2] ? array_search( $m[2], $keys, true ) : $a;
		$days = array();
		for ( $i = $a; $i <= $b; $i++ ) { $days[] = $map[ $keys[ $i ] ]; }
		$out[] = array( '@type' => 'OpeningHoursSpecification', 'dayOfWeek' => $days, 'opens' => $m[3], 'closes' => $m[4] );
	}
	return $out;
}

/* -------------------------------------------------------------------------
 * 6. One-time alignment of saved Customizer values with the GBP
 * ---------------------------------------------------------------------- */

add_action( 'init', 'vtech_align_gbp_2026_10', 6 );
function vtech_align_gbp_2026_10() {
	if ( '2026-10-gbp' === get_option( 'vtech_gbp_alignment' ) ) { return; }
	$d       = vtech_contact_defaults();
	$retired = array(
		'hours'        => array( 'Mon-Fri, 9:00 AM - 6:00 PM', '' ),
		'hours_schema' => array( 'Mo-Fr 09:00-18:00', '' ),
		'geo_lat'      => array( '-1.2669', '' ),
		'geo_lng'      => array( '36.8047', '' ),
	);
	$pairs = array();
	foreach ( $retired as $key => $olds ) {
		$saved = (string) get_theme_mod( 'vtech_' . $key, '' );
		if ( in_array( trim( $saved ), $olds, true ) ) {
			set_theme_mod( 'vtech_' . $key, $d[ $key ] );
			if ( 'hours' === $key && '' !== $saved ) { $pairs[ $saved ] = $d[ $key ]; }
		}
	}
	// Rewrite the old hours wording inside existing page text (e.g. contact blocks).
	$pairs['Mon-Fri, 9:00 AM - 6:00 PM'] = $d['hours'];
	if ( function_exists( 'vtech_contact_propagate' ) ) { vtech_contact_propagate( $pairs ); }
	if ( function_exists( 'vtech_contact_snapshot_values' ) ) { update_option( 'vtech_contact_snapshot', vtech_contact_snapshot_values(), false ); }
	update_option( 'vtech_gbp_alignment', '2026-10-gbp', true );
}

/* -------------------------------------------------------------------------
 * 7. v5.49.3 Entity extras: public-holiday closures (matches the Google
 *    Business Profile special hours) and topics people search for.
 * ---------------------------------------------------------------------- */

/** Holiday closures, kept identical to the Google Business Profile special hours. */
function vtech_holiday_closures() {
	return (array) apply_filters( 'vtech_holiday_closures', array(
		'2026-10-20', // Mashujaa Day.
	) );
}

add_filter( 'vtech_business_entity', function ( $e ) {
	$special = array();
	$today   = gmdate( 'Y-m-d' );
	foreach ( vtech_holiday_closures() as $d ) {
		if ( $d < $today ) { continue; } // Past dates drop out automatically.
		$special[] = array( '@type' => 'OpeningHoursSpecification', 'validFrom' => $d, 'validThrough' => $d, 'opens' => '00:00', 'closes' => '00:00' );
	}
	if ( $special ) { $e['specialOpeningHoursSpecification'] = $special; }

	$topics = isset( $e['knowsAbout'] ) && is_array( $e['knowsAbout'] ) ? $e['knowsAbout'] : array();
	$e['knowsAbout'] = array_values( array_unique( array_merge( $topics, array(
		'Sound system hire in Nairobi', 'PA system installation', 'LED screen hire', 'Outdoor LED screens',
		'Delegate conference systems', 'Conference interpretation systems', 'Hybrid meeting rooms', 'Microsoft Teams Rooms',
		'Zoom Rooms', 'Hospital paging systems', 'Background music systems', 'Church live streaming',
		'Event production in Kenya', 'AV maintenance and repair',
	) ) ) );
	return $e;
}, 40 );
