<?php
/**
 * WhatsApp order + consultation popup (v5.44.0).
 *
 * Any button with data-wa-order="package|item|consult|enquiry" (and every
 * plain wa.me link on the site) opens a short form. On submit the visitor's
 * details are logged as a lead in wp-admin (Hire Requests / Consultations,
 * with a reference number and an email to the office), then WhatsApp opens
 * with a professionally formatted message ready to send.
 *
 * Everything visitors see is editable in Appearance > Customize >
 * VTECH Theme Options > WhatsApp Orders.
 *
 * @package VTECH_AV
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

/** Default, editable copy for the popup. */
function vtech_wa_defaults() {
	return array(
		'vtech_wa_popup'          => true,
		'vtech_wa_title_package'  => 'Book this package on WhatsApp',
		'vtech_wa_title_item'     => 'Order on WhatsApp',
		'vtech_wa_title_consult'  => 'Book a consultation on WhatsApp',
		'vtech_wa_title_enquiry'  => 'Chat with VTECH on WhatsApp',
		'vtech_wa_intro'          => 'Share a few details and we will open WhatsApp with your request ready to send. Our team replies within business hours, usually in under an hour.',
		'vtech_wa_greeting'       => 'Hello VTECH Audio Visual Solutions,',
		'vtech_wa_signoff'        => 'Sent from vtechaudio.co.ke',
		'vtech_wa_button'         => 'Continue to WhatsApp',
		'vtech_wa_services'       => "Sound / PA system installation\nChurch sound system\nLED screen or video wall\nConference / boardroom AV\nVideo conferencing room\nStage lighting\nAcoustic treatment / soundproofing\nDigital signage\nBackground music / paging\nAV maintenance or repair\nEquipment hire for an event\nNot sure yet - advise me",
		'vtech_wa_event_types'    => "Wedding\nConference / seminar\nCorporate event or AGM\nChurch service or crusade\nConcert or festival\nGraduation or school event\nProduct launch\nFuneral or memorial\nOther",
		'vtech_wa_topics'         => "Request a quotation\nEquipment hire\nNew installation\nSupport or repair\nPartnership / supply\nOther",
	);
}

function vtech_wa_opt( $key ) {
	$d = vtech_wa_defaults();
	$v = get_theme_mod( $key, isset( $d[ $key ] ) ? $d[ $key ] : '' );
	return ( '' === $v || null === $v ) && isset( $d[ $key ] ) ? $d[ $key ] : $v;
}

function vtech_wa_lines( $key ) {
	return array_values( array_filter( array_map( 'trim', preg_split( '/\r\n|\r|\n/', (string) vtech_wa_opt( $key ) ) ) ) );
}

/* -------------------------------------------------------------------------
 * Customizer
 * ---------------------------------------------------------------------- */
add_action( 'customize_register', function ( $wp_customize ) {
	$wp_customize->add_section( 'vtech_wa', array(
		'title'       => 'WhatsApp Orders & Consultations',
		'panel'       => 'vtech_panel',
		'description' => 'Controls the popup that opens when visitors click any WhatsApp, "Book on WhatsApp" or "Order on WhatsApp" button. The WhatsApp number comes from Company Details.',
	) );
	$d      = vtech_wa_defaults();
	$fields = array(
		'vtech_wa_popup'         => array( 'Show the details popup before opening WhatsApp', 'checkbox' ),
		'vtech_wa_title_package' => array( 'Popup title: hire package', 'text' ),
		'vtech_wa_title_item'    => array( 'Popup title: equipment item', 'text' ),
		'vtech_wa_title_consult' => array( 'Popup title: consultation', 'text' ),
		'vtech_wa_title_enquiry' => array( 'Popup title: general chat', 'text' ),
		'vtech_wa_intro'         => array( 'Popup intro text', 'textarea' ),
		'vtech_wa_greeting'      => array( 'First line of the WhatsApp message', 'text' ),
		'vtech_wa_signoff'       => array( 'Last line of the WhatsApp message', 'text' ),
		'vtech_wa_button'        => array( 'Submit button text', 'text' ),
		'vtech_wa_services'      => array( 'Consultation services (one per line)', 'textarea' ),
		'vtech_wa_event_types'   => array( 'Event types (one per line)', 'textarea' ),
		'vtech_wa_topics'        => array( 'General chat topics (one per line)', 'textarea' ),
	);
	foreach ( $fields as $id => $f ) {
		$wp_customize->add_setting( $id, array(
			'default'           => $d[ $id ],
			'sanitize_callback' => 'checkbox' === $f[1] ? 'wp_validate_boolean' : ( 'textarea' === $f[1] ? 'sanitize_textarea_field' : 'sanitize_text_field' ),
		) );
		$wp_customize->add_control( $id, array( 'label' => $f[0], 'section' => 'vtech_wa', 'type' => $f[1] ) );
	}
}, 20 );

