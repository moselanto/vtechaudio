<?php
/**
 * Services archive (/services/) — service-led copy and CTAs (v5.38.0).
 * Replaces the blog-style fallback from index.php.
 *
 * @package VTECH_AV
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();
$img = VTECH_URI . '/assets/img/';
?>
<section class="svc-hero">
	<div class="container">
		<?php if ( function_exists( 'vtech_breadcrumbs' ) ) { vtech_breadcrumbs(); } ?>
		<h1 class="svc-hero__title"><?php esc_html_e( 'Audio Visual Services in Kenya', 'vtech-av' ); ?></h1>
		<p class="svc-hero__tagline"><?php esc_html_e( 'PA and sound systems, LED screens, conference and boardroom AV, stage lighting and acoustics: designed, supplied, installed and supported by one team in Nairobi, serving all 47 counties.', 'vtech-av' ); ?></p>
		<div class="svc-hero__cta"><a class="btn btn--accent btn--lg" href="<?php echo esc_url( home_url( '/book-a-consultation/' ) ); ?>"><?php esc_html_e( 'Request a Free Site Survey', 'vtech-av' ); ?></a></div>
	</div>
</section>

<section class="section section--surface">
	<div class="container">
		<?php if ( have_posts() ) : ?>
		<div class="card-grid card-grid--3">
			<?php while ( have_posts() ) : the_post(); ?>
				<a class="card card--service" href="<?php the_permalink(); ?>">
					<figure class="card__media">
						<?php if ( has_post_thumbnail() ) { the_post_thumbnail( 'vtech-card', array( 'loading' => 'lazy', 'alt' => get_the_title() . ' in Kenya' ) ); }
						else { echo '<img src="' . esc_url( $img . 'og-default.webp' ) . '" alt="' . esc_attr( get_the_title() ) . ' in Kenya" loading="lazy" decoding="async">'; } ?>
					</figure>
					<div class="card__body">
						<h2 class="card__title"><?php the_title(); ?></h2>
						<p class="card__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 22 ) ); ?></p>
						<span class="card__link"><?php esc_html_e( 'View service', 'vtech-av' ); ?></span>
					</div>
				</a>
			<?php endwhile; ?>
		</div>
		<?php endif; ?>
	</div>
</section>

<section class="section">
	<div class="container narrow">
		<h2 class="section__title"><?php esc_html_e( 'One integrator from design to support', 'vtech-av' ); ?></h2>
		<p><?php esc_html_e( 'Every project starts with a free site survey. We design the system around your room, audience and budget, supply proven pro audio and video brands including dB Technologies speakers and Shure microphones, install and commission everything, train your team, and support you with a 12-month warranty window and maintenance contracts.', 'vtech-av' ); ?></p>
		<p><?php esc_html_e( 'Need equipment for a single event instead?', 'vtech-av' ); ?> <a href="<?php echo esc_url( home_url( '/equipment-hire/' ) ); ?>"><?php esc_html_e( 'See sound, PA, LED and lighting hire in Nairobi', 'vtech-av' ); ?></a>.</p>
	</div>
</section>

<section class="section"><div class="container"><div class="cta-band section"><div class="cta-band__inner">
	<h2><?php esc_html_e( 'Planning an AV project in Kenya?', 'vtech-av' ); ?></h2>
	<p><?php esc_html_e( 'Book a free site survey and get a fixed quote within 24 hours.', 'vtech-av' ); ?></p>
	<a class="btn btn--accent btn--lg" href="<?php echo esc_url( home_url( '/book-a-consultation/' ) ); ?>"><?php esc_html_e( 'Book a Consultation', 'vtech-av' ); ?></a>
</div></div></div></section>
<?php get_footer();
