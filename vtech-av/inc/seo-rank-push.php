<?php
/**
 * Ranking push (v5.62.0).
 *
 *  1. Buyer content on the two weakest money pages (LED screens, conference
 *     and boardroom systems): comparison tables, room sizing, what is included.
 *  2. A Nairobi landing page (/audio-visual-company-nairobi/) created once,
 *     with its own title, description, FAQs and schema, plus Nairobi
 *     neighbourhoods added to the business areaServed.
 *  3. Industry depth: hospital PA and paging content on the healthcare page,
 *     and a new Banks and Financial Institutions industry page.
 *  4. Proof on every service page: recent real projects (from the Projects
 *     list), trusted brands and a "serving Nairobi and all 47 counties" line.
 *
 * Content is factual and general: no invented prices, clients, reviews or
 * results. Anything an editor has already written always takes priority.
 *
 * @package VTECH_AV
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

/* -------------------------------------------------------------------------
 * Helpers
 * ---------------------------------------------------------------------- */

function vtech_rp_table( $head, $rows ) {
	$h = '<div class="rp-table-wrap"><table class="rp-table"><thead><tr>';
	foreach ( $head as $c ) { $h .= '<th scope="col">' . esc_html( $c ) . '</th>'; }
	$h .= '</tr></thead><tbody>';
	foreach ( $rows as $r ) {
		$h .= '<tr>';
		foreach ( $r as $i => $c ) { $h .= ( 0 === $i ? '<th scope="row">' : '<td>' ) . esc_html( $c ) . ( 0 === $i ? '</th>' : '</td>' ); }
		$h .= '</tr>';
	}
	return $h . '</tbody></table></div>';
}

function vtech_rp_faq_html( $faqs ) {
	$h = '<div class="faq">';
	foreach ( $faqs as $f ) {
		$h .= '<details class="faq__item"><summary>' . esc_html( $f[0] ) . '</summary><div class="faq__answer"><p>' . esc_html( $f[1] ) . '</p></div></details>';
	}
	return $h . '</div>';
}