/* -------------------------------------------------------------------------
 * Helpers for templates
 * ---------------------------------------------------------------------- */

/** Data attributes that turn a link into a popup trigger. */
function vtech_wa_attrs( $kind, $item = '', $price = '', $extra = '' ) {
	$out = ' data-wa-order="' . esc_attr( $kind ) . '"';
	if ( '' !== $item )  { $out .= ' data-wa-item="' . esc_attr( wp_strip_all_tags( html_entity_decode( $item, ENT_QUOTES, 'UTF-8' ) ) ) . '"'; }
	if ( '' !== $price ) { $out .= ' data-wa-price="' . esc_attr( $price ) . '"'; }
	if ( '' !== $extra ) { $out .= ' data-wa-extra="' . esc_attr( $extra ) . '"'; }
	return $out;
}

/** Popup attributes for a hire package post. */
function vtech_wa_pkg_attrs( $post_id ) {
	$price = get_post_meta( $post_id, 'vtech_pkg_price', true );
	if ( '' === (string) $price && function_exists( 'get_field' ) ) { $price = (string) get_field( 'price', $post_id ); }
	$price = trim( (string) $price );
	if ( '' !== $price && is_numeric( str_replace( array( ',', ' ' ), '', $price ) ) ) {
		$price = 'KES ' . number_format( (float) str_replace( array( ',', ' ' ), '', $price ) );
	}
	$cap = trim( (string) get_post_meta( $post_id, 'vtech_pkg_capacity', true ) );
	return vtech_wa_attrs( 'package', get_the_title( $post_id ), $price, $cap ? 'Up to ' . $cap . ' guests' : '' );
}

/* -------------------------------------------------------------------------
 * Front end: config + popup markup
 * ---------------------------------------------------------------------- */
add_action( 'wp_enqueue_scripts', function () {
	wp_localize_script( 'vtech-app', 'VTECH_WA', array(
		'enabled'  => (bool) vtech_wa_opt( 'vtech_wa_popup' ),
		'number'   => vtech_contact_wa(),
		'endpoint' => esc_url_raw( rest_url( 'vtech/v1/wa-lead' ) ),
		'greeting' => vtech_wa_opt( 'vtech_wa_greeting' ),
		'signoff'  => vtech_wa_opt( 'vtech_wa_signoff' ),
		'company'  => vtech_contact( 'company' ),
		'titles'   => array(
			'package' => vtech_wa_opt( 'vtech_wa_title_package' ),
			'item'    => vtech_wa_opt( 'vtech_wa_title_item' ),
			'consult' => vtech_wa_opt( 'vtech_wa_title_consult' ),
			'enquiry' => vtech_wa_opt( 'vtech_wa_title_enquiry' ),
		),
	) );
}, 20 );

