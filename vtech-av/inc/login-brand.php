<?php
/**
 * Branded WordPress login screen (v5.45.0).
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

add_action( 'login_enqueue_scripts', function () {
	$logo  = esc_url( vtech_login_logo_url() );
	$hero  = esc_url( get_theme_mod( 'vtech_hero_img', VTECH_URI . '/assets/img/hero.webp' ) );
	$fonts = esc_url( VTECH_URI . '/assets/fonts/' );
	?>
<style id="vtech-login">
@font-face{font-family:Manrope;font-weight:200 800;font-display:swap;src:url(<?php echo $fonts; ?>manrope-var.woff2) format("woff2")}
@font-face{font-family:Inter;font-weight:100 900;font-display:swap;src:url(<?php echo $fonts; ?>inter-var.woff2) format("woff2")}
:root{--v-p:#81007F;--v-pd:#5C005A;--v-ink:#16111a;--v-muted:#5b6472;--v-line:#e7e3ea}
html,body.login{height:100%}
body.login{margin:0;display:flex;flex-direction:column;justify-content:center;min-height:100vh;font-family:Inter,system-ui,sans-serif;color:var(--v-ink);
  background:linear-gradient(120deg,rgba(26,0,25,.88) 0%,rgba(92,0,90,.78) 55%,rgba(129,0,127,.55) 100%),url(<?php echo $hero; ?>) center/cover no-repeat fixed;}
body.login::before{content:"";position:fixed;width:620px;height:620px;right:-200px;top:-260px;border-radius:50%;background:radial-gradient(circle,rgba(255,255,255,.16),transparent 70%);pointer-events:none}
#login{width:100%;max-width:400px;padding:0 1.25rem;margin:0 auto;position:relative;z-index:1}
#login h1{margin:0 0 1.25rem}
#login h1 a{background:#fff url(<?php echo $logo; ?>) center/auto 62px no-repeat;width:100%;max-width:360px;height:96px;margin:0 auto;border-radius:18px;box-shadow:0 18px 44px rgba(0,0,0,.28);text-indent:-9999px;overflow:hidden;display:block;outline:0}
#login h1 a:focus-visible{box-shadow:0 0 0 4px rgba(255,255,255,.55),0 18px 44px rgba(0,0,0,.28)}
.login form{margin:0;padding:1.75rem 1.6rem 1.5rem;border:0;border-radius:20px;background:#fff;box-shadow:0 24px 60px rgba(0,0,0,.3)}
.login form::before{content:"Welcome back";display:block;font-family:Manrope,Inter,sans-serif;font-weight:800;font-size:1.35rem;letter-spacing:-.02em;margin:0 0 .25rem}
.login form::after{content:"Sign in to manage the VTECH website";display:block;position:absolute;top:3.55rem;left:1.6rem;font-size:.88rem;color:var(--v-muted)}
.login form{position:relative;padding-top:4.5rem}
#lostpasswordform::before{content:"Reset your password"}
#lostpasswordform::after{content:"We will email you a reset link"}
#registerform::before{content:"Create an account"}
#registerform::after{content:""}
.login label{font-family:Manrope,Inter,sans-serif;font-weight:600;font-size:.86rem;color:var(--v-ink)}
.login form .input,.login input[type=text],.login input[type=password],.login input[type=email]{min-height:48px;margin:.35rem 0 1.1rem;padding:.6rem .9rem;font-size:16px;border:1px solid var(--v-line);border-radius:12px;background:#fff;box-shadow:none;transition:border-color .2s,box-shadow .2s}
.login form .input:focus,.login input[type=text]:focus,.login input[type=password]:focus{border-color:var(--v-p);box-shadow:0 0 0 4px rgba(129,0,127,.14);outline:0}
.login .button.wp-hide-pw{min-height:48px;margin-top:.35rem;color:var(--v-p)}
.login .button.wp-hide-pw:focus{box-shadow:0 0 0 3px rgba(129,0,127,.25);border-color:transparent}
.login .forgetmenot{margin:.5rem 0 0;float:none}
.login .forgetmenot label{font-weight:500;color:var(--v-muted)}
.login input[type=checkbox]{border-color:#c9bfcc;border-radius:5px}
.login input[type=checkbox]:checked::before{filter:hue-rotate(80deg) saturate(2)}
.login input[type=checkbox]:focus{box-shadow:0 0 0 3px rgba(129,0,127,.2);border-color:var(--v-p)}
.login .submit{margin-top:1.1rem;float:none;clear:both}
.wp-core-ui .button-primary,.login .button-primary{float:none;width:100%;min-height:50px;font-family:Manrope,Inter,sans-serif;font-weight:700;font-size:1rem;border:0;border-radius:12px;background:var(--v-p);box-shadow:0 10px 24px rgba(129,0,127,.32);text-shadow:none;transition:background .2s,transform .15s}
.wp-core-ui .button-primary:hover,.wp-core-ui .button-primary:focus{background:var(--v-pd);transform:translateY(-1px)}
.wp-core-ui .button-primary:focus{box-shadow:0 0 0 4px rgba(129,0,127,.25)}
.login #nav,.login #backtoblog{margin:1rem 0 0;padding:0;text-align:center;font-size:.9rem}
.login #nav a,.login #backtoblog a,.login .privacy-policy-page-link a{color:rgba(255,255,255,.88);text-decoration:none}
.login #nav a:hover,.login #backtoblog a:hover,.login .privacy-policy-page-link a:hover{color:#fff;text-decoration:underline}
.login .privacy-policy-page-link{margin:1.25rem 0 0}
.login .message,.login .notice,.login #login_error{margin:0 0 1rem;border:0;border-left:4px solid var(--v-p);border-radius:12px;background:#fff;box-shadow:0 10px 24px rgba(0,0,0,.18);padding:.85rem 1rem}
.login #login_error{border-left-color:#c62828}
.login .language-switcher{display:none}
.vtech-login-foot{position:relative;z-index:1;margin:1.5rem auto 1.25rem;text-align:center;font-size:.8rem;color:rgba(255,255,255,.7)}
.vtech-login-foot a{color:rgba(255,255,255,.85);text-decoration:none}
@media(max-width:480px){.login form{padding:4.4rem 1.2rem 1.25rem}.login form::after{left:1.2rem}#login h1 a{height:84px;background-size:auto 52px}}
@media(prefers-reduced-motion:reduce){.wp-core-ui .button-primary{transition:none}}
</style>
	<?php
} );

/** Small footer with phone + website under the login card. */
add_action( 'login_footer', function () {
	echo '<p class="vtech-login-foot">' . esc_html( vtech_contact( 'company' ) ) . ' &middot; <a href="tel:' . esc_attr( vtech_contact_tel() ) . '">' . esc_html( vtech_contact( 'phone' ) ) . '</a> &middot; <a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html( wp_parse_url( home_url(), PHP_URL_HOST ) ) . '</a></p>';
} );
