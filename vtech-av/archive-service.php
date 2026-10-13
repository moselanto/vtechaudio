<?php
/**
 * Services archive (/services/) — redesigned v5.50.0.
 * Split hero with photo collage, category filter chips, photo cards with
 * per-service imagery, process strip, trust points and CTA band.
 *
 * @package VTECH_AV
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();

$img = VTECH_URI . '/assets/img/';

/** Per-service fallback photo, category and tags (used when no featured image is set). */
$vtc_meta = array(
	'sound-systems'              => array( 'services/sound-systems.webp', 'audio', array( 'Line arrays', 'PA', 'Mixers' ) ),
	'church-sound-systems'       => array( 'services/church-sound-systems.webp', 'audio', array( 'Worship', 'Live stream', 'Wireless mics' ) ),
	'background-music-systems'   => array( 'services/background-music-systems.webp', 'audio', array( 'Zoned music', 'Paging', 'Hotels & retail' ) ),
	'led-screens'                => array( 'led-screens.webp', 'video', array( 'Indoor & outdoor', 'Video walls', 'Events' ) ),
	'digital-signage'            => array( 'services/digital-signage.webp', 'video', array( 'Displays', 'Content playback', 'Retail' ) ),
	'conference-systems'         => array( 'conference-systems.webp', 'meetings', array( 'Boardrooms', 'Delegate mics', 'One-touch' ) ),
	'video-conferencing-systems' => array( 'services/video-conferencing-systems.webp', 'meetings', array( 'Teams', 'Zoom', 'Hybrid rooms' ) ),
	'lighting'                   => array( 'stage-lighting.webp', 'stage', array( 'Stage', 'Architectural', 'DMX control' ) ),
	'acoustic-solutions'         => array( 'acoustic-solutions.webp', 'stage', array( 'Echo control', 'Panels', 'Soundproofing' ) ),
	'consultation'               => array( 'services/consultation.webp', 'support', array( 'Free site survey', 'Design', '24h quote' ) ),
	'av-maintenance-support'     => array( 'services/av-maintenance-support.webp', 'support', array( 'Repairs', 'Servicing', 'Contracts' ) ),
);
$vtc_cats = array(
	'all'      => __( 'All services', 'vtech-av' ),
	'audio'    => __( 'Sound & audio', 'vtech-av' ),
	'video'    => __( 'LED & displays', 'vtech-av' ),
	'meetings' => __( 'Conference & meetings', 'vtech-av' ),
	'stage'    => __( 'Lighting & acoustics', 'vtech-av' ),
	'support'  => __( 'Design & support', 'vtech-av' ),
);
$vtc_wa = function_exists( 'vtech_wa_attrs' );
?>
<section class="svx-hero">
	<div class="container svx-hero__grid">
		<div class="svx-hero__copy">
			<?php if ( function_exists( 'vtech_breadcrumbs' ) ) { vtech_breadcrumbs(); } ?>
			<p class="svx-eyebrow"><?php esc_html_e( 'Design · Supply · Install · Support', 'vtech-av' ); ?></p>
			<h1 class="svx-hero__title"><?php esc_html_e( 'Audio Visual Services in Kenya', 'vtech-av' ); ?></h1>
			<p class="svx-hero__lead"><?php esc_html_e( 'Sound and PA systems, LED screens, conference and boardroom AV, stage lighting and acoustics, designed around your room and supported by one team in Nairobi, across all 47 counties.', 'vtech-av' ); ?></p>
			<div class="svx-hero__cta">
				<a class="btn btn--accent btn--lg" href="<?php echo esc_url( home_url( '/book-a-consultation/' ) ); ?>"><?php esc_html_e( 'Get a free site survey', 'vtech-av' ); ?></a>
				<?php if ( $vtc_wa && function_exists( 'vtech_contact_wa_url' ) ) : ?>
					<a class="btn btn--outline-light btn--lg" href="<?php echo esc_url( vtech_contact_wa_url() ); ?>"<?php echo vtech_wa_attrs( 'consult' ); // phpcs:ignore ?>><?php esc_html_e( 'Ask on WhatsApp', 'vtech-av' ); ?></a>
				<?php endif; ?>
			</div>
			<ul class="svx-proof">
				<li><strong>24h</strong> <?php esc_html_e( 'fixed quote', 'vtech-av' ); ?></li>
				<li><strong>47</strong> <?php esc_html_e( 'counties served', 'vtech-av' ); ?></li>
				<li><strong>12mo</strong> <?php esc_html_e( 'support', 'vtech-av' ); ?></li>
			</ul>
		</div>
		<div class="svx-hero__collage" aria-hidden="true">
			<img class="svx-c svx-c--a" src="<?php echo esc_url( $img . 'services/church-sound-systems.webp' ); ?>" alt="" width="600" height="450" fetchpriority="high" decoding="async">
			<img class="svx-c svx-c--b" src="<?php echo esc_url( $img . 'services/video-conferencing-systems.webp' ); ?>" alt="" width="600" height="450" decoding="async">
			<img class="svx-c svx-c--c" src="<?php echo esc_url( $img . 'services/sound-systems.webp' ); ?>" alt="" width="600" height="450" decoding="async">
		</div>
	</div>
