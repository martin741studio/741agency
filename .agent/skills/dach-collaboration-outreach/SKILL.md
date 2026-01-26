---
name: dach-collaboration-outreach
description: Find B2B collaboration partners (referral partners, agencies, complementary businesses) and extract high-value contact info including CEO LinkedIn profiles.
---

# DACH Collaboration & Outreach Discovery

## Purpose
Find legitimate B2B companies in a specific niche and city that are suitable for partnership, collaboration, or referral networks. Unlike directory building, this targets **individual company websites**.

## Inputs
*   **Target Niche** (e.g., Webdesign, Maler, Architekt)
*   **City / Region** (e.g., Berlin, DACH-wide)
*   **Collaboration Type** (optional): "Partner", "Referenzen", "Kooperation"

## Output Format (Strict)
The output must be a table or structured list with **exactly** these columns:
1.  **Company** (Name)
2.  **Website** (URL)
3.  **City**
4.  **Industry**
5.  **Contact URL** (Link to Impressum or Kontakt)
6.  **Email** (Extracted from footer/impressum)
7.  **Linked In Page of CEO and Manager** (The most critical high-value field)
8.  **Source** (Where the partner was found, e.g., Google Search, Competitor Link)

## Process & Logic

### Phase 1: Discovery (Search)
Use "reciprocal" footprints to find companies that activeley list partners or are clearly in the target niche.

**Search Query Examples:**
*   `[Niche] [City] "Partner werden"`
*   `[Niche] [City] "Unsere Partner"`
*   `[Niche] [City] "Kooperationspartner"`
*   `[Niche] [City] "Referenzen"` (Good for finding active companies)
*   `related:[CompetitorDomain]`

### Phase 2: Deep Extraction (The Agent's Core Job)
For each potential partner found:

1.  **Validate**: Is it a real company? (Not a directory, not a blog post).
2.  **Extract Basic Info**: Get Name, City, Industry from the specific landing page.
3.  **Find Email**: Check `mailto:` links, Footer, legal notice (Impressum).
4.  **Find Decision Maker (CEO/Manager)**:
    *   **Step A**: Go to `Impressum` (Legal Notice) - typically lists "Geschäftsführer" (CEO) or "Inhaber" (Owner).
    *   **Step B**: Go to `Über uns` / `Team` pages if Impressum is generic.
    *   **Step C**: **LinkedIn Search**. Once the name is found (e.g., "Max Mustermann"), perform a targeted search:
        *   `site:linkedin.com "Max Mustermann" "[Company Name]"`
    *   **Step D**: Extract the LinkedIn Profile URL.

### Phase 3: Reporting
Present the data clearly.

| Company | Website | City | Industry | Contact URL | Email | Linked In Page of CEO and Manager | Source |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| Sample GmbH | sample.de | Berlin | SEO | sample.de/kontakt | info@sample.de | linkedin.com/in/ceo-name | Google |

## Rules
*   **No Directories**: Skip results like Gelbe Seiten, LinkedIn (company pages), Yelp, etc. We want specific company domains.
*   **Privacy**: Use publicly available business contact info only (Impressum/LinkedIn).
*   **Accuracy**: If CEO LinkedIn is not found, state "Not found" rather than guessing.
