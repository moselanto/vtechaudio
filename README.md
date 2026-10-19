<div align="center">

<a href="https://vtechaudio.co.ke/"><img src="vtech-av/assets/img/og-default.jpg" alt="VTECH Audio Visual Solutions - Kenya's audio visual company" width="100%"></a>

<img src="vtech-av/assets/img/logo-3d.png" alt="VTech Audio Kenya logo" height="150">

# VTECH Audio Visual Solutions

### Custom, SEO-first WordPress platform for [vtechaudio.co.ke](https://vtechaudio.co.ke/)

Kenya's audio-visual integrator: professional sound and PA systems, LED screens and video walls, conference and boardroom AV, stage lighting and acoustic treatment, designed, supplied, installed, hired out and supported across Kenya and East Africa from Nairobi.

![Theme](https://img.shields.io/badge/Theme-v5.62.0-81007F)
![Child](https://img.shields.io/badge/Child-v5.35.0-5C005A)
![WordPress](https://img.shields.io/badge/WordPress-6.4%2B-21759B?logo=wordpress&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.0%2B-777BB4?logo=php&logoColor=white)
![Gutenberg](https://img.shields.io/badge/Gutenberg-native%2C%20no%20page%20builder-000000)
![Schema](https://img.shields.io/badge/Local%20SEO-LocalBusiness%20%2B%20Rank%20Math-4C2A85)
![No jQuery](https://img.shields.io/badge/Front%20end-no%20jQuery-2e7d32)
![License](https://img.shields.io/badge/License-GPLv2%2B-blue)
![Status](https://img.shields.io/badge/Status-Live-brightgreen)
![Blog](https://img.shields.io/badge/Blog-6%20SEO%20articles-81007F)
![Analytics](https://img.shields.io/badge/Analytics-GA4%20%2F%20GTM-E37400?logo=googleanalytics&logoColor=white)
![WhatsApp](https://img.shields.io/badge/Leads-WhatsApp%20popup-25D366?logo=whatsapp&logoColor=white)

[Live site](https://vtechaudio.co.ke/) · [Services](https://vtechaudio.co.ke/services/) · [Industries](https://vtechaudio.co.ke/industries/) · [Projects](https://vtechaudio.co.ke/projects/) · [Hire Packages](https://vtechaudio.co.ke/hire-packages/) · [Blog](https://vtechaudio.co.ke/blog/) · [FAQ](https://vtechaudio.co.ke/faq/) · [Book a Consultation](https://vtechaudio.co.ke/book-a-consultation/) · [Install guide](vtech-av/docs/INSTALL.md) · [Deploy guide](vtech-av/docs/DEPLOY.md) · [Style guide](vtech-av/docs/STYLE-GUIDE.md)

</div>

---

![VTECH Audio Visual homepage](docs/screenshots/home-desktop.jpg)

## Documentation

| Document | Purpose |
| --- | --- |
| [AGENTS.md](AGENTS.md) | Rules and current state for AI agents and developers (read first) |
| [ARCHITECTURE-ESSENTIALS.md](ARCHITECTURE-ESSENTIALS.md) | One-page overview |
| [ARCHITECTURE.md](ARCHITECTURE.md) | Full technical architecture, pipelines, deployment |
| [PRD.md](PRD.md) | Purpose, users, goals, requirements, roadmap |
| [SECURITY-AUDIT.md](SECURITY-AUDIT.md) | Security review, controls, owner actions, re-tests |
| [vtech-av/docs/SEO-GEO-AUDIT-2026-10.md](vtech-av/docs/SEO-GEO-AUDIT-2026-10.md) | SEO, AEO, GEO and schema audit |
| [vtech-av/docs/AI-ANSWER-BASELINE-2026-10-19.md](vtech-av/docs/AI-ANSWER-BASELINE-2026-10-19.md) | AI assistant visibility baseline |
| [vtech-av/docs/INSTALL.md](vtech-av/docs/INSTALL.md), [DEPLOY.md](vtech-av/docs/DEPLOY.md), [STYLE-GUIDE.md](vtech-av/docs/STYLE-GUIDE.md) | Install, deploy, design system |

## Contents

- [Overview](#overview)
- [What's new](#whats-new)
- [Screenshots](#screenshots)
- [What the site delivers](#what-the-site-delivers)
- [Blog and content marketing](#blog-and-content-marketing)
- [Editing the site in wp-admin](#editing-the-site-in-wp-admin)
- [Architecture](#architecture)
- [Content model](#content-model)
- [Hire, quotes and bookings](#hire-quotes-and-bookings)
- [Local SEO and structured data](#local-seo-and-structured-data)
- [Analytics and lead tracking](#analytics-and-lead-tracking)
- [Performance and security](#performance-and-security)
- [Project structure](#project-structure)
- [Requirements and installation](#requirements-and-installation)
- [Customising](#customising)
- [Roadmap](#roadmap)
- [Business](#business)

## Overview

VTECH Audio Visual Solutions serves churches, corporates, hotels, schools and universities, hospitals, government, conference centres, media houses and event organisers in all 47 counties. This repository holds the complete custom theme behind the website: a **native Gutenberg build with no page builder**, tuned for Core Web Vitals and local search in Kenya, with a built-in equipment-hire, quoting and booking back office.

| Package | Folder | Version | Role |
| --- | --- | --- | --- |
| **VTECH Audio Visual** (parent theme) | [`vtech-av/`](vtech-av) | 5.62.0 | Design system, templates, content model, local SEO and schema, blog, analytics, WhatsApp leads, hire/quote/booking features, setup wizard, branded login |
| **VTECH Audio Visual Child** | [`vtech-av-child/`](vtech-av-child) | 5.35.0 | Update-safe layer for brand colours and site-specific overrides |

> **Single source of truth:** company name, phone, WhatsApp, email, address and hours are set once in the Customizer and flow into every template, page, form and schema node.

## What's new

Highlights since v5.38.0. Every release is committed directly to `main`.

| Version | Highlights |
| --- | --- |
| **5.62.0** | Ranking push: LED screen and boardroom buyer guides (pixel pitch, indoor vs outdoor, room sizing tables, extra FAQs with schema), new Nairobi landing page `/audio-visual-company-nairobi/` with Service + FAQ schema and Nairobi neighbourhoods in areaServed, hospital PA and paging content on the healthcare page, new Banks & Financial Institutions industry page, proof block (recent projects, brands, coverage) on every service page. See `vtech-av/inc/seo-rank-push.php` |
| **5.61.0** | Security release: rate limits on every lead form and API, login lockout, 12-hour sessions, 12-character passwords, CORS lock on theme API, safer quote acceptance (confirm step), bounded lead payloads, extra headers. See [SECURITY-AUDIT.md](SECURITY-AUDIT.md) |
| **5.60.x** | GEO/AEO: `/llms-full.txt`, fixed `/llms.txt`, richer Service schema, brand-page SEO, booking schema, more AI crawlers, 3 new guides with photos |
| **5.59.1** | New VTech Audio Kenya logo across the theme fallback logo, login, social share image and README |
| **5.59.0** | Service FAQs with FAQPage schema, service-to-guide internal links, article author box, richer project schema |
| **5.58.0** | Homepage "Latest AV insights" strip with the 3 newest articles |
| **5.57.0** | Redesigned admin login: split screen with a purple brand panel (stage photo, VTECH message, phone) beside a clean white sign-in card; compact banner layout on phones |
| **5.56.0** | Blog launched with 6 long-form SEO articles (backdated Aug to Oct 2026), featured images, categories, tags, Rank Math meta, visible FAQs and `FAQPage` schema |
| **5.55.0** | Desktop header spacing fix, all services, industries and packages listed, readable project filter chips, phone bottom bar (Home / Services / Call / Get a quote) with floating WhatsApp, new 1200x630 social share image |
| **5.54.0** | Equipment Hire, FAQ and Blog pages redesigned; 301 redirect from `/equipment-hire-2/` to `/equipment-hire-request/` |
| **5.49.0** | GA4 / Google Tag Manager with `phone_click`, `whatsapp_click` and `generate_lead` events |
| **5.48.0** | One-time Rank Math seeding: SEO title, description and focus keyword on every page (only empty fields are filled) |
| **5.47.x** | Header redesign: top contact bar, cleaner navigation, "Book a Consultation" CTA |
| **5.46.0** | Brand and entity SEO: "VTECH", "V-Tech", "Vitech" recognised as one business; `WebSite` schema, `llms.txt`, robots rules, all editable in the Customizer |
| **5.45.0** | Branded WordPress login screen with the VTECH logo and colours |
| **5.44.0** | WhatsApp order and consultation popup that logs a lead before opening WhatsApp; every homepage section and service FAQ editable without code |
| **5.43.0** | Five new service pages matching the Google Business Profile (church sound, video conferencing, AV maintenance, digital signage, background music) |

## Screenshots

### Desktop

| Services | Professional Sound Systems |
| --- | --- |
| ![Services](docs/screenshots/services.jpg) | ![Sound systems](docs/screenshots/service-sound-systems.jpg) |
| **LED Screens & Video Walls** | **Industries we serve** |
| ![LED screens](docs/screenshots/service-led-screens.jpg) | ![Industries](docs/screenshots/industries.jpg) |
| **AV solutions for churches** | **Featured projects with filters** |
| ![Churches](docs/screenshots/industry-churches.jpg) | ![Projects](docs/screenshots/projects.jpg) |
| **Project: County Assembly delegate system** | **Equipment hire** |
| ![County Assembly project](docs/screenshots/project-county-assembly.jpg) | ![Equipment hire](docs/screenshots/equipment-hire.jpg) |
| **AV hire packages (KES pricing)** | **Book a consultation (multi-step)** |
| ![Hire packages](docs/screenshots/hire-packages.jpg) | ![Consultation](docs/screenshots/consultation.jpg) |
| **Get a quote in 24 hours** | **About** |
| ![Contact and quote](docs/screenshots/contact-quote.jpg) | ![About](docs/screenshots/about.jpg) |
| **Frequently asked questions (searchable)** | **Social share card (1200x630)** |
| ![FAQ](docs/screenshots/faq.jpg) | ![Social share image](vtech-av/assets/img/og-default.jpg) |

### Homepage sections

| Complete audio visual solutions | AV solutions for every sector |
| --- | --- |
| ![Homepage services](docs/screenshots/home-services.jpg) | ![Homepage industries](docs/screenshots/home-industries.jpg) |
| **Recent projects in Kenya** | **Frequently asked questions** |
| ![Homepage projects](docs/screenshots/home-projects.jpg) | ![Homepage FAQ](docs/screenshots/home-faq.jpg) |

### Mobile

<p align="center">
  <img src="docs/screenshots/home-mobile.jpg" alt="Mobile homepage" width="240">
  &nbsp;&nbsp;
  <img src="docs/screenshots/services-mobile.jpg" alt="Mobile services" width="240">
  &nbsp;&nbsp;
  <img src="docs/screenshots/hire-mobile.jpg" alt="Mobile hire packages" width="240">
  <br>
  <sub>Mobile-first layout with a Home / Services / Call / Get a quote bottom bar and a floating WhatsApp button</sub>
</p>

<sub>Screenshots captured from the live site on 5 October 2026; layouts have since been refined (see [What's new](#whats-new)).</sub>

## What the site delivers

### Services

<table>
  <tr>
    <td align="center" width="33%"><img src="vtech-av/assets/img/sound-systems.webp" width="220" alt="Sound systems"><br><sub><b>Professional Sound Systems</b><br>Line arrays, PA and mixing</sub></td>
    <td align="center" width="33%"><img src="vtech-av/assets/img/led-screens.webp" width="220" alt="LED screens"><br><sub><b>LED Screens & Video Walls</b><br>Indoor and outdoor</sub></td>
    <td align="center" width="33%"><img src="vtech-av/assets/img/conference-systems.webp" width="220" alt="Conference systems"><br><sub><b>Conference & Boardroom Systems</b><br>Delegate, video conferencing</sub></td>
  </tr>
  <tr>
    <td align="center"><img src="vtech-av/assets/img/stage-lighting.webp" width="220" alt="Stage lighting"><br><sub><b>Stage & Architectural Lighting</b><br>Events and permanent installs</sub></td>
    <td align="center"><img src="vtech-av/assets/img/acoustic-solutions.webp" width="220" alt="Acoustics"><br><sub><b>Acoustic Design & Soundproofing</b><br>Room acoustics that work</sub></td>
    <td align="center"><img src="vtech-av/assets/img/consultation.webp" width="220" alt="Consultation"><br><sub><b>AV Consultation & System Design</b><br>Free site survey</sub></td>
  </tr>
  <tr>
    <td align="center"><img src="vtech-av/assets/img/services/church-sound-systems.webp" width="220" alt="Church sound systems"><br><sub><b>Church Sound Systems</b><br>Clear speech and worship music</sub></td>
    <td align="center"><img src="vtech-av/assets/img/services/video-conferencing-systems.webp" width="220" alt="Video conferencing"><br><sub><b>Video Conferencing Systems</b><br>Teams and Zoom rooms</sub></td>
    <td align="center"><img src="vtech-av/assets/img/services/digital-signage.webp" width="220" alt="Digital signage"><br><sub><b>Digital Signage</b><br>Screens for retail and lobbies</sub></td>
  </tr>
  <tr>
    <td align="center"><img src="vtech-av/assets/img/services/background-music-systems.webp" width="220" alt="Background music"><br><sub><b>Background Music Systems</b><br>Hotels, restaurants, retail</sub></td>
    <td align="center"><img src="vtech-av/assets/img/services/av-maintenance-support.webp" width="220" alt="AV maintenance"><br><sub><b>AV Maintenance & Support</b><br>Service plans and repairs</sub></td>
    <td align="center"><img src="vtech-av/assets/img/video-systems.webp" width="220" alt="Equipment hire"><br><sub><b>Equipment Hire</b><br>Bronze, Silver and Gold packages</sub></td>
  </tr>
</table>

### Industries

<p align="center">
  <img src="vtech-av/assets/img/industry-churches.webp" width="118" alt="Churches">
  <img src="vtech-av/assets/img/industry-corporate.webp" width="118" alt="Corporate">
  <img src="vtech-av/assets/img/industry-hotels.webp" width="118" alt="Hotels">
  <img src="vtech-av/assets/img/industry-education.webp" width="118" alt="Education">
  <img src="vtech-av/assets/img/industry-healthcare.webp" width="118" alt="Healthcare">
  <img src="vtech-av/assets/img/industry-government.webp" width="118" alt="Government">
  <img src="vtech-av/assets/img/industry-conference-centres.webp" width="118" alt="Conference centres">
  <img src="vtech-av/assets/img/industry-media.webp" width="118" alt="Media">
  <img src="vtech-av/assets/img/industry-events.webp" width="118" alt="Events">
  <br>
  <sub>Churches · Corporates · Hotels · Schools & Universities · Hospitals · Government · Conference Centres · Media Houses · Event Organisers</sub>
</p>

### Key features

| Area | What's included |
| --- | --- |
| **Design system** | `theme.json` palette (brand purple `#81007F`), self-hosted Manrope and Inter variable fonts, fluid type, block patterns for every homepage section |
| **Conversion** | Sticky header with contact bar and CTA, WhatsApp popup that logs every enquiry as a lead, phone bottom bar (Home / Services / Call / Get a quote), floating WhatsApp button, quote and site-survey forms, multi-step consultation booking, WhatsApp booking links on hire packages |
| **Equipment hire** | Bronze, Silver and Gold hire packages with "What's included and pricing", hire request form and reference numbers |
| **Back office** | Inventory with availability locking, branded printable quotes and invoices, quote acceptance that auto-creates a booking, payments and admin actions |
| **Clients and brands** | Editable client-logo manager and brand logos per brand term |
| **Editable content** | Every homepage section, "Why VTECH" points, process steps, industry cards, FAQs and the closing CTA editable in the Customizer; benefits and FAQs per service page |
| **Blog** | Redesigned article listing, 6 seeded SEO articles with FAQs, tables and schema |
| **Brand login** | Split-screen branded sign-in page for staff |
| **Setup** | One-click setup wizard that builds every page, menu and demo entry |
| **Contact data** | One Customizer source synced into templates, shortcodes and schema, with guards against stale or conflicting values |

## Blog and content marketing

Six long-form articles ship inside the theme (`content/blog/*.json`) and are published automatically by `inc/blog-seed.php` the first time an administrator opens wp-admin. Existing posts are never overwritten.

<table>
  <tr>
    <td align="center" width="33%"><img src="vtech-av/assets/img/blog/shure-microphones-kenya-guide.webp" width="220" alt="Shure microphones"><br><sub><b>Shure microphones in Kenya</b><br>12 Aug 2026</sub></td>
    <td align="center" width="33%"><img src="vtech-av/assets/img/blog/db-technologies-speakers-kenya.webp" width="220" alt="dB Technologies speakers"><br><sub><b>dB Technologies speakers in Kenya</b><br>26 Aug 2026</sub></td>
    <td align="center" width="33%"><img src="vtech-av/assets/img/blog/boardroom-conference-room-solutions-kenya.webp" width="220" alt="Boardroom AV"><br><sub><b>Boardroom and conference room solutions</b><br>9 Sep 2026</sub></td>
  </tr>
  <tr>
    <td align="center"><img src="vtech-av/assets/img/blog/led-screens-kenya-guide.webp" width="220" alt="LED screens"><br><sub><b>LED screens in Kenya: buyer's guide</b><br>24 Sep 2026</sub></td>
    <td align="center"><img src="vtech-av/assets/img/blog/church-sound-system-kenya-guide.webp" width="220" alt="Church sound"><br><sub><b>Church sound system guide</b><br>3 Oct 2026</sub></td>
    <td align="center"><img src="vtech-av/assets/img/blog/event-sound-led-lighting-hire-nairobi.webp" width="220" alt="Event hire"><br><sub><b>Event sound, LED and lighting hire in Nairobi</b><br>10 Oct 2026</sub></td>
  </tr>
</table>

Each article gets its featured image, category, tags, Rank Math title, description and focus keywords, a visible FAQ block and `FAQPage` schema.

## Editing the site in wp-admin

| What to change | Where |
| --- | --- |
| Phone, WhatsApp, email, address, hours | Appearance > Customize > VTECH Theme Options > Company Details |
| Homepage sections, FAQs, CTA | Appearance > Customize > VTECH Theme Options > Homepage Sections |
| WhatsApp popup wording | Appearance > Customize > VTECH Theme Options > WhatsApp Orders |
| Brand names, slogan, areas served, AI summary | Appearance > Customize > VTECH Theme Options > SEO & Brand |
| GA4 or Tag Manager ID | Appearance > Customize > VTECH Theme Options > Analytics & Tracking |
| Service benefits and FAQs | Services > edit a service > "Service page content" box |
| SEO titles and descriptions | Rank Math box on each page or post |
| Logo (site and login screen) | Appearance > Customize > Site Identity |

## Architecture

### Theme map

```mermaid
flowchart TB
    subgraph CHILD["vtech-av-child (active)"]
        CH["Brand colour overrides · filters"]
    end
    subgraph PARENT["vtech-av (parent theme)"]
        TPL["Templates<br/>front-page · page-* · single-* · archive-* · patterns"]
        CORE["Core inc/<br/>post-types · taxonomies · acf-fields · theme-options · contact"]
        SEO["SEO inc/<br/>seo-local · seo-meta · seo-pages · seo-services<br/>seo-brand · seo-seed · blog-seed · schema · breadcrumbs"]
        FEAT["Business features inc/features/<br/>hire-module · wa-order · inventory · quote-invoice<br/>accept-booking · payments · clients · brand-logos"]
        OPS["Ops inc/<br/>setup-wizard · demo-import · performance · security<br/>content-options · tracking · login-brand"]
    end
    CHILD -- "Template: vtech-av" --> PARENT
    TPL --> CORE
    TPL --> SEO
    TPL --> FEAT
    CORE --> OPT[("Customizer: company details")]
    SEO --> RM["Rank Math / Yoast<br/>(theme defers when active)"]
    FEAT --> DB[("WordPress DB<br/>CPTs + post meta")]
    FEAT --> MAIL["Owner email + WhatsApp links"]
```

### Lead to booking pipeline

```mermaid
sequenceDiagram
    autonumber
    participant C as Client
    participant W as Website
    participant A as VTECH admin
    participant Q as Quote / invoice
    participant I as Inventory
    C->>W: Hire request, quote form, consultation booking or WhatsApp popup
    W->>A: Saved as lead with reference number, owner emailed, GA4 generate_lead event
    A->>Q: Generate branded quote (printable PDF)
    Q-->>C: Quote with secure "Accept this quote" link
    C->>Q: Accepts quote
    Q->>I: Auto-create booking, lock equipment for the dates
    A->>Q: Record payment, issue invoice
```

### Single source of truth for contact data

```mermaid
flowchart LR
    CZ["Customizer<br/>VTECH Theme Options > Company Details"] --> CT["inc/contact.php"]
    CT --> HDR["Header · footer · floating buttons"]
    CT --> PG["Pages and shortcodes"]
    CT --> FM["Forms and notifications"]
    CT --> LB["LocalBusiness schema<br/>NAP · geo · hours · areas served"]
    LB --> RM["Rank Math entity (shared)"]
```

### Release pipeline

```mermaid
flowchart LR
    A["Edit code"] --> B["Commit to GitHub<br/>moselanto/vtechaudio"]
    B --> C["Upload vtech-av<br/>(zip or SFTP)"]
    C --> D["Clear page cache"]
    D --> E["Live on vtechaudio.co.ke"]
    E --> F["Rank Math sitemap<br/>Search Console"]
```

> **Deployment note:** commits to this repository do **not** deploy automatically. Download [main.zip](https://github.com/moselanto/vtechaudio/archive/refs/heads/main.zip), replace the `vtech-av` folder on the server (or upload only the changed files), then clear the Seraphinite Accelerator cache. See [DEPLOY.md](vtech-av/docs/DEPLOY.md).

## Content model

```mermaid
erDiagram
    SERVICE ||--o{ PROJECT : "delivered in"
    INDUSTRY ||--o{ PROJECT : "sector"
    HIRE_PACKAGE }o--|| PACKAGE_CATEGORY : "grouped by"
    HIRE_REQUEST ||--o| VTECH_QUOTE : "quoted as"
    VTECH_QUOTE ||--o| VTECH_BOOKING : "accepted into"
    VTECH_BOOKING }o--o{ EQUIPMENT : "locks"
    BRAND ||--o{ EQUIPMENT : makes
```

| Group | Content types |
| --- | --- |
| **Public content** | Services, Projects, Industries, Equipment, Brands, Testimonials, Case Studies, FAQs, Hire Packages |
| **Back office (private)** | `hire_request`, `consultation`, `vtech_lead`, `vtech_quote`, `vtech_booking`, `vtech_client` |
| **Taxonomies** | Seven, including `package_category` and brand terms with logos |

## Hire, quotes and bookings

| Step | Module | What happens |
| --- | --- | --- |
| 1. Request | `hire-module.php`, `contact-form.php` | Hire request, quote or consultation saved with a reference number; owner notified |
| 2. Quote | `quote-invoice.php` | Branded, printable quote generated and stored as a private `vtech_quote` |
| 3. Accept | `accept-booking.php` | Secure accept link; acceptance auto-creates a booking |
| 4. Lock stock | `inventory.php` | Equipment reserved for the booking dates, preventing double-booking |
| 5. Pay | `payments.php` | Payments recorded with admin actions; invoice issued |

## Local SEO and structured data

- **One canonical `LocalBusiness` entity** shared with Rank Math: name, address and phone, geo, opening hours, areas served (Nairobi and all 47 counties), service catalogue and brand catalogue (dB Technologies, Shure, mixers and more).
- **Per-page schema:** `Service` + `Offer`, `FAQPage`, `BreadcrumbList`, project `CreativeWork`, `ItemList`, `WebSite` + `SearchAction` and `OpeningHoursSpecification`.
- **Per-page titles and descriptions** targeting searches such as *audio visual company Kenya, sound system installation Nairobi, LED screen hire Kenya, church sound systems Kenya* and *conference systems Kenya*; the theme defers its own meta when Rank Math or Yoast is active.
- **Brand entity:** "VTECH", "V-Tech", "Vitech" and "VTECH Audio Kenya" resolve to one business through `alternateName`, `sameAs` and the Google Business Profile link; `llms.txt` gives AI assistants a factual summary.
- **Social sharing:** a 1200x630 branded share image (`assets/img/og-default.jpg`) for WhatsApp, Facebook and LinkedIn previews.
- **Clean URLs:** retired URLs 301-redirect to their replacements; archives list every service, industry and package.
- Contact-data guards keep the same name, address and phone across every page and schema node.

## Analytics and lead tracking

Set a `GTM-` or `G-` ID in the Customizer and the theme loads Tag Manager or GA4. Logged-in staff are not tracked.

| Event | Fires when |
| --- | --- |
| `phone_click` | Any `tel:` link is tapped |
| `whatsapp_click` | Any WhatsApp link or popup button is used |
| `generate_lead` | A consultation, hire, quote or contact form is sent, or the WhatsApp popup is submitted |

## Performance and security

- **Performance:** inlined critical CSS, preloaded variable fonts, LCP hero preload, WebP imagery, lazy-loading and **no jQuery on the front end**
- **Security:** escaped output and sanitised input, nonce-protected admin actions and AJAX, capability checks, rate limiting on all public forms and APIs, sign-in lockout (5 failures / 15 min), 12-hour sessions, 12-character passwords, CORS restricted to this site, HSTS and security headers, private documents never cached or indexed. Code: `inc/security.php`, `inc/security-hardening.php`. Full review: [SECURITY-AUDIT.md](SECURITY-AUDIT.md)

## Project structure

```text
vtechaudio/
├── README.md
├── docs/screenshots/              # README images captured from the live site
├── vtech-av/                      # Parent theme
│   ├── front-page.php             # Homepage
│   ├── page-*.php                 # About, consultation, contact, equipment hire, FAQ, hire request
│   ├── index.php  single.php      # Blog listing and articles
│   ├── single-*.php  archive-*.php # Services, industries, projects, hire packages
│   ├── patterns/                  # Hero, services, industries, stats, CTA + FAQ, service layout
│   ├── theme.json  style.css  functions.php
│   ├── inc/
│   │   ├── post-types.php  taxonomies.php  acf-fields.php  theme-options.php  contact.php
│   │   ├── seo-local.php  seo-meta.php  seo-pages.php  schema.php  breadcrumbs.php
│   │   ├── seo-services.php  seo-brand.php  seo-seed.php  blog-seed.php  tracking.php
│   │   ├── content-options.php  login-brand.php
│   │   ├── setup-wizard.php  demo-import.php  required-plugins.php  block-patterns.php
│   │   ├── performance.php  security.php  form-helpers.php
│   │   └── features/              # hire-module, inventory, quote-invoice, accept-booking,
│   │                              # payments, package-meta, clients, brand-logos, wa-order,
│   │                              # equipment-catalog, cost-estimator, client-portal, contact-form
│   ├── assets/                    # css (critical, main, editor), js/app.js, fonts, icons,
│   │                              # img (services/, blog/, og-default share image)
│   ├── content/                   # Page copy reference + blog/*.json articles
│   └── docs/                      # INSTALL, DEPLOY, STYLE-GUIDE, demo-content.xml
└── vtech-av-child/                # Child theme (brand overrides)
```

## Requirements and installation

| Component | Version |
| --- | --- |
| WordPress | 6.4 or later |
| PHP | 8.0 or later |
| HTTPS | Required |
| Recommended plugins | Advanced Custom Fields, Contact Form 7, Rank Math SEO, Seraphinite Accelerator (or another page cache) |

1. Zip `vtech-av`, then **Appearance > Themes > Add New > Upload Theme**; upload and activate `vtech-av-child` the same way.
2. Install the recommended plugins shown after activation.
3. Run **VTECH Setup > Set up my website**, then **Settings > Permalinks > Save Changes** once.
4. Set phone, WhatsApp, email, address and hours in **Appearance > Customize > VTECH Theme Options > Company Details**.

Full steps: [INSTALL.md](vtech-av/docs/INSTALL.md).

## Customising

Brands, services, areas served and homepage FAQs are filterable from the child theme:

```php
add_filter( 'vtech_seo_brands', function ( $brands ) {
    $brands[] = array( 'brand' => 'Allen & Heath', 'name' => 'Allen & Heath digital mixers', 'category' => 'Audio mixers', 'desc' => '...' );
    return $brands;
} );
```

Filters: `vtech_seo_brands`, `vtech_seo_services`, `vtech_seo_areas`, `vtech_home_faqs`, `vtech_business_entity`, `vtech_home_seo_title`, `vtech_home_seo_description`. Brand colours live in `vtech-av-child/style.css` (`--c-primary`, `--c-primary-dark`).

## Roadmap

These modules ship as working front-end scaffolds; their back-end logic is planned:

- [ ] **Client portal**: authenticated quote tracking and project status
- [ ] **Project cost estimator**: real pricing logic (currently returns an indicative range)
- [ ] **Equipment catalogue**: real-time, calendar-based availability
- [ ] **Homepage "Latest insights"** strip showing the newest articles
- [ ] **Real project photography** to replace stock and generated images
- [x] Blog with SEO articles (5.56.0)
- [x] Analytics and lead events (5.49.0)
- [x] WhatsApp lead popup (5.44.0)

## Business

**VTECH Audio Visual Solutions**
Ground Floor, Mpaka Plaza, Mpaka Road, Westlands, Nairobi · P.O. Box 66734-00800
Phone and WhatsApp: [+254 182 942 946](tel:+254182942946) · Email: [info@vtechaudio.co.ke](mailto:info@vtechaudio.co.ke)
Hours: Mon - Fri, 9:00 AM - 6:00 PM · [Facebook](https://web.facebook.com/vtechaudio)

## Credits

Designed, developed and maintained by **[Pimofy Digital](https://github.com/moselanto)**, Nairobi.
Fonts: Manrope and Inter (SIL Open Font License). Brand names belong to their respective owners.

## License

GNU General Public License v2 or later. See the [license text](http://www.gnu.org/licenses/gpl-2.0.html).