</section>


<section class="svx-trust" aria-label="<?php esc_attr_e( 'Brands and coverage', 'vtech-av' ); ?>">
	<div class="container svx-trust__inner">
		<p class="svx-trust__label"><?php esc_html_e( 'Genuine pro audio and video brands, installed to manufacturer standards', 'vtech-av' ); ?></p>
		<ul class="svx-trust__list">
			<li>dB Technologies</li><li>Shure</li><li><?php esc_html_e( 'Digital mixing', 'vtech-av' ); ?></li><li><?php esc_html_e( 'LED video walls', 'vtech-av' ); ?></li><li><?php esc_html_e( 'Teams &amp; Zoom rooms', 'vtech-av' ); ?></li>
		</ul>
	</div>
</section>

<section class="section svx-list" id="services-list">
	<div class="container">
		<div class="svx-head">
			<h2 class="svx-head__title"><?php esc_html_e( 'What we do', 'vtech-av' ); ?></h2>
			<div class="svx-chips" role="tablist" aria-label="<?php esc_attr_e( 'Filter services', 'vtech-av' ); ?>">
				<?php foreach ( $vtc_cats as $k => $label ) : ?>
					<button type="button" class="svx-chip<?php echo 'all' === $k ? ' is-active' : ''; ?>" data-svx-filter="<?php echo esc_attr( $k ); ?>" aria-pressed="<?php echo 'all' === $k ? 'true' : 'false'; ?>"><?php echo esc_html( $label ); ?></button>
				<?php endforeach; ?>
			</div>
		</div>

		<?php if ( have_posts() ) : $vtc_i = 0; ?>
		<div class="svx-grid">
			<?php while ( have_posts() ) : the_post(); $vtc_i++;
				$slug  = get_post_field( 'post_name' );
				$m     = isset( $vtc_meta[ $slug ] ) ? $vtc_meta[ $slug ] : array( 'og-default.webp', 'support', array() );
				$alt   = get_the_title() . ' in Kenya by VTECH';
				$ex    = has_excerpt() ? get_the_excerpt() : wp_trim_words( wp_strip_all_tags( get_the_content() ), 24 );
			?>
				<article class="svx-card" data-svx-cat="<?php echo esc_attr( $m[1] ); ?>">
					<a class="svx-card__media" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
						<?php
						if ( has_post_thumbnail() ) {
							the_post_thumbnail( 'vtech-card', array( 'loading' => $vtc_i > 3 ? 'lazy' : 'eager', 'alt' => $alt ) );
						} else {
							printf( '<img src="%s" alt="%s" width="600" height="450" loading="%s" decoding="async">', esc_url( $img . $m[0] ), esc_attr( $alt ), $vtc_i > 3 ? 'lazy' : 'eager' );
						}
						?>
						<span class="svx-card__num"><?php echo esc_html( sprintf( '%02d', $vtc_i ) ); ?></span>
					</a>
					<div class="svx-card__body">
						<h3 class="svx-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
						<p class="svx-card__text"><?php echo esc_html( wp_trim_words( $ex, 24 ) ); ?></p>
						<?php if ( $m[2] ) : ?>
							<ul class="svx-tags"><?php foreach ( $m[2] as $t ) { echo '<li>' . esc_html( $t ) . '</li>'; } ?></ul>
						<?php endif; ?>
						<div class="svx-card__actions">
							<a class="svx-link" href="<?php the_permalink(); ?>"><?php esc_html_e( 'See details', 'vtech-av' ); ?><span aria-hidden="true"> &rarr;</span></a>
							<?php if ( $vtc_wa && function_exists( 'vtech_contact_wa_url' ) ) : ?>
								<a class="svx-wa" href="<?php echo esc_url( vtech_contact_wa_url() ); ?>"<?php echo vtech_wa_attrs( 'consult', get_the_title() ); // phpcs:ignore ?> aria-label="<?php echo esc_attr( sprintf( __( 'Ask about %s on WhatsApp', 'vtech-av' ), get_the_title() ) ); ?>"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2a10 10 0 0 0-8.6 15.1L2 22l5-1.3A10 10 0 1 0 12 2zm4.5 12.1c-.2-.1-1.5-.7-1.7-.8-.2-.1-.4-.1-.6.1l-.8 1c-.1.2-.3.2-.5.1a6.7 6.7 0 0 1-3.3-2.9c-.2-.4.2-.4.7-1.3.1-.2 0-.3 0-.4l-.8-1.8c-.2-.5-.4-.4-.6-.4h-.5a1 1 0 0 0-.7.3 3 3 0 0 0-.9 2.2 5.2 5.2 0 0 0 1.1 2.7 11.8 11.8 0 0 0 4.5 4c1.7.7 2.3.8 3.2.6a2.7 2.7 0 0 0 1.8-1.2 2.2 2.2 0 0 0 .2-1.3c-.1-.1-.3-.2-.5-.3z"/></svg></a>
							<?php endif; ?>
						</div>
					</div>
				</article>
			<?php endwhile; ?>
		</div>
		<p class="svx-empty" hidden><?php esc_html_e( 'No services in this group yet.', 'vtech-av' ); ?></p>
		<?php endif; ?>
	</div>
