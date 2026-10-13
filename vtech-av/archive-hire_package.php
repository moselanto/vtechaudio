<?php
/**
 * Hire Packages archive (/hire-packages/) — redesigned v5.52.0.
 * Pricing-table layout: guest-count picker, tiered cards (smallest to largest)
 * with a highlighted "Most popular" tier, what's-included strip, event types,
 * booking steps, FAQ and final CTA. Shares svx- components with Services.
 *
 * @package VTECH_AV
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();

$img    = VTECH_URI . '/assets/img/';
$af     = function_exists( 'get_field' );
$vtc_wa = function_exists( 'vtech_wa_attrs' ) && function_exists( 'vtech_contact_wa_url' );

/** Read one package's price, capacity and up to 6 highlights (plain meta first, ACF fallback). */
$vtc_pkg = function ( $id ) use ( $af ) {
	$price = get_post_meta( $id, 'vtech_pkg_price', true );
	if ( '' === (string) $price && $af ) { $price = get_field( 'price', $id ); }
	$price = (float) str_replace( array( ',', ' ', 'KES', 'Ksh' ), '', (string) $price );

	$cap = get_post_meta( $id, 'vtech_pkg_capacity', true );
	if ( '' === (string) $cap && $af ) { $cap = get_field( 'capacity', $id ); }
	$cap_n = (int) preg_replace( '/\D/', '', (string) $cap );

	$items = array();
	$hl    = $af ? get_field( 'highlights', $id ) : array();
	if ( is_array( $hl ) ) {
		foreach ( $hl as $h ) { $t = is_array( $h ) ? ( $h['text'] ?? '' ) : (string) $h; if ( $t ) { $items[] = $t; } }
	}
	if ( ! $items ) {
		$eq = get_post_meta( $id, 'vtech_pkg_equipment', true );
		if ( is_string( $eq ) ) { $eq = preg_split( '/\r\n|\r|\n/', $eq ); }
		if ( is_array( $eq ) ) {
			foreach ( $eq as $e ) { $t = is_array( $e ) ? ( $e['item'] ?? ( $e['text'] ?? '' ) ) : (string) $e; $t = trim( $t ); if ( $t ) { $items[] = $t; } }
		}
	}
	$featured = $af ? (bool) get_field( 'featured', $id ) : false;
	return array( 'price' => $price, 'cap' => (string) $cap, 'cap_n' => $cap_n, 'items' => array_slice( $items, 0, 6 ), 'featured' => $featured );
};

// Collect and sort smallest to largest so the table reads like an international pricing page.
$vtc_list = array();
if ( have_posts() ) {
	while ( have_posts() ) { the_post(); $vtc_list[] = array( 'post' => get_post(), 'd' => $vtc_pkg( get_the_ID() ) ); }
	rewind_posts();
}
usort( $vtc_list, function ( $a, $b ) { return $a['d']['price'] <=> $b['d']['price']; } );

