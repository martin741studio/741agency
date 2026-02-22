import json
import os
import sys
import asyncio
import csv
from playwright.async_api import async_playwright

async def generate_pdf(payload_path, output_path):
    with open(payload_path, 'r') as f:
        data = json.load(f)

    meta = data['invoice_metadata']
    client = data['client_details']
    items = data['items']
    tax_info = data['tax']

    # Calculations
    subtotal = sum(item['price'] * item['quantity'] for item in items)
    withholding = int(subtotal * tax_info['withholding_rate'])
    grand_total = subtotal - withholding

    # Format numbers
    def fmt(n): return f"{n:,}".replace(",", ".")

    html_content = f"""
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <style>
            @page {{ size: A4; margin: 0; }}
            body {{ 
                font-family: 'Helvetica Neue', 'Helvetica', 'Arial', sans-serif; 
                margin: 0; 
                padding: 50px 60px; 
                color: #1a1a1a; 
                line-height: 1.5;
                height: 297mm;
                box-sizing: border-box;
                display: flex;
                flex-direction: column;
                background-color: #fff;
            }}
            .top-msg {{ font-size: 15px; margin-bottom: 25px; color: #333; }}
            
            .header-info {{ display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 5px; }}
            .invoice-title {{ font-size: 68px; font-weight: bold; color: #222; margin: 0; letter-spacing: -2px; }}
            .invoice-num {{ font-size: 68px; font-weight: bold; color: #222; margin: 0; letter-spacing: -2px; }}
            
            .date-row {{ font-size: 17px; margin-bottom: 45px; font-weight: 500; color: #444; }}
            
            .main-content {{ display: flex; flex-grow: 1; border-top: 1.5px solid #000; }}
            
            .left-col {{ width: 35%; border-right: 1.5px solid #000; padding-top: 25px; }}
            .right-col {{ width: 65%; padding-top: 0; display: flex; flex-direction: column; }}
            
            .address-box {{ margin-bottom: 40px; font-size: 14px; padding-right: 25px; }}
            .address-box h3 {{ font-size: 15px; font-weight: bold; margin: 0 0 8px 0; text-transform: none; }}
            
            .table-row {{ display: flex; border-bottom: 1.5px solid #000; font-size: 14px; min-height: 35px; }}
            .table-head {{ font-weight: bold; border-bottom: 2px solid #000; color: #000; }}
            .table-row > div {{ padding: 12px 10px; box-sizing: border-box; }}
            .col-item {{ width: 55%; border-right: 1.5px solid #000; }}
            .col-qty {{ width: 15%; border-right: 1.5px solid #000; text-align: center; }}
            .col-cost {{ width: 30%; text-align: right; }}
            
            .summary-area {{ margin-left: auto; width: 60%; margin-top: auto; padding-bottom: 60px; }}
            .summary-line {{ display: flex; justify-content: space-between; font-size: 16px; padding: 10px 0; }}
            .summary-line.divider {{ border-bottom: 1.5px solid #000; }}
            .summary-line.total {{ font-weight: bold; font-size: 22px; margin-top: 15px; }}
            .tax-note {{ font-size: 10px; color: #555; font-style: italic; text-align: right; margin-top: 8px; line-height: 1.2; }}
            
            .footer {{ margin-top: auto; display: flex; align-items: flex-end; justify-content: space-between; padding-top: 40px; }}
            .footer-branding {{ display: flex; flex-direction: column; align-items: flex-start; }}
            .company-name-large {{ font-size: 64px; font-weight: bold; letter-spacing: -3px; color: #000; line-height: 0.8; margin-bottom: 10px; }}
            
            .footer-right {{ flex-grow: 1; display: flex; flex-direction: column; align-items: flex-end; padding-left: 40px; }}
            .footer-grid {{ display: grid; grid-template-columns: 1fr; grid-gap: 5px; font-size: 12px; margin-bottom: 20px; width: 300px; }}
            .f-cell {{ padding: 6px 12px; border: 1px solid #ddd; background: #fafafa; }}
            
            .nuance-container {{ display: flex; justify-content: flex-end; gap: 8px; width: 100%; }}
            .nuance-block {{ 
                height: 30px; 
                background: linear-gradient(135deg, #FFD700 0%, #F5C500 100%); 
                border-radius: 2px;
            }}
            .nb-1 {{ width: 60px; opacity: 0.4; }}
            .nb-2 {{ width: 110px; opacity: 0.7; }}
            .nb-3 {{ width: 150px; opacity: 1; background: linear-gradient(135deg, #FFD700 0%, #E6B800 100%); }}
        </style>
    </head>
    <body>
        <div class="top-msg">
            Thank you for your service booking at 741.Studio. <br>
            This is your service invoice. Please write us with any questions or tips.
        </div>
        
        <div class="header-info">
            <h1 class="invoice-title">Invoice</h1>
            <h1 class="invoice-num">{meta['invoice_number']}</h1>
        </div>
        <div class="date-row">Date: {meta['issue_date']}</div>
        
        <div class="main-content">
            <div class="left-col">
                <div class="address-box">
                    <h3>Bill to:</h3>
                    {client['name']}<br>
                    {client['address']}
                </div>
                
                <div class="address-box" style="margin-top: 100px; font-size: 12px; color: #666;">
                    <strong>PT Amana Events Indonesia</strong><br>
                    Villa No 4, Jl. Pantai Seseh, Br. Sedahan<br>
                    Munggu, Mengwi, Badung, Bali<br>
                    NPWP: 84.750.732.4-903.000
                </div>
            </div>
            
            <div class="right-col">
                <div class="table-row table-head">
                    <div class="col-item">Service:</div>
                    <div class="col-qty">Qty:</div>
                    <div class="col-cost">Rate:</div>
                </div>
                {"".join([f'<div class="table-row"><div class="col-item">{i["description"]}</div><div class="col-qty">{i["quantity"]}</div><div class="col-cost">{meta["currency"]}{fmt(i["price"])}</div></div>' for i in items])}
                
                <div style="flex-grow: 1;"></div>
                
                <div class="summary-area">
                    <div class="summary-line divider">
                        <span>Subtotal</span>
                        <span>{meta['currency']}{fmt(subtotal)}</span>
                    </div>
                    <div class="summary-line divider">
                        <span>Withholding Tax (PPh 23)</span>
                        <span>-{meta['currency']}{fmt(withholding)}</span>
                    </div>
                    <div class="tax-note">{tax_info['description']}</div>
                    <div class="summary-line total">
                        <span>Total</span>
                        <span>{meta['currency']}{fmt(grand_total)}</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="footer">
            <div class="footer-branding">
                <div class="company-name-large">741.Studio</div>
            </div>
            
            <div class="footer-right">
                <div class="footer-grid">
                    <div class="f-cell"><strong>WEBSITE:</strong> 741.studio</div>
                    <div class="f-cell"><strong>EMAIL:</strong> hello@741.studio</div>
                    <div class="f-cell"><strong>BANK:</strong> CIMB NIAGA (707920524600)</div>
                </div>
                <div class="nuance-container">
                    <div class="nuance-block nb-1"></div>
                    <div class="nuance-block nb-2"></div>
                    <div class="nuance-block nb-3"></div>
                </div>
            </div>
        </div>
    </body>
    </html>
    """

    async with async_playwright() as p:
        browser = await p.chromium.launch()
        page = await browser.new_page()
        await page.set_content(html_content)
        await page.pdf(path=output_path, format="A4", print_background=True)
        await browser.close()

    print(f"SUCCESS: v4 PDF generated at {output_path}")

