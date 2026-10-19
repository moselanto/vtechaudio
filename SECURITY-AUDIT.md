# Security audit - VTECH Audio Visual theme

Review date: 19 Oct 2026. Scope: every PHP and JS file in `vtech-av/` and `vtech-av-child/` (72 PHP files), theme 5.60.5 -> 5.61.0.
Method: manual code review of every entry point that accepts input from visitors or staff (AJAX, REST, admin-post, query strings, meta boxes, Customizer), plus database, output, file and session handling. PHP could not be executed in the review environment, so every change is small, additive and must be re-tested on the live site after upload (section 6).

## 1. Summary

The codebase was already well protected: nonces on forms and meta boxes, capability checks on admin actions, sanitised input, escaped output, prepared SQL, safe redirects and hashed document tokens. The review found **6 issues (0 critical, 2 medium, 4 low)**, all fixed in 5.61.0 without removing any feature.

| # | Severity | Issue | Fix | File |
|---|---|---|---|---|
| 1 | Medium | Public form endpoints (REST `vtech/v1/submit`, `admin-post vtech_form`, AJAX consultation / hire / quote) had no rate limit. A bot could create unlimited leads and make the site send unlimited confirmation emails to any address (spam relay, mailbox flooding, database growth). | Rate limit of 6 submissions per 10 minutes per visitor on every lead path; honeypot added to the REST path; staff are never throttled. | `inc/security-hardening.php`, `inc/features/hire-module.php` |
| 2 | Medium | The quote acceptance link performed the acceptance on a plain GET. Email security scanners and link previews open links automatically, so a quote could be accepted, equipment booked and an invoice issued without the client clicking. | GET now shows a confirmation page; acceptance only happens on a POST with a valid nonce. Existing links keep working. | `inc/features/accept-booking.php` |
| 3 | Low | `vtech_process_submission()` stored every posted field with no limit on field count or length. | Max 60 fields, 5,000 characters each, scalar values only; internal fields skipped. | `inc/features/hire-module.php` |
| 4 | Low | The submitter's name went into the email `Reply-To` header with only `sanitize_text_field()`. | Line breaks and header characters (`< > " , ;`) stripped before use. | `inc/features/hire-module.php` |
| 5 | Low | The quote basket started a PHP session with default cookie flags and accepted any post ID. | Session cookie is HttpOnly, SameSite=Lax, Secure on HTTPS; only published equipment items accepted; basket capped at 50. | `inc/features/equipment-catalog.php` |
| 6 | Low | The availability check accepted any date string and quantity. | Dates must be `YYYY-MM-DD`, item must be published, quantity capped at 500, rate limited. | `inc/features/inventory.php` |

## 2. Required controls

| Control | Status | Where |
|---|---|---|
| Rate limiting | **Added**: leads 6 / 10 min; tools 30 / 10 min; WhatsApp lead 8 / 10 min (existing); sign-in 5 failures / 15 min | `inc/security-hardening.php`, `inc/features/wa-order.php` |
| Input sanitisation | In place on every field (`sanitize_text_field`, `sanitize_textarea_field`, `sanitize_email`, `sanitize_key`, `absint`, `esc_url_raw`); payload limits added | all handlers |
| Password hashing | WordPress core (bcrypt/phpass via `wp_hash_password`); theme never stores passwords. **Added** 12-character minimum on profile save and reset | `inc/security-hardening.php` |
| Environment variables / no hard-coded keys | No secrets in the repository. Paystack **public** key, GA4 and GTM IDs live in the Customizer (database), not code. Secret keys must stay in `wp-config.php` or the host's environment | `inc/features/payments.php`, `inc/tracking.php` |
| CORS | **Added**: theme REST routes (`/vtech/v1/*`) only return `Access-Control-Allow-Origin` for this site's own origin | `inc/security-hardening.php` |
| SQL / NoSQL injection | All custom SQL uses `$wpdb->prepare()` with `esc_like()`; everything else uses WordPress APIs. No NoSQL store | `inc/contact.php` |
| Session expiry | **Added**: sign-ins expire after 12 hours (7 days with Remember me) | `inc/security-hardening.php` |
| HTTPS everywhere | HSTS (1 year, subdomains) sent on HTTPS; site URL is https. Host must keep the HTTP to HTTPS redirect | `inc/security.php` |
| Security headers | X-Content-Type-Options, X-Frame-Options, Referrer-Policy, Permissions-Policy, HSTS; **added** Cross-Origin-Opener-Policy and X-Permitted-Cross-Domain-Policies; private documents get `noindex` and `no-store` | `inc/security.php`, `inc/security-hardening.php` |
| Regular dependency updates | Theme has no Composer / npm runtime dependencies. Plugins (Rank Math, ACF, CF7, Elementor if present) must be updated monthly after a backup (section 5) | operations |

