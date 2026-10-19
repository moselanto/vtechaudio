# VTECH SEO / AEO / GEO / Schema post-implementation audit (19 Oct 2026, theme 5.60.0)

Scope: repository `moselanto/vtechaudio` (main @ 5.59.1) and the live site https://vtechaudio.co.ke/, checked on 19 Oct 2026. Follows the brief in *VTECH SEO, AEO, GEO SCHEMA.docx* and *VTECH AUDIO SEO PROMPT.docx*.

Status keys: PASS = tested and fine, FAIL = tested and broken, FIXED = fixed in 5.60.0 (awaiting upload), BLOCKED = needs access or a business decision, NOT RUN = not tested.

## 1. What already works (verified on the live site)

| Check | Result |
|---|---|
| All 73 sitemap URLs (posts, pages, 12 services, 10 projects, 10 industries, brand, 5 hire packages) | PASS - sitemap index loads, key URLs return 200 |
| robots.txt welcomes Googlebot, Bingbot, GPTBot, OAI-SearchBot, ChatGPT-User, PerplexityBot, ClaudeBot, Google-Extended, Applebot | PASS |
| One H1 on every page tested (12 page types) | PASS |
| Canonical + `index, follow, max-snippet:-1` on home and service pages | PASS |
| Homepage graph: Place, Organization/LocalBusiness/ProfessionalService, WebSite (SearchAction, alternate names), WebPage, FAQPage | PASS, valid JSON |
| Service pages: Service + FAQPage + BreadcrumbList; Projects: Article + CreativeWork; Blog: BlogPosting + FAQPage; Hire packages: Product with real KES price | PASS, valid JSON |
| Opening hours in schema match the visible site (Mon-Fri 08:00-18:00, Sat 08:00-18:30) | PASS |
| Keyword-led titles on all priority pages (e.g. "Church Sound System Installation in Kenya", "Sound System Installation Kenya") | PASS |

## 2. Issues found and fixed in 5.60.0

| # | Severity | Issue (evidence) | Fix | Files |
|---|---|---|---|---|
| 1 | High | `/llms.txt` listed no services, industries or hire packages: its handler ran at `init` priority 1, before the post types were registered at priority 10 | Handler moved to priority 99; projects added; links to the new full file | `inc/seo-brand.php` |
| 2 | High (GEO) | No complete knowledge file for AI assistants | New `/llms-full.txt`: quick facts, "when to recommend VTECH", every service with its visible FAQs, homepage FAQs, hire packages with real prices, industries, projects, guides | `inc/seo-geo.php` |
| 3 | Medium | Service pages had no Organization node on the page, so `provider` was an unresolvable `@id`; no `@id`, `serviceType` or booking channel | Service node now has `@id`, `serviceType`, full provider (name, URL, phone, email, address), Nairobi + Kenya `areaServed`, `availableChannel` (booking URL + phone), image | `inc/schema.php` |
| 4 | Medium | `sameAs` contained a WhatsApp chat link (`wa.me`), which is not an official profile | Removed from sameAs in both the theme entity and Rank Math graph | `inc/schema.php`, `inc/seo-geo.php` |
| 5 | Low | Rank Math WebPage nodes declared `en-US` while WebSite declares `en-KE` | All page nodes set to `en-KE` | `inc/seo-geo.php` |
| 6 | Medium | Brand page title showed "Dbtechnologies" and had only breadcrumb schema | Title "dB Technologies in Kenya - Supply, Installation & Hire", new description, CollectionPage + Brand + Service schema (manual Rank Math values still win) | `inc/seo-geo.php` |
| 7 | Low | Consultation page had only breadcrumb schema | WebPage + consultation Service + ReserveAction | `inc/seo-geo.php` |
| 8 | Low (GEO) | Some AI answer crawlers not named in robots.txt | Added Perplexity-User, DuckAssistBot, MistralAI-User, Meta-ExternalAgent, Amazonbot, YouBot, CCBot, cohere-ai; link to llms-full.txt | `inc/seo-geo.php` |
| 9 | Content (AEO) | No pages answering acoustics-vs-soundproofing, school PA planning or AV maintenance questions | Three answer-first guides with FAQs, saved as **drafts** for engineer review | `content/blog/*.json`, `inc/blog-seed.php` (now supports `status`) |

