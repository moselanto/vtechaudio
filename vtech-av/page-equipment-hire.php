<?php
/**
 * Template Name: Equipment Hire Guide
 * Equipment hire landing page — redesigned v5.54.0.
 * Hero with dual CTA, what-you-can-hire tiles, packages teaser, how it works,
 * why VTECH, three hire guides, FAQ (with FAQPage schema) and final CTA.
 *
 * @package VTECH_AV
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();
$img    = VTECH_URI . '/assets/img/';
$vtc_wa = function_exists( 'vtech_wa_attrs' ) && function_exists( 'vtech_contact_wa_url' );
$pk_url = get_post_type_archive_link( 'hire_package' ) ?: home_url( '/hire-packages/' );
$rq_url = home_url( '/equipment-hire-request/' );

$categories = array(
	array( 'equip-line-array.webp', __( 'Sound & PA systems', 'vtech-av' ), __( 'Line arrays, powered tops, subwoofers, mixers, wireless mics and stage monitors for events of any size.', 'vtech-av' ) ),
	array( 'equip-led-panel.webp', __( 'LED screens & video', 'vtech-av' ), __( 'Indoor and outdoor LED walls, projectors, screens, video switchers and confidence monitors.', 'vtech-av' ) ),
	array( 'equip-moving-head.webp', __( 'Stage & lighting', 'vtech-av' ), __( 'Stage decks, trussing, moving heads, wash and par lighting, hazers and DMX control.', 'vtech-av' ) ),
	array( 'blog-live-streaming.webp', __( 'Live streaming', 'vtech-av' ), __( 'Cameras, switchers and encoders to stream to Facebook, YouTube, Zoom or Teams.', 'vtech-av' ) ),
	array( 'equip-wireless-mic.webp', __( 'Conferencing', 'vtech-av' ), __( 'Delegate microphone systems, interpretation, projectors and displays for AGMs and summits.', 'vtech-av' ) ),
	array( 'article-event-setup.webp', __( 'Power & crew', 'vtech-av' ), __( 'Silent generators, cabling, distribution and trained technicians on site throughout your event.', 'vtech-av' ) ),
);

$guides = array(
	array( 'article-event-setup.webp', __( 'How to choose the right sound system for your event', 'vtech-av' ), array(
		__( 'The biggest factor in event audio is matching the system to the space and the audience. A garden wedding for 150 guests needs something very different from a 2,000-seat crusade or a corporate AGM in a hotel ballroom. Start with three questions: how many guests, indoor or outdoor, and is it speech, music or both?', 'vtech-av' ),
		__( 'For speech, clarity and even coverage matter more than raw power: a well-placed pair of full-range tops with delay speakers beats a bigger, badly positioned rig. For music and worship, add subwoofers and stage monitors. Outdoor events usually need more power and often a generator.', 'vtech-av' ),
		__( 'Never hire on speaker count alone. Tell us the venue, guest numbers and programme and we will size the system properly, with a technician on site so it sounds right from the first minute.', 'vtech-av' ),
	) ),
	array( 'blog-led-projector.webp', __( 'LED screens vs projectors: what to hire for visibility', 'vtech-av' ), array(
		__( 'Projectors are cost-effective indoors in low ambient light, such as conference rooms and dim halls. In bright halls, tents or daytime outdoor events a projected image washes out. LED screens stay readable in direct sunlight, come in modular sizes and look far more premium on stages, launches and concerts.', 'vtech-av' ),
		__( 'For live events add a confidence monitor for presenters and a video switcher when mixing cameras, slides and lyrics. The same feed can stream to Facebook, YouTube or Zoom.', 'vtech-av' ),
		__( 'The right choice depends on light levels, viewing distance and budget. We recommend the most cost-effective option that still looks world-class.', 'vtech-av' ),
	) ),
	array( 'article-boardroom.webp', __( 'A practical checklist before your event day', 'vtech-av' ), array(
		__( 'Confirm your equipment list and delivery, setup and collection times at least a week ahead. Check power: is mains available, how far is it from the stage, and do you need a backup generator? Walk the venue for vehicle access, stairs, lifts and floor level.', 'vtech-av' ),
		__( 'Decide early whether you need technicians on site; a sound engineer, lighting technician or camera operator makes a visible difference. Agree who is responsible for overnight security if equipment stays on site.', 'vtech-av' ),
		__( 'Our hire request form captures all of this in one place, and we reply with a same-day itemised quotation.', 'vtech-av' ),
	) ),
);

$faqs = array(
	array( __( 'How far in advance should I book?', 'vtech-av' ), __( 'For weekends and peak seasons we recommend booking at least one to two weeks ahead. For large events, earlier is better so we can reserve the exact equipment and crew.', 'vtech-av' ) ),
	array( __( 'Do you deliver outside Nairobi?', 'vtech-av' ), __( 'Yes. We deliver, set up and support events across Kenya. Transport is quoted based on distance and shown clearly in your quotation.', 'vtech-av' ) ),
	array( __( 'Are technicians included?', 'vtech-av' ), __( 'Technicians are strongly recommended for anything beyond a simple PA and can be added to any hire. Our hire packages include an on-site technician.', 'vtech-av' ) ),
	array( __( 'Is a deposit or ID required?', 'vtech-av' ), __( 'A security deposit and valid identification may be required. Hired equipment remains the property of VTECH and is your responsibility from delivery to collection.', 'vtech-av' ) ),
	array( __( 'What if something fails during my event?', 'vtech-av' ), __( 'We build in redundancy and our on-site technicians carry spares. If an issue arises, we resolve it fast so your event keeps running.', 'vtech-av' ) ),
);
?>
<section class="eh-hero">
	<div class="container">
		<?php if ( function_exists( 'vtech_breadcrumbs' ) ) { vtech_breadcrumbs(); } ?>
		<div class="ab-hero__grid">
			<div>
				<p class="svx-eyebrow"><?php esc_html_e( 'Event equipment hire · Kenya', 'vtech-av' ); ?></p>
				<h1 class="ab-hero__title"><?php esc_html_e( 'Sound, LED, lighting and stage hire in Kenya', 'vtech-av' ); ?></h1>
				<p class="ab-hero__lead"><?php esc_html_e( 'Professional event equipment delivered, set up and run by trained technicians, anywhere in Kenya. Pick a ready-made package or tell us about your event for a same-day quote.', 'vtech-av' ); ?></p>
				<div class="svx-hero__cta">
					<a class="btn btn--accent btn--lg" href="<?php echo esc_url( $pk_url ); ?>"><?php esc_html_e( 'See hire packages', 'vtech-av' ); ?></a>
					<a class="btn btn--outline-light btn--lg" href="<?php echo esc_url( $rq_url ); ?>"><?php esc_html_e( 'Get a custom quote', 'vtech-av' ); ?></a>
				</div>
				<ul class="svx-proof">
					<li><strong><?php esc_html_e( 'Same day', 'vtech-av' ); ?></strong> <?php esc_html_e( 'quotes', 'vtech-av' ); ?></li>
					<li><strong><?php esc_html_e( 'Crew', 'vtech-av' ); ?></strong> <?php esc_html_e( 'on site', 'vtech-av' ); ?></li>
					<li><strong>KES</strong> <?php esc_html_e( 'itemised pricing', 'vtech-av' ); ?></li>
				</ul>
			</div>
			<div class="ab-hero__media" aria-hidden="true">
				<img class="ab-hero__img ab-hero__img--a" src="<?php echo esc_url( $img . 'services/sound-systems.webp' ); ?>" alt="" width="600" height="450" fetchpriority="high" decoding="async">
				<img class="ab-hero__img ab-hero__img--b" src="<?php echo esc_url( $img . 'equip-led-panel.webp' ); ?>" alt="" width="600" height="450" decoding="async">
			</div>
		</div>
	</div>
</section>

<section class="section eh-cats">
	<div class="container">
		<div class="ab-head">
			<p class="ab-kicker"><?php esc_html_e( 'What you can hire', 'vtech-av' ); ?></p>
			<h2 class="svx-head__title"><?php esc_html_e( 'Event-grade equipment, individually or as packages', 'vtech-av' ); ?></h2>
		</div>
		<div class="eh-cats__grid">
			<?php foreach ( $categories as $c ) : ?>
				<article class="eh-cat">
					<figure class="eh-cat__media"><img src="<?php echo esc_url( $img . $c[0] ); ?>" alt="<?php echo esc_attr( $c[1] . ' hire in Nairobi, Kenya' ); ?>" width="600" height="400" loading="lazy" decoding="async"></figure>
					<div class="eh-cat__body">
						<h3><?php echo esc_html( $c[1] ); ?></h3>
						<p><?php echo esc_html( $c[2] ); ?></p>
						<?php if ( $vtc_wa ) : ?>
							<a class="svx-link" href="<?php echo esc_url( vtech_contact_wa_url() ); ?>"<?php echo vtech_wa_attrs( 'enquiry', $c[1] . ' hire' ); // phpcs:ignore ?>><?php esc_html_e( 'Ask about availability', 'vtech-av' ); ?> <span aria-hidden="true">&rarr;</span></a>
						<?php endif; ?>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<section class="section eh-band">
	<div class="container">
		<div class="eh-band__box">
			<div>
				<p class="ab-kicker eh-band__kicker"><?php esc_html_e( 'Ready-made packages', 'vtech-av' ); ?></p>
				<h2><?php esc_html_e( 'From small meetings to 1,000-guest events, priced in KES', 'vtech-av' ); ?></h2>
				<p><?php esc_html_e( 'Bronze, Silver, Gold and Platinum packages with delivery, setup and an on-site technician. Book as-is or customise.', 'vtech-av' ); ?></p>
			</div>
			<a class="btn btn--accent btn--lg" href="<?php echo esc_url( $pk_url ); ?>"><?php esc_html_e( 'Compare packages', 'vtech-av' ); ?></a>
		</div>
	</div>
</section>

<section class="section section--surface svx-process">
	<div class="container">
		<h2 class="svx-head__title svx-center"><?php esc_html_e( 'How equipment hire works', 'vtech-av' ); ?></h2>
		<ol class="svx-steps">
			<li><span>1</span><h3><?php esc_html_e( 'Tell us about your event', 'vtech-av' ); ?></h3><p><?php esc_html_e( 'Dates, venue, guest numbers and programme, by form, call or WhatsApp.', 'vtech-av' ); ?></p></li>
			<li><span>2</span><h3><?php esc_html_e( 'Get a same-day quote', 'vtech-av' ); ?></h3><p><?php esc_html_e( 'We size the system and send an itemised quote including delivery, setup and crew.', 'vtech-av' ); ?></p></li>
			<li><span>3</span><h3><?php esc_html_e( 'We deliver & set up', 'vtech-av' ); ?></h3><p><?php esc_html_e( 'Installed and tested before your event begins, with the crew on standby.', 'vtech-av' ); ?></p></li>
			<li><span>4</span><h3><?php esc_html_e( 'We pack down & collect', 'vtech-av' ); ?></h3><p><?php esc_html_e( 'After the event we dismantle and collect, so you never chase logistics.', 'vtech-av' ); ?></p></li>
		</ol>
	</div>
</section>

<section class="section">
	<div class="container">
		<div class="ab-head">
			<p class="ab-kicker"><?php esc_html_e( 'Why hire from VTECH', 'vtech-av' ); ?></p>
			<h2 class="svx-head__title"><?php esc_html_e( 'Event AV without the stress', 'vtech-av' ); ?></h2>
		</div>
		<div class="ab-values__grid">
			<article class="ab-value"><span class="ab-value__n">01</span><h3><?php esc_html_e( 'Well-maintained gear', 'vtech-av' ); ?></h3><p><?php esc_html_e( 'Professional brands, tested before every hire so it performs when it matters.', 'vtech-av' ); ?></p></article>
			<article class="ab-value"><span class="ab-value__n">02</span><h3><?php esc_html_e( 'End-to-end logistics', 'vtech-av' ); ?></h3><p><?php esc_html_e( 'Delivery, setup and collection handled, so you can focus on your guests.', 'vtech-av' ); ?></p></article>
			<article class="ab-value"><span class="ab-value__n">03</span><h3><?php esc_html_e( 'Trained technicians', 'vtech-av' ); ?></h3><p><?php esc_html_e( 'Sound, lighting and video crew who keep everything running.', 'vtech-av' ); ?></p></article>
			<article class="ab-value"><span class="ab-value__n">04</span><h3><?php esc_html_e( 'Transparent pricing', 'vtech-av' ); ?></h3><p><?php esc_html_e( 'Itemised quotes in Kenya Shillings, with no hidden extras.', 'vtech-av' ); ?></p></article>
		</div>
	</div>
</section>

<section class="section section--surface eh-guides">
	<div class="container">
		<div class="ab-head">
			<p class="ab-kicker"><?php esc_html_e( 'Hire guides', 'vtech-av' ); ?></p>
			<h2 class="svx-head__title"><?php esc_html_e( 'Plan your event like a pro', 'vtech-av' ); ?></h2>
		</div>
		<?php foreach ( $guides as $i => $g ) : ?>
			<article class="eh-guide<?php echo $i % 2 ? ' eh-guide--flip' : ''; ?>">
				<figure class="eh-guide__media"><img src="<?php echo esc_url( $img . $g[0] ); ?>" alt="<?php echo esc_attr( $g[1] ); ?>" width="720" height="480" loading="lazy" decoding="async"></figure>
				<div class="eh-guide__copy">
					<span class="eh-guide__n"><?php echo esc_html( sprintf( __( 'Guide %d', 'vtech-av' ), $i + 1 ) ); ?></span>
					<h3><?php echo esc_html( $g[1] ); ?></h3>
					<?php foreach ( $g[2] as $para ) { echo '<p>' . esc_html( $para ) . '</p>'; } ?>
				</div>
			</article>
		<?php endforeach; ?>
	</div>
</section>

<section class="section svx-faq">
	<div class="container narrow">
		<h2 class="svx-head__title svx-center"><?php esc_html_e( 'Equipment hire FAQs', 'vtech-av' ); ?></h2>
		<?php foreach ( $faqs as $i => $q ) : ?>
			<details class="svx-q"<?php echo 0 === $i ? ' open' : ''; ?>><summary><?php echo esc_html( $q[0] ); ?></summary><p><?php echo esc_html( $q[1] ); ?></p></details>
		<?php endforeach; ?>
	</div>
</section>

<section class="section svx-final">
	<div class="container">
		<div class="svx-final__box">
			<div class="svx-final__copy">
				<h2><?php esc_html_e( 'Ready to hire equipment for your event?', 'vtech-av' ); ?></h2>
				<p><?php esc_html_e( 'Send your date, venue and guest numbers and get a same-day quotation with delivery, setup and technician.', 'vtech-av' ); ?></p>
			</div>
			<div class="svx-final__actions">
				<a class="btn btn--accent btn--lg" href="<?php echo esc_url( $rq_url ); ?>"><?php esc_html_e( 'Start a hire request', 'vtech-av' ); ?></a>
				<?php if ( function_exists( 'vtech_contact_tel' ) ) : ?>
					<a class="svx-final__alt" href="tel:<?php echo esc_attr( vtech_contact_tel() ); ?>"><?php esc_html_e( 'Call', 'vtech-av' ); ?> <?php echo esc_html( vtech_contact( 'phone' ) ); ?></a>
				<?php endif; ?>
				<?php if ( $vtc_wa ) : ?>
					<a class="svx-final__alt" href="<?php echo esc_url( vtech_contact_wa_url() ); ?>"<?php echo vtech_wa_attrs( 'enquiry' ); // phpcs:ignore ?>><?php esc_html_e( 'Chat on WhatsApp', 'vtech-av' ); ?></a>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>

<?php
// FAQPage schema for the hire FAQs shown above (visible content only).
$ld = array( '@context' => 'https://schema.org', '@type' => 'FAQPage', '@id' => get_permalink() . '#faq', 'mainEntity' => array() );
foreach ( $faqs as $f ) {
	$ld['mainEntity'][] = array( '@type' => 'Question', 'name' => $f[0], 'acceptedAnswer' => array( '@type' => 'Answer', 'text' => $f[1] ) );
}
echo "\n<script type=\"application/ld+json\">" . wp_json_encode( $ld, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . "</script>\n"; // phpcs:ignore

get_footer();