## 3. Vulnerability classes checked

| Class | Result |
|---|---|
| SQL injection | Pass. Only 3 raw queries, all prepared. |
| XSS | Pass. Templates escape with `esc_html`, `esc_attr`, `esc_url`, `wp_kses_post`; JSON-LD via `wp_json_encode`. New confirmation page escapes every value. |
| CSRF | Pass after fix #2. Nonces on all admin meta boxes, setup wizard, SEO seed, AJAX forms. Public lead forms rely on honeypot + time trap + rate limit (nonces on cached public pages would break real visitors). |
| Broken authentication | Improved: login lockout, session expiry, password length, generic login error, XML-RPC off, user enumeration blocked (REST users route and `?author=`). |
| Insecure file uploads | Pass. The theme has no public upload handler; media import uses `media_handle_sideload()` from theme files only, admin-only. |
| Unauthorised access | Pass. Lead CPTs are private with `show_ui` only; documents need an HMAC token (`wp_hash`) compared with `hash_equals`; admin actions check capabilities and nonces. |
| Session hijacking | Improved: shorter WordPress sessions; hardened basket session cookie; HSTS. |
| Password security | WordPress hashing + 12-character minimum. |
| API vulnerabilities | REST routes: honeypot, rate limit, CORS restriction, bounded payload, generic responses (no stack traces). |
| Data exposure | Leads stored as private posts; IPs stored only as salted hashes in transients; document pages `noindex`, `no-store`, `no-referrer`; theme file editor disabled (`DISALLOW_FILE_EDIT`). |

## 4. Secure coding rules for future work

1. Sanitise on input, escape on output, every time.
2. Every new admin action: nonce + `current_user_can()`.
3. Every new public endpoint: honeypot + `vtech_rate_limited()` + bounded fields.
4. SQL only through WordPress APIs or `$wpdb->prepare()`.
5. Never commit keys or passwords; use `wp-config.php` constants or host environment variables.
6. No raw `header()` before output except in `template_redirect`/REST filters with a `headers_sent()` check.
7. Don't remove existing behaviour without the owner's approval.

## 5. Actions for the owner and host

| Action | Owner |
|---|---|
| Turn on two-step login for every administrator (host security tools or a well-known 2FA plugin) | Agnes / admins |
| Give content-only staff the Editor role, not Administrator | Agnes |
| Monthly: back up, then update WordPress, plugins and themes | Agnes / host |
| Keep the HTTP to HTTPS redirect and a web application firewall at the host | Host |
| Remove unused plugins and inactive themes | Agnes |

## 6. Re-test after uploading 5.61.0

1. Submit the consultation form and the hire request form once each: both should succeed with a reference number.
2. Submit the same form 7 times in a row from one device: the 7th is refused (JSON forms) or silently ignored (non-JS form).
3. Open a quote acceptance link: a confirmation page appears; click Accept: the booking and invoice are created as before.
4. Sign in to wp-admin normally; 5 wrong passwords lock that username for 15 minutes from that device.
5. Check a page's response headers include `Cross-Origin-Opener-Policy`.