Business entity also gains `keywords` (priority Kenyan keyword clusters) and `knowsLanguage` (English, Swahili).

## 3. Blocked: needs a business decision

| Item | Evidence | Needed |
|---|---|---|
| **Phone number conflict** | Header, footer, contact page, schema and llms.txt show **+254 182 942 946**; the Terms page shows **+254 728 135246**; the SEO prompt also records +254 728 135246 as the indexed number | Confirm the number on the Google Business Profile, then use it everywhere (Customizer > Contact and the Terms page) |
| Google Business Profile categories, services, photos and reviews | No access | GBP manager access |
| Search Console / GA4 baseline, URL inspection, Rich Results tests | No access in this session | Search Console + GA4 access, or run after upload |
| Featured images for the 3 new guides | None in the repo | Real VTECH project photos (falls back to the default share image) |

## 4. Competitor snapshot (homepages fetched 19 Oct 2026)

| Competitor | Title focus | Schema | llms.txt |
|---|---|---|---|
| Rivarb Media Group | Enterprise AV, LED walls, event production | WebSite, Corporation/LocalBusiness, WebPage, Breadcrumb | Yes |
| Sound Creations Ltd (Kenya) | AV and acoustic solutions, Nairobi since 2004 | Organization/ProfessionalService, WebSite, WebPage, Breadcrumb | No |
| PowerWave AV | Premium AV, boardroom, CCTV, maintenance | None found | No |
| AudioPhase Kenya | - | Site did not respond | - |
| The Audiophile | - | Blocked automated requests | - |

VTECH already has the deepest schema in this set (services, FAQs, projects, hire prices, brand aliases). The lasting advantage now comes from real project evidence, reviews and external references, not more markup.

## 5. Keyword-to-page map (one intent per URL)

| Cluster | Page |
|---|---|
| audio visual company Kenya, AV company Nairobi | `/` |
| sound / PA system installation Kenya | `/services/sound-systems/` |
| church sound system Kenya | `/services/church-sound-systems/` + cost guide |
| LED screen / video wall Kenya | `/services/led-screens/` |
| digital signage Kenya | `/services/digital-signage/` |
| conference room / boardroom AV | `/services/conference-systems/` |
| video conferencing installation Kenya | `/services/video-conferencing-systems/` |
| stage lighting Kenya | `/services/lighting/` |
| acoustic treatment, soundproofing Nairobi | `/services/acoustic-solutions/` + new guide |
| sound / PA / LED hire Nairobi | `/equipment-hire/`, `/hire-packages/` |
| AV maintenance Kenya | `/services/av-maintenance-support/` + new checklist |
| school PA system Kenya | `/industry/education/` + new guide |
| dB Technologies Kenya, Shure Kenya | brand page + brand guides |

## 6. Acceptance tests after uploading 5.60.0

1. `/llms.txt` lists Services, Industries, Hire packages and Projects.
2. `/llms-full.txt` returns text with every service and its FAQs.
3. `/robots.txt` shows the new crawlers and the llms-full.txt line.
4. A service page (e.g. church sound) passes the Rich Results Test and schema.org validator with the new Service node.
5. Homepage sameAs no longer contains wa.me.
6. `/equipment-brand/dbtechnologies/` title reads "dB Technologies in Kenya...".
7. Open wp-admin once: the three guides appear under Posts > Drafts.

## 7. 30 / 60 / 90 days

- **30:** fix the phone number, upload 5.60.0, review and publish the 3 guides, re-submit sitemap, record an AI-answer baseline (ChatGPT, Perplexity, Gemini, Copilot) for the 9 test questions in the brief.
- **60:** expand 3 project pages with real photos, scope and results (with client permission); request reviews from recent clients; correct dealer listings with dB Technologies and Shure distributors.
- **90:** compare Search Console clicks and impressions by cluster against the baseline; repeat the AI-answer tests; publish the next guides only where Search Console shows demand.
