<?php
/**
 * Industries archive (/industries/) — redesigned v5.51.0.
 * Editorial hero with sector quick-jump, bento photo grid, capability matrix,
 * process, FAQ and final CTA. Shares svx- components with the services page.
 *
 * @package VTECH_AV
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();

$img    = VTECH_URI . '/assets/img/';
$vtc_wa = function_exists( 'vtech_wa_attrs' ) && function_exists( 'vtech_contact_wa_url' );

/** Per-sector fallback photo, short label, outcome line and typical systems. */
$vtc_ind = array(
	'churches'           => array( 'industry-churches.webp', __( 'Churches', 'vtech-av' ), __( 'Every word heard, worship that sounds full, services streamed properly.', 'vtech-av' ), array( 'Sound', 'LED', 'Live stream' ) ),
	'corporate'          => array( 'industry-corporate.webp', __( 'Corporate', 'vtech-av' ), __( 'Meetings that start on time and remote colleagues who hear every seat.', 'vtech-av' ), array( 'Boardroom AV', 'Teams / Zoom', 'Displays' ) ),
	'hotels'             => array( 'industry-hotels.webp', __( 'Hotels', 'vtech-av' ), __( 'Banquet and conference AV guests remember, plus music in every zone.', 'vtech-av' ), array( 'Conference', 'Background music', 'LED' ) ),
	'events'             => array( 'industry-events.webp', __( 'Events', 'vtech-av' ), __( 'Concert-grade sound, LED and lighting, delivered and run by our crew.', 'vtech-av' ), array( 'Line arrays', 'LED walls', 'Lighting' ) ),
	'conference-centres' => array( 'industry-conference-centres.webp', __( 'Conference centres', 'vtech-av' ), __( 'Plenary sound, delegate microphones and interpretation that scale.', 'vtech-av' ), array( 'Delegate mics', 'Interpretation', 'Streaming' ) ),
	'government'         => array( 'industry-government.webp', __( 'Government', 'vtech-av' ), __( 'Assembly chambers with clear debate, recording and public broadcast.', 'vtech-av' ), array( 'Delegate system', 'Recording', 'PA' ) ),
	'education'          => array( 'industry-education.webp', __( 'Education', 'vtech-av' ), __( 'Lecture halls and assemblies where every student can hear and see.', 'vtech-av' ), array( 'Lecture AV', 'Campus PA', 'Projection' ) ),
	'healthcare'         => array( 'industry-healthcare.webp', __( 'Healthcare', 'vtech-av' ), __( 'Calm, zoned paging and clear announcements across wards and waiting areas.', 'vtech-av' ), array( 'Paging', 'PA', 'Training rooms' ) ),
	'media'              => array( 'industry-media.webp', __( 'Media', 'vtech-av' ), __( 'Studios built for broadcast: treated rooms, lighting and multi-camera streaming.', 'vtech-av' ), array( 'Studio audio', 'Acoustics', 'Lighting' ) ),
);

// Collect posts first so we can order the bento grid and build the quick-jump.
$vtc_posts = array();
if ( have_posts() ) {
	while ( have_posts() ) { the_post(); $vtc_posts[] = get_post(); }
	rewind_posts();
}
$vtc_order = array_keys( $vtc_ind );
usort( $vtc_posts, function ( $a, $b ) use ( $vtc_order ) {
	$ia = array_search( $a->post_name, $vtc_order, true );
	$ib = array_search( $b->post_name, $vtc_order, true );
	return ( false === $ia ? 99 : $ia ) - ( false === $ib ? 99 : $ib );
} );
?>
<section class="ind-hero">
	<div class="container">
		<?php if ( function_exists( 'vtech_breadcrumbs' ) ) { vtech_breadcrumbs(); } ?>
		<div class="ind-hero__grid">
			<div>
				<p class="svx-eyebrow"><?php esc_html_e( 'Industries we serve', 'vtech-av' ); ?></p>
				<h1 class="ind-hero__title"><?php esc_html_e( 'AV engineered around how your sector works', 'vtech-av' ); ?></h1>
			</div>
			<div class="ind-hero__side">
				<p class="ind-hero__lead"><?php esc_html_e( 'From churches and boardrooms to assembly chambers and broadcast studios, we design, install and support sound, video and lighting for organisations across Kenya and East Africa.', 'vtech-av' ); ?></p>
				<div class="svx-hero__cta">
					<a class="btn btn--accent btn--lg" href="<?php echo esc_url( home_url( '/book-a-consultation/' ) ); ?>"><?php esc_html_e( 'Get a free site survey', 'vtech-av' ); ?></a>
					<?php if ( $vtc_wa ) : ?>
						<a class="btn btn--outline-light btn--lg" href="<?php echo esc_url( vtech_contact_wa_url() ); ?>"<?php echo vtech_wa_attrs( 'consult' ); // phpcs:ignore ?>><?php esc_html_e( 'Ask on WhatsApp', 'vtech-av' ); ?></a>
					<?php endif; ?>
				</div>
			</div>
		</div>
		<?php if ( $vtc_posts ) : ?>
		<nav class="ind-jump" aria-label="<?php esc_attr_e( 'Jump to a sector', 'vtech-av' ); ?>">
			<?php foreach ( $vtc_posts as $p ) :
				$lbl = isset( $vtc_ind[ $p->post_name ] ) ? $vtc_ind[ $p->post_name ][1] : get_the_title( $p ); ?>
				<a href="<?php echo esc_url( get_permalink( $p ) ); ?>"><?php echo esc_html( $lbl ); ?></a>
			<?php endforeach; ?>
		</nav>
		<?php endif; ?>
	</div>
