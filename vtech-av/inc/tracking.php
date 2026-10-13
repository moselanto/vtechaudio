<?php
/**
 * Analytics + lead tracking (v5.49.0).
 *
 * Customize > VTECH Theme Options > Analytics & Tracking > "Google Analytics / GTM ID":
 *  - GTM-XXXXXXX  -> Google Tag Manager container (recommended).
 *  - G-XXXXXXXXXX -> GA4 directly via gtag.js.
 * Logged-in administrators and editors are not tracked, so office visits don't skew reports.
 *
 * Events pushed to the dataLayer (and sent straight to GA4 in gtag mode):
 *  - phone_click     any tel: link
 *  - whatsapp_click  any WhatsApp link or WhatsApp popup button
 *  - generate_lead   consultation / hire / quote / contact form sent, or WhatsApp popup submitted
 *
 * @package vtech-av
 */

defined( 'ABSPATH' ) || exit;

function vtech_tracking_id() {
	$id = strtoupper( trim( (string) get_theme_mod( 'vtech_gtag', '' ) ) );
	return preg_match( '/^(GTM-[A-Z0-9]{4,12}|G-[A-Z0-9]{6,14})$/', $id ) ? $id : '';
}

function vtech_tracking_enabled() {
	if ( is_admin() || is_customize_preview() ) { return false; }
	if ( is_user_logged_in() && current_user_can( 'edit_others_posts' ) ) { return false; }
	return '' !== vtech_tracking_id();
}

add_action( 'wp_head', function () {
	if ( ! vtech_tracking_enabled() ) { return; }
	$id   = vtech_tracking_id();
	$mode = 0 === strpos( $id, 'GTM-' ) ? 'gtm' : 'gtag';
	?>
<script>
window.dataLayer=window.dataLayer||[];
window.vtechTrack=function(n,p){p=p||{};try{var o={event:n};for(var k in p){o[k]=p[k];}window.dataLayer.push(o);if(<?php echo 'gtag' === $mode ? 'true' : 'false'; ?>&&window.gtag){window.gtag('event',n,p);}}catch(e){}};
</script>
<?php if ( 'gtm' === $mode ) : ?>
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);})(window,document,'script','dataLayer','<?php echo esc_js( $id ); ?>');</script>
<!-- End Google Tag Manager -->
<?php else : ?>
<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo esc_attr( $id ); ?>"></script>
<script>function gtag(){dataLayer.push(arguments);}gtag('js',new Date());gtag('config','<?php echo esc_js( $id ); ?>');</script>
<?php endif; ?>
	<?php
}, 1 );

add_action( 'wp_body_open', function () {
	if ( ! vtech_tracking_enabled() ) { return; }
	$id = vtech_tracking_id();
	if ( 0 !== strpos( $id, 'GTM-' ) ) { return; }
	printf( '<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=%s" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>' . "\n", esc_attr( $id ) );
}, 1 );

add_action( 'wp_footer', function () {
	if ( ! vtech_tracking_enabled() ) { return; }
	?>
<script>
(function(){
  if(!window.vtechTrack)return;
  function where(el){var s=el.closest('header,.site-header')?'header':el.closest('.mobile-nav')?'menu':el.closest('.action-bar,.float-btn')?'sticky_bar':el.closest('footer,.site-footer')?'footer':'page';return s;}
  document.addEventListener('click',function(e){
    var a=e.target.closest&&e.target.closest('a,button');if(!a)return;
    var href=a.getAttribute('href')||'';
    if(/^tel:/i.test(href)){window.vtechTrack('phone_click',{link_url:href,click_location:where(a),page_path:location.pathname});return;}
    if(a.hasAttribute('data-wa-order')||/wa\.me|api\.whatsapp\.com|whatsapp:\/\//i.test(href)){
      if(a.closest('#wa-modal'))return;
      window.vtechTrack('whatsapp_click',{lead_type:a.getAttribute('data-wa-order')||'direct',item:a.getAttribute('data-wa-item')||'',click_location:where(a),page_path:location.pathname});
    }
  },true);
  var sent=/[?&]vtech_sent=1/.test(location.search)||document.querySelector('.cf-success');
  if(sent){
    var p=location.pathname,f=/consult/.test(p)?'consultation':/hire/.test(p)?'hire_request':/quote/.test(p)?'quote':/contact/.test(p)?'contact':'form';
    try{var key='vtech_lead_'+p+location.search;if(sessionStorage.getItem(key))return;sessionStorage.setItem(key,'1');}catch(x){}
    window.vtechTrack('generate_lead',{lead_source:f,page_path:p});
  }
})();
</script>
	<?php
}, 99 );
