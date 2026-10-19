# Architecture essentials (one page)

**What:** custom WordPress theme for VTECH Audio Visual Solutions (vtechaudio.co.ke), a Nairobi AV integrator. Parent theme `vtech-av/` (5.61.0) + child theme `vtech-av-child/`.

**Stack:** WordPress 6.x, PHP 8.x, Gutenberg templates, Rank Math SEO, optional ACF and Contact Form 7. No page builder, no jQuery on the front end, no build step.

**Bootstrap:** `vtech-av/functions.php` loads modules from `inc/` with `vtech_require()` (missing files are skipped, never fatal).

**Modules**
- Content model: `post-types.php`, `taxonomies.php`, `acf-fields.php` (services, industries, projects, brands, equipment, hire packages).
- Contact data (single source of truth): `contact.php` -> Customizer -> every template, form and schema node.
- SEO / schema / AI: `seo-local.php` (business entity), `seo-brand.php` (aliases, WebSite, llms.txt, robots), `seo-geo.php` (llms-full.txt, brand + booking schema), `schema.php`, `seo-pages.php`, `seo-services.php`, `seo-boost.php`, `breadcrumbs.php`, `seo-seed.php`, `blog-seed.php`.
- Leads and operations: `features/hire-module.php` (forms -> private posts + email), `features/wa-order.php` (WhatsApp popup), `features/quote-invoice.php`, `features/accept-booking.php`, `features/inventory.php`, `features/payments.php`.
- Analytics: `tracking.php` (GTM-WFRJWTHC, GA4 G-NZ4SE0NDNG; events phone_click, whatsapp_click, generate_lead).
- Security: `security.php` (headers, enumeration, XML-RPC) + `security-hardening.php` (rate limits, login lockout, sessions, passwords, CORS).

**Lead flow:** form -> nonce/honeypot/rate limit -> `vtech_process_submission()` -> private `consultation` / `hire_request` post + owner email + client confirmation -> owner generates quote -> client accepts via token link + confirm -> booking locks inventory + deposit invoice.

**Deploy:** commit to `main` -> owner uploads the `vtech-av` folder in WordPress -> open wp-admin once (runs one-time seeds) -> verify live.

Full detail: `ARCHITECTURE.md`. Security: `SECURITY-AUDIT.md`. Rules for agents: `AGENTS.md`.