</section>


<section class="section svx-guide">
	<div class="container">
		<h2 class="svx-head__title svx-center"><?php esc_html_e( 'Not sure where to start? Choose your space', 'vtech-av' ); ?></h2>
		<div class="svx-guide__grid">
			<a class="svx-need" href="<?php echo esc_url( home_url( '/services/church-sound-systems/' ) ); ?>"><span class="svx-need__k"><?php esc_html_e( 'Church or auditorium', 'vtech-av' ); ?></span><span class="svx-need__v"><?php esc_html_e( 'Clear speech in every seat, worship audio and live streaming', 'vtech-av' ); ?></span></a>
			<a class="svx-need" href="<?php echo esc_url( home_url( '/services/conference-systems/' ) ); ?>"><span class="svx-need__k"><?php esc_html_e( 'Boardroom or office', 'vtech-av' ); ?></span><span class="svx-need__v"><?php esc_html_e( 'Displays, microphones and one-touch Teams or Zoom meetings', 'vtech-av' ); ?></span></a>
			<a class="svx-need" href="<?php echo esc_url( home_url( '/services/led-screens/' ) ); ?>"><span class="svx-need__k"><?php esc_html_e( 'Venue, retail or outdoor', 'vtech-av' ); ?></span><span class="svx-need__v"><?php esc_html_e( 'LED screens, video walls and digital signage that get noticed', 'vtech-av' ); ?></span></a>
			<a class="svx-need" href="<?php echo esc_url( home_url( '/equipment-hire/' ) ); ?>"><span class="svx-need__k"><?php esc_html_e( 'One-off event', 'vtech-av' ); ?></span><span class="svx-need__v"><?php esc_html_e( 'Sound, LED and lighting hire with setup and a technician', 'vtech-av' ); ?></span></a>
		</div>
	</div>
</section>

<section class="section section--surface svx-process">
	<div class="container">
		<h2 class="svx-head__title svx-center"><?php esc_html_e( 'From first call to a working system', 'vtech-av' ); ?></h2>
		<ol class="svx-steps">
			<li><span>1</span><h3><?php esc_html_e( 'Free site survey', 'vtech-av' ); ?></h3><p><?php esc_html_e( 'We visit, listen to how you use the space and take measurements.', 'vtech-av' ); ?></p></li>
			<li><span>2</span><h3><?php esc_html_e( 'Design & fixed quote', 'vtech-av' ); ?></h3><p><?php esc_html_e( 'A technical design and itemised quote within 24 hours in Nairobi.', 'vtech-av' ); ?></p></li>
			<li><span>3</span><h3><?php esc_html_e( 'Install & commission', 'vtech-av' ); ?></h3><p><?php esc_html_e( 'Our technicians install, tune and test everything to the design.', 'vtech-av' ); ?></p></li>
			<li><span>4</span><h3><?php esc_html_e( 'Train & support', 'vtech-av' ); ?></h3><p><?php esc_html_e( 'Your team is trained, with 12 months of support and maintenance plans.', 'vtech-av' ); ?></p></li>
		</ol>
	</div>
</section>