</section>

<section class="svx-trust" aria-label="<?php esc_attr_e( 'Coverage', 'vtech-av' ); ?>">
	<div class="container svx-trust__inner">
		<p class="svx-trust__label"><?php esc_html_e( 'One accountable team from site survey to support', 'vtech-av' ); ?></p>
		<ul class="svx-proof svx-proof--dark">
			<li><strong>24h</strong> <?php esc_html_e( 'fixed quote', 'vtech-av' ); ?></li>
			<li><strong>47</strong> <?php esc_html_e( 'counties', 'vtech-av' ); ?></li>
			<li><strong>12mo</strong> <?php esc_html_e( 'support', 'vtech-av' ); ?></li>
		</ul>
	</div>
</section>

<section class="section ind-list">
	<div class="container">
		<?php if ( $vtc_posts ) : ?>
		<div class="ind-bento">
			<?php foreach ( $vtc_posts as $i => $p ) :
				$slug = $p->post_name;
				$m    = isset( $vtc_ind[ $slug ] ) ? $vtc_ind[ $slug ] : array( 'og-default.webp', get_the_title( $p ), wp_trim_words( get_the_excerpt( $p ), 18 ), array() );
				$alt  = get_the_title( $p ) . ' AV solutions in Kenya';
				$big  = $i < 2 ? ' ind-tile--lg' : '';
			?>
			<article class="ind-tile<?php echo esc_attr( $big ); ?>">
				<?php
				if ( has_post_thumbnail( $p ) ) {
					echo get_the_post_thumbnail( $p, $i < 2 ? 'vtech-hero' : 'vtech-card', array( 'class' => 'ind-tile__img', 'alt' => $alt, 'loading' => $i < 3 ? 'eager' : 'lazy' ) ); // phpcs:ignore
				} else {
					printf( '<img class="ind-tile__img" src="%s" alt="%s" loading="%s" decoding="async">', esc_url( $img . $m[0] ), esc_attr( $alt ), $i < 3 ? 'eager' : 'lazy' );
				}
				?>
				<div class="ind-tile__body">
					<span class="ind-tile__kicker"><?php echo esc_html( $m[1] ); ?></span>
					<h2 class="ind-tile__title"><a href="<?php echo esc_url( get_permalink( $p ) ); ?>"><?php echo esc_html( get_the_title( $p ) ); ?></a></h2>
					<p class="ind-tile__text"><?php echo esc_html( $m[2] ); ?></p>
					<?php if ( $m[3] ) : ?><ul class="ind-tile__tags"><?php foreach ( $m[3] as $t ) { echo '<li>' . esc_html( $t ) . '</li>'; } ?></ul><?php endif; ?>
					<span class="ind-tile__cta" aria-hidden="true"><?php esc_html_e( 'See solutions', 'vtech-av' ); ?> &rarr;</span>
				</div>
			</article>
			<?php endforeach; ?>
		</div>
		<?php else : ?>
			<p><?php esc_html_e( 'Industry pages are being added. Please check back soon.', 'vtech-av' ); ?></p>
		<?php endif; ?>
	</div>
</section>

