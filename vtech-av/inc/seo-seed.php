<?php
/**
 * One-time Rank Math SEO seeding (v5.48.0).
 *
 * Writes an SEO title, meta description and focus keyword(s) into each
 * page's Rank Math box so they are visible and editable in wp-admin.
 * Only EMPTY fields are filled: anything already typed in Rank Math is kept.
 * Runs once when an administrator opens wp-admin; re-run from
 * Tools > VTECH SEO Seed if needed.
 *
 * @package vtech-av
 */

defined( 'ABSPATH' ) || exit;

define( 'VTECH_SEO_SEED_VERSION', '1' );

/**
 * Seed map: post type => slug => array( title, description, focus keywords ).
 * Service titles/descriptions come from the service catalogue when blank here.
 */
function vtech_seo_seed_map() {
	return array(
		'service'      => array(
			'church-sound-systems'       => array( '', '', 'church sound system Kenya, church PA system, church audio installation Nairobi' ),
			'video-conferencing-systems' => array( '', '', 'video conferencing system Kenya, Zoom room installation, Microsoft Teams room Kenya' ),
			'av-maintenance-support'     => array( '', '', 'AV maintenance Kenya, sound system repair Nairobi, PA system servicing' ),
			'digital-signage'            => array( '', '', 'digital signage Kenya, commercial display screens, digital signage Nairobi' ),
			'background-music-systems'   => array( '', '', 'background music system Kenya, distributed audio, paging system Kenya' ),
			'sound-systems'              => array( '', '', 'sound system installation Kenya, PA system Kenya, professional audio Nairobi' ),
			'led-screens'                => array( '', '', 'LED screens Kenya, LED video wall Kenya, outdoor LED screen Nairobi' ),
			'conference-systems'         => array( '', '', 'conference system Kenya, boardroom AV Nairobi, delegate microphone system' ),
			'lighting'                   => array( '', '', 'stage lighting Kenya, church lighting, architectural lighting Nairobi' ),
			'acoustic-solutions'         => array( '', '', 'acoustic treatment Kenya, soundproofing Nairobi, acoustic panels Kenya' ),
			'consultation'               => array( '', '', 'AV consultation Kenya, audio visual system design, AV site survey Nairobi' ),
		),
		'industry'     => array(
			'churches'           => array( 'Church Sound, LED & Lighting Systems in Kenya | VTECH', 'Church sound systems, LED screens, stage lighting and live-stream AV for churches across Kenya. Designed, installed and supported by VTECH. Free site survey.', 'church audio visual Kenya, church sound and lighting, church LED screen Kenya' ),
			'hotels'             => array( 'Hotel AV Systems in Kenya | Conference & Music | VTECH', 'AV for hotels in Kenya: conference and banqueting AV, background music, paging and LED screens, installed and maintained by VTECH. Get a 24h quote.', 'hotel AV systems Kenya, hotel conference AV, hotel background music' ),
			'education'          => array( 'School & University AV Systems in Kenya | VTECH', 'Lecture hall AV, PA systems, projectors, LED screens and hybrid classrooms for schools, colleges and universities in Kenya. Designed and installed by VTECH.', 'school audio visual Kenya, lecture hall AV, university PA system Kenya' ),
			'corporate'          => array( 'Corporate & Boardroom AV Solutions in Kenya | VTECH', 'Boardroom AV, video conferencing, meeting room displays and office sound for businesses in Nairobi and across Kenya. Designed, installed and supported by VTECH.', 'corporate AV solutions Kenya, office AV Nairobi, meeting room AV Kenya' ),
			'government'         => array( 'Government & County Assembly AV Systems Kenya | VTECH', 'Delegate microphone, voting, PA, LED and recording systems for county assemblies, ministries and public institutions in Kenya. Designed and installed by VTECH.', 'county assembly conference system, government AV Kenya, delegate system Kenya' ),
			'healthcare'         => array( 'Hospital PA & Paging Systems in Kenya | Healthcare AV | VTECH', 'Hospital paging and PA, nurse-station announcements, background music and training-room AV for hospitals and clinics in Kenya. Installed and supported by VTECH.', 'hospital PA system Kenya, hospital paging system, healthcare AV Kenya' ),
			'media'              => array( 'Studio & Broadcast AV for Media Houses in Kenya | VTECH', 'Studio audio, acoustic treatment, lighting, LED walls and broadcast AV for TV, radio and media houses in Kenya. Designed, built and supported by VTECH.', 'studio AV installation Kenya, broadcast studio Kenya, radio studio setup Nairobi' ),
			'conference-centres' => array( 'Conference Centre AV & Delegate Systems in Kenya | VTECH', 'Plenary sound, delegate microphones, interpretation, LED screens and lighting for conference centres and halls in Kenya. Designed and installed by VTECH.', 'conference centre AV Kenya, plenary hall sound system, interpretation system Kenya' ),
			'events'             => array( 'Event Sound, LED Screen & Lighting Hire in Kenya | VTECH', 'Sound, PA, LED screen and stage lighting hire for concerts, weddings, launches and corporate events in Nairobi and across Kenya, with setup and technicians.', 'event sound hire Kenya, event AV hire Nairobi, stage and sound hire' ),
		),
		'hire_package' => array(
			'platinum-package' => array( 'Platinum Package | Concert Sound & LED Hire Nairobi | VTECH', 'Platinum sound, LED and lighting hire package for large concerts, festivals and conferences in Nairobi and Kenya. Delivery, setup and technicians included.', 'concert sound hire Nairobi, large event PA hire Kenya' ),
			'gold-package'     => array( 'Gold Package | Event Sound & Lighting Hire Nairobi | VTECH', 'Gold sound and lighting hire package for weddings, launches and corporate events in Nairobi and Kenya. Delivery, setup and an on-site technician included.', 'event sound hire Nairobi, wedding sound hire Kenya' ),
			'silver-package'   => array( 'Silver Package | PA System Hire Nairobi | VTECH Audio', 'Silver PA system hire package for mid-size events, church functions and meetings in Nairobi and Kenya. Delivery, setup and an on-site technician included.', 'PA system hire Nairobi, sound system hire Kenya' ),
			'bronze-package'   => array( 'Bronze Package | Small PA & Mic Hire Nairobi | VTECH', 'Bronze PA and microphone hire for small events, meetings and functions in Nairobi and Kenya. Affordable, with delivery and setup included. Book on WhatsApp.', 'small PA hire Nairobi, microphone hire Nairobi' ),
		),
		'page'         => array(
			'__front'                => array( 'VTECH Audio Visual Kenya | Sound, LED Screens & AV Installation', 'VTECH Audio Visual Solutions: sound and PA systems, LED screens, conference AV, stage lighting and acoustics, installed and hired across Kenya and East Africa.', 'audio visual company Kenya, VTECH Audio, AV installation Kenya' ),
			'about'                  => array( 'About VTECH | Audio Visual Company in Nairobi, Kenya', 'VTECH Audio Visual Solutions is a Nairobi AV integrator at Mpaka Plaza, Westlands, designing and installing sound, LED, conference and lighting systems in Kenya.', 'audio visual company Nairobi, about VTECH Audio' ),
			'contact'                => array( 'Contact VTECH Audio | Westlands, Nairobi | Call or WhatsApp', 'Contact VTECH Audio Visual Solutions, Mpaka Plaza, Westlands, Nairobi. Call or WhatsApp +254 182 942 946 for AV quotes, installations and equipment hire.', 'contact VTECH Audio, AV company Westlands Nairobi' ),
			'book-a-consultation'    => array( 'Book a Free AV Site Survey in Kenya | 24h Quote | VTECH', 'Book a free AV site survey anywhere in Kenya. VTECH designs your sound, LED, conference or lighting system and sends a fixed quote within 24 hours.', 'AV site survey Kenya, audio visual quote Nairobi' ),
			'equipment-hire'         => array( 'Sound, PA, LED Screen & Lighting Hire in Nairobi, Kenya | VTECH', 'Sound, PA, LED screen and lighting hire in Nairobi and across Kenya. Packages with delivery, setup and a technician. Get a quote or book on WhatsApp.', 'sound system hire Nairobi, LED screen hire Kenya, PA hire Nairobi' ),
			'equipment-hire-request' => array( 'Equipment Hire Request | Book Sound & LED Hire | VTECH', 'Request sound, PA, LED screen or lighting hire for your event in Kenya. Share your date, venue and guest numbers and VTECH replies with a quote fast.', 'book sound hire Nairobi, equipment hire request' ),
			'faq'                    => array( 'AV, Sound System & Equipment Hire FAQs (Kenya) | VTECH', 'Answers on sound systems, LED screens, conference AV, installation timelines, pricing and equipment hire in Kenya from VTECH Audio Visual Solutions.', 'sound system FAQ Kenya, AV installation questions' ),
		),
	);
}

