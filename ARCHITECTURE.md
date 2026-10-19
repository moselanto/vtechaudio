# Architecture - VTECH Audio Visual platform

## 1. Overview
A custom WordPress theme that runs vtechaudio.co.ke: marketing site, local SEO and AI-search layer, lead capture, equipment hire, quotes, invoices and bookings. Repository: `moselanto/vtechaudio`.

| Part | Path | Version | Role |
|---|---|---|---|
| Parent theme | `vtech-av/` | 5.61.0 | All features |
| Child theme | `vtech-av-child/` | 5.35.0 | Update-safe overrides |
| Docs | repo root + `vtech-av/docs/` | | This file, PRD, security, install, deploy, style, SEO audit, AI baseline |

## 2. Runtime
- WordPress 6.x, PHP 8.0+, MySQL/MariaDB, HTTPS.
- Plugins: Rank Math SEO (required for SEO metadata), ACF (optional fields), Contact Form 7 (optional; theme forms work without it).
- Front end: `assets/css/main.css`, `assets/js/app.js` (vanilla JS, deferred). Critical CSS inlined by `inc/performance.php`.

## 3. Boot sequence (`functions.php`)
1. Constants `VTECH_VERSION`, `VTECH_DIR`, `VTECH_URI`.
2. `vtech_require()` loads, in order: contact data, form helpers, setup wizard, required plugins, content model, SEO modules, tracking, seeds, schema, breadcrumbs, performance, patterns, theme options, content options, login brand, OG default, GEO, boost, features, demo import.
3. `inc/security.php` and `inc/security-hardening.php`.
4. Rewrite flush gated by `VTECH_REWRITE_VER`; host-proof template forcing by page slug.

## 4. Content model
| Type | Slug | Public | Notes |
|---|---|---|---|
| service | /services/... | yes | Service schema + FAQs |
| industry | /industry/... | yes | Sector pages |
| project | /projects/... | yes | Case studies, CreativeWork schema |
| brand | /equipment-brand/... | yes | Brand pages (single posts) |
| equipment | | yes | Hire catalogue items |
| hire_package | /hire-packages/... | yes | Product + Offer schema with real KES price |
| post | /slug/ | yes | Guides, FAQs, BlogPosting |
| consultation, hire_request, vtech_lead | | private | Leads |
| vtech_quote, vtech_booking | | private | Documents, inventory locks |

## 5. SEO, schema and AI layer
- **Business entity** (`seo-local.php` + `seo-brand.php` + `seo-geo.php`): one Organization/LocalBusiness/ProfessionalService node merged into Rank Math's graph via `rank_math/json_ld`; aliases, areas, services catalogue, keywords, sameAs (no WhatsApp links).
- **Per page** (`schema.php`, `seo-pages.php`, `seo-boost.php`, `blog-seed.php`): Service (+provider, channel), FAQPage from visible FAQs only, BreadcrumbList, CreativeWork, Product only when a real price exists, CollectionPage + Brand, WebPage + ReserveAction on the consultation page.
- **AI** : `/llms.txt` (summary) and `/llms-full.txt` (services, FAQs, packages, projects, guides) built live; robots.txt welcomes search and AI crawlers.
- **Seeds:** `seo-seed.php` fills empty Rank Math fields once; `blog-seed.php` creates articles from `content/blog/*.json` (status supported) and attaches theme images to articles without a featured image.

## 6. Lead, quote and booking pipeline
1. Visitor submits a form (AJAX, REST `vtech/v1/submit`, or `admin-post vtech_form`) or the WhatsApp popup (REST `vtech/v1/wa-lead`).
2. Guards: nonce (AJAX), honeypot, time trap, rate limit, bounded payload.
3. `vtech_process_submission()` stores a private post with reference `VTA-<TYPE>-<YEAR>-<N>`, emails the office and confirms to the client.
4. Staff generate a quote or invoice from the request (`payments.php` meta box, nonce-protected).
5. Client opens the tokenised document (`?vtech_doc=ID&token=`), then the acceptance link (`?vtech_accept=ID&token=`) -> confirmation page -> POST with nonce.
6. Acceptance re-checks inventory, creates a confirmed booking, generates the deposit invoice and emails both sides.
7. Optional Paystack button (public key from Customizer).

## 7. Analytics
GTM container GTM-WFRJWTHC (version 2) loads GA4 G-NZ4SE0NDNG. `app.js` pushes `phone_click`, `whatsapp_click`, `generate_lead`. No enquiry content is sent to analytics.

## 8. Security architecture
See `SECURITY-AUDIT.md`. Layers: WordPress core auth + hashing; `security.php` headers and enumeration blocks; `security-hardening.php` rate limits, login lockout, session expiry, password length, CORS, private-document headers; per-handler sanitisation, nonces and capability checks.

## 9. Deployment
1. Commit to `main` (small, focused commits; bump `VTECH_VERSION` and `style.css`).
2. Owner uploads the whole `vtech-av` folder (Appearance > Themes > Add New > Upload, Replace current).
3. Open wp-admin once (one-time seeds).
4. Verify: pages load, forms submit, schema validates, `/llms.txt` and `/llms-full.txt` respond.
Rollback: re-upload the previous release zip from GitHub history.

## 10. External services
Google Search Console + Bing Webmaster Tools (sitemap `sitemap_index.xml`), Google Business Profile, GA4/GTM, WhatsApp click-to-chat, optional Paystack.