<section class="section svx-why">
	<div class="container svx-why__grid">
		<div>
			<h2 class="svx-head__title"><?php esc_html_e( 'One integrator from design to support', 'vtech-av' ); ?></h2>
			<p><?php esc_html_e( 'We design the system around your room, audience and budget, supply proven pro audio and video brands including dB Technologies speakers and Shure microphones, install and commission everything, and stay on call afterwards.', 'vtech-av' ); ?></p>
			<p><?php esc_html_e( 'Need equipment for a single event instead?', 'vtech-av' ); ?> <a href="<?php echo esc_url( home_url( '/equipment-hire/' ) ); ?>"><?php esc_html_e( 'See sound, PA, LED and lighting hire in Nairobi', 'vtech-av' ); ?></a>.</p>
		</div>
		<ul class="svx-why__list">
			<li><strong><?php esc_html_e( 'Engineered, not guessed', 'vtech-av' ); ?></strong><?php esc_html_e( 'Speaker coverage, screen sizing and acoustics planned for every seat.', 'vtech-av' ); ?></li>
			<li><strong><?php esc_html_e( 'Genuine brands', 'vtech-av' ); ?></strong><?php esc_html_e( 'Supplied with manufacturer warranties and local spares.', 'vtech-av' ); ?></li>
			<li><strong><?php esc_html_e( 'Kenya-wide technicians', 'vtech-av' ); ?></strong><?php esc_html_e( 'Installation and support from Mombasa to Kisumu.', 'vtech-av' ); ?></li>
		</ul>
	</div>
</section>


<section class="section section--surface svx-faq">
	<div class="container narrow">
		<h2 class="svx-head__title svx-center"><?php esc_html_e( 'Questions clients ask before they book', 'vtech-av' ); ?></h2>
		<details class="svx-q" open><summary><?php esc_html_e( 'How much does an AV installation cost?', 'vtech-av' ); ?></summary><p><?php esc_html_e( 'It depends on the room size, audience and equipment. After a free site survey we send a fixed, itemised quote within 24 hours in Nairobi and 48 hours upcountry, with options to fit your budget.', 'vtech-av' ); ?></p></details>
		<details class="svx-q"><summary><?php esc_html_e( 'How long does installation take?', 'vtech-av' ); ?></summary><p><?php esc_html_e( 'Most boardrooms and church systems are installed in a few days once equipment is on site. Larger venues are scheduled in phases so your space stays usable.', 'vtech-av' ); ?></p></details>
		<details class="svx-q"><summary><?php esc_html_e( 'Do you work outside Nairobi?', 'vtech-av' ); ?></summary><p><?php esc_html_e( 'Yes. We install and support systems across all 47 counties, including Mombasa, Kisumu, Nakuru and Eldoret, and take on selected projects across East Africa.', 'vtech-av' ); ?></p></details>
		<details class="svx-q"><summary><?php esc_html_e( 'What happens after installation?', 'vtech-av' ); ?></summary><p><?php esc_html_e( 'We train your team on site, and every installation includes 12 months of support, with maintenance contracts available afterwards.', 'vtech-av' ); ?></p></details>
	</div>
</section>

<section class="section svx-final">
	<div class="container">
		<div class="svx-final__box">
			<div class="svx-final__copy">
				<h2><?php esc_html_e( 'Tell us about your space. Get a fixed quote in 24 hours.', 'vtech-av' ); ?></h2>
				<p><?php esc_html_e( 'Free site survey, no obligation. Talk to an engineer, not a call centre.', 'vtech-av' ); ?></p>
			</div>
			<div class="svx-final__actions">
				<a class="btn btn--accent btn--lg" href="<?php echo esc_url( home_url( '/book-a-consultation/' ) ); ?>"><?php esc_html_e( 'Book a free site survey', 'vtech-av' ); ?></a>
				<?php if ( function_exists( 'vtech_contact_tel' ) ) : ?>
					<a class="svx-final__alt" href="tel:<?php echo esc_attr( vtech_contact_tel() ); ?>"><?php esc_html_e( 'Call', 'vtech-av' ); ?> <?php echo esc_html( vtech_contact( 'phone' ) ); ?></a>
				<?php endif; ?>
				<?php if ( $vtc_wa && function_exists( 'vtech_contact_wa_url' ) ) : ?>
					<a class="svx-final__alt" href="<?php echo esc_url( vtech_contact_wa_url() ); ?>"<?php echo vtech_wa_attrs( 'consult' ); // phpcs:ignore ?>><?php esc_html_e( 'Chat on WhatsApp', 'vtech-av' ); ?></a>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>

<script>
(function(){
  var chips=document.querySelectorAll('[data-svx-filter]'),cards=document.querySelectorAll('[data-svx-cat]'),empty=document.querySelector('.svx-empty');
  chips.forEach(function(c){c.addEventListener('click',function(){
    var f=c.getAttribute('data-svx-filter'),n=0;
    chips.forEach(function(x){var on=x===c;x.classList.toggle('is-active',on);x.setAttribute('aria-pressed',on?'true':'false');});
    cards.forEach(function(k){var show=f==='all'||k.getAttribute('data-svx-cat')===f;k.hidden=!show;if(show)n++;});
    if(empty)empty.hidden=n>0;
  });});
})();
</script>
<?php get_footer();