<section class="section section--surface ind-matrix">
	<div class="container">
		<h2 class="svx-head__title svx-center"><?php esc_html_e( 'What every sector gets from VTECH', 'vtech-av' ); ?></h2>
		<div class="ind-matrix__grid">
			<div class="ind-cap"><h3><?php esc_html_e( 'Designed for the room', 'vtech-av' ); ?></h3><p><?php esc_html_e( 'Speaker coverage, screen sizes and acoustics planned from measurements, not guesswork.', 'vtech-av' ); ?></p></div>
			<div class="ind-cap"><h3><?php esc_html_e( 'Simple for your staff', 'vtech-av' ); ?></h3><p><?php esc_html_e( 'One-touch control, clear labelling and on-site training for volunteers and teams.', 'vtech-av' ); ?></p></div>
			<div class="ind-cap"><h3><?php esc_html_e( 'Genuine equipment', 'vtech-av' ); ?></h3><p><?php esc_html_e( 'Proven pro brands such as dB Technologies and Shure, with manufacturer warranties.', 'vtech-av' ); ?></p></div>
			<div class="ind-cap"><h3><?php esc_html_e( 'Support that stays', 'vtech-av' ); ?></h3><p><?php esc_html_e( '12 months of support on every installation and maintenance contracts after that.', 'vtech-av' ); ?></p></div>
		</div>
	</div>
</section>

<section class="section svx-process">
	<div class="container">
		<h2 class="svx-head__title svx-center"><?php esc_html_e( 'How a project runs', 'vtech-av' ); ?></h2>
		<ol class="svx-steps">
			<li><span>1</span><h3><?php esc_html_e( 'Site survey', 'vtech-av' ); ?></h3><p><?php esc_html_e( 'Free visit to understand your space, people and goals.', 'vtech-av' ); ?></p></li>
			<li><span>2</span><h3><?php esc_html_e( 'Design & quote', 'vtech-av' ); ?></h3><p><?php esc_html_e( 'Technical design and fixed, itemised quote in 24 hours.', 'vtech-av' ); ?></p></li>
			<li><span>3</span><h3><?php esc_html_e( 'Install', 'vtech-av' ); ?></h3><p><?php esc_html_e( 'Installed, tuned and tested with minimal disruption.', 'vtech-av' ); ?></p></li>
			<li><span>4</span><h3><?php esc_html_e( 'Train & support', 'vtech-av' ); ?></h3><p><?php esc_html_e( 'Your team trained, with ongoing support and maintenance.', 'vtech-av' ); ?></p></li>
		</ol>
	</div>
</section>

<section class="section section--surface svx-faq">
	<div class="container narrow">
		<h2 class="svx-head__title svx-center"><?php esc_html_e( 'Common questions', 'vtech-av' ); ?></h2>
		<details class="svx-q" open><summary><?php esc_html_e( 'My sector is not listed. Can you still help?', 'vtech-av' ); ?></summary><p><?php esc_html_e( 'Yes. Restaurants, retail, sports venues, NGOs and factories all use the same building blocks. Tell us about your space and we will recommend the right system.', 'vtech-av' ); ?></p></details>
		<details class="svx-q"><summary><?php esc_html_e( 'Can you work while we stay open?', 'vtech-av' ); ?></summary><p><?php esc_html_e( 'Yes. We schedule installations around services, classes, meetings and guests, including evenings and weekends where needed.', 'vtech-av' ); ?></p></details>
		<details class="svx-q"><summary><?php esc_html_e( 'Do you handle tenders and procurement paperwork?', 'vtech-av' ); ?></summary><p><?php esc_html_e( 'We provide itemised quotations, technical specifications and drawings to support your procurement process.', 'vtech-av' ); ?></p></details>
		<details class="svx-q"><summary><?php esc_html_e( 'Do you work outside Nairobi?', 'vtech-av' ); ?></summary><p><?php esc_html_e( 'Yes. We install and support systems across all 47 counties and take on selected projects across East Africa.', 'vtech-av' ); ?></p></details>
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
				<?php if ( $vtc_wa ) : ?>
					<a class="svx-final__alt" href="<?php echo esc_url( vtech_contact_wa_url() ); ?>"<?php echo vtech_wa_attrs( 'consult' ); // phpcs:ignore ?>><?php esc_html_e( 'Chat on WhatsApp', 'vtech-av' ); ?></a>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
<?php get_footer();
