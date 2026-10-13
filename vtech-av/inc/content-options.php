<?php
/**
 * Editable homepage + service content (v5.44.0).
 *
 * Everything that used to be typed into the templates is now editable:
 *  - Customize > VTECH Theme Options > Homepage Sections: every section label,
 *    title and intro, the "Why VTECH" points, the "How we work" steps, the
 *    industry cards (title, subtitle, image, link), the homepage FAQs and the
 *    closing call to action.
 *  - Each Service edit screen: a "Service page content" box for the benefits
 *    and FAQs shown on that service page (also used for FAQ search data).
 *
 * List fields use one item per line, with parts separated by " | ".
 *
 * @package VTECH_AV
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

/** Default homepage copy (used until something is saved in the Customizer). */
function vtech_hs_defaults() {
	$img = VTECH_URI . '/assets/img/';
	return array(
		'hero_eyebrow'  => 'Audio Visual Company · Kenya & East Africa',
		'hero_btn1'     => 'Book a Consultation',
		'hero_btn2'     => 'See Our Projects',
		'clients_label' => 'Trusted by leading organisations across Kenya',
		'svc_eyebrow'   => 'What we do',
		'svc_title'     => 'Complete Audio Visual Solutions',
		'svc_lead'      => 'From boardrooms in Nairobi to auditoriums nationwide — one integrator for design, supply, installation and support.',
		'ind_eyebrow'   => 'Industries',
		'ind_title'     => 'AV Solutions for Every Sector in Kenya',
		'ind_lead'      => 'Systems engineered around how each sector actually uses sound, video and light.',
		'ind_items'     => implode( "\n", array(
			'Churches | Church sound systems Kenya | ' . $img . 'industry-churches.webp | /industry/churches/',
			'Hotels & Hospitality | Hotel audio visual solutions Kenya | ' . $img . 'industry-hotels.webp | /industry/hotels/',
			'Schools & Universities | School PA systems Kenya | ' . $img . 'industry-education.webp | /industry/education/',
			'Corporates | Boardroom AV solutions Nairobi | ' . $img . 'industry-corporate.webp | /industry/corporate/',
			'Government | Conference systems Kenya | ' . $img . 'industry-government.webp | /industry/government/',
			'Media Houses | Broadcast & live streaming | ' . $img . 'industry-media.webp | /industry/media/',
			'Hospitals & Healthcare | Nurse call & PA systems Kenya | ' . $img . 'industry-healthcare.webp | /industry/healthcare/',
			'Conference Centres | Delegate & conference systems | ' . $img . 'industry-conference-centres.webp | /industry/conference-centres/',
			'Event Organisers | Sound & lighting hire Kenya | ' . $img . 'industry-events.webp | /industry/events/',
		) ),
		'why_eyebrow'   => 'Why VTECH',
		'why_title'     => 'One accountable AV partner, from survey to support',
		'why_lead'      => 'We design, supply, install and look after every system ourselves, so you get one team, one quote and one point of contact.',
		'why_items'     => implode( "\n", array(
			'Design-led engineering | Every project starts with a free site survey and a written technical design matched to your room, audience and budget.',
			'Fixed quotes in 24 hours | Clear, itemised pricing within 24 hours in Nairobi and 48 hours upcountry. No surprise costs after installation.',
			'Trusted pro-audio brands | dB Technologies, Shure and leading LED, lighting and conferencing brands, supplied with genuine warranties.',
			'Support across Kenya | 12 months of support on every installation, maintenance contracts and technicians covering all 47 counties.',
		) ),
		'steps_title'   => 'How we work',
		'steps_items'   => implode( "\n", array(
			'Free site survey | We visit your venue, listen to how you use the space and take measurements.',
			'Design and fixed quote | You receive a technical design and a fixed, itemised quote within 24 hours.',
			'Install and commission | Our technicians install, tune and test everything to the agreed design.',
			'Training and support | We train your team and stay on call with 12 months of support.',
		) ),
		'proj_eyebrow'  => 'Our work',
		'proj_title'    => 'Recent Projects in Kenya',
		'proj_btn'      => 'View all projects',
		'faq_eyebrow'   => 'FAQ',
		'faq_title'     => 'Frequently Asked Questions',
		'faq_items'     => implode( "\n", array(
			'How fast can VTECH quote my AV project? | We provide a fixed written quote within 24 hours of a free site survey in Nairobi, and within 48 hours upcountry.',
			'Do you cover locations outside Nairobi? | Yes. We install and support AV systems across all 47 counties in Kenya, including Mombasa, Kisumu, Nakuru and Eldoret, and select projects across East Africa.',
			'Do you supply dB Technologies speakers and Shure microphones in Kenya? | Yes. We supply, install and hire dB Technologies speakers and line arrays and Shure wired and wireless microphones, matched to your venue and set up by our technicians.',
			'Can you help me choose the right audio mixer? | Yes. We recommend analogue or digital mixers based on your number of microphones, instruments and outputs, then install, configure and train your team on site.',
			'Do you offer equipment hire as well as installation? | Yes. Sound, PA, lighting, LED screen and conferencing equipment is available for event hire in Nairobi and across Kenya, with delivery, setup and an on-site technician.',
			'Do you provide maintenance after installation? | Every installation includes a 12-month support window, with annual maintenance contracts available.',
		) ),
		'cta_title'     => 'Planning an AV project in Kenya?',
		'cta_text'      => 'Book a free site survey and get a fixed quote within 24 hours.',
		'cta_btn'       => 'Request a Site Survey',
	);
}

