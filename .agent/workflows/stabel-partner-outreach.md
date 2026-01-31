---
description: Run the Agency-led outreach pipeline for Arbeitsbühnen Stabel to find partners for content exchange and domain authority booking.
---

# Stabel Partner Outreach Pipeline (Agency Edition)

This workflow is specifically for **Arbeitsbühnen Stabel** to find B2B partners via **741.Studio (Agency)**.

## Step 1: Discovery & Pre-Filter
**Skills:** `dach-collaboration-outreach` (Mode: Discovery) + `dach-qualification`

1.  **Search**: Find candidates in the target Niche/City using `dach-collaboration-outreach` (Mode: Discovery).
2.  **Pre-Qualify** (Optional but recommended): Run `dach-qualification` on the found URLs to remove obvious spam/non-DACH sites automatically.
3.  **Output**: Create a file `leads/PENDING-stabel-[Niche]-[City].md` with the list of candidates.
    *   **Columns**: Company, Website, City, Status (e.g., "Pending Review").

## Step 2: User Review (The "Soft Negotiation" Loop)
**Action:** 🛑 **STOP and Ask User for Review** 🛑

1.  Present the `leads/PENDING-...md` file to the user.
2.  **Instruction**: "Please review the list in `[Filename]`. Delete any rows you do NOT want to contact. Mark the rest as 'Approved'."
3.  **Wait** for user confirmation before proceeding.

## Step 3: Deep Enrichment & Drafting
**Skills:** `dach-collaboration-outreach` (Mode: Enrichment) + `outreach-setup-enhanced` + `agency-link-collaboration`

1.  **Input**: Read the *reviewed* file from Step 2.
2.  **Enrichment**: For every company left in the list:
    *   Run `dach-collaboration-outreach` (Mode: Enrichment) to find CEO/Manager LinkedIn and Email.
3.  **Context**: Run `outreach-setup-enhanced` to generate the Contact Brief and personalization hooks.
4.  **Drafting**: Immediately generate the outreach email using `agency-link-collaboration`.
    *   **Arguments**: Client="Arbeitsbühnen Stabel", Agency="741.Studio".
5.  **Final Output**: Save the fully enriched and drafted leads to `leads/READY-stabel-[Niche]-[City].md`.

## Usage Example
> "Start the Stabel partner outreach for Painters in Würzburg. Let me review the list before you draft emails."
