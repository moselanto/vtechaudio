<?php
/**
 * Branded WordPress login screen (v5.45.0, redesigned 5.57.0, fixed 5.57.2: styles print after core login.css).
 *
 * Replaces the generic WordPress logo and grey layout on wp-login.php with
 * the VTECH logo, brand colours and a split layout. The logo follows the
 * site logo set in Customize > Site Identity, so changing it there updates
 * the login screen too.
 *
 * @package VTECH_AV
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

/** Logo URL: Site Identity logo first, theme logo as fallback. */
function vtech_login_logo_url() {
	$id = (int) get_theme_mod( 'custom_logo' );
	if ( $id ) {
		$src = wp_get_attachment_image_src( $id, 'full' );
		if ( $src && ! empty( $src[0] ) ) { return $src[0]; }
	}
	return VTECH_URI . '/assets/img/logo.png';
}

add_filter( 'login_headerurl', function () { return home_url( '/' ); } );
add_filter( 'login_headertext', function () { return vtech_contact( 'company' ); } );
add_filter( 'login_title', function ( $title ) { return __( 'Sign in', 'vtech-av' ) . ' | ' . vtech_contact( 'company' ); } );

add_action( 'login_head', function () {
	$logo  = esc_url( vtech_login_logo_url() );
	$side  = esc_url( VTECH_URI . '/assets/img/services/sound-systems.webp' );
	$fonts = esc_url( VTECH_URI . '/assets/fonts/' );
	?>
<style id="vtech-login">
@font-face{font-family:Manrope;font-weight:200 800;font-display:swap;src:url(<?php echo $fonts; ?>manrope-var.woff2) format("woff2")}
@font-face{font-family:Inter;font-weight:100 900;font-display:swap;src:url(<?php echo $fonts; ?>inter-var.woff2) format("woff2")}
:root{--v-p:#81007F;--v-pd:#5C005A;--v-ink:#16111a;--v-muted:#5b6472;--v-line:#e7e3ea;--v-tint:#f6eaf6}
html{height:auto;min-height:100%}body.login{position:relative}
body.login{margin:0;min-height:100vh;font-family:Inter,system-ui,sans-serif;color:var(--v-ink);background:#faf8fb;display:flex;flex-direction:column;justify-content:center;padding-left:46vw;box-sizing:border-box}
/* Brand panel (left) */
body.login .vl-side{position:absolute;top:0;left:0;bottom:0;min-height:100vh;width:46vw;color:#fff;display:flex;flex-direction:column;justify-content:space-between;padding:3rem 3.5rem;box-sizing:border-box;overflow:hidden;background:linear-gradient(160deg,rgba(22,8,24,.92) 0%,rgba(61,0,60,.86) 50%,rgba(129,0,127,.72) 100%),url(<?php echo $side; ?>) center/cover no-repeat}
body.login .vl-side::after{content:"";position:absolute;width:520px;height:520px;right:-180px;bottom:-200px;border-radius:50%;background:radial-gradient(circle,rgba(255,255,255,.12),transparent 70%)}
body.login .vl-brand{font-family:Manrope,Inter,sans-serif;font-weight:800;font-size:1.5rem;letter-spacing:-.01em;color:#fff;text-decoration:none}
body.login .vl-brand span{font-weight:600;color:#f6c6f4}
body.login .vl-copy{position:relative;z-index:1;max-width:30rem}
body.login .vl-eyebrow{display:inline-block;margin:0 0 1rem;padding:.35rem .8rem;border:1px solid rgba(255,255,255,.28);border-radius:999px;font-size:.72rem;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#f6dcf5;background:rgba(255,255,255,.06)}
body.login .vl-title{font-family:Manrope,Inter,sans-serif;font-weight:800;font-size:clamp(1.9rem,3vw,2.8rem);line-height:1.08;letter-spacing:-.02em;margin:0 0 1rem}
body.login .vl-lead{margin:0 0 1.5rem;color:rgba(255,255,255,.82);line-height:1.65}
body.login .vl-list{list-style:none;margin:0;padding:0;display:grid;gap:.6rem}
body.login .vl-list li{position:relative;padding-left:1.8rem;color:rgba(255,255,255,.9);font-size:.95rem}
body.login .vl-list li::before{content:"";position:absolute;left:0;top:.15rem;width:1.15rem;height:1.15rem;border-radius:50%;background:rgba(255,255,255,.18) url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Cpath fill='%23fff' d='M9 16.2 4.8 12l-1.4 1.4L9 19 21 7l-1.4-1.4z'/%3E%3C/svg%3E") center/70% no-repeat}
body.login .vl-foot{position:relative;z-index:1;font-size:.82rem;color:rgba(255,255,255,.65)}
body.login .vl-foot a{color:rgba(255,255,255,.85);text-decoration:none}
/* Form (right) */
body.login #login{width:100%;max-width:420px;padding:2rem 1.5rem;margin:0 auto;position:relative;z-index:1;box-sizing:border-box}
body.login #login h1{margin:0 0 1.75rem}
body.login #login h1 a{background:url(<?php echo $logo; ?>) left center/contain no-repeat;width:220px;height:64px;margin:0;text-indent:-9999px;overflow:hidden;display:block;outline:0}
body.login #login h1 a:focus-visible{box-shadow:0 0 0 4px rgba(129,0,127,.25);border-radius:8px}
body.login form{position:relative;margin:0;padding:4.6rem 1.75rem 1.6rem;border:1px solid var(--v-line);border-radius:22px;background:#fff;box-shadow:0 24px 60px rgba(40,0,40,.08)}
body.login form::before{content:"Welcome back";position:absolute;top:1.6rem;left:1.75rem;font-family:Manrope,Inter,sans-serif;font-weight:800;font-size:1.45rem;letter-spacing:-.02em;color:var(--v-ink)}
body.login form::after{content:"Sign in to manage the VTECH website";position:absolute;top:3.4rem;left:1.75rem;font-size:.88rem;color:var(--v-muted)}
body.login #lostpasswordform::before{content:"Reset your password"}
body.login #lostpasswordform::after{content:"Enter your username or email and we will send a reset link"}
body.login #registerform::before{content:"Create an account"}
body.login #registerform::after{content:""}
body.login label{font-family:Manrope,Inter,sans-serif;font-weight:600;font-size:.86rem;color:var(--v-ink)}
body.login form .input,body.login input[type=text],body.login input[type=password],body.login input[type=email]{min-height:50px;margin:.4rem 0 1.15rem;padding:.6rem 1rem;font-size:16px;border:1px solid var(--v-line);border-radius:12px;background:#fbfafc;box-shadow:none;transition:border-color .2s,box-shadow .2s,background .2s}
body.login form .input:focus,body.login input[type=text]:focus,body.login input[type=password]:focus,body.login input[type=email]:focus{border-color:var(--v-p);background:#fff;box-shadow:0 0 0 4px rgba(129,0,127,.12);outline:0}
body.login .button.wp-hide-pw{min-height:50px;margin-top:.4rem;color:var(--v-p)}
body.login .button.wp-hide-pw:focus{box-shadow:0 0 0 3px rgba(129,0,127,.25);border-color:transparent}
body.login .forgetmenot{margin:.25rem 0 0;float:none}
body.login .forgetmenot label{font-weight:500;color:var(--v-muted)}
body.login input[type=checkbox]{border-color:#c9bfcc;border-radius:5px}
body.login input[type=checkbox]:checked::before{filter:hue-rotate(80deg) saturate(2)}
body.login input[type=checkbox]:focus{box-shadow:0 0 0 3px rgba(129,0,127,.2);border-color:var(--v-p)}
body.login .submit{margin-top:1.25rem;float:none;clear:both}
body.login .button-primary,body.login #wp-submit{float:none;width:100%;min-height:52px;font-family:Manrope,Inter,sans-serif;font-weight:700;font-size:1rem;border:0;border-radius:12px;background:linear-gradient(135deg,#81007F,#5C005A);box-shadow:0 12px 26px rgba(129,0,127,.28);text-shadow:none;transition:transform .15s,box-shadow .2s}
body.login .button-primary:hover,body.login .button-primary:focus{background:linear-gradient(135deg,#8f0a8d,#4a0049);transform:translateY(-1px);box-shadow:0 16px 30px rgba(129,0,127,.34)}
body.login .button-primary:focus{outline:3px solid rgba(129,0,127,.25);outline-offset:2px}
body.login #nav,body.login #backtoblog{margin:1.1rem 0 0;padding:0;text-align:center;font-size:.9rem}
body.login #nav a,body.login #backtoblog a,body.login .privacy-policy-page-link a{color:var(--v-p);text-decoration:none;font-weight:600}
body.login #nav a:hover,body.login #backtoblog a:hover,body.login .privacy-policy-page-link a:hover{text-decoration:underline}
body.login .privacy-policy-page-link{margin:1.25rem 0 0}
body.login .message,body.login .notice,body.login #login_error{margin:0 0 1rem;border:1px solid var(--v-line);border-left:4px solid var(--v-p);border-radius:12px;background:#fff;box-shadow:none;padding:.85rem 1rem}
body.login #login_error{border-left-color:#c62828}
body.login .language-switcher{display:none}
body.login .vtech-login-foot{position:relative;z-index:1;margin:1.25rem auto 1.5rem;text-align:center;font-size:.8rem;color:var(--v-muted)}
body.login .vtech-login-foot a{color:var(--v-p);text-decoration:none}
@media(max-width:900px){
  body.login{padding-left:0;justify-content:flex-start}
  body.login .vl-side{position:relative;width:auto;padding:1.5rem 1.5rem 1.75rem;min-height:0}
  body.login .vl-side .vl-lead,body.login .vl-side .vl-list,body.login .vl-side .vl-foot,body.login .vl-side .vl-eyebrow{display:none}
  body.login .vl-title{font-size:1.5rem;margin:.9rem 0 0}
  body.login .vl-side::after{display:none}
  body.login #login{padding:1.5rem 1.25rem 0}
  body.login #login h1{margin-bottom:1.25rem}
  body.login #login h1 a{width:180px;height:52px}
}
@media(max-width:480px){body.login form{padding:4.4rem 1.25rem 1.3rem}body.login form::before,body.login form::after{left:1.25rem}}
@media(prefers-reduced-motion:reduce){body.login .button-primary{transition:none}}
</style>
	<?php
}, 99 );

/** Brand panel shown beside (desktop) or above (phone) the sign-in card. */
add_action( 'login_header', function () {
	?>
<aside class="vl-side" aria-label="<?php esc_attr_e( 'About VTECH', 'vtech-av' ); ?>">
	<a class="vl-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>">VTECH <span>Audio Visual</span></a>
	<div class="vl-copy">
		<p class="vl-eyebrow"><?php esc_html_e( 'Website admin', 'vtech-av' ); ?></p>
		<h2 class="vl-title"><?php esc_html_e( 'Sound, LED and conference AV, installed right.', 'vtech-av' ); ?></h2>
		<p class="vl-lead"><?php esc_html_e( 'Manage services, projects, hire packages, articles and enquiries for vtechaudio.co.ke.', 'vtech-av' ); ?></p>
		<ul class="vl-list">
			<li><?php esc_html_e( 'Leads from forms and WhatsApp in one place', 'vtech-av' ); ?></li>
			<li><?php esc_html_e( 'Edit pages, packages and prices', 'vtech-av' ); ?></li>
			<li><?php esc_html_e( 'Publish articles with SEO built in', 'vtech-av' ); ?></li>
		</ul>
	</div>
	<p class="vl-foot"><?php echo esc_html( vtech_contact( 'company' ) ); ?> &middot; <a href="tel:<?php echo esc_attr( vtech_contact_tel() ); ?>"><?php echo esc_html( vtech_contact( 'phone' ) ); ?></a></p>
</aside>
	<?php
} );

/** Small footer with phone + website under the login card. */
add_action( 'login_footer', function () {
	echo '<p class="vtech-login-foot">' . esc_html( vtech_contact( 'company' ) ) . ' &middot; <a href="tel:' . esc_attr( vtech_contact_tel() ) . '">' . esc_html( vtech_contact( 'phone' ) ) . '</a> &middot; <a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html( wp_parse_url( home_url(), PHP_URL_HOST ) ) . '</a></p>';
} );
