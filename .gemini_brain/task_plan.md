# Implementation Plan: Reload Sanctuary Invoice (v4 - Service Focused)

## Goal
Refine the invoice for a service-based business model, remove physical product references, update text branding to `741.Studio`, and provide both PDF and CSV deliverables.

## User Review Required
> [!IMPORTANT]
> - **Content:** Removed "Ship to" and "Items" terminology. Using Service-based labels.
> - **Branding:** Removed square logo. Changed naming to `741.Studio` (with dot).
> - **Footer:** Stripped down to Website, Email, and Bank.
> - **Deliverables:** Generating a PDF and a CSV (for Google Sheets upload).

## Proposed Changes

### [Component] Invoice Generator (v4)
- **[MODIFY] [invoice_gen.py](file:///Users/martindrendel/741agency/tools/invoice_gen.py)**:
    - Update HTML template to remove logo/ship-to.
    - Update footer contact info.
    - Add `generate_csv` function to save structured data to the Desktop.

## Verification Plan

### Automated Tests
- Run `./venv/bin/python3 tools/invoice_gen.py`.
- Verify BOTH `Reload_Sanctuary_Invoice_V4.pdf` and `Reload_Sanctuary_Invoice_V4.csv` exist on the Desktop.

### Manual Verification
- View PDF to confirm `741.Studio` branding and cleanup footer.
- Open CSV to ensure it contains all necessary data fields for Sheets.