def generate_csv(payload_path, output_path):
    with open(payload_path, 'r') as f:
        data = json.load(f)

    meta = data['invoice_metadata']
    client = data['client_details']
    items = data['items']
    tax_info = data['tax']

    # Calculations
    subtotal = sum(item['price'] * item['quantity'] for item in items)
    withholding = int(subtotal * tax_info['withholding_rate'])
    grand_total = subtotal - withholding

    rows = [
        ['Invoice Number', meta['invoice_number']],
        ['Date', meta['issue_date']],
        ['Due Date', meta['due_date']],
        ['', ''],
        ['Bill To', client['name']],
        ['Client Address', client['address']],
        ['', ''],
        ['Service', 'Quantity', 'Rate', 'Amount'],
    ]
    
    for i in items:
        rows.append([i['description'], i['quantity'], i['price'], i['price'] * i['quantity']])

    rows.extend([
        ['', '', '', ''],
        ['', '', 'Subtotal', subtotal],
        ['', '', 'Withholding Tax (PPh 23)', withholding],
        ['', '', 'Grand Total', grand_total]
    ])

    with open(output_path, 'w', newline='') as f:
        writer = csv.writer(f)
        writer.writerows(rows)
    
    print(f"SUCCESS: v4 CSV generated at {output_path}")

if __name__ == "__main__":
    if len(sys.argv) < 3:
        print("Usage: python invoice_gen.py <payload_path> <output_base_name>")
        sys.exit(1)
    
    payload = sys.argv[1]
    base_name = sys.argv[2]
    
    pdf_path = f"{base_name}.pdf"
    csv_path = f"{base_name}.csv"
    
    asyncio.run(generate_pdf(payload, pdf_path))
    generate_csv(payload, csv_path)