/** Service catalogue title + description for a slug (from inc/seo-services.php). */
function vtech_seo_seed_service_defaults( $slug ) {
	if ( function_exists( 'vtech_service_catalog_item' ) ) {
		$item = vtech_service_catalog_item( $slug );
		if ( $item && ! empty( $item['seo'] ) ) { return array( $item['seo'], $item['desc'] ); }
	}
	if ( function_exists( 'vtech_principal_service_seo' ) ) {
		$p = vtech_principal_service_seo();
		if ( isset( $p[ $slug ] ) ) { return $p[ $slug ]; }
	}
	return array( '', '' );
}

/** Fill empty Rank Math fields. Returns the number of fields written. */
function vtech_seo_seed_run() {
	$written = 0;
	foreach ( vtech_seo_seed_map() as $type => $rows ) {
		foreach ( $rows as $slug => $row ) {
			if ( '__front' === $slug ) {
				$id = (int) get_option( 'page_on_front' );
			} else {
				$post = get_page_by_path( $slug, OBJECT, $type );
				$id   = $post ? (int) $post->ID : 0;
			}
			if ( ! $id ) { continue; }

			list( $title, $desc, $kw ) = $row;
			if ( 'service' === $type && ( '' === $title || '' === $desc ) ) {
				$d     = vtech_seo_seed_service_defaults( $slug );
				$title = $title ? $title : $d[0];
				$desc  = $desc ? $desc : $d[1];
			}
			$fields = array(
				'rank_math_title'         => $title,
				'rank_math_description'   => $desc,
				'rank_math_focus_keyword' => $kw,
			);
			foreach ( $fields as $key => $val ) {
				if ( '' === $val ) { continue; }
				if ( '' !== trim( (string) get_post_meta( $id, $key, true ) ) ) { continue; }
				update_post_meta( $id, $key, $val );
				$written++;
			}
		}
	}
	update_option( 'vtech_seo_seed_done', VTECH_SEO_SEED_VERSION, false );
	return $written;
}

