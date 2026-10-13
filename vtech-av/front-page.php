<?php
/**
 * Front page — renders the FULL homepage directly in PHP using bundled assets.
 *
 * This does NOT depend on page content, block patterns, or the setup wizard.
 * The homepage always displays correctly the moment the theme is active and a
 * static front page is set — nothing can strip or fail to expand. Content is
 * pulled from the CPTs when present, with built-in fallbacks so it looks
 * complete even before setup runs.
 *
 * @package VTECH_AV
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();

// --- DEFENSIVE FALLBACKS (guarantee the homepage never blanks) ---
if ( ! function_exists( 'vtech_opt' ) ) {
	function vtech_opt( $key, $default = '' ) { return get_theme_mod( $key, $default ); }
}
if ( ! defined( 'VTECH_URI' ) ) { define( 'VTECH_URI', get_template_directory_uri() ); }

if ( ! function_exists( 'vtech_service_fallback_img' ) ) {
	function vtech_service_fallback_img( $slug ) {
		$slug = strtolower( (string) $slug );
		$map = array(
			'sound' => 'sound-systems.webp', 'led' => 'led-screens.webp', 'screen' => 'led-screens.webp',
			'video-wall' => 'led-screens.webp', 'conference' => 'conference-systems.webp', 'boardroom' => 'conference-systems.webp',
			'light' => 'stage-lighting.webp', 'acoustic' => 'acoustic-solutions.webp',
			'signage' => 'video-systems.webp', 'display' => 'video-systems.webp', 'video-system' => 'video-systems.webp',
			'consult' => 'consultation.webp', 'design' => 'consultation.webp',
		);
		foreach ( $map as $needle => $file ) {
			if ( false !== strpos( $slug, $needle ) ) { return $file; }
		}
		return 'og-default.webp';
	}
}

$img = VTECH_URI . '/assets/img/';
?>

<!-- ============ HERO ============ -->
<section class="hero" aria-labelledby="hero-title">
	<img class="hero__bg skip-lazy no-lazy" src="<?php echo esc_url( get_theme_mod( 'vtech_hero_img', $img . 'hero.webp' ) ); ?>" alt="VTECH Audio Visual — premium AV solutions in Kenya" fetchpriority="high" loading="eager" decoding="async" data-no-lazy="1" data-skip-lazy width="1600" height="900">
	<div class="container hero__inner">
		<p class="hero__eyebrow"><?php esc_html_e( 'Audio Visual Company · Kenya & East Africa', 'vtech-av' ); ?></p>
		<h1 id="hero-title" class="hero__title"><?php echo esc_html( vtech_opt( 'vtech_hero_title', "Kenya's Professional Audio Visual Company" ) ); ?></h1>
		<p class="hero__sub"><?php echo esc_html( vtech_opt( 'vtech_hero_sub', 'Sound systems, LED screens, stage lighting, conference & PA systems, acoustics and digital signage — designed, installed and supported for organisations across Kenya.' ) ); ?></p>
		<div class="hero__cta">
			<a class="btn btn--accent btn--lg" href="<?php echo esc_url( home_url( '/book-a-consultation/' ) ); ?>"><?php esc_html_e( 'Book a Consultation', 'vtech-av' ); ?></a>
			<a class="btn btn--ghost btn--lg" href="<?php echo esc_url( get_post_type_archive_link( 'project' ) ?: home_url( '/projects/' ) ); ?>"><?php esc_html_e( 'See Our Projects', 'vtech-av' ); ?></a>
		</div>
		<?php
		$vtc_stats = array(
			array( vtech_opt( 'vtech_stat1_num', '42+' ),  vtech_opt( 'vtech_stat1_lbl', 'Installations delivered' ) ),
			array( vtech_opt( 'vtech_stat2_num', '47' ),    vtech_opt( 'vtech_stat2_lbl', 'Counties served' ) ),
			array( vtech_opt( 'vtech_stat3_num', '24h' ),   vtech_opt( 'vtech_stat3_lbl', 'Quote turnaround' ) ),
			array( vtech_opt( 'vtech_stat4_num', '12mo' ),  vtech_opt( 'vtech_stat4_lbl', 'Support & warranty' ) ),
		);
		?>
		<div class="hero__stats">
			<?php foreach ( $vtc_stats as $vtc_st ) : if ( '' === trim( (string) $vtc_st[0] ) && '' === trim( (string) $vtc_st[1] ) ) { continue; } ?>
				<div class="hero__stat">
					<span class="hero__stat-num"><?php echo esc_html( $vtc_st[0] ); ?></span>
					<span class="hero__stat-lbl"><?php echo esc_html( $vtc_st[1] ); ?></span>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<!-- ============ TRUSTED CLIENTS (logo slider) ============ -->
<?php
$vtc_clients = function_exists( 'vtech_get_clients' ) ? vtech_get_clients() : array();
// Only render this section when there are real clients (added in the dashboard).
// No placeholder names are ever shown.
if ( ! empty( $vtc_clients ) ) : ?>
<section class="clients" aria-label="Organisations that trust VTECH">
	<div class="container">
		<p class="clients__eyebrow"><?php esc_html_e( 'Trusted by leading organisations across Kenya', 'vtech-av' ); ?></p>
	</div>
	<div class="logo-marquee" role="group" aria-label="Client logos">
		<div class="logo-marquee__track">
			<?php
			$loop = array_merge( $vtc_clients, $vtc_clients );
			foreach ( $loop as $vtc_c ) :
				$vtc_inner = $vtc_c['logo']
					? '<img src="' . esc_url( $vtc_c['logo'] ) . '" alt="' . esc_attr( $vtc_c['name'] ) . '" loading="lazy" decoding="async" class="logo-marquee__img">'
					: '<span class="logo-marquee__name">' . esc_html( $vtc_c['name'] ) . '</span>';
				if ( ! empty( $vtc_c['url'] ) ) {
					echo '<a class="logo-marquee__item" href="' . esc_url( $vtc_c['url'] ) . '" target="_blank" rel="noopener">' . $vtc_inner . '</a>';
				} else {
					echo '<span class="logo-marquee__item">' . $vtc_inner . '</span>';
				}
			endforeach; ?>
		</div>
	</div>
</section>
<?php endif; ?>

<!-- ============ SERVICES ============ -->
<section class="section section--surface" aria-labelledby="svc-title">
	<div class="container">
		<div class="section-head">
			<p class="eyebrow"><?php esc_html_e( 'What we do', 'vtech-av' ); ?></p>
			<h2 id="svc-title" class="section__title"><?php esc_html_e( 'Complete Audio Visual Solutions', 'vtech-av' ); ?></h2>
			<p><?php esc_html_e( 'From boardrooms in Nairobi to auditoriums nationwide — one integrator for design, supply, installation and support.', 'vtech-av' ); ?></p>
		</div>
		<div class="card-grid card-grid--3">
		<?php
		$services = new WP_Query( array( 'post_type' => 'service', 'posts_per_page' => 6, 'orderby' => 'menu_order', 'order' => 'ASC' ) );
		if ( $services->have_posts() ) :
			while ( $services->have_posts() ) : $services->the_post(); ?>
				<a class="card card--service" href="<?php the_permalink(); ?>">
					<?php if ( has_post_thumbnail() ) : ?>
						<figure class="card__media"><?php the_post_thumbnail( 'vtech-card', array( 'loading' => 'lazy' ) ); ?></figure>
					<?php else : ?>
						<figure class="card__media"><img src="<?php echo esc_url( $img . vtech_service_fallback_img( get_post_field( 'post_name', get_the_ID() ) ) ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?> in Kenya" loading="lazy" decoding="async"></figure>
					<?php endif; ?>
					<h3 class="card__title"><?php the_title(); ?></h3>
					<p class="card__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 16 ) ); ?></p>
					<span class="card__link"><?php esc_html_e( 'Explore', 'vtech-av' ); ?></span>
				</a>
			<?php endwhile; wp_reset_postdata();
		else :
			// Fallback before setup: static cards with bundled images.
			$fallback = array(
				array( 'Professional Sound Systems', 'Line arrays, mixing and PA for churches, halls and stadiums.', 'sound-systems.webp', '/services/professional-sound-systems/' ),
				array( 'LED Screens & Video Walls', 'Indoor and outdoor LED display supply and installation.', 'led-screens.webp', '/services/led-screens-video-walls/' ),
				array( 'Conference & Boardroom AV', 'Video conferencing and one-touch meeting rooms.', 'conference-systems.webp', '/services/conference-boardroom-av/' ),
				array( 'Stage & Architectural Lighting', 'Event and permanent lighting design and control.', 'stage-lighting.webp', '/services/stage-architectural-lighting/' ),
				array( 'Acoustic Design & Soundproofing', 'Room acoustics and noise control for clean audio.', 'acoustic-solutions.webp', '/services/acoustic-design-soundproofing/' ),
				array( 'Digital Signage & Displays', 'Indoor and outdoor digital signage and professional display networks.', 'video-systems.webp', '/services/digital-signage/' ),
			);
			foreach ( $fallback as $f ) : ?>
				<a class="card card--service" href="<?php echo esc_url( home_url( $f[3] ) ); ?>">
					<figure class="card__media"><img src="<?php echo esc_url( $img . $f[2] ); ?>" alt="<?php echo esc_attr( $f[0] ); ?> in Kenya" loading="lazy" decoding="async"></figure>
					<h3 class="card__title"><?php echo esc_html( $f[0] ); ?></h3>
					<p class="card__excerpt"><?php echo esc_html( $f[1] ); ?></p>
					<span class="card__link"><?php esc_html_e( 'Explore', 'vtech-av' ); ?></span>
				</a>
			<?php endforeach;
		endif; ?>
		</div>
	</div>
</section>

<!-- ============ INDUSTRIES ============ -->
<section class="section" aria-labelledby="ind-title">
	<div class="container">
		<div class="section-head">
			<p class="eyebrow"><?php esc_html_e( 'Industries', 'vtech-av' ); ?></p>
			<h2 id="ind-title" class="section__title"><?php esc_html_e( 'AV Solutions for Every Sector in Kenya', 'vtech-av' ); ?></h2>
			<p><?php esc_html_e( 'Systems engineered around how each sector actually uses sound, video and light.', 'vtech-av' ); ?></p>
		</div>
		<div class="card-grid card-grid--3 card-grid--industries">
		<?php
		$industries = array(
			array( 'Churches', 'Church sound systems Kenya', 'industry-churches.webp', '/industry/churches/' ),
			array( 'Hotels & Hospitality', 'Hotel audio visual solutions Kenya', 'industry-hotels.webp', '/industry/hotels/' ),
			array( 'Schools & Universities', 'School PA systems Kenya', 'industry-education.webp', '/industry/education/' ),
			array( 'Corporates', 'Boardroom AV solutions Nairobi', 'industry-corporate.webp', '/industry/corporate/' ),
			array( 'Government', 'Conference systems Kenya', 'industry-government.webp', '/industry/government/' ),
			array( 'Media Houses', 'Broadcast & live streaming', 'industry-media.webp', '/industry/media/' ),
			array( 'Hospitals & Healthcare', 'Nurse call & PA systems Kenya', 'industry-healthcare.webp', '/industry/healthcare/' ),
			array( 'Conference Centres', 'Delegate & conference systems', 'industry-conference-centres.webp', '/industry/conference-centres/' ),
			array( 'Event Organisers', 'Sound & lighting hire Kenya', 'industry-events.webp', '/industry/events/' ),
		);
		// Homepage shows a curated, fixed set (default 9). Filterable if you ever want to change it.
		$ind_limit = (int) apply_filters( 'vtech_home_industries_limit', 9 );
		$industries = array_slice( $industries, 0, $ind_limit );
		foreach ( $industries as $i ) : ?>
			<a class="card card--overlay" href="<?php echo esc_url( home_url( $i[3] ) ); ?>">
				<figure class="card__media"><img src="<?php echo esc_url( $img . $i[2] ); ?>" alt="AV solutions for <?php echo esc_attr( $i[0] ); ?> in Kenya" loading="lazy" decoding="async"></figure>
				<div class="card__body">
					<h3 class="card__title"><?php echo esc_html( $i[0] ); ?></h3>
					<p class="card__excerpt"><?php echo esc_html( $i[1] ); ?></p>
				</div>
			</a>
		<?php endforeach; ?>
		</div>
	</div>
</section>

<!-- ============ WHY VTECH + HOW WE WORK ============ -->
<section class="section why" aria-labelledby="why-title">
	<div class="container">
		<div class="section-head">
			<p class="eyebrow"><?php esc_html_e( 'Why VTECH', 'vtech-av' ); ?></p>
			<h2 id="why-title" class="section__title"><?php esc_html_e( 'One accountable AV partner, from survey to support', 'vtech-av' ); ?></h2>
			<p><?php esc_html_e( 'We design, supply, install and look after every system ourselves, so you get one team, one quote and one point of contact.', 'vtech-av' ); ?></p>
		</div>
		<div class="why-grid">
			<?php
			$vtc_why = array(
				array( 'Design-led engineering', 'Every project starts with a free site survey and a written technical design matched to your room, audience and budget.' ),
				array( 'Fixed quotes in 24 hours', 'Clear, itemised pricing within 24 hours in Nairobi and 48 hours upcountry. No surprise costs after installation.' ),
				array( 'Trusted pro-audio brands', 'dB Technologies, Shure and leading LED, lighting and conferencing brands, supplied with genuine warranties.' ),
				array( 'Support across Kenya', '12 months of support on every installation, maintenance contracts and technicians covering all 47 counties.' ),
			);
			foreach ( $vtc_why as $vtc_w ) : ?>
				<div class="why-card">
					<span class="why-card__icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M9 16.2 4.8 12l-1.4 1.4L9 19 21 7l-1.4-1.4z"/></svg></span>
					<h3 class="why-card__title"><?php echo esc_html( $vtc_w[0] ); ?></h3>
					<p><?php echo esc_html( $vtc_w[1] ); ?></p>
				</div>
			<?php endforeach; ?>
		</div>

		<div class="process-band">
			<h3 class="process-band__title"><?php esc_html_e( 'How we work', 'vtech-av' ); ?></h3>
			<ol class="process-steps">
				<?php
				$vtc_steps = array(
					array( 'Free site survey', 'We visit your venue, listen to how you use the space and take measurements.' ),
					array( 'Design and fixed quote', 'You receive a technical design and a fixed, itemised quote within 24 hours.' ),
					array( 'Install and commission', 'Our technicians install, tune and test everything to the agreed design.' ),
					array( 'Training and support', 'We train your team and stay on call with 12 months of support.' ),
				);
				foreach ( $vtc_steps as $vtc_n => $vtc_s ) : ?>
					<li class="process-steps__item">
						<span class="process-steps__num"><?php echo esc_html( (string) ( $vtc_n + 1 ) ); ?></span>
						<h4><?php echo esc_html( $vtc_s[0] ); ?></h4>
						<p><?php echo esc_html( $vtc_s[1] ); ?></p>
					</li>
				<?php endforeach; ?>
			</ol>
		</div>
	</div>
</section>

<!-- ============ FEATURED PROJECTS ============ -->
<?php
$vtech_proj_limit = (int) apply_filters( 'vtech_home_projects_limit', (int) get_theme_mod( 'vtech_home_projects', 6 ) );
if ( $vtech_proj_limit < 1 ) { $vtech_proj_limit = 6; }
$projects = new WP_Query( array( 'post_type' => 'project', 'posts_per_page' => $vtech_proj_limit, 'orderby' => 'date', 'order' => 'DESC' ) );
if ( $projects->have_posts() ) : ?>
<section class="section section--surface">
	<div class="container">
		<div class="section-head">
			<p class="eyebrow"><?php esc_html_e( 'Our work', 'vtech-av' ); ?></p>
			<h2 class="section__title"><?php esc_html_e( 'Recent Projects in Kenya', 'vtech-av' ); ?></h2>
		</div>
		<div class="card-grid card-grid--3">
		<?php while ( $projects->have_posts() ) : $projects->the_post(); ?>
			<a class="card card--project" href="<?php the_permalink(); ?>">
				<?php if ( has_post_thumbnail() ) : ?><figure class="card__media"><?php the_post_thumbnail( 'vtech-card', array( 'loading' => 'lazy' ) ); ?></figure><?php else : ?><figure class="card__media"><img src="<?php echo esc_url( $img . 'og-default.webp' ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?> — VTECH project" loading="lazy" decoding="async"></figure><?php endif; ?>
				<div class="card__body">
					<h3 class="card__title"><?php the_title(); ?></h3>
					<p class="card__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 16 ) ); ?></p>
				</div>
			</a>
		<?php endwhile; wp_reset_postdata(); ?>
		</div>
		<p class="section-more"><a class="btn btn--ghost" href="<?php echo esc_url( get_post_type_archive_link( 'project' ) ?: home_url( '/projects/' ) ); ?>"><?php esc_html_e( 'View all projects', 'vtech-av' ); ?></a></p>
	</div>
</section>
<?php endif; ?>

<!-- ============ FAQ ============ -->
<section class="section">
	<div class="container narrow">
		<div class="section-head">
			<p class="eyebrow"><?php esc_html_e( 'FAQ', 'vtech-av' ); ?></p>
			<h2 class="section__title"><?php esc_html_e( 'Frequently Asked Questions', 'vtech-av' ); ?></h2>
		</div>
		<div class="faq">
			<?php
			$vtc_faqs = function_exists( 'vtech_home_faqs' ) ? vtech_home_faqs() : array();
			foreach ( $vtc_faqs as $vtc_f ) : ?>
				<details class="faq__item"><summary><?php echo esc_html( $vtc_f[0] ); ?></summary><div class="faq__answer"><?php echo esc_html( $vtc_f[1] ); ?></div></details>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<!-- ============ CTA ============ -->
<section class="section">
	<div class="container">
		<div class="cta-band section">
			<div class="cta-band__inner">
				<h2><?php esc_html_e( 'Planning an AV project in Kenya?', 'vtech-av' ); ?></h2>
				<p><?php esc_html_e( 'Book a free site survey and get a fixed quote within 24 hours.', 'vtech-av' ); ?></p>
				<div class="cta-band__actions">
					<a class="btn btn--light btn--lg" href="<?php echo esc_url( home_url( '/book-a-consultation/' ) ); ?>"><?php esc_html_e( 'Request a Site Survey', 'vtech-av' ); ?></a>
					<a class="btn btn--outline-light btn--lg" href="tel:<?php echo esc_attr( vtech_contact_tel() ); ?>"><?php echo esc_html( sprintf( __( 'Call %s', 'vtech-av' ), vtech_contact( 'phone' ) ) ); ?></a>
				</div>
			</div>
		</div>
	</div>
</section>

<?php get_footer();
