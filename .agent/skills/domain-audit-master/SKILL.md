---
name: domain-audit-master
description: Check the domain using a comprehensive audit checklist covering technical health, mobile/UX, on-page SEO, content authority, analytics, local SEO, off-page SEO, and competitive snapshot.
---

# Domain Audit Master

Depending on the tools available, execute as many of the following checks as possible.
Use `browser` to visually inspect pages and run lighthouse reports if possible, or check mobile responsiveness.
Use `curl` or `read_url_content` to fetch headers and HTML for parsing.
Use `search_web` to finding indexation status (`site:domain.com`), backlinks, and competitors.

🔹 INPUT
	•	Website URL: [INSERT DOMAIN HERE]
	•	(Optional) Google Business Profile link: [INSERT IF AVAILABLE]

⸻

🔍 AUDIT CHECKLIST (RUN ALL SECTIONS)

1️⃣ Technical Website Health
	•	SSL certificate (valid / invalid / mixed content)
	•	Indexability (robots.txt, meta robots)
	•	Canonical tags (correct / missing / duplicates)
	•	Sitemap.xml (exists, clean, submitted)
	•	Page speed (desktop & mobile)
	•	Core Web Vitals (LCP, CLS, INP)
	•	CDN usage (yes / no)
	•	Server response & hosting quality
	•	Cache headers
	•	Broken links (internal & outbound)
	•	Incorrect URL formats (http/https, trailing slashes)

⸻

2️⃣ Mobile & UX
	•	Mobile responsiveness
	•	Tap targets & font size
	•	Layout shifts
	•	Accessibility basics (alt text, contrast)
	•	Navigation clarity
	•	CTA visibility
	•	Chat / contact options
	•	Social links present & functional
	•	Favicon present

⸻

3️⃣ On-Page SEO
	•	Title tags (length, uniqueness, intent)
	•	Meta descriptions (CTR-focused, unique)
	•	H1–H3 structure (logical, keyword-aligned)
	•	Keyword usage (primary & secondary)
	•	Service / product paragraph definitions
	•	Internal linking structure
	•	Image optimization (size, alt text, filenames)
	•	Schema / rich results (LocalBusiness, Services, FAQ if relevant)
	•	OpenGraph & social meta tags

⸻

4️⃣ Content & Authority
	•	Core service pages present or missing
	•	Location relevance (if local business)
	•	Blog / updates section (active / inactive)
	•	Content depth vs competitors
	•	Trust elements (team, story, credentials)
	•	Reviews embedded on site (yes / no)
	•	Email consistency (old vs new emails indexed)

⸻

5️⃣ Analytics & Tracking
	•	Google Analytics installed (GA4)
	•	Google Tag Manager installed
	•	Conversion tracking present
	•	Event tracking (forms, calls, buttons)
	•	Google Search Console connected
	•	Bing Webmaster Tools connected

⸻

6️⃣ Local SEO (If Applicable)
	•	Google Business Profile:
	•	Ownership status
	•	Primary & secondary categories
	•	Services listed (and matching website)
	•	Address & NAP consistency
	•	Phone indexed
	•	Opening hours
	•	Photos & branding quality
	•	Reviews (count, rating, replies)
	•	Posts / updates activity
	•	Apple Maps listing
	•	Citations consistency
	•	Maps ranking (top 3 snapshot if possible)

⸻

7️⃣ Off-Page SEO
	•	Backlink profile quality
	•	Referring domains
	•	Anchor text distribution
	•	Spam risk
	•	Best backlinks (authority score)
	•	Competitor backlink comparison
	•	Citations presence
	•	Brand mentions

⸻

8️⃣ Competitive Snapshot
	•	Top 3 organic competitors
	•	Top 3 Maps competitors
	•	Keyword overlap
	•	Content gap vs competitors
	•	Review gap vs competitors

⸻

📊 OUTPUT FORMAT (MANDATORY)
	1.	Executive Summary (TL;DR)
	•	Overall health score (Low / Medium / High)
	•	Biggest risks
	•	Biggest leverage points
	2.	Issues (Grouped by Priority)
	•	Critical
	•	Important
	•	Nice to have
	3.	Quick Wins (≤14 days)
	4.	Strategic Recommendations (30–90 days)
	5.	SEO Readiness Verdict
	•	Ready for Ads? (Yes / No / Conditional)
	•	Ready for Scaling SEO? (Yes / No)

⸻

❗ RULES
	•	Be factual, concise, and structured
	•	Do not guess data you cannot verify
	•	Clearly say “Not detectable” where needed
	•	No fluff, no generic SEO advice