// Run once automatically for an administrator in wp-admin.
add_action( 'admin_init', function () {
	if ( ! current_user_can( 'manage_options' ) ) { return; }
	if ( VTECH_SEO_SEED_VERSION === get_option( 'vtech_seo_seed_done' ) ) { return; }
	$n = vtech_seo_seed_run();
	set_transient( 'vtech_seo_seed_notice', (int) $n, 300 );
} );

add_action( 'admin_notices', function () {
	$n = get_transient( 'vtech_seo_seed_notice' );
	if ( false === $n ) { return; }
	delete_transient( 'vtech_seo_seed_notice' );
	printf( '<div class="notice notice-success is-dismissible"><p>%s</p></div>', esc_html( sprintf( 'VTECH SEO: %d Rank Math titles, descriptions and focus keywords were filled in. Existing entries were not changed.', $n ) ) );
} );

// Tools > VTECH SEO Seed: manual re-run (fills only empty fields).
add_action( 'admin_menu', function () {
	add_management_page( 'VTECH SEO Seed', 'VTECH SEO Seed', 'manage_options', 'vtech-seo-seed', function () {
		$msg = '';
		if ( isset( $_POST['vtech_seo_seed'] ) && check_admin_referer( 'vtech_seo_seed' ) ) {
			$msg = sprintf( '%d empty fields filled.', vtech_seo_seed_run() );
		}
		echo '<div class="wrap"><h1>VTECH SEO Seed</h1>';
		echo '<p>Fills empty Rank Math SEO titles, descriptions and focus keywords for services, industries, hire packages and key pages. Anything already entered is never overwritten.</p>';
		if ( $msg ) { echo '<div class="notice notice-success"><p>' . esc_html( $msg ) . '</p></div>'; }
		echo '<form method="post">';
		wp_nonce_field( 'vtech_seo_seed' );
		echo '<p><button class="button button-primary" name="vtech_seo_seed" value="1">Fill empty SEO fields</button></p></form></div>';
	} );
} );

/*
 * v2 fix (5.49.1): homepage focus keywords had several phrases run together in one
 * entry ("av company kenya audio visual solutions kenya ..."). Split into separate
 * comma-separated keywords. Only touches the homepage, and only if that run-together
 * entry is still there.
 */
add_action( 'admin_init', function () {
	if ( ! current_user_can( 'manage_options' ) || get_option( 'vtech_seo_fix_home_kw' ) ) { return; }
	$id = (int) get_option( 'page_on_front' );
	if ( $id ) {
		$kw = (string) get_post_meta( $id, 'rank_math_focus_keyword', true );
		if ( '' === trim( $kw ) || false !== stripos( $kw, 'kenya audio visual' ) || false !== stripos( $kw, 'kenya av systems' ) ) {
			update_post_meta( $id, 'rank_math_focus_keyword', 'audio visual company Kenya,AV company Kenya,audio visual solutions Kenya,audio visual consultant Kenya,AV systems integrator Kenya' );
		}
	}
	update_option( 'vtech_seo_fix_home_kw', 1, false );
} );
