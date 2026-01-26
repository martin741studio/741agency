---
name: 741-service-pricings-proposals
description: "Generates structured proposals and invoice logic for 741.Studio. Uses a Tier-based system (T1-T4) mapped to hourly estimates to ensure transparent value justification."
---

# Pricing & Proposal Generator (741.Studio)

## Core Logic

Use this skill whenever the user asks to:
1.  **Create a Proposal:** Generate a scope of work with estimated hours and pricing.
2.  **Estimate a Project:** Break down a loose request into specific services and tiers.
3.  **Justify Invoicing:** Explain costs by mapping delivered work back to the hourly values.

### 1. The Anchor Rates
These are the internal multipliers. Use them to calculate totals, but verify if the user wants to explicitly show the rate or just the total hours/fee.
*   **$65 / hour** → Standard / New Clients / Short-term / Ad-hoc
*   **$55 / hour** → Long-term Collaboration / Retainers / Bundled Work

### 2. The Tier System (Value Mapping)
Map all work to these tiers to show *type* of value, not just "time".

| Tier | Name | Focus | Typical Work |
| :--- | :--- | :--- | :--- |
| **T1** | **Strategic / High-Leverage** | Thinking > Execution | Strategy, Audits, Planning, Workshops |
| **T2** | **Core Execution** | Skilled Implementation | Ads, SEO, CRO, Local SEO |
| **T3** | **Support / Systems** | Maintenance & Consistency | CRM, Automation, Content Systems |
| **T4** | **Physical / Hybrid** | Setup & Entry Points | NFC, Kits, Displays, Physical Products |

---

## Service Catalog & Estimation Ranges

When generating a proposal, pick relevant modules and select an hour value within the specific range based on client size/complexity.

### 1️⃣ Strategy & Oversight (T1)
*   **Retainer:** 2–6 hrs/mo
*   **One-off Strategy:** 3–8 hrs
*   *Note: Highest margin, high authority.*

### 2️⃣ Paid Advertising (Google / Meta) (T2)
*   **Small Accounts:** 6–10 hrs/mo
*   **Medium Accounts:** 10–15 hrs/mo
*   **Scaling/Large:** 15–25 hrs/mo

### 3️⃣ SEO (Strategy + Execution) (T1 + T2)
*   **Strategy & Audits (T1):** 2–4 hrs
*   **On-page & Structure (T2):** 6–12 hrs
*   **Authority Building (T2):** Ongoing (Custom)

### 4️⃣ Website & CRO (T2)
*   **Landing Page:** 8–15 hrs (Project)
*   **Full Site:** 25–60 hrs (Project)
*   **CRO Optimization:** 4–10 hrs/mo

### 5️⃣ Local SEO & Google Business (T2)
*   *High ROI, pairs with Physical Products.*
*   **Setup:** 6–10 hrs
*   **Monthly Optimization:** 3–6 hrs

### 6️⃣ Content & Media (T3)
*   *System & Guidance, not just "posting".*
*   **Strategy & Planning:** 2–4 hrs
*   **Execution (AI-Supported):** 4–8 hrs

### 7️⃣ Outreach & Partnerships (T1 + T3)
*   **Strategy (T1):** Planning & Targeting
*   **Execution (T3):** System management
*   *Total Range:* Typically 5-15 hrs/mo depending on volume.

### 8️⃣ Email, CRM & Lifecycle (T3)
*   **Setup:** 6–15 hrs
*   **Optimization:** 2–5 hrs/mo

### 9️⃣ AI, Automation & Systems (T3)
*   **Discovery:** 2–4 hrs
*   **Build:** 6–20 hrs (Project)
*   **Maintenance:** 1–3 hrs/mo

### 🔟 Training & Enablement (T1)
*   **Workshops:** 2–6 hrs
*   **Mentoring:** Hourly

---

## Physical Product Anchors (T4)
These are "Trojan Horses" to start relationships. Low hardware cost, high setup value.
*   **NFC Review / Contact Plate:** 1–2 hrs setup. ("Local Trust Setup")
*   **QR + NFC Local Trust Kit:** 2–3 hrs setup + 1 hr/mo monitoring.
*   **Desk / Counter CTA Displays:** 1–2 hrs setup.
*   **Digital Health Check Card:** 2–4 hrs setup. (Sales enablement tool).
*   **In-store Tablet:** 4–8 hrs setup.
*   **Client Onboarding Box:** 1–2 hrs setup.
*   **NFC Physical Stand:** 0 hrs setup. Cost: ~$40/day.

---

## Proposal Output Format

When asked to write a proposal, follow this structure:

### [Client Name] Growth Proposal

**Executive Summary:**
[1-2 sentences on the goal]

**Proposed Scope:**

| Service Component | Tier | Est. Monthly Hours | Deliverables / Focus |
| :--- | :--- | :--- | :--- |
| **[Service Name]** | [Tn] | [Range] | [Key Outcome 1]<br>[Key Outcome 2] |
| **[Service Name]** | [Tn] | [Range] | [Key Outcome 1]<br>[Key Outcome 2] |

**Investment Breakdown:**
*   **Total System Hours:** ~[Sum] hours / month
*   **Applied Rate:** $[Rate] / hour ([Reason for rate, e.g. "Long-term Partner Rate"])
*   **Monthly Investment:** $[Total Cost]

*Note: Hours are estimated based on standard execution times. Unused hours in T3 can be rolled over or swapped for T1 strategy sessions upon request.*
