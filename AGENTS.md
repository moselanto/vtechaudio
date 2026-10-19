# Instructions for AI agents working on this repository

Read in this order: `AGENTS.md` -> `vtech-av/docs/HANDOVER-2026-10.md` -> `ARCHITECTURE-ESSENTIALS.md` -> `SECURITY-AUDIT.md` -> `ARCHITECTURE.md` -> `PRD.md` -> `vtech-av/docs/SEO-GEO-AUDIT-2026-10.md`.

## Ground rules
1. **Do not remove existing functionality** without the owner's explicit approval.
2. **Commit directly to `main`** in small, focused commits. Bump `VTECH_VERSION` (`vtech-av/functions.php`) and `Version:` (`vtech-av/style.css`) on every theme change.
3. **No PHP runtime may be available to you.** Keep changes small, check brace balance, and list a re-test for the owner after upload.
4. **Deployment is manual:** the owner uploads the `vtech-av` folder; tell them exactly what to upload, then verify on the live site.
5. **Never invent** prices, reviews, ratings, clients, projects, certifications or performance claims. The business does not publish fixed prices except real hire package prices.
6. **Contact details:** phone/WhatsApp +254 182 942 946, info@vtechaudio.co.ke, Ground Floor, Mpaka Plaza, Mpaka Road, Westlands, Nairobi, Mon-Fri 8:00-18:00, Sat 8:00-18:30. Change them only in the Customizer / `inc/contact.php`.
7. **Schema** must match visible content; one business entity (`#organization`); no WhatsApp links in sameAs.

## Security rules
- Sanitise input, escape output, `$wpdb->prepare()` for SQL.
- New admin action: nonce + capability check. New public endpoint: honeypot + `vtech_rate_limited()` + bounded fields.
- No secrets in code; use `wp-config.php` or host environment variables.
- Raw `header()` only in `template_redirect` / REST filters with `headers_sent()` check.

## Known state (19 Oct 2026, theme 5.65.0 in repo, 5.64.1 live)
- Full handover: `vtech-av/docs/HANDOVER-2026-10.md`. Keyword targets: `vtech-av/docs/KEYWORD-MAP-2026-10.md`.
- Search Console / Bing / GA4 / GTM / Business Profile are under mosetivincent4@gmail.com. Bing is verified via Search Console import.
- Ranking work lives in `vtech-av/inc/seo-rank-push.php` (5.62.0 to 5.65.0). Seeds run on admin_init; seed dates must be in the past (site time) or WordPress schedules the post.
- A host/Cloudflare cache can serve stale pages after upload; verify with `?nocache=1` and ask the owner to purge.
- Google Request indexing often fails or hits the daily quota; Bing URL Submission works.
- Business Profile: 0 reviews; opening month not set (needs owner).
- Owner decisions: no dealer listing outreach, no directory submissions; Copilot excluded from AI tests.
- Next AI answer test: 18 Nov 2026 (`vtech-av/docs/AI-ANSWER-BASELINE-2026-10-19.md`).
