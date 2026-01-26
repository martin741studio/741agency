---
name: modeling-product-finance
description: Analyzes product ideas or purchases by mapping all costs, pricing, marketing, logistics, cash flow, and financing options. Use when the user asks to plan a new product, evaluate profitability, model costs, or check financial viability.
---

# Product Budgeting, Cost Modeling & Financing

## When to use this skill
- When the user introduces a new product idea or potential purchase.
- When the user asks for a financial viability check (break-even, profitability).
- When the user needs to model costs (shipping, marketing, fixed vs variable).
- When the user asks about financing options or cash flow planning for a product.
- When the user wants to know if they should "kill" or "scale" a product.

## Workflow
1.  **classify_costs**: detailed breakdown of all cost layers (One-time, Variable, Fixed).
2.  **model_logistics**: Estimate landed costs including shipping and taxes.
3.  **calculate_marketing_metrics**: Model CAC, LTV, and sales needed to recover spend.
4.  **pricing_analysis**: Test pricing scenarios and margins (Gross, Contribution, Net).
5.  **cash_flow_check**: Identify timing gaps between spend and revenue.
6.  **recommend_financing**: Suggest funding based on risk/cost.
7.  **viability_decision**: Determine break-even and give a GO/NO-GO recommendation.
8.  **scaling_check**: If viable, determine safe scaling conditions.

## Instructions

### 1. Product Cost Structure
Classify EVERY cost item into one of three categories. Do not leave any unclassified.
- **One-time (Setup/Entry)**: Product purchase, molds, branding, legal, platform setup.
- **Variable (Per Unit)**: Production cost, shipping, packaging, payment fees, commissions, duties.
- **Fixed Recurring**: Subscriptions, hosting, salaries, storage, accounting.

### 2. Logistics & Shipping Modeling
Calculate the **Landed Cost per Unit** (True cost delivered to warehouse/customer).
- Include: Supplier -> Warehouse -> Customer.
- Factors: Weight, volume, zones, discounts, damage buffer (1-5%).

### 3. Marketing & Customer Acquisition
Model the "Cost to Sell".
- **CAC (Customer Acquisition Cost)**: Total Spend / New Customers.
- **Payback Period**: Days to recover CAC from gross profit.
- **LTV**: Lifetime Value of a customer.
- **Output**: How many units MUST be sold to recover the marketing budget?

### 4. Pricing & Margin Logic
Calculate margins at three levels:
1.  **Gross Margin**: Price - Cost of Goods Sold (COGS).
2.  **Contribution Margin**: Price - Variable Costs (COGS + Shipping + Ad Spend/unit).
3.  **Net Margin**: Price - All Costs (Variable + Fixed Share).

*Run Sensitivity Checks*:
- "What if shipping increases 10%?"
- "What if CAC doubles?"
- "What if market price drops 15%?"

### 5. Cash Flow & Runway
Identify **Liquidity Risk**.
- Map the TIMING of outflows (Inventory, Ads) vs Inflows (Customer payments).
- ALERT if there is a "Cash Gap" (e.g., paying supplier 60 days before selling stock).

### 6. Financing Options
Recommend the *least risky* method available.
- **Bootstrapped**: Reinvested profits (Safest).
- **Supplier Credit**: Net 30/60 terms (Good for cash flow).
- **Revenue-Based**: Flexible but can be expensive.
- **Loans/Equity**: Only for scaling, high risk/dilution.

### 7. Break-even & Viability Check (Kill Switch)
Calculate:
- **Break-even Units**: Fixed Costs / (Price - Variable Cost).
- **Break-even Time**: Months to profitability.

**DECISION LOGIC**:
- IF (Break-even > 6 months) OR (Net Margin < 10%) OR (Cash Gap > Runaway) -> **RECOMMEND KILL / PIVOT**.
- ELSE -> **PROCEED with Caution**.

### 8. Scaling Logic
Only triggering after viability is proven.
- **Safe to Scale When**:
    - CAC is stable/decreasing.
    - Supply chain can handle 3x volume.
    - Cash flow is positive or funding is secured.

## Resources
- Use `planning` skill to structure the output if a full report is requested.