// "Most popular": the package flagged as featured, otherwise the second-largest tier.
$vtc_pop = -1;
foreach ( $vtc_list as $i => $row ) { if ( $row['d']['featured'] ) { $vtc_pop = $i; break; } }
if ( $vtc_pop < 0 && count( $vtc_list ) >= 3 ) { $vtc_pop = count( $vtc_list ) - 2; }
?>
<section class="hp-hero">
	<div class="container">
		<?php if ( function_exists( 'vtech_breadcrumbs' ) ) { vtech_breadcrumbs(); } ?>
		<div class="hp-hero__inner">
			<p class="svx-eyebrow"><?php esc_html_e( 'Sound · LED · Lighting hire', 'vtech-av' ); ?></p>
			<h1 class="hp-hero__title"><?php esc_html_e( 'Event AV hire packages, delivered and run by our crew', 'vtech-av' ); ?></h1>
			<p class="hp-hero__lead"><?php esc_html_e( 'Clear prices in KES with delivery, setup and an on-site technician included. Book a package as it is, customise it, or ask for a tailored quote.', 'vtech-av' ); ?></p>
		</div>

		<?php if ( $vtc_list ) : ?>
		<div class="hp-picker" role="group" aria-label="<?php esc_attr_e( 'How many guests are you expecting?', 'vtech-av' ); ?>">
			<span class="hp-picker__label"><?php esc_html_e( 'How many guests?', 'vtech-av' ); ?></span>
			<?php foreach ( $vtc_list as $i => $row ) : if ( ! $row['d']['cap_n'] ) { continue; } ?>
				<button type="button" class="hp-picker__btn" data-hp-pick="<?php echo esc_attr( $i ); ?>"><?php echo esc_html( sprintf( __( 'Up to %s', 'vtech-av' ), number_format( $row['d']['cap_n'] ) ) ); ?></button>
			<?php endforeach; ?>
			<a class="hp-picker__btn hp-picker__btn--more" href="<?php echo esc_url( home_url( '/equipment-hire-request/' ) ); ?>"><?php esc_html_e( 'Larger / custom', 'vtech-av' ); ?></a>
		</div>
		<?php endif; ?>
	</div>
</section>

<section class="section hp-plans" id="packages">
	<div class="container">
		<?php if ( $vtc_list ) : ?>
		<div class="hp-grid hp-grid--<?php echo esc_attr( min( 4, count( $vtc_list ) ) ); ?>">
			<?php foreach ( $vtc_list as $i => $row ) :
				$p   = $row['post'];
				$d   = $row['d'];
				$id  = $p->ID;
				$pop = ( $i === $vtc_pop );
				$alt = get_the_title( $p ) . ' - sound and AV hire in Nairobi';
			?>
			<article class="hp-card<?php echo $pop ? ' hp-card--pop' : ''; ?>" id="pkg-<?php echo esc_attr( $i ); ?>" data-hp-card="<?php echo esc_attr( $i ); ?>">
				<?php if ( $pop ) : ?><span class="hp-card__flag"><?php esc_html_e( 'Most popular', 'vtech-av' ); ?></span><?php endif; ?>
				<a class="hp-card__media" href="<?php echo esc_url( get_permalink( $p ) ); ?>" tabindex="-1" aria-hidden="true">
					<?php
					if ( has_post_thumbnail( $p ) ) {
						echo get_the_post_thumbnail( $p, 'vtech-card', array( 'alt' => $alt, 'loading' => 'lazy' ) ); // phpcs:ignore
					} else {
						printf( '<img src="%s" alt="%s" loading="lazy" decoding="async">', esc_url( $img . 'og-default.webp' ), esc_attr( $alt ) );
					}
					?>
				</a>
				<div class="hp-card__body">
					<h2 class="hp-card__name"><a href="<?php echo esc_url( get_permalink( $p ) ); ?>"><?php echo esc_html( get_the_title( $p ) ); ?></a></h2>
					<?php if ( $d['cap_n'] ) : ?><p class="hp-card__cap"><?php echo esc_html( sprintf( __( 'For up to %s guests', 'vtech-av' ), number_format( $d['cap_n'] ) ) ); ?></p><?php endif; ?>
					<?php if ( $d['price'] > 0 ) : ?>
						<p class="hp-card__price"><span class="hp-card__from"><?php esc_html_e( 'From', 'vtech-av' ); ?></span> <span class="hp-card__cur">KES</span> <strong><?php echo esc_html( number_format( $d['price'] ) ); ?></strong></p>
					<?php else : ?>
						<p class="hp-card__price"><strong><?php esc_html_e( 'Quote on request', 'vtech-av' ); ?></strong></p>
					<?php endif; ?>
					<p class="hp-card__summary"><?php echo esc_html( wp_trim_words( get_the_excerpt( $p ), 18 ) ); ?></p>
					<?php if ( $d['items'] ) : ?>
						<ul class="hp-card__list"><?php foreach ( $d['items'] as $t ) { echo '<li>' . esc_html( $t ) . '</li>'; } ?></ul>
					<?php endif; ?>
					<div class="hp-card__actions">
						<a class="btn <?php echo $pop ? 'btn--accent' : 'btn--ghost'; ?> hp-card__book" href="<?php echo esc_url( function_exists( 'vtech_whatsapp_book_url' ) ? vtech_whatsapp_book_url( $id ) : add_query_arg( 'package', $p->post_name, home_url( '/equipment-hire-request/' ) ) ); ?>"<?php echo function_exists( 'vtech_wa_pkg_attrs' ) ? vtech_wa_pkg_attrs( $id ) : ''; // phpcs:ignore ?>><?php esc_html_e( 'Check availability', 'vtech-av' ); ?></a>
						<a class="hp-card__more" href="<?php echo esc_url( get_permalink( $p ) ); ?>"><?php esc_html_e( 'Full details & customise', 'vtech-av' ); ?> &rarr;</a>
					</div>
				</div>
			</article>
			<?php endforeach; ?>
		</div>
		<p class="hp-note"><?php esc_html_e( 'Final price is confirmed once we check your date, venue and requirements.', 'vtech-av' ); ?></p>
		<?php else : ?>
			<p><?php esc_html_e( 'Packages are being added. Please check back soon, or', 'vtech-av' ); ?> <a href="<?php echo esc_url( home_url( '/equipment-hire-request/' ) ); ?>"><?php esc_html_e( 'request a custom quote', 'vtech-av' ); ?></a>.</p>
		<?php endif; ?>
	</div>
