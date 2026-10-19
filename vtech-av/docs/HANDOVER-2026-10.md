# VTECH Handover: work done 11 to 19 October 2026

Read this with `AGENTS.md` before continuing. Repo `moselanto/vtechaudio`, branch `main`. Live site https://vtechaudio.co.ke (WordPress, theme `vtech-av` + `vtech-av-child`). Work is done for Pimofy Digital LLP (Moses Munyi) on behalf of VTECH Audio Visual Solutions.

## 1. Current state (19 Oct 2026, 20:40 EAT)
| Item | State |
|---|---|
| Theme in repo | **5.65.0** (commit cf6b4b8) |
| Theme live | **5.64.1** confirmed live; 5.65.0 committed, NOT yet uploaded |
| GitHub Pages | Enabled on repo by mistake; irrelevant to the site. Owner told to set Settings > Pages > Source: None |
| Cache | A host/Cloudflare page cache serves stale copies of /, /services/led-screens/, /services/conference-systems/, /industry/healthcare/. `?nocache=1` shows the new content. Owner asked to purge host/Cloudflare cache |

## 2. Releases (summary)
| Version | What |
|---|---|
| 5.43 to 5.59 | GBP service pages, WhatsApp popup, branded login, brand/entity SEO, llms.txt, Rank Math seeding, GA4/GTM events, page redesigns (Services, Industries, Hire packages, About, Equipment hire, FAQ, Blog), mobile bar, share image, 6 articles, service FAQs + guide links, new logo |
| 5.60.0 to 5.60.5 | GEO/schema audit fixes: /llms-full.txt, llms.txt fix, Service schema, brand + booking schema, 19 AI crawlers allowed, 3 guides (acoustics, school PA, AV maintenance) with photos |
| 5.61.0 | Security release: `inc/security-hardening.php` (rate limits, login lockout, 12h sessions, 12-char passwords, CORS on vtech/v1, noindex/no-store on quote pages), hire-module/accept-booking/equipment-catalog/inventory hardening. Docs: AGENTS.md, ARCHITECTURE*.md, PRD.md, SECURITY-AUDIT.md |
| 5.62.0 | `inc/seo-rank-push.php`: LED + boardroom buyer content (tables, extra FAQs + schema), Nairobi page `/audio-visual-company-nairobi/` (Service + FAQ schema, Nairobi areas), hospital PA content on `/industry/healthcare/`, new `/industry/banking/`, proof block (recent projects, brands, coverage) on every service page |
| 5.63.0 | 7 guides in `content/blog/*.json`: pa-system-kenya-guide, how-to-choose-audio-visual-company-kenya, video-conferencing-system-kenya-guide, digital-signage-kenya-guide, stage-lighting-kenya-guide, background-music-system-hotels-restaurants-kenya, conference-delegate-microphone-system-kenya; linked via `vtech_service_guide_map` |
| 5.64.0 | "Popular in Kenya" footer links (18-19 keyword links, published pages only); `docs/KEYWORD-MAP-2026-10.md` |
| 5.64.1 | Fix: 5.63.0 guides were saved as Scheduled (evening seed dates). admin_init publishes them with 10:00 dates. Lesson: **seed dates must be in the past in site time** |
| 5.65.0 | AV Planner `/av-planner/` (shortcode `[vtech_av_planner]`, `assets/js/av-planner.js`): LED size + pixel pitch calculator and boardroom planner, WebApplication + FAQ schema, linked from LED/boardroom pages and footer |

Seeds (Nairobi page, Banks page, guides, planner page) run on `admin_init` when an administrator opens wp-admin, once, never overwriting existing content.

## 3. Accounts and findings
All under **mosetivincent4@gmail.com** (Search Console, GA4, GTM, Bing, Google Business Profile). soundcreationsrwanda@gmail.com does NOT have VTECH properties. Never use Sound Creations Kenya/Rwanda logins for VTECH.
- **Search Console:** sitemap_index.xml OK. ~50 indexed. Request indexing has repeatedly failed ("Something went wrong") and the daily quota was exhausted on 19 Oct. Corporate and Events industry pages not indexed yet.
- **Search performance (13 Aug to 17 Oct):** 33 clicks, 488 impressions, avg position 13.5. Brand "vtech audio" pos 1.1. Weak: LED video wall 56, conference system 59, boardroom AV installation 59.5, kenya bank av installation 70, church speakers 61. Promising: av companies near me 7, hospital pa system 7.5, audio visual kenya 10.5.
- **Bing:** verified, sitemap OK; 17 URLs submitted on 19 Oct incl. the 9 new pages.
- **GA4:** G-NZ4SE0NDNG (property 558632590). generate_lead, phone_click, whatsapp_click fire; purchase/qualify_lead/close_convert_lead never fired (suggest removing purchase).
- **GTM:** GTM-WFRJWTHC v2.
- **GBP:** main category Audio visual consultant; 8 services moved there; "Home audio store" removed; address "Ground Floor, Mpaka Plaza, Mpaka Road, Westlands, Nairobi"; 0 reviews; opening month (year 2021) still needed from owner. Review link https://g.page/r/CSSMsI0DGoj5ECE/review.
- **AI answer baseline (19 Oct):** ChatGPT 7/9 mention, Perplexity 0/9, Gemini 0/8, Copilot not tested (owner has no Microsoft login). See `AI-ANSWER-BASELINE-2026-10-19.md`.
- **Competitors seen in Kenya results:** Clarity Solutions, Rivarb, Perlogy, Granular IT, Proscene, Talinda, Adcents, GZEP, PS Audio Visual, StagePass, Sight & Sound, Stepman, Sound Creations.

## 4. Owner decisions (do not revisit unless the owner raises them)
- No manufacturer dealer-listing outreach (dB Technologies, Shure) and no directory/listicle submissions.
- Skip Copilot in AI tests.
- Phone/WhatsApp is +254 182 942 946 only; +254 728135246 is outdated.
- Never invent prices, reviews, ratings, clients or results.

## 5. Open actions
| Action | Owner | When |
|---|---|---|
| Upload 5.65.0, open wp-admin, purge plugin + host/Cloudflare cache | VTECH | Now |
| Test AV Planner calculators on a phone | VTECH | After upload |
| Retry Google Request indexing (quota resets daily): Nairobi, banking, planner, 7 guides, LED, conference, healthcare, corporate, events | Agent (scheduled 20 Oct ~10:00 EAT) | 20 Oct |
| 10 to 15 genuine Google reviews | VTECH | Ongoing |
| 2 to 3 real case studies with photos (LED, boardroom, church, hospital, bank) | VTECH + Pimofy | Next 60 days |
| GBP opening month | VTECH | Now |
| Two-step login for admins; Editor role for content staff | VTECH | This week |
| Turn off GitHub Pages | VTECH | Any time |
| AI answer re-test vs baseline | Agent (scheduled) | 18 Nov 2026 |

## 6. Client documents
- Pimofy-format report "VTECH Website, SEO, AEO & GEO Work Report" (.docx, ref PMF-VTA-RPT-2026-01) saved in the owner's Town library, VTECH Audio collection. It covers work up to 5.61.0; releases 5.62.0 to 5.65.0 are not in it yet.
