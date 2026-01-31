---
name: dach-collaboration-outreach
description: Find B2B collaboration partners (referral partners, agencies, complementary businesses) and extract high-value contact info including CEO LinkedIn profiles.
---

# DACH Collaboration & Outreach Discovery

## Purpose
Find legitimate B2B companies in a specific niche and city that are suitable for partnership, collaboration, or referral networks. Unlike directory building, this targets **individual company websites**.

## Inputs
*   **Mode** (Required):
    *   `Discovery`: Find candidate companies and URLs only. (Fast, low cost).
    *   `Enrichment`: Take a list of URLs/Companies and find contact info. (Deep, high value).
*   **Target Niche** (e.g., Webdesign, Maler, Architekt)
*   **City / Region** (e.g., Berlin, DACH-wide)
*   **Input List** (For Enrichment Mode): List of companies/URLs to process.

## Output Format (Strict)

### Mode: Discovery
| Company | Website | City | Niche | Source |
| :--- | :--- | :--- | :--- | :--- |
| Sample GmbH | sample.de | Berlin | Maler | Google |

### Mode: Enrichment
| Company | Website | City | Industry | Contact URL | Email | Linked In Page of CEO and Manager | Source |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| Sample GmbH | sample.de | Berlin | SEO | sample.de/kontakt | info@sample.de | linkedin.com/in/ceo-name | Google |

## Process & Logic

### Mode: Discovery (Phase 1)
Use "reciprocal" footprints to find companies that activeley list partners or are clearly in the target niche.

**Search Query Examples:**
*   `[Niche] [City] "Partner werden"`
*   `[Niche] [City] "Unsere Partner"`
*   `[Niche] [City] "Kooperationspartner"`
*   `[Niche] [City] "Referenzen"` (Good for finding active companies)
*   `related:[CompetitorDomain]`

**Action**:
1.  Search Google using the footprints.
2.  Extract Company Name and URL from the SERP or landing page.
3.  Filter out directories (Yelp, Gelbe Seiten, etc.).
4.  Return the Clean Candidate List.

### Mode: Enrichment (Phase 2)
For each approved partner in the Input List:

1.  **Validate**: Is it a real company? (Not a directory, not a blog post).
2.  **Extract Basic Info**: Get Name, City, Industry from the specific landing page.
3.  **Find Email**: Check `mailto:` links, Footer, legal notice (Impressum).
4.  **Find Decision Maker (CEO/Manager)**:
    *   **Step A**: Go to `Impressum` (Legal Notice) - typically lists "Geschäftsführer" (CEO) or "Inhaber" (Owner).
    *   **Step B**: Go to `Über uns` / `Team` pages if Impressum is generic.
    *   **Step C**: **LinkedIn Search**. Once the name is found (e.g., "Max Mustermann"), perform a targeted search:
        *   `site:linkedin.com "Max Mustermann" "[Company Name]"`
    *   **Step D**: Extract the LinkedIn Profile URL.

## Rules
*   **No Directories**: Skip results like Gelbe Seiten, LinkedIn (company pages), Yelp, etc. We want specific company domains.
*   **Privacy**: Use publicly available business contact info only (Impressum/LinkedIn).
*   **Accuracy**: If CEO LinkedIn is not found, state "Not found" rather than guessing.
