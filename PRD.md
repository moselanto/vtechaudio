# Product requirements - vtechaudio.co.ke

## 1. Purpose
Make VTECH Audio Visual Solutions the most visible and trusted AV integrator for relevant searches in Nairobi and Kenya, and turn that visibility into qualified enquiries, quotes and bookings.

## 2. Users
| User | Needs |
|---|---|
| Buyers (churches, corporates, hotels, schools, hospitals, government, event organisers) | Understand services, see proof, get a quote fast by form, phone or WhatsApp |
| Event hirers | See packages and prices, check availability, book |
| VTECH staff | Edit content without code, receive and track leads, issue quotes and invoices |
| Search engines and AI assistants | Accurate, consistent, machine-readable business facts |

## 3. Goals and measures
| Goal | Measure |
|---|---|
| Search visibility | Search Console impressions and clicks for Kenyan AV queries |
| AI visibility | Mentions in ChatGPT, Perplexity, Gemini for the 9 baseline questions (baseline 19 Oct 2026: 7/9, 0/9, 0/8) |
| Leads | GA4 key events: generate_lead, phone_click, whatsapp_click |
| Local trust | Google Business Profile reviews (baseline 0) |
| Reliability | Forms never lose a submission; zero schema errors |

## 4. Functional requirements
1. Pages: home, about, services (11), industries (9), projects, brands, equipment hire, hire packages, blog, FAQ, consultation, hire request, contact, terms, privacy.
2. Contact details set once (Customizer) and reused everywhere. Phone: +254 182 942 946.
3. Lead capture by form and WhatsApp popup with reference numbers, stored privately and emailed.
4. Quote and invoice documents, client acceptance, inventory-locked bookings, optional deposit payment.
5. SEO: unique titles/descriptions, canonical, sitemap, breadcrumbs, structured data that matches visible content, llms.txt files.
6. Analytics: GTM + GA4 with lead events.
7. Staff editing without code for homepage sections, service content and FAQs.

## 5. Non-functional requirements
- Security: see `SECURITY-AUDIT.md` (rate limits, nonces, sanitisation, escaping, session expiry, headers).
- Performance: mobile-first, critical CSS, WebP, no jQuery front end.
- Accessibility: one H1 per page, alt text, keyboard-usable forms.
- Accuracy: no invented prices, reviews, ratings, projects or credentials.

## 6. Out of scope
Online shop checkout, client portal accounts, manufacturer dealer listings and directory submissions (owner decision, 19 Oct 2026).

## 7. Roadmap
| When | Item |
|---|---|
| Now | Collect genuine Google reviews; add opening month to Business Profile |
| 30 days | Re-run AI answer test (18 Nov 2026); index Corporate and Events industry pages |
| 60 days | Detailed case studies with real photos and results (with client permission), especially acoustics |
| 90 days | Compare Search Console and GA4 against baseline; publish new guides only where demand exists |