/** One homepage text value. */
function vtech_hs( $key ) {
	$d = vtech_hs_defaults();
	$v = get_theme_mod( 'vtech_hs_' . $key, '' );
	return ( '' === trim( (string) $v ) && isset( $d[ $key ] ) ) ? $d[ $key ] : (string) $v;
}

/**
 * Parse a "one item per line, parts separated by |" field into rows.
 *
 * @param string $text Raw text.
 * @param int    $cols Expected columns (missing parts become '').
 * @return array
 */
function vtech_pipe_rows( $text, $cols ) {
	$rows = array();
	foreach ( preg_split( '/\r\n|\r|\n/', (string) $text ) as $line ) {
		$line = trim( $line );
		if ( '' === $line ) { continue; }
		$parts = array_map( 'trim', explode( '|', $line, $cols ) );
		$rows[] = array_pad( $parts, $cols, '' );
	}
	return $rows;
}

function vtech_hs_rows( $key, $cols ) {
	return vtech_pipe_rows( vtech_hs( $key ), $cols );
}

/** Homepage FAQs come from the Customizer field (also used for FAQ schema). */
add_filter( 'vtech_home_faqs', function ( $faqs ) {
	$rows = vtech_hs_rows( 'faq_items', 2 );
	$out  = array();
	foreach ( $rows as $r ) { if ( $r[0] && $r[1] ) { $out[] = array( $r[0], $r[1] ); } }
	return $out ? $out : $faqs;
}, 5 );

/* -------------------------------------------------------------------------
 * Customizer: Homepage Sections
 * ---------------------------------------------------------------------- */
