import typer
import os
from datetime import datetime
from dotenv import load_dotenv
from scraper import LinkedInSearcher, GoogleMapsScraper
from rich.console import Console
from rich.progress import Progress

app = typer.Typer()
console = Console()

@app.command()
def generate_leads(
    gm_limit: int = 20,
    output_dir: str = "leads"
):
    """
    Scrape Google Maps for Bali Hospitality leads and find LinkedIn contacts (CEO, Marketing Manager).
    """
    console.print(f"[bold green]Starting Daily Lead Generation Task...[/bold green]")

    # Load environment variables
    load_dotenv()
    
    # Create output directory
    os.makedirs(output_dir, exist_ok=True)
    today = datetime.now().strftime("%Y-%m-%d")
    filename = os.path.join(output_dir, f"{today}-outreach.md")
    
    # Initialize scrapers
    gm_scraper = GoogleMapsScraper()
    # li_searcher = LinkedInSearcher() # Disabled due to bot blocking
    
    with Progress() as progress:
        task1 = progress.add_task("[magenta]Scraping Google Maps...", total=gm_limit)
        
        console.print("Fetching Google Maps leads...")
        gm_leads = gm_scraper.get_leads(query="Restaurants in Seminyak, Bali", limit=gm_limit)
        progress.update(task1, completed=gm_limit)

    # Generate Markdown
    content = f"# Daily Outreach: {today}\n\n"
    content += "## 📍 Leads & Contacts\n"
    
    if gm_leads:
        for lead in gm_leads:
            # Generate manual search link
            query = f'site:linkedin.com/in "{lead["name"]}" Bali ("CEO" OR "Marketing Manager" OR "Brand Manager")'
            search_url = f"https://www.google.com/search?q={query.replace(' ', '+')}"
            
            content += f"- [ ] **{lead['name']}**\n"
            content += f"    - 📍 [Maps Link]({lead['url']})\n"
            content += f"    - 👔 [Find Contacts on LinkedIn]({search_url})\n"
            
            if lead.get('contacts'):
                for contact in lead['contacts']:
                     content += f"    - 👤 [{contact['name_and_role']}]({contact['url']})\n"
    else:
        content += "- No leads found.\n"
        
    # Write to file
    with open(filename, "w") as f:
        f.write(content)
        
    console.print(f"\n[bold green]Success![/bold green] Checklist generated at: [underline]{filename}[/underline]")

if __name__ == "__main__":
    app()