add_action( 'wp_footer', 'vtech_wa_modal_markup', 5 );
function vtech_wa_modal_markup() {
	if ( ! vtech_wa_opt( 'vtech_wa_popup' ) || is_admin() ) { return; }
	$opts = function ( $key ) {
		$h = '<option value="">' . esc_html__( 'Select...', 'vtech-av' ) . '</option>';
		foreach ( vtech_wa_lines( $key ) as $o ) { $h .= '<option>' . esc_html( $o ) . '</option>'; }
		return $h;
	};
	$guests = array( 'Under 50', '50 - 150', '150 - 500', '500 - 1,000', '1,000 - 5,000', 'Above 5,000' );
	$cc     = array( '254' => 'Kenya +254', '255' => 'Tanzania +255', '256' => 'Uganda +256', '250' => 'Rwanda +250', '257' => 'Burundi +257', '211' => 'South Sudan +211', '251' => 'Ethiopia +251', '252' => 'Somalia +252', '243' => 'DR Congo +243', '' => 'Other (type full number)' );
	?>
<div class="wa-modal" id="wa-modal" hidden role="dialog" aria-modal="true" aria-labelledby="wa-modal-title">
	<div class="wa-modal__backdrop" data-wa-close></div>
	<div class="wa-modal__panel" role="document">
		<header class="wa-modal__head">
			<span class="wa-modal__badge" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M12 2a10 10 0 0 0-8.6 15.1L2 22l5-1.3A10 10 0 1 0 12 2zm4.5 12.1c-.2-.1-1.5-.7-1.7-.8-.2-.1-.4-.1-.6.1l-.8 1c-.1.2-.3.2-.5.1a6.7 6.7 0 0 1-3.3-2.9c-.2-.4.2-.4.7-1.3.1-.2 0-.3 0-.4l-.8-1.8c-.2-.5-.4-.4-.6-.4h-.5a1 1 0 0 0-.7.3 3 3 0 0 0-.9 2.2 5.2 5.2 0 0 0 1.1 2.7 11.8 11.8 0 0 0 4.5 4c1.7.7 2.3.8 3.2.6a2.7 2.7 0 0 0 1.8-1.2 2.2 2.2 0 0 0 .2-1.3c-.1-.1-.3-.2-.5-.3z"/></svg></span>
			<div>
				<h2 class="wa-modal__title" id="wa-modal-title"><?php echo esc_html( vtech_wa_opt( 'vtech_wa_title_enquiry' ) ); ?></h2>
				<p class="wa-modal__intro"><?php echo esc_html( vtech_wa_opt( 'vtech_wa_intro' ) ); ?></p>
			</div>
			<button type="button" class="wa-modal__close" data-wa-close aria-label="<?php esc_attr_e( 'Close', 'vtech-av' ); ?>">&times;</button>
		</header>

		<div class="wa-modal__summary" data-wa-summary hidden>
			<span class="wa-modal__summary-label" data-wa-summary-label><?php esc_html_e( 'Selected', 'vtech-av' ); ?></span>
			<strong data-wa-summary-item></strong>
			<span class="wa-modal__summary-meta" data-wa-summary-meta></span>
		</div>

		<form class="wa-modal__form" data-wa-form novalidate>
			<p class="vtech-hp" aria-hidden="true"><label>Leave empty<input type="text" name="website" tabindex="-1" autocomplete="off"></label></p>

			<fieldset class="wa-grid">
				<legend class="wa-legend"><?php esc_html_e( 'Your details', 'vtech-av' ); ?></legend>
				<div class="wa-field"><label for="wa-name"><?php esc_html_e( 'Full name *', 'vtech-av' ); ?></label><input id="wa-name" name="name" type="text" autocomplete="name" required></div>
				<div class="wa-field"><label for="wa-org"><?php esc_html_e( 'Organisation (optional)', 'vtech-av' ); ?></label><input id="wa-org" name="organisation" type="text" autocomplete="organization"></div>
				<div class="wa-field wa-field--phone"><label for="wa-phone"><?php esc_html_e( 'Phone / WhatsApp *', 'vtech-av' ); ?></label>
					<div class="wa-phone">
						<select name="cc" aria-label="<?php esc_attr_e( 'Country code', 'vtech-av' ); ?>"><?php foreach ( $cc as $k => $l ) { echo '<option value="' . esc_attr( $k ) . '">' . esc_html( $l ) . '</option>'; } ?></select>
						<input id="wa-phone" name="phone" type="tel" inputmode="tel" autocomplete="tel-national" placeholder="0712 345 678" required>
					</div>
				</div>
				<div class="wa-field"><label for="wa-email"><?php esc_html_e( 'Email (optional)', 'vtech-av' ); ?></label><input id="wa-email" name="email" type="email" inputmode="email" autocomplete="email"></div>
			</fieldset>

			<fieldset class="wa-grid" data-wa-for="package item">
				<legend class="wa-legend"><?php esc_html_e( 'Event details', 'vtech-av' ); ?></legend>
				<div class="wa-field"><label for="wa-date"><?php esc_html_e( 'Event date *', 'vtech-av' ); ?></label><input id="wa-date" name="event_date" type="date" data-required></div>
				<div class="wa-field"><label for="wa-days"><?php esc_html_e( 'Number of days', 'vtech-av' ); ?></label><input id="wa-days" name="days" type="number" min="1" value="1"></div>
				<div class="wa-field wa-field--full"><label for="wa-venue"><?php esc_html_e( 'Venue and town *', 'vtech-av' ); ?></label><input id="wa-venue" name="venue" type="text" placeholder="<?php esc_attr_e( 'e.g. KICC, Nairobi', 'vtech-av' ); ?>" data-required></div>
				<div class="wa-field"><label for="wa-type"><?php esc_html_e( 'Event type', 'vtech-av' ); ?></label><select id="wa-type" name="event_type"><?php echo $opts( 'vtech_wa_event_types' ); // phpcs:ignore ?></select></div>
				<div class="wa-field"><label for="wa-guests"><?php esc_html_e( 'Expected guests', 'vtech-av' ); ?></label><select id="wa-guests" name="guests"><option value=""><?php esc_html_e( 'Select...', 'vtech-av' ); ?></option><?php foreach ( $guests as $g ) { echo '<option>' . esc_html( $g ) . '</option>'; } ?></select></div>
				<div class="wa-field" data-wa-for="item"><label for="wa-qty"><?php esc_html_e( 'Quantity', 'vtech-av' ); ?></label><input id="wa-qty" name="quantity" type="number" min="1" value="1"></div>
				<div class="wa-field"><label for="wa-setup"><?php esc_html_e( 'Technician on site?', 'vtech-av' ); ?></label><select id="wa-setup" name="technician"><option>Yes, please</option><option>No, delivery only</option><option>Not sure, advise me</option></select></div>
			</fieldset>

			<fieldset class="wa-grid" data-wa-for="consult">
				<legend class="wa-legend"><?php esc_html_e( 'Project details', 'vtech-av' ); ?></legend>
				<div class="wa-field wa-field--full"><label for="wa-service"><?php esc_html_e( 'What do you need? *', 'vtech-av' ); ?></label><select id="wa-service" name="service" data-required><?php echo $opts( 'vtech_wa_services' ); // phpcs:ignore ?></select></div>
				<div class="wa-field"><label for="wa-location"><?php esc_html_e( 'Location / county *', 'vtech-av' ); ?></label><input id="wa-location" name="location" type="text" placeholder="<?php esc_attr_e( 'e.g. Westlands, Nairobi', 'vtech-av' ); ?>" data-required></div>
				<div class="wa-field"><label for="wa-visit"><?php esc_html_e( 'Preferred date', 'vtech-av' ); ?></label><input id="wa-visit" name="preferred_date" type="date"></div>
				<div class="wa-field wa-field--full"><span class="wa-label"><?php esc_html_e( 'How should we follow up?', 'vtech-av' ); ?></span>
					<div class="wa-chips">
						<label class="wa-chip"><input type="radio" name="follow_up" value="WhatsApp chat" checked><span>WhatsApp chat</span></label>
						<label class="wa-chip"><input type="radio" name="follow_up" value="Phone call"><span>Phone call</span></label>
						<label class="wa-chip"><input type="radio" name="follow_up" value="Site visit"><span>Site visit</span></label>
						<label class="wa-chip"><input type="radio" name="follow_up" value="Video call"><span>Video call</span></label>
					</div>
				</div>
			</fieldset>

			<fieldset class="wa-grid" data-wa-for="enquiry">
				<legend class="wa-legend"><?php esc_html_e( 'Your enquiry', 'vtech-av' ); ?></legend>
				<div class="wa-field wa-field--full"><label for="wa-topic"><?php esc_html_e( 'Topic', 'vtech-av' ); ?></label><select id="wa-topic" name="topic"><?php echo $opts( 'vtech_wa_topics' ); // phpcs:ignore ?></select></div>
			</fieldset>

			<div class="wa-field wa-field--full"><label for="wa-notes" data-wa-notes-label><?php esc_html_e( 'Anything else we should know?', 'vtech-av' ); ?></label><textarea id="wa-notes" name="notes" rows="3"></textarea></div>

			<p class="wa-modal__error" data-wa-error role="alert" hidden></p>
			<button type="submit" class="btn btn--wa btn--lg btn--block wa-modal__submit" data-wa-submit><?php echo esc_html( vtech_wa_opt( 'vtech_wa_button' ) ); ?></button>
			<p class="wa-modal__fine"><?php esc_html_e( 'Your details are only used to respond to this request. No spam.', 'vtech-av' ); ?></p>
		</form>

		<div class="wa-modal__done" data-wa-done hidden>
			<span class="wa-modal__done-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M9 16.2 4.8 12l-1.4 1.4L9 19 21 7l-1.4-1.4z"/></svg></span>
			<h3><?php esc_html_e( 'Your request is ready', 'vtech-av' ); ?></h3>
			<p data-wa-done-ref></p>
			<p class="wa-modal__done-note"><?php esc_html_e( 'WhatsApp should open with your message filled in. Just press send. If it did not open, use the button below.', 'vtech-av' ); ?></p>
			<a class="btn btn--wa btn--lg btn--block" data-wa-open href="#" target="_blank" rel="noopener"><?php esc_html_e( 'Open WhatsApp and send', 'vtech-av' ); ?></a>
			<button type="button" class="wa-modal__copy" data-wa-copy><?php esc_html_e( 'Copy message instead', 'vtech-av' ); ?></button>
		</div>
	</div>
</div>
	<?php
}

