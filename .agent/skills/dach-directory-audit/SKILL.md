---
name: dach-directory-audit
description: Audits a business's presence across DACH directories and maps (Tier 0-4), checks for registration, and generates a priority action plan.
---

# DACH Directory & Maps Registration Audit Agent

## Purpose

Given a client’s website URL, this agent audits where the business is already registered across a tiered DACH list (Tier 0–4) and outputs:
*   ✅ Registered / Found
*   ⚠️ Possible match (needs human review)
*   ❌ Not found / Missing
*   🔒 Needs login / access required

Then it generates a priority action plan (“fill these first”).

## Inputs

*   **Client website URL** (primary)
*   **Optional** (if available later, improves match accuracy):
    *   Business name + legal name
    *   Address (street, city, postcode)
    *   Phone
    *   Email
    *   Brand variations (e.g., STABEL / STABEL Lift etc.)

If not provided, the agent should pull what it can from the website:
*   Imprint (Impressum)
*   Contact page
*   Footer NAP
*   Schema markup (LocalBusiness)

## What it checks (by Tier)

### Tier 0 (Mandatory - Ecosystems & Maps)
*   Google Business Profile
*   Apple Maps (Apple Business Connect)
*   Bing Places for Business
*   Here WeGo (major map data provider)
*   TomTom (major map data provider)
*   OpenStreetMap
*   Facebook (Local Pages)
*   Instagram (Location Tags)
*   LinkedIn (Company Page)
*   WhatsApp Business

### Tier 1 (High-Trust / Traditional DACH)
*   Gelbe Seiten (gelbeseiten.de)
*   Das Örtliche (dasoertliche.de)
*   Das Telefonbuch (dastelefonbuch.de)
*   11880.com
*   WLW (Wer Liefert Was - wlw.de)
*   Kompass (kompass.com)
*   Creditreform (creditreform.de)
*   Northdata (northdata.de)
*   MeineStadt (meinestadt.de)
*   GoYellow (goyellow.de)
*   Cylex (cylex.de)
*   Infobel (infobel.com)
*   Unternehmensverzeichnis.org
*   Europages

### Tier 2 (General Business & Reviews)
*   Golocal (golocal.de)
*   Trustpilot (de.trustpilot.com)
*   ProvenExpert
*   YellowMap (yellowmap.de)
*   Stadtbranchenbuch (stadtbranchenbuch.com)
*   Marktplatz Mittelstand
*   Dialo (dialo.de)
*   Hotfrog (hotfrog.de)
*   Webwiki (webwiki.de)
*   Yalwa (yalwa.de)
*   Brownbook (brownbook.net)
*   Tupalo (tupalo.de)
*   Koomio (koomio.com)
*   Finden.de
*   Auskunft.de

### Tier 3 (Niche / Secondary)
*   MisterWhat (misterwhat.de)
*   Branchen-Domain.de
*   Branchenbuchdeutschland.de
*   Exilon.de
*   Tuugo (tuugo.de)
*   Zizado (zizado.de)
*   Werkenntdenbesten (werkenntdenbesten.de)
*   Focus Branchensuche
*   Wirtschaftswoche Branchenbuch
*   Opendi (opendi.de)

### Tier 4 (Long Tail / Regional placeholders)
*   Regional examples: Berlin.de, Hamburg.de, Muenchen.de, etc. (Agent should check city-specific portals)
*   Industry specific: Jameda, Sanego, Anwalt.de, Houzz, Tripadvisor (dependent on business type)

## Matching logic (how it decides “found”)

### Strong match (✅)
*   Same business name AND same city
*   **AND** at least one:
    *   Same phone
    *   Same street
    *   Same domain listed
    *   Same brand name + unique keyword

### Possible match (⚠️)
*   Name is similar, city matches, but missing phone/address
*   **OR**
*   Listing exists but appears outdated / duplicate

### Not found (❌)
*   No matching listing discovered via:
    *   Internal site search (if any)
    *   Google “site:” query footprints
    *   Directory’s own search endpoint (if public)

### Access required (🔒)
*   The listing exists but the agent can’t verify edits without credentials

## Footprints the agent uses to verify presence (Germany-first)

For each platform, it generates query patterns like:
*   `site:DOMAIN "Brand" "City"`
*   `site:DOMAIN "Telefon" "City"`
*   `site:DOMAIN arbeitsbuehne-wuerzburg.de`
*   `site:DOMAIN "Adresse snippet"`

### Example generic templates:
*   `site:wlw.de "{business_name}" "{city}"`
*   `site:kompass.com "{business_name}" "{city}"`
*   `site:cylex.de "{business_name}" "{city}"`
*   `site:11880.com "{phone}"`

Also direct directory search if available.

## Outputs (what the agent returns)

### A) Audit table (client-ready)

Columns:
*   Tier
*   Site/Domain
*   Status (✅/⚠️/❌/🔒)
*   Found URL (if any)
*   Match confidence (0–100)
*   Notes (duplicate/outdated/missing NAP/etc.)
*   Next action (create / claim / update)

### B) Priority plan (action list)
1.  Fix Tier 0
2.  Fill Tier 1
3.  Fill Tier 2 (top 5)
4.  Optional fillers (Tier 3–4)

### C) NAP consistency report
*   Business name variant used
*   Address formatting differences
*   Phone format differences
*   Website URL differences (http/https, trailing slash)

## Automation boundaries (important)

This agent should audit and generate tasks, **not blindly create accounts**.

**Recommended workflow:**
*   Audit → human approves → publishing agent executes
