# SOP: Invoice Generation (741.Studio)

## Goal
Generate a deterministic, professional invoice in Markdown.

## Inputs
- `invoice_payload` (JSON)

## Logic
1. Validate payload against `gemini.md` schema.
2. Apply Markdown template:
    - Header with 741 Agency branding cues.
    - Client Info (Reload Sanctuary).
    - Service Table (Description, Tier, Amount).
    - Total in IDR.
3. Save output to `clients/reload-sanctuary/invoices/`.

## Edge Cases
- Missing payload fields: Halt and ask user.
- Currency mismatch: Default to IDR for Bali clients.