add_action( 'customize_register', function ( $wp_customize ) {
	$wp_customize->add_section( 'vtech_home_sections', array(
		'title'       => 'Homepage Sections',
		'panel'       => 'vtech_panel',
		'description' => 'Edit every homepage heading and list. For lists, put one item per line and separate the parts with a | character. Leave a field empty to restore its default text.',
	) );
	$d      = vtech_hs_defaults();
	$labels = array(
		'hero_eyebrow'  => array( 'Hero: small label above the title', 'text' ),
		'hero_btn1'     => array( 'Hero: main button text', 'text' ),
		'hero_btn2'     => array( 'Hero: second button text', 'text' ),
		'clients_label' => array( 'Client logos: label', 'text' ),
		'svc_eyebrow'   => array( 'Services: label', 'text' ),
		'svc_title'     => array( 'Services: title', 'text' ),
		'svc_lead'      => array( 'Services: intro', 'textarea' ),
		'ind_eyebrow'   => array( 'Industries: label', 'text' ),
		'ind_title'     => array( 'Industries: title', 'text' ),
		'ind_lead'      => array( 'Industries: intro', 'textarea' ),
		'ind_items'     => array( 'Industries: cards (Title | Subtitle | Image URL | Link)', 'textarea', 'Image URL: upload in Media > Add New, then copy the file URL.' ),
		'why_eyebrow'   => array( 'Why VTECH: label', 'text' ),
		'why_title'     => array( 'Why VTECH: title', 'text' ),
		'why_lead'      => array( 'Why VTECH: intro', 'textarea' ),
		'why_items'     => array( 'Why VTECH: points (Title | Text)', 'textarea' ),
		'steps_title'   => array( 'How we work: title', 'text' ),
		'steps_items'   => array( 'How we work: steps (Title | Text)', 'textarea' ),
		'proj_eyebrow'  => array( 'Projects: label', 'text' ),
		'proj_title'    => array( 'Projects: title', 'text' ),
		'proj_btn'      => array( 'Projects: button text', 'text' ),
		'faq_eyebrow'   => array( 'FAQ: label', 'text' ),
		'faq_title'     => array( 'FAQ: title', 'text' ),
		'faq_items'     => array( 'FAQ: questions (Question | Answer)', 'textarea', 'Shown on the homepage and sent to Google as FAQ data.' ),
		'cta_title'     => array( 'Closing banner: title', 'text' ),
		'cta_text'      => array( 'Closing banner: text', 'textarea' ),
		'cta_btn'       => array( 'Closing banner: button text', 'text' ),
	);
	foreach ( $labels as $key => $l ) {
		$id = 'vtech_hs_' . $key;
		$wp_customize->add_setting( $id, array(
			'default'           => $d[ $key ],
			'sanitize_callback' => 'textarea' === $l[1] ? 'sanitize_textarea_field' : 'sanitize_text_field',
		) );
		$wp_customize->add_control( $id, array(
			'label'       => $l[0],
			'description' => isset( $l[2] ) ? $l[2] : '',
			'section'     => 'vtech_home_sections',
			'type'        => $l[1],
		) );
	}
}, 15 );

/* -------------------------------------------------------------------------
 * Service edit screen: benefits + FAQs box
 * ---------------------------------------------------------------------- */
add_action( 'add_meta_boxes', function () {
	add_meta_box( 'vtech_service_content', __( 'Service page content', 'vtech-av' ), 'vtech_service_content_box', 'service', 'normal', 'high' );
} );

function vtech_service_rows_to_text( $rows, $a, $b ) {
	$lines = array();
	foreach ( (array) $rows as $r ) {
		if ( ! empty( $r[ $a ] ) ) { $lines[] = $r[ $a ] . ' | ' . ( isset( $r[ $b ] ) ? $r[ $b ] : '' ); }
	}
	return implode( "\n", $lines );
}