</section>

<section class="section section--surface hp-incl">
	<div class="container">
		<h2 class="svx-head__title svx-center"><?php esc_html_e( 'Included with every package', 'vtech-av' ); ?></h2>
		<div class="hp-incl__grid">
			<div class="hp-incl__item"><h3><?php esc_html_e( 'Delivery & collection', 'vtech-av' ); ?></h3><p><?php esc_html_e( 'We bring it, and we take it away after your event.', 'vtech-av' ); ?></p></div>
			<div class="hp-incl__item"><h3><?php esc_html_e( 'Professional setup', 'vtech-av' ); ?></h3><p><?php esc_html_e( 'Rigged, wired and sound-checked before guests arrive.', 'vtech-av' ); ?></p></div>
			<div class="hp-incl__item"><h3><?php esc_html_e( 'On-site technician', 'vtech-av' ); ?></h3><p><?php esc_html_e( 'An engineer runs the system for the whole event.', 'vtech-av' ); ?></p></div>
			<div class="hp-incl__item"><h3><?php esc_html_e( 'Tested equipment', 'vtech-av' ); ?></h3><p><?php esc_html_e( 'Maintained pro gear, checked before every booking.', 'vtech-av' ); ?></p></div>
		</div>
	</div>
</section>

<section class="section hp-uses">
	<div class="container">
		<h2 class="svx-head__title svx-center"><?php esc_html_e( 'Built for events like yours', 'vtech-av' ); ?></h2>
		<ul class="hp-uses__list">
			<li><?php esc_html_e( 'Weddings & receptions', 'vtech-av' ); ?></li>
			<li><?php esc_html_e( 'Corporate launches & AGMs', 'vtech-av' ); ?></li>
			<li><?php esc_html_e( 'Conferences & seminars', 'vtech-av' ); ?></li>
			<li><?php esc_html_e( 'Concerts & festivals', 'vtech-av' ); ?></li>
			<li><?php esc_html_e( 'Church crusades & conventions', 'vtech-av' ); ?></li>
			<li><?php esc_html_e( 'School & graduation events', 'vtech-av' ); ?></li>
			<li><?php esc_html_e( 'Private parties', 'vtech-av' ); ?></li>
			<li><?php esc_html_e( 'Outdoor & public events', 'vtech-av' ); ?></li>
		</ul>
	</div>
</section>

