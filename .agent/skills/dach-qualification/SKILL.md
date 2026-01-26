---
name: dach-qualification
description: Filter prospects so you only contact real, relevant, reputable DACH businesses that can plausibly lead to a collaboration/mention/exchange.
---

# DACH Qualification Agent (Prospect Worth Contacting)

## Purpose

Filter prospects so you only contact real, relevant, reputable DACH businesses that can plausibly lead to a collaboration/mention/exchange — while avoiding SEO farms, junk sites, and reputational risk.

## Inputs
- Prospect website URL
- Optional: niche keyword set, city/region, target audience
- Optional: competitor list (domains + brand names)

## Outputs
- Status: ✅ Approved / ❌ Rejected / ⚠️ Manual Review
- Confidence score: 0–100
- Reason codes (machine-readable)
- Suggested outreach angle (1 line, for Agent 3)

---

## 1) Evidence Collection (what the agent checks)

### A) Language & geo signals (DACH overlay)
- Website language predominantly German (DE/AT/CH)
- DACH signals:
    - .de / .at / .ch domain (nice, not required)
    - Address in DACH
    - Phone format +49/+43/+41
    - “Impressum”, “Datenschutz” present

> [!NOTE]
> Strong positive if Impressum exists and contains full business info.

### B) “Real business” signals (high importance)

**Pass signals**
- Impressum page exists and is reachable
- Business name + address + contact are present
- VAT ID or HRB/Handelsregister mention (optional but strong)
- Team/about page or clear service pages
- Consistent branding (logo, navigation, non-generic template)

**Red flags**
- No address, no company name, only a form
- Stock “lorem ipsum” sections
- Suspiciously thin, one-page site with vague claims

### C) Topical relevance (niche + city aware)

The agent should evaluate:
- Is the site in the same ecosystem or complementary?
- Is it:
    - supplier / partner / adjacent service?
    - local/regional player in same city/region?
    - an association / directory / chamber / network?
- Does it publish content that could naturally mention others?

**Bonus points**
- Has a “Partner”, “Referenzen”, “Kunden”, “Netzwerk” section
- Has blog/resources/case studies (for contextual mention opportunities)

### D) Competitor detection (protect brand)

**Reject if:**
- Same primary service + same city + direct competing offer

**Manual review if:**
- Partial overlap (e.g., same industry but different segment)

**How to detect:**
- Compare service keywords + pricing pages + location pages
- Domain match against competitor list (if provided)

---

## 2) Hard Reject Rules (instant ❌)

Reject immediately if any of these are true:

**Spam / SEO farm signals**
- “Write for us” / guest post for money
- Casino/crypto/adult/pharma mixed topics (topic drift)
- Hundreds of outbound links per page
- Obvious PBN footprint (thin posts, random categories, no real audience)
- “Submit your website” directory spam vibes

**Thin/low-trust site**
- No Impressum (for DACH this is a huge trust break)
- No real contact info
- Domain looks auto-generated / subdomain farms

**Unrelated niche**
- No plausible collaboration angle in one sentence

---

## 3) Manual Review Rules (⚠️)

Flag for manual review if:
- Impressum exists but the site looks outdated / messy (could still be a real business)
- Mixed language (DE+EN)
- Potential relevance but unclear
- Possible competitor overlap

**Manual review output should include:**
- “Why it might be good”
- “What’s risky”
- “What to check quickly” (2 bullets)

---

## 4) Scoring System (makes agent consistent)

Score from 0–100.

**Suggested weightings**
- Real business signals (Impressum + NAP): 0–40
- Topical relevance: 0–30
- Local/DACH relevance: 0–15
- Collaboration surface area (partners/blog/resources): 0–10
- Risk penalties (SEO farm indicators, thin site, competitor): -0 to -50

**Thresholds**
- ✅ Approved: 70+
- ⚠️ Manual review: 40–69
- ❌ Rejected: <40 or any hard reject hit

---

## 5) Output Reason Codes (for automation)

Examples:
- `DE_LANGUAGE_OK`
- `IMPRESSUM_FOUND`
- `NAP_FOUND`
- `TOPICAL_MATCH_HIGH`
- `LOCAL_MATCH_CITY`
- `HAS_PARTNER_PAGE`
- `SEO_FARM_SIGNAL`
- `NO_IMPRESSUM`
- `COMPETITOR_RISK`

This helps you debug and improve.

---

## 6) “Outreach Angle” Generator (feeds Agent 3)

If Approved, the agent produces 1 line:
- “Complementary services in same region”
- “Potential partner listing opportunity”
- “Local network/association mention”
- “Supplier/client ecosystem overlap”
- “Resource page / case study relevance”

This makes outreach feel real and not random.

---

## 7) Extra improvements you should add (high leverage)

✅ **Duplicate avoidance**
If the domain already contacted in last X days → reject or pause.

✅ **Authority sanity check (light)**
You don’t need DR, but check for:
- indexed pages (quick “site:” count)
- normal-looking traffic signals (not required)

✅ **Link opportunity detection**
Look for pages like:
- `/partner`
- `/netzwerk`
- `/referenzen`
- `/kunden`
- `/links`
- `/blog`
If found → raise score.