/* -------------------------------------------------------------------------
 * Lead logging endpoint (keeps a record + emails the office)
 * ---------------------------------------------------------------------- */
add_action( 'rest_api_init', function () {
	register_rest_route( 'vtech/v1', '/wa-lead', array(
		'methods'             => 'POST',
		'permission_callback' => '__return_true',
		'callback'            => 'vtech_wa_lead',
	) );
} );

function vtech_wa_lead( $request ) {
	$p = $request->get_json_params();
	if ( ! is_array( $p ) ) { $p = $request->get_params(); }
	if ( ! empty( $p['website'] ) ) { return new WP_REST_Response( array( 'ok' => true, 'ref' => '' ), 200 ); }

	// Light rate limit: 8 requests per IP per 10 minutes.
	$ip  = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : 'x';
	$key = 'vtech_wa_rl_' . md5( $ip );
	$n   = (int) get_transient( $key );
	if ( $n >= 8 ) { return new WP_REST_Response( array( 'ok' => false, 'ref' => '' ), 200 ); }
	set_transient( $key, $n + 1, 10 * MINUTE_IN_SECONDS );

	$kind = isset( $p['kind'] ) ? sanitize_key( $p['kind'] ) : 'enquiry';
	$keep = array( 'name', 'phone', 'email', 'organisation', 'item', 'price', 'event_date', 'days', 'venue', 'event_type', 'guests', 'quantity', 'technician', 'service', 'location', 'preferred_date', 'follow_up', 'topic', 'notes', 'page' );
	$_POST = array( 'channel' => 'WhatsApp popup', 'request_type' => ucfirst( $kind ) ); // phpcs:ignore
	foreach ( $keep as $k ) {
		if ( isset( $p[ $k ] ) && '' !== trim( (string) $p[ $k ] ) ) { $_POST[ $k ] = mb_substr( (string) $p[ $k ], 0, 2000 ); }
	}
	if ( empty( $_POST['name'] ) || empty( $_POST['phone'] ) ) {
		return new WP_REST_Response( array( 'ok' => false, 'ref' => '' ), 200 );
	}
	try {
		if ( in_array( $kind, array( 'package', 'item' ), true ) ) {
			$res = vtech_process_submission( 'hire_request', 'HIRE', 'WhatsApp Hire Order' );
		} else {
			$res = vtech_process_submission( 'consultation', 'consult' === $kind ? 'CONSULT' : 'QUOTE', 'consult' === $kind ? 'WhatsApp Consultation Request' : 'WhatsApp Enquiry' );
		}
		return new WP_REST_Response( array( 'ok' => true, 'ref' => isset( $res['ref'] ) ? $res['ref'] : '' ), 200 );
	} catch ( \Throwable $e ) {
		return new WP_REST_Response( array( 'ok' => true, 'ref' => '' ), 200 );
	}
}
