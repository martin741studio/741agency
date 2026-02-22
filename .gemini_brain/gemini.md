# Project Constitution: Reload Sanctuary Invoice

## Data Schemas

### PT Amana Events Indonesia (Official Entity)
- **Name:** (741Studio) PT Amana Events Indonesia
- **Address:** VILLA NO 4, JL. PANTAI SESEH, BR. SEDAHAN, MUNGGU, MENGWI, BADUNG, BALI
- **NPWP:** 84.750.732.4-903.000
- **Bank:** CIMB NIAGA
- **Account Number:** 707920524600
- **Address (Bank):** Jalan Drupadi V No 8, Denpasar Bali 80235

### Invoice Payload
```json
{
  "invoice_metadata": {
    "invoice_number": "2026-RS-01",
    "issue_date": "2026-02-22",
    "due_date": "2026-03-01",
    "currency": "Rp"
  },
  "client_details": {
    "name": "Reload Sanctuary",
    "address": "Jalan Canggu Padang Linjong No.8, Canggu, Bali, 80351"
  },
  "items": [
    {
      "item": "Monthly",
      "description": "Paid Advertising Service (Meta & Google Ads) - Management and Optimization",
      "quantity": 1,
      "price": 20000000
    }
  ],
  "tax": {
    "withholding_rate": 0.02,
    "description": "PPh 23 (2%) is withheld and paid by the Client to the Indonesian Tax Authority."
  }
}
```

## Behavioral Rules
- **Formatting:** Use HTML/CSS to replicate the "Yellow Bar" aesthetic exactly.
- **Logo:** Use the 741 Studio logo.
- **Payload:** Separate Subtotal, Tax, and Grand Total.
- **Language:** English/Indonesian (Mixed as per image).
- **Delivery:** Save as PDF to `/Users/martindrendel/Desktop/Reload_Sanctuary_Invoice.pdf`.

## Architectural Invariants
- 3-Layer Architecture (Architecture, Navigation, Tools).