function vtech_service_content_box( $post ) {
	wp_nonce_field( 'vtech_service_content', 'vtech_service_content_nonce' );
	$benefits = get_post_meta( $post->ID, 'vtech_svc_benefits', true );
	$faqs     = get_post_meta( $post->ID, 'vtech_svc_faqs', true );
	$tagline  = get_post_meta( $post->ID, 'vtech_svc_tagline', true );
	if ( '' === (string) $benefits || '' === (string) $faqs ) {
		$cat = function_exists( 'vtech_service_catalog_item' ) ? vtech_service_catalog_item( $post->post_name ) : null;
		if ( $cat ) {
			if ( '' === (string) $benefits ) { $benefits = vtech_service_rows_to_text( $cat['benefits'], 'title', 'text' ); }
			if ( '' === (string) $faqs )     { $faqs = vtech_service_rows_to_text( $cat['faqs'], 'question', 'answer' ); }
		}
	}
	?>
	<p><?php esc_html_e( 'One item per line. Separate the parts with a | character. Leave a box empty to use the standard VTECH text.', 'vtech-av' ); ?></p>
	<p><label for="vtech_svc_tagline"><strong><?php esc_html_e( 'Hero tagline (under the page title)', 'vtech-av' ); ?></strong></label><br>
	<input type="text" class="widefat" id="vtech_svc_tagline" name="vtech_svc_tagline" value="<?php echo esc_attr( $tagline ); ?>" placeholder="<?php esc_attr_e( 'Leave empty to use the excerpt', 'vtech-av' ); ?>"></p>
	<p><label for="vtech_svc_benefits"><strong><?php esc_html_e( 'Benefits (Title | Text)', 'vtech-av' ); ?></strong></label><br>
	<textarea class="widefat" rows="7" id="vtech_svc_benefits" name="vtech_svc_benefits"><?php echo esc_textarea( $benefits ); ?></textarea></p>
	<p><label for="vtech_svc_faqs"><strong><?php esc_html_e( 'FAQs (Question | Answer)', 'vtech-av' ); ?></strong></label><br>
	<textarea class="widefat" rows="8" id="vtech_svc_faqs" name="vtech_svc_faqs"><?php echo esc_textarea( $faqs ); ?></textarea>
	<span class="description"><?php esc_html_e( 'FAQs are shown on the page and sent to Google as FAQ data.', 'vtech-av' ); ?></span></p>
	<?php
}

add_action( 'save_post_service', function ( $post_id ) {
	if ( ! isset( $_POST['vtech_service_content_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['vtech_service_content_nonce'] ) ), 'vtech_service_content' ) ) { return; }
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) { return; }
	if ( ! current_user_can( 'edit_post', $post_id ) ) { return; }
	foreach ( array( 'vtech_svc_benefits', 'vtech_svc_faqs' ) as $k ) {
		update_post_meta( $post_id, $k, isset( $_POST[ $k ] ) ? sanitize_textarea_field( wp_unslash( $_POST[ $k ] ) ) : '' );
	}
	update_post_meta( $post_id, 'vtech_svc_tagline', isset( $_POST['vtech_svc_tagline'] ) ? sanitize_text_field( wp_unslash( $_POST['vtech_svc_tagline'] ) ) : '' );
} );

/**
 * Service content for templates + schema: edit-screen box first, then the
 * built-in catalogue. Returns array( 'benefits' => [...], 'faqs' => [...] ).
 */
function vtech_service_content( $post_id ) {
	$out = array( 'benefits' => array(), 'faqs' => array(), 'tagline' => '' );
	foreach ( vtech_pipe_rows( get_post_meta( $post_id, 'vtech_svc_benefits', true ), 2 ) as $r ) {
		if ( $r[0] ) { $out['benefits'][] = array( 'title' => $r[0], 'text' => $r[1] ); }
	}
	foreach ( vtech_pipe_rows( get_post_meta( $post_id, 'vtech_svc_faqs', true ), 2 ) as $r ) {
		if ( $r[0] && $r[1] ) { $out['faqs'][] = array( 'question' => $r[0], 'answer' => $r[1] ); }
	}
	$out['tagline'] = (string) get_post_meta( $post_id, 'vtech_svc_tagline', true );
	$cat = function_exists( 'vtech_service_catalog_item' ) ? vtech_service_catalog_item( get_post_field( 'post_name', $post_id ) ) : null;
	if ( $cat ) {
		if ( ! $out['benefits'] ) { $out['benefits'] = $cat['benefits']; }
		if ( ! $out['faqs'] )     { $out['faqs'] = $cat['faqs']; }
	}
	return $out;
}
