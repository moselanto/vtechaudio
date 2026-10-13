<?php
/**
 * Footer: NAP, quick links, services, visit/map, social, floating CTAs.
 *
 * @package VTECH_AV
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
?>
</main><!-- #main -->

<footer class="site-footer">
	<div class="container site-footer__grid">

		<!-- Col 1: Brand + NAP -->
		<div class="site-footer__col site-footer__brand">
			<a class="brand-text brand-text--light" href="<?php echo esc_url( home_url( '/' ) ); ?>">VTECH <span>Audio Visual</span></a>
			<p><?php echo esc_html( vtech_opt( 'vtech_footer_blurb', "Kenya's premium audio-visual integrator. Sound, LED, lighting, conference & PA systems, acoustics and digital signage designed, installed and supported across Kenya and East Africa." ) ); ?></p>
			<address>
				<strong><?php echo esc_html( vtech_contact( 'company' ) ); ?></strong><br>
				<?php echo esc_html( vtech_contact( 'address' ) ); ?><br>
				<a href="tel:<?php echo esc_attr( vtech_contact_tel() ); ?>"><?php echo esc_html( vtech_contact( 'phone' ) ); ?></a><br>
				<a href="mailto:<?php echo esc_attr( vtech_contact( 'email' ) ); ?>"><?php echo esc_html( vtech_contact( 'email' ) ); ?></a><br>
				<span><?php echo esc_html( vtech_contact( 'hours' ) ); ?></span>
			</address>
			<div class="footer-social" aria-label="<?php esc_attr_e( 'Social media', 'vtech-av' ); ?>">
				<?php if ( function_exists( 'vtech_social_links' ) ) : foreach ( vtech_social_links() as $name => $url ) : if ( ! $url ) { continue; } ?>
					<a href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener" aria-label="<?php echo esc_attr( ucfirst( $name ) ); ?>"><svg viewBox="0 0 24 24" aria-hidden="true"><?php echo vtech_social_icon( $name ); // phpcs:ignore ?></svg></a>
				<?php endforeach; endif; ?>
			</div>
		</div>

		<!-- Col 2: Quick links -->
		<div class="site-footer__col">
			<h3 class="footer-widget__title"><?php esc_html_e( 'Company', 'vtech-av' ); ?></h3>
			<?php if ( has_nav_menu( 'footer_company' ) ) {
				wp_nav_menu( array( 'theme_location' => 'footer_company', 'container' => false, 'menu_class' => 'footer-menu', 'fallback_cb' => false, 'depth' => 1 ) );
			} else { ?>
			<ul class="footer-menu">
				<li><a href="<?php echo esc_url( home_url( '/about/' ) ); ?>"><?php esc_html_e( 'About Us', 'vtech-av' ); ?></a></li>
				<li><a href="<?php echo esc_url( get_post_type_archive_link( 'project' ) ?: home_url( '/projects/' ) ); ?>"><?php esc_html_e( 'Projects', 'vtech-av' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/equipment-hire/' ) ); ?>"><?php esc_html_e( 'Equipment Hire', 'vtech-av' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/faq/' ) ); ?>"><?php esc_html_e( 'FAQ', 'vtech-av' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/book-a-consultation/' ) ); ?>"><?php esc_html_e( 'Contact', 'vtech-av' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/privacy-policy/' ) ); ?>"><?php esc_html_e( 'Privacy Policy', 'vtech-av' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/terms/' ) ); ?>"><?php esc_html_e( 'Terms & Conditions', 'vtech-av' ); ?></a></li>
			</ul>
			<?php } ?>
		</div>

		<!-- Col 3: Services -->
		<div class="site-footer__col">
			<h3 class="footer-widget__title"><?php esc_html_e( 'Services', 'vtech-av' ); ?></h3>
			<?php
			$has_footer_menu = has_nav_menu( 'footer' );
			if ( $has_footer_menu ) {
				wp_nav_menu( array( 'theme_location' => 'footer', 'container' => false, 'menu_class' => 'footer-menu', 'fallback_cb' => false, 'depth' => 1 ) );
			} else {
				$svc = new WP_Query( array( 'post_type' => 'service', 'posts_per_page' => 6, 'orderby' => 'menu_order', 'order' => 'ASC' ) );
				echo '<ul class="footer-menu">';
				if ( $svc->have_posts() ) {
					while ( $svc->have_posts() ) { $svc->the_post(); echo '<li><a href="' . esc_url( get_permalink() ) . '">' . esc_html( get_the_title() ) . '</a></li>'; }
					wp_reset_postdata();
				} else {
					foreach ( array( 'Sound Systems' => '/services/professional-sound-systems/', 'LED Screens' => '/services/led-screens-video-walls/', 'Conference Systems' => '/services/conference-boardroom-av/', 'Stage Lighting' => '/services/stage-architectural-lighting/', 'Acoustic Solutions' => '/services/acoustic-design-soundproofing/', 'Digital Signage' => '/services/digital-signage/' ) as $t => $u ) {
						echo '<li><a href="' . esc_url( home_url( $u ) ) . '">' . esc_html( $t ) . '</a></li>';
					}
				}
				echo '</ul>';
			}
			?>
		</div>

		<!-- Col 4: Visit us + map -->
		<div class="site-footer__col">
			<h3 class="footer-widget__title"><?php esc_html_e( 'Visit Us', 'vtech-av' ); ?></h3>
			<?php
			$map = vtech_contact_map_embed();
			if ( $map ) : ?>
				<div class="footer-map map-facade" data-map-src="<?php echo esc_url( $map ); ?>" data-map-title="<?php esc_attr_e( 'VTECH office location map', 'vtech-av' ); ?>">
					<button type="button" class="map-facade__btn" aria-label="<?php esc_attr_e( 'Show office location map', 'vtech-av' ); ?>"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2a7 7 0 0 0-7 7c0 5.2 7 13 7 13s7-7.8 7-13a7 7 0 0 0-7-7zm0 9.5A2.5 2.5 0 1 1 12 6.5a2.5 2.5 0 0 1 0 5z"/></svg><span><?php esc_html_e( 'Mpaka Plaza, Westlands', 'vtech-av' ); ?></span><small><?php esc_html_e( 'Tap to view map', 'vtech-av' ); ?></small></button>
				</div>
			<?php endif; ?>
			<a class="btn btn--accent btn--block" style="margin-top:1rem" href="<?php echo esc_url( home_url( '/book-a-consultation/' ) ); ?>"><?php esc_html_e( 'Book a Consultation', 'vtech-av' ); ?></a>
		</div>

	</div>
	<div class="site-footer__bottom container">
		<p>&copy; <?php echo esc_html( date_i18n( 'Y' ) ); ?> <?php echo esc_html( vtech_opt( 'vtech_footer_copyright', vtech_contact( 'company' ) . '. All rights reserved.' ) ); ?></p>
		<p class="site-footer__legal"><a href="<?php echo esc_url( home_url( '/privacy-policy/' ) ); ?>"><?php esc_html_e( 'Privacy', 'vtech-av' ); ?></a> &middot; <a href="<?php echo esc_url( home_url( '/terms/' ) ); ?>"><?php esc_html_e( 'Terms', 'vtech-av' ); ?></a></p>
	</div>
</footer>

<?php // Floating conversion elements (desktop) + quick-action bar (mobile).
$wa_url    = vtech_contact_wa_url( __( 'Hello VTECH, I would like a quote for an AV project.', 'vtech-av' ) );
$show_wa   = $wa_url && vtech_opt( 'vtech_show_whatsapp', true );
$show_call = (bool) vtech_opt( 'vtech_show_call', true );
$ico_wa    = '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2a10 10 0 0 0-8.6 15.1L2 22l5-1.3A10 10 0 1 0 12 2zm0 18.2a8.2 8.2 0 0 1-4.2-1.1l-.3-.2-3 .8.8-2.9-.2-.3A8.2 8.2 0 1 1 12 20.2zm4.5-6.1c-.2-.1-1.5-.7-1.7-.8-.2-.1-.4-.1-.6.1l-.8 1c-.1.2-.3.2-.5.1a6.7 6.7 0 0 1-3.3-2.9c-.2-.4.2-.4.7-1.3.1-.2 0-.3 0-.4l-.8-1.8c-.2-.5-.4-.4-.6-.4h-.5a1 1 0 0 0-.7.3 3 3 0 0 0-.9 2.2 5.2 5.2 0 0 0 1.1 2.7 11.8 11.8 0 0 0 4.5 4c1.7.7 2.3.8 3.2.6a2.7 2.7 0 0 0 1.8-1.2 2.2 2.2 0 0 0 .2-1.3c-.1-.1-.3-.2-.5-.3z"/></svg>';
$ico_call  = '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6.6 10.8a15.1 15.1 0 0 0 6.6 6.6l2.2-2.2a1 1 0 0 1 1-.2 11.4 11.4 0 0 0 3.6.6 1 1 0 0 1 1 1V20a1 1 0 0 1-1 1A17 17 0 0 1 3 4a1 1 0 0 1 1-1h3.5a1 1 0 0 1 1 1c0 1.2.2 2.5.6 3.6a1 1 0 0 1-.3 1z"/></svg>';
$ico_quote = '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M19 3h-1V1h-2v2H8V1H6v2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2zm0 16H5V8h14v11zM7 10h5v5H7z"/></svg>';

if ( $show_wa ) : ?>
	<a class="float-btn float-btn--wa" href="<?php echo esc_url( $wa_url ); ?>" target="_blank" rel="noopener" aria-label="<?php esc_attr_e( 'Chat on WhatsApp', 'vtech-av' ); ?>"><?php echo $ico_wa; // phpcs:ignore ?><span><?php esc_html_e( 'WhatsApp', 'vtech-av' ); ?></span></a>
<?php endif;
if ( $show_call ) : ?>
	<a class="float-btn float-btn--call" href="tel:<?php echo esc_attr( vtech_contact_tel() ); ?>" aria-label="<?php esc_attr_e( 'Call VTECH', 'vtech-av' ); ?>"><?php echo $ico_call; // phpcs:ignore ?><span><?php esc_html_e( 'Call us', 'vtech-av' ); ?></span></a>
<?php endif;

if ( vtech_opt( 'vtech_show_sticky_cta', true ) ) : ?>
	<div class="sticky-cta" data-sticky-cta hidden>
		<span><?php esc_html_e( 'Ready to upgrade your AV setup?', 'vtech-av' ); ?></span>
		<a class="btn btn--accent" href="<?php echo esc_url( home_url( '/book-a-consultation/' ) ); ?>"><?php esc_html_e( 'Book a Consultation', 'vtech-av' ); ?></a>
	</div>
<?php endif; ?>

<nav class="action-bar" aria-label="<?php esc_attr_e( 'Quick contact', 'vtech-av' ); ?>">
	<?php if ( $show_call ) : ?>
		<a class="action-bar__btn" href="tel:<?php echo esc_attr( vtech_contact_tel() ); ?>"><?php echo $ico_call; // phpcs:ignore ?><span><?php esc_html_e( 'Call', 'vtech-av' ); ?></span></a>
	<?php endif; ?>
	<?php if ( $show_wa ) : ?>
		<a class="action-bar__btn action-bar__btn--wa" href="<?php echo esc_url( $wa_url ); ?>" target="_blank" rel="noopener"><?php echo $ico_wa; // phpcs:ignore ?><span><?php esc_html_e( 'WhatsApp', 'vtech-av' ); ?></span></a>
	<?php endif; ?>
	<a class="action-bar__btn action-bar__btn--primary" href="<?php echo esc_url( home_url( '/book-a-consultation/' ) ); ?>"><?php echo $ico_quote; // phpcs:ignore ?><span><?php esc_html_e( 'Get a quote', 'vtech-av' ); ?></span></a>
</nav>

<?php wp_footer(); ?>
</body>
</html>