function vtech_rp_faq_schema( $faqs, $url ) {
	$q = array();
	foreach ( $faqs as $f ) {
		$q[] = array( '@type' => 'Question', 'name' => $f[0], 'acceptedAnswer' => array( '@type' => 'Answer', 'text' => $f[1] ) );
	}
	if ( ! $q ) { return; }
	echo "\n<script type=\"application/ld+json\">" . wp_json_encode( array( '@context' => 'https://schema.org', '@type' => 'FAQPage', '@id' => $url . '#rp-faq', 'mainEntity' => $q ), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . "</script>\n"; // phpcs:ignore
}

function vtech_rp_org_id() {
	return trailingslashit( home_url( '/' ) ) . '#organization';
}

/* -------------------------------------------------------------------------
 * 1. Buyer content for LED screens and conference systems
 * ---------------------------------------------------------------------- */

function vtech_rp_service_content( $slug ) {
	$c = array(
		'led-screens' => array(
			'eyebrow' => 'LED screen buyer guide',
			'title'   => 'Choosing an LED screen or video wall in Kenya',
			'intro'   => 'An LED screen is a long-term investment. The right choice depends on where it goes, how far away people are, and how bright the space is. These are the questions we answer with you during the free site survey.',
			'tables'  => array(
				array(
					'caption' => 'Pixel pitch and viewing distance',
					'head'    => array( 'Pixel pitch', 'Comfortable viewing from about', 'Typical use' ),
					'rows'    => array(
						array( 'P1.2 to P1.9', '1.5 to 2 metres', 'Boardrooms, control rooms, studios, close-up lobby walls' ),
						array( 'P2.5 to P3.9', '3 to 4 metres', 'Churches, conference halls, auditoriums, retail' ),
						array( 'P4 to P6', '5 to 8 metres', 'Large halls, stages, semi-outdoor areas' ),
						array( 'P8 and above', '10 metres and more', 'Outdoor billboards, stadiums, roadside displays' ),
					),
					'note'    => 'Rule of thumb: the closest comfortable viewing distance in metres is roughly the pixel pitch in millimetres. We confirm the right pitch on site.',
				),
				array(
					'caption' => 'Indoor, outdoor or projector?',
					'head'    => array( 'Option', 'Best for', 'Points to consider' ),
					'rows'    => array(
						array( 'Indoor LED wall', 'Churches, halls, boardrooms, retail', 'Finer pixel pitch, sharp at short distances, bright enough for well-lit rooms' ),
						array( 'Outdoor LED screen', 'Billboards, stadiums, open-air events', 'Weather-rated and much brighter to stay readable in direct sun' ),
						array( 'Projector and screen', 'Small, dark rooms on a tight budget', 'Lower upfront cost, but washes out in daylight and needs a dark room' ),
					),
					'note'    => '',
				),
			),
			'included_title' => 'What an LED installation from VTECH includes',
			'included'       => array(
				'Site survey, viewing-distance and brightness assessment, and a written design.',
				'Screen supply, mounting structure, power and signal planning.',
				'Video processor, scaling and content source set-up, including live cameras where needed.',
				'Calibration, testing and handover training for your team.',
				'12 months of support, with maintenance contracts available afterwards.',
			),
			'faqs' => array(
				array( 'How much does an LED video wall cost in Kenya?', 'The price depends mainly on screen size, pixel pitch, indoor or outdoor rating, the mounting structure and the processor. After a free site survey we give a fixed, itemised quote within 24 hours in Nairobi.' ),
				array( 'Is an LED wall better than a projector for a church?', 'For most churches with daylight or bright stage lighting, yes. An LED wall stays bright and sharp where a projector washes out. For a small, dark room a projector can still be the budget option.' ),
			),
		),
		'conference-systems' => array(
			'eyebrow' => 'Boardroom AV buyer guide',
			'title'   => 'Sizing a conference room or boardroom AV system',
			'intro'   => 'The right meeting-room system depends on the number of seats, the shape of the room and the platform you use. Here is how we size rooms for corporates, banks, NGOs, county governments and institutions in Kenya.',
			'tables'  => array(
				array(
					'caption' => 'Room size and typical equipment',
					'head'    => array( 'Room', 'Seats', 'Typical system' ),
					'rows'    => array(
						array( 'Huddle room', '2 to 6', 'One display, all-in-one video bar with camera, microphones and speaker, wireless sharing' ),
						array( 'Meeting room', '6 to 12', 'Large display or two, conference camera, table or ceiling microphones, room speakers, one-touch control' ),
						array( 'Boardroom', '12 to 20', 'Dual displays or fine-pitch LED wall, PTZ or auto-framing cameras, ceiling microphone arrays, DSP audio, touch-panel control' ),
						array( 'Council chamber or training hall', '20 and more', 'Delegate microphone system with voting or recording options, multiple cameras, LED wall or projection, PA and streaming' ),
					),
					'note'    => 'Every room is designed around Microsoft Teams, Zoom or Google Meet, whichever your organisation uses.',
				),
			),
			'included_title' => 'What a boardroom installation from VTECH includes',
			'included'       => array(
				'Site survey covering seating, acoustics, network and power.',
				'Design and supply of displays, cameras, microphones, speakers and control.',
				'Neat cabling, installation, room tuning and echo control.',
				'Platform set-up and a simple one-touch start for staff.',
				'Training, documentation and 12 months of support.',
			),
			'faqs' => array(
				array( 'How much does a boardroom AV installation cost in Kenya?', 'It depends on the number of seats, displays, cameras and microphones, and on how much control you need. We survey the room for free and send a fixed, itemised quote within 24 hours in Nairobi.' ),
				array( 'Do you install conference systems for banks and head offices?', 'Yes. We design boardrooms, AGM halls, training rooms and branch audio for banks and financial institutions, with neat installation that respects security and working hours.' ),
			),
		),
	);
	return isset( $c[ $slug ] ) ? $c[ $slug ] : null;
}

function vtech_rp_render_service( $service_id ) {
	$slug = get_post_field( 'post_name', $service_id );
	$c    = vtech_rp_service_content( $slug );
	if ( $c ) {
		?>
		<section class="section rp-guide" aria-labelledby="rp-guide-title"><div class="container narrow">
			<p class="eyebrow"><?php echo esc_html( $c['eyebrow'] ); ?></p>
			<h2 id="rp-guide-title" class="section__title"><?php echo esc_html( $c['title'] ); ?></h2>
			<p><?php echo esc_html( $c['intro'] ); ?></p>
			<?php foreach ( $c['tables'] as $t ) : ?>
				<h3><?php echo esc_html( $t['caption'] ); ?></h3>
				<?php echo vtech_rp_table( $t['head'], $t['rows'] ); // phpcs:ignore -- escaped inside. ?>
				<?php if ( $t['note'] ) : ?><p class="rp-note"><?php echo esc_html( $t['note'] ); ?></p><?php endif; ?>
			<?php endforeach; ?>
			<h3><?php echo esc_html( $c['included_title'] ); ?></h3>
			<ul class="rp-list"><?php foreach ( $c['included'] as $i ) : ?><li><?php echo esc_html( $i ); ?></li><?php endforeach; ?></ul>
			<p class="rp-planner-link"><a class="btn btn--accent" href="<?php echo esc_url( home_url( '/av-planner/' ) ); ?>"><?php echo esc_html( 'led-screens' === $slug ? __( 'Try the free LED screen size calculator', 'vtech-av' ) : __( 'Plan your meeting room with our free planner', 'vtech-av' ) ); ?></a></p>
			<h3><?php esc_html_e( 'More questions buyers ask', 'vtech-av' ); ?></h3>
			<?php echo vtech_rp_faq_html( $c['faqs'] ); // phpcs:ignore -- escaped inside. ?>
		</div></section>
		<?php
	}
	vtech_rp_render_proof( $service_id );
}

/* FAQ schema for the extra buyer FAQs. */
add_action( 'wp_head', function () {
	if ( ! is_singular( 'service' ) ) { return; }
	$id = get_queried_object_id();
	$c  = vtech_rp_service_content( get_post_field( 'post_name', $id ) );
	if ( $c ) { vtech_rp_faq_schema( $c['faqs'], get_permalink( $id ) ); }
}, 27 );

/* -------------------------------------------------------------------------
 * 4. Proof block: real projects, trusted brands, coverage
 * ---------------------------------------------------------------------- */

function vtech_rp_render_proof( $context_id = 0 ) {
	$q = new WP_Query( array(
		'post_type'      => 'project',
		'post_status'    => 'publish',
		'posts_per_page' => 3,
		'no_found_rows'  => true,
		'meta_query'     => array( array( 'key' => '_thumbnail_id', 'compare' => 'EXISTS' ) ),
	) );
	$brands = function_exists( 'vtech_get_brands' ) ? vtech_get_brands( 8 ) : array();
	if ( ! $q->have_posts() && ! $brands ) { return; }
	?>
	<section class="section section--surface rp-proof" aria-labelledby="rp-proof-title"><div class="container">
		<div class="section-head">
			<p class="eyebrow"><?php esc_html_e( 'Our work', 'vtech-av' ); ?></p>
			<h2 id="rp-proof-title" class="section__title"><?php esc_html_e( 'Recent projects across Kenya', 'vtech-av' ); ?></h2>
		</div>
		<?php if ( $q->have_posts() ) : ?>
		<div class="card-grid card-grid--3">
			<?php while ( $q->have_posts() ) : $q->the_post(); ?>
				<a class="card card--project" href="<?php the_permalink(); ?>">
					<figure class="card__media"><?php the_post_thumbnail( 'vtech-card', array( 'loading' => 'lazy' ) ); ?></figure>
					<div class="card__body"><h3 class="card__title"><?php the_title(); ?></h3><p class="card__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 16 ) ); ?></p></div>
				</a>
			<?php endwhile; wp_reset_postdata(); ?>
		</div>
		<p class="rp-more"><a href="<?php echo esc_url( home_url( '/projects/' ) ); ?>"><?php esc_html_e( 'See all projects', 'vtech-av' ); ?></a></p>
		<?php endif; ?>
		<?php if ( $brands ) : ?>
		<p class="rp-brands-label"><?php esc_html_e( 'Brands we supply, install and support', 'vtech-av' ); ?></p>
		<ul class="rp-brands">
			<?php foreach ( $brands as $b ) : ?>
				<li><?php if ( $b['logo'] ) : ?><img src="<?php echo esc_url( $b['logo'] ); ?>" alt="<?php echo esc_attr( $b['name'] ); ?>" loading="lazy" decoding="async"><?php else : echo esc_html( $b['name'] ); endif; ?></li>
			<?php endforeach; ?>
		</ul>
		<?php endif; ?>
		<p class="rp-coverage"><?php
			printf(
				/* translators: %s: link to the Nairobi page */
				esc_html__( 'Based at Mpaka Plaza, Westlands. %s and all 47 counties of Kenya.', 'vtech-av' ),
				'<a href="' . esc_url( home_url( '/audio-visual-company-nairobi/' ) ) . '">' . esc_html__( 'Serving Nairobi', 'vtech-av' ) . '</a>'
			); // phpcs:ignore
		?></p>
	</div></section>
	<?php
}

/* -------------------------------------------------------------------------
 * 2. Nairobi landing page + local signals
 * ---------------------------------------------------------------------- */

define( 'VTECH_RP_NAIROBI', 'audio-visual-company-nairobi' );

function vtech_rp_nairobi_areas() {
	return array( 'Westlands', 'Nairobi CBD', 'Upper Hill', 'Kilimani', 'Parklands', 'Gigiri', 'Karen', 'Lavington', 'Industrial Area', 'Mombasa Road', 'Embakasi', 'Ruaka', 'Kiambu Road', 'Thika Road', 'Rongai', 'Syokimau' );
}

function vtech_rp_nairobi_faqs() {
	return array(
		array( 'Where is VTECH located in Nairobi?', 'Ground Floor, Mpaka Plaza, Mpaka Road, Westlands, Nairobi. Visits are best booked in advance on +254 182 942 946.' ),
		array( 'Which parts of Nairobi do you cover?', 'All of Nairobi and its surroundings, including Westlands, the CBD, Upper Hill, Kilimani, Karen, Gigiri, Industrial Area, Mombasa Road, Ruaka, Thika Road and Rongai.' ),
		array( 'How fast can you visit a site in Nairobi?', 'Site surveys in Nairobi are usually arranged within a few working days, and the written quote follows within 24 hours of the visit.' ),
		array( 'Do you also hire out sound, LED screens and lighting in Nairobi?', 'Yes. Sound systems, LED screens and stage lighting are available for hire across Nairobi, delivered, set up and run by our technicians.' ),
		array( 'Do you work outside Nairobi?', 'Yes. We install and support systems in all 47 counties of Kenya and on selected projects in East Africa.' ),
	);
}

function vtech_rp_nairobi_html() {
	$s = function ( $path, $label ) { return '<a href="' . esc_url( home_url( $path ) ) . '">' . esc_html( $label ) . '</a>'; };
	$h  = '<p><strong>VTECH Audio Visual Solutions is an audio visual company based at Mpaka Plaza in Westlands, Nairobi.</strong> We design, supply, install, hire out and support professional sound, LED screens, stage lighting, conference and PA systems and acoustics for churches, corporates, banks, hotels, schools, hospitals, government and events across Nairobi.</p>';
	$h .= '<h2>AV services in Nairobi</h2><ul>';
	$h .= '<li>' . $s( '/services/sound-systems/', 'Sound and PA system installation' ) . ' for halls, churches, offices and venues.</li>';
	$h .= '<li>' . $s( '/services/church-sound-systems/', 'Church sound systems' ) . ', from small fellowships to large sanctuaries.</li>';
	$h .= '<li>' . $s( '/services/led-screens/', 'LED screens and video walls' ) . ', indoor and outdoor.</li>';
	$h .= '<li>' . $s( '/services/conference-systems/', 'Conference and boardroom AV' ) . ' for Teams, Zoom and Google Meet.</li>';
	$h .= '<li>' . $s( '/services/video-conferencing-systems/', 'Video conferencing systems' ) . '.</li>';
	$h .= '<li>' . $s( '/services/lighting/', 'Stage and event lighting' ) . '.</li>';
	$h .= '<li>' . $s( '/services/acoustic-solutions/', 'Acoustic treatment' ) . ' to fix echo in halls and boardrooms.</li>';
	$h .= '<li>' . $s( '/services/background-music-systems/', 'Background music and paging' ) . ' for hotels, restaurants, banks and malls.</li>';
	$h .= '<li>' . $s( '/services/digital-signage/', 'Digital signage' ) . '.</li>';
	$h .= '<li>' . $s( '/services/av-maintenance-support/', 'AV maintenance and repair' ) . '.</li>';
	$h .= '<li>' . $s( '/equipment-hire/', 'Sound, LED screen and lighting hire' ) . ' and ' . $s( '/hire-packages/', 'event hire packages' ) . '.</li>';
	$h .= '</ul>';
	$h .= '<h2>Areas we serve in Nairobi</h2><p>' . esc_html( implode( ', ', vtech_rp_nairobi_areas() ) ) . ', and the rest of Nairobi and Kiambu.</p>';
	$h .= '<h2>Why Nairobi organisations choose VTECH</h2><ul>';
	$h .= '<li>Free site survey and a fixed, itemised quote within 24 hours.</li>';
	$h .= '<li>One team for design, supply, installation, training and aftercare.</li>';
	$h .= '<li>Established professional brands such as dB Technologies and Shure.</li>';
	$h .= '<li>12 months of support on every installation, with maintenance contracts available.</li>';
	$h .= '<li>Local technicians who can be on site quickly anywhere in Nairobi.</li>';
	$h .= '</ul>';
	$h .= '<h2>Visit or contact us</h2><p>Ground Floor, Mpaka Plaza, Mpaka Road, Westlands, Nairobi. Phone and WhatsApp +254 182 942 946, email info@vtechaudio.co.ke. Open Monday to Friday 8:00 AM to 6:00 PM and Saturday 8:00 AM to 6:30 PM. ' . $s( '/book-a-consultation/', 'Book a free site survey' ) . '.</p>';
	return $h;
}

/* Create the page once (never overwrite an existing one). */
add_action( 'admin_init', function () {
	if ( get_option( 'vtech_rp_nairobi_page' ) ) { return; }
	if ( ! current_user_can( 'publish_pages' ) ) { return; }
	$existing = get_page_by_path( VTECH_RP_NAIROBI, OBJECT, 'page' );
	if ( $existing ) { update_option( 'vtech_rp_nairobi_page', (int) $existing->ID, false ); return; }
	$id = wp_insert_post( array(
		'post_type'    => 'page',
		'post_status'  => 'publish',
		'post_title'   => 'Audio Visual Company in Nairobi',
		'post_name'    => VTECH_RP_NAIROBI,
		'post_excerpt' => 'Sound, LED screens, lighting, conference and PA systems installed, hired and supported across Nairobi, from Mpaka Plaza, Westlands.',
		'post_content' => vtech_rp_nairobi_html(),
	), true );
	if ( ! is_wp_error( $id ) && $id ) { update_option( 'vtech_rp_nairobi_page', (int) $id, false ); }
} );

function vtech_rp_is_nairobi() {
	return is_page( VTECH_RP_NAIROBI );
}

/* FAQs under the page content (visible) ... */
add_filter( 'the_content', function ( $content ) {
	if ( ! vtech_rp_is_nairobi() || ! in_the_loop() || ! is_main_query() ) { return $content; }
	return $content . '<h2>Questions about our Nairobi service</h2>' . vtech_rp_faq_html( vtech_rp_nairobi_faqs() );
}, 20 );

/* ... and as schema, with a Nairobi-specific service description. */
add_action( 'wp_head', function () {
	if ( ! vtech_rp_is_nairobi() ) { return; }
	$url = get_permalink();
	vtech_rp_faq_schema( vtech_rp_nairobi_faqs(), $url );
	$places = array( array( '@type' => 'City', 'name' => 'Nairobi' ) );
	foreach ( vtech_rp_nairobi_areas() as $a ) { $places[] = array( '@type' => 'Place', 'name' => $a . ', Nairobi' ); }
	$svc = array(
		'@context'    => 'https://schema.org',
		'@type'       => 'Service',
		'@id'         => $url . '#service',
		'name'        => 'Audio visual installation, hire and support in Nairobi',
		'serviceType' => 'Audio visual integration',
		'url'         => $url,
		'provider'    => array( '@id' => vtech_rp_org_id() ),
		'areaServed'  => $places,
	);
	echo "\n<script type=\"application/ld+json\">" . wp_json_encode( $svc, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . "</script>\n"; // phpcs:ignore
}, 28 );

/* Title and description unless an editor has set their own in Rank Math. */
function vtech_rp_nairobi_title() { return 'Audio Visual Company in Nairobi | Sound, LED & Conference AV | VTECH'; }
function vtech_rp_nairobi_desc() { return 'Nairobi AV company at Mpaka Plaza, Westlands: sound and PA systems, LED screens, boardroom AV, lighting and hire. Free site survey, quote in 24 hours.'; }
function vtech_rp_custom( $field ) { return function_exists( 'vtech_has_custom_rm' ) ? vtech_has_custom_rm( $field ) : false; }
add_filter( 'rank_math/frontend/title', function ( $t ) { return ( vtech_rp_is_nairobi() && ! vtech_rp_custom( 'title' ) ) ? vtech_rp_nairobi_title() : $t; }, 30 );
add_filter( 'rank_math/frontend/description', function ( $d ) { return ( vtech_rp_is_nairobi() && ! vtech_rp_custom( 'description' ) ) ? vtech_rp_nairobi_desc() : $d; }, 30 );
add_filter( 'rank_math/opengraph/facebook/og_title', function ( $t ) { return ( vtech_rp_is_nairobi() && ! vtech_rp_custom( 'title' ) ) ? vtech_rp_nairobi_title() : $t; }, 30 );
add_filter( 'rank_math/opengraph/facebook/og_description', function ( $d ) { return ( vtech_rp_is_nairobi() && ! vtech_rp_custom( 'description' ) ) ? vtech_rp_nairobi_desc() : $d; }, 30 );

/* Nairobi neighbourhoods in the business areaServed (site-wide entity). */
add_filter( 'vtech_seo_areas', function ( $areas ) {
	foreach ( array( 'Nairobi CBD', 'Upper Hill', 'Kilimani', 'Karen', 'Gigiri', 'Industrial Area', 'Ruaka' ) as $a ) {
		if ( ! in_array( $a, $areas, true ) ) { $areas[] = $a; }
	}
	return $areas;
} );

/* -------------------------------------------------------------------------
 * 3. Industry depth: healthcare (hospital PA) and banking
 * ---------------------------------------------------------------------- */

function vtech_rp_industry_content( $slug ) {
	$c = array(
		'healthcare' => array(
			'title' => 'Hospital PA, paging and AV systems',
			'intro' => 'Hospitals and clinics need clear announcements that reach the right areas without disturbing patients. We design public address and paging systems zone by zone, alongside meeting, training and patient-information screens.',
			'list'  => array(
				'Zoned public address and paging for wards, outpatient areas, corridors and car parks.',
				'Priority and emergency announcements from a desk microphone or reception.',
				'Background music at low, controlled levels for waiting areas.',
				'Queue and wayfinding information on digital signage screens.',
				'Boardroom, training-room and telemedicine conferencing.',
				'Acoustic treatment for noisy waiting halls and chapels.',
			),
			'faqs'  => array(
				array( 'What is a hospital PA system?', 'A public address system that lets staff make announcements and pages to selected areas of a hospital, such as one ward, a waiting area or the whole building, with emergency messages taking priority.' ),
				array( 'Can announcements be limited to certain wards or areas?', 'Yes. We split the building into zones, so staff can page one area, several areas or all areas, and keep quiet zones quiet.' ),
				array( 'Can you upgrade an existing hospital PA system?', 'Often yes. We test the existing speakers, amplifiers and cabling, reuse what is sound and add zoning, microphones or speakers where needed.' ),
			),
		),
		'banking' => array(
			'title' => 'AV systems for banks and financial institutions',
			'intro' => 'Banks need professional head-office meeting rooms, dependable AGM and training venues, and calm, clear branch environments. We plan installations around security procedures and working hours.',
			'list'  => array(
				'Boardrooms and executive meeting rooms for Teams, Zoom and Google Meet.',
				'AGM, town-hall and training-room sound, delegate microphones, LED walls and streaming.',
				'Branch background music and paging, zoned and set at controlled levels.',
				'Digital signage for queues, rates, products and internal communication.',
				'Acoustic treatment for banking halls and open-plan offices.',
				'Maintenance contracts so meeting rooms and branches keep working.',
			),
			'faqs'  => array(
				array( 'Do you install AV systems for banks in Kenya?', 'Yes. We design, supply, install and support boardroom, AGM, training-room, branch audio and digital signage systems for banks and financial institutions across Kenya.' ),
				array( 'Can you standardise meeting rooms across several branches?', 'Yes. We create a standard design for each room size so every branch and head-office room works the same way, which makes support simpler.' ),
				array( 'Can installation happen outside banking hours?', 'Yes. We schedule noisy or disruptive work for evenings and weekends when needed, and follow your site security procedures.' ),
			),
		),
	);
	return isset( $c[ $slug ] ) ? $c[ $slug ] : null;
}

function vtech_rp_render_industry( $industry_id ) {
	$c = vtech_rp_industry_content( get_post_field( 'post_name', $industry_id ) );
	if ( ! $c ) { return; }
	?>
	<section class="section rp-guide" aria-labelledby="rp-ind-title"><div class="container narrow">
		<h2 id="rp-ind-title" class="section__title"><?php echo esc_html( $c['title'] ); ?></h2>
		<p><?php echo esc_html( $c['intro'] ); ?></p>
		<ul class="rp-list"><?php foreach ( $c['list'] as $i ) : ?><li><?php echo esc_html( $i ); ?></li><?php endforeach; ?></ul>
		<h3><?php esc_html_e( 'Frequently asked questions', 'vtech-av' ); ?></h3>
		<?php echo vtech_rp_faq_html( $c['faqs'] ); // phpcs:ignore -- escaped inside. ?>
	</div></section>
	<?php
}

add_action( 'wp_head', function () {
	if ( ! is_singular( 'industry' ) ) { return; }
	$id = get_queried_object_id();
	$c  = vtech_rp_industry_content( get_post_field( 'post_name', $id ) );
	if ( $c ) { vtech_rp_faq_schema( $c['faqs'], get_permalink( $id ) ); }
}, 27 );

/* Create the Banks industry page once. */
add_action( 'admin_init', function () {
	if ( get_option( 'vtech_rp_banking_page' ) ) { return; }
	if ( ! current_user_can( 'publish_posts' ) || ! post_type_exists( 'industry' ) ) { return; }
	$existing = get_page_by_path( 'banking', OBJECT, 'industry' );
	if ( $existing ) { update_option( 'vtech_rp_banking_page', (int) $existing->ID, false ); return; }
	$id = wp_insert_post( array(
		'post_type'    => 'industry',
		'post_status'  => 'publish',
		'post_title'   => 'Banks & Financial Institutions',
		'post_name'    => 'banking',
		'post_excerpt' => 'Boardroom, AGM, training-room, branch audio and digital signage systems for banks and financial institutions in Kenya.',
		'post_content' => '<p>VTECH designs, supplies, installs and supports audio visual systems for banks, SACCOs, insurers and other financial institutions in Nairobi and across Kenya, from head-office boardrooms to branch background music and queue screens.</p>',
	), true );
	if ( ! is_wp_error( $id ) && $id ) { update_option( 'vtech_rp_banking_page', (int) $id, false ); }
} );

/* Titles and descriptions for the two industry pages. */
function vtech_rp_industry_meta() {
	if ( ! is_singular( 'industry' ) ) { return null; }
	$m = array(
		'healthcare' => array( 'Hospital PA & Paging Systems Kenya | Healthcare AV | VTECH', 'Hospital PA, zoned paging, waiting-area audio, signage and conferencing for hospitals and clinics in Kenya. Free site survey, quote in 24 hours.' ),
		'banking'    => array( 'Bank AV Installation Kenya | Boardroom, AGM & Branch AV | VTECH', 'AV for banks in Kenya: boardrooms, AGM and training rooms, branch background music and paging, and digital signage. Free survey, 24-hour quote.' ),
	);
	$slug = get_post_field( 'post_name', get_queried_object_id() );
	return isset( $m[ $slug ] ) ? $m[ $slug ] : null;
}
add_filter( 'rank_math/frontend/title', function ( $t ) { $m = vtech_rp_industry_meta(); return ( $m && ! vtech_rp_custom( 'title' ) ) ? $m[0] : $t; }, 30 );
add_filter( 'rank_math/frontend/description', function ( $d ) { $m = vtech_rp_industry_meta(); return ( $m && ! vtech_rp_custom( 'description' ) ) ? $m[1] : $d; }, 30 );
add_filter( 'rank_math/opengraph/facebook/og_title', function ( $t ) { $m = vtech_rp_industry_meta(); return ( $m && ! vtech_rp_custom( 'title' ) ) ? $m[0] : $t; }, 30 );
add_filter( 'rank_math/opengraph/facebook/og_description', function ( $d ) { $m = vtech_rp_industry_meta(); return ( $m && ! vtech_rp_custom( 'description' ) ) ? $m[1] : $d; }, 30 );

/* v5.63.0: link the new buyer guides from the matching service pages. */
add_filter( 'vtech_service_guide_map', function ( $map ) {
	$add = array(
		'sound-systems'              => array( 'pa-system-kenya-guide' ),
		'consultation'               => array( 'how-to-choose-audio-visual-company-kenya' ),
		'video-conferencing-systems' => array( 'video-conferencing-system-kenya-guide' ),
		'conference-systems'         => array( 'conference-delegate-microphone-system-kenya', 'video-conferencing-system-kenya-guide' ),
		'digital-signage'            => array( 'digital-signage-kenya-guide' ),
		'lighting'                   => array( 'stage-lighting-kenya-guide' ),
		'background-music-systems'   => array( 'background-music-system-hotels-restaurants-kenya' ),
	);
	foreach ( $add as $svc => $slugs ) {
		$cur = isset( $map[ $svc ] ) ? (array) $map[ $svc ] : array();
		$map[ $svc ] = array_slice( array_values( array_unique( array_merge( $slugs, $cur ) ) ), 0, 3 );
	}
	return $map;
} );

/* -------------------------------------------------------------------------
 * v5.64.0: "Popular in Kenya" footer links. Every key money page and guide
 * gets a descriptive, keyword-matched link from every page of the site,
 * which helps Google, Bing and AI crawlers find and understand them.
 * Only published pages are shown.
 * ---------------------------------------------------------------------- */
function vtech_rp_footer_items() {
	return array(
		array( '/audio-visual-company-nairobi/', 'Audio visual company Nairobi' ),
		array( '/av-planner/', 'LED screen size calculator' ),
		array( '/services/sound-systems/', 'Sound & PA system installation Kenya' ),
		array( '/services/church-sound-systems/', 'Church sound systems Kenya' ),
		array( '/services/led-screens/', 'LED screens & video walls Kenya' ),
		array( '/services/conference-systems/', 'Boardroom & conference AV' ),
		array( '/services/video-conferencing-systems/', 'Video conferencing systems' ),
		array( '/services/lighting/', 'Stage lighting Kenya' ),
		array( '/services/acoustic-solutions/', 'Acoustic treatment Kenya' ),
		array( '/services/digital-signage/', 'Digital signage Kenya' ),
		array( '/services/background-music-systems/', 'Background music systems' ),
		array( '/services/av-maintenance-support/', 'AV maintenance & repair' ),
		array( '/equipment-hire/', 'Sound, LED & lighting hire Nairobi' ),
		array( '/industry/healthcare/', 'Hospital PA systems' ),
		array( '/industry/banking/', 'AV for banks' ),
		array( '/industry/education/', 'School PA & classroom AV' ),
		array( '/pa-system-kenya-guide/', 'PA system guide' ),
		array( '/church-sound-system-cost-kenya/', 'Church sound system cost' ),
		array( '/led-screens-kenya-guide/', 'LED screen buying guide' ),
	);
}

function vtech_rp_footer_links() {
	$items = array();
	foreach ( vtech_rp_footer_items() as $it ) {
		$pid = url_to_postid( home_url( $it[0] ) );
		if ( $pid && 'publish' !== get_post_status( $pid ) ) { continue; }
		if ( ! $pid && 0 !== strpos( $it[0], '/equipment-hire/' ) ) { continue; }
		$items[] = $it;
	}
	if ( ! $items ) { return; }
	echo '<nav class="container rp-footer-links" aria-label="' . esc_attr__( 'Popular in Kenya', 'vtech-av' ) . '"><p class="rp-footer-links__title">' . esc_html__( 'Popular in Kenya', 'vtech-av' ) . '</p><ul>';
	foreach ( $items as $it ) {
		echo '<li><a href="' . esc_url( home_url( $it[0] ) ) . '">' . esc_html( $it[1] ) . '</a></li>';
	}
	echo '</ul></nav>';
}

/* -------------------------------------------------------------------------
 * v5.64.1: publish the seven 5.63.0 guides. They were seeded with an evening
 * time on 19 Oct 2026, so WordPress saved them as "Scheduled" instead of
 * published. When an administrator opens wp-admin, any of them still
 * scheduled (or missed) is published now with a 10:00 date on that day.
 * Runs until all seven are published, then stops.
 * ---------------------------------------------------------------------- */
add_action( 'admin_init', function () {
	if ( wp_doing_ajax() || ! current_user_can( 'publish_posts' ) ) { return; }
	if ( get_option( 'vtech_rp_guides_published' ) ) { return; }
	$slugs = array( 'pa-system-kenya-guide', 'how-to-choose-audio-visual-company-kenya', 'video-conferencing-system-kenya-guide', 'digital-signage-kenya-guide', 'stage-lighting-kenya-guide', 'background-music-system-hotels-restaurants-kenya', 'conference-delegate-microphone-system-kenya' );
	$all = true;
	foreach ( $slugs as $i => $slug ) {
		$found = get_posts( array( 'name' => $slug, 'post_type' => 'post', 'post_status' => array( 'future', 'publish', 'draft', 'pending' ), 'numberposts' => 1, 'suppress_filters' => true ) );
		if ( ! $found ) { $all = false; continue; }
		$post = $found[0];
		if ( 'publish' === $post->post_status ) { continue; }
		if ( 'future' !== $post->post_status ) { $all = false; continue; } // Leave real drafts alone.
		$date = sprintf( '2026-10-19 10:%02d:00', $i * 5 );
		wp_update_post( array(
			'ID'            => $post->ID,
			'post_status'   => 'publish',
			'post_date'     => $date,
			'post_date_gmt' => get_gmt_from_date( $date ),
			'edit_date'     => true,
		) );
		if ( 'publish' !== get_post_status( $post->ID ) ) { $all = false; }
	}
	if ( $all ) { update_option( 'vtech_rp_guides_published', '1', false ); }
}, 40 );

/* -------------------------------------------------------------------------
 * v5.65.0: VTECH AV Planner (/av-planner/). Two free tools that answer the
 * questions buyers actually search for ("what size LED screen do I need",
 * "what pixel pitch", "what does our boardroom need"): an LED screen size and
 * pixel-pitch calculator and a meeting-room planner. Pure browser maths in
 * assets/js/av-planner.js (no data leaves the page, CSP-friendly external
 * file). Shortcode [vtech_av_planner]; page created once; WebApplication +
 * HowTo-free FAQ schema; linked from the LED and boardroom pages and footer.
 * ---------------------------------------------------------------------- */
define( 'VTECH_RP_PLANNER', 'av-planner' );

function vtech_rp_planner_faqs() {
	return array(
		array( 'What size LED screen do I need?', 'A common guideline is a screen height of about one sixth of the distance to the farthest viewer, in a 16:9 shape. For a farthest viewer at 18 metres that is about 3 metres high and 5.3 metres wide.' ),
		array( 'Which pixel pitch should I choose?', 'Roughly 1 mm of pixel pitch per metre to the closest viewer. If the front row is 3 metres away, P2.5 to P2.9 works well; outdoor screens are usually P3.9 and above.' ),
		array( 'What display size does a boardroom need?', 'Aim for an image height of about one sixth of the distance to the farthest seat. Rooms with more than 12 seats usually work better with two displays or a fine-pitch LED wall.' ),
		array( 'Are the planner results a quote?', 'No. They are guidelines to help you plan. VTECH confirms the design, brightness, structure and power at a free site survey and quotes within 24 hours.' ),
	);
}

add_shortcode( 'vtech_av_planner', function () {
	ob_start();
	?>
	<div class="ap">
		<section class="ap-card" aria-labelledby="ap-led-h">
			<h2 id="ap-led-h">LED screen size and pixel pitch calculator</h2>
			<form id="ap-led-form" class="ap-form">
				<label>Closest viewer distance (m)<input id="ap-led-near" type="number" min="0.5" step="0.1" value="4" required></label>
				<label>Farthest viewer distance (m)<input id="ap-led-far" type="number" min="1" step="0.5" value="20" required></label>
				<label>Available wall width (m, optional)<input id="ap-led-wall" type="number" min="0" step="0.1"></label>
				<label>Location<select id="ap-led-env"><option value="indoor">Indoor</option><option value="outdoor">Outdoor</option></select></label>
				<button class="btn btn--accent" type="submit">Calculate</button>
			</form>
			<div id="ap-led-out" class="ap-out" aria-live="polite"></div>
		</section>
		<section class="ap-card" aria-labelledby="ap-room-h">
			<h2 id="ap-room-h">Meeting room and boardroom planner</h2>
			<form id="ap-room-form" class="ap-form">
				<label>Number of seats<input id="ap-room-seats" type="number" min="1" step="1" value="12" required></label>
				<label>Farthest seat from the screen (m)<input id="ap-room-far" type="number" min="1" step="0.5" value="6" required></label>
				<button class="btn btn--accent" type="submit">Plan my room</button>
			</form>
			<div id="ap-room-out" class="ap-out" aria-live="polite"></div>
		</section>
		<p class="ap-cta"><a class="btn btn--lg" href="<?php echo esc_url( home_url( '/book-a-consultation/' ) ); ?>"><?php esc_html_e( 'Turn this into a real design: book a free site survey', 'vtech-av' ); ?></a></p>
		<h2><?php esc_html_e( 'Planner questions', 'vtech-av' ); ?></h2>
		<?php echo vtech_rp_faq_html( vtech_rp_planner_faqs() ); // phpcs:ignore -- escaped inside. ?>
	</div>
	<?php
	return ob_get_clean();
} );

function vtech_rp_is_planner() { return is_page( VTECH_RP_PLANNER ); }

add_action( 'wp_enqueue_scripts', function () {
	if ( ! vtech_rp_is_planner() ) { return; }
	wp_enqueue_script( 'vtech-av-planner', VTECH_URI . '/assets/js/av-planner.js', array(), VTECH_VERSION, array( 'strategy' => 'defer', 'in_footer' => true ) );
} );

add_action( 'admin_init', function () {
	if ( get_option( 'vtech_rp_planner_page' ) || ! current_user_can( 'publish_pages' ) ) { return; }
	$existing = get_page_by_path( VTECH_RP_PLANNER, OBJECT, 'page' );
	if ( $existing ) { update_option( 'vtech_rp_planner_page', (int) $existing->ID, false ); return; }
	$id = wp_insert_post( array(
		'post_type'    => 'page',
		'post_status'  => 'publish',
		'post_title'   => 'AV Planner: LED Screen Size & Boardroom Calculator',
		'post_name'    => VTECH_RP_PLANNER,
		'post_excerpt' => 'Free tools to work out the LED screen size and pixel pitch you need, and what your meeting room or boardroom needs.',
		'post_content' => '<p><strong>Plan your project in a minute.</strong> Enter your room measurements to get a suggested LED screen size, pixel pitch and resolution, or the display, camera and microphones a meeting room needs. Everything runs in your browser.</p>[vtech_av_planner]',
	), true );
	if ( ! is_wp_error( $id ) && $id ) { update_option( 'vtech_rp_planner_page', (int) $id, false ); }
} );

add_action( 'wp_head', function () {
	if ( ! vtech_rp_is_planner() ) { return; }
	$url = get_permalink();
	vtech_rp_faq_schema( vtech_rp_planner_faqs(), $url );
	$app = array(
		'@context'            => 'https://schema.org',
		'@type'               => 'WebApplication',
		'@id'                 => $url . '#app',
		'name'                => 'VTECH AV Planner',
		'url'                 => $url,
		'applicationCategory' => 'UtilitiesApplication',
		'operatingSystem'     => 'Any (web browser)',
		'isAccessibleForFree' => true,
		'offers'              => array( '@type' => 'Offer', 'price' => '0', 'priceCurrency' => 'KES' ),
		'featureList'         => array( 'LED screen size calculator', 'LED pixel pitch calculator', 'Meeting room and boardroom AV planner' ),
		'publisher'           => array( '@id' => vtech_rp_org_id() ),
		'inLanguage'          => 'en-KE',
	);
	echo "\n<script type=\"application/ld+json\">" . wp_json_encode( $app, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . "</script>\n"; // phpcs:ignore
}, 28 );

function vtech_rp_planner_title() { return 'LED Screen Size & Pixel Pitch Calculator | Boardroom AV Planner | VTECH'; }
function vtech_rp_planner_desc() { return 'Free calculator: what size LED screen and pixel pitch you need, and what your boardroom or meeting room needs. Instant results, made in Kenya by VTECH.'; }
add_filter( 'rank_math/frontend/title', function ( $t ) { return ( vtech_rp_is_planner() && ! vtech_rp_custom( 'title' ) ) ? vtech_rp_planner_title() : $t; }, 31 );
add_filter( 'rank_math/frontend/description', function ( $d ) { return ( vtech_rp_is_planner() && ! vtech_rp_custom( 'description' ) ) ? vtech_rp_planner_desc() : $d; }, 31 );
add_filter( 'rank_math/opengraph/facebook/og_title', function ( $t ) { return ( vtech_rp_is_planner() && ! vtech_rp_custom( 'title' ) ) ? vtech_rp_planner_title() : $t; }, 31 );
add_filter( 'rank_math/opengraph/facebook/og_description', function ( $d ) { return ( vtech_rp_is_planner() && ! vtech_rp_custom( 'description' ) ) ? vtech_rp_planner_desc() : $d; }, 31 );