<section class="section section--surface svx-process">
	<div class="container">
		<h2 class="svx-head__title svx-center"><?php esc_html_e( 'Booking takes minutes', 'vtech-av' ); ?></h2>
		<ol class="svx-steps">
			<li><span>1</span><h3><?php esc_html_e( 'Pick a package', 'vtech-av' ); ?></h3><p><?php esc_html_e( 'Choose by guest numbers, or ask us to tailor one.', 'vtech-av' ); ?></p></li>
			<li><span>2</span><h3><?php esc_html_e( 'Check availability', 'vtech-av' ); ?></h3><p><?php esc_html_e( 'Send your date and venue on WhatsApp or the form.', 'vtech-av' ); ?></p></li>
			<li><span>3</span><h3><?php esc_html_e( 'Confirm', 'vtech-av' ); ?></h3><p><?php esc_html_e( 'We confirm the booking and plan setup with your venue.', 'vtech-av' ); ?></p></li>
			<li><span>4</span><h3><?php esc_html_e( 'Enjoy the event', 'vtech-av' ); ?></h3><p><?php esc_html_e( 'Our crew sets up, runs the show and packs down.', 'vtech-av' ); ?></p></li>
		</ol>
	</div>
</section>

<section class="section svx-faq">
	<div class="container narrow">
		<h2 class="svx-head__title svx-center"><?php esc_html_e( 'Hire questions', 'vtech-av' ); ?></h2>
		<details class="svx-q" open><summary><?php esc_html_e( 'Can I change what is in a package?', 'vtech-av' ); ?></summary><p><?php esc_html_e( 'Yes. Add microphones, screens, lighting or extra speakers, or swap items out. Open a package and choose customise, or tell us on WhatsApp.', 'vtech-av' ); ?></p></details>
		<details class="svx-q"><summary><?php esc_html_e( 'Do you deliver outside Nairobi?', 'vtech-av' ); ?></summary><p><?php esc_html_e( 'Yes, across Kenya. Delivery outside Nairobi is quoted based on the location.', 'vtech-av' ); ?></p></details>
		<details class="svx-q"><summary><?php esc_html_e( 'How early should I book?', 'vtech-av' ); ?></summary><p><?php esc_html_e( 'As early as you can, especially for weekends and December. Send your date and we will confirm availability quickly.', 'vtech-av' ); ?></p></details>
		<details class="svx-q"><summary><?php esc_html_e( 'Which package do I need?', 'vtech-av' ); ?></summary><p><?php esc_html_e( 'Start with your guest numbers and whether the event is indoors or outdoors. If you are unsure, share your venue and programme and we will recommend the right setup.', 'vtech-av' ); ?></p></details>
	</div>
</section>

<section class="section svx-final">
	<div class="container">
		<div class="svx-final__box">
			<div class="svx-final__copy">
				<h2><?php esc_html_e( 'Need something bigger or different?', 'vtech-av' ); ?></h2>
				<p><?php esc_html_e( 'Tell us your date, venue and guest numbers and we will build a tailored package with a same-day quote.', 'vtech-av' ); ?></p>
			</div>
			<div class="svx-final__actions">
				<a class="btn btn--accent btn--lg" href="<?php echo esc_url( home_url( '/equipment-hire-request/' ) ); ?>"><?php esc_html_e( 'Request a hire quote', 'vtech-av' ); ?></a>
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

<script>
(function(){
  var btns=document.querySelectorAll('[data-hp-pick]');
  btns.forEach(function(b){b.addEventListener('click',function(){
    var i=b.getAttribute('data-hp-pick');
    btns.forEach(function(x){x.classList.toggle('is-active',x===b);});
    document.querySelectorAll('[data-hp-card]').forEach(function(c){c.classList.toggle('is-match',c.getAttribute('data-hp-card')===i);});
    var t=document.getElementById('pkg-'+i);
    if(t){t.scrollIntoView({behavior:window.matchMedia('(prefers-reduced-motion: reduce)').matches?'auto':'smooth',block:'center'});}
  });});
})();
</script>
<?php get_footer();
