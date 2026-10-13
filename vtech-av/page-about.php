<?php
/**
 * Template Name: About VTECH
 * About page — redesigned v5.53.0 in an international brand style:
 * statement hero with imagery, mission, numbers, story, values, capabilities,
 * sectors, approach, brands, visit-us and final CTA.
 *
 * @package VTECH_AV
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();
$img    = VTECH_URI . '/assets/img/';
$vtc_wa = function_exists( 'vtech_wa_attrs' ) && function_exists( 'vtech_contact_wa_url' );
$vtc_c  = function ( $k, $d = '' ) { return function_exists( 'vtech_contact' ) ? (string) vtech_contact( $k ) : $d; };
?>
<section class="ab-hero">
	<div class="container">
		<?php if ( function_exists( 'vtech_breadcrumbs' ) ) { vtech_breadcrumbs(); } ?>
		<div class="ab-hero__grid">
			<div class="ab-hero__copy">
				<p class="svx-eyebrow"><?php esc_html_e( 'About VTECH Audio Visual Solutions', 'vtech-av' ); ?></p>
				<h1 class="ab-hero__title"><?php esc_html_e( 'We make spaces sound, look and work the way they should.', 'vtech-av' ); ?></h1>
				<p class="ab-hero__lead"><?php esc_html_e( 'A Nairobi audio visual integrator designing, installing and supporting sound, video and lighting for organisations across Kenya and East Africa. Engineered to international standards, delivered with local expertise.', 'vtech-av' ); ?></p>
				<div class="svx-hero__cta">
					<a class="btn btn--accent btn--lg" href="<?php echo esc_url( home_url( '/book-a-consultation/' ) ); ?>"><?php esc_html_e( 'Work with us', 'vtech-av' ); ?></a>
					<a class="btn btn--outline-light btn--lg" href="<?php echo esc_url( home_url( '/projects/' ) ); ?>"><?php esc_html_e( 'See our projects', 'vtech-av' ); ?></a>
				</div>
			</div>
			<div class="ab-hero__media" aria-hidden="true">
				<img class="ab-hero__img ab-hero__img--a" src="<?php echo esc_url( $img . 'services/consultation.webp' ); ?>" alt="" width="600" height="450" fetchpriority="high" decoding="async">
				<img class="ab-hero__img ab-hero__img--b" src="<?php echo esc_url( $img . 'services/av-maintenance-support.webp' ); ?>" alt="" width="600" height="450" decoding="async">
			</div>
		</div>
	</div>
</section>

<section class="ab-stats" aria-label="<?php esc_attr_e( 'VTECH in numbers', 'vtech-av' ); ?>">
	<div class="container ab-stats__grid">
		<div class="ab-stat"><span class="ab-stat__num">42+</span><span class="ab-stat__lbl"><?php esc_html_e( 'Installations delivered', 'vtech-av' ); ?></span></div>
		<div class="ab-stat"><span class="ab-stat__num">47</span><span class="ab-stat__lbl"><?php esc_html_e( 'Counties served', 'vtech-av' ); ?></span></div>
		<div class="ab-stat"><span class="ab-stat__num">24h</span><span class="ab-stat__lbl"><?php esc_html_e( 'Fixed quote turnaround', 'vtech-av' ); ?></span></div>
		<div class="ab-stat"><span class="ab-stat__num">12mo</span><span class="ab-stat__lbl"><?php esc_html_e( 'Support on every install', 'vtech-av' ); ?></span></div>
	</div>
</section>

<section class="section ab-mission">
	<div class="container">
		<p class="ab-kicker"><?php esc_html_e( 'Our mission', 'vtech-av' ); ?></p>
		<p class="ab-mission__text"><?php esc_html_e( 'To give every church, boardroom, classroom and venue in East Africa audio visual that is clear, dependable and simple to use, designed properly, installed cleanly and supported for years.', 'vtech-av' ); ?></p>
	</div>
</section>

<section class="section section--surface ab-story">
	<div class="container ab-split">
		<figure class="ab-split__media">
			<img src="<?php echo esc_url( $img . 'services/church-sound-systems.webp' ); ?>" alt="<?php esc_attr_e( 'Sound engineer mixing a live church service in Kenya', 'vtech-av' ); ?>" width="600" height="450" loading="lazy" decoding="async">
		</figure>
		<div class="ab-split__copy">
			<p class="ab-kicker"><?php esc_html_e( 'Who we are', 'vtech-av' ); ?></p>
			<h2 class="svx-head__title"><?php esc_html_e( 'One accountable partner, from first survey to years of support', 'vtech-av' ); ?></h2>
			<p><?php esc_html_e( 'Founded and headquartered in Nairobi, VTECH Audio Visual Solutions is a full-service integrator serving churches, hotels, schools and universities, corporates, government, healthcare, media houses and event organisers.', 'vtech-av' ); ?></p>
			<p><?php esc_html_e( 'Our engineers specify equipment from leading global manufacturers and install to recognised standards for acoustics, electrical safety and signal integrity, while designing for the realities of the region: power conditions, room types, budgets and timelines.', 'vtech-av' ); ?></p>
			<ul class="ab-checks">
				<li><?php esc_html_e( 'Design, supply, installation and support under one roof', 'vtech-av' ); ?></li>
				<li><?php esc_html_e( 'Fixed, itemised quotes with no hidden costs', 'vtech-av' ); ?></li>
				<li><?php esc_html_e( 'Technicians covering all 47 counties', 'vtech-av' ); ?></li>
			</ul>
		</div>
	</div>
</section>

<section class="section ab-values">
	<div class="container">
		<div class="ab-head">
			<p class="ab-kicker"><?php esc_html_e( 'What we stand for', 'vtech-av' ); ?></p>
			<h2 class="svx-head__title"><?php esc_html_e( 'Four promises we keep on every project', 'vtech-av' ); ?></h2>
		</div>
		<div class="ab-values__grid">
			<article class="ab-value"><span class="ab-value__n">01</span><h3><?php esc_html_e( 'Engineering integrity', 'vtech-av' ); ?></h3><p><?php esc_html_e( 'Systems designed to international standards, not shortcuts. We measure, specify and document every install.', 'vtech-av' ); ?></p></article>
			<article class="ab-value"><span class="ab-value__n">02</span><h3><?php esc_html_e( 'Transparent pricing', 'vtech-av' ); ?></h3><p><?php esc_html_e( 'Fixed written quotes within 24 hours of a free site survey. No hidden costs, no surprises.', 'vtech-av' ); ?></p></article>
			<article class="ab-value"><span class="ab-value__n">03</span><h3><?php esc_html_e( 'Local accountability', 'vtech-av' ); ?></h3><p><?php esc_html_e( 'A Kenyan team you can reach, with nationwide coverage and support across East Africa.', 'vtech-av' ); ?></p></article>
			<article class="ab-value"><span class="ab-value__n">04</span><h3><?php esc_html_e( 'Long-term partnership', 'vtech-av' ); ?></h3><p><?php esc_html_e( 'Every project includes a 12-month support window and optional annual maintenance contracts.', 'vtech-av' ); ?></p></article>
		</div>
	</div>
</section>

<section class="section section--surface ab-caps">
	<div class="container">
		<div class="ab-head ab-head--row">
			<div>
				<p class="ab-kicker"><?php esc_html_e( 'What we do', 'vtech-av' ); ?></p>
				<h2 class="svx-head__title"><?php esc_html_e( 'Complete audio visual capability', 'vtech-av' ); ?></h2>
			</div>
			<a class="svx-link" href="<?php echo esc_url( home_url( '/services/' ) ); ?>"><?php esc_html_e( 'All services', 'vtech-av' ); ?> &rarr;</a>
		</div>
		<div class="ab-caps__grid">
			<?php
			$vtc_caps = array(
				array( '/services/sound-systems/', __( 'Sound & PA systems', 'vtech-av' ) ),
				array( '/services/led-screens/', __( 'LED screens & video walls', 'vtech-av' ) ),
				array( '/services/conference-systems/', __( 'Conference & boardroom AV', 'vtech-av' ) ),
				array( '/services/video-conferencing-systems/', __( 'Video conferencing', 'vtech-av' ) ),
				array( '/services/lighting/', __( 'Stage & architectural lighting', 'vtech-av' ) ),
				array( '/services/acoustic-solutions/', __( 'Acoustics & soundproofing', 'vtech-av' ) ),
				array( '/services/digital-signage/', __( 'Digital signage', 'vtech-av' ) ),
				array( '/hire-packages/', __( 'Event equipment hire', 'vtech-av' ) ),
			);
			foreach ( $vtc_caps as $cap ) {
				printf( '<a class="ab-cap" href="%s"><span>%s</span><span aria-hidden="true">&rarr;</span></a>', esc_url( home_url( $cap[0] ) ), esc_html( $cap[1] ) );
			}
			?>
		</div>
	</div>
</section>

<section class="section ab-sectors">
	<div class="container">
		<div class="ab-head">
			<p class="ab-kicker"><?php esc_html_e( 'Who we serve', 'vtech-av' ); ?></p>
			<h2 class="svx-head__title"><?php esc_html_e( 'Trusted across sectors', 'vtech-av' ); ?></h2>
		</div>
		<ul class="hp-uses__list">
			<?php
			foreach ( array( 'churches' => __( 'Churches', 'vtech-av' ), 'corporate' => __( 'Corporates', 'vtech-av' ), 'hotels' => __( 'Hotels', 'vtech-av' ), 'education' => __( 'Schools & universities', 'vtech-av' ), 'government' => __( 'Government', 'vtech-av' ), 'healthcare' => __( 'Healthcare', 'vtech-av' ), 'media' => __( 'Media houses', 'vtech-av' ), 'conference-centres' => __( 'Conference centres', 'vtech-av' ), 'events' => __( 'Events', 'vtech-av' ) ) as $s => $l ) {
				printf( '<li><a href="%s">%s</a></li>', esc_url( home_url( '/industry/' . $s . '/' ) ), esc_html( $l ) );
			}
			?>
		</ul>
	</div>
</section>

<section class="section section--surface svx-process">
	<div class="container">
		<h2 class="svx-head__title svx-center"><?php esc_html_e( 'How we work', 'vtech-av' ); ?></h2>
		<ol class="svx-steps">
			<li><span>1</span><h3><?php esc_html_e( 'Listen & survey', 'vtech-av' ); ?></h3><p><?php esc_html_e( 'Your goals first, then a free on-site survey of the space and acoustics.', 'vtech-av' ); ?></p></li>
			<li><span>2</span><h3><?php esc_html_e( 'Design & quote', 'vtech-av' ); ?></h3><p><?php esc_html_e( 'A tailored design and a fixed, transparent quote within 24 hours.', 'vtech-av' ); ?></p></li>
			<li><span>3</span><h3><?php esc_html_e( 'Install & commission', 'vtech-av' ); ?></h3><p><?php esc_html_e( 'Clean, standards-compliant installation, full testing and training.', 'vtech-av' ); ?></p></li>
			<li><span>4</span><h3><?php esc_html_e( 'Support & maintain', 'vtech-av' ); ?></h3><p><?php esc_html_e( 'Ongoing support and maintenance so your system keeps performing.', 'vtech-av' ); ?></p></li>
		</ol>
	</div>
</section>

<?php $vtc_brands = function_exists( 'vtech_get_brands' ) ? vtech_get_brands( 12 ) : array(); if ( ! empty( $vtc_brands ) ) : ?>
<section class="section ab-brands"><div class="container">
	<div class="ab-head">
		<p class="ab-kicker"><?php esc_html_e( 'Partners in performance', 'vtech-av' ); ?></p>
		<h2 class="svx-head__title"><?php esc_html_e( 'Brands we work with', 'vtech-av' ); ?></h2>
		<p class="ab-head__sub"><?php esc_html_e( 'Equipment from leading global manufacturers, matched to your budget and application.', 'vtech-av' ); ?></p>
	</div>
	<div class="brand-carousel" data-brand-carousel>
		<button type="button" class="brand-carousel__nav brand-carousel__nav--prev" data-brand-prev aria-label="<?php esc_attr_e( 'Previous brands', 'vtech-av' ); ?>">&#8249;</button>
		<div class="brand-carousel__track" data-brand-track>
			<?php foreach ( $vtc_brands as $vtc_b ) : ?>
				<div class="brand-carousel__item">
					<?php if ( $vtc_b['logo'] ) : ?>
						<img src="<?php echo esc_url( $vtc_b['logo'] ); ?>" alt="<?php echo esc_attr( $vtc_b['name'] ); ?>" loading="lazy" decoding="async">
					<?php else : ?>
						<span class="brand-carousel__name"><?php echo esc_html( $vtc_b['name'] ); ?></span>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>
		<button type="button" class="brand-carousel__nav brand-carousel__nav--next" data-brand-next aria-label="<?php esc_attr_e( 'Next brands', 'vtech-av' ); ?>">&#8250;</button>
	</div>
</div></section>
<?php endif; ?>

<section class="section section--surface ab-visit">
	<div class="container ab-visit__grid">
		<div>
			<p class="ab-kicker"><?php esc_html_e( 'Visit us', 'vtech-av' ); ?></p>
			<h2 class="svx-head__title"><?php esc_html_e( 'Our Nairobi office', 'vtech-av' ); ?></h2>
			<p class="ab-visit__lead"><?php esc_html_e( 'See equipment, talk through your project, or plan a site survey with our team.', 'vtech-av' ); ?></p>
		</div>
		<dl class="ab-visit__list">
			<div><dt><?php esc_html_e( 'Address', 'vtech-av' ); ?></dt><dd><?php echo esc_html( $vtc_c( 'address', 'Ground Floor, Mpaka Plaza, Mpaka Road, Westlands, Nairobi' ) ); ?></dd></div>
			<div><dt><?php esc_html_e( 'Hours', 'vtech-av' ); ?></dt><dd><?php echo esc_html( $vtc_c( 'hours' ) ); ?></dd></div>
			<div><dt><?php esc_html_e( 'Phone & WhatsApp', 'vtech-av' ); ?></dt><dd><a href="tel:<?php echo esc_attr( function_exists( 'vtech_contact_tel' ) ? vtech_contact_tel() : '' ); ?>"><?php echo esc_html( $vtc_c( 'phone' ) ); ?></a></dd></div>
			<div><dt><?php esc_html_e( 'Email', 'vtech-av' ); ?></dt><dd><a href="mailto:<?php echo esc_attr( $vtc_c( 'email' ) ); ?>"><?php echo esc_html( $vtc_c( 'email' ) ); ?></a></dd></div>
			<?php if ( function_exists( 'vtech_gbp_url' ) && vtech_gbp_url() ) : ?>
				<div><dt><?php esc_html_e( 'Directions', 'vtech-av' ); ?></dt><dd><a href="<?php echo esc_url( vtech_gbp_url() ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Open in Google Maps', 'vtech-av' ); ?> &rarr;</a></dd></div>
			<?php endif; ?>
		</dl>
	</div>
</section>

<section class="section svx-final">
	<div class="container">
		<div class="svx-final__box">
			<div class="svx-final__copy">
				<h2><?php esc_html_e( 'Let us build your AV system the right way.', 'vtech-av' ); ?></h2>
				<p><?php esc_html_e( 'Book a free site survey and get a fixed quote within 24 hours.', 'vtech-av' ); ?></p>
			</div>
			<div class="svx-final__actions">
				<a class="btn btn--accent btn--lg" href="<?php echo esc_url( home_url( '/book-a-consultation/' ) ); ?>"><?php esc_html_e( 'Book a free site survey', 'vtech-av' ); ?></a>
				<?php if ( $vtc_wa ) : ?>
					<a class="svx-final__alt" href="<?php echo esc_url( vtech_contact_wa_url() ); ?>"<?php echo vtech_wa_attrs( 'consult' ); // phpcs:ignore ?>><?php esc_html_e( 'Chat on WhatsApp', 'vtech-av' ); ?></a>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>

<?php get_footer();
