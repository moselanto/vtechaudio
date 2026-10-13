<?php
/**
 * Template Name: FAQ
 * FAQ page, redesigned v5.54.0: searchable, grouped by topic, with a help
 * sidebar and FAQPage schema built from the same visible questions.
 *
 * @package VTECH_AV
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();
$vtc_wa = function_exists( 'vtech_wa_attrs' ) && function_exists( 'vtech_contact_wa_url' );

$faqs = array(
	array( 'How much does a professional sound system cost in Kenya?', 'Costs depend on venue size, coverage and equipment tier. A small meeting-room PA can start from around KES 120,000, while a full church or auditorium line-array system runs into the millions. After a free site survey we provide a fixed, itemised quote within 24 hours.' ),
	array( 'Do you cover the whole of Kenya?', 'Yes. We design, install and support AV systems across all 47 counties in Kenya, and we take on select projects across East Africa (Uganda, Tanzania, Rwanda and beyond).' ),
	array( 'How fast can you quote my AV project?', 'We provide a fixed written quotation within 24 hours of a free site survey in Nairobi, and within 48 hours upcountry.' ),
	array( 'Do you offer equipment hire as well as installation?', 'Yes. We hire out sound, lighting, LED screens and conferencing equipment for events, with delivery, setup and an on-site technician. You can also request a ready-made hire package.' ),
	array( 'What brands of equipment do you work with?', 'We specify reliable, warrantied equipment from leading global manufacturers including Shure, Sennheiser, Yamaha, JBL, QSC, Allen & Heath, Bosch and Blackmagic Design — matched to your budget and application.' ),
	array( 'Can you set up live streaming for our church or event?', 'Absolutely. We build live-streaming systems for Facebook, YouTube, Zoom and Microsoft Teams, including cameras, switching (Blackmagic, Roland, vMix), audio embedding and reliable internet bonding.' ),
	array( 'Do you handle church sound systems specifically?', 'Yes — church audio is one of our specialities. We tune systems for clear speech and music, manage choir and instrument mics, and add streaming, translation and hearing-assistance where needed.' ),
	array( 'What is included in a conference-room / boardroom AV solution?', 'Typically video conferencing (Zoom/Teams/Google Meet/Webex), ceiling or table microphones, an auto-tracking camera, displays or a projector, wireless presentation and one-touch room control.' ),
	array( 'Do you provide acoustic treatment and soundproofing?', 'Yes. We diagnose echo, reverberation, noise and sound leakage, then design acoustic panels, bass traps, diffusers and isolation appropriate to your room and construction.' ),
	array( 'Do you install LED screens and video walls?', 'We supply and install indoor and outdoor LED screens and video walls for churches, events, retail, corporate and advertising, including content and control.' ),
	array( 'Do you offer maintenance after installation?', 'Every installation includes a 12-month support window covering workmanship, and we offer Annual Maintenance Contracts (AMC) with priority and emergency support.' ),
	array( 'Can you work with our existing equipment?', 'Where possible we assess and integrate compatible existing systems to protect your investment, upgrading only what is necessary.' ),
	array( 'What payment terms do you offer?', 'Projects are typically confirmed with a deposit, with the balance per the quotation. We accept M-Pesa and bank transfer, and can structure phased payments for larger installations.' ),
	array( 'Do you handle government tenders and NGO procurement?', 'Yes. We are experienced with tender documentation, compliance and phased delivery for government, NGO, hospital, school and corporate procurement.' ),
	array( 'How do I get started?', 'Book a free consultation. Tell us about your project or event, we survey the site where needed, and you receive a tailored quotation and technical recommendation — usually within 24 hours.' ),
);

// Topic for each question (by position), used for the group headings and filter chips.
$vtc_topics = array(
	'pricing'  => array( __( 'Pricing & quotes', 'vtech-av' ), array( 0, 2, 12, 14 ) ),
	'services' => array( __( 'Systems & services', 'vtech-av' ), array( 4, 5, 6, 7, 8, 9, 11 ) ),
	'hire'     => array( __( 'Coverage & hire', 'vtech-av' ), array( 1, 3, 13 ) ),
	'support'  => array( __( 'Support', 'vtech-av' ), array( 10 ) ),
);
?>
<section class="faq-hero">
	<div class="container">
		<?php if ( function_exists( 'vtech_breadcrumbs' ) ) { vtech_breadcrumbs(); } ?>
		<p class="svx-eyebrow"><?php esc_html_e( 'Help centre', 'vtech-av' ); ?></p>
		<h1 class="hp-hero__title"><?php esc_html_e( 'Frequently asked questions', 'vtech-av' ); ?></h1>
		<p class="hp-hero__lead"><?php esc_html_e( 'Straight answers about AV systems, installation, hire and support across Kenya and East Africa.', 'vtech-av' ); ?></p>
		<div class="faq-search">
			<label class="screen-reader-text" for="faq-q"><?php esc_html_e( 'Search questions', 'vtech-av' ); ?></label>
			<input id="faq-q" type="search" placeholder="<?php esc_attr_e( 'Search a question, e.g. live streaming, cost, LED', 'vtech-av' ); ?>" autocomplete="off" data-faq-search>
		</div>
		<div class="faq-chips" role="group" aria-label="<?php esc_attr_e( 'Filter by topic', 'vtech-av' ); ?>">
			<button type="button" class="hp-picker__btn is-active" data-faq-topic="all"><?php esc_html_e( 'All', 'vtech-av' ); ?></button>
			<?php foreach ( $vtc_topics as $k => $tp ) : ?>
				<button type="button" class="hp-picker__btn" data-faq-topic="<?php echo esc_attr( $k ); ?>"><?php echo esc_html( $tp[0] ); ?></button>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<section class="section faq-body">
	<div class="container faq-layout">
		<div class="faq-main">
			<?php foreach ( $vtc_topics as $k => $tp ) : ?>
				<div class="faq-group" data-faq-group="<?php echo esc_attr( $k ); ?>">
					<h2 class="faq-group__title"><?php echo esc_html( $tp[0] ); ?></h2>
					<?php foreach ( $tp[1] as $n ) : if ( empty( $faqs[ $n ] ) ) { continue; } $f = $faqs[ $n ]; ?>
						<details class="svx-q" data-faq-item><summary><?php echo esc_html( $f[0] ); ?></summary><p><?php echo esc_html( $f[1] ); ?></p></details>
					<?php endforeach; ?>
				</div>
			<?php endforeach; ?>
			<p class="svx-empty" data-faq-empty hidden><?php esc_html_e( 'No matching questions. Ask us directly and we will reply quickly.', 'vtech-av' ); ?></p>
		</div>
		<aside class="faq-side">
			<div class="faq-card">
				<h2><?php esc_html_e( 'Still have a question?', 'vtech-av' ); ?></h2>
				<p><?php esc_html_e( 'Talk to an engineer. We usually reply within working hours the same day.', 'vtech-av' ); ?></p>
				<a class="btn btn--accent" href="<?php echo esc_url( home_url( '/book-a-consultation/' ) ); ?>"><?php esc_html_e( 'Book a free consultation', 'vtech-av' ); ?></a>
				<?php if ( $vtc_wa ) : ?><a class="svx-final__alt faq-card__alt" href="<?php echo esc_url( vtech_contact_wa_url() ); ?>"<?php echo vtech_wa_attrs( 'enquiry' ); // phpcs:ignore ?>><?php esc_html_e( 'Ask on WhatsApp', 'vtech-av' ); ?></a><?php endif; ?>
				<?php if ( function_exists( 'vtech_contact_tel' ) ) : ?><a class="svx-final__alt faq-card__alt" href="tel:<?php echo esc_attr( vtech_contact_tel() ); ?>"><?php esc_html_e( 'Call', 'vtech-av' ); ?> <?php echo esc_html( vtech_contact( 'phone' ) ); ?></a><?php endif; ?>
			</div>
			<nav class="faq-links" aria-label="<?php esc_attr_e( 'Popular pages', 'vtech-av' ); ?>">
				<a href="<?php echo esc_url( home_url( '/services/' ) ); ?>"><?php esc_html_e( 'Our services', 'vtech-av' ); ?> &rarr;</a>
				<a href="<?php echo esc_url( home_url( '/hire-packages/' ) ); ?>"><?php esc_html_e( 'Hire packages & prices', 'vtech-av' ); ?> &rarr;</a>
				<a href="<?php echo esc_url( home_url( '/projects/' ) ); ?>"><?php esc_html_e( 'Recent projects', 'vtech-av' ); ?> &rarr;</a>
			</nav>
		</aside>
	</div>
</section>

<script>
(function(){
  var q=document.querySelector('[data-faq-search]'),chips=document.querySelectorAll('[data-faq-topic]'),groups=document.querySelectorAll('[data-faq-group]'),empty=document.querySelector('[data-faq-empty]'),topic='all';
  function run(){
    var term=(q&&q.value||'').toLowerCase().trim(),shown=0;
    groups.forEach(function(g){
      var gOk=topic==='all'||g.getAttribute('data-faq-group')===topic,n=0;
      g.querySelectorAll('[data-faq-item]').forEach(function(it){
        var ok=gOk&&(term===''||it.textContent.toLowerCase().indexOf(term)>-1);
        it.hidden=!ok; if(ok){n++; if(term){it.open=true;}}
      });
      g.hidden=n===0; shown+=n;
    });
    if(empty)empty.hidden=shown>0;
  }
  if(q)q.addEventListener('input',run);
  chips.forEach(function(c){c.addEventListener('click',function(){topic=c.getAttribute('data-faq-topic');chips.forEach(function(x){x.classList.toggle('is-active',x===c);});run();});});
})();
</script>

<?php
// FAQPage schema for rich results (same visible questions).
$ld = array( '@context' => 'https://schema.org', '@type' => 'FAQPage', '@id' => get_permalink() . '#faq', 'mainEntity' => array() );
foreach ( $faqs as $f ) {
	$ld['mainEntity'][] = array( '@type' => 'Question', 'name' => $f[0], 'acceptedAnswer' => array( '@type' => 'Answer', 'text' => $f[1] ) );
}
echo "\n<script type=\"application/ld+json\">" . wp_json_encode( $ld, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . "</script>\n"; // phpcs:ignore

get_footer();
