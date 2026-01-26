---
description: Run the full DACH Connect pipeline: find B2B partners, qualify, research, and draft outreach.
---

# DACH Outreach Ecosystem Pipeline (B2B Edition)

This workflow chains 4 skills to go from a "search intention" to a "ready-to-send email" for B2B collaboration.

## Step 1: Prospecting (Find B2B Partners)
**Skill:** `dach-collaboration-outreach`

1.  Ask the user for the **Niche** and **City/Region**.
2.  Execute the `dach-collaboration-outreach` skill to find **real B2B companies** (potential partners, customers, or complementary businesses).
    *   *Search Strategy*: Look for "Partner", "Referenzen", or simply companies in complementary niches (e.g. if Client is "Arbeitsbühnen", look for "Maler", "Baumpflege", "Montage").
3.  **STOP and ASK**: Present the list of found companies to the user and ask them to **select prospects** to proceed with (or auto-select the best 2 for testing).

## Step 2: Qualification (Filter & Verify)
**Skill:** `dach-qualification`

1.  Take the **Selected URL** from Step 1.
2.  Execute the `dach-qualification` skill on this URL.
3.  **Check Output**:
    *   If `Status: ✅ Approved`, proceed to Step 3.
    *   If `Status: ❌ Rejected`, skip this prospect.
    *   If `Status: ⚠️ Manual Review`, ask user or proceed with caution.

## Step 3: Setup (Context & Research)
**Skill:** `outreach-setup-enhanced`

1.  Take the **Approved URL** from Step 2.
2.  Execute the `outreach-setup-enhanced` skill.
    *   Input: Company Domain, City, Niche.
3.  **Output**: Ensure you generate the full `Contact Brief` JSON.

## Step 4: Drafting (Write Email)
**Skill:** `initial-outreach-enhanced`

1.  Take the **Contact Brief JSON** from Step 3.
2.  Execute the `initial-outreach-enhanced` skill.
    *   Input: The JSON object from the previous step.
3.  **Output**: Generate the final email draft (Subject + Body).

---

## Usage Example
> "Run the DACH outreach pipeline for 'Arbeitsbühnen' in 'Würzburg'."
